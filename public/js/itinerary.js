// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);



async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}

// Récupère les éléments input où l'utilisateur entre le nom de la commune pour 'arrival' et 'departure'
const departureInput = document.querySelector('#custom_itinerary_departure');
const arrivalInput = document.querySelector('#custom_itinerary_arrival');

// Récupère les éléments où seront affichées les suggestions de communes pour 'arrival' et 'departure'
const departureCommuneSuggestions = document.querySelector('#commune-with-places-departure');
const arrivalCommuneSuggestions = document.querySelector('#commune-with-places-arrival');

const polyline = L.polyline([]).addTo(mapItinerary);

const updatePolyline = (latLng) => {
    polyline.addLatLng(latLng);
}

// Ajoute un écouteur d'événement sur l'élément input pour 'departure'
departureInput.addEventListener('input', async () => {
    // Réinitialise la liste de suggestions de communes
    departureCommuneSuggestions.innerHTML = '';

    // Récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
    const search = departureInput.value.trim();

    // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
    if (search.length >= 3)
    {
        const citiesWithPlaces = await getCitiesWithPlaces();
        
        // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
        fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {

            // Filtre les résultats
            const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom));

            // Ajoute les éléments <li> contenant les suggestions de communes à la liste <ul> appropriée
            filteredData.forEach(commune => {
                const listItem = document.createElement('li');
                listItem.textContent = commune.nom;

                let lat = commune.centre.coordinates[1];
                let lng = commune.centre.coordinates[0];

                // Ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click
                listItem.addEventListener('click', () => {
                    // Attribue comme valeur à l'input le nom de la commune sélectionnée
                    departureInput.value = commune.nom;
                    const codeInput = document.querySelector('#custom_itinerary_codeDeparture');
                    codeInput.value = commune.code;

                    console.log('departure : ' + lat, lng);

                    // Ajoute un marqueur à la carte
                    const marker = L.marker([lat, lng]);
                    marker.addTo(mapItinerary);
                    updatePolyline(marker.getLatLng());

                    // Réinitialise la liste de suggestions de communes
                    departureCommuneSuggestions.innerHTML = '';
                });

                // Ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                departureCommuneSuggestions.appendChild(listItem);
            });
        });
    }   
});

// Ajoute un écouteur d'événement sur l'élément input pour 'arrival'
arrivalInput.addEventListener('input', async () => {
    // Réinitialise la liste de suggestions de communes
    arrivalCommuneSuggestions.innerHTML = '';

    // Récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
    const search = arrivalInput.value.trim();

    // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
    if (search.length >= 3)
    {
        const citiesWithPlaces = await getCitiesWithPlaces();
        
        // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
        fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {

            // Filtre les résultats
            const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom));

            // Ajoute les éléments <li> contenant les suggestions de communes à la liste <ul> appropriée
            filteredData.forEach(commune => {
                const listItem = document.createElement('li');
                listItem.textContent = commune.nom;

                let lat = commune.centre.coordinates[1];
                let lng = commune.centre.coordinates[0];

                // Ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click
                listItem.addEventListener('click', () => {
                    // Attribue comme valeur à l'input le nom de la commune sélectionnée
                    arrivalInput.value = commune.nom;
                    const codeInput = document.querySelector('#custom_itinerary_codeArrival');
                    codeInput.value = commune.code;

                    console.log('arrival : ' + lat, lng);

                    // Ajoute un marqueur à la carte
                    const marker = L.marker([lat, lng]);
                    marker.addTo(mapItinerary);
                    updatePolyline(marker.getLatLng());

                    // Réinitialise la liste de suggestions de communes
                    arrivalCommuneSuggestions.innerHTML = '';
                });

                // Ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                arrivalCommuneSuggestions.appendChild(listItem);
            });
        });
    }
});

