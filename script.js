// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {

    // Validate registration form
    const registerForm = document.querySelector("form[action='auth.php'][method='POST']");
    if (registerForm && registerForm.querySelector("input[name='action'][value='register']")) {
        registerForm.addEventListener("submit", function (e) {
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();

            if (name === "" || email === "" || password === "") {
                alert("All fields are required!");
                e.preventDefault(); // Prevent form submission
                return;
            }

            // Basic email pattern check
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address!");
                e.preventDefault();
                return;
            }

            // Password length check
            if (password.length < 6) {
                alert("Password must be at least 6 characters long!");
                e.preventDefault();
            }
        });
    }

    // Validate login form
    const loginForm = document.querySelector("form[action='auth.php'][method='POST']");
    if (loginForm && loginForm.querySelector("input[name='action'][value='login']")) {
        loginForm.addEventListener("submit", function (e) {
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();

            if (email === "" || password === "") {
                alert("Both email and password are required!");
                e.preventDefault(); // Prevent form submission
                return;
            }

            // Basic email pattern check
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address!");
                e.preventDefault();
            }
        });
    }
});
              
