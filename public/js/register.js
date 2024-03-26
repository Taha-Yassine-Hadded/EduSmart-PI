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
    const selectedDate = new Date(dateOfBirthInput.value);
    const year = selectedDate.getFullYear();
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();

    if ((currentYear - year) < 16) {
        event.preventDefault();
        document.getElementById('errorDateTeacher').classList.remove('d-none');
    } else {
        document.getElementById('errorDateTeacher').classList.add('d-none');
    }
});

document.getElementById('registrationFormStudent').addEventListener('submit', function(event)  {

    const dateOfBirthInput = document.getElementById('dateStudent');
    const selectedDate = new Date(dateOfBirthInput.value);
    const year = selectedDate.getFullYear();
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();

    if ((currentYear - year) < 16) {
        event.preventDefault();
        document.getElementById('errorDateStudent').classList.remove('d-none');
    } else {
        document.getElementById('errorDateStudent').classList.add('d-none');
    }
});