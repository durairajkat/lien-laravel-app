<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Platform</title>
</head>
<body>
<h1>Welcome, {{ $newUser->name }}!</h1>
<p>You have been successfully registered on our platform. Here are your login details:</p>
<ul>
    <li><strong>Username:</strong> {{ $newUser->email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>
<p>We recommend that you log in and change your password immediately for security purposes.</p>
<p><a href="{{ url('/') }}">Login Here</a></p>
<p>Thank you for joining us!</p>
</body>
</html>
