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
    <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
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
            <tr>
              <td>10/19/2025</td>
              <td>Topic 10</td>
              <td>5/5</td>
            </tr>
            <tr>
              <td>10/18/2025</td>
              <td>Topic 11</td>
              <td>4/5</td>
            </tr>
            <tr>
              <td>10/17/2025</td>
              <td>Topic 12</td>
              <td>5/5</td>
            </tr>
            <tr>
              <td>10/16/2025</td>
              <td>Topic 13</td>
              <td>3/5</td>
            </tr>
            <tr>
              <td>10/15/2025</td>
              <td>Topic 14</td>
              <td>4/5</td>
            </tr>
            <tr>
              <td>10/14/2025</td>
              <td>Topic 15</td>
              <td>2/5</td>
            </tr>
            <tr>
              <td>10/13/2025</td>
              <td>Topic 16</td>
              <td>4/5</td>
            </tr>
            <tr>
              <td>10/12/2025</td>
              <td>Topic 17</td>
              <td>5/5</td>
            </tr>
            <tr>
              <td>10/11/2025</td>
              <td>Topic 18</td>
              <td>5/5</td>
            </tr>
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