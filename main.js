// Get button elements by ID
const loginBtn = document.getElementById("loginBtn");
const registerBtn = document.getElementById("registerBtn");

// Redirect to login.php when login button is clicked
loginBtn.addEventListener("click", () => {
  window.location.href = "login.php";
});

// Redirect to register.php when register button is clicked
registerBtn.addEventListener("click", () => {
  window.location.href = "register.php";
});
