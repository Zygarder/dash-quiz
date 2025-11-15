<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz - Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- Sidebar -->
  <nav id="sidebar" class="sidebar">
    <a href="{{ route('user-board') }}">Dashboard</a>
    <a href="{{ route('quiz-page') }}">Take a Quiz</a>
    <a href="#">Check Records</a>
    <a href="{{ route('quiz-page') }}">My Profile</a>
  </nav>

  <!-- Top Bar -->
  <header class="top-bar">
    <div class="menu-btn" id="menuBtn">&#9776;</div>
    <p>Welcome, user!</p>
    <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
  </header>

  <!-- Main Content -->
  <main class="dashboard">
    <h3>Recent Quizzes:</h3>
    <table class="quiz-table">
      <tr>
        <th>Date</th>
        <th>Topic</th>
        <th>Score</th>
      </tr>
      <tr>
        <td>MM/DD/YYYY</td>
        <td>LATEST TOPIC</td>
        <td>SCORE</td>
      </tr>
      <tr>
        <td>MM/DD/YYYY</td>
        <td>TOPIC</td>
        <td>SCORE</td>
      </tr>
      <tr>
        <td>MM/DD/YYYY</td>
        <td>TOPIC</td>
        <td>SCORE</td>
      </tr>
      <tr>
        <td>MM/DD/YYYY</td>
        <td>TOPIC</td>
        <td>SCORE</td>
      </tr>
      <tr>
        <td>MM/DD/YYYY</td>
        <td>OLDEST TOPIC</td>
        <td>SCORE</td>
      </tr>
    </table>
    <br>
    <button type="button">Admin Mode</button>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 Dash Quiz | All Rights Reserved.</p>
  </footer>

  <script src="js\script.js"></script>
</body>
</html>
