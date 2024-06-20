// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    minZoom:8,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);




async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}


// Je récupère et stock les éléments input où l'utilisateur entre le nom de la commune de départ et d'arrivée 
const arrivalInput = document.querySelector('#custom_itinerary_arrival');
const departureInput = document.querySelector('#custom_itinerary_departure');


  
// J'ajoute un écouteur d'évènement sur le sélecteur de la ville d'arrivée
departureInput.addEventListener('change', () => {
    // Je récupère la valeur sélectionnée 
    const departureValue = departureInput.value;
    console.log(departureValue);

    fetch(`https://geo.api.gouv.fr/communes?code=${departureValue}&fields=nom,code,centre&limit=3`)
        .then(response => response.json()) // Je convertis la réponse en JSON
        .then(data => {
            data.forEach(commune => {
                let marker;

                // Je définis l'icône pour la ville de départ
                const departureIcon = new L.icon({
                    iconUrl: "/images/departure-city-icon.webp",
                    // Je définis la largeur et la hauteur de l'icône
                    iconSize: [32,35],
                    // Je définis le point d'ancrage de l'icône
                    iconAnchor: [16,40],
                    // Je définis le point d'ancrage du popup
                    popupAnchor:[0,-39]
                });

                // Je récupère et stocke dans la variable 'lat' la latitude de chaque commune
                let lat = commune.centre.coordinates[1];
                
                // Je récupère et stocke dans la variable 'lng' la longitude de chaque commune
                let lng = commune.centre.coordinates[0];
                
                console.log(lat, lng);
                if(marker){
                    mapItinerary.removeLayer(marker);
                }
                        
                // Je récupère et stock dans la constante 'marker' la latitude et la longitude de la commune sélectionnée
                marker = L.marker([lat, lng], {icon: departureIcon});
                        
                // J'ajoute de marqueur de la commune correspondante à la map 
                mapItinerary.addLayer(marker)
                marker.bindPopup(`Je suis la ville de départ`).openPopup();
                        
            })
        
    })
  
});    

// J'ajoute un écouteur d'évènement sur le sélecteur de la ville d'arrivée
arrivalInput.addEventListener('change', () => {
    // Je récupère la valeur sélectionnée 
    const arrivalValue = arrivalInput.value;
    
    fetch(`https://geo.api.gouv.fr/communes?code=${arrivalValue}&fields=nom,code,centre&limit=3`)
    .then(response => response.json()) // Je convertis la réponse en JSON
    .then(data => {
        data.forEach(commune => {
            let marker;
            // Je définis l'icône pour la ville d'arrivée
            const arrivalIcon = new L.icon({
                iconUrl: "/images/arrival-city-icon.webp",
                // Je définis la largeur et la hauteur de l'icône
                iconSize: [32,35],
                // Je définis le point d'ancrage de l'icône
                iconAnchor: [16,40],
                // Je définis le point d'ancrage du popup
                popupAnchor:[0,-39]
            });
            // Je récupère et stocke dans la variable 'lat' la latitude de la commune
            let lat = commune.centre.coordinates[1];
            
            // Je récupère et stocke dans la variable 'lng' la longitude de la commune
            let lng = commune.centre.coordinates[0];
            
            console.log(lat, lng);
            if(marker){
                mapItinerary.removeLayer(marker);
            }
                    
            // Je récupère et stock dans la constante 'marker' la latitude et la longitude de la commune sélectionnée
            marker = L.marker([lat, lng], {icon: arrivalIcon});
            
            // J'ajoute de marqueur de la commune correspondante à la map 
            mapItinerary.addLayer(marker)
            marker.bindPopup(`Je suis la ville d'arrivée`).openPopup();
                    
        })
    })
});    


