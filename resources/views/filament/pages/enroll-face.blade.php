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

        <input id="descriptorInput" type="hidden" wire:model.defer="descriptor" />
        <input id="referencePhotoInput" type="hidden" wire:model.defer="referencePhoto" />

    </div>
    <div class="flex flex-row gap-12">
        <button id="captureBtn" type="button" disabled class="bg-primary-600 text-white rounded-lg px-4 py-2">Capture Face</button>
        <button id="saveBtn" type="button" hidden class="bg-emerald-600 text-white rounded-lg px-4 py-2">Save Enrollment</button>
        <button id="retakeBtn" type="button" hidden class="bg-gray-600 text-white rounded-lg px-4 py-2">Retake</button>
    </div>
    @vite('resources/js/face-enroll.js')
</x-filament-panels::page>
