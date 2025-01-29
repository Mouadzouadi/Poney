function openBookingPopup(courseInfo) {
    // Met à jour le contenu du popup
    updatePopupContent(courseInfo);

    // Charge les poneys disponibles
    fetchPoneyDispo(courseInfo.date, courseInfo.heure);

    // Affiche le popup
    togglePopupVisibility(true);
}

function updatePopupContent(courseInfo) {
    const popupTitle = document.getElementById('popup-title');
    const courseInfoElem = document.getElementById('popup-course-info');
    const dateTimeElem = document.getElementById('popup-date-time');
    let places_restantes = courseInfo.nb_personnes_max - courseInfo.participants.length;
    console.log(places_restantes);

    popupTitle.textContent = `Réservation pour le cours : ${courseInfo.nom_cours}`;
    courseInfoElem.innerHTML = `
        <p><strong>Cours :</strong> ${courseInfo.nom_cours}</p>
        <p><strong>Moniteur :</strong> ${courseInfo.moniteur}</p>
        <p><strong>Places restantes:</strong> ${places_restantes}</p>
        <p><strong> Prix :</strong> ${courseInfo.prix} €
        <p><strong>Niveau:</strong> ${courseInfo.niveau}</p>
       <p><strong>Liste des Participants :</strong></p>
    `;

    const select = document.createElement('select');

    if (courseInfo.participants && courseInfo.participants.length > 0) {
        courseInfo.participants.forEach(participant => {
            const option = document.createElement('option');
            option.value = participant.nom + ' ' + participant.prenom;
            option.textContent = participant.nom + ' ' + participant.prenom;
            select.appendChild(option);
        });
    } else {
        const option = document.createElement('option');
        option.textContent = "Aucun participant";
        select.appendChild(option);
    }

    courseInfoElem.appendChild(select);


    dateTimeElem.textContent = `${courseInfo.date} de ${courseInfo.heure} à ${courseInfo.heureFin}`;

    document.getElementById('id_cours').value = courseInfo.id_cours;
    document.getElementById('id_user').value = courseInfo.id_user;
    document.getElementById('dateC').value = `${courseInfo.date} ${courseInfo.heure}`;

    const bookingForm = document.getElementById('booking-form');
    bookingForm.dataset.courseName = courseInfo.nom_cours;
    bookingForm.dataset.coursePrice = courseInfo.prix;
    bookingForm.dataset.courseNiveau = courseInfo.niveau;
}

function fetchPoneyDispo(date, heure) {
    fetch('/App/Controllers/Planning/PlanningDB.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'getPoneyDispo',
            date,
            heure
        })
    })
        .then(response => response.json())
        .then(data => handlePoneyDispoResponse(data))
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la récupération des poneys disponibles.');
        });
}

function updatePlaces_restantes(){
    
}

function handlePoneyDispoResponse(data) {
    if (data.success) {
        const poneys = data.poneys;
        const poneySelect = document.getElementById('poney_dispo');
        poneySelect.innerHTML = '';

        poneys.forEach(poney => {
            const option = document.createElement('option');
            option.value = poney.id;
            option.textContent = `${poney.nom} - ${poney.poids_max} kg - ${poney.age} ans`;
            poneySelect.appendChild(option);
        });
    } else {
        alert('Erreur lors du chargement des poneys disponibles.');
    }
}

function togglePopupVisibility(isVisible) {
    const popup = document.getElementById('booking-popup');
    popup.style.display = isVisible ? 'flex' : 'none';
}

function closeBookingPopup() {
    togglePopupVisibility(false);
}

document.getElementById('booking-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const id_cours = document.getElementById('id_cours').value;
    const id_poney = document.getElementById('poney_dispo').value;
    const dateTime = document.getElementById('dateC').value;
    const prix = this.dataset.coursePrice; 
    const nom_cours = this.dataset.courseName;
    const niveau = this.dataset.courseNiveau;

    const [date, heure] = dateTime.split(' ');

    const urlPaiement = `index.php?action=paiement&prix=${prix}&type=${nom_cours}&heure=${encodeURIComponent(heure)}&date=${encodeURIComponent(date)}&id_cours=${id_cours}&id_poney=${id_poney}&niveau=${niveau}`;

    window.location.href = urlPaiement;

});

function submitBooking(bookingData){
    fetch('/App/Controllers/Planning/PlanningDB.php',{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(bookingData)
    })
        .then(response => response.json())
        .then(data => handleBookingResponse(data))
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la réservation.' + error);
        });
}

function handleBookingResponse(data) {
    if (data.success) {
        alert(data.message);
        closeBookingPopup();
        updatePlaces_restantes();
    } else {
        alert('Erreur lors de la réservation: ' + data.message);
    }
}
