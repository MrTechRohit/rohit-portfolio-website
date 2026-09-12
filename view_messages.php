<?php
// Optional: Set a simple security passkey (e.g., view_messages.php?key=mysecret123)
$secretKey = "admin123";
if (($_GET['key'] ?? '') !== $secretKey) {
    die("Access denied. Please provide the valid security key in the URL: ?key=admin123");
}

$file = __DIR__ . '/messages.txt';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Form Messages</title>
    <style>
        body { font-family: monospace; background: #0f172a; color: #f8fafc; padding: 20px; }
        h2 { color: #38bdf8; }
        pre { background: #1e293b; padding: 15px; border-radius: 8px; border: 1px solid #334155; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h2>Inbox - Contact Form Submissions</h2>
    <?php if (file_exists($file) && filesize($file) > 0): ?>
        <pre><?= htmlspecialchars(file_get_contents($file)) ?></pre>
    <?php else: ?>
        <p>No messages received yet.</p>
    <?php endif; ?>
</body>
</html>