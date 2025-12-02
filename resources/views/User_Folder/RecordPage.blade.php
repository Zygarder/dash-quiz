<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dash Quiz | Check Records</title>
  <link rel="stylesheet" href="{{asset('css/style.css')}}" />
  <link rel="stylesheet" href="{{ asset('css/record.css') }}">
</head>

<body>
  <!-- Sidebar -->
  @include('User_Folder.Sidebar')

  <!-- Top Bar -->
  <header class="top-bar">
        <div class="menu-btn" id="menuBtn">&#9776;</div>
        <a href="{{ route('profile-page') }}">
            <img src="{{ auth()->guard('dasher')->user()->profile() }}" alt="DP"
                style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:1px solid #ccc;">
        </a>
    </header>

  <!-- Main Content -->
  <main class="main-content" id="mainContent">
    <div class="records-container">
      <h3 class="records-title">Your Quiz History</h3>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="searchInput" placeholder="Search topic or score..." />
      </div>

      <div class="header-lists">
        <div>Dates</div>
        <div>Topics</div>
        <div>Scores</div>
      </div>
      <!-- Scrollable Table -->
      <div class="table-wrapper">
        <table id="recordsTable">
          <tbody>
            @forelse ($records as $record)
              <tr>
                <td>{{ $record->completed_at }}</td>
                <td>{{ $record->quiz->title ?? 'N/A' }}</td>
                <td>{{ $record->score }}/{{ $record->quiz->questions->count() ?? 10 }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3">No records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </main>

  <!-- Sidebar Toggle Script -->
  <script src="{{ asset('js/sidebar_function.js') }}"></script>
  <script>
    const users = JSON.parse(localStorage.getItem("users"));
    document.querySelector("#user").textContent = users[0].name;
    console.log(users);
  </script>
</body>

</html>