// aside filter
function asideSelectCustom() {
  const inputDateWrapper = document.getElementsByClassName(
    "custom-select__date-wrapper"
  )[0];

  for (const dropdown of document.querySelectorAll(".custom-select__wrapper")) {
    dropdown.addEventListener("click", function () {
      this.classList.toggle("active");
    });
  }

  for (const option of document.querySelectorAll(".custom-select__option")) {
    option.addEventListener("click", function () {
      if (!this.classList.contains("selected")) {
        this.parentNode
          .querySelector(".custom-select__option.selected")
          .classList.remove("selected");
        this.classList.add("selected");
        this.closest(".custom-select__wrapper").querySelector(
          ".custom-select__button input"
        ).value = this.textContent;

        // show inputs for personalized date
        if (
          this.closest(".custom-select__wrapper").querySelector(
            ".custom-select__button input"
          ).value === "Intervalo personalizado"
        ) {
          inputDateWrapper.style.display = "flex";
        } else {
          inputDateWrapper.style.display = "none";
        }
      }
    });
  }

  window.addEventListener("click", function (e) {
    for (const select of document.querySelectorAll(".custom-select__wrapper")) {
      if (!select.contains(e.target)) {
        select.classList.remove("active");
      }
    }
  });
}

asideSelectCustom();
