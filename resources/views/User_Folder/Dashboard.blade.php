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

  <!--success key, pls ayaw payaa, ikaw ra ini nagbutangh sa controller.-->

  @if (session('success'))
    <div style="padding:10px; background:lightgreen; margin-bottom:10px; border:1px solid green;">
      {{ session('success') }}
    </div>
  @endif

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

      @php
        $currentUserId = auth()->guard('dasher')->user()->id;
        $foundYou = false;
      @endphp

      @foreach ($leaders as $index => $leader)
        @php
          if ($leader->user->id == $currentUserId) {
            $foundYou = true;
          }
        @endphp

        <tr>
          <td>{{ $index + 1 }}</td>

          {{-- Highlight logged-in user --}}
          @if($leader->user->id == $currentUserId)
            <td>{{ $leader->user->first_name . ' ' . $leader->user->last_name }} (You)</td>
          @else
            <td>{{ $leader->user->first_name . ' ' . $leader->user->last_name }}</td>
          @endif

          <td>{{ $leader->quiz->title }}</td>
          <td>{{ $leader->score }}</td>
        </tr>
      @endforeach

      {{-- If logged-in user has no score, show a message --}}
      @if(!$foundYou)
        <tr>
          <td colspan="4" style="text-align:center; font-style:italic; color:gray;">
            You have no quiz scores yet.
          </td>
        </tr>
      @endif
    </table>


    <br>
  </main>


  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>