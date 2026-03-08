<?php
// Bước 1: Nạp file cấu hình và thư viện
// require_once '../config/email.php';
require_once '../libraries/email.php';

// Bước 2: (Tùy chọn) Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bước 3: Gọi hàm gửi email
$result = send_mail(
    'baldricdrake2511@gmail.com',           // Thay bằng email thật của bạn
    'Khách Test',
    'Test Email từ Website',
    '<h3>Xin chào!</h3><p>Đây là email test từ hệ thống.</p>'
);

// Bước 4: Hiển thị kết quả
if ($result['status']) {
    echo '<h2 style="color:green;">GỬI THÀNH CÔNG!</h2>';
    echo '<p>Email đã được gửi đến: <strong>testreceiver@gmail.com</strong></p>';
} else {
    echo '<h2 style="color:red;">GỬI THẤT BẠI!</h2>';
    echo '<p>Lỗi: <strong>' . htmlspecialchars($result['message']) . '</strong></p>';
}
?>