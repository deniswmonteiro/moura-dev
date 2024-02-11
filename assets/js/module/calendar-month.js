const calendarDateInput1 = document.querySelector("#calendar-date-1");
const calendarDateInput2 = document.querySelector("#calendar-date-2");

const optionsCalendar1 = {
  type: "month",
  input: true,
  settings: {
    lang: "pt-BR",
    iso8601: false,
  },
  actions: {
    showCalendar(input, calendar) {
      if (!calendar.classList.contains("vanilla-calendar_hidden")) {
          input.parentElement.classList.add("active");
      }
    },
    hideCalendar(input, calendar) {
        if (calendar.classList.contains("vanilla-calendar_hidden")) {
            input.parentElement.classList.remove("active");
        }
    },
    clickMonth(e, selectedMonth, selectedYear) {
      e.target.closest(".vanilla-calendar").classList.add("vanilla-calendar_hidden");

      const month = formatMonth(selectedMonth + 1);
      const year = selectedYear.toString();

      calendarDateInput1.value = `${month}/${year}`;
      calendarDateInput1.setAttribute("value", `${year}-${month}`);

      // Calendar 2
      calendarDateInput2.removeAttribute("disabled");
      calendarDateInput2.classList.remove("disabled");
      calendarDateInput2.value = `${formatMonth(selectedMonth + 2)}/${year}`;
      calendarDateInput2.setAttribute("value", `${year}-${formatMonth(selectedMonth + 2)}`);

      const calendar2 = getCalendar2Options(calendarDateInput2, selectedMonth, year);
      const calendars = document.querySelectorAll(".vanilla-calendar");
      
      if (calendars.length === 1) {
        calendar2.init();
        calendarDateInput2.addEventListener("click", addClassToCalendar);
      }

      else {
        calendars[1].remove();
        calendar2.init();

        calendarDateInput2.addEventListener("click", () => {
          const calendar = document.querySelectorAll(".vanilla-calendar");

          if (calendar.length > 1) calendar[1].classList.add("calendar-2");
        });
      }
    },
  },
};

/** Calendar position */
function addClassToCalendar() {
  const calendars = document.querySelectorAll(".vanilla-calendar");
  
  if (calendars.length > 1) calendars[1].classList.add("calendar-2");
}

/** Second calendar options */
function getCalendar2Options(calendarDateInput, selectedMonth, year) {
  return new VanillaCalendar(calendarDateInput, {
    type: "month",
    input: true,
    settings: {
      visibility: {
        positionToInput: 'center',
      },
      lang: "pt-BR",
      iso8601: false,
      range: {
        min: `${year}-${formatMonth(selectedMonth + 2)}`
      },
      selected: {
        month: selectedMonth + 1,
      },
    },
    actions: {
      showCalendar(input, calendar) {
        if (!calendar.classList.contains("vanilla-calendar_hidden")) {
            input.parentElement.classList.add("active");
        }
      },
      hideCalendar(input, calendar) {
          if (calendar.classList.contains("vanilla-calendar_hidden")) {
              input.parentElement.classList.remove("active");
          }
      },
      clickMonth(e, selectedMonth, selectedYear) {
        e.target.closest(".vanilla-calendar").classList.add("vanilla-calendar_hidden");

        const month = formatMonth(selectedMonth + 1);
        const year = selectedYear.toString();
  
        calendarDateInput.value = `${month}/${year}`;
        calendarDateInput.setAttribute("value", `${year}-${month}`);
      },
    },
  });
}

/** Format month string */
function formatMonth(month) {
  return (month.toString().length === 1) ? `0${(month).toString()}` : (month).toString();
}

const calendar1 = new VanillaCalendar(calendarDateInput1, optionsCalendar1);
calendar1.init();