// Ajoute un écouteur d'événement sur le bouton d'ajout de ville intermédiaire
$(document).ready(function() {
    var addCityButton = $('.add_city_button');
    addCityButton.click(function() {

        var list = $($(this).attr('data-list-selector'))

        var counter = list.data('widget-counter') || list.children().length;
        // Récupère le prototype de la ville intermédiaire à partir de l'attribut data-prototype
        var newCityWidget = list.attr('data-prototype');

        // Remplace le texte "__name__" dans le prototype par l'index de la nouvelle ville intermédiaire
        
        newCityWidget = newCityWidget.replace(/__name__/g, counter);

        // Ajoute un attribut data-index à l'élément input de commune intermédiaire
        newCityWidget = newCityWidget.replace('<input type="text"', '<input type="text" data-type="intermediateCity" data-index="' + counter + '"');

        // Crée un nouvel élément div pour contenir le nouvel input texte
        var newCityDiv = $('<div class="intermediate_city_container"></div>');
        newCityDiv.html(newCityWidget);

        // Crée une nouvelle liste <ul> pour les suggestions de communes
        var newCommuneSuggestions = $('<ul id="commune-with-places-' + counter + '"></ul>');

        // Ajoute la nouvelle liste <ul> après le nouvel élément input
        newCityDiv.append(newCommuneSuggestions);

        // Ajoute le nouvel élément div à la collection de villes intermédiaires
        $('#custom_itinerary_intermediate_cities').append(newCityDiv);

        // Ajoute un écouteur d'événement sur l'élément input de commune intermédiaire
        newCityDiv.find('input[type="text"]').on('input', async function() {
            // Réinitialise la liste de suggestions de communes
            newCommuneSuggestions.innerHTML = '';

            // Récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
            const search = this.value.trim();

            // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
            if (search.length >= 3)
            {
                const citiesWithPlaces = await getCitiesWithPlaces();
                
                // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
                fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
                    .then(response => response.json()) // Je convertis la réponse en JSON
                    .then(data => {

                    // Filtre les résultats
                    const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom));

                    // Ajoute les éléments <li> contenant les suggestions de communes à la liste <ul> appropriée
                    filteredData.forEach(commune => {
                        const listItem = document.createElement('li');
                        listItem.textContent = commune.nom;

                        let lat = commune.centre.coordinates[1];
                        let lng = commune.centre.coordinates[0];

                        // Ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click
                        listItem.addEventListener('click', () => {
                            // Attribue comme valeur à l'input le nom de la commune sélectionnée
                            this.value = commune.nom;
                            let inputType = this.getAttribute('data-type');

                            if (inputType === "intermediateCity") {
                                // Récupère l'élément input caché qui contient le code INSEE de la commune sélectionnée
                                // const codeInput = this.parentNode.querySelector('input[type="hidden"]');
                                // codeInput.value = commune.code;

                                console.log(`intermediate city ${this.dataset.index}: `, lat, lng);
                            }

                            // Ajoute un marqueur à la carte
                            const marker = L.marker([lat, lng]);
                            marker.addTo(mapItinerary);
                            updatePolyline(marker.getLatLng());

                            // Réinitialise la liste de suggestions de communes
                            // newCommuneSuggestions.innerHTML = '';
                            newCommuneSuggestions.empty();
                        });

                        // Ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                        newCommuneSuggestions.append(listItem);
                    });
                });
            }
        });

        // Incrémente le compteur de villes intermédiaires
        // $(this).data('widget-counter', counter + 1);
        counter++
        list.data('widget-counter', counter)
    });
});





   
// $(document).ready(function() {
//     // var cityIndex = $('.intermediate_city').length

//     // J'ajoute un élément bouton pour permettre l'ajout d'une nouvelle ville intermédiaire
//     // var addCityButton = $('<button type="button" class="add_city_button">Ajouter une ville</button>');
//     // $('#custom_itinerary_intermediate_cities').append(addCityButton);
//      var addCityButton = $('.add_city_button')

//     // j'ajoute un événement clic sur le bouton d'ajout de ville intermédiaire
//     addCityButton.click(function() {

//         // cityIndex++;
//         var list = $($(this).attr('data-list-selector'))

//         // var counter = list.data('widget-counter') || list.children().length;
//         var counter =  list.children().length;

//         var itinerary = list.data('itinerary')

//         // Je récupère le prototype de la ville intermédiaire à partir de l'attribut data-prototype
//         // var newCityWidget = $('#custom_itinerary_intermediate_cities').data('prototype');
//         var newCityWidget = list.attr('data-prototype')

