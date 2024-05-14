
// -------------------- Show custom itinerary {id}--------------------------------------

// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);


let departureCoordinates;
// const polyline = L.polyline([]).addTo(mapItinerary);

fetch(`https://geo.api.gouv.fr/communes/${itineraryData.departure}?fields=centre`)
.then(response => response.json())
.then(data => {

    // departureCoordinates = [data.centre.coordinates[1], data.centre.coordinates[0]];
    let lat = data.centre.coordinates[1];
    let lng = data.centre.coordinates[0];

    const departureIcon = new L.icon({
        iconUrl: "/images/departure-city-icon.png",
        // Je définis la largeur et la hauteur de l'icône
        iconSize: [32,35],
        // Je définis le point d'ancrage de l'icône
        iconAnchor: [16,40],
        // Je définis le point d'ancrage du popup
        popupAnchor:[0,-39]
    });

    const marker = L.marker([lat,lng], {icon: departureIcon});
    marker.addTo(mapItinerary);
    // polyline.addLatLng(marker.getLatLng());

    
});

fetch(`https://geo.api.gouv.fr/communes/${itineraryData.arrival}?fields=centre`)
.then(response => response.json())
.then(data => {

    let lat = data.centre.coordinates[1];
    let lng = data.centre.coordinates[0];

    const arrivalIcon = new L.icon({
        iconUrl: "/images/arrival-city-icon.png",
        // Je définis la largeur et la hauteur de l'icône
        iconSize: [32,35],
        // Je définis le point d'ancrage de l'icône
        iconAnchor: [16,40],
        // Je définis le point d'ancrage du popup
        popupAnchor:[0,-39]
    });

    const marker = L.marker([lat,lng], {icon: arrivalIcon});
    marker.addTo(mapItinerary);
    // polyline.addLatLng(marker.getLatLng());
});


itineraryData.cities.forEach(cityCode => {
    fetch(`https://geo.api.gouv.fr/communes/${cityCode}?fields=centre`)
    .then(response => response.json())
    .then(data => {

        let lat = data.centre.coordinates[1];
        let lng = data.centre.coordinates[0];

        const intermediateIcon = new L.icon({
            iconUrl: "/images/intermediate-city-icon.png",
            // Je définis la largeur et la hauteur de l'icône
            iconSize: [32,35],
            // Je définis le point d'ancrage de l'icône
            iconAnchor: [16,40],
            // Je définis le point d'ancrage du popup
            popupAnchor:[0,-39]
        });

        const marker = L.marker([lat,lng], {icon: intermediateIcon})
        marker.addTo(mapItinerary);
    });
})



// const intermediateCitiesCoordinates = [];

// // Je récupère les coordonnées de chaque ville intermédiaire
// itineraryData.intermediateCities.forEach(city => {
//     fetch(`https://geo.api.gouv.fr/communes/${city.code}?fields=centre`)
//         .then(response => response.json())
//         .then(data => {
//             // J'ajoute les coordonnées de la ville intermédiaire à la carte
//             intermediateCitiesCoordinates.push([data.centre.coordinates[1], data.centre.coordinates[0]]);
//             L.marker(intermediateCitiesCoordinates[intermediateCitiesCoordinates.length - 1]).addTo(map);
//         });
// });


// J'ajoute une polyligne entre la ville de départ et la première ville intermédiaire
// if (intermediateCitiesCoordinates.length > 0) {
//     L.polyline([departureCoordinates, intermediateCitiesCoordinates[0]]).addTo(map);
// }

// // J'ajoute une polyligne entre chaque paire de villes intermédiaires
// for (let i = 1; i < intermediateCitiesCoordinates.length; i++) {
//     L.polyline([intermediateCitiesCoordinates[i - 1], intermediateCitiesCoordinates[i]]).addTo(map);
// }

// // J'ajoute une polyligne entre la dernière ville intermédiaire et la ville d'arrivée
// if (intermediateCitiesCoordinates.length > 0) {
//     L.polyline([intermediateCitiesCoordinates[intermediateCitiesCoordinates.length - 1], arrivalCoordinates]).addTo(map);
// }