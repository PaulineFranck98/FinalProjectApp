//Envoie une requête http get à l'url
async function getCitiesWithPlaces() {
    const response = await fetch('/place/cities_with_places'); 
    const data = await response.json();
    return data;
}
function initCitiesWithPlaces() {

    // Je récupère l'élément input où l'utilisateur entre le nom de la commune
    const communeInputs = document.querySelectorAll('#custom_itinerary_departure, #custom_itinerary_arrival');
    // je récupère l'élément où seront affichés les suggestions de communes
    const communeSuggestions = document.querySelector('#commune-with-places');

    communeInputs.forEach(communeInput => {
    communeInput.addEventListener('input', async () => {
        // Je récupère la valeur entrée par l'utilisateur et supprime les espaces avant et après
        const searchTerm = communeInput.value.trim();
        // console.log(communeInput);
        // Si la longueur de la string entrée par l'utilisateur est supérieur ou égale à 3
        if (searchTerm.length >= 3) {

            const citiesWithPlaces = await getCitiesWithPlaces();

            // console.log('cities with places : ', citiesWithPlaces);

            // J'effectue une requête API vers l'API Geo Gouv pour récupérer les communes correspondant à la recherche effectuée
            fetch(`https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=nom,codesPostaux,centre,code,codeDepartement&format=json`)
                .then(response => response.json()) // Je convertis la réponse en JSON
                .then(data => {
                    // Je réinitialise l'élément où sont affichées les suggestions de communes
                    communeSuggestions.innerHTML = '';
    
                    // Je filtre les résultats pour ne garder que les communes des départements 67 et 68
                    const filteredData = data.filter(commune => citiesWithPlaces.some(city => city.city === commune.nom))
                    // Pour chaque commune filtrée
                        console.log('données', filteredData);

                    filteredData.forEach(commune => {
                        // Je crée un élément li qui contient le nom de la commune
                        const listItem = document.createElement('li');
                        listItem.textContent = commune.nom;
                        // console.log(listItem);
                        // J'ajoute un écouteur d'événement sur l'élément li qui se déclenchera au click 
                        listItem.addEventListener('click', () => {
                            // Je remplis l'élément input avec le nom de la commune sélectionnée
                            communeInput.value = commune.nom;

                            communeInput.setAttribute('code', commune.code);
                            console.log(communeInput.setAttribute('code', commune.code));
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
});
}
    // J'appelle la fonction initCommune lorsque le DOM est entièrement chargé
    document.addEventListener('DOMContentLoaded', () => {

        initCitiesWithPlaces();
    });