//         // Je remplace le texte "__name__" dans le prototype par l'index de la nouvelle ville intermédiaire
//         newCityWidget = newCityWidget.replace(/__name__/g, counter)
//         newCityWidget = newCityWidget.replace('<input type="text"', '<input type="text" data-type="intermediateCity" data-index="' + counter + '"class="intermediate_city"');
//         // var inputElement = $(newCityWidget).find('input');
//         // inputElement.attr('data-type', 'intermediateCity');
//         // Ajout  des attributs personnalisés "class" et "value", qui n'apparaissent pas dans le prototype original 
//         newCityWidget = newCityWidget.replace(/><input type="hidden"/, '><input type="hidden" value="'+itinerary+'"')

//         counter++
//         list.data('widget-counter', counter)

//         // Je crée un nouvel élément div pour contenir le nouvel input texte
//         var newCityDiv = $('<div class="intermediate_city_container"></div>')
//         newCityDiv.html(newCityWidget)

        
//         // Je crée une nouvelle liste <ul> pour les suggestions de communes
//         var newCommuneSuggestions = $('<ul id="commune-with-places-' + counter + '"></ul>');

//         // J'ajoute la nouvelle liste <ul> après le nouvel élément input
//         newCityDiv.append(newCommuneSuggestions);

//         // J'ajoute un élément bouton "Supprimer" à chaque nouvelle ville intermédiaire
//         var deleteCityButton = $('<button type="button" class="delete_city_button">Supprimer</button>')
//         newCityDiv.append(deleteCityButton)

//         // J'ajoute le nouvel élément div à la collection de villes intermédiaires
//         $('#custom_itinerary_intermediate_cities').append(newCityDiv);

//     });

//     // J'ajoute un événement clic sur le bouton de suppression de ville intermédiaire permettant de supprimer l'élément div, parent du bouton supprimer
//     $(document).on('click', '.delete_city_button', function() {
//         $(this).parent().remove()
//     });
// });



// //Envoie une requête http get à l'url
// async function getCitiesWithPlaces() {
//     const response = await fetch('/place/cities_with_places'); 
//     const data = await response.json();
//     return data;
// }
    
//     // Je récupère l'élément input où l'utilisateur entre le nom de la commune
//     const communeInputs = document.querySelectorAll('#custom_itinerary_departure, #custom_itinerary_arrival, .intermediate_city');
//     // je récupère l'élément où seront stockés le code INSEE de la commune de départ
//     const codeDeparture = document.querySelector('#custom_itinerary_codeDeparture');
    
//     // je récupère l'élément où seront stockés le code INSEE de la commune d'arrivée
//     const codeArrival = document.querySelector('#custom_itinerary_codeArrival');

//     // console.log(document.querySelector('#custom_itinerary_cities_0_cityCode'));
    
//     // 
//     const polyline = L.polyline([]).addTo(mapItinerary);

//     const updatePolyline = (latLng) => {
//         polyline.addLatLng(latLng);
//     }

//     communeInputs.forEach(communeInput => {
//         communeInput.addEventListener('input', async () => {

//             const communeSuggestions = communeInput.nextElementSibling;
//             // console.log(communeInput.id);

//             // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
//             const search = communeInput.value.trim();
//             // console.log(communeInput);

//             // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
//             if (search.length >= 3)
//             {
//                 const citiesWithPlaces = await getCitiesWithPlaces();
                
//                 // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
//                 fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
//                     .then(response => response.json()) // Je convertis la réponse en JSON
//                     .then(data => {
//                         // Je réinitialise l'élément où sont affichées les suggestions de communes
//                         communeSuggestions.innerHTML = '';
        
//                         // Je filtre les résultats 
//                         const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom))
//                         // Pour chaque commune filtrée
//                             // console.log('données', filteredData);

//                         filteredData.forEach(commune => {
//                             // Je crée un élément li qui contient le nom de la commune
//                             const listItem = document.createElement('li');
//                             listItem.textContent = commune.nom;

//                             let lat = commune.centre.coordinates[1];
//                             let lng = commune.centre.coordinates[0];

//                             // let inputType = communeInput.getAttribute('data-type');
//                             // console.log()
//                             const marker = L.marker([lat,lng]);
//                             console.log(listItem);
//                             console.log(communeSuggestions);
//                             // J'ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click 
//                             listItem.addEventListener('click', () => {
//                                 // J'attribue comme valeur à l'input le nom de la commune sélectionnée
//                                 communeInput.value = commune.nom; 
//                                 let inputType = communeInput.getAttribute('data-type');

//                                 if( inputType === "departure")
//                                 {  
//                                     codeDeparture.value = commune.code;

