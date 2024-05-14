// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    minZoom:8,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);





// var citySelect = new TomSelect('#custom_itinerary_cities', {
//     // maxItems: null,
//     labelField: 'cityName',
//     searchField: 'cityName',
//     options: [],
// });
// console.log(citySelect);
// $(document).ready(function() {
//     $('.tom-select').tomSelect({
//         create: true,
//         sortField: {
//             field: 'text',
//             direction: 'asc'
//         },
//         plugins: {
//             remove_button: {
//                 title: 'Supprimer la ville',
//             }
//         },
//         render: {
//             option: function(data, escape) {
//                 return '<div>' + escape(data.text) + '</div>';
//             },
//             item: function(data, escape) {
//                 return '<div>' + escape(data.text) + ' <span class="badge badge-secondary">' + data.id + '</span></div>';
//             }
//         },
//         ajax: {
//             type: 'GET',
//             url: '/place/cities_with_places',
//             dataType: 'json',
//             // data: function(params) {
//             //     return {
//             //         q: params.term
//             //     };
//             // },
//             // processResults: function(data) {
//             //     return {
//             //         results: data
//             //     };
//             // }
//         }
//     });
// });




async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}


// Je récupère et stock les éléments input où l'utilisateur entre le nom de la commune de départ et d'arrivée 
const arrivalInput = document.querySelector('#custom_itinerary_arrival');
const departureInput = document.querySelector('#custom_itinerary_departure');

// Je récupère et stock les éléments où seront affichées les suggestions de communes de départ et d'arrivée
const departureCommuneSuggestions = document.querySelector('#commune-with-places-departure');
const arrivalCommuneSuggestions = document.querySelector('#commune-with-places-arrival');

// 
const polyline = L.polyline([]).addTo(mapItinerary);

const updatePolyline = (latLng) => {
    polyline.addLatLng(latLng);
}

// J'ajoute un écouteur d'événement sur l'élément input de la ville de départ 'departure'
departureInput.addEventListener('input', async () => {

    // Je réinitialise la liste de suggestions de communes
    departureCommuneSuggestions.innerHTML = '';

    // Je récupère et stock la valeur entrée par l'utilisateur et je supprime les espaces avant et après
    const search = departureInput.value.trim();

    // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
    if (search.length >= 3)
    {
        const citiesWithPlaces = await getCitiesWithPlaces();
        // console.log(citiesWithPlaces);
        // J'effectue une requête vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
        fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {

            // Je filtre les résultats pour récupérer les villes contenant des lieux d'intérêts
            const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.cityName === commune.nom));

            filteredData.forEach(commune => {

                // J'ajoute un élément <li> contenant les suggestions de communes à la liste <ul> #departureCommuneSuggestions
                const listItem = document.createElement('li');

                // Je définis le contenu texte de chaque élément <li> sur le nom de chaque commune suggérée dans la liste <uk>
                listItem.textContent = commune.nom;

                //Je récupère et stock la latitude de chaque commune  
                let lat = commune.centre.coordinates[1];

                // Je récupère et stock la longitude de chaque commune
                let lng = commune.centre.coordinates[0];

                // J'ajoute un écouteur d'événement au click sur l'élément <li> 
                listItem.addEventListener('click', () => {

                    // J'attribue comme valeur à l'input le nom de la commune sélectionnée
                    departureInput.value = commune.nom;

                    // Je récupère et stock l'élément input caché où le code unique INSEE de la commune sera stocké   
                    const codeInput = document.querySelector('#custom_itinerary_codeDeparture');

                    // Je définis la valeur de l'input sur le code INSEE de la commune
                    codeInput.value = commune.code;

                    console.log('departure : ' + lat, lng);

                    const departureIcon = new L.icon({
                        iconUrl: "/images/departure-city-icon.png",
                        // Je définis la largeur et la hauteur de l'icône
                        iconSize: [32,35],
                        // Je définis le point d'ancrage de l'icône
                        iconAnchor: [16,40],
                        // Je définis le point d'ancrage du popup
                        popupAnchor:[0,-39]
                    });

                    // Je récupère et stock la position de la commune sélectionnée en utilisant sa latitude et sa longitude
                    const marker = L.marker([lat, lng], {icon: departureIcon});

                    // J'ajoute le marqueur à la carte en utilisant la fonction addTo(), native de Leaflet
                    marker.addTo(mapItinerary);
                    marker.bindPopup('Je suis la ville de départ!').openPopup();
                    // Je mets à jour la polyline en récupérant la latitude et longitude du marqueur avec la fonction getLatLng(), native de Leaflet
                    // updatePolyline(marker.getLatLng());

                    // Je réinitialise la liste de suggestions de communes une fois la commune sélectionnée
                    departureCommuneSuggestions.innerHTML = '';
                });

                // J'ajoute l'élément <li> à l'élément <ul> où sont affichées les suggestions de communes grâce à la fonction appendChild, native Javascript
                departureCommuneSuggestions.appendChild(listItem);
            });
        });
    }   
});

