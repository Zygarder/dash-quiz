<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Basic</h1>

    <form action="{{ route('basic-login') }}">
        <input type="text" placeholder="Name" name="name"><br><br>
        @error('name')
            <div>{{ $message }}</div>
        @enderror
        <input type="password" placeholder="Password" name="password"><br><br>
        @error('password')
            <div>{{ $message }}</div>
        @enderror
        <button type="submit">Submit</button>
    </form>
</body>

</html>