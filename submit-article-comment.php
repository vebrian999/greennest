<?php
session_start();
require_once 'config/db.php';

$userId = $_SESSION['user_id'] ?? 0;
$articleId = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
$comment = trim($_POST['comment'] ?? '');

if ($userId && $articleId && $comment) {
    $stmt = $conn->prepare("INSERT INTO article_comments (article_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $articleId, $userId, $comment);
    $stmt->execute();
}

header("Location: detail-artikel.php?id=$articleId#comments");
exit;
?>