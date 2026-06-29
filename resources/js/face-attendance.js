import * as faceapi from 'face-api.js';

/**
 * face-attendance.js
 *
 * Tanggung jawab file ini HANYA untuk proses absensi:
 *   1. Aktifkan kamera & load model face-api.js
 *   2. Ambil 1x lokasi GPS (tidak ada polling/interval)
 *   3. Deteksi wajah live, bandingkan dengan descriptor hasil enrollment
 *   4. Kirim semua data (GPS + hasil verifikasi wajah) ke Livewire
 *      SECARA BERURUTAN & DI-AWAIT, baru kemudian submit form
 *
 * Tidak ada logika enrollment (capture/save/retake) di file ini.
 * Untuk enrollment, lihat face-enrollment.js
 */

const FACE_MATCH_THRESHOLD = 0.5; // jarak euclidean; makin kecil makin mirip (face-api.js default ~0.6)

/**
 * Menunggu sampai elemen #video dan #captureBtn benar-benar ada di DOM.
 * Tidak bergantung pada urutan/timing event Livewire (DOMContentLoaded,
 * livewire:init, dsb) karena komponen Filament/Livewire bisa merender
 * elemen ini secara async, setelah event-event tersebut sudah lewat.
 */
function waitForAttendanceElements(timeoutMs = 15000) {
    return new Promise((resolve, reject) => {
        const existing = document.getElementById('video');
        if (existing && document.getElementById('captureBtn')) {
            resolve();
            return;
        }

        const observer = new MutationObserver(() => {
            if (document.getElementById('video') && document.getElementById('captureBtn')) {
                observer.disconnect();
                clearTimeout(timeoutHandle);
                resolve();
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });

        const timeoutHandle = setTimeout(() => {
            observer.disconnect();
            reject(new Error('Elemen video/captureBtn tidak ditemukan setelah menunggu'));
        }, timeoutMs);
    });
}

window.initFaceAttendance = async function () {
    try {
        await waitForAttendanceElements();
    } catch (error) {
        console.warn('[face-attendance]', error.message);
        return;
    }

    const video = document.getElementById('video');
    const captureBtn = document.getElementById('captureBtn');
    const status = document.getElementById('status');
    const statusText = document.getElementById('statusText');
    const videoPlaceholder = document.getElementById('videoPlaceholder');

    // Tandai status init pada elemen video itu sendiri, bukan flag global
    // di window. Filament/Livewire SPA (wire:navigate) bisa mengganti
    // seluruh elemen dengan yang baru saat navigasi antar halaman; flag
    // global lama akan mencegah init berjalan lagi untuk elemen baru
    // tersebut, menyebabkan kamera/tombol macet permanen.
    if (video.dataset.faceAttendanceInitialized === 'true') {
        return;
    }
    video.dataset.faceAttendanceInitialized = 'true';

    let modelsLoaded = false;
    let savedDescriptor = null;

    // Pastikan video tampil mirror (seperti cermin) terlepas dari CSS apa pun
    // yang mungkin gagal ter-compile atau tertimpa style lain di project.
    if (video) {
        video.style.transform = 'scaleX(-1)';
    }

    /**
     * Update teks status DAN warna badge-nya sekaligus.
     * state: 'idle' (netral/abu-abu), 'progress' (biru), 'success' (hijau),
     * 'error' (merah). Ini mengubah tampilan visual status supaya user
     * langsung bisa membedakan tahap proses tanpa harus membaca teksnya.
     */
    function setStatus(msg, state = 'idle') {
        if (statusText) {
            statusText.innerText = msg;
        } else if (status) {
            status.innerText = msg;
        }
        if (status) status.dataset.state = state;
        console.log('[face-attendance]', msg);
    }

    function parseSavedDescriptor() {
        const raw = window.faceAttendance?.savedDescriptor;

        if (!raw) {
            return null;
        }

        try {
            const arr = typeof raw === 'string' ? JSON.parse(raw) : raw;
            return Array.isArray(arr) ? Float32Array.from(arr) : null;
        } catch (error) {
            console.error('[face-attendance] Gagal parse savedDescriptor', error);
            return null;
        }
    }

    async function loadModels() {
        if (modelsLoaded) return;
        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
        modelsLoaded = true;
    }

    async function startVideo(videoElement) {
        if (!videoElement || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Kamera tidak tersedia di browser ini');
        }

        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user' },
            audio: false,
        });

        videoElement.srcObject = stream;

        await new Promise((resolve, reject) => {
            videoElement.onloadedmetadata = () => {
                videoElement.play().then(resolve).catch(reject);
            };
            videoElement.onerror = reject;
        });
    }

    function stopVideo(videoElement) {
        if (videoElement?.srcObject) {
            videoElement.srcObject.getTracks().forEach((track) => track.stop());
            videoElement.srcObject = null;
        }
    }

    async function reverseGeocode(latitude, longitude) {
        try {
            const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(latitude)}&lon=${encodeURIComponent(longitude)}`;
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Reverse geocoding gagal');
            }

            const data = await response.json();
            return data.display_name || 'Browser GPS';
        } catch (error) {
            console.warn('[face-attendance] reverse geocode failed', error);
            return 'Browser GPS';
        }
    }

    async function getGps() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                return reject(new Error('Geolocation tidak didukung browser ini'));
            }

            navigator.geolocation.getCurrentPosition(
                async (pos) => {
                    const locationName = await reverseGeocode(pos.coords.latitude, pos.coords.longitude);
                    resolve({
                        latitude: pos.coords.latitude,
                        longitude: pos.coords.longitude,
                        location_name: locationName,
                    });
                },
                (err) => reject(err),
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        });
    }

    /**
     * Mendapatkan objek $wire dari elemen Alpine terdekat (#face-attendance-root).
     * $wire adalah API resmi & didukung Livewire untuk berinteraksi dengan
     * komponen dari JavaScript — jauh lebih stabil dibanding memakai
     * Livewire.find(componentId) secara manual, yang rentan terhadap
     * referensi basi (stale) setelah Livewire/Alpine re-render komponen.
     */
    function getWire() {
        const root = document.getElementById('face-attendance-root');
        if (!root) return null;

        // Alpine menyimpan $wire di scope data elemen yang punya x-data.
        if (window.Alpine && typeof window.Alpine.$data === 'function') {
            try {
                const data = window.Alpine.$data(root);
                if (data && data.$wire) {
                    return data.$wire;
                }
            } catch {
                // lanjut ke fallback di bawah
            }
        }

        return null;
    }

    async function waitForWire(timeoutMs = 10000) {
        const start = Date.now();

        while (Date.now() - start < timeoutMs) {
            const wire = getWire();
            if (wire) return wire;
            await new Promise((resolve) => setTimeout(resolve, 200));
        }

        return null;
    }

    /**
     * Deteksi wajah dari video dan bandingkan dengan descriptor tersimpan.
     *
     * PENTING: descriptor enrollment (savedDescriptor) sekarang dihitung
     * dari frame yang SUDAH di-flip horizontal secara permanen (lihat
     * face-enrollment.js — foto referensi disimpan dalam orientasi
     * selfie-mirror, bukan orientasi mentah kamera). Supaya perbandingan
     * euclidean distance akurat, descriptor attendance di sini HARUS
     * dihitung dari frame yang di-flip dengan cara yang sama, bukan
     * langsung dari elemen <video> mentah.
     *
     * Mengembalikan { verified, score } — score = euclidean distance
     * (semakin kecil semakin mirip).
     */
    async function matchFace(videoElement) {
        if (!savedDescriptor) {
            throw new Error('Data wajah belum terdaftar. Silakan enroll wajah Anda terlebih dahulu.');
        }

        const flipCanvas = document.createElement('canvas');
        flipCanvas.width = videoElement.videoWidth;
        flipCanvas.height = videoElement.videoHeight;

        const flipContext = flipCanvas.getContext('2d');
        flipContext.save();
        flipContext.translate(flipCanvas.width, 0);
        flipContext.scale(-1, 1);
        flipContext.drawImage(videoElement, 0, 0, flipCanvas.width, flipCanvas.height);
        flipContext.restore();

        const detectionOptions = new faceapi.TinyFaceDetectorOptions({
            inputSize: 608,
            scoreThreshold: 0.3,
        });

        const detection = await faceapi
            .detectSingleFace(flipCanvas, detectionOptions)
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detection) {
            throw new Error('Wajah tidak terdeteksi. Posisikan wajah Anda dengan jelas di depan kamera.');
        }

        const distance = faceapi.euclideanDistance(detection.descriptor, savedDescriptor);
        const verified = distance <= FACE_MATCH_THRESHOLD;

        return { verified, score: distance };
    }

    /**
     * Mengirim semua data (GPS + hasil verifikasi wajah) ke server dalam
     * SATU panggilan method Livewire (submitAttendance), bukan dengan
     * men-set banyak property satu-satu lalu submit form secara terpisah.
     *
     * Karena data dikirim sebagai ARGUMEN method call, ia dijamin sampai
     * utuh dan atomic dalam satu request — tidak ada lagi kemungkinan
     * sebagian field belum tersimpan saat create() dijalankan di server,
     * yang sebelumnya menyebabkan error "GPS belum ditemukan" meski JS
     * sudah melaporkan sukses men-set semua field.
     */
    async function submitAttendance({ gps, faceResult }) {
        const wire = await waitForWire();

        if (!wire) {
            throw new Error('Komponen form tidak ditemukan. Silakan reload halaman.');
        }

        await wire.submitAttendance(
            Number(gps.latitude),
            Number(gps.longitude),
            gps.location_name ?? 'Browser GPS',
            Boolean(faceResult.verified),
            Number(faceResult.score.toFixed(4))
        );
    }

    async function bootCamera() {
        try {
            setStatus('Mengaktifkan kamera...', 'progress');
            await startVideo(video);
            if (videoPlaceholder) videoPlaceholder.style.display = 'none';
            setStatus('Kamera aktif. Memuat model pengenalan wajah...', 'progress');
            await loadModels();
            setStatus('Siap. Klik tombol di bawah untuk verifikasi & absen.', 'idle');
            captureBtn && (captureBtn.disabled = false);
        } catch (error) {
            const message = error?.message ?? String(error);
            setStatus('Gagal mengaktifkan kamera/model: ' + message, 'error');
            console.error('[face-attendance]', error);
        }
    }

    async function runAttendanceFlow() {
        try {
            captureBtn && (captureBtn.disabled = true);

            if (!modelsLoaded) {
                setStatus('Memuat model pengenalan wajah...', 'progress');
                await loadModels();
            }

            if (!video.srcObject) {
                setStatus('Mengaktifkan kamera...', 'progress');
                await startVideo(video);
                if (videoPlaceholder) videoPlaceholder.style.display = 'none';
            }

            savedDescriptor = parseSavedDescriptor();
            if (!savedDescriptor) {
                setStatus('Data wajah belum ditemukan. Silakan enroll wajah Anda terlebih dahulu.', 'error');
                return;
            }

            setStatus('Mengambil lokasi GPS...', 'progress');
            const gps = await getGps();
            setStatus(`Lokasi diperoleh: ${gps.location_name}`, 'progress');

            setStatus('Memverifikasi wajah...', 'progress');
            const faceResult = await matchFace(video);

            if (!faceResult.verified) {
                setStatus('Wajah tidak cocok dengan data terdaftar. Silakan coba lagi.', 'error');
                return;
            }

            setStatus('Wajah terverifikasi. Menyimpan absensi...', 'progress');
            await submitAttendance({ gps, faceResult });

            stopVideo(video);
            setStatus('Absensi berhasil dikirim.', 'success');
        } catch (error) {
            const message = error?.message ?? String(error);
            setStatus('Gagal melakukan absensi: ' + message, 'error');
            console.error('[face-attendance]', error);
        } finally {
            captureBtn && (captureBtn.disabled = false);
        }
    }

    if (captureBtn) {
        captureBtn.disabled = true;
        captureBtn.addEventListener('click', runAttendanceFlow);
    }

    await bootCamera();
};

document.addEventListener('livewire:init', () => {
    window.initFaceAttendance();
});

document.addEventListener('livewire:navigated', () => {
    window.initFaceAttendance();
});

if (document.readyState !== 'loading') {
    window.initFaceAttendance();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        window.initFaceAttendance();
    });
}