// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);

// const coordinates = [
//     [47.7527, 7.3256], 
//     [48.4546, 7.4817]
// ];

// const line = L.polyline(coordinates, {color: 'black'}).addTo(mapItinerary);
//Envoie une requête http get à l'url
async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}
// function initCitiesWithPlaces() {
    
    // Je récupère l'élément input où l'utilisateur entre le nom de la commune
    const communeInputs = document.querySelectorAll('#custom_itinerary_departure, #custom_itinerary_arrival');
    // je récupère l'élément où seront affichés les suggestions de communes
    const communeSuggestions = document.querySelector('#commune-with-places');
    
    const codeDeparture = document.querySelector('#custom_itinerary_codeDeparture');
    
    const codeArrival = document.querySelector('#custom_itinerary_codeArrival');
    // console.log(codeArrival.id);
    // console.log(communeInputs[0].id);
    let latlngs = [];
    // console.log(latlngs.length);

    // if (latlngs.length >= 2) {
    //     for (let i = 1; i < latlngs.length - 1; i++) {
        
    //         // if (prevLatLng && currentLatLng && prevLatLng.lat !== undefined && prevLatLng.lng !== undefined && currentLatLng.lat !== undefined && currentLatLng.lng !== undefined) {
    //             const line = L.polyline([latlngs[i], latlngs[i+1]], { color: 'black' }).addTo(mapItinerary);
    //             // line.addTo(mapItinerary);
    //             mapItinerary.fitBounds(line.getBounds());
    //         // }
    //     }
    // }
    communeInputs.forEach(communeInput => {
        communeInput.addEventListener('input', async () => {
            
            // console.log(communeInput.id);
            // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
            const search = communeInput.value.trim();
            // console.log(communeInput);
            // Si la longueur de la string entrée par l'utilisateur est supérieur ou égale à 3
            if (search.length >= 3) {
                
                const citiesWithPlaces = await getCitiesWithPlaces();
                
                // console.log('cities with places : ', citiesWithPlaces);
            // let latlngs = [];
            // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
            // fetch(`https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=nom,codesPostaux,centre,code,codeDepartement&format=json`)
            fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
                .then(response => response.json()) // Je convertis la réponse en JSON
                .then(data => {
                    // Je réinitialise l'élément où sont affichées les suggestions de communes
                    communeSuggestions.innerHTML = '';
    
                    // Je filtre les résultats 
                    const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom))
                    // Pour chaque commune filtrée
                        // console.log('données', filteredData);

                    filteredData.forEach(commune => {
                        // Je crée un élément li qui contient le nom de la commune
                        const listItem = document.createElement('li');
                        listItem.textContent = commune.nom;

                        let lat = commune.centre.coordinates[1];
                        let lng = commune.centre.coordinates[0];

                        const marker = L.marker([lat,lng]);
                        // console.log(listItem);
                        // J'ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click 
                        listItem.addEventListener('click', () => {
                            // J'attribue comme valeur à l'input le nom de la commune sélectionnée
                            communeInput.value = commune.nom; 
                            let inputType = communeInput.getAttribute('data-type');

                            // if(communeInput.id === "custom_itinerary_departure")
                            if( inputType === "departure")
                            {  
                                codeDeparture.value = commune.code;
                                // latlngs.push([lat,lng]);

                                console.log('departure : ' + lat,lng);
                                // latlngs[0] = [lat, lng];

                            // } else if(communeInput.id === "custom_itinerary_arrival"){
                            } else if(inputType === "arrival")
                            {
                                  
                                codeArrival.value = commune.code;
                                // latlngs.push([lat,lng]);

                                console.log('arrival : ' + lat, lng);
                                // latlngs[1]=[lat, lng];
                                
          
                            }

                            marker.addTo(mapItinerary);
                            latlngs.push([lat,lng]);
                            console.log(latlngs.length);
                            // if (latlngs.length >= 2) {
                            //     const line = L.polyline(latlngs, { color: 'black' }).addTo(mapItinerary);
                            //     // mapItinerary.fitBounds(line.getBounds());
                            // }
                            // latlngs[] = [lat, lng];

                            // console.log(latlngs.length);
                            // console.log(coordinates);
                           
                            

                            // if (latlngs.length >= 2) {
                            //     for (let i = 1; i < latlngs.length - 1; i++) {
                                
                            //         // if (prevLatLng && currentLatLng && prevLatLng.lat !== undefined && prevLatLng.lng !== undefined && currentLatLng.lat !== undefined && currentLatLng.lng !== undefined) {
                            //             const line = L.polyline([latlngs[i], latlngs[i+1]], { color: 'black' }).addTo(mapItinerary);
                            //             // line.addTo(mapItinerary);
                            //             mapItinerary.fitBounds(line.getBounds());
                            //         // }
                            //     }
                            // }
                            // console.log(codeDeparture.value, codeArrival.value);
                            
                            // Je réinitialise l'élément où sont affichées les suggestions de communes
                            communeSuggestions.innerHTML = '';
                        });

                        // J'ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                        communeSuggestions.appendChild(listItem);

                        // console.log(communeSuggestions);
                    });
                    console.log(latlngs.length);
                    // if (latlngs.length >= 2) {
                    //     const line = L.polyline(latlngs, { color: 'black' }).addTo(mapItinerary);
                    //     // mapItinerary.fitBounds(line.getBounds());
                    // }
                    // if (latlngs.length >= 2) {
                    //     const linePoints = latlngs.map(coord => L.latLng(coord[0], coord[1]));
                    //     const line = L.polyline(linePoints, { color: 'black' }).addTo(mapItinerary);
                    //     // mapItinerary.fitBounds(line.getBounds());
                    // }
                })
                .catch(error => console.error(error)); // j'affiche l'erreur en cas d'échec de la requête
            } else {
                // Je réinitialise l'élément où sont affichées les suggestions de communes
                communeSuggestions.innerHTML = '';
            }
        
            // if (latlngs.length >= 2) {
            //     for (let i = 1; i < latlngs.length - 1; i++) {
                
            //         // if (prevLatLng && currentLatLng && prevLatLng.lat !== undefined && prevLatLng.lng !== undefined && currentLatLng.lat !== undefined && currentLatLng.lng !== undefined) {
            //             const line = L.polyline([latlngs[i], latlngs[i+1]], { color: 'black' }).addTo(mapItinerary);
            //             // line.addTo(mapItinerary);
            //             mapItinerary.fitBounds(line.getBounds());
            //         // }
            //     }
            // }
            // const line = L.polyline(latlngs, {color: 'black'}).addTo(mapItinerary);
        });
      
    });
  


