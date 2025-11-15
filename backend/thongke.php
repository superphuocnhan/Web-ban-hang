<?php
// Bật hiển thị tất cả lỗi PHP để dễ debug (tránh lỗi trắng trang)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối database
require_once __DIR__ . '/connect.php';

// Kiểm tra kết nối database có lỗi không
if ($conn->connect_error) {
    die("Lỗi kết nối database: " . $conn->connect_error);
}

/*
 * Hàm tiện ích: chạy query & lấy 1 dòng kết quả
 * - Nếu query lỗi → dừng chương trình và in lỗi
 * - Nếu không có dòng nào → trả về mảng rỗng []
 */
function getRow($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        die("Lỗi SQL: " . $conn->error . "<br> Câu lệnh: " . $sql);
    }
    $row = $result->fetch_assoc();
    return $row ?: []; // nếu null thì trả về mảng rỗng
}

// -----------------------
//  LẤY DỮ LIỆU THỐNG KÊ
// -----------------------
$kq1 = getRow($conn, "SELECT COUNT(*) AS tongSP FROM sanpham");                 // Tổng số sản phẩm
$kq2 = getRow($conn, "SELECT SUM(soLuong) AS tongSL FROM sanpham");            // Tổng tồn kho
$kq3 = getRow($conn, "SELECT SUM(gia * soLuong) AS tongGiaTri FROM sanpham");  // Tổng giá trị hàng
$kq4 = getRow($conn, "SELECT tenSP, gia FROM sanpham ORDER BY gia DESC LIMIT 1");  // SP giá cao nhất
$kq5 = getRow($conn, "SELECT tenSP, soLuong FROM sanpham ORDER BY soLuong DESC LIMIT 1"); // SP tồn nhiều nhất

// Ép kiểu & xử lý khi không có dữ liệu
$tongSP     = (int)($kq1['tongSP']     ?? 0);
$tongSL     = (int)($kq2['tongSL']     ?? 0);
$tongGiaTri = (int)($kq3['tongGiaTri'] ?? 0);

$spMaxGiaTen  = $kq4['tenSP']   ?? null;
$spMaxGiaGia  = isset($kq4['gia']) ? (int)$kq4['gia'] : null;

$spTonTen     = $kq5['tenSP']   ?? null;
$spTonSoLuong = isset($kq5['soLuong']) ? (int)$kq5['soLuong'] : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê sản phẩm</title>
    <link rel="stylesheet" href="../css/thongke.css">
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

        <!-- Header + nút trở về -->
        <div class="header">
            <div class="title-block">
                <h1>🎄 Thống kê sản phẩm Noel 🎁</h1>
                <p class="subtitle">Không khí Giáng Sinh lan tỏa khắp kho hàng 🎅</p>
            </div>
            <a href="../index.html" class="btn-back">⬅ Về trang chủ</a>
        </div>

        <!-- Hàng cây thông trang trí -->
        <div class="tree-line">
            <span>🎄</span><span>🎄</span><span>🎄</span><span>🎄</span><span>🎄</span>
        </div>

        <!-- GRID các card thống kê -->
        <div class="stats-grid">

            <!-- Tổng số sản phẩm -->
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-label">Tổng số sản phẩm</div>
                <div class="stat-value"><?= $tongSP ?></div>
            </div>

            <!-- Tổng tồn kho -->
            <div class="stat-card">
                <div class="stat-icon">🎁</div>
                <div class="stat-label">Tổng số lượng tồn</div>
                <div class="stat-value"><?= $tongSL ?></div>
            </div>

            <!-- Tổng giá trị hàng hóa -->
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Tổng giá trị hàng tồn</div>
                <div class="stat-value"><?= number_format($tongGiaTri) ?>₫</div>
            </div>

            <!-- Sản phẩm giá cao nhất -->
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-label">Sản phẩm giá cao nhất</div>
                <div class="stat-value">
                    <?php if ($spMaxGiaTen): ?>
                        <?= htmlspecialchars($spMaxGiaTen) ?> (<?= number_format($spMaxGiaGia) ?>₫)
                    <?php else: ?>
                        Chưa có sản phẩm
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sản phẩm tồn kho nhiều nhất -->
            <div class="stat-card">
                <div class="stat-icon">🎄</div>
                <div class="stat-label">Sản phẩm tồn kho nhiều nhất</div>
                <div class="stat-value">
                    <?php if ($spTonTen): ?>
                        <?= htmlspecialchars($spTonTen) ?> (<?= $spTonSoLuong ?> cái)
                    <?php else: ?>
                        Chưa có sản phẩm
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê chi tiết -->
        <div class="table-wrapper">
            <h2>📋 Bảng chi tiết thống kê</h2>

            <table class="stat-table">
                <tr>
                    <th>Mục</th>
                    <th>Kết quả</th>
                </tr>
                <tr>
                    <td>Tổng số sản phẩm</td>
                    <td><?= $tongSP ?></td>
                </tr>
                <tr>
                    <td>Tổng số lượng tồn</td>
                    <td><?= $tongSL ?></td>
                </tr>
                <tr>
                    <td>Tổng giá trị hàng tồn</td>
                    <td><?= number_format($tongGiaTri) ?>₫</td>
                </tr>
                <tr>
                    <td>Sản phẩm giá cao nhất</td>
                    <td>
                        <?php if ($spMaxGiaTen): ?>
                            <?= htmlspecialchars($spMaxGiaTen) ?> (<?= number_format($spMaxGiaGia) ?>₫)
                        <?php else: ?>
                            Chưa có sản phẩm
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Sản phẩm tồn kho nhiều nhất</td>
                    <td>
                        <?php if ($spTonTen): ?>
                            <?= htmlspecialchars($spTonTen) ?> (<?= $spTonSoLuong ?> cái)
                        <?php else: ?>
                            Chưa có sản phẩm
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        <footer class="footer">
            <span>✨ Merry Christmas & Happy New Year ✨</span>
        </footer>

    </div>
</body>
</html>
