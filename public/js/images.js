window.onload = () => {
    // Gestion des boutons "Supprimer" : je cherche tous les liens avec l'attribut 'data-delete'
    let links = document.querySelectorAll("[data-delete]")
    // console.log(links); 

    // On boucle sur liens 
    for(let link of links){

        // On met un écouteur d'évènements
        link.addEventListener("click", function(e){

            // On empêche la navigation : je ne veux pas envoyer directement vers la page du delete, je veux utiliser ajax
            // je dis mon évènement e de ne paspas le faire 
            e.preventDefault()

            // On demande confirmation
            if(confirm("Voulez-vous supprimer cette image ?")){

                // On envoie une requête Ajax vers le href du lien avec la méthode correspondante
                // je recherche l'élément href
                fetch(this.getAttribute("href"), {

                    // J'utilise la méthode DELETE
                    method: "DELETE",

                    // J'envoie des headers
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        // On précise qu'on envoie du json
                        "Content-Type": "application/json"
                    },
                    // On envoie body -> on envoie le token
                    // On prend sur le lien qui a été cliqué  le dataset.token
                    // cette partie envoie la requête
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // Si ça fonctionne on récupère la réponse en JSON
                    response => response.json()
                    // Une fois qu'on a la réponse
                ).then(data => {
                    // Je vérifie si ça a fonctionné
                    if(data.success)
                        // parentElement représente la div : élément parent du lien
                        this.parentElement.remove();
                    else
                        alert(data.error);
                }).catch(e => alert(e))
            }
        })
    }
}
