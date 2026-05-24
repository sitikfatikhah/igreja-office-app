import * as faceapi from 'face-api.js';

async function loadModels() {
    console.debug('[face-enroll] loadModels: start');
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    console.debug('[face-enroll] loadModels: tinyFaceDetector loaded');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    console.debug('[face-enroll] loadModels: faceLandmark68Net loaded');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
    console.debug('[face-enroll] loadModels: faceRecognitionNet loaded');
}

let liveStream = null;

async function startVideo(video) {
    console.debug('[face-enroll] startVideo: requesting camera access');
    liveStream = await navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: 'user',
        },
        audio: false,
    });

    console.debug('[face-enroll] startVideo: camera access granted');
    video.srcObject = liveStream;

    await new Promise((resolve, reject) => {
        video.onloadedmetadata = () => {
            console.debug('[face-enroll] startVideo: onloadedmetadata');
            video.play().then(() => {
                console.debug('[face-enroll] startVideo: video.play resolved');
                resolve();
            }).catch((err) => {
                console.error('[face-enroll] startVideo: video.play rejected', err);
                reject(err);
            });
        };
        video.onerror = (err) => {
            console.error('[face-enroll] startVideo: video.onerror', err);
            reject(err);
        };
    });
    console.debug('[face-enroll] startVideo: video started');
}

function stopVideo(video) {
    if (video.srcObject) {
        const tracks = video.srcObject.getTracks();
        tracks.forEach((track) => track.stop());
        video.srcObject = null;
    }
    liveStream = null;
}

function resolveLivewireComponent(element) {
    if (!window.Livewire || !element) {
        console.error('[face-enroll] Livewire atau element tidak tersedia.');
        return null;
    }

    // Cari parent element yang memiliki atribut wire:id
    const root =
        element.closest('[wire\\:id]') ||
        document.querySelector('[wire\\:id]');

    if (!root) {
        console.error('[face-enroll] Root Livewire component tidak ditemukan.');
        return null;
    }

    const componentId = root.getAttribute('wire:id');

    if (!componentId) {
        console.error('[face-enroll] wire:id tidak ditemukan.');
        return null;
    }

    if (typeof window.Livewire.find !== 'function') {
        console.error('[face-enroll] Livewire.find() tidak tersedia.');
        return null;
    }

    const component = window.Livewire.find(componentId);

    if (!component) {
        console.error('[face-enroll] Component Livewire tidak ditemukan.');
        return null;
    }

    console.debug('[face-enroll] Livewire component ditemukan:', componentId);

    return component;
}


    
async function init() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const descriptorInput = document.getElementById('descriptorInput');
    const referencePhotoInput = document.getElementById('referencePhotoInput');
    const status = document.getElementById('status');

    let lastDescriptor = null;
    let lastReferencePhoto = null;


    if (!video || !canvas || !captureBtn || !descriptorInput || !referencePhotoInput || !status) {
        console.warn('Face enroll elements atau input Livewire tidak ditemukan. Pastikan view menampilkan semua elemen.');
        return;
    }

    captureBtn.disabled = true;
    status.innerText = 'Memuat model face recognition...';

    try {
        await loadModels();
        status.innerText = 'Memulai kamera...';
        await startVideo(video);
        status.innerText = 'Kamera aktif. Posisikan wajah Anda di depan kamera lalu klik Capture Face.';
        captureBtn.disabled = false;
    } catch (error) {
        console.error('Gagal memulai face enrollment:', error);
        status.innerText = 'Tidak dapat memulai kamera atau model. Cek console untuk detail.';
        return;
    }

    const saveBtn = document.getElementById('saveBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const videoContainer = document.getElementById('videoContainer');
    const photoContainer = document.getElementById('photoContainer');
    const capturedPhoto = document.getElementById('capturedPhoto');


    if (!saveBtn || !retakeBtn || !videoContainer || !photoContainer || !capturedPhoto) {
        console.warn('Save/retake controls atau container foto tidak ditemukan.');
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
        status.innerText = 'Kamera siap. Klik Capture Face untuk memfoto wajah.';
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
        status.innerText = 'Foto wajah siap disimpan atau ambil ulang.';
    }

    captureBtn.addEventListener('click', async () => {
        status.innerText = 'Memindai wajah...';

        const detectionOptions = new faceapi.TinyFaceDetectorOptions({
            inputSize: 608,
            scoreThreshold: 0.3
        });

        const detections = await faceapi
            .detectSingleFace(video, detectionOptions)
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detections) {
            status.innerText = 'No face detected. Please try lagi.';
            return;
        }

        const descriptor = Array.from(detections.descriptor);
        lastDescriptor = JSON.stringify(descriptor);

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const referencePhoto = canvas.toDataURL('image/png');
        lastReferencePhoto = referencePhoto;

        descriptorInput.value = lastDescriptor;
        referencePhotoInput.value = lastReferencePhoto;
        descriptorInput.dispatchEvent(new Event('input', { bubbles: true }));
        descriptorInput.dispatchEvent(new Event('change', { bubbles: true }));
        referencePhotoInput.dispatchEvent(new Event('input', { bubbles: true }));
        referencePhotoInput.dispatchEvent(new Event('change', { bubbles: true }));

        showReviewMode(referencePhoto);
        stopVideo(video);

        console.debug('[face-enroll] capture success');
    });

    saveBtn.addEventListener('click', async () => {
        try {
            status.innerText = 'Menyimpan data enroll...';
            saveBtn.disabled = true;

            if (!lastDescriptor || !lastReferencePhoto) {
                status.innerText = 'Data capture belum lengkap. Silakan capture ulang.';
                saveBtn.disabled = false;
                return;
            }

            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenElement ? tokenElement.content : null;

            const response = await fetch('/admin/enroll-face/save-face', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    descriptor: lastDescriptor,
                    reference_photo: lastReferencePhoto,
                }),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menyimpan data enroll.');
            }

            status.innerText = result.message || 'Face enrollment berhasil disimpan.';

        } catch (error) {
            console.error(error);
            status.innerText = 'Gagal menyimpan data enroll.';
        } finally {
            saveBtn.disabled = false;
        }
    });
    retakeBtn.addEventListener('click', async () => {
        showCaptureMode();
        try {
            await startVideo(video);
            status.innerText = 'Ambil ulang: kamera aktif kembali. Klik Capture Face.';
        } catch (err) {
            console.error('[face-enroll] retake click: gagal memulai ulang kamera', err);
            status.innerText = 'Gagal memulai ulang kamera. Cek console.';
        }
    });

    // showCaptureMode();
}


document.addEventListener('DOMContentLoaded', init);