// eventActions.js
let existingID =[] 

function removeParticipant(eventId, userId, divId) {
    buttonJoin = document.getElementById(`join_${eventId}`)
    buttonJoin.disabled = false;
    buttonJoin.classList.remove('disabled-button');
    fetch(`/feed/${eventId}/remove-participant/${userId}`, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Participant removed successfully:', data);
        document.getElementById(`event_${divId}`).remove();
        document.getElementById(`chat_${eventId}`).remove();

    const index = existingID.indexOf(eventId);
    existingID.splice(index, 1);
    console.log(existingID);


    })
    .catch(error => {
        console.error('There was a problem removing the participant:', error);
    });
}




function addParticipant(button,eventId,userId,eventName,eventPhoto,userName) {
    button.disabled = true;
    button.classList.add('disabled-button');
    fetch(`/feed/${eventId}/add-participant/${userId}`, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (!existingID.includes(eventId)){
        existingID.push(eventId)
        console.log('participant added', data)
        const eventContainer = document.getElementById('eventParticipationContainer');
        chatContainer = document.getElementById("chatContainer");
        const newDiv = document.createElement('div');
        newDiv.setAttribute('id', eventId); 
        newDiv.innerHTML = `
            <div id="event_${ eventId }" class=" d-flex justify-content-around align-items-center flex-row gap-4 mb-3">
            <div class="d-flex justify-content-center align-items-center gap-1">
            <img src="../uploads/${eventPhoto}" alt="" style="border-radius: 50%;" width="40">
            <p style="margin:0; width: 150px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><b>${eventName}</b></p>
          </div>
          <div class=" d-flex justify-content-center align-items-center gap-2 " > 
            <button class="bin-button" onclick="removeParticipant(${ eventId }, ${ userId}, ${ eventId })">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 39 7"
                  class="bin-top"
                >
                  <line stroke-width="4" stroke="white" y2="5" x2="39" y1="5"></line>
                  <line
                    stroke-width="3"
                    stroke="white"
                    y2="1.5"
                    x2="26.0357"
                    y1="1.5"
                    x1="12"
                  ></line>
                </svg>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 33 39"
                  class="bin-bottom"
                >
                  <mask fill="white" id="path-1-inside-1_8_19">
                    <path
                      d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"
                    ></path>
                  </mask>
                  <path
                    mask="url(#path-1-inside-1_8_19)"
                    fill="white"
                    d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"
                  ></path>
                  <path stroke-width="4" stroke="white" d="M12 6L12 29"></path>
                  <path stroke-width="4" stroke="white" d="M21 6V29"></path>
                </svg>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 89 80"
                  class="garbage"
                >
                  <path
                    fill="white"
                    d="M20.5 10.5L37.5 15.5L42.5 11.5L51.5 12.5L68.75 0L72 11.5L79.5 12.5H88.5L87 22L68.75 31.5L75.5066 25L86 26L87 35.5L77.5 48L70.5 49.5L80 50L77.5 71.5L63.5 58.5L53.5 68.5L65.5 70.5L45.5 73L35.5 79.5L28 67L16 63L12 51.5L0 48L16 25L22.5 17L20.5 10.5Z"
                  ></path>
                </svg>
            </button>
            </a>
          
            <div class="btn-conteiner">
                <a href="{{ path('event_show_one' , {'id':${eventId}}) }}" class="btn-content">
                  <span class="icon-arrow">
                    <svg
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      xmlns="http://www.w3.org/2000/svg"
                      version="1.1"
                      viewBox="0 0 66 43"
                      height="30px"
                      width="30px"
                    >
                      <g
                        fill-rule="evenodd"
                        fill="none"
                        stroke-width="1"
                        stroke="none"
                        id="arrow"
                      >
                        <path
                          fill="#ffffff"
                          d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z"
                          id="arrow-icon-one"
                        ></path>
                        <path
                          fill="#ffffff"
                          d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z"
                          id="arrow-icon-two"
                        ></path>
                        <path
                          fill="#ffffff"
                          d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z"
                          id="arrow-icon-three"
                        ></path>
                      </g>
                    </svg>
                  </span>
                  <div class="img-popup"><img src="../uploads/${eventPhoto}" alt="" width="100px" style="border-radius: 30%;"></div>  
                </a>
            </div>
            </div>   
        </div>
        
        `;
        eventContainer.appendChild(newDiv);
        chatContainer.innerHTML+=`<div id="chat_${eventId}" class="chat-wrapper">
        <header onclick="collapseChat(this)" style="cursor: pointer;" class="chat-header">
            <h2>${eventName}</h2>
            <span class="status online" ><img src="../uploads/${eventPhoto}" width="25" alt=""></span>
          </header>
        <div class="chat-window">
          <main class="chat-history mt-1" id="chat${eventId}">
            
          </main>
          <form class="chat-input-form" id="chatForm">
            <div class="messageBox mt-1" >
              <div class="fileUploadWrapper">
                <label for="file">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 337 337">
                    <circle
                      stroke-width="20"
                      stroke="#6c6c6c"
                      fill="none"
                      r="158.5"
                      cy="168.5"
                      cx="168.5"
                    ></circle>
                    <path
                      stroke-linecap="round"
                      stroke-width="25"
                      stroke="#6c6c6c"
                      d="M167.759 79V259"
                    ></path>
                    <path
                      stroke-linecap="round"
                      stroke-width="25"
                      stroke="#6c6c6c"
                      d="M79 167.138H259"
                    ></path>
                  </svg>
                  <span class="tooltip">Add an image</span>
                </label>
                <input type="file" id="file" name="file" />
              </div>
              <input required="" placeholder="Message..." type="text" class="messageInput" id="send${eventId}" />
              <button id="sendButton" class="sendBtn" data-send-id="${eventId}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 664 663">
                  <path
                    fill="none"
                    d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"
                  ></path>
                  <path
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="33.67"
                    stroke="#6c6c6c"
                    d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"
                  ></path>
                </svg>
              </button>
            </div>
          </form>
        </div>
      </div>`;
      socketFunction(userName);
      
    }
        else{
            console.log('already exist')
        }
    })
    .catch(error => {
        console.error('There was an error!', error);
        alert('An error occurred while joining the event');
    });
   
};
function handleKeyUp(event) {
  if (event.keyCode === 13) {
 
      searchTNT();
  }
}
function addReaction(eventId,reactionType){
    fetch(`/feed/${eventId}/reaction`, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reactionType: reactionType }) 

    })
    .then(response=>{
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data=>{
        console.log('reacted  successfully:', reactionType);
        console.log(data.action);
        let color;
        const img = document.getElementById(`mainReaction_${eventId}`);
        const spann = document.getElementById(`span_${eventId}`);
        const div_img = document.getElementById(`div_reaction_${eventId}`)
        const counterReaction = document.getElementById(`counter_${eventId}`)
        if(img.getAttribute('src') ===`assets/reactions/${reactionType}.png` )
        {
            img.setAttribute('src',"assets/reactions/thumbs-up-stroke-rounded.svg")
            spann.textContent = "like"
            spann.style.color = '#9B9B9B'
        }
        else{
        if (reactionType === 'like') {
            img.setAttribute('src', `assets/reactions/like.png`);
           
            color = '#77A7FF';
        } else if (reactionType === 'heart') {
            img.setAttribute('src', `assets/reactions/heart.png`);
           
            color = '#F35369';
        } else {
            img.setAttribute('src', `assets/reactions/${reactionType}.png`);
          
            color = '#F5C33B';
        } 
        spann.textContent = reactionType
        spann.style.color = color;

    }
     if (data.action === 0){
        counterReaction.textContent = parseInt(counterReaction.textContent) - 1;
     }
     if (data.action === 1){
        counterReaction.textContent = parseInt(counterReaction.textContent) + 1;
     }
     
        
    })
    .catch(error => {
        console.error('There was an error!', error);
        alert('An error occurred while joining the event');
    });
   
}

