function editCategory(id, name, description, image) {
  // Populate the form fields with the category data
  document.getElementById("categoryId").value = id;
  document.getElementById("categoryName").value = name;
  document.getElementById("categoryDescription").value = description;

  // If image is available, you might want to handle it here as well
  // For now, we assume the image is shown in the table and doesn't need to be handled in the form

  // Change the submit button text to "Update Category"
  document.getElementById("submitButton").innerText = "Update Category";

  // Optionally, scroll to the form for better user experience
  window.scrollTo({
    top: document.getElementById("categoryName").offsetTop,
    behavior: "smooth",
  });
}

// Optional function to reset the form
function resetForm() {
  document.getElementById("categoryId").value = "";
  document.getElementById("categoryName").value = "";
  document.getElementById("categoryDescription").value = "";
  document.getElementById("categoryImage").value = "";
  document.getElementById("submitButton").innerText = "Add Category";
}

function editCategory(id, name, description, image) {
  // Populate form fields with data
  document.getElementById("categoryId").value = id;
  document.getElementById("categoryName").value = name;
  document.getElementById("categoryDescription").value = description;

  // Set the image preview if needed
  var imagePreview = document.getElementById("imagePreview");
  if (imagePreview) {
    imagePreview.src = "../bws_ui/images/" + image;
  }

  // Change button text to "Update"
  document.getElementById("submitButton").textContent = "Update Category";
}

