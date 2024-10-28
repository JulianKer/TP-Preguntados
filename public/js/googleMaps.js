let mapa;
let marcadorDeUbicacion;

function initMap() {
    const localizacionInicial = { lat: -34.6705534, lng: -58.5653413 };

    mapa = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: localizacionInicial,
    });

    marcadorDeUbicacion = new google.maps.Marker({
        position: localizacionInicial,
        map: mapa,
        draggable: true, // con este dejo q se pueda arrastrar el marcador
    });

    google.maps.event.addListener(marcadorDeUbicacion, 'dragend', function(event) {
        const location = event.latLng;
        let lat = event.latLng.lat();
        let lng = event.latLng.lng();
        console.log(lat)
        console.log(lng)


        // checkear pq uso esta api para obtener la ubicacion pero puede q tenga limite de uso jajaj
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                const country = data.address.country === undefined ? "" : `${data.address.country}.`;
                const state = data.address.state === undefined ? "" : `${data.address.state},`;
                const city = data.address.city === undefined ? "" : `${data.address.city},`;

                console.log(`Country: ${country}, State: ${state}, City: ${city}`);



                document.getElementById("selectedLocation").innerText =  `${city} ${state} ${country}`;
                document.getElementById("locationInput").value = `${lat}, ${lng}`; // este es el q vamos a guardar en la bdd
            })
            .catch(error => console.error('Error:', error));









        //document.getElementById("selectedLocation").innerText = location;
        //document.getElementById("locationInput").value = location;
    });
}