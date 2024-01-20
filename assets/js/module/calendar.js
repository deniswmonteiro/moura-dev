const options = {
  input: true,
  settings: {
    lang: "pt-BR",
    iso8601: false,
  },
  actions: {
    changeToInput(e, calendar, dates) {
      const newDate = new Date(dates[0]).toLocaleDateString("pt-BR");
      if (newDate) {
        calendar.HTMLInputElement.value = newDate;
        // if you want to hide the calendar after picking a date
        calendar.hide();
      } else {
        calendar.HTMLInputElement.value = "";
      }
    },
  },
};

const calendar1 = new VanillaCalendar("#calendar-date-1", options);
calendar1.init();

const calendar2 = new VanillaCalendar("#calendar-date-2", options);
calendar2.init();