const addNewPerson = document.getElementById('add_new_person');

addNewPerson.addEventListener('click', (event) => {
  event.preventDefault();

  let xhr = new XMLHttpRequest();

  xhr.open('POST', likesOBJ.url, true);

  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send('newperson=new&action=newperson');

  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
    }
    else 
      return;
  };
});