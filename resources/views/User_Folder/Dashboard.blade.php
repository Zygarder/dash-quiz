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

<body>
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
        <img src="{{ auth()->guard('dasher')->user()->profile() }}" alt="DP"
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

          // Rank medals for the 1st, 2nd and 3rd placer
          $medal = '';
          if ($index === 0)
            $medal = '🥇';
          elseif ($index === 1)
            $medal = '🥈';
          elseif ($index === 2)
            $medal = '🥉';
        @endphp

        <tr class="{{ $leader->user->id == $currentUserId ? 'you' : '' }}">
          <td>
            <strong>
              @if($medal)
                {{ $medal }}
              @else
                #{{ $index + 1 }}
              @endif
            </strong>
          </td>

          <td>
            <div class="dash-user">
              <img src="{{ $leader->user->profile() }}" class="dash-avatar" alt="DP">

              <div class="dash-name">
                {{ $leader->user->first_name }} {{ $leader->user->last_name }}
                @if($leader->user->id == $currentUserId)
                  <span class="you-tag">(You)</span>
                @endif
              </div>
            </div>
          </td>

          <td>{{ $leader->quiz->title }}</td>

          <td><strong>{{ $leader->score }}</strong></td>
        </tr>
      @endforeach

      @if(!$foundYou)
        <tr>
          <td colspan="4" class="no-score">
            You have no quiz scores yet.
          </td>
        </tr>
      @endif
    </table>

  </main>

  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
</body>

</html>