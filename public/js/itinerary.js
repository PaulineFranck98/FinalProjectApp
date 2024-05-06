// J'initialise la carte Leaflet
const mapItinerary = L.map('map-itinerary').setView([48.267, 7.45], 8);

// J'ajoute une couche de tuiles OpenStreetMap à la carte 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapItinerary);



//Envoie une requête http get à l'url
async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}

    
    // Je récupère l'élément input où l'utilisateur entre le nom de la commune
    const communeInputs = document.querySelectorAll('#custom_itinerary_departure, #custom_itinerary_arrival');
    // je récupère l'élément où seront affichées les suggestions de communes
    const communeSuggestions = document.querySelector('#commune-with-places');

    // je récupère l'élément où seront stockés le code INSEE de la commune de départ
    const codeDeparture = document.querySelector('#custom_itinerary_codeDeparture');
    
    // je récupère l'élément où seront stockés le code INSEE de la commune d'arrivée
    const codeArrival = document.querySelector('#custom_itinerary_codeArrival');
  
    // 
    const polyline = L.polyline([]).addTo(mapItinerary);

    communeInputs.forEach(communeInput => {
        communeInput.addEventListener('input', async () => {
            
            // console.log(communeInput.id);

            // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
            const search = communeInput.value.trim();
            // console.log(communeInput);

            // Si la longueur de la chaîne de caractères entrée par l'utilisateur est supérieur ou égale à 3
            if (search.length >= 3)
            {
                const citiesWithPlaces = await getCitiesWithPlaces();
                
                // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
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

                                // } else if(communeInput.id === "custom_itinerary_arrival"){
                                } else if(inputType === "arrival")
                                {
                                    
                                    codeArrival.value = commune.code;
                                    // latlngs.push([lat,lng]);

                                    console.log('arrival : ' + lat, lng);
                                    
            
                                }

                                marker.addTo(mapItinerary);

                                polyline.addLatLng(marker.getLatLng());
                        
                                communeSuggestions.innerHTML = '';
                            });
                

                            // J'ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                            communeSuggestions.appendChild(listItem);
                        
                        });
                    
                    
                    })
                    .catch(error => console.error(error)); // j'affiche l'erreur en cas d'échec de la requête
            } else {
                // Je réinitialise l'élément où sont affichées les suggestions de communes
                communeSuggestions.innerHTML = '';
            }
    
        });
      
    });

 

