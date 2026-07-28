<style>
    .face-attendance-card {
        max-width: 28rem;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .face-attendance-video-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #111827;
        overflow: hidden;
    }
    .face-attendance-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background-color: #111827;
        transform: scaleX(-1);
    }
    .face-attendance-video-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 0.875rem;
        pointer-events: none;
    }
    .face-attendance-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        background-color: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease, transform 0.1s ease;
    }
    .face-attendance-btn:hover:not(:disabled) {
        background-color: #4338ca;
    }
    .face-attendance-btn:active:not(:disabled) {
        transform: scale(0.98);
    }
    .face-attendance-btn:disabled {
        background-color: #9ca3af;
        cursor: not-allowed;
    }
    .face-attendance-btn-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    .face-attendance-status {
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
    .face-attendance-status[data-state="error"] {
        background-color: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .face-attendance-status[data-state="success"] {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }
    .face-attendance-status[data-state="progress"] {
        background-color: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
    }
    .face-attendance-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #9ca3af;
        flex-shrink: 0;
    }
    .face-attendance-status[data-state="error"] .face-attendance-status-dot {
        background-color: #ef4444;
    }
    .face-attendance-status[data-state="success"] .face-attendance-status-dot {
        background-color: #22c55e;
    }
    .face-attendance-status[data-state="progress"] .face-attendance-status-dot {
        background-color: #3b82f6;
    }
</style>

<div id="face-attendance-root" class="face-attendance-card" x-data>
    <div id="videoContainer" class="face-attendance-video-wrap">
        <video
            id="video"
            autoplay
            playsinline
            muted
            class="face-attendance-video"
        ></video>
        <div class="face-attendance-video-placeholder" id="videoPlaceholder">
            Mengaktifkan kamera...
        </div>
    </div>

    <button
        id="captureBtn"
        type="button"
        disabled
        class="face-attendance-btn"
    >
        <svg class="face-attendance-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 7h3l2-3h8l2 3h3v12H3V7z"></path>
            <circle cx="12" cy="13" r="3.5"></circle>
        </svg>
        Verify &amp; check in/out
    </button>

    <div
        id="status"
        class="face-attendance-status"
        data-state="idle"
        data-has-face="{{ auth()->user()->face_descriptor ? 'true' : 'false' }}"
    >
        <span class="face-attendance-status-dot"></span>
        <span id="statusText">
            @if(auth()->user()->face_descriptor)
                Menyiapkan verifikasi wajah...
            @else
                Data wajah belum ditemukan. Silakan enroll wajah Anda terlebih dahulu.
            @endif
        </span>
    </div>
</div>

@push('scripts')
    <script>
        window.faceAttendance = {
            savedDescriptor: @json(auth()->user()->face_descriptor),
        };
    </script>
    @php
    $settings = app(\App\Services\SettingsService::class);
    @endphp

        <script>
        window.appSettings = {
            office_latitude: @json($settings->get('attendance.office_latitude')),
            office_longitude: @json($settings->get('attendance.office_longitude')),
            radius: @json($settings->get('attendance.radius')),
        };
        </script>


    @vite('resources/js/face-attendance.js')
@endpush