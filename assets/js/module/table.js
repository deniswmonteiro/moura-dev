// table menu dropdown
for (const tableDropdown of document.querySelectorAll(".table__body-tr")) {
  tableDropdown.addEventListener("click", function () {
    this.classList.toggle("active");
  });
}

window.addEventListener("click", function (e) {
  for (const tableToggle of document.querySelectorAll(".table__body-tr")) {
    if (!tableToggle.contains(e.target)) {
      tableToggle.classList.remove("active");
    }
  }
});

/** Change Participant Details table view */
window.changeAwardingTable = (el) => {
  const participantsDetailsButtons =  document.querySelectorAll("#table-participant-details-buttons div");

  participantsDetailsButtons.forEach((button) => button.classList.remove("active"));
  el.classList.add("active");
}