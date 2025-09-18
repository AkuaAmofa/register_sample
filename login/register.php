<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
        }
        .highlight {
            color: #D19C97;
            transition: color 0.3s;
        }
        .highlight:hover {
            color: #b77a7a;
        }
        body {
            background-color: #f8f9fa;
            background-image:
                repeating-linear-gradient(0deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                repeating-linear-gradient(90deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                linear-gradient(rgba(183, 122, 122, 0.1),
                    rgba(183, 122, 122, 0.1));
            background-blend-mode: overlay;
            background-size: 20px 20px;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .register-container { margin-top: 100px; }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header { background-color: #D19C97; color: #fff; }
        .animate-pulse-custom { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .form-label i { margin-left: 5px; color: #b77a7a; }
        .alert-info { animation: fadeIn 1s; }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <!-- NOTE: no action attribute, handled by register.js -->
                        <form method="POST" class="mt-4" id="registerForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <i class="fa fa-user"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <i class="fa fa-flag"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="country" name="country" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City <i class="fa fa-city"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact Number <i class="fa fa-phone"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="contact" name="contact" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <div class="d-flex justify-content-start">
                                    <div class="form-check me-3 custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="2" checked>
                                        <label class="form-check-label" for="customer">Customer</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="admin" value="1">
                                        <label class="form-check-label" for="admin">Admin</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>
</html>
