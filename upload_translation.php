<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File vi.json</title>
</head>
<body>
    <h2>Upload File vi.json</h2>
    <form action="upload_translation.php" method="post" enctype="multipart/form-data">
        <input type="file" name="json_file" accept=".json" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = __DIR__ . '/../core/resources/lang/';
    $targetFile = $targetDir . 'vi.json';
$path = storage_path('app/lang/vi.json'); // Đường dẫn mới
file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Kiểm tra xem file có được tải lên không
    if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
        die("Lỗi khi tải lên file! Mã lỗi: " . $_FILES['json_file']['error']);
    }

    // Kiểm tra quyền ghi vào thư mục
    if (!is_writable($targetDir)) {
        die("Lỗi: Không có quyền ghi vào thư mục '$targetDir'. Hãy kiểm tra quyền truy cập.");
    }

    // Di chuyển file
    if (move_uploaded_file($_FILES['json_file']['tmp_name'], $targetFile)) {
        echo "Cập nhật file dịch thành công!";
    } else {
        die("Lỗi khi di chuyển file. Kiểm tra quyền truy cập hoặc dung lượng file.");
    }
}
?>


