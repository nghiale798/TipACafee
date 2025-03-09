<?php
function convertToUnicode($str) {
    $unicodeMap = [
        'à' => '\u00e0', 'á' => '\u00e1', 'ạ' => '\u1ea1', 'ả' => '\u1ea3', 'ã' => '\u00e3',
        'â' => '\u00e2', 'ầ' => '\u1ea7', 'ấ' => '\u1ea5', 'ậ' => '\u1ead', 'ẩ' => '\u1ea9', 'ẫ' => '\u1eab',
        'ă' => '\u0103', 'ằ' => '\u1eb1', 'ắ' => '\u1eaf', 'ặ' => '\u1eb7', 'ẳ' => '\u1eb3', 'ẵ' => '\u1eb5',
        'è' => '\u00e8', 'é' => '\u00e9', 'ẹ' => '\u1eb9', 'ẻ' => '\u1ebb', 'ẽ' => '\u1ebd',
        'ê' => '\u00ea', 'ề' => '\u1ec1', 'ế' => '\u1ebf', 'ệ' => '\u1ec7', 'ể' => '\u1ec3', 'ễ' => '\u1ec5',
        'ì' => '\u00ec', 'í' => '\u00ed', 'ị' => '\u1ecb', 'ỉ' => '\u1ec9', 'ĩ' => '\u0129',
        'ò' => '\u00f2', 'ó' => '\u00f3', 'ọ' => '\u1ecd', 'ỏ' => '\u1ecf', 'õ' => '\u00f5',
        'ô' => '\u00f4', 'ồ' => '\u1ed3', 'ố' => '\u1ed1', 'ộ' => '\u1ed9', 'ổ' => '\u1ed5', 'ỗ' => '\u1ed7',
        'ơ' => '\u01a1', 'ờ' => '\u1edd', 'ớ' => '\u1edb', 'ợ' => '\u1ee3', 'ở' => '\u1edf', 'ỡ' => '\u1ee1',
        'ù' => '\u00f9', 'ú' => '\u00fa', 'ụ' => '\u1ee5', 'ủ' => '\u1ee7', 'ũ' => '\u0169',
        'ư' => '\u01b0', 'ừ' => '\u1eeb', 'ứ' => '\u1ee9', 'ự' => '\u1ef1', 'ử' => '\u1eed', 'ữ' => '\u1eef',
        'ỳ' => '\u1ef3', 'ý' => '\u00fd', 'ỵ' => '\u1ef5', 'ỷ' => '\u1ef7', 'ỹ' => '\u1ef9',
        'đ' => '\u0111',

        'À' => '\u00c0', 'Á' => '\u00c1', 'Ạ' => '\u1ea0', 'Ả' => '\u1ea2', 'Ã' => '\u00c3',
        'Â' => '\u00c2', 'Ầ' => '\u1ea6', 'Ấ' => '\u1ea4', 'Ậ' => '\u1eac', 'Ẩ' => '\u1ea8', 'Ẫ' => '\u1eaa',
        'Ă' => '\u0102', 'Ằ' => '\u1eb0', 'Ắ' => '\u1eae', 'Ặ' => '\u1eb6', 'Ẳ' => '\u1eb2', 'Ẵ' => '\u1eb4',
        'È' => '\u00c8', 'É' => '\u00c9', 'Ẹ' => '\u1eb8', 'Ẻ' => '\u1eba', 'Ẽ' => '\u1ebc',
        'Ê' => '\u00ca', 'Ề' => '\u1ec0', 'Ế' => '\u1ebe', 'Ệ' => '\u1ec6', 'Ể' => '\u1ec2', 'Ễ' => '\u1ec4',
        'Ì' => '\u00cc', 'Í' => '\u00cd', 'Ị' => '\u1eca', 'Ỉ' => '\u1ec8', 'Ĩ' => '\u0128',
        'Ò' => '\u00d2', 'Ó' => '\u00d3', 'Ọ' => '\u1ecc', 'Ỏ' => '\u1ece', 'Õ' => '\u00d5',
        'Ô' => '\u00d4', 'Ồ' => '\u1ed2', 'Ố' => '\u1ed0', 'Ộ' => '\u1ed8', 'Ổ' => '\u1ed4', 'Ỗ' => '\u1ed6',
        'Ơ' => '\u01a0', 'Ờ' => '\u1edc', 'Ớ' => '\u1eda', 'Ợ' => '\u1ee2', 'Ở' => '\u1ede', 'Ỡ' => '\u1ee0',
        'Ù' => '\u00d9', 'Ú' => '\u00da', 'Ụ' => '\u1ee4', 'Ủ' => '\u1ee6', 'Ũ' => '\u0168',
        'Ư' => '\u01af', 'Ừ' => '\u1eea', 'Ứ' => '\u1ee8', 'Ự' => '\u1ef0', 'Ử' => '\u1eec', 'Ữ' => '\u1eee',
        'Ỳ' => '\u1ef2', 'Ý' => '\u00dd', 'Ỵ' => '\u1ef4', 'Ỷ' => '\u1ef6', 'Ỹ' => '\u1ef8',
        'Đ' => '\u0110'
    ];
    return strtr($str, $unicodeMap);
}

// 🔹 Đọc dữ liệu từ file nguồn
$filePath = 'large_translation_source.php';
if (!file_exists($filePath)) {
    die("Lỗi: Không tìm thấy file dữ liệu dịch.");
}

$translations = include $filePath;
if (!is_array($translations)) {
    die("Lỗi: Dữ liệu không hợp lệ.");
}

// 🔹 Chuyển đổi dữ liệu sang Unicode
$converted = [];
foreach ($translations as $key => $value) {
    $converted[$key] = convertToUnicode($value);
}

// 🔹 Ghi dữ liệu JSON thủ công để tránh \\u
$jsonContent = "{\n";
foreach ($converted as $key => $value) {
    $jsonContent .= "    \"$key\": \"$value\",\n";
}
$jsonContent = rtrim($jsonContent, ",\n") . "\n}"; // Xóa dấu phẩy cuối cùng

// 🔹 Lưu vào file vi.json
$jsonFilePath = 'vi.json';
file_put_contents($jsonFilePath, $jsonContent);

echo "Xuất file $jsonFilePath thành công!";
?>
