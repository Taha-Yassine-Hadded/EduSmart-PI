/*
const info = document.getElementById('info');
const error = document.getElementById('error');

document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {

  var email = document.querySelector('input[type="email"]').value;
    if (email) {

    fetch('/sendResetMail', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email }),
        })
        .then(response => response.json())
        .then(data => {
          if(data.result === 'success') {

            info.textContent= 'Nous enverrons un lien de réinitialisation du mot de passe à votre adresse e-mail';
            error.textContent='';

          } else {

            event.preventDefault();
            error.textContent= 'Impossible de trouver un utilisateur avec cet e-mail';
            info.textContent='';

          }
          
        });
    }

});
*/