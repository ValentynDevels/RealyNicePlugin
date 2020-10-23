const loadMore = document.querySelector('.load_more');
const postarea = document.querySelector('.posts');
const calendarBody = document.getElementById('calendar-body');
let count = 0; //variable for calendar
const pagedUl = document.querySelector('ul.page-numbers');
const searchInput = document.querySelector('.old-search');
const searchIcon = document.querySelector('.seacrh-icon');
const openFiltersBtn = document.querySelector('.open-filters-btn');
const filtersDisplayer = document.querySelector('.filters-displayer');

if (pagedUl) {
  
const paged = pagedUl.querySelector('a.page-numbers');
const pagedLi = pagedUl.querySelectorAll('li');

// fix archive pagination
if (paged.textContent == '1')
  paged.setAttribute('href', "?page=1");

pagedLi.forEach((element) => {
  if (element.querySelector('.page-numbers.current'))
    element.classList.add('slide-up');
});
}



// Load more btn
if (loadMore) {
  loadMore.addEventListener('click', (event) => {
    event.preventDefault();
    let ids = [];
    const posts = document.querySelectorAll('.post');

    posts.forEach((post) => {
      ids.push(post.dataset.id);
    });

    loadMoreRequest(ids);
  });
}

// ajax request which get posts for load more
function loadMoreRequest(id) {
  var xlhprt = new XMLHttpRequest();

  xlhprt.open("POST", likesOBJ.url, true);
  
  xlhprt.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xlhprt.send(`ids=${id}&action=loadmore`);
  
  xlhprt.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      
      const json = JSON.parse(this.responseText);

      for(let key in json) {

        // inset new posts to archive page 
        postarea.insertAdjacentHTML('beforeEnd', `
        <div class="post" data-id="${json[key].id}">
        <a href="${json[key].link}" class="post_url">
          <img src="${json[key].img}" width="500px">

          <h4 class="post__title">${json[key].title}</h4 class="post__title">

        </a>

        <p class="fragment" style="margin: 0;">${json[key].fragment}</p>
        </div>
        `);
      }
    }
    else
      return; 
  };

    return;
}

// CALENDAR 

  if (calendarBody) {
    // ajax return object with all posts which has date metakey
    // and save their in localstorage
    var calXHR = new XMLHttpRequest();

    calXHR.open("POST", likesOBJ.url, true);

    calXHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    calXHR.send("calendar=is&action=calendar");

    calXHR.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        localStorage.setItem('json', this.responseText);
      }
      else 
        return;
    };
}

// I don't know what to do next code.
// I think that this code create calendar

  function generate_year_range(start, end) {
    var years = "";
    for (var year = start; year <= end; year++) {
        years += "<option value='" + year + "'>" + year + "</option>";
    }
    return years;
  }
  let postDate = JSON.parse(localStorage.getItem('json'));
  var today = new Date();
  var currentMonth = today.getMonth();
  var currentYear = today.getFullYear();
  var selectYear = document.getElementById("year");
  var selectMonth = document.getElementById("month");
  
  
  var createYear = generate_year_range(1970, 2050);
  /** or
  * createYear = generate_year_range( 1970, currentYear );
  *
  */
  document.getElementById("year").innerHTML = createYear;
  
  var calendar = document.getElementById("calendar");
  var lang = calendar.getAttribute('data-lang');
  
  var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  
  var dayHeader = "<tr>";
  for (day in days) {
    dayHeader += "<th data-days='" + days[day] + "'>" + days[day] + "</th>";
  }
  dayHeader += "</tr>";
  
  document.getElementById("thead-month").innerHTML = dayHeader;
  
  monthAndYear = document.getElementById("monthAndYear");
  showCalendar(currentMonth, currentYear);
  
  function next() {
    currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
    currentMonth = (currentMonth + 1) % 12;
    showCalendar(currentMonth, currentYear);
  }
  
  function previous() {
    currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
    currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
    showCalendar(currentMonth, currentYear);
  }
  
  function jump() {
    currentYear = parseInt(selectYear.value);
    currentMonth = parseInt(selectMonth.value);
    showCalendar(currentMonth, currentYear);
  }
  
  function showCalendar(month, year) {
  
    var firstDay = ( new Date( year, month ) ).getDay();
  
    tbl = document.getElementById("calendar-body");
  
    
    tbl.innerHTML = "";
  
    
    monthAndYear.innerHTML = months[month] + " " + year;
    selectYear.value = year;
    selectMonth.value = month;
  
    // creating all cells
    var date = 1;
    for ( var i = 0; i < 6; i++ ) {
        var row = document.createElement("tr");
  
        for ( var j = 0; j < 7; j++ ) {
            if ( i === 0 && j < firstDay ) {
                cell = document.createElement( "td" );
                cellText = document.createTextNode("");
                cell.appendChild(cellText);
                row.appendChild(cell);
            } else if (date > daysInMonth(month, year)) {
                break;
            } else {
                cell = document.createElement("td");
                cell.setAttribute("data-date", date);
                cell.setAttribute("data-month", month + 1);
                cell.setAttribute("data-year", year);
                cell.setAttribute("data-month_name", months[month]);
                cell.className = "date-picker";

                count = 0;
                let dateZero = '';

                for(let jkey in postDate) {
                  if (Number(postDate[jkey].day) == date && Number(postDate[jkey].month) == (month + 1)
                      && Number(postDate[jkey].year) == year) {
                    count++;
                    dateZero = postDate[jkey].dayZero;
                  }
                }
                if (count > 0) {
                  cell.innerHTML = `<a class="calendar_url" href="${likesOBJ.archive_url}?day=${dateZero}&month=${month + 1}&year=${year}"><span>` + date + "</span></a>";
                }
                else {
                  cell.innerHTML = "<span>" + date + "</span>";
                }
  
                if ( date === today.getDate() && year === today.getFullYear() && month === today.getMonth() ) {
                    cell.className = "date-picker selected";
                }
                row.appendChild(cell);
                date++;
            }
  
  
        }
  
        tbl.appendChild(row);
    }
  
  }
  
  function daysInMonth(iMonth, iYear) {
    return 32 - new Date(iYear, iMonth, 32).getDate();
  }

  // Search

  if (searchInput) {
    const inday1 = document.getElementById('inday');
    const inmonth1 = document.getElementById('inmonth');
    const inyear1 = document.getElementById('inyear');
    const inday2 = document.getElementById('inday');
    const inmonth2 = document.getElementById('inmonth');
    const inyear2 = document.getElementById('inyear');

    const inday1min = document.getElementById('indaymin');
    const inday2max = document.getElementById('indaymax');
    const inmonth1min = document.getElementById('inmonthmin');
    const inmonth2max = document.getElementById('inmonthmax');
    const inyear1min = document.getElementById('inyearmin');
    const inyear2max = document.getElementById('inyearmax');

    let output = '';

    filtersDisplayer.oninput = (ev) => {
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
    searchInput.oninput = () => {
      searchIcon.classList.add('in-search');
      console.log(searchInput.value);
    };
    searchInput.addEventListener('blur', () => {
      searchIcon.classList.remove('focus-icon');
      searchIcon.classList.remove('in-search');
    });
    openFiltersBtn.addEventListener('click', () => {
      filtersDisplayer.classList.toggle('open-filters');
    });
  }








