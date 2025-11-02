<!DOCTYPE html>
<html>
<head>
    <title>Rider Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <h2>Welcome, {{ $rider->name }}</h2>
    <p>Email: {{ $rider->email }}</p>
    <a href="/rider/logout" class="btn btn-danger">Logout</a>
</body>
</html>
