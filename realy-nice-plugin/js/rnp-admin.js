const addNewPerson = document.getElementById('add_new_person');
const peopleMetaWrapper = document.querySelector('.people_meta_wrapper');
const deleteRecPerson = document.getElementById('delete_rec_person');
let lastPerson = peopleMetaWrapper.querySelector('.people_small_wrapper:last-child');
let clickCount = 0;

addNewPerson.addEventListener('click', (event) => {
  event.preventDefault();

  clickCount++;

  let xhr = new XMLHttpRequest();

  xhr.open('POST', likesOBJ.url, true);

  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send(`newperson=new&clickCount=${clickCount}&action=newperson`);

  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      peopleMetaWrapper.insertAdjacentHTML('beforeend', this.responseText);
    }
    else 
      return;
  };
});

deleteRecPerson.addEventListener('click', (event) => {
  event.preventDefault();

  let postId = deleteRecPerson.dataset.id;
  lastPerson = peopleMetaWrapper.querySelector('.people_small_wrapper:last-child');
  let name = lastPerson.querySelector('input[placeholder=Vitalik]').value;
  let lastnName = lastPerson.querySelector('input[placeholder=Superman]').value;
  let url = lastPerson.querySelector('input[type=url]').value;

  lastPerson.remove();

  if (name && lastnName && url) {
    let xhr = new XMLHttpRequest();

    xhr.open('POST', likesOBJ.url, true);

    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`deleteperson=delete&name=${name}&lastName=${lastnName}&url=${url}&postId=${postId}&action=newperson`);

    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
      }
      else 
        return;
    };
  }
});