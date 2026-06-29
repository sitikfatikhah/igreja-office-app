import * as faceapi from 'face-api.js';

/**
 * face-enrollment.js
 *
 * Tanggung jawab file ini HANYA untuk pendaftaran (enrollment) wajah:
 *   - Capture Face
 *   - Save Enrollment
 *   - Retake
 *
 * Tidak ada logika GPS atau absensi di file ini.
 * Untuk proses absensi, lihat face-attendance.js
 *
 * Pengiriman data ke server memakai $wire.saveFace(...) — bukan
 * fetch() ke endpoint API terpisah dengan CSRF token manual seperti
 * sebelumnya. Ini mengikuti pola yang sama dengan face-attendance.js:
 * data dikirim sebagai ARGUMEN method call Livewire, sehingga selalu
 * sampai utuh dalam satu request yang atomic.
 */

/**
 * Mendapatkan objek $wire komponen Livewire yang BENAR (yang punya method
 * saveFace), bukan sekadar komponen Livewire terdekat apa pun.
 *
 * Alpine.$data(root) pada elemen dengan x-data kosong akan inherit $wire
 * dari komponen Livewire ancestor terdekat — ini seharusnya benar, TAPI
 * jika halaman punya struktur Livewire bersarang (nested component, modal,
 * atau Filament merender ulang sebagian tree), $wire yang didapat bisa
 * merujuk komponen yang salah/stale. Untuk itu kita verifikasi keberadaan
 * method saveFace sebelum memakai $wire tersebut, dan jika tidak
 * cocok, jelajahi semua komponen Livewire di halaman untuk mencari yang
 * benar.
 */
function getWire() {
    const root = document.getElementById('face-enroll-root');
    if (!root) return null;

    // 1) Coba Alpine.$data dulu — cara tercepat & biasanya benar.
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

    // 2) Fallback: cari [wire:id] ancestor terdekat secara manual.
    if (window.Livewire && typeof window.Livewire.find === 'function') {
        const wireRoot = root.closest('[wire\\:id]');
        if (wireRoot) {
            const component = window.Livewire.find(wireRoot.getAttribute('wire:id'));
            if (component) return component;
        }
    }

    return null;
}

/**
 * Jika $wire yang ditemukan ternyata tidak punya method saveFace
 * (misalnya karena merujuk komponen Livewire lain di halaman), jelajahi
 * SEMUA komponen Livewire yang aktif untuk mencari yang benar-benar punya
 * method ini.
 */
function findWireWithSaveFace() {
    if (!window.Livewire || typeof window.Livewire.all !== 'function') {
        return null;
    }

    try {
        const components = window.Livewire.all();
        for (const component of components) {
            if (typeof component.saveFace === 'function') {
                return component;
            }
        }
    } catch (error) {
        console.warn('[face-enrollment] Gagal menjelajahi komponen Livewire', error);
    }

    return null;
}

async function waitForWire(timeoutMs = 10000) {
    const start = Date.now();

    while (Date.now() - start < timeoutMs) {
        let wire = getWire();

        // Verifikasi: pastikan komponen yang ditemukan benar-benar punya
        // method saveFace. Jika tidak (merujuk komponen yang
        // salah), coba cari lewat semua komponen aktif di halaman.
        if (wire && typeof wire.saveFace !== 'function') {
            const correctWire = findWireWithSaveFace();
            if (correctWire) {
                wire = correctWire;
            }
        }

        if (wire && typeof wire.saveFace === 'function') {
            return wire;
        }

        await new Promise((resolve) => setTimeout(resolve, 200));
    }

    return null;
}

async function loadModels() {
    console.debug('[face-enrollment] loadModels: start');
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    console.debug('[face-enrollment] loadModels: tinyFaceDetector loaded');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    console.debug('[face-enrollment] loadModels: faceLandmark68Net loaded');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
    console.debug('[face-enrollment] loadModels: faceRecognitionNet loaded');
}

let liveStream = null;

async function startVideo(video) {
    console.debug('[face-enrollment] startVideo: requesting camera access');
    liveStream = await navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: 'user',
        },
        audio: false,
    });

    console.debug('[face-enrollment] startVideo: camera access granted');
    video.srcObject = liveStream;

    await new Promise((resolve, reject) => {
        video.onloadedmetadata = () => {
            console.debug('[face-enrollment] startVideo: onloadedmetadata');
            video.play().then(() => {
                console.debug('[face-enrollment] startVideo: video.play resolved');
                resolve();
            }).catch((err) => {
                console.error('[face-enrollment] startVideo: video.play rejected', err);
                reject(err);
            });
        };
        video.onerror = (err) => {
            console.error('[face-enrollment] startVideo: video.onerror', err);
            reject(err);
        };
    });
    console.debug('[face-enrollment] startVideo: video started');
}

