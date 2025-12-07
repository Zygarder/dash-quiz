<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz - Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .leaderboard {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      margin-top: 10px;
    }

    .leaderboard th {
      background: #222;
      color: #fff;
      padding: 10px;
    }

    .leaderboard td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }

    /* Avatar + name flex */
    .dash-user {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Profile image */
    .dash-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ccc;
    }

    /* Name label */
    .dash-name {
      font-weight: bold;
      color: #333;
    }

    /* "You" label */
    .you-tag {
      color: #007bff;
      font-size: 0.9em;
    }

    /* Hover effect */
    .leaderboard tr:hover {
      background: #f5faff;
    }

    /* Highlight logged-in user */
    .leaderboard tr.you {
      background: #e8f4ff !important;
      border-left: 4px solid #007bff;
    }

    /* No-score row */
    .no-score {
      text-align: center;
      font-style: italic;
      color: gray;
    }
  </style>
</head>

<body data-user-id="{{ auth()->guard('dasher')->id() }}">
  <!-- Sidebar -->
  @include('User_Folder.Sidebar')

  <!-- Top Bar -->
  <header>
    <div class="top-bar">
      <button class="menu-btn" id="menuBtn">&#9776;</button>
      <div>
        <h2>Welcome {{ auth()->guard('dasher')->user()->first_name }}!</h2> {{-- display current user name --}}
      </div>

      <a href="{{ route('profile-page') }}">
        <img src="{{ auth()->guard('dasher')->user()->get_profile() }}" alt="DP"
          style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #ccc;">
      </a>
    </div>
  </header>

  <!--success key, pls ayaw payaa, ikaw ra ini nagbutangh sa controller.-->
  @if (session('success'))
    <div style="padding:10px; background:lightgreen; margin-bottom:10px; border:1px solid green;">
      {{ session('success') }}
    </div>
  @endif

  <!-- Main Content -->
  <main class="dashboard">
    <h3>Leaderboard (Top Scores)</h3>

    <br>
    <table class="quiz-table leaderboard">
      <thead>
        <tr>
          <th>Rank</th>
          <th>Dasher</th>
          <th>Quiz Title</th>
          <th>Best Score</th>
        </tr>
      </thead>
      <tbody class="leaderboard-body"></tbody>
    </table>

  </main>

  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
  <script src="{{ asset('js/leaderboard.js') }}"></script>
</body>

</html>