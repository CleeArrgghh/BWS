// Search functionality
const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("searchInput");
const serviceList = document.getElementById("serviceList");

searchForm.addEventListener("submit", (event) => {
  event.preventDefault(); // Prevent page reload

  const searchTerm = searchInput.value.toLowerCase();

  // Filter service cards based on search term
  const serviceCards = serviceList.querySelectorAll(".col-md-4");
  serviceCards.forEach((card) => {
    const serviceName = card.dataset.serviceName.toLowerCase();
    if (serviceName.includes(searchTerm)) {
      card.style.display = "block"; // Show matching cards
    } else {
      card.style.display = "none"; // Hide non-matching cards
    }
  });
});

// Reset search results when the search input is cleared
searchInput.addEventListener("input", () => {
  if (searchInput.value === "") {
    // Show all service cards again
    const serviceCards = serviceList.querySelectorAll(".col-md-4");
    serviceCards.forEach((card) => {
      card.style.display = "block";
    });
  }
});
