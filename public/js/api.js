

// ------------------ Commune suggestions --------------------

// Je déclare la fonction InitCommune pour l'appeler après et permettre le chargement complet du DOM
function initCommmune(){
    // Je récupère l'élément input où l'utilisateur entre le nom de la commune
    const communeInput = document.querySelector('#place_city');
    // je récupère l'élément où seront affichés les suggestions de communes
    const communeSuggestions = document.querySelector('#commune-suggestions');
    
    // J'ajoute une écouteur d'évènement sur l'élément input qui se déclenchera à chaque fois que l'utilisateur remplira cet input 
    communeInput.addEventListener('input', () => {
        // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
        const searchTerm = communeInput.value.trim();
        
        // Si la longueur de la string entrée par l'utilisateur est supérieur ou égale à 3
        if (searchTerm.length >= 3) {
            // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
            fetch(`https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=nom,codesPostaux,centre,codeDepartement&format=json`)
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
                        listItem.textContent = commune.nom;
                        
                        // J'ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click 
                        listItem.addEventListener('click', () => {
                            // Je remplis l'élément input avec le nom de la commune sélectionnée
                            communeInput.value = commune.nom;
    
                             // Je vérifie la longueur du tableau contenant les codes postaux de la commune sélectionnée 
                             if (commune.codesPostaux.length === 1) {
                                // S'il est unique, alors je remplis l'input avec la valeur du code postal
                                document.querySelector('#place_zipcode').value = commune.codesPostaux[0];
                            } else {
                                // Sinon je crée un élément select pour sélectionner le bon code postal
                                // J'utilise la méthode createElement de l'objet document
                                const select = document.createElement('select');
                                // Je définis la propriété name de l'élément select pour lier la valeur sélectionnée au champ 'zipcode'
                                select.name = 'place[zipcode]';
                                // Je définis la propriété id de l'élément select pour récupérer l'élément select
                                select.id = 'place_zipcode';
                            
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
                                const zipcodeInput = document.querySelector('#place_zipcode');
                                // J'accède au parent de l'élément qui contient l'input avec parentNode
                                // replaceChild prend 2 arguments : le nouvel élément et l'élément existant
                                // Je remplace l'élément input existant par le nouvel élément select
                                zipcodeInput.parentNode.replaceChild(select, zipcodeInput);
                            }
                            // Je réinitialise l'élément où sont affichées les suggestions de communes
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
}
    // J'appelle la fonction initCommune lorsque le DOM est entièrement chargé
    document.addEventListener('DOMContentLoaded', () => {
        initCommmune();
    });


// --------- Formulaire Ajout Lieu Map --------------------------

// Je déclare une variable mapInitialized pour vérifier si la carte a été initialisée
let mapInitialized = false;
// Je déclare une variable marker pour stocker le marqueur sur la carte
let marker = null;
// Je déclare une variable mapNew pour stocker la map
let mapNew;

// Je définis une fonction initMapNew pour initialiser la carte
function initMapNew(){
    // Je vérifie si la map a déjà été initialisée
    if(!mapInitialized){
        // Je stocke la nouvelle instance de map Leaflet dans la variable mapNew
        mapNew = L.map('map-new').setView([47.9458, 7.3572], 9);

        // J'ajoute une couche de tuiles OpenStreetMap à la carte 
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapNew);

        // Je mets à jour la variable mapInitialized pour indiquer que la map a été initialisée
        mapInitialized = true;
    }
    // Je récupère l'élément input où l'utilisateur entre la latitude 
    const inputLatitude = document.querySelector('#place_latitude');
    // Je récupère l'élément input où l'utilisateur entre la longitude 
    const inputLongitude = document.querySelector('#place_longitude');

    // J'ajoute un écouteur d'événement pour les changements dans l'input de latitude
    inputLatitude.addEventListener('change', updateMarker);
    // J'ajoute un écouteur d'événement pour les changements dans l'input de longitude
    inputLongitude.addEventListener('change', updateMarker);

    // Je définis la fonction updateMaker pour mettre à jour le marqueur sur la map
    function updateMarker() {
        // Je récupère la valeur de latitude et je la convertis en float
        const selectedLatitude = parseFloat(inputLatitude.value);
        // Je récupère la valeur de longitude et je la convertis en float
        const selectedLongitude = parseFloat(inputLongitude.value);

        // Je vérifie que les valeurs de latitude et longitude sont des nombres valides
        if (!isNaN(selectedLatitude) && !isNaN(selectedLongitude)) {

            // Je supprime le marqueur précédent s'il existe
            if (marker !== null) {
                mapNew.removeLayer(marker);
            }

            // J'ajoute un nouveau marqueur à la carte à la position entrée
            const position = [selectedLatitude, selectedLongitude];
            marker = L.marker(position).addTo(mapNew)
            .bindPopup(`<strong>Je suis là ! Suis-je bien placé ?</strong>`)
            .openPopup();

            // Je centre la carte sur la position entrée
            mapNew.setView(position);
        }
    }
}
    // J'appelle la fonction initMapNew lorsque le DOM est entièrement chargé
    document.addEventListener('DOMContentLoaded', () => {
    initMapNew();
});
