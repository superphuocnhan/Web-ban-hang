<?php
// Kết nối database
include 'connect.php';

// ------------------------------
// 1. Kiểm tra ID truyền vào
// ------------------------------
// Nếu không có id hoặc id không phải là số → báo lỗi và dừng chương trình
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ ID không hợp lệ hoặc không được truyền vào!");
}

$id = (int)$_GET['id']; // Ép kiểu int để đảm bảo an toàn

// ------------------------------
// 2. Lấy thông tin sản phẩm theo ID
// ------------------------------
$sql = "SELECT * FROM sanpham WHERE maSP = $id";
$result = $conn->query($sql);

// Nếu query lỗi → dừng và in lỗi
if (!$result) {
    die("❌ Lỗi truy vấn: " . $conn->error);
}

// Không tìm thấy sản phẩm
if ($result->num_rows === 0) {
    die("❌ Không tìm thấy sản phẩm có ID = $id");
}

// Lấy dữ liệu sản phẩm
$row = $result->fetch_assoc();

// ------------------------------
// 3. Khi người dùng nhấn nút "Cập nhật"
// ------------------------------
if (isset($_POST['update'])) {

    // Lấy dữ liệu từ form gửi lên
    $tenSP   = $_POST['tenSP'] ?? '';
    $gia     = $_POST['gia'] ?? 0;
    $moTa    = $_POST['moTa'] ?? '';
    $hinhAnh = $_POST['hinhAnh'] ?? '';
    $soLuong = $_POST['soLuong'] ?? 0;

    // Ép kiểu số cho những trường cần số
    $gia     = (int)$gia;
    $soLuong = (int)$soLuong;

    // Câu lệnh UPDATE sản phẩm
    // (Phiên bản đơn giản theo đúng bài học)
    $sqlUpdate = "
        UPDATE sanpham 
        SET tenSP='$tenSP', gia=$gia, moTa='$moTa', hinhAnh='$hinhAnh', soLuong=$soLuong 
        WHERE maSP=$id
    ";

    // Thực thi câu lệnh UPDATE
    if ($conn->query($sqlUpdate) === TRUE) {
        echo "✅ Cập nhật thành công! Đang quay lại danh sách...";

        // Chuyển trang sau 1 giây
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../index.html';
                }, 1000);
              </script>";
    } else {
        echo "❌ Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa sản phẩm</title>
  <link rel="stylesheet" href="../css/edit.css">
</head>
<body>

    <!-- Hiệu ứng tuyết rơi Noel -->
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">❄</div>
        <div class="snowflake">✻</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">✼</div>
        <div class="snowflake">❆</div>
        <div class="snowflake">❄</div>
        <div class="snowflake">✻</div>
        <div class="snowflake">❅</div>
        <div class="snowflake">✼</div>
        <div class="snowflake">❆</div>
    </div>

    <!-- Banner ông già Noel chạy ngang -->
    <div class="santa-banner-track">
        <div class="santa-banner">
            🎅✨🦌🦌🦌🎁🎄  🎅✨🦌🦌🦌🎁🎄
        </div>
    </div>

    <div class="container">
        <div class="edit-card">

            <!-- Header trang sửa sản phẩm -->
            <div class="edit-header">
                <div>
                    <h1>✏️ Sửa sản phẩm Noel</h1>
                    <p class="subtitle">Cập nhật lại món ăn cho kịp mùa Giáng Sinh 🎄</p>
                </div>

                <!-- Nút quay lại -->
                <a href="../index.html" class="btn-back">⬅ Quay lại danh sách</a>
            </div>

            <!-- Form sửa sản phẩm -->
            <form method="post" class="edit-form">

                <div class="form-group">
                    <label for="tenSP">Tên sản phẩm</label>
                    <input type="text" id="tenSP" name="tenSP"
                           value="<?php echo htmlspecialchars($row['tenSP']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gia">Giá</label>
                    <input type="number" id="gia" name="gia"
                           value="<?php echo $row['gia']; ?>" min="0" required>
                </div>

                <div class="form-group">
                    <label for="moTa">Mô tả</label>
                    <input type="text" id="moTa" name="moTa"
                           value="<?php echo htmlspecialchars($row['moTa']); ?>">
                </div>

                <div class="form-group">
                    <label for="hinhAnh">Hình ảnh (URL / tên file)</label>
                    <input type="text" id="hinhAnh" name="hinhAnh"
                           value="<?php echo htmlspecialchars($row['hinhAnh']); ?>">
                </div>

                <div class="form-group">
                    <label for="soLuong">Số lượng</label>
                    <input type="number" id="soLuong" name="soLuong"
                           value="<?php echo $row['soLuong']; ?>" min="0" required>
                </div>

                <!-- Nút cập nhật / hủy -->
                <div class="form-actions">
                    <input type="submit" name="update" value="🎁 Cập nhật sản phẩm" class="btn-submit">
                    <a href="../index.html" class="btn-cancel">Hủy</a>
                </div>

            </form>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <span>✨ Merry Christmas & Happy New Year ✨</span>
        </footer>
    </div>

</body>
</html>
