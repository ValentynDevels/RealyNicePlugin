const addNewPerson = document.getElementById('add_new_person');
const peopleMetaWrapper = document.querySelector('.people_meta_wrapper');
const deleteRecPerson = document.getElementById('delete_rec_person');
let lastPerson = peopleMetaWrapper.querySelector('.people_small_wrapper:last-child');

addNewPerson.addEventListener('click', (event) => {
  event.preventDefault();

  let xhr = new XMLHttpRequest();

  xhr.open('POST', likesOBJ.url, true);

  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send('newperson=new&action=newperson');

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
  let name = lastPerson.querySelector('input[name=person_name]').value;
  let lastnName = lastPerson.querySelector('input[name=person_last_name]').value;
  let url = lastPerson.querySelector('input[name=person_url]').value;

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