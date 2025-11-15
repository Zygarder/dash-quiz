

@extends('dashboard')

@section('content')

<div>
    <button class="menu-btn" id="menuBtn">&#9776;</button>
    <h2>My Profile</h2>
    <a href="{{ route('logout') }}" class="logout-btn">Log Out</a>
</div>

@endsection