//                                     console.log('departure : ' + lat,lng);

//                                 } else if(inputType === "arrival")
//                                 {
                                    
//                                     codeArrival.value = commune.code;

//                                     console.log('arrival : ' + lat, lng);
            
//                                 } 
//                                 else if ( inputType === "intermediateCity"){
                                    
//                                 // console.log(document.querySelector('#custom_itinerary_cities_0_cityCode'));

//                                     console.log(`intermediate city ${communeInput.dataset.index} : `, lat, lng);
//                                 }

//                                 marker.addTo(mapItinerary);

//                                 // polyline.addLatLng(marker.getLatLng());
//                                 updatePolyline(marker.getLatLng());
                        
//                                 communeSuggestions.innerHTML = '';
//                             });
                

//                             // J'ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
//                             communeSuggestions.appendChild(listItem);
                        
//                         });
                    
                    
//                     })
//                     .catch(error => console.error(error)); // j'affiche l'erreur en cas d'échec de la requête
//             } else {
//                 // Je réinitialise l'élément où sont affichées les suggestions de communes
//                 communeSuggestions.innerHTML = '';
//             }
    
//         });
      
//     });


    











//  --------------------------------------------------------------------------------------


    // fetch api et suggestions 

// J'ajoute un événement clic sur le bouton d'ajout de ville intermédiaire
// addCityButton.click(function() {
//     cityIndex++;
//// Je récupère le prototype de la ville intermédiaire à partir de l'attribut data-prototype
//     var newCityPrototype = $('#custom_itinerary_intermediate_cities').data('prototype');

//     // Je remplace le texte "__name__" dans le prototype par l'index de la nouvelle ville intermédiaire
//     var newCityHtml = newCityPrototype.replace(/__name__/g, cityIndex);

//     // Je crée un nouvel élément div pour contenir le nouvel input
//     var newCityDiv = $('<div class="intermediate_city_container"></div>');
//     newCityDiv.html(newCityHtml);

//     // J'ajoute le bouton "Supprimer" à la nouvelle ville intermédiaire
//     var deleteCityButton = $('<button type="button" class="delete_city_button">Supprimer</button>');
//     newCityDiv.append(deleteCityButton);

//     // J'ajoute un écouteur d'événements sur le nouvel input de ville intermédiaire
//     var newCityInput = newCityDiv.find('input.intermediate_city');
//     newCityInput.on('input', function() {
//         // J'effectue la recherche de villes correspondantes et j'affiche les suggestions dans la liste <ul>
//         // code ville départ et ville arrivée 
//     });

//     // J'ajoute le nouvel élément div à la collection de villes intermédiaires
//     $('#custom_itinerary_intermediate_cities').append(newCityDiv);
// });


// --- écouteur d'événement sur l'input
// newCityInput.on('input', function() {
//// Je récupère la valeur entrée par l'utilisateur et je supprime les espaces avant et après
//     const search = newCityInput.val().trim();

// Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieure ou égale à 3
//     if (search.length >= 3) {
//         // J'effectue la recherche de villes correspondantes et j'affiche les suggestions dans la liste <ul>
//         const filteredData = ;
// //Je réinitialise la liste de suggestions
//         $('#intermediate_city_suggestions').empty();

//// J'ajoute les suggestions à la liste
//filteredData.forEach(city => {
//     const listItem = $('<li></li>');
//     listItem.text(city.nom);

//// J'ajoute un écouteur d'événements sur l'élément de suggestion
//     listItem.on('click', function() {
//// J'attribut la valeur sélectionnée à l'input de ville intermédiaire
//     newCityInput.val(city.nom);
//// J'ajoute un marqueur à la carte pour la ville sélectionnée
// const marker = L.marker([city.latitude, city.longitude]);
// marker.addTo(mapItinerary);

// J'ajoute le marqueur au tableau de marqueurs
// markers.push(marker);

// Si la ville sélectionnée n'est pas la dernière ville ajoutée, je trace une ligne entre la dernière ville et la ville sélectionnée
// if (markers.length > 1) {
//     const polyline = L.polyline([markers[markers.length - 2].getLatLng(), marker.getLatLng()]).addTo(mapItinerary);
//     polylines.push(polyline);
// }

// // Je réinitialise la liste de suggestions
// $('#intermediate_city_suggestions').empty();
