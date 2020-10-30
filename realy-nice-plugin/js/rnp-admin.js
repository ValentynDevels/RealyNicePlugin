const addNewPerson = document.getElementById('add_new_person');
const peopleMetaWrapper = document.querySelector('.people_meta_wrapper');
const deleteRecPerson = document.getElementById('delete_rec_person');
let clickCount = 0;

addNewPerson.addEventListener('click', (event) => {
  event.preventDefault();

  clickCount++;

  peopleMetaWrapper.insertAdjacentHTML('beforeend',
    `
    <div class="people_small_wrapper">
      <div class="people_meta_input">
        <label>First name</label><input name="person[${clickCount}][pn]" type="text"  placeholder="Vitalik" required/>
      </div>
      <div class="people_meta_input">
        <label>Last name</label><input name="person[${clickCount}][pl]" type="text"  placeholder="Superman" required/>
      </div>
      <div class="people_meta_input">
        <label>Person url</label><input name="person[${clickCount}][pu]" type="url"  placeholder="https://ivan.com" required/>
      </div>
      <button class="delete-the-person">Delete</button> 
    </div>
    `
  );

});

peopleMetaWrapper.addEventListener('click', ev => {
  ev.preventDefault();

  if (ev.target.classList.contains('delete-the-person')) {
    const smalWrapper = ev.target.closest('.people_small_wrapper');

    if (smalWrapper.querySelector('input[type=url]').value
    || smalWrapper.querySelector('input[placeholder=Superman]').value
    || smalWrapper.querySelector('input[placeholder=Vitalik]').value) {
      let confirmResponse = confirm('Are you sure you want to delete this entry?');

      if (confirmResponse)
        ev.target.closest('.people_small_wrapper').remove();
    }
    else 
      ev.target.closest('.people_small_wrapper').remove();   
  }
});

jQuery(document).ready(function($) {
  $('.js-example-basic-multiple').select2({
    width: 800,
  });

});