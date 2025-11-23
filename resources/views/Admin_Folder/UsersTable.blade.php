<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dash Quiz Admin | Users Table</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
  <header class="admin-header">
    <h2>Dash Quiz Admin Dashboard</h2>
    <a href="{{ route('adminlogout') }}" class="logout-btn">Log Out</a>
  </header>

  <div class="admin-container">
    <aside class="admin-sidebar">
      <h3 class="sidebar-title">Admin Menu</h3>
      <nav>
        <ul>
          <li><a href="{{ route('admin-board') }}">Dashboard</a></li>
          <li><a href="{{ route('quiz-manage') }}">Manage Quizzes</a></li>
          <li class="active"><a href="{{ route('user-table') }}">Users Table</a></li>
          <li><a href="{{ route('srecords') }}">Student Records</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-main">
      <section class="admin-section">

        @if (session('success'))
        <div style="padding:10px; background:lightgreen; margin-bottom:10px; border:1px solid green;">
            {{ session('success') }}
        </div>
    @endif

        <h3 class="section-title">Registered Users</h3>

        <div class="admin-table">
          <div class="table-header">
            <span>User ID</span>
            <span>First Name</span>
            <span>Last Name</span>
            <span>Email</span>
            <span>Date Registered</span>
            <span>Action</span>
          </div>

          @foreach ($dasher as $user)
          <div class="table-row">
            <span>{{ $user->id }}</span>
            <span>{{ $user->first_name }}</span>
            <span>{{ $user->last_name }}</span>
            <span>{{ $user->email }}</span>
            <span>{{ $user->created_at }}</span>
            <span>
              <form action="{{ route('deleteuser', $user->id) }}" method="POST">
                @csrf
                <button class="action-btn delete">Delete</button>
            </form>
            </span>
          </div>
          @endforeach
        </div>
      </section>
    </main>
  </div>

</body>
</html>
