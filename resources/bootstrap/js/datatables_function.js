function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("active");
}

$(document).ready(function () {
  $("#serviceTable").DataTable({
    pageLength: 3, // Set the number of records per page
    responsive: true, // Make the table responsive
  });
});
