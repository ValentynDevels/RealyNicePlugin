const addNewPerson = document.getElementById('add_new_person');
const peopleMetaWrapper = document.querySelector('.people_meta_wrapper');
const deleteRecPerson = document.getElementById('delete_rec_person');
let clickCount = 0;

if (addNewPerson) {
  const lastPeople = peopleMetaWrapper.querySelector('.people_small_wrapper:last-child');
  
  if (!lastPeople)
    clickCount = 0;
  else 
    clickCount = Number(lastPeople.dataset.clickCount);

  addNewPerson.addEventListener('click', (event) => {
    event.preventDefault();
  
    clickCount++;
  
    peopleMetaWrapper.insertAdjacentHTML('beforeend',
      `
      <div data-click-count="${clickCount}" class="people_small_wrapper">
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
}

if (peopleMetaWrapper) {
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
          clickCount--;
      }
      else 
        ev.target.closest('.people_small_wrapper').remove();
        clickCount--;   
    }
  });
}

let dataa;

jQuery(document).ready(function($) {
  $('.js-data-example-ajax').select2({
    minimumInputLength: 3,
    ajax: {
      url: likesOBJ.url,
      dataType: 'json',
      delay: 500,
      data: function (params) {
        var query = {
          title: params.term,
          action: 'posts',
        }

        return query;
      },
      processResults: function (data) {
        let res = [];
        data = sortBubble(data);

        for (let i = 0; i < data.length; i++) {
          res.push({
            "id": i,
            "text": data[i].title,
            "value": data[i].postId
          });
        }

        return {
          results: res
        };
      }
    },
    width: 800,
    /*templateSelection: function (data, container) {
      $(data.element).attr('value', data.value);
      $(data.element).attr('selected', true);
      return data.text;
    },*/
  });

});

function sortBubble(data) {
  let tmp; 
  for (let i = data.length - 1; i > 0; i--) {  
    let counter = 0;
    for (let j = 0; j < i; j++) {
        if (data[j].raiting < data[j+1].raiting) {
            tmp = data[j];
            data[j] = data[j+1];
            data[j+1] = tmp;
            counter++;
        }
    }  
    if(counter==0){
      break;
    } 
  }
  return data;
};