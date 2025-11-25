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
    <h3>Leaderboard (Top Scores)</h3>

    <table class="quiz-table">
      <tr>
        <th>Rank</th>
        <th>Dasher</th>
        <th>Quiz Title</th>
        <th>Best Score</th>
      </tr>

      @foreach ($leaders as $index => $leader)
        <tr>
          <td>{{ $index + 1 }}</td>
          {{-- Checks if its you --}}
          @if($leader->user->id == auth()->guard('dasher')->user()->id)
            <td>{{ $leader->user->first_name . ' ' . $leader->user->last_name }}(You)</td>
          @else
            {{-- add other --}}
            <td>{{ $leader->user->first_name . ' ' . $leader->user->last_name }}</td>
          @endif
          <td>{{ $leader->quiz->title }}</td>
          <td>{{ $leader->score }}</td>
        </tr>
      @endforeach
    </table>

    <br>
  </main>


  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>