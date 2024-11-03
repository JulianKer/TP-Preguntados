const lat = document.getElementById("lat").value;
const lng = document.getElementById("lng").value;

// checkear pq uso esta api para obtener la ubicacion pero puede q tenga limite de uso jajaj
fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
    .then(response => response.json())
    .then(data => {
        console.log(data);

        const country = data.address.country === undefined ? "" : `${data.address.country}.`;
        const state = data.address.state === undefined ? "" : `${data.address.state},`;
        const city = data.address.city === undefined ? "" : `${data.address.city},`;

        console.log(`Country: ${country}, State: ${state}, City: ${city}`);

        const todo = data.display_name; // mejor uso este q me da mas datos y tod junto je
        console.log(todo);

        document.getElementById("selectedLocation").innerText = `${todo}`; //`${city} ${state} ${country}`;
        document.getElementById("locationInput").value = `${lat}, ${lng}`; // este es el q vamos a guardar en la bdd
    })
    .catch(error => console.error('Error:', error));