<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
  <!-- Sidebar -->
  @include('User_Folder.Sidebar')

  <!-- Top Bar -->
  <header>
    <div class="top-bar">
      <button class="menu-btn" id="menuBtn">&#9776;</button>
      <h2>Welcome {{ auth()->guard('dasher')->user()->first_name }}!</h2>
      
      <a href="{{ route('logout-user') }}" class="logout-btn">Log Out</a>
    </div>
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
  </main>

  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>