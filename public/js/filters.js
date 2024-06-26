window.onload = () => {
    const filtersForm = document.querySelector('#filters');
    console.log(filtersForm);

    // Je boucle sur les inputs
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change", () => {
            // console.log("clic");
            // J'intercepte les clicks
            // Je récupère chaque donnée de mon formulaire 
            const form = new FormData(filtersForm);
            // console.log(form);

            // je fabrique la "queryString" (partie après le '?' dans l'url)
            const params = new URLSearchParams();
            // console.log(params.toString())

            // J'ai besoin de la valeur et de la clé 
            form.forEach((value, key) => {
                // Grâce à urlsearchparams et append, je fabrique la "queryString" qui doit être envoyée
                params.append(key,value);

                // console.log(key, value)
            });

            // console.log(Object.keys(params.toString()).length === 0)

            // Je récupère l'url active
            const url = new URL(window.location.href);
            // console.log(url);

            // Je lance la requête ajax
            fetch(url.pathname + "?" + params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-with": "XMLHttpRequest"
                }
            // fetch retournant une promesse, je fais alors un then qui récupère la réponse
            }).then(response => {
                if(!response.ok){
                    throw new Error(response.statusText)
            }
                return response.json();
            }).then(data => {
                // Je récupère la zone de contenu
                const content = document.querySelector('#content');

                // Je remplace le contenuss
                content.innerHTML = data.content;
            }).catch(error => {
                console.error(error);
            })

        });
    });
}