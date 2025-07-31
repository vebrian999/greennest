<?php
require_once '../config/db.php';
session_start();

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $profile_image = ''; // default kosong

    // Upload profile image jika ada
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../uploads/";
        $filename = time() . '_' . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image = $filename;
        }
    }

    if ($password !== $confirm) {
        $error = 'Password tidak sama!';
    } elseif (!$name || !$email || !$password || !$phone) {
        $error = 'Semua kolom wajib diisi!';
    } else {
        // Cek email sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $error = 'Email sudah digunakan!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $now = date('Y-m-d H:i:s');
            $ins = $conn->prepare("INSERT INTO users (name, email, password, phone, created_at, updated_at, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins->bind_param('sssssss', $name, $email, $hash, $phone, $now, $now, $profile_image);
            if ($ins->execute()) {
                $success = 'Admin berhasil didaftarkan. Silakan login.';
            } else {
                $error = 'Gagal register admin!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin | GreenNest</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="../src/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet" />
    <link rel="icon" href="../src/img/favicon.ico" type="image/x-icon" />
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen">
        <form method="post" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-primary text-center">Register Admin</h2>
            <?php if ($error): ?>
                <div class="mb-4 text-red-500 text-center"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="mb-4 text-green-600 text-center"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Nama Lengkap</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">No. HP</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Foto Profil (opsional)</label>
                <input type="file" name="profile_image" accept="image/*" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium">Konfirmasi Password</label>
                <input type="password" name="confirm" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-primary text-white py-2 rounded hover:bg-opacity-90 font-semibold">Register</button>
            <div class="mt-4 text-center">
                <a href="login.php" class="text-primary hover:underline">Sudah punya akun? Login</a>
            </div>
        </form>
    </div>
</body>
</html>