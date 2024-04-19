
// J'initialise la carte Leaflet
const map = L.map('map').setView([48.267, 7.45], 9);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Je définis les icônes pour chaque type de lieu
const hotelIcon = new L.icon({
    iconUrl: "/images/marker-hotel.png",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [32,40],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const restaurantIcon = new L.icon({
    iconUrl: "/images/marker-restaurant.png",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [32,40],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const hotelRestaurantIcon = new L.icon({
    iconUrl: "/images/marker-hotel-restaurant.png",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [32,40],
    // Je définis le point d'ancrage de l'icône
    iconAnchor: [16,40],
    // Je définis le point d'ancrage du popup
    popupAnchor:[0,-39]
});

const activityIcon = new L.icon({
    iconUrl: "/images/marker-activity.png",
    // Je définis la largeur et la hauteur de l'icône
    iconSize: [32,40],
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
    marker.bindPopup(`<strong>${place.name}</strong><br>${place.address}`);

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