// }
//     // J'appelle la fonction initCommune lorsque le DOM est entièrement chargé
//     document.addEventListener('DOMContentLoaded', () => {

//         initCitiesWithPlaces();
//     });

// ---------------------------------------------------------------------


// L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 19,
//     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
// }).addTo(mapItinerary);

// // const coordinates = [
// //     [47.7527, 7.3256], 
// //     [48.4546, 7.4817]
// // ];

// // const line = L.polyline(coordinates, {color: 'black'}).addTo(mapItinerary);

// async function getCitiesWithPlaces() {
//     const response = await fetch('/place/cities_with_places'); 
//     const data = await response.json();
//     return data;
// }

//     const communeInputs = document.querySelectorAll('#custom_itinerary_departure, #custom_itinerary_arrival');
//     const communeSuggestions = document.querySelector('#commune-with-places');
    
//     const codeDeparture = document.querySelector('#custom_itinerary_codeDeparture');
    
//     const codeArrival = document.querySelector('#custom_itinerary_codeArrival');
 
//     let latlngs = [];
   
//     communeInputs.forEach(communeInput => {
//         communeInput.addEventListener('input', async () => {
            
//             const search = communeInput.value.trim();
//             if (search.length >= 3) {
                
//                 const citiesWithPlaces = await getCitiesWithPlaces();
                
//             fetch(`https://geo.api.gouv.fr/communes?nom=${search}&fields=nom,code,centre&limit=3`)
//                 .then(response => response.json()) // Je convertis la réponse en JSON
//                 .then(data => {
//                     communeSuggestions.innerHTML = '';
    
//                     const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom))

//                     filteredData.forEach(commune => {
//                         const listItem = document.createElement('li');
//                         listItem.textContent = commune.nom;

//                         let lat = commune.centre.coordinates[1];
//                         let lng = commune.centre.coordinates[0];

//                         const marker = L.marker([lat,lng]);
//                         listItem.addEventListener('click', () => {
//                             communeInput.value = commune.nom; 
//                             let inputType = communeInput.getAttribute('data-type');

//                             if( inputType === "departure")
//                             {  
//                                 codeDeparture.value = commune.code;

//                                 console.log('departure : ' + lat,lng);

//                             } else if(inputType === "arrival")
//                             {
                                  
//                                 codeArrival.value = commune.code;

//                                 console.log('arrival : ' + lat, lng);
                                
//                             }

//                             marker.addTo(mapItinerary);
//                             latlngs.push([lat,lng]);
                       
//                             communeSuggestions.innerHTML = '';
//                         });

//                         communeSuggestions.appendChild(listItem);

//                         console.log(latlngs.length);
//                     });
//                 })
//                 .catch(error => console.error(error)); // j'affiche l'erreur en cas d'échec de la requête
//             } else {
//                 communeSuggestions.innerHTML = '';
//             }
    
//         });
      
//     });
//     if (latlngs.length >= 2) {
//         const line = L.polyline(latlngs, { color: 'black' }).addTo(mapItinerary);
//         mapItinerary.fitBounds(line.getBounds());
//     }