<x-filament-panels::page>
    <div class="space-y-4">
        <div id="videoContainer" class="w-full max-w-md rounded-lg overflow-hidden" style="display: block;">
            <video id="video" autoplay playsinline class="w-full"></video>
        </div>
        <canvas id="canvas" style="display: none;"></canvas>
        <div id="photoContainer" class="w-full max-w-md rounded-lg overflow-hidden" style="display: none;">
            <img id="capturedPhoto" class="w-full" alt="Captured face" />
        </div>

        <p id="status" class="text-sm text-gray-600">Memuat kamera...</p>

        <input id="descriptorInput" type="hidden" />
        <input id="referencePhotoInput" type="hidden"/>

    </div>
    <div class="flex flex-wrap gap-3">

        <x-filament::button
            id="saveBtn"
            type="button"
            hidden
            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-500"
        >
            Save Enrollment
        </x-filament::button>

        <x-filament::button
            id="retakeBtn"
            type="button"
            hidden
            class="inline-flex items-center justify-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-gray-500"
        >
            Retake
        </x-filament::button>

    </div>
    @vite([
        'resources/js/face-enroll.js',
    ])
</x-filament-panels::page>
