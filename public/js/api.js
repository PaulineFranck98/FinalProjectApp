
// ------------------ Commune suggestions --------------------

    
    // Je récupère l'élément input où l'utilisateur entre le nom de la commune et je le stocke dans la constante 'communeInput'
    const communeInput = document.getElementById('place_cityName');

    // Je récupère l'élément input où le code INSEE de la commune sera stocké
    const codeCommuneInput = document.getElementById('place_cityCodeId');


    // Je récupère l'élément ul où seront affichées les suggestions de communes
    const communeSuggestions = document.getElementById('commune-suggestions');
    
    // Je récupère l'élément input où le code postal sera stocké
    let postCode = document.getElementById('place_zipcode');
    
    // J'ajoute une écouteur d'événement sur l'élément input qui se déclenchera à chaque fois que l'utilisateur entrera du texte
    communeInput.addEventListener('input', () => {
        // Je récupère la valeur entrée par l'utilisateur en supprimant les espaces avant et après, et je la stocke dans la constante 'search'
        const search = communeInput.value.trim();
       
        // Je vérifie si la chaîne entrée par l'utilisateur est supérieure ou égale à 3
        if (search.length >= 3) {


            // J'envoie une requête à l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
            fetch(`https://geo.api.gouv.fr/communes?nom=${search}&limit=3`)
            .then(response => response.json()) // Je convertis la réponse en JSON
            .then(data => {
                // Je réinitialise l'élément où sont affichées les suggestions de communes
                communeSuggestions.innerHTML = '';

                // Je filtre les résultats pour ne garder que les communes des départements 67 et 68 en utilisant la fonction filter() et includes()
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
                        // J'affecte le nom de la commune sélectionnée à l'élément input de la commune
                        communeInput.value = commune.nom;
                        // J'affecte le code INSEE de la commune sélectionnée à l'élément input du code INSEE
                        codeCommuneInput.value = commune.code;

                        // Je vérifie la longueur du tableau contenant les codes postaux de la commune sélectionnée (si elle ne possède qu'un code postal)
                        if (commune.codesPostaux.length === 1) {
                            // S'il est unique, alors j'affecte la valeur du code postal de la commune sélectionnée à l'élément input du code postal
                            postCode.value = commune.codesPostaux[0];
                        } else {
                            // Sinon je crée un élément select pour permettre à l'utilisateur de sélectionner le bon code postal
                            // J'utilise la méthode createElement() de l'objet document
                            const select = document.createElement('select');
                            // Je définis la propriété name de l'élément select pour lier la valeur sélectionnée au champ 'zipcode'
                            select.name = 'place[zipcode]';
                            // Je définis la propriété id de l'élément select pour récupérer l'élément select
                            select.id = 'place_zipcode';

                            // J'ajoute un écouteur d'événement sur l'élément select qui se déclenchera au changement de valeur
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

// Je récupère l'élément input où l'utilisateur entre l'adresse du lieu, et je le stocke dans la constante 'inputAddress'
const inputAdresse = document.getElementById('place_address');
// Je récupère l'élément input caché où la latitude du lieu sera stockée 
const inputLatitude = document.getElementById('place_latitude');
// Je récupère l'élément input caché où la longitude du lieu sera stockée 
const inputLongitude = document.getElementById('place_longitude');
 // Je récupère l'élément ul où seront affichées les suggestions d'adresses
const inputSuggestions = document.getElementById('address-suggestions');

// J'ajoute d'un écouteur d'événement sur l'élément input d'adresse, qui se déclenchera à chaque fois que l'utilisateur entrera du texte
inputAdresse.addEventListener('input', () => {
     // Je récupère la valeur entrée par l'utilisateur en supprimant les espaces avant et après, et je la stocke dans la constante 'search'
    const search = inputAdresse.value.trim();
     // Je vérifie si la chaîne entrée par l'utilisateur est supérieur ou égale à 5
    if (search.length >= 5) {
        // J'envoie une requête à l'API Adresse pour récupérer les adresses correspondant à la recherche effectuée
        fetch(`https://api-adresse.data.gouv.fr/search/?q=${search}&postcode=${postCode.value}&autocomplete=1&limit=3`, {mode: 'cors'})
        .then(response => response.json())
        .then(data => {
                // Je réinitialise l'élément où sont affichées les suggestions d'adresse
                inputSuggestions.innerHTML = '';
                
                // Je parcours les adresses récupérées
                data.features.forEach( feature => { 
                // Pour chaque adresse, je crée un élément li qui contient le nom et la ville de l'adresse
                const listItem = document.createElement('li');
                listItem.textContent = feature.properties.name + ' ' + feature.properties.city;

                // J'ajoute un écouteur d'événement  sur l'élément li qui se déclenchera au click  
                listItem.addEventListener('click', () => {

                    // J'affecte le nom de l'adresse sélectionnée à l'élément input d'adresse
                    inputAdresse.value = feature.properties.name;
                    // J'affecte la latitude de l'adresse sélectionnée à l'élément input de latitude
                    inputLatitude.value = feature.geometry.coordinates[1];
                    // J'affecte la longitude de l'adresse sélectionnée à l'élément input de longitude
                    inputLongitude.value = feature.geometry.coordinates[0];
                    // Je réinitialise l'élément où sont affichées les suggestions d'adresse
                    inputSuggestions.innerHTML = '';
                });
                // J'ajoute l'élément li à l'élément ul où sont affichées les suggestions d'adresses avec la méthode appendChild()
                inputSuggestions.appendChild(listItem);
            });
        })
    //         .catch(error => console.error(error));
    } else {
        // Je réinitialise l'élément où sont affichées les suggestions d'adresse
        inputSuggestions.innerHTML = '';
    }
});



// --------- Formulaire Ajout Lieu Map --------------------------


// Je déclare une variable marker pour stocker le marqueur sur la carte
let marker;

    // Je crée une nouvelle instance de carte Leaflet et je la stocke dans la constante mapNew
    const mapNew = L.map('map-new').setView([47.9458, 7.3572], 9);

    // J'ajoute une couche de tuiles OpenStreetMap à la carte 
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapNew);

    // J'ajoute une évènement au click sur la liste de suggestions de communes
    inputSuggestions.addEventListener('click', () => {

    // Je récupère la valeur de la latitude et je la stocke dans la constante selectedLatitude
    const selectedLatitude = inputLatitude.value;
    
    // Je récupère la valeur de longitude et je la stocke dans la constante selectedLongitude
    const selectedLongitude = inputLongitude.value;
    
    // Je vérifie si un marqueur existe déjà sur la carte
    if (marker) {
        //S'il existe, j'appelle la méthode removeLayer() avec la référence au marqueur passée en argument pour le retirer de la carte
        mapNew.removeLayer(marker);
    } 

    // Je stocke la valeur de latitude et la valeur de la longitude dans un tableau nommé 'position'
    const position = [selectedLatitude, selectedLongitude];
 
    // Je crée un nouveau marqueur avec les coordonnées de la variable position 
    marker =  new L.marker(position);

    // J'ajoute le marqueur à la carte via une layer 
    mapNew.addLayer(marker);
    // J'ajoute un popup au marqueur en utilisant la méthode bindPopup() et je l'affiche en appelant la méthode openPopup()
    marker.bindPopup(`<strong>Je suis là ! Suis-je bien placé ?</strong>`)
    .openPopup();

    // Je centre la carte sur la position entrée en utilisant la méthode setView sur l'instance de la carte
    mapNew.setView(position);
    
    });






