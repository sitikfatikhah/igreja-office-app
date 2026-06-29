<x-filament-panels::page>
    <style>
        .face-enroll-card {
            width: 100%;
            max-width: 32rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .face-enroll-frame {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            background-color: #111827;
            overflow: hidden;
        }
        .face-enroll-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-color: #111827;
            transform: scaleX(-1);
        }
        .face-enroll-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .face-enroll-frame-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.875rem;
            pointer-events: none;
        }
        .face-enroll-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .face-enroll-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 140px;
            border-radius: 10px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }
        .face-enroll-btn:active:not(:disabled) {
            transform: scale(0.98);
        }
        .face-enroll-btn:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        .face-enroll-btn-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .face-enroll-btn-capture {
            background-color: #4f46e5;
        }
        .face-enroll-btn-capture:hover:not(:disabled) {
            background-color: #4338ca;
        }
        .face-enroll-btn-save {
            background-color: #059669;
        }
        .face-enroll-btn-save:hover:not(:disabled) {
            background-color: #047857;
        }
        .face-enroll-btn-retake {
            background-color: #4b5563;
        }
        .face-enroll-btn-retake:hover:not(:disabled) {
            background-color: #374151;
        }
        .face-enroll-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            padding: 0.625rem 0.875rem;
            border-radius: 8px;
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .face-enroll-status[data-state="error"] {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }
        .face-enroll-status[data-state="success"] {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        .face-enroll-status[data-state="progress"] {
            background-color: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }
        .face-enroll-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #9ca3af;
            flex-shrink: 0;
        }
        .face-enroll-status[data-state="error"] .face-enroll-status-dot {
            background-color: #ef4444;
        }
        .face-enroll-status[data-state="success"] .face-enroll-status-dot {
            background-color: #22c55e;
        }
        .face-enroll-status[data-state="progress"] .face-enroll-status-dot {
            background-color: #3b82f6;
        }
    </style>

    <div id="face-enroll-root" class="face-enroll-card" x-data>
        <div id="videoContainer" class="face-enroll-frame" style="display: block;">
            <video id="video" autoplay playsinline class="face-enroll-video"></video>
            <div class="face-enroll-frame-placeholder" id="videoPlaceholder">
                Mengaktifkan kamera...
            </div>
        </div>

        <canvas id="canvas" style="display: none;"></canvas>

        <div id="photoContainer" class="face-enroll-frame" style="display: none;">
            <img id="capturedPhoto" class="face-enroll-photo" alt="Foto wajah tercapture" />
        </div>

        <div
            id="status"
            class="face-enroll-status"
            data-state="idle"
        >
            <span class="face-enroll-status-dot"></span>
            <span id="statusText">Memuat kamera...</span>
        </div>

        <div class="face-enroll-actions">

            <button
                id="captureBtn"
                type="button"
                disabled
                class="face-enroll-btn face-enroll-btn-capture"
            >
                <svg class="face-enroll-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7h3l2-3h8l2 3h3v12H3V7z"></path>
                    <circle cx="12" cy="13" r="3.5"></circle>
                </svg>
                Capture face
            </button>

            <button
                id="saveBtn"
                type="button"
                hidden
                class="face-enroll-btn face-enroll-btn-save"
            >
                <svg class="face-enroll-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
                Save enrollment
            </button>

            <button
                id="retakeBtn"
                type="button"
                hidden
                class="face-enroll-btn face-enroll-btn-retake"
            >
                <svg class="face-enroll-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"></path>
                    <path d="M3 4v4h4"></path>
                </svg>
                Retake
            </button>

        </div>

        <input id="descriptorInput" type="hidden" />
        <input id="referencePhotoInput" type="hidden"/>
    </div>

    @vite([
        'resources/js/face-enrollment.js',
    ])
</x-filament-panels::page>