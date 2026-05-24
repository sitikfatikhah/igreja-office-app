<div class="space-y-4"
     x-data
>

    <video
        id="video"
        autoplay
        playsinline
        muted
        class="w-full max-w-md rounded-lg border pb-10"
    ></video>

    <canvas
        id="canvas"
        class="hidden"
        style="display: none;"
    ></canvas>

    <input id="descriptorInput" type="hidden" />
    <input id="referencePhotoInput" type="hidden"/>

    <div class="flex flex-row gap-3">
        <button
            id="captureBtn"
            type="button"
            disabled
            class="bg-primary-600 text-white rounded-lg px-4 py-6"
        >
            Capture Face
        </button>
        <button
            id="saveBtn"
            type="button"
            hidden
            class="bg-emerald-600 text-white rounded-lg px-4 py-6"
        >
            Save Enrollment
        </button>
        <button
            id="retakeBtn"
            type="button"
            hidden
            class="bg-gray-600 text-white rounded-lg px-4 py-6"
        >
            Retake
        </button>
    </div>

    <div
        id="status"
        class="text-sm text-gray-600"
        data-has-face="{{ auth()->user()->face_descriptor ? 'true' : 'false' }}"
    >
        @if(auth()->user()->face_descriptor)
            Face data found. You can capture a new face or save to update.
        @else
            No face data found. Please enroll your face first.
        @endif
    </div>
</div>

@push('scripts')
    <script>
        window.faceAttendance = {
            savedDescriptor: @json(auth()->user()->face_descriptor),
        };
    </script>

    @vite('resources/js/face-attendance.js')
@endpush