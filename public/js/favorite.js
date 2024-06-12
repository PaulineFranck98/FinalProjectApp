
document.addEventListener('DOMContentLoaded', function() {

    const buttons = document.querySelectorAll('.favorite-button');
    console.log(buttons);


    buttons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log(this.dataset.itinerary)
            const itineraryId = this.dataset.itinerary;
            const buttonElement = this;
            fetch(`/favorite/${itineraryId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.status === 'added'){
                    buttonElement.textContent = 'Retiré des favoris';
                } else {
                    buttonElement.textContent = 'Ajouté aux favoris';
                }
            });
        });
    });

});


