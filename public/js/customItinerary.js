
// -------------------- Show custom itinerary {id}--------------------------------------

// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);


let departureCoordinates;
const polyline = L.polyline([]).addTo(mapItinerary);

fetch(`https://geo.api.gouv.fr/communes/${itineraryData.departure}?fields=centre`)
.then(response => response.json())
.then(data => {

    departureCoordinates = [data.centre.coordinates[1], data.centre.coordinates[0]];
    let lat = data.centre.coordinates[1];
    let lng = data.centre.coordinates[0];

    const marker = L.marker([lat,lng]);
    marker.addTo(mapItinerary);
    polyline.addLatLng(marker.getLatLng());

    
});

fetch(`https://geo.api.gouv.fr/communes/${itineraryData.arrival}?fields=centre`)
.then(response => response.json())
.then(data => {

    let lat = data.centre.coordinates[1];
    let lng = data.centre.coordinates[0];

    const marker = L.marker([lat,lng])
    marker.addTo(mapItinerary);
    polyline.addLatLng(marker.getLatLng());
});





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