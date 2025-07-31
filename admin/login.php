<?php
require_once '../config/db.php';
session_start();

// HAPUS atau KOMENTARI kode berikut:
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
//     header('Location: login.php');
//     exit;
// }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Cek apakah user adalah admin
            if ($user['is_admin'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = 1;
                header('Location: index.php');
                exit;
            } else {
                // Jika bukan admin, tolak login
                $error = "Access denied. Only admin can login.";
            }
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin | GreenNest</title>
    <link rel="stylesheet" href="../src/output.css" />
    <link rel="stylesheet" href="../src/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="../src/img/favicon.ico" type="image/x-icon" />
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen">
        <form method="post" class="bg-white shadow rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-primary text-center">Admin Login</h2>
            <?php if ($error): ?>
                <div class="mb-4 text-red-500 text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-primary" required>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-primary" required>
            </div>
            <button type="submit" class="w-full bg-primary text-white py-2 rounded hover:bg-opacity-90 font-semibold">Login</button>
            <div class="mt-4 text-center">
                <a href="register.php" class="text-primary hover:underline">Register Admin</a>
            </div>
        </form>
    </div>
</body>
</html>