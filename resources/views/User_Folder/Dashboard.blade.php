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
      <h2>Welcome {{ auth()->guard('dasher')->user()->first_name }}!</h2> {{-- display current user name --}}

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

    <table class="quiz-table" style="width:100%; border-collapse: collapse;">
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

        <tr @if($leader->user->id == $currentUserId) style="background-color: lightgreen;" @endif>
          <td style="text-align:center;">{{ $index + 1 }}</td>

          <td>
            <div style="display: flex; align-items: center; gap: 10px;">
              <img src="{{ $leader->user->profilePhotoUrl() }}" alt="DP"
                style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #ccc;">
              <span>
                {{ $leader->user->first_name . ' ' . $leader->user->last_name }}
                @if($leader->user->id == $currentUserId)
                  (You)
                @endif
              </span>
            </div>
          </td>

          <td>{{ $leader->quiz->title }}</td>
          <td style="text-align:center;">{{ $leader->score }}</td>
        </tr>
      @endforeach

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