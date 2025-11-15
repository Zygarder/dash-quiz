<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Account</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <!-- Top Bar -->
    <header class="top-bar">
      <p>New Account</p>
    </header>

    <!-- Main Content -->
    <main class="center-container">
      <div class="register-box">
        <h2>Account Registration</h2>

        <input type="name" name="name" placeholder="Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input
          type="password"
          name="pass"
          placeholder="New Enter Password"
          required
        />

        <input
          type="password"
          name="pass_confirm"
          placeholder="Re-type Password"
          required
        />

        <button class="register-btn" onclick="registerUser()">Submit</button>

        <p class="small-text">
          Already have an account? <a href="index.html">click here</a>
        </p>
      </div>
    </main>

    <!-- Footer -->
    <footer>
      <p>© 2025 Dash Quiz All Rights Reserved.</p>
    </footer>

    <script>
      const data = JSON.parse(localStorage.getItem("users")) || [];

      function registerUser() {
        console.log("click");
        let name = document.querySelector("#name").value;
        let email = document.querySelector("#email").value;
        let pass = document.querySelector("#pass").value;
        let pass_confirm = document.querySelector("#pass_confirm").value;

        data.push({
          name: name,
          email: email,
          pass: pass,
          pass_confirm: pass_confirm,
        });
        console.table(data);

        localStorage.setItem("users", JSON.stringify(data));
      }

      console.log(data);
      // localStorage.removeItem('users')
    </script>
  </body>
</html>
