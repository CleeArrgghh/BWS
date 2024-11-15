document.querySelectorAll(".view-button").forEach((button) => {
  button.addEventListener("click", function () {
    const categoryId = this.getAttribute("data-category-id");

    fetch(`load_services.php?category_id=${categoryId}`)
      .then((response) => response.text())
      .then((data) => {
        document.getElementById("modal-services-content").innerHTML = data;
      })
      .catch((error) => console.error("Error:", error));
  });
});

// Flag to track if close button was clicked
let closeButtonClicked = false;

// When the close button is clicked, set the flag to true
document
  .getElementById("modal-close-button")
  .addEventListener("click", function () {
    closeButtonClicked = true;
  });

// Also handle the footer close button
document
  .getElementById("footer-close-button")
  .addEventListener("click", function () {
    closeButtonClicked = true;
  });

// Handle the modal close event
$("#servicesModal").on("hide.bs.modal", function (e) {
  if (!closeButtonClicked) {
    e.preventDefault(); // Prevent the modal from closing
    e.stopImmediatePropagation();
  } else {
    closeButtonClicked = false; // Reset the flag for the next time
  }
});
