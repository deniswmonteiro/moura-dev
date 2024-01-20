const modal = document.getElementById("modal");
const btnCloseModal = document.getElementsByClassName(
  "modal-wrapper__button-close"
)[0];

for (const tableModal of document.querySelectorAll(
  ".table__dropdown-modal-toggle"
)) {
  tableModal.addEventListener("click", function () {
    modal.style.display = "flex";
  });
}

btnCloseModal.onclick = function () {
  modal.style.display = "none";
};

window.addEventListener("click", function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
});
