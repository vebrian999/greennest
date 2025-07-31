<?php
require_once 'config/db.php';
session_start();

// Set content type untuk JSON response
header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? 0;
$productId = $_POST['product_id'] ?? 0;
$quantity = intval($_POST['quantity'] ?? 1);

// Validasi input
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if (!$productId || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
    exit;
}

try {
    // Cek stok produk
    $stockStmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stockStmt->bind_param('i', $productId);
    $stockStmt->execute();
    $stockResult = $stockStmt->get_result();
    $product = $stockResult->fetch_assoc();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    // Cari atau buat cart untuk user
    $cartStmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ?");
    $cartStmt->bind_param('i', $userId);
    $cartStmt->execute();
    $cartRes = $cartStmt->get_result();
    $cartData = $cartRes->fetch_assoc();
    $cartId = $cartData['id'] ?? null;

    // Jika belum ada cart, buat baru
    if (!$cartId) {
        $newCartStmt = $conn->prepare("INSERT INTO cart (user_id, created_at) VALUES (?, NOW())");
        $newCartStmt->bind_param('i', $userId);
        $newCartStmt->execute();
        $cartId = $conn->insert_id;
    }

    // Cek apakah produk sudah ada di cart
    $itemStmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $itemStmt->bind_param('ii', $cartId, $productId);
    $itemStmt->execute();
    $itemRes = $itemStmt->get_result();
    $existingItem = $itemRes->fetch_assoc();

    if ($existingItem) {
        // Update quantity jika item sudah ada
        $newQuantity = $existingItem['quantity'] + $quantity;
        
        // Cek apakah total quantity tidak melebihi stok
        if ($newQuantity > $product['stock']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit;
        }
        
        $updateStmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $updateStmt->bind_param('ii', $newQuantity, $existingItem['id']);
        $updateStmt->execute();
    } else {
        // Cek stok sebelum menambah item baru
        if ($quantity > $product['stock']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit;
        }
        
        // Insert item baru ke cart
        $insertStmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param('iii', $cartId, $productId, $quantity);
        $insertStmt->execute();
    }
    
    // Hitung total items di cart untuk response
    $countStmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart_items WHERE cart_id = ?");
    $countStmt->bind_param('i', $cartId);
    $countStmt->execute();
    $countRes = $countStmt->get_result();
    $totalItems = $countRes->fetch_assoc()['total_items'] ?? 0;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Product added to cart successfully',
        'total_items' => $totalItems
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>