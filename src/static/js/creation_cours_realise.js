function validateDay(input) {
    const date = new Date(input.value);
    const date_cours_programme = document.getElementById('cours').value;
    if (date.getDay() !== convertDayToNumber(date_cours_programme.split('&')[1])) {
        alert('Veuillez sélectionner le jour du cours programmé');
        input.value = '';
    }
}

//clear date inpute value if the cours is changed
document.getElementById('cours').addEventListener('change', function() {
    document.getElementById('date_c').value = '';
});

//static function convert day to number
function convertDayToNumber(day) {
    switch (day) {
        case 'Lundi':
            return 1;
        case 'Mardi':
            return 2;
        case 'Mercredi':
            return 3;
        case 'Jeudi':
            return 4;
        case 'Vendredi':
            return 5;
        case 'Samedi':
            return 6;
        case 'Dimanche':
            return 0;
    }
}