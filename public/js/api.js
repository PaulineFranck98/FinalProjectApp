
// ------------------ Commune suggestions --------------------

    
    // Je récupère l'élément input où l'utilisateur entre le nom de la commune
    const communeInput = document.querySelector('#place_cityName');

    // Je récupère l'élément input où le code de la commune sera stocké
    const codeCommuneInput = document.querySelector('#place_cityCodeId');


    // Je récupère l'élément où seront affichées les suggestions de communes
    const communeSuggestions = document.querySelector('#commune-suggestions');
    
    let postCode = document.getElementById('place_zipcode');
    
    // J'ajoute une écouteur d'évènement sur l'élément input qui se déclenchera à chaque fois que l'utilisateur remplira cet input 
    communeInput.addEventListener('input', () => {
        // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
        const search = communeInput.value.trim();
        // console.log(communeInput);
        // Si la longueur de la string entrée par l'utilisateur est supérieur ou égale à 3
        if (search.length >= 3) {


            // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
            fetch(`https://geo.api.gouv.fr/communes?nom=${search}&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {
                // Je réinitialise l'élément où sont affichées les suggestions de communes
                communeSuggestions.innerHTML = '';

                // Je filtre les résultats pour ne garder que les communes des départements 67 et 68
                const filteredData = data.filter(commune => ['67', '68'].includes(commune.codeDepartement));
                
                // Pour chaque commune filtrée
                filteredData.forEach(commune => {
                    // Je crée un élément li qui contient le nom de la commune
                    const listItem = document.createElement('li');
                    // Je définis le contenu texte de l'élément li sur le nom de la commune 
                    listItem.textContent = commune.nom;
                    // console.log(listItem);
                    // J'ajoute un écouteur d'événement  sur l'élément li qui se déclenchera au click 
                    listItem.addEventListener('click', () => {
                        // Je remplis l'élément input avec le nom de la commune sélectionnée
                        communeInput.value = commune.nom;

                        codeCommuneInput.value = commune.code;

                        // Je vérifie la longueur du tableau contenant les codes postaux de la commune sélectionnée 
                        if (commune.codesPostaux.length === 1) {
                            // S'il est unique, alors je remplis l'input avec la valeur du code postal
                            postCode.value = commune.codesPostaux[0];
                        } else {
                            // Sinon je crée un élément select pour sélectionner le bon code postal
                            // J'utilise la méthode createElement de l'objet document
                            const select = document.createElement('select');
                            // Je définis la propriété name de l'élément select pour lier la valeur sélectionnée au champ 'zipcode'
                            select.name = 'place[zipcode]';
                            // Je définis la propriété id de l'élément select pour récupérer l'élément select
                            select.id = 'place_zipcode';

                            select.addEventListener('change', () => {
                                postCode = select;
                                postCode.value = select.value;
                            });
                        
                            // J'ajoute les options de codes postaux en parcourant le tableau codesPostaux
                            commune.codesPostaux.forEach(codePostal => {
                                // Pour chaque élément du tableau je crée un nouvel élément option
                                const option = document.createElement('option');
                                // Je définis la valeur de l'option
                                option.value = codePostal;
                                // Je définis le texte de l'option
                                option.textContent = codePostal;
                                // J'ajoute l'option créée à la liste des codes postaux de la commune
                                select.appendChild(option);
                            });
                        
                            // Je remplace l'élément input existant par le nouvel élément select
                            // J'accède au parent de l'élément qui contient l'input avec parentNode
                            // replaceChild prend 2 arguments : le nouvel élément et l'élément existant
                            // Je remplace l'élément input existant par le nouvel élément select
                            postCode.parentNode.replaceChild(select, postCode);
                            postCode = select;
                        }
                        // Je réinitialise l'élément où sont affichées les suggestions de communes
                        communeSuggestions.innerHTML = '';
                    });
                    // J'ajoute l'élément li à l'élément ul où sont affichées les suggestions de communes
                    communeSuggestions.appendChild(listItem);
                    // console.log(communeSuggestions);
                });
            })
            .catch(error => console.error(error)); // j'affiche l'erreur en cas d'échec de la requête
        } else {
            // Je réinitialise l'élément où sont affichées les suggestions de communes
            communeSuggestions.innerHTML = '';
        }
    });

// ------- Suggestions Adresse + autocomplétion code postal, latitude, longitude -------------------

const inputAdresse = document.querySelector('#place_address');
const inputLatitude = document.querySelector('#place_latitude');
const inputLongitude = document.querySelector('#place_longitude');
const inputSuggestions = document.querySelector('#address-suggestions');

inputAdresse.addEventListener('input', () => {
    const search = inputAdresse.value.trim();
    // console.log(postCode.value);
    if (search.length >= 5) {
        fetch(`https://api-adresse.data.gouv.fr/search/?q=${search}&postcode=${postCode.value}&autocomplete=1&limit=3`, {mode: 'cors'})
        .then(response => response.json())
        .then(data => {
            // data.features.forEach( feature => { console.log(feature.properties.name, feature.properties.city)});
                // console.log(data.features.properties.name);

            
                inputSuggestions.innerHTML = '';
                // console.log(data);
                // data.forEach(result => {
                    data.features.forEach( feature => { 
                    const listItem = document.createElement('li');
                    listItem.textContent = feature.properties.name + ' ' + feature.properties.city;

                    listItem.addEventListener('click', () => {
                        inputAdresse.value = feature.properties.name;
                        inputLatitude.value = feature.geometry.coordinates[1];
                        inputLongitude.value = feature.geometry.coordinates[0];

                        inputSuggestions.innerHTML = '';
                    });
                    inputSuggestions.appendChild(listItem);
                });
            })
    //         .catch(error => console.error(error));
    } else {
        inputSuggestions.innerHTML = '';
    }
});



// --------- Formulaire Ajout Lieu Map --------------------------


// Je déclare une variable marker pour stocker le marqueur sur la carte
let marker;

    // Je stocke la nouvelle instance de map Leaflet dans la variable mapNew
    const mapNew = L.map('map-new').setView([47.9458, 7.3572], 9);

    // J'ajoute une couche de tuiles OpenStreetMap à la carte 
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapNew);

    inputSuggestions.addEventListener('click', (event) => {

    event.target.textContent;

    // Je récupère la valeur de latitude et je la convertis en float
    const selectedLatitude = parseFloat(inputLatitude.value);
    // Je récupère la valeur de longitude et je la convertis en float
    const selectedLongitude = parseFloat(inputLongitude.value);

    // Je vérifie si un marqueur existe déjà
    if (marker) {
        // Je supprime 
        mapNew.removeLayer(marker);
    } 

    // Je stocke la valeur de latitude et la valeur de la longitude dans la variable position
    const position = [selectedLatitude, selectedLongitude];
    // console.log(position);
    // Je crée un nouveau marqueur avec les coordonnées de la variable position 
    marker =  new L.marker(position);
    // J'ajoute le marqueur à la carte via une layer 
    mapNew.addLayer(marker);
    // J'ajoute un popup et je l'affiche 
    marker.bindPopup(`<strong>Je suis là ! Suis-je bien placé ?</strong>`)
    .openPopup();

    // Je centre la carte sur la position entrée
    mapNew.setView(position);
    
    });






