<?php 
// Kết nối database
include 'connect.php'; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm sản phẩm</title>
</head>
<body>
  <h1>➕ Thêm sản phẩm mới</h1>

  <!-- Form nhập dữ liệu sản phẩm -->
  <form method="post">
    <!-- Nhập tên sản phẩm -->
    Tên sản phẩm: <input type="text" name="tenSP" required><br>

    <!-- Nhập giá sản phẩm -->
    Giá: <input type="number" name="gia" required><br>

    <!-- Mô tả sản phẩm -->
    Mô tả: <input type="text" name="moTa"><br>

    <!-- Link hình ảnh (URL hoặc tên file) -->
    Hình ảnh: <input type="text" name="hinhAnh"><br>

    <!-- Mã danh mục (liên kết khóa ngoại) -->
    Mã danh mục: <input type="number" name="maDM" required><br>

    <!-- Số lượng tồn kho -->
    Số lượng: <input type="number" name="soLuong" value="0"><br>

    <!-- Nút submit -->
    <input type="submit" name="submit" value="Thêm">
  </form>

  <?php
  // Kiểm tra xem người dùng đã nhấn nút "Thêm" hay chưa
  if (isset($_POST['submit'])) {

      // Lấy dữ liệu từ form gửi lên
      $tenSP = $_POST['tenSP'];
      $gia = $_POST['gia'];
      $moTa = $_POST['moTa'];
      $hinhAnh = $_POST['hinhAnh'];
      $maDM = $_POST['maDM'];
      $soLuong = $_POST['soLuong'];

      // Câu lệnh SQL thêm sản phẩm mới vào bảng sanpham
      $sql = "INSERT INTO sanpham (tenSP, gia, moTa, hinhAnh, maDM, soLuong)
              VALUES ('$tenSP', '$gia', '$moTa', '$hinhAnh', '$maDM', '$soLuong')";

      // Thực thi câu SQL
      if ($conn->query($sql) === TRUE) {

          // Nếu thành công → thông báo + chuyển trang sau 1 giây
          echo "✅ Thêm thành công!";
          header("refresh:1; url=/Web-ban-hang/index.html");

      } else {

          // Nếu lỗi → in lỗi ra màn hình
          echo "❌ Lỗi: " . $conn->error;
      }
  }
  ?>
</body>
</html>
