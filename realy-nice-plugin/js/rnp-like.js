const like = document.getElementById('like');
const likeCount = document.querySelector('.like-count');
const postID = like.dataset.id;

if (like) {

  if (localStorage.getItem(`${postID}_likes_count`))
    likeCount.textContent = localStorage.getItem(`${postID}_likes_count`);
  else
    sendLikes('first');
  checkLocal();

  like.addEventListener('click', () => {

    if (localStorage.getItem(`like-${postID}`) == 'no') {
      localStorage.setItem(`like-${postID}`, 'yes');
      like.classList.add('active-like');
      sendLikes('yes');
    }
    else if (localStorage.getItem(`like-${postID}`) == 'yes') {
      localStorage.setItem(`like-${postID}`, 'no');
      like.classList.remove('active-like');
      sendLikes('no');
    }
  });
}

// Check is like or not in localstorage 
function checkLocal() {
  if ( !localStorage.getItem(`like-${postID}`) ) {
    localStorage.setItem(`like-${postID}`, 'no');
    like.classList.remove('active-like');
  }
  else if (localStorage.getItem(`like-${postID}`) == 'yes')
    like.classList.add('active-like');
}
// ajax request which check and sends lakes
function sendLikes(lukas) {
  var xhr = new XMLHttpRequest();

  xhr.open("POST", likesOBJ.url, true);

  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(`postID=${postID}&like=${lukas}&action=likos`);

  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      likeCount.textContent = this.responseText;
      localStorage.setItem(`${postID}_likes_count`, this.responseText);
    }
    else 
      return;
  };

}