function stopVideo(video) {
    if (video.srcObject) {
        const tracks = video.srcObject.getTracks();
        tracks.forEach((track) => track.stop());
        video.srcObject = null;
    }
    liveStream = null;
}

async function init() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const descriptorInput = document.getElementById('descriptorInput');
    const referencePhotoInput = document.getElementById('referencePhotoInput');
    const status = document.getElementById('status');
    const statusText = document.getElementById('statusText');
    const videoPlaceholder = document.getElementById('videoPlaceholder');

    let lastDescriptor = null;
    let lastReferencePhoto = null;

    if (!video || !canvas || !captureBtn || !descriptorInput || !referencePhotoInput || !status) {
        console.warn('[face-enrollment] Elemen atau input Livewire tidak ditemukan. Pastikan view menampilkan semua elemen.');
        return;
    }

    /**
     * Update teks status DAN warna badge-nya sekaligus.
     * state: 'idle' (netral/abu-abu), 'progress' (biru), 'success' (hijau),
     * 'error' (merah).
     */
    function setStatus(msg, state = 'idle') {
        if (statusText) {
            statusText.innerText = msg;
        } else {
            status.innerText = msg;
        }
        status.dataset.state = state;
        console.log('[face-enrollment]', msg);
    }

    // Pastikan video tampil mirror (seperti cermin) terlepas dari CSS apa pun
    // yang mungkin gagal ter-compile atau tertimpa style lain di project.
    video.style.transform = 'scaleX(-1)';

    captureBtn.disabled = true;
    setStatus('Memuat model face recognition...', 'progress');

    try {
        await loadModels();
        setStatus('Memulai kamera...', 'progress');
        await startVideo(video);
        if (videoPlaceholder) videoPlaceholder.style.display = 'none';
        setStatus('Kamera aktif. Posisikan wajah Anda lalu klik capture face.', 'idle');
        captureBtn.disabled = false;
        captureBtn.removeAttribute('disabled');
    } catch (error) {
        console.error('[face-enrollment] Gagal memulai face enrollment:', error);
        setStatus('Tidak dapat memulai kamera atau model. Cek console untuk detail.', 'error');
        return;
    }

    const saveBtn = document.getElementById('saveBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const videoContainer = document.getElementById('videoContainer');
    const photoContainer = document.getElementById('photoContainer');
    const capturedPhoto = document.getElementById('capturedPhoto');

    if (!saveBtn || !retakeBtn || !videoContainer || !photoContainer || !capturedPhoto) {
        console.warn('[face-enrollment] Save/retake controls atau container foto tidak ditemukan.');
        return;
    }

    function showCaptureMode() {
        captureBtn.disabled = false;
        saveBtn.hidden = true;
        retakeBtn.hidden = true;
        videoContainer.style.display = 'block';
        photoContainer.style.display = 'none';
        canvas.style.display = 'none';
        canvas.classList.add('hidden');
        capturedPhoto.src = '';
        setStatus('Kamera siap. Klik capture face untuk memfoto wajah.', 'idle');
    }

    function showReviewMode(previewSrc) {
        captureBtn.disabled = true;
        saveBtn.hidden = false;
        retakeBtn.hidden = false;
        videoContainer.style.display = 'none';
        photoContainer.style.display = 'block';
        canvas.style.display = 'none';
        canvas.classList.add('hidden');
        capturedPhoto.src = previewSrc;
        setStatus('Foto wajah siap disimpan atau ambil ulang.', 'idle');
    }

    captureBtn.addEventListener('click', async () => {
        setStatus('Memindai wajah...', 'progress');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');

        // Gambar frame video ke canvas dalam keadaan SUDAH di-mirror
        // (flip horizontal permanen), supaya foto yang tersimpan dan
        // descriptor yang dihitung konsisten dengan apa yang user lihat
        // di live preview (selfie-style), bukan orientasi mentah kamera.
        context.save();
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.restore();

        const detectionOptions = new faceapi.TinyFaceDetectorOptions({
            inputSize: 608,
            scoreThreshold: 0.3,
        });

        // Deteksi wajah dari CANVAS yang sudah di-flip (bukan dari video
        // mentah), supaya descriptor yang dihasilkan konsisten dengan
        // foto yang disimpan dan dengan descriptor yang akan dihitung
        // saat proses attendance nanti (yang juga harus memakai orientasi
        // yang sama untuk perbandingan euclidean distance yang akurat).
        const detections = await faceapi
            .detectSingleFace(canvas, detectionOptions)
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detections) {
            setStatus('Wajah tidak terdeteksi. Silakan coba lagi.', 'error');
            return;
        }

        const descriptor = Array.from(detections.descriptor);
        lastDescriptor = JSON.stringify(descriptor);

        const referencePhoto = canvas.toDataURL('image/png');
        lastReferencePhoto = referencePhoto;

        descriptorInput.value = lastDescriptor;
        referencePhotoInput.value = lastReferencePhoto;

        showReviewMode(referencePhoto);
        stopVideo(video);

        console.debug('[face-enrollment] capture success');
    });

    saveBtn.addEventListener('click', async () => {
        try {
            setStatus('Menyimpan data enroll...', 'progress');
            saveBtn.disabled = true;

            if (!lastDescriptor || !lastReferencePhoto) {
                setStatus('Data capture belum lengkap. Silakan capture ulang.', 'error');
                saveBtn.disabled = false;
                return;
            }

            const wire = await waitForWire();

            if (!wire) {
                throw new Error('Komponen form tidak ditemukan. Silakan reload halaman.');
            }

            await wire.saveFace(lastDescriptor, lastReferencePhoto);

            setStatus('Face enrollment berhasil disimpan.', 'success');
        } catch (error) {
            console.error('[face-enrollment]', error);
            setStatus('Gagal menyimpan data enroll.', 'error');
        } finally {
            saveBtn.disabled = false;
        }
    });

    retakeBtn.addEventListener('click', async () => {
        showCaptureMode();
        try {
            await startVideo(video);
            setStatus('Ambil ulang: kamera aktif kembali. Klik capture face.', 'idle');
        } catch (err) {
            console.error('[face-enrollment] retake click: gagal memulai ulang kamera', err);
            setStatus('Gagal memulai ulang kamera. Cek console.', 'error');
        }
    });
}

