
document.addEventListener('DOMContentLoaded', function() {

    const buttons = document.querySelectorAll('.favorite-button');
    console.log(buttons);


    buttons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log(this.dataset.itinerary)
            const itineraryId = this.dataset.itinerary;
            fetch(`/favorite/${itineraryId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.status === 'added'){
                    this.textContent = 'Retiré des favoris';
                } else {
                    this.textContent = 'Ajouté aux favoris';
                }
            });
        });
    });

});