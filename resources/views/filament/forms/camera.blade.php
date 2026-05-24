<div
    x-data="cameraApp('{{ $field }}')"
    x-init="init()"
>
    <video x-ref="video" autoplay playsinline class="w-full rounded border"></video>

    <button
        type="button"
        class="mt-2 px-4 py-2 bg-primary-600 text-white rounded"
        @click="takePhoto"
    >
        Ambil Foto
    </button>

    <canvas x-ref="canvas" class="hidden"></canvas>

    <!-- PREVIEW FOTO -->
    <img
        x-show="preview"
        :src="preview"
        class="mt-2 rounded border w-full"
    />
</div>

<script>
window.cameraApp = function (field) {
    return {
        stream: null,
        preview: null,

        init() {
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' }
            }).then(stream => {
                this.stream = stream;
                this.$refs.video.srcObject = stream;
            });
        },

        takePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob((blob) => {

                // STOP CAMERA (INI YANG KAMU LUPA)
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }

                // preview biar tidak hilang
                this.preview = URL.createObjectURL(blob);

                // IMPORTANT: kirim ke Filament state
                this.$wire.set(`data.${field}`, blob);

            }, 'image/jpeg', 0.9);
        }
    }
}
</script>