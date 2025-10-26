<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: 'poppins', sans-serif;
        }

        .header {
            background-color: #5DB5E6;
            color: white;
            padding: 50px 20px;
            position: relative;
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            text-align: left;
        }

        .header h1 {
            font-size: 50px;
            font-weight: bold;
            margin: 0;
            margin-left: 25px;
        }

        .container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            flex-wrap: wrap;
            gap: 100px;
            /* Tambahkan gap untuk jarak antar elemen */
        }

        .image-container {
            flex-basis: 30%;
            margin-right: 50px;
            /* Ukuran relatif untuk gambar */
            text-align: center;
            /* Agar gambar di tengah */
        }

        .image-container img {
            max-width: 100%;
            /* Sesuaikan ukuran gambar */
            height: auto;
            border-radius: 10px;
            display: block;
        }

        .form-container {
            flex-basis: 50%;
            /* Ukuran relatif untuk form */
            width: 100%;
            max-width: 500px;
        }


        h2 {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .small-text {
            text-align: center;
            font-size: 14px;
            color: gray;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            font-size: 16px;
            padding: 12px;
            border-color: #5DB5E6;
            margin-bottom: 5px;
            width: 100%;
        }

        .btn-primary {
            background-color: #5DB5E6;
            border-color: #5DB5E6;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            height: 70px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #4aa3d4;
        }

        .additional-links {
            text-align: center;
            margin-top: 20px;
        }

        .additional-links a {
            color: #5DB5E6;
            text-decoration: none;
        }

        .additional-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-container {
                margin: 0 0 20px 0;
            }
        }
    </style>
    <script>
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const eyeIcon = document.getElementById(id + "-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="header">
        <h1>sewa.in</h1>
    </div>
    <div class="container">
        <div class="image-container">
            <img src="assets/sbuser/images/signup-image.png" alt="Registration Image">
        </div>
        <div class="form-container">
            <h2>Registe Adminr</h2>
            <p class="small-text">pastikan mengisi data dengan baik dan benar</p>
            <form action="{{ route('registeradmin') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-light" onclick="togglePasswordVisibility('password')">
                            <i id="password-icon" class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" required>
                        <button type="button" class="btn btn-light"
                            onclick="togglePasswordVisibility('password_confirmation')">
                            <i id="password_confirmation-icon" class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <div class="additional-links">
                <span>sudah memiliki account? <a href="{{ route('login') }}">Masuk.</a></span>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
