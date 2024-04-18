

// ------------------ Commune suggestions --------------------

function initCommmune(){
    const communeInput = document.querySelector('#place_city');
    const communeSuggestions = document.querySelector('#commune-suggestions');
    
    communeInput.addEventListener('input', () => {
        const searchTerm = communeInput.value.trim();
    
        if (searchTerm.length >= 3) {
            fetch(`https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=nom,code,codeDepartement,codeRegion,codesPostaux,centre,codeDepartement&format=json`)
                .then(response => response.json())
                .then(data => {
                    communeSuggestions.innerHTML = '';
    
                    // Filtre les résultats en fonction du code du département
                    const filteredData = data.filter(commune => ['67', '68'].includes(commune.codeDepartement));
                    filteredData.forEach(commune => {
                        const listItem = document.createElement('li');
                        listItem.textContent = commune.nom;
                
                        listItem.addEventListener('click', () => {
                            communeInput.value = commune.nom;
    
                             // Vérifie la longueur du tableau 'codesPostaux' 
                             if (commune.codesPostaux.length === 1) {
                                // Si unique, alors j'ajoute sa valeur dans l'input du code postal
                                document.querySelector('#place_zipcode').value = commune.codesPostaux[0];
                            } else {
                                // Je crée un élément sélecteur pour sélectionner le bon code postal
                                const select = document.createElement('select');
                                select.name = 'place[zipcode]';
                                select.id = 'place_zipcode';
                            
                                // J'ajoute les options de codes postaux en parcourant le tableau
                                commune.codesPostaux.forEach(codePostal => {
                                    const option = document.createElement('option');
                                    option.value = codePostal;
                                    option.textContent = codePostal;
                                    select.appendChild(option);
                                });
                            
                                // Remplacer l'élément input existant par le nouvel élément sélecteur
                                const zipcodeInput = document.querySelector('#place_zipcode');
                                zipcodeInput.parentNode.replaceChild(select, zipcodeInput);
                            }
                        
                            communeSuggestions.innerHTML = '';
                        });
                        communeSuggestions.appendChild(listItem);
                    });
                })
                .catch(error => console.error(error));
        } else {
            communeSuggestions.innerHTML = '';
        }
    });
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        initCommmune();
    });

// ------- Address suggestions + autocomplete zipcode, latitude, longitude -------------------

// function initAddress(){
// const inputAddress = document.querySelector('#place_address');
// const inputLatitude = document.querySelector('#place_latitude');
// const inputLongitude = document.querySelector('#place_longitude');
// const inputSuggestions = document.querySelector('#address-suggestions');

// inputAddress.addEventListener('input', () => {
//     const searchTerm = inputAddress.value.trim();

//     if (searchTerm.length >= 3) {
//         fetch(`https://nominatim.openstreetmap.org/search?q=${searchTerm}&format=json&countrycodes=fr`)
//             .then(response => response.json())
//             .then(data => {
//                 inputSuggestions.innerHTML = '';

//                 data.forEach(result => {
//                     const listItem = document.createElement('li');
//                     listItem.textContent = result.display_name;

//                     listItem.addEventListener('click', () => {
//                         inputAddress.value = result.display_name;
//                         inputLatitude.value = result.lat;
//                         inputLongitude.value = result.lon;

//                         inputSuggestions.innerHTML = '';
//                     });
//                     inputSuggestions.appendChild(listItem);
//                 });
//             })
//             .catch(error => console.error(error));
//     } else {
//         inputSuggestions.innerHTML = '';
//     }
// });
// }

// document.addEventListener('DOMContentLoaded', () => {
//     initAddress();
// });