// J'ajoute un écouteur d'événement sur l'élément input pour la ville d'arrivée,  'arrival'
arrivalInput.addEventListener('input', async () => {

    // Je réinitialise la liste de suggestions de communes
    arrivalCommuneSuggestions.innerHTML = '';

    // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
    const search = arrivalInput.value.trim();

    // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
    if (search.length >= 3)
    {
        const citiesWithPlaces = await getCitiesWithPlaces();
        
        // J'effectue une requête vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
        fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {

            // Je filtre les résultats pour récupérer les villes contenant des lieux d'intérêts grâce à la fonction some(), fonction native Javascript
            const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.cityName === commune.nom));

            // J'ajoute les éléments <li> contenant les suggestions de communes à la liste <ul> appropriée
            filteredData.forEach(commune => {

                // J'ajoute un élément <li> contenant les suggestions de communes à la liste <ul> #arrivalCommunSuggestions
                const listItem = document.createElement('li');

                // Je définis le contenu texte de chaque élément <li> sur me nom de chaque commune suggérée dans la liste <ul>
                listItem.textContent = commune.nom;

                // Je récupère et stock la latitude de chaque commune 
                let lat = commune.centre.coordinates[1];
                
                // Je récupère et stock la longitude de chaque commune
                let lng = commune.centre.coordinates[0];

                // J'ajoute un écouteur d'événement au click sur l'élément <li>
                listItem.addEventListener('click', () => {

                    // J'attribue comme valeur à l'input le nom de la commune sélectionnée
                    arrivalInput.value = commune.nom;

                    // Je récupère et stock l'élément input caché où le code unique INSEE de la commune sera stocké
                    const codeInput = document.querySelector('#custom_itinerary_codeArrival');

                    // Je définis la valeur de l'input sur le code INSEE de la commune
                    codeInput.value = commune.code;

                    console.log('arrival : ' + lat, lng);

                    const arrivalIcon = new L.icon({
                        iconUrl: "/images/arrival-city-icon.png",
                        // Je définis la largeur et la hauteur de l'icône
                        iconSize: [32,35],
                        // Je définis le point d'ancrage de l'icône
                        iconAnchor: [16,40],
                        // Je définis le point d'ancrage du popup
                        popupAnchor:[0,-39]
                    });

                    // Je récupère et stock la position de la commune de l'élément sélectionnée en utilisant sa latitude et sa longitude
                    const marker = L.marker([lat, lng], {icon: arrivalIcon});

                    // J'ajoute le marqueur à la carte en utilisant la fonction addTo(), native de Leaflet
                    marker.addTo(mapItinerary);
                    marker.bindPopup('Je suis la ville d\'arrivée!').openPopup();
                    // Je mets à jour la polyline en récupérant la latitude et la longitude du marqueur avec la fonction getLatLng(), native de Leaflet
                    // updatePolyline(marker.getLatLng());

                    // Je réinitialise la liste de suggestions de communes une fois la commune sélectionnée
                    arrivalCommuneSuggestions.innerHTML = '';
                });

                // J'ajoute l'élément <li> à l'élément <ul> où sont affichées les suggestions de communes grâce à la fonction appendChild(), native Javascript
                arrivalCommuneSuggestions.appendChild(listItem);
            });
        });
    }
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

        var deleteButton = $('<button type="button" class="delete_city_button">Supprimer</button>');
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
            iconUrl: "/images/intermediate-city-icon.png",
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


