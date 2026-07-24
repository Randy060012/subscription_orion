<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #212529;
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .divider-line {
            border-top: 1px solid #dee2e6;
            margin: 35px 0;
        }

        .auth-title {
            font-weight: 300;
            font-size: 2.2rem;
            letter-spacing: 1px;
            color: #212529;
            margin-bottom: 35px;
        }

        /* Input avec bordure inférieure uniquement */
        .input-group-custom {
            position: relative;
            border-bottom: 1.5px solid #495057;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .input-group-custom i {
            font-size: 1.1rem;
            color: #495057;
            width: 30px;
        }

        .input-group-custom input {
            background: transparent;
            border: none;
            outline: none;
            box-shadow: none;
            color: #212529;
            width: 100%;
            padding: 8px 10px;
            font-size: 0.95rem;
        }

        .input-group-custom input::placeholder {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .input-group-custom input:focus {
            background: transparent;
            box-shadow: none;
        }

        /* Checkbox & Options */
        .form-check-input-custom {
            background-color: transparent;
            border: 1.5px solid #495057;
            border-radius: 2px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .form-check-input-custom:checked {
            background-color: #212529;
            border-color: #212529;
        }

        .auth-link {
            color: #6c757d;
            text-decoration: none;
            font-style: italic;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #212529;
        }

        /* Bouton */
        .btn-custom {
            background-color: #212529;
            color: #ffffff;
            border: none;
            border-radius: 0px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 0.9rem;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #343a40;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="auth-container text-center">

        <!-- Ligne supérieure -->
        <div class="divider-line"></div>

        <!-- Titre principal -->
        <h2 class="auth-title">User Login</h2>

        <!-- Formulaire -->
        <form action="#" method="POST" class="text-start">
            @csrf

            <!-- Email Input -->
            <div class="input-group-custom">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" placeholder="Email ID" required autofocus>
            </div>

            <!-- Password Input -->
            <div class="input-group-custom">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <!-- Options: Remember me & Forgot password -->
            <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input-custom me-2" id="remember" name="remember">
                    <label for="remember" class="small text-secondary" style="font-size: 0.88rem; cursor: pointer;">Remember me</label>
                </div>
                <a href="#" class="auth-link">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 pt-2">
                <button type="submit" class="btn btn-custom">LOGIN</button>
            </div>

            <!-- Lien d'inscription sous le bouton -->
            <div class="text-center mt-3">
                <a href="#" class="auth-link" style="font-style: normal;">Don't have an account? <strong>Register</strong></a>
            </div>
        </form>

        <!-- Ligne inférieure -->
        <div class="divider-line"></div>

    </div>

</body>
</html>