$(document).ready(function() {

    // Je récupère et stock l'élément input où l'utilisateur entre le nom de la ville intermédiaire
    var addCityButton = $('.add_city_button');

    // J'ajoute un écouteur d'évènement au click sur le bouton d'ajout de ville 'addCityButton'
    addCityButton.click(function() {

        var list = $($(this).attr('data-list-selector'))

        var counter = list.data('widget-counter') || list.children().length;

        // Je récupère le prototype de la ville intermédiaire à partir de l'attribut data-prototype
        var newCityWidget = list.attr('data-prototype');

        // Je remplace le texte "__name__" dans le prototype par l'index de la nouvelle ville intermédiaire
        
        newCityWidget = newCityWidget.replace(/__name__/g, counter);

        // J'ajoute un attribut data-type et  data-index à l'élément input de commune intermédiaire
        // newCityWidget = newCityWidget.replace('<input type="text"', '<input type="text" data-type="intermediateCity" data-index="' + counter + '"');

        // Je récupère l'élément <div> et je le stocke dans la varibable 'newCityDiv'rée un nouvel élément div pour contenir le nouvel input texte
        var newCityDiv = $('<div class="intermediate_city_container"></div>');

        // Je définis le contenu de cet élément grâce à la fonction html(), native jQuery
        newCityDiv.html(newCityWidget);

        var deleteButton = $('<button type="button" class="delete_city_button"><i class="fa-solid fa-circle-minus"></i></button>');
        newCityDiv.append(deleteButton);

        // Je crée une nouvelle liste <ul> pour les suggestions de communes et je rends leur id unique grâce à l'index (counter)
        // var newCommuneSuggestions = $('<ul id="commune-with-places-' + counter + '"></ul>');

        // J'ajoute la liste <ul> après chaque nouvel élément input
        // newCityDiv.append(newCommuneSuggestions);

        // J'ajoute le nouvel élément <div> à la collection de villes intermédiaires grâce à la fonction append(), native jQuery
        $('#custom_itinerary_intermediate_cities').append(newCityDiv);

        counter++
        list.data('widget-counter', counter)

        let marker;
        // Je définis les icônes pour les villes intermédiaires
        const intermediateIcon = new L.icon({
            iconUrl: "/images/intermediate-city-icon.webp",
            // Je définis la largeur et la hauteur de l'icône
            iconSize: [32,35],
            // Je définis le point d'ancrage de l'icône
            iconAnchor: [16,40],
            // Je définis le point d'ancrage du popup
            popupAnchor:[0,-39]
        });

        // console.log(newCityDiv.find('select'));
        newCityDiv.find('select').change(function(){
            var selectedCity = $(this).children("option:selected").val();
            console.log(selectedCity);

            fetch(`https://geo.api.gouv.fr/communes?code=${selectedCity}&fields=nom,code,centre&limit=3`)
                .then(response => response.json()) // Je convertis la réponse en JSON
                .then(data => {
                    data.forEach(commune => {
                        // Je récupère et stocke dans la variable 'lat' la latitude de chaque commune
                        let lat = commune.centre.coordinates[1];

                        // Je récupère et stocke dans la variable 'lng' la longitude de chaque commune
                        let lng = commune.centre.coordinates[0];

                        console.log(lat, lng);
                        if(marker){
                            mapItinerary.removeLayer(marker);
                        }

                        // Je récupère et stock dans la constante 'marker' la latitude et la longitude de la commune sélectionnée
                       marker = L.marker([lat, lng], {icon: intermediateIcon});

                        // J'ajoute de marqueur de la commune correspondante à la map 
                        mapItinerary.addLayer(marker)
                        marker.bindPopup(`Je suis la ville n°${counter}`).openPopup();

                    })
                })
        })

             // J'ajoute un écouteur d'évènement au clic sur le bouton "supprimer"
             deleteButton.click(function() {
                // Je supprime le conteneur de la ville intermédiaire
                newCityDiv.remove();
                // Je supprime le marqueur correspondant à la ville intermédiaire de la carte
                mapItinerary.removeLayer(marker);
            });
    
       
        // counter++
        // list.data('widget-counter', counter)
    });
});


