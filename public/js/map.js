// ------------ Affichage de la liste des lieux ----------------
// const placesData = JSON.parse() 

// const placesContainer = document.getElementById('places-container');
// // console.log(placesContainer);

// const displayPlaces = (placesData) => {
//     // console.log(placesData);
//     placesData.forEach(place => {
//         let placeImages = place.images.map(image => `<figure><img src="/images/place/${image}"></figure>`).join('');
        
//         placesContainer.innerHTML += `
//         <div id="${place.id}">
//             <h2>${place.name}</h2>
//             <p>${place.address}</p>
//             <p>${place.city}</p>
//             <p>${place.companions.join(', ')}</p>
//             <p>${place.themes.join(', ')}</p>
//             <div class="place-img">${placeImages}</div>
//             <button id="hide-button">Masquer</button>
//             <p class="border"></p>
//         </div>`;
//         // console.log(placeImages);
//     });
// }
// displayPlaces(placesData);


// ------------ Affichage des marqueurs sur la map ----------------


// J'initialise la carte Leaflet
const map = L.map('map').setView([48.267, 7.45], 9);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Je définis les icônes pour chaque type de lieu
const hotelIcon = new L.icon({
    iconUrl: "/images/hotel-marker.webp",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [45,50],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const restaurantIcon = new L.icon({
    iconUrl: "/images/restaurant-marker.webp",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [45,50],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const hotelRestaurantIcon = new L.icon({
    iconUrl: "/images/hotel-restaurant-marker.webp",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [42,47],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const activityIcon = new L.icon({
    iconUrl: "/images/activity-marker.webp",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [45,50],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

// Je crée les LayerGroups pour les différents types de lieux
const hotelLayer = L.layerGroup();
const restaurantLayer = L.layerGroup();
const hotelRestaurantLayer = L.layerGroup();
const activityLayer = L.layerGroup();

// Je crée un objet pour associer les types de lieux à leurs LayerGroups respectifs
const layerGroups = {
    "Hôtel" : hotelLayer,
    "Restaurant" : restaurantLayer,
    "Hôtel-Restaurant" : hotelRestaurantLayer,
    "Activité": activityLayer
};

// J'ajoute les marqueurs aux LayerGroups en fonction du type de lieu
placesData.forEach(place => {
    let markerIcon;

    if(place.type === "Hôtel"){
        markerIcon = hotelIcon;
    } else if (place.type === "Restaurant"){
        markerIcon = restaurantIcon;
    } else if (place.type === "Hôtel-Restaurant"){
        markerIcon = hotelRestaurantIcon;
    } else if (place.type === "Activité"){
        markerIcon = activityIcon;
    }
    const marker = L.marker([place.latitude, place.longitude], {icon: markerIcon});

    // J'ajoute le marqueur au LayerGroup correspondant
    marker.addTo(layerGroups[place.type]);

    // J'ajoute un popup au marqueur avec le nom et l'adresse du lieu
    marker.bindPopup(`<strong>${place.name}</strong><br>${place.address}<br><img src="/images/place/${place.images[0]}" width=100>`);

});

// J'ajoute les LayerGroups au contrôle de couches
const layerControl = L.control.layers(null, {
    'Hôtels' : hotelLayer,
    'Restaurants' : restaurantLayer,
    'Hôtels-Restaurants' : hotelRestaurantLayer,
    'Activités' : activityLayer,
}).addTo(map);

// J'ajoute tous les LayerGroups à la carte
hotelLayer.addTo(map);
restaurantLayer.addTo(map);
hotelRestaurantLayer.addTo(map);
activityLayer.addTo(map);




// ---------------Masquer les marqueurs -----------------------

// const hideButton = document.getElementById('hide-button');
// // console.log(hideButton);

//     hideButton.addEventListener('click', () =>{
//         maker
//     })