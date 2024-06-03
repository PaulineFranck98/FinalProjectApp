window.onload = () => {
    // Gestion des boutons "Supprimer" : je cherche tous les liens avec l'attribut 'data-delete'
    let links = document.querySelectorAll("[data-delete]")
    // console.log(links); 

    //Je parcours tous les liens récupérés avec une boucle for 
    for(let link of links){

        // Pour chaque lien, j'ajoute un écouteur d'évènements qui se déclenchera au click
        link.addEventListener("click", function(e){

            // J'utilise preventDefault pour empêcher la navigateur de suivre le lien vers la page de suppression
            // Cela me permet de gérer la suppression via une requête AJAX
            e.preventDefault()

            // Je demande confirmation avant de supprimer l'image
            if(confirm("Voulez-vous supprimer cette image ?")){
                // Si l'utilisateur confirme j'envoie une requête Ajax vers l'URL associée au lien cliqué
                // J'utilise la méthode fetch pour envoyer la requête AJAX
                fetch(this.getAttribute("href"), {

                    // J'utilise la méthode HTTP 'DELETE' 
                    method: "DELETE",

                    // Je définis les en-têtes pour inidiquer au serveur que la requête est envoyée via AJAX
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        // Je précise que le corps de requête est au format JSON
                        "Content-Type": "application/json"
                    },
                    //le corps de requête contient le jeton CSRF associé à l'élément cliqué
                    // Je le récupère avec la propriété dataset.token
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // une fois que la requête AJAX a été envoyée :
                    // une première promesse est résolue avec la méthode 'response.json() qui convertit le corps de réponse JSON en objet Javascript
                    response => response.json()
                    // un deuxième promesse est utilisée pour gérer l'objet Javascript récupéré
                ).then(data => {
                    // Si la propriété success de l'objet est définie sur true, la suppression de l'image a réussi sur le serveur
                    if(data.success)
                        // l'élément HTML parent (div) est supprimé en utilisant la méthode remove()
                        this.parentElement.remove();
                    else
                        // Sinon, une erreur s'est produite lors de la suppression
                        // J'affiche une boîte de dialogue d'alerte avec le message d'erreur associé.
                        alert(data.error);
                }).catch(e => alert(e))
            }
        })
    }
}
