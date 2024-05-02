const facebookBtn = document.querySelectorAll(".facebook-btn");
const twitterBtn = document.querySelector(".twitter-btn");
const pinterestBtn = document.querySelector(".pinterest-btn");
const linkedinBtn = document.querySelector(".linkedin-btn");
const whatsappBtn = document.querySelector(".whatsapp-btn");

function init() {
  const pinterestImg = document.querySelector(".pinterest-img");

  let postUrl = encodeURI(document.location.href);
  let postTitle = encodeURI("Hi everyone, please check this out: ");
  //let postImg = encodeURI(pinterestImg.src);

  facebookBtn.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default anchor tag behavior
        
        const postUrl = encodeURI(this.getAttribute('href'));
        console.log(postUrl)
        // Open the Facebook share dialog in a new window
        window.open(
            `https://www.facebook.com/sharer.php?u=http://127.0.0.1:8000/${postUrl}`,
            "_blank"
        );
    });
});

  /* Similar code for other social platforms */

  /*twitterBtn.setAttribute(
    "href",
    `https://twitter.com/share?url=${postUrl}&text=${postTitle}`
  );

  pinterestBtn.setAttribute(
    "href",
    `https://pinterest.com/pin/create/bookmarklet/?media=${postImg}&url=${postUrl}&description=${postTitle}`
  );

  linkedinBtn.setAttribute(
    "href",
    `https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`
  );

  whatsappBtn.setAttribute(
    "href",
    `https://wa.me/?text=${postTitle} ${postUrl}`
  );*/
}

init();
