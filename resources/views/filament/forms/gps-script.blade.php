<div
    x-data="{ loading: true, message: 'Mengambil lokasi GPS...' }"
    x-init="
        setTimeout(() => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by this browser.');
                loading = false;
                message = 'GPS not supported';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    // Ambil koordinat GPS
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Kirim ke Livewire / Filament form
                    $wire.set('data.latitude', latitude);
                    $wire.set('data.longitude', longitude);

                    console.log('Latitude:', latitude);
                    console.log('Longitude:', longitude);

                    loading = false;

                    // Reverse geocoding untuk nama lokasi
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                        .then(res => res.json())
                        .then(data => {
                            const locationName = data.display_name;

                            // Simpan ke field hidden location_name
                            $wire.set('data.location_name', locationName);

                            // Tampilkan pesan lokasi
                            message = `Lokasi: ${locationName}`;

                            // Simpan URL Google Maps
                            window.mapUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;

                            loading = false;
                        });
                },
                (error) => {
                    console.error('GPS Error:', error);

                    let msg = 'Failed to get GPS location';

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            msg = 'GPS permission denied';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            msg = 'Location unavailable';
                            break;
                        case error.TIMEOUT:
                            msg = 'GPS request timeout';
                            break;
                    }

                    loading = false;
                    message = msg;
                    alert(msg);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0,
                }
            );
        }, 1000);
    "
>
    <p
        x-text="message"
        :class="loading ? 'text-gray-500' : 'text-green-600 font-semibold'"
        class="text-sm"
    ></p>
</div>