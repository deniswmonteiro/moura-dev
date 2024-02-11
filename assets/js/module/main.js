$(document).ready(function() {
  /** Select2 configuration */
  $(".custom-select__item, .custom-form__wrapper__select").select2({
    language: "pt-BR",
    dropdownParent: $("#custom-form")
  }).on("select2:open", function(e){
    $(".select2-search__field").attr("placeholder", "Busca pelo nome");
  });

  $(".custom-select__item.no-search, .custom-form__wrapper__select.no-search").select2({
    minimumResultsForSearch: Infinity,
    dropdownParent: $("#custom-form")
  });

  showNotifications();
});

/** Set active navbar */
function setActiveNavbar () {
  const headerNavbarTitle = document.querySelector(".header__navbar .header__navbar-title h2");
  const page = document.querySelector("meta[name='page']");

  headerNavbarTitle.innerText = page.content.replaceAll("-", " ").toUpperCase();

  if (page.content === "dashboard" || page.content === "meus-dados") {
    const headerNavbarMenu = document.querySelector(".header__navbar-wrapper");

    headerNavbarMenu.remove();
  }

  else {
    const navbarMenuItems = document.querySelectorAll(".header__navbar-list");

    navbarMenuItems.forEach((navbar) => {
      if (navbar.dataset.navbar !== page.content) navbar.remove();
    });
  }
}

setActiveNavbar();

/** Set active navbar menu item */
function setActiveHeaderMenu () {
  const headerNavbarMenuItems = document.querySelectorAll(".header__navbar-list li a");
  const url = window.location.href;

  headerNavbarMenuItems.forEach((item) => {
    if (item.classList.contains("active")) item.classList.remove("active");

    if (item.href === url) item.classList.add("active");
  });
}

setActiveHeaderMenu();

/** Show Custom Period Date on aside filter */
window.changeFilterCustomPeriod = (el) => {
  const customPeriodDate = document.querySelector("#custom-period-date");

  if (el.selectedIndex === 5) {
    customPeriodDate.classList.add("active");
    customPeriodDate.closest(".aside-filter").style.height = "530px";
  }
  
  else {
    customPeriodDate.classList.remove("active");
    customPeriodDate.closest(".aside-filter").style.height = "475px";
  }
}

/** Show/hide notifications */
function showNotifications() {
  const page = document.querySelector("meta[name='page']");
  const notifications = document.querySelector(".header__navbar-notifications");

  if (page.content === "plataforma-de-artes") notifications.classList.add("active");
  else notifications.classList.remove("active");
}