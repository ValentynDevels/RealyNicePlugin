const addNewPerson = document.getElementById('add_new_person');
const peopleMetaWrapper = document.querySelector('.people_meta_wrapper');
const deleteRecPerson = document.getElementById('delete_rec_person');
let lastPerson = peopleMetaWrapper.querySelector('.people_small_wrapper:last-child');
let clickCount = 0;
const select2Container = document.querySelector('.select2-container');

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

jQuery(document).ready(function($) {
  $('.js-example-basic-multiple').select2({
    width: 800,
  });

  $('.js-example-basic-multiple').on('select2:unselecting', function(e) {
    let postID = e.params.args.data.element.dataset.id;
    console.log(postID);
  });
  $('.js-example-basic-multiple').on('select2:selecting', function(e) {
    let postID = e.params.args.data.element.dataset.id;
    console.log(postID);
  });
});

//select2Container.setAttribute('style', 'max-width: 500px;');