<?php
// filepath: c:\laragon\www\greennest\generate_invoice.php
session_start();
require_once __DIR__ . '/config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id'] ?? 0);

if (!$order_id) {
    header('Location: profile.php?tab=orders');
    exit;
}

// Verify order belongs to user
$stmt = $conn->prepare("
    SELECT o.*, u.name as user_name, u.email, u.phone
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: profile.php?tab=orders');
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name as product_name, p.botanical_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Set headers for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice-' . $order['tracking_number'] . '.pdf"');

// For now, we'll create a simple HTML invoice (in production, use a PDF library like TCPDF or FPDF)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order['tracking_number']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 40px; }
        .company-name { font-size: 24px; font-weight: bold; color: #22c55e; }
        .invoice-title { font-size: 20px; margin: 20px 0; }
        .info-section { margin: 30px 0; }
        .info-title { font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .footer { margin-top: 40px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GreenNest</div>
        <div>Plant Store & Garden Center</div>
        <div class="invoice-title">INVOICE</div>
    </div>
    
    <div class="info-section">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <div class="info-title">Bill To:</div>
                <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                <div><?php echo htmlspecialchars($order['email']); ?></div>
                <?php if ($order['phone']): ?>
                <div><?php echo htmlspecialchars($order['phone']); ?></div>
                <?php endif; ?>
                <div><?php echo htmlspecialchars($order['shipping_address']); ?></div>
            </div>
            <div style="text-align: right;">
                <div class="info-title">Invoice Details:</div>
                <div>Invoice #: <?php echo htmlspecialchars($order['tracking_number']); ?></div>
                <div>Order Date: <?php echo date('M d, Y', strtotime($order['order_date'])); ?></div>
                <div>Payment Method: <?php echo ucfirst($order['payment_method']); ?></div>
                <div>Status: <?php echo ucfirst($order['status']); ?></div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo htmlspecialchars($item['botanical_name'] ?? ''); ?></td>
                <td class="text-right"><?php echo $item['quantity']; ?></td>
                <td class="text-right">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right"><strong>Rp <?php echo number_format($order['total_amount'] - $order['shipping_cost'], 0, ',', '.'); ?></strong></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>Shipping:</strong></td>
                <td class="text-right"><strong><?php echo $order['shipping_cost'] > 0 ? 'Rp ' . number_format($order['shipping_cost'], 0, ',', '.') : 'Free'; ?></strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>For questions about this invoice, contact us at support@greennest.com</p>
    </div>
    
    <script>
        // Auto print when page loads (for PDF generation)
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>