const searchInput = document.querySelector('.old-search');
const searchIcon = document.querySelector('.seacrh-icon');
const openFiltersBtn = document.querySelector('.open-filters-btn');
const filtersDisplayer = document.querySelector('.filters-displayer');
const ranger = document.querySelector('.ranger');
const importances = document.querySelectorAll('.importance');
const searchResults = document.querySelector('.search-results');
const postResults = document.querySelector('.post_results');
const oldResults = document.querySelector('.old_results');
const peopleResults = document.querySelector('.people_results');
let searchValue;

const main = document.querySelector('main');
main.classList.add('main');
main.addEventListener('click', main_event => {
  if (main_event.target.classList.contains('main')) {
    searchResults.classList.add('no-focused');
  }
})

if (searchInput) {

  let output = '';
  const rangeInputs = document.querySelectorAll('.range-inputs');
  let typingTimer = '';

  rangeInputs.forEach(input => {
    replaceValues(input);
  });

  ranger.oninput = (ev) => {
    let target = ev.target;

    if (target.getAttribute('id').slice(-1) == '1')
      output = target.getAttribute('id') + 'min';
    else if (target.getAttribute('id').slice(-1) == '2')
      output = target.getAttribute('id') + 'max';

    output = document.getElementById(`${output}`);
    output.value = target.value;
  }

  searchInput.addEventListener('focus', () => {
    searchIcon.classList.add('focus-icon');
  });
  searchInput.addEventListener('blur', () => {
    searchIcon.classList.remove('focus-icon');
  });

  let datemin;
  let datemax;
  let imp = 0;

  openFiltersBtn.addEventListener('click', () => {
    filtersDisplayer.classList.toggle('open-filters');

    if (openFiltersBtn.innerText == "FILTERS") {
      openFiltersBtn.style.background = "#00a2b7";
      openFiltersBtn.innerText = "APPLY";
    }
    else if (openFiltersBtn.innerText == "APPLY") {
      openFiltersBtn.style.background = " #cd2653";
      openFiltersBtn.innerText = "FILTERS";

      let day = document.getElementById('inday1min').value;
      let month = document.getElementById('inmonth1min').value;
      let year = document.getElementById('inyear1min').value;
      day = day > 9 ? day : '0' + day;
      month = month > 9 ? month : '0' + month;

      datemin = year + '-' + month + '-' + day;

      day = document.getElementById('inday2max').value;
      month = document.getElementById('inmonth2max').value;
      year = document.getElementById('inyear2max').value;
      day = day > 9 ? day : '0' + day;
      month = month > 9 ? month : '0' + month;

      datemax = year + '-' + month + '-' + day;

      imp = 0;

      importances.forEach(importance => {
        if (importance.checked)
          imp = (imp * 10) + Number(importance.dataset.name);
      });
    }
  });

  searchInput.oninput = async () => {
    if (!searchIcon.classList.contains('in-search'))
      searchIcon.classList.add('in-search');
    clearTimeout(typingTimer);

    let resultUrlWrapper = document.querySelectorAll('.res_url_wrapper');

    typingTimer = setTimeout(async () => {
      searchIcon.classList.remove('in-search');

      resultUrlWrapper.forEach( el => {
        el.remove();
      });

      if (searchInput.value) {

      searchValue = searchInput.value.replace(/ /g, "_");

      let json;  

      if (!datemin || !datemax) {
        let response = await fetch(`${likesOBJ.domain}/wp-json/rnp/v1/old_events/${searchValue}`);

        if (response.ok)
          json = await response.json();
        else {
          alert("Ошибка HTTP: " + response.status);
        }
      }
      else if (datemin || datemax) {
        let body = {
          from: datemin,
          to: datemax,
          imp: imp
        };

        let response = await fetch(`${likesOBJ.domain}/wp-json/rnp/v1/old_events/${searchValue}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json;charset=utf-8'
          },
          body: JSON.stringify(body)
        });

        if (response.ok) 
          json = await response.json();
        else {
          alert("Ошибка HTTP: " + response.status);
        }
      }

        if (json) {
          let id = 0;
          searchResults.classList.remove('no-focused');

          json = sortBubble(json);

          json.forEach(oldEvent => {


            oldResults.insertAdjacentHTML('beforeend', 
            `
            <li class="res_url_wrapper old_url_wrapper">
              <a data-id="${id}" href="${oldEvent[1]}" class="result_url result_old">${oldEvent[0]}</a>
            </li>
            `
            );

            oldResults.addEventListener('mouseover', (ev) => {         
              
              if (!ev.target.classList.contains('result_old')) 
                return;

              let target = ev.target;
              let otherLinks = document.querySelectorAll('.res_other_wrapper');
              let pseudoDiv = document.querySelectorAll('.pseudo-div');

              otherLinks.forEach(link => {
                  link.remove();
              });
              pseudoDiv.forEach(div => {
                div.remove();
              });

              let the_ID = target.dataset.id;
              let pseudodivs = '';
              let j = the_ID;

              for (; j >= 1; j--) {
                pseudodivs += '<div class="pseudo-div"></div>';
              }

              peopleResults.insertAdjacentHTML('afterbegin', pseudodivs);
              postResults.insertAdjacentHTML('afterbegin', pseudodivs);

              json[the_ID][2][0].forEach(person => {
                
                peopleResults.insertAdjacentHTML('beforeend', 
                `
                <li class="res_url_wrapper res_other_wrapper">
                  <a href="${person[2]}" class="result_url other-results">${person[0]} ${person[1]}</a>
                </li>
                `);
              });

              json[the_ID][3][0].forEach(person => {

                postResults.insertAdjacentHTML('beforeend', 
                `
                <li class="res_url_wrapper res_other_wrapper">
                  <a href="${person[1]}" class="result_url other-results">${person[0]}</a>
                </li>
                `);
              });
              
            });

            id++;

          });
        }
      }
    }, 800);
  };
}

function replaceValues(inputName) {
  let inputValueName = '';

  if (inputName.getAttribute('id').slice(-1) == '1')
    inputValueName = inputName.getAttribute('id') + 'min';
  else if (inputName.getAttribute('id').slice(-1) == '2')
    inputValueName = inputName.getAttribute('id') + 'max';

  inputValueName = document.getElementById(`${inputValueName}`);

  inputValueName.value = inputName.value;
}

function sortBubble(data) {
  let tmp; 
  for (let i = data.length - 1; i > 0; i--) {  
    let counter = 0;
    for (let j = 0; j < i; j++) {
        if (data[j][4] < data[j+1][4]) {
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