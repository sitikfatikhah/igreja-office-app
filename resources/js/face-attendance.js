import * as faceapi from 'face-api.js';

window.initFaceAttendance = async function () {
    if (window.faceAttendanceInitialized) {
        return;
    }

    window.faceAttendanceInitialized = true;

    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const status = document.getElementById('status');

    const captureBtn = document.getElementById('captureBtn');
    const saveBtn = document.getElementById('saveBtn');
    const retakeBtn = document.getElementById('retakeBtn');

    const descriptorInput = document.getElementById('descriptorInput');
    const referencePhotoInput = document.getElementById('referencePhotoInput');

    // Pastikan elemen tersedia
    if (
        !video ||
        !canvas ||
        !status ||
        !captureBtn ||
        !saveBtn ||
        !retakeBtn
    ) {
        console.warn('Element video/canvas/status/button tidak ditemukan.');
        return;
    }

    // Cek apakah user sudah memiliki face descriptor
    const hasFace = status.dataset.hasFace === 'true';

    // Reset warna status
    status.classList.remove('text-green-600', 'text-red-600');

    if (hasFace) {
        status.innerText =
            'Face data found. You can capture a new face or save to update.';
        status.classList.add('text-green-600');

        // Pastikan video tampil
        video.style.display = 'block';
        canvas.style.display = 'none';

        captureBtn.hidden = false;
    } else {
        status.innerText =
            'No face data found. Please enroll your face first.';
        status.classList.add('text-red-600');

        captureBtn.hidden = true;
        saveBtn.hidden = true;
        retakeBtn.hidden = true;

        video.style.display = 'none';
        canvas.style.display = 'none';

        return;
    }

    let capturedDescriptor = null;
    let capturedImage = null;

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                },
                audio: false,
            });

            video.srcObject = stream;

            await new Promise((resolve) => {
                video.onloadedmetadata = async () => {
                    await video.play();
                    resolve();
                };
            });

            status.innerText = 'Kamera aktif.';
        } catch (error) {
            console.error(error);
            status.innerText = 'Gagal mengakses kamera.';
        }
    }

    async function loadModels() {
        status.innerText = 'Memuat model face recognition...';

        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

        status.innerText = 'Model berhasil dimuat.';
    }

    async function captureFace() {
        status.innerText = 'Mendeteksi wajah...';

        const detection = await faceapi
            .detectSingleFace(
                video,
                new faceapi.TinyFaceDetectorOptions()
            )
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detection) {
            status.innerText = 'Wajah tidak terdeteksi.';
            return;
        }

        capturedDescriptor = JSON.stringify(
            Array.from(detection.descriptor)
        );

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        capturedImage = canvas.toDataURL('image/jpeg', 0.9);

        if (descriptorInput) {
            descriptorInput.value = capturedDescriptor;
            descriptorInput.dispatchEvent(
                new Event('input', { bubbles: true })
            );
        }

        if (referencePhotoInput) {
            referencePhotoInput.value = capturedImage;
            referencePhotoInput.dispatchEvent(
                new Event('input', { bubbles: true })
            );
        }

        // Stop kamera
        const stream = video.srcObject;
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }

        video.pause();
        video.srcObject = null;

        video.style.display = 'none';
        canvas.style.display = 'block';

        captureBtn.hidden = true;
        saveBtn.hidden = false;
        retakeBtn.hidden = false;

        status.innerText = 'Capture berhasil.';
    }

    async function retake() {
        capturedDescriptor = null;
        capturedImage = null;

        if (descriptorInput) descriptorInput.value = '';
        if (referencePhotoInput) referencePhotoInput.value = '';

        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        canvas.style.display = 'none';
        video.style.display = 'block';

        captureBtn.hidden = false;
        saveBtn.hidden = true;
        retakeBtn.hidden = true;

        await startCamera();

        status.innerText = 'Silakan ambil ulang foto.';
    }

    async function saveEnrollment() {
    try {

        status.innerText = 'Mengambil lokasi GPS...';

        const gps = await getGps();

        console.log('GPS FOUND', gps);

        const livewireEl = document.querySelector('[wire\\:id]');

        if (!livewireEl) {
            throw new Error('Livewire component tidak ditemukan');
        }

        const component =
            window.Livewire.find(
                livewireEl.getAttribute('wire:id')
            );

        if (!component) {
            throw new Error('CreateAttendance component tidak ditemukan');
        }
        
        await component.$wire.set(
            'data.latitude',
            Number(gps.latitude)
        );

        await component.$wire.set(
            'data.longitude',
            Number(gps.longitude)
        );

        await component.$wire.set(
            'data.location_name',
            'GPS Device'
        );

        console.log('GPS SAVED TO LIVEWIRE');

        submitButton.click();

        } catch (error) {

            console.error(error);

            status.innerText =
                'Gagal memperoleh GPS: ' + error.message;
        }
}

    try {
        // Jalankan kamera dan load model
        await startCamera();
        await loadModels();

        // Setup tampilan awal
        canvas.classList.add('hidden');
        canvas.style.display = 'none';

        saveBtn.hidden = true;
        retakeBtn.hidden = true;

        // Event listeners
        captureBtn.disabled = false;
        captureBtn.addEventListener('click', captureFace);
        retakeBtn.addEventListener('click', retake);
        saveBtn.addEventListener('click', saveEnrollment);

        status.innerText = 'Siap untuk capture wajah.';
    } catch (error) {
        console.error(error);
        status.innerText = 'Terjadi kesalahan.';
    }
};

if (document.readyState !== 'loading') {
        window.initFaceAttendance();
    } else {
        document.addEventListener('DOMContentLoaded', () => {
            window.initFaceAttendance();
        });
    }