/**
 * Menunggu sampai elemen #video dan #captureBtn benar-benar ada di DOM.
 * Filament/Livewire bisa merender komponen ini secara async, setelah
 * DOMContentLoaded/livewire:load sudah lewat, sehingga listener event
 * saja tidak cukup untuk menjamin init berjalan.
 */
function waitForEnrollmentElements(timeoutMs = 15000) {
    return new Promise((resolve, reject) => {
        if (document.getElementById('video') && document.getElementById('captureBtn')) {
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

async function tryInitFaceEnrollment() {
    try {
        await waitForEnrollmentElements();
    } catch (err) {
        console.warn('[face-enrollment]', err.message);
        return;
    }

    const captureBtn = document.getElementById('captureBtn');

    // Tandai status init pada ELEMEN itu sendiri, bukan flag global di window.
    // Filament/Livewire SPA (wire:navigate) bisa mengganti seluruh elemen
    // dengan yang baru saat navigasi antar halaman; flag global lama akan
    // mencegah init berjalan lagi untuk elemen baru tersebut, menyebabkan
    // tombol macet permanen dalam keadaan disabled bawaan HTML.
    if (captureBtn.dataset.faceEnrollmentInitialized === 'true') {
        return;
    }
    captureBtn.dataset.faceEnrollmentInitialized = 'true';

    try {
        await init();
        console.debug('[face-enrollment] initialized');
    } catch (err) {
        captureBtn.dataset.faceEnrollmentInitialized = 'false';
        console.error('[face-enrollment] initialization failed', err);
    }
}

document.addEventListener('DOMContentLoaded', tryInitFaceEnrollment);
document.addEventListener('livewire:load', tryInitFaceEnrollment);
document.addEventListener('livewire:update', tryInitFaceEnrollment);
document.addEventListener('livewire:navigated', tryInitFaceEnrollment);
if (window.Livewire && typeof window.Livewire.hook === 'function') {
    window.Livewire.hook('message.processed', tryInitFaceEnrollment);
}

// Panggil langsung juga: script Vite biasanya dimuat dengan defer/type=module,
// sehingga DOMContentLoaded mungkin sudah lewat sebelum listener di atas terpasang.
tryInitFaceEnrollment();