<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login</title>

    <!-- ✅ Local Bootstrap (if you downloaded it) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        
         body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            object-fit: cover;

            background: url("{{ asset('videos/samgyup.jpg') }}") no-repeat center center;
            background-size: 110%; /* zoomed-in effect */

            animation: panBg 7s ease-in-out infinite alternate; /* left-right motion */

        }

        /* 🎥 Smooth left ↔ right animation */
        @keyframes panBg {
            0% {
                background-position: left center;
            }
            100% {
                background-position: right center;
            }
        }
        
        /* 🔹 Dark Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: -1;
        }

        /* 🌟 Glassmorphism Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px;
            width: 420px;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            z-index: 10;
            overflow: hidden;
        }

        .login-card h3 {
            font-weight: 700;
            color: #fff;
        }

        /* 🔹 Blurred Input Fields */
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* 🔹 Button Styling */
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.4);
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.6);
        }

        /* 🔹 Extra links */
        .extra-links a {
            color: #fff;
            opacity: 0.8;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .extra-links a:hover {
            opacity: 1;
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    
    <!-- 🔹 Overlay -->
    <div class="overlay"></div>

    <!-- 🔹 Login Card -->
    <div class="login-card text-center">
        <h3 class="mb-4">Rider Login</h3>

        @if($errors->any())
            <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif

        <form action="/rider/login" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label text-white">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label text-white">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button class="btn btn-primary w-100 mt-2">Login</button>
        </form>

        <div class="extra-links mt-3">
            <a href="#">Forgot Password?</a> |
        </div>
    </div>

</body>
</html>