function addLikeReaction(eventId,reactionType){
    fetch(`/feed/${eventId}/reaction`, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reactionType: reactionType }) 

    })
    .then(response=>{
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data=>{
        console.log('reacted  successfully:', data.action);
        console.log('reacted  successfully:', reactionType);
        let color;
        const img = document.getElementById(`mainReaction_${eventId}`);
        const div_img = document.getElementById(`div_reaction_${eventId}`)
        const counterReaction = document.getElementById(`counter_${eventId}`)
        img.setAttribute('src',"/assets/reactions/like.png")
        const spann = document.getElementById(`span_${eventId}`);
        spann.style.color = "#77A7FF";
        spann.textContent = "like"
        if (data.action === 0){
            counterReaction.textContent = parseInt(counterReaction.textContent) - 1;
         }
         if (data.action === 1){
            counterReaction.textContent = parseInt(counterReaction.textContent) + 1;
         }
    })
    .catch(error => {
        console.error('There was an error!', error);
        alert('An error occurred while joining the event');
    });
   
}

function socketFunction(userName){
  console.log("i am here")


function sendMessage(chatId) {
    const messageInput = document.querySelector(`#send${chatId}`);
    const message = {
        message: messageInput.value,
        chatId: chatId,
        senderName: userName 
    };
    document.getElementById(`chat${chatId}`).innerHTML += "<div class='message self-end'>" + message.message + "</div>"
    socket.send(JSON.stringify(message));
    messageInput.value = ''; 
}

document.querySelectorAll(".sendBtn").forEach(button => {
  function clickHandler(e) {
    const sender = e.target.dataset.sendId;
    sendMessage(sender);
    console.log("i work");
}
button.removeEventListener("click", clickHandler);
// Add the event listener
button.addEventListener("click", clickHandler);

   
});
}

function collapseChat(span) {
  const commentForm = span.nextElementSibling;
  commentForm.classList.toggle('chat-window--expanded');
}


function searchTNT() {
  const searchInput = document.getElementById('searchInput').value.trim();

  // Fetch data from the server only if searchInput is not empty
  if (searchInput) {
      fetch(`/search?searchTerm=${encodeURIComponent(searchInput)}`, {
          method: 'GET',
          headers: {
              'Content-Type': 'application/json',
          },
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          console.log(data); // Log the data for debugging

          // Convert the JSON response into an array of ids
          const ids = data.map(item => item.id);
          console.log(ids);

          // Loop through all div elements with class "facebook-post"
          document.querySelectorAll('.facebook-post').forEach(div => {
              // Extract the id from the div's id attribute
              const postId = parseInt(div.id);

              // Check if the postId is included in the ids array
              if (!ids.includes(postId)) {
                  // Hide the div if its id is not in the list of ids
                  div.style.display = 'none';
              } else {
                  // Show the div if its id is in the list of ids
                  div.style.display = 'block';
              }
          });
      })
      .catch(error => {
          console.error('Error fetching or processing data:', error);
      });
  } else {
      // If searchInput is empty, restore all elements to block display
      document.querySelectorAll('.facebook-post').forEach(div => {
          div.style.display = 'block';
      });
  }
}
