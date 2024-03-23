let popUp = document.getElementById("cookiePopup");
//When user clicks the accept button
document.getElementById("acceptCookie").addEventListener("click", () => {

  var d = new Date();
  d.setTime(d.getTime() + (7*24*60*60*1000)); // 7 days from now
  var expires = "expires="+ d.toUTCString();

  //Create Cookie with value ACCEPTED
  document.cookie = "ACCEPTED=; " + expires + ";";
  //Hide the popup
  popUp.classList.add("hide");
  popUp.classList.remove("show");

});

//When user clicks the decline button
document.getElementById("declineCookie").addEventListener("click", () => {
  
  var d = new Date();
  d.setTime(d.getTime() + (7*24*60*60*1000)); // 7 days from now
  var expires = "expires="+ d.toUTCString();

  // Create a cookie to remember the decline choice
  document.cookie = "DECLINED=; " + expires + ";";
  //Hide the popup
  popUp.classList.add("hide");
  popUp.classList.remove("show");
  });

const checkCookie = () => {
  // Check if the accept or decline cookies are set
  if (document.cookie) {
    //Hide the popup
    popUp.classList.add("hide");
    popUp.classList.remove("show");
  } else {
    // No decision has been made; show the popup
    popUp.classList.add("show");
    popUp.classList.remove("hide");
  }
};
//Check if cookie exists when page loads
window.onload = () => {
  setTimeout(() => {
    checkCookie();
  }, 1500);
};