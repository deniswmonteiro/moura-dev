(function () {
  // open lateral menu
  $(".menu-toggle").on("click", function (event) {
    $(".header__menu").toggle();
  });

  // close lateral menu
  $(".menu-toggle-close").on("click", function (event) {
    $(".header__menu").hide();
  });

  // header profile dropdown
  $(".header__profile-button").on("click", function (event) {
    const dropdownOptions = $(".header__profile-dropdown-options");
    $(this).next(".header__profile-dropdown-options").slideToggle();
    dropdownOptions.css("display", "flex");

    const target = event.target;

    if (
      !$(target).is(".header__profile-button") &&
      !$(target).parents().is(".header__profile")
    ) {
      $(".header__profile-dropdown-options").hide();
    }
  });
})(jQuery);
