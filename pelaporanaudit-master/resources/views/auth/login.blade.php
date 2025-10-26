<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> 
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
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .header img {
            max-width: 320px;
            position: absolute;
            top: 15px;
            left: 70px;
        }



        .header h1 {
            font-size: 100px;
            font-weight: bold;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        form {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        h2 {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .form-control {
            border-radius: 10px;
            font-size: 16px;
            padding: 12px;
            border-color: #5DB5E6;
            margin-bottom: 20px;
            width: 100%;
            height: 70px;

            &::placeholder {
                color: rgb(206, 206, 206);
            }
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
            margin-top: 80px;
        }

        .additional-links a {
            color: #5DB5E6;
            text-decoration: none;
        }

        .additional-links a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-align: left;
            color: #5DB5E6;
            text-decoration: none;
            margin-bottom: 40px;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #5DB5E6;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;

        }

        @media (max-width: 768px) {
            
            .header {
                height: auto;
                flex-direction: column;
                padding: 20px;
            }

            .header img {
                position: static;
                /* Reset posisi absolut */
                margin-bottom: 20px;
                /* Tambahkan jarak bawah */
                left: 0;
                /* Reset posisi horizontal */
                top: 0;
                /* Reset posisi vertical */
            }

            .header h1 {
                font-size: 47px;
                /* Sesuaikan ukuran font */
            }
        }


    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('assets/sbuser/images/Sign-in-img.svg') }}" alt="Login Illustration" class="img-fluid">
        <h1>sewa.in</h1>
    </div>

    <div class="login-container">
        <form action="{{ route('login.post') }}" method="POST">
            <h2>Selamat Datang!</h2>
            @csrf
            <div class="form-group mb-3">
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="Masukkan Email kamu" required>
            </div>

            <div class="form-group mb-3">
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Masukkan Kata Sandi" required>
            </div>
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Masuk</button>
            <div class="additional-links mt-3">
                <span>Belum memiliki account? <a href="{{ route('register') }}">Daftar</a></span>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
