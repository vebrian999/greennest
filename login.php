<?php
session_start();
include __DIR__ . '/config/db.php';
$login_error = "";
$register_error = "";
$register_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses Login
    if (isset($_POST['login'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($email === '' || $password === '') {
            $login_error = "Email dan password wajib diisi.";
        } else {
            if (!$conn) {
                $login_error = "Gagal koneksi database.";
            } else {
                $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    if (password_verify($password, $row['password'])) {
                        // Set session
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_name'] = $row['name'];
                        $_SESSION['user_email'] = $row['email'];
                        $loginBerhasil = true;
                    } else {
                        $login_error = "Password salah.";
                    }
                } else {
                    $login_error = "Email tidak ditemukan.";
                }
                $stmt->close();
            }
        }
    }
    // Proses Register
    if (isset($_POST['register'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirmPassword'] ?? '';
        if ($name === '' || $email === '' || $password === '' || $confirm === '') {
            $register_error = "Semua field wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = "Email tidak valid.";
        } elseif ($password !== $confirm) {
            $register_error = "Password dan konfirmasi tidak sama.";
        } else {
            if (!$conn) {
                $register_error = "Gagal koneksi database.";
            } else {
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $register_error = "Email sudah terdaftar.";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                    $stmt->bind_param('sss', $name, $email, $hashed);
                    if ($stmt->execute()) {
                        $register_success = true;
                    } else {
                        $register_error = "Gagal registrasi. Silakan coba lagi.";
                    }
                }
                $stmt->close();
            }
        }
    }
}

if (isset($loginBerhasil) && $loginBerhasil) {
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
    header('Location: ' . $redirect);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GreenNest | Authentication</title>
    <link rel="stylesheet" href="./src/output.css" />
    <link rel="stylesheet" href="./src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="./src/img/favicon.ico" type="image/x-icon" />
  </head>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#45671E',
                        secondary: '#73AC32',
                        accent: '#8BC34A'
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<?php include './component/navbar.php'; ?>

<body class="min-h-screen bg-gradient-to-br from-white via-green-50 to-green-100 py-16">
    <!-- Navigation Toggle -->
    <div class="fixed top-20 right-16 z-50">
        <button id="toggleAuth" class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg text-primary font-medium hover:bg-white transition-all duration-300 border border-green-100">
            Switch to <span id="toggleText">Register</span>
        </button>
    </div>

    <!-- Login Form -->
    <div id="loginForm" class="min-h-screen flex items-center justify-center px-4 py-8 transition-all duration-500 ease-in-out">
        <div class="w-full max-w-md transform transition-all duration-500 ease-in-out">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-2xl mb-4 shadow-lg">
                <h1 class="text-3xl font-semibold text-white">GN</h1>
                </div>
                <h1 class="text-3xl font-bold text-primary mb-2">GreenNest</h1>
                <p class="text-gray-600">Welcome back to your plant paradise</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-xl p-8 border border-white/30">
                <h2 class="text-2xl font-semibold text-primary mb-6 text-center">Sign In</h2>
                <?php if ($login_error): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"> <?= htmlspecialchars($login_error) ?> </div>
                <?php endif; ?>
                <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">Registrasi berhasil! Silakan login.</div>
                <?php endif; ?>
                <form class="space-y-6" method="POST" autocomplete="off">
                    <div>
                        <label for="loginEmail" class="block text-sm font-medium text-primary mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="loginEmail" 
                            name="email" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/80 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="your@email.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="loginPassword" class="block text-sm font-medium text-primary mb-2">Password</label>
                        <input 
                            type="password" 
                            id="loginPassword" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/80 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="••••••••"
                        >
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-gray-600">
                            <input type="checkbox" class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2">
                            Remember me
                        </label>
                        <a href="#" class="text-secondary hover:text-primary transition-colors font-medium">Forgot password?</a>
                    </div>

                    <button 
                        type="submit" 
                        name="login"
                        class="w-full bg-primary text-white py-3 rounded-2xl font-semibold hover:bg-opacity-90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Sign In
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <span class="text-gray-600">New to GreenNest? </span>
                    <button id="showRegister" class="text-secondary hover:text-primary font-semibold transition-colors">Create account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="min-h-screen flex items-center justify-center px-4 py-8 transition-all duration-500 ease-in-out hidden">
        <div class="w-full max-w-md transform transition-all duration-500 ease-in-out">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-2xl mb-4 shadow-lg">
                    <h1 class="text-3xl font-semibold text-white">GN</h1>
                </div>
                <h1 class="text-3xl font-bold text-primary mb-2">GreenNest</h1>
                <p class="text-gray-600">Join our growing community of plant lovers</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-xl p-8 border border-white/30">
                <h2 class="text-2xl font-semibold text-primary mb-6 text-center">Create Account</h2>
                <?php if ($register_error): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"> <?= htmlspecialchars($register_error) ?> </div>
                <?php endif; ?>
                <?php if ($register_success): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">Registrasi berhasil! Silakan login.</div>
                <?php endif; ?>
                <form class="space-y-5" method="POST" autocomplete="off">
                    <div>
                        <label for="registerName" class="block text-sm font-medium text-primary mb-2">Full Name</label>
                        <input 
                            type="text" 
                            id="registerName" 
                            name="name" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/80 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="John Doe"
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="registerEmail" class="block text-sm font-medium text-primary mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="registerEmail" 
                            name="email" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="your@email.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="registerPassword" class="block text-sm font-medium text-primary mb-2">Password</label>
                        <input 
                            type="password" 
                            id="registerPassword" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-primary mb-2">Confirm Password</label>
                        <input 
                            type="password" 
                            id="confirmPassword" 
                            name="confirmPassword" 
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent bg-white/50 backdrop-blur-sm transition-all duration-300 text-gray-700 placeholder-gray-400"
                            placeholder="••••••••"
                        >
                    </div>

                    <div class="flex items-start">
                        <input type="checkbox" required class="rounded border-gray-300 text-secondary focus:ring-secondary mt-1 mr-3">
                        <label class="text-sm text-gray-600 leading-relaxed">
                            I agree to the <a href="#" class="text-secondary hover:text-primary font-medium">Terms of Service</a> and <a href="#" class="text-secondary hover:text-primary font-medium">Privacy Policy</a>
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        name="register"
                        class="w-full bg-primary text-white py-3 rounded-2xl font-semibold hover:bg-opacity-90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <span class="text-gray-600">Already have an account? </span>
                    <button id="showLogin" class="text-secondary hover:text-primary font-semibold transition-colors">Sign in</button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Floating Elements for Visual Appeal -->
    <div class="fixed top-20 left-10 w-20 h-20 bg-secondary/10 rounded-full blur-xl animate-pulse"></div>
    <div class="fixed bottom-20 right-10 w-32 h-32 bg-primary/10 rounded-full blur-xl animate-pulse" style="animation-delay: 2s;"></div>
    <div class="fixed top-1/2 left-5 w-16 h-16 bg-secondary/15 rounded-full blur-xl animate-pulse" style="animation-delay: 4s;"></div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const toggleAuth = document.getElementById('toggleAuth');
        const toggleText = document.getElementById('toggleText');
        const showRegister = document.getElementById('showRegister');
        const showLogin = document.getElementById('showLogin');

        function switchToRegister() {
            const loginContainer = loginForm.querySelector('.w-full.max-w-md');
            const registerContainer = registerForm.querySelector('.w-full.max-w-md');
            
            // Fade out login form
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                toggleText.textContent = 'Login';
                
                // Fade in register form
                setTimeout(() => {
                    registerContainer.style.opacity = '1';
                    registerContainer.style.transform = 'translateY(0)';
                }, 50);
            }, 250);
        }

        function switchToLogin() {
            const loginContainer = loginForm.querySelector('.w-full.max-w-md');
            const registerContainer = registerForm.querySelector('.w-full.max-w-md');
            
            // Fade out register form
            registerContainer.style.opacity = '0';
            registerContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                registerForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
                toggleText.textContent = 'Register';
                
                // Fade in login form
                setTimeout(() => {
                    loginContainer.style.opacity = '1';
                    loginContainer.style.transform = 'translateY(0)';
                }, 50);
            }, 250);
        }

        toggleAuth.addEventListener('click', () => {
            if (loginForm.classList.contains('hidden')) {
                switchToLogin();
            } else {
                switchToRegister();
            }
        });

        showRegister.addEventListener('click', switchToRegister);
        showLogin.addEventListener('click', switchToLogin);

        // Form validation for register
        const registerPasswordInput = document.getElementById('registerPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        function validatePasswords() {
            if (registerPasswordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }

        registerPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);

        // Enhanced form interactions
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>

    <style>
        .focused {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</body>
</html>