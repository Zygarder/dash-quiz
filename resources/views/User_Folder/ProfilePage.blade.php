<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dash Quiz / User Profile</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/profile-page.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

  <style>
    .alert-error,
    .alert-success {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 999;
      border-radius: 10px;
      width: 300px;
      padding: 10px;
      display: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .alert-error {
      background-color: pink;
    }

    .alert-success {
      background-color: green;
    }

    .alert-side.active {
      display: block;
    }

    .alert-close {
      position: absolute;
      top: 5px;
      right: 10px;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
    }

    .alert-body {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .alert-body div {
      font-size: 12px;
      margin-bottom: 5px;
    }

    .picture-set {
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      font-family: sans-serif;
    }

    .file-label {
      display: inline-block;
      padding: 8px 25px;
      background-color: #007BFF;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      margin-bottom: 10px;
      transition: background-color 0.3s ease;
    }

    .file-label:hover {
      background-color: #0056b3;
    }

    .file-input {
      display: none;
    }

    .upload-btn {
      padding: 10px 25px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .upload-btn:hover {
      background-color: black;
    }
  </style>
</head>

<body>

  @include('User_Folder.Sidebar')

  <header class="top-bar">
    <button class="menu-btn" id="menuBtn">&#9776;</button>
    <h2>My Profile</h2>
    <a href="{{ route('logout-user') }}" class="logout-btn">Log Out</a>
  </header>
  <!--success key, pls ayaw payaa, ikaw ra ini nagbutangh sa controller.-->
  @if (session('success'))
    <div style="padding:10px; background:lightgreen; position: absolute; border:1px solid green;">
      {{ session('success') }}
    </div>
  @endif

  <main class="main-content" id="mainContent">
    <div class="profile-card">

      <div class="profile-header">
        <div class="avatar">
          <img src="{{ $dasher->profile_photo
  ? asset('storage/images/profiles/' . $dasher->profile_photo)
  : asset('images/profiles/person.jpg') }}" alt="DP">
        </div>

        <h2 id="username">{{ $dasher->first_name }}</h2>
        <small id="userEmail">{{ $dasher->email }}</small>

        <form action="{{ route('profile.upload') }}" class="picture-set" method="POST" enctype="multipart/form-data">
          @csrf
          <label class="file-label">
            Change Profile
            <input type="file" name="myfile" class="file-input">
          </label>
          <button type="submit" class="upload-btn">Upload</button>
        </form>
      </div>

      <div class="profile-details">
        <div class="detail-row">
          <span>Full Name:</span>
          <b id="fullName">{{ $fullname }}</b>
        </div>

        <div class="detail-row">
          <span>Date Joined:</span>
          <b id="dateJoined">{{ $dasher->created_at->format('M-d-Y') }}</b>
        </div>

        <div class="detail-row">
          <span>Quizzes Taken:</span>
          <b id="quizzesTaken">{{ $quizzesCount ?: 'N/A' }}</b>
        </div>
      </div>

      <div class="profile-buttons">
        <button id="edit-profile">Edit Profile</button>
        <button id="delete">Delete Account</button>
      </div>

    </div>
  </main>

  <!-- ERROR ALERT -->
  <div class="alert-error" id="alertError">
    <div class="alert-close" id="alertCloseError">&times;</div>
    <div class="alert-body">
      @if ($errors->any())
        @foreach ($errors->all() as $error)
          <div style="color: red;">{{ $error }}</div>
        @endforeach
      @endif

      @if (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
      @endif
    </div>
  </div>

  <!-- SUCCESS ALERT -->
  <div class="alert-success" id="alertSuccess">
    <div class="alert-close" id="alertCloseSuccess">&times;</div>
    <div class="alert-body">
      @if (session('success'))
        <div style="color: white;">{{ session('success') }}</div>
      @endif
    </div>
  </div>

  <!-- EDIT PROFILE MODAL -->
  <div class="modal" id="editProfileModal">
    <div class="modal-content">
      <h3>Edit Profile</h3>
      <form action="{{ route('profile-update') }}" method="POST">
        @csrf
        @method('PUT')
        <label>Full Name</label>
        <input type="text" name="fullName" value="{{ $dasher->first_name . ' ' . $dasher->last_name }}" />

        <label>Email</label>
        <input type="email" value="{{ $dasher->email }}" />

        <label>Current Password</label>
        <input type="password" name="current_password" autocomplete="false" />

        <label>New Password</label>
        <input type="password" name="new_password" autocomplete="false" />

        <label>Confirm New Password</label>
        <input type="password" name="new_confirm_password" autocomplete="false" />

        <div class="modal-buttons">
          <button type="button" class="cancel-btn" id="cancelEdit">Cancel</button>
          <button type="submit" class="save-btn">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- CHANGE PASSWORD MODAL -->
  <div class="modal" id="changePassModal">
    <div class="modal-content">
      <h3 style="text-align:center">Delete Account</h3>
      <form action="{{ route('user-delete-account') }}" method="POST">
        @csrf
        @method('DELETE')
        {{-- profile-new-password --}}

        <input type="hidden" name="id" value="{{ $dasher->id }}">
        <p class="small-text">Are you sure you wanna delete your account?</p>

        <div class="modal-buttons">
          <button type="button" class="cancel-btn" id="cancelDelete">Cancel</button>
          <button type="submit" class="save-btn">Yes, of course</button>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/sidebar_function.js') }}"></script>

  <script>
    function getID(target) {
      return document.querySelector("#" + target)
    }

    // ===== MODALS =====
    getID("edit-profile").onclick = () =>
      getID("editProfileModal").style.display = "flex";

    getID("cancelEdit").onclick = () =>
      getID("editProfileModal").style.display = "none";

    getID("delete").onclick = () =>
      getID("changePassModal").style.display = "flex";

    getID("cancelDelete").onclick = () =>
      getID("changePassModal").style.display = "none";

    // ===== ALERT SYSTEM =====
    const alertError = document.getElementById("alertError");
    const alertSuccess = document.getElementById("alertSuccess");

    const hasError = alertError.querySelector(".alert-body").children.length > 0;
    const hasSuccess = alertSuccess.querySelector(".alert-body").children.length > 0;

    // show either success or error
    if (hasError) alertError.style.display = "block";
    if (hasSuccess) alertSuccess.style.display = "block";

    // set 5 secs then display none
    setTimeout(() => {
      alertError.style.display = "none";
      alertSuccess.style.display = "none";
    }, 5000);

    getID("alertCloseError").onclick = () =>
      alertError.style.display = "none";

    getID("alertCloseSuccess").onclick = () =>
      alertSuccess.style.display = "none";
  </script>

</body>

</html>