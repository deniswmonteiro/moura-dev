const calendarDateInput1 = document.querySelector("#calendar-date-1");
const calendarDateInput2 = document.querySelector("#calendar-date-2");

const optionsCalendar1 = {
    input: true,
    settings: {
        lang: "pt-BR",
        iso8601: false,
    },
    actions: {
        showCalendar(input, calendar) {
            const page = document.querySelector("meta[name='page']");

            if (page.content === "plataforma-de-artes") calendar.classList.add("search");
            else calendar.classList.remove("search");

            if (!calendar.classList.contains("vanilla-calendar_hidden")) {
                input.parentElement.classList.add("active");
            }
        },
        hideCalendar(input, calendar) {
            if (calendar.classList.contains("vanilla-calendar_hidden")) {
                input.parentElement.classList.remove("active");
            }
        },
        changeToInput(e, calendar, dates) {
            const formatedDate = `${dates[0].split("-")[2]}/${dates[0].split("-")[1]}/${dates[0].split("-")[0]}`;

            if (formatedDate) {
                calendar.HTMLInputElement.value = formatedDate;
                calendar.HTMLInputElement.setAttribute("value", `${dates[0]}`);
                calendar.hide();

                // Calendar 2
                calendarDateInput2.removeAttribute("disabled");
                calendarDateInput2.classList.remove("disabled");

                const calendar2 = getCalendar2Options(calendarDateInput2, dates[0]);
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
            }
            
            else calendar.HTMLInputElement.value = "";
        },
    },
};

/** Calendar position */
function addClassToCalendar() {
    const calendars = document.querySelectorAll(".vanilla-calendar");
    
    if (calendars.length > 1) calendars[1].classList.add("calendar-2");
}

/** Second calendar options */
function getCalendar2Options(calendarDateInput, date) {
    return new VanillaCalendar(calendarDateInput, {
        input: true,
        settings: {
            lang: "pt-BR",
            iso8601: false,
            range: {
                min: `${date}`
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
            changeToInput(e, calendar, dates) {
                const formatedDate = `${dates[0].split("-")[2]}/${dates[0].split("-")[1]}/${dates[0].split("-")[0]}`;

                if (formatedDate) {
                    calendar.HTMLInputElement.value = formatedDate;
                    calendar.HTMLInputElement.setAttribute("value", `${dates[0]}`);
                    calendar.hide();
                }
            }
        },
    });
}

const calendar1 = new VanillaCalendar(calendarDateInput1, optionsCalendar1);
calendar1.init();