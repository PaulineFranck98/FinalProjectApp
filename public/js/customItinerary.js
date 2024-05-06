
// -------------------- Show custom itinerary {id}--------------------------------------

// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);


    // fetch(`/map/itinerary`)
    //     .then(response => response.json()) 
    //     .then(data => console.log(data))
    

// const placesData = JSON.parse() 

console.log(itineraryData);
