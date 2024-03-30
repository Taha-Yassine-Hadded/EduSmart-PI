const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");

const container = document.querySelector(".containerr");

sign_up_btn.addEventListener('click', () => {
    container.classList.add("sign-up-mode");
});
sign_in_btn.addEventListener('click', () => {
    container.classList.remove("sign-up-mode");
});


document.getElementById('registrationFormTeacher').addEventListener('submit', function(event)  {

    const dateOfBirthInput = document.getElementById('dateTeacher');
    const errorDate = document.getElementById('errorDateTeacher');

    const nom = document.getElementById('nomTeacher');
    const errorNom = document.getElementById('errorNomTeacher');

    const prenom = document.getElementById('prenomTeacher');
    const erroPrenom = document.getElementById('errorPrenomTeacher');

    const cinTeacher = document.getElementById('cinTeacher');
    const errorCinTeacher = document.getElementById('errorCinTeacher');


    const selectedDate = new Date(dateOfBirthInput.value);
    const year = selectedDate.getFullYear();
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();

    if ((currentYear - year) < 18) {
        event.preventDefault();
        errorDate.textContent= 'Maximum 2006';
    } else {
        errorDate.textContent= '';
    }

    if (nom.value.length < 3) {
        event.preventDefault();
        errorNom.textContent= 'Minimum 3 caractéres';
    } else {
        errorNom.textContent= '';
    }

    if ( prenom.value.length < 3) {
        event.preventDefault();
        erroPrenom.textContent= 'Minimum 3 caractéres';
    } else {
        erroPrenom.textContent= '';
    }

    const regex = /^[01][0-9]{7}$/;

    if (!regex.test(cinTeacher.value)) {
        event.preventDefault();
        errorCinTeacher.textContent = '8 chiffres et commence par 0 ou 1 ';
    } else {
        errorCinTeacher.textContent = '';
    }

});

document.getElementById('registrationFormStudent').addEventListener('submit', function(event)  {


    const dateOfBirthInput = document.getElementById('dateStudent');
    const errorDateStudent = document.getElementById('errorDateStudent');

    const nomStudent = document.getElementById('nomStudent');
    const errorNomStudent = document.getElementById('errorNomStudent');

    const prenomStudent = document.getElementById('prenomStudent');
    const erroPrenomStudent = document.getElementById('errorPrenomStudent');

    const niveau = document.getElementById('niveau');
    const errorNiveau = document.getElementById('errorNiveau');

    const classe = document.getElementById('classe');
    const errorClasse = document.getElementById('errorClasse');

    const cin = document.getElementById('cin');
    const errorCin = document.getElementById('errorCin');


    const selectedDate = new Date(dateOfBirthInput.value);
    const year = selectedDate.getFullYear();
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();

    if ((currentYear - year) < 18) {
        event.preventDefault();
        errorDateStudent.textContent= 'Maximum 2006';
    } else {
        errorDateStudent.textContent= '';
    }

    if (nomStudent.value.length < 3) {
        event.preventDefault();
        errorNomStudent.textContent= 'Minimum 3 caractéres';
    } else {
        errorNomStudent.textContent= '';
    }

    if ( prenomStudent.value.length < 3) {
        event.preventDefault();
        erroPrenomStudent.textContent= 'Minimum 3 caractéres';
    } else {
        erroPrenomStudent.textContent= '';
    }

    if ( niveau.value >= 1 && niveau.value <= 5) {
        errorNiveau.textContent= '';
    } else {
        event.preventDefault();
        errorNiveau.textContent= '1,2,3,4 ou 5';
    }

    const regex = /^[01][0-9]{7}$/;

    if (!regex.test(cin.value)) {
        event.preventDefault();
        errorCin.textContent = '8 chiffres et commence par 0 ou 1 ';
    } else {
        errorCin.textContent = '';
    }

    if (!classe.value.startsWith(niveau.value) || classe.value.length < 2) {
        event.preventDefault();
        errorClasse.textContent = 'Doit commencer par la valeur de niveau';
    } else {
        errorClasse.textContent = '';
    }

});

document.getElementById('profilePhotoStudent').addEventListener('change', function() {
    if (this.files.length > 0) {
        alert('Photo ajouté avec succés');
    }
});

document.getElementById('profilePhoto').addEventListener('change', function() {
    if (this.files.length > 0) {
        alert('Photo ajouté avec succés');
    }
});