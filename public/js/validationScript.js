const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const nextBtnSec = document.querySelector(".next-1");
const submitBtn = document.querySelector(".submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");
let current = 1;
let endInterval = null;


nextBtnFirst.addEventListener("click", function(event) {
  

  var email = document.querySelector('input[type="email"]').value;
    if (email) {

      event.preventDefault();

      if (endInterval) {
        clearInterval(endInterval);
      }

        fetch('/sendMail', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email }),
        })
        .then(response => response.json())
        .then(data => {
          if(data.exist === 'false') {
            
            slidePage.style.marginLeft = "-25%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;

            document.getElementById('emailAlert').classList.add('d-none');

            var fifteenMinutes = 60 * 15;
            var display = document.querySelector('.timer');
            startTimer(fifteenMinutes, display);
            requestId = data.id;
          } else {

            document.getElementById('emailAlert').classList.remove('d-none');
          }
          
        });
    }

    function startTimer(duration, display) {
      var timer = duration, minutes, seconds;

      endInterval = setInterval(function() {
          minutes = parseInt(timer / 60, 10);
          seconds = parseInt(timer % 60, 10);
  
          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;
  
          display.textContent = minutes + ":" + seconds;
  
          if (--timer < 0) {
              clearInterval(endInterval);
              event.preventDefault();
              slidePage.style.marginLeft = "0%";
              bullet[current - 2].classList.remove("active");
              progressCheck[current - 2].classList.remove("active");
              progressText[current - 2].classList.remove("active");
              current -= 1;
              setTimeout(function(){
                alert("Code expiré !!");
              },500);
          }
      }, 1000);
  }
});

nextBtnSec.addEventListener("click", function(event){

  var codeInput = document.querySelector(".code");
  var code = document.querySelector(".code").value;

  event.preventDefault();

  fetch('/verifyCode', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ code: code, id: requestId}),
})
    .then(response => response.json())
    .then(data => {
        if (data.verified) {
          slidePage.style.marginLeft = "-50%";
          bullet[current - 1].classList.add("active");
          progressCheck[current - 1].classList.add("active");
          progressText[current - 1].classList.add("active");
          current += 1;
        } else {
          codeInput.style.border = "#9f1c00 solid 3px";
        }
    })

});

submitBtn.addEventListener("click", function(){

  var password = document.querySelector('#password').value;
  var confirm = document.querySelector('#confirm').value;

  if (password.length >= 8) {
    document.getElementById('passwordAlert').classList.add('d-none');
    if (password === confirm) {
      document.getElementById('confirmAlert').classList.add('d-none');
      bullet[current - 1].classList.add("active");
      progressCheck[current - 1].classList.add("active");
      progressText[current - 1].classList.add("active");
      current += 1;
      setTimeout(function(){
        alert("Compte créer avec succés");
      },500);
    } else {
      event.preventDefault();
      document.getElementById('confirmAlert').classList.remove('d-none');
    }
} else {
    event.preventDefault();
    document.getElementById('passwordAlert').classList.remove('d-none');
}




});

prevBtnSec.addEventListener("click", function(event){

  event.preventDefault();

  fetch('/deleteCode', {
    method: 'DELETE',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ id: requestId }),
})
    .then(response => response.json())

      slidePage.style.marginLeft = "0%";
      bullet[current - 2].classList.remove("active");
      progressCheck[current - 2].classList.remove("active");
      progressText[current - 2].classList.remove("active");
      current -= 1;
  
});

document.addEventListener("DOMContentLoaded", function() {
  const emailInput = document.querySelector('#emailInput');
  const nextBtnFirst = document.querySelector(".firstNext");

  nextBtnFirst.disabled = true;
  nextBtnFirst.style.backgroundColor = "black";

  emailInput.addEventListener("input", function() {
    const emailPattern = /^[^@]+@esprit\.tn$/;
    const isValidEmail = emailPattern.test(emailInput.value);
    
    nextBtnFirst.disabled = !isValidEmail;
    nextBtnFirst.style.backgroundColor = isValidEmail ? "#9f1c00" : "black";
    emailInput.style.border = isValidEmail ? "green solid 3px" : "#9f1c00 solid 3px";

  });
});