<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dash Quiz / Check Records</title>
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
      <img src="{{ auth()->guard('dasher')->user()->get_profile() }}" alt="DP"
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

      <div >
        
      </div>
      <!-- Scrollable Table -->
      <div class="table-wrapper">
        <table id="recordsTable">
          <thead>
              <th>Date taken</th>
              <th>Topics</th>
              <th>Scores</th>
          </thead>
          <tbody>
            @forelse ($records as $record)
              <tr>
                <td>{{ $record->created_at->format('d/M/Y') }}</td>
                <td>{{ $record->quiz->description ?? 'N/A' }}</td>
                <td>{{ $record->score }}/{{ $record->total_questions}}</td>
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