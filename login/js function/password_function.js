// kina na function kay  mag determine kn match bah ang password sa confirm password.

function checkPasswordMatch() {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm-password").value;
  const messageDiv = document.getElementById("password-message");

  if (confirmPassword === "") {
    messageDiv.textContent = ""; // Clear message if confirm password is empty
  } else if (password === confirmPassword && password !== "") {
    messageDiv.textContent = "Passwords match.";
    messageDiv.classList.remove("text-danger");
    messageDiv.classList.add("text-success");
  } else if (password !== "") {
    messageDiv.textContent = "Passwords do not match.";
    messageDiv.classList.remove("text-success");
    messageDiv.classList.add("text-danger");
  } else {
    messageDiv.textContent = ""; // Clear message if password is empty
  }
}

