<script>
function openMap(latitude, longitude) {
    const latLng = `${latitude},${longitude}`;
    const userAgent = window.navigator.userAgent;

    let mapUrl = "";

    // iOS users — Prefer Apple Maps
    if (/iPhone|iPad|iPod/.test(userAgent)) {
    mapUrl = `http://maps.apple.com/?ll=${latLng}`;
    }
        // Android or others — Prefer Google Maps
        else if (/Android/.test(userAgent)) {
        mapUrl = `geo:${latLng}?q=${latLng}`;
    }
        // Default fallback — Google Maps in browser
        else {
        mapUrl = `https://www.google.com/maps/search/?api=1&query=${latLng}`;
    }

        // Open the map in new window or tab
        window.open(mapUrl, '_blank');
}
</script>
