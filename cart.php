<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove']) && isset($_SESSION['cart'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Đảm bảo chỉ số mảng liên tục
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ Hàng - BTEC SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">BTEC SHOP</a>
            <div class="d-flex align-items-center nav-icons position-relative">
                <a href="index.php" class="mx-2 text-dark"><i class="bi bi-house"></i> Trang chủ</a>
                <a href="cart.php" class="mx-2 text-dark"><i class="bi bi-cart4"></i> Giỏ hàng</a>
                <a href="logout.php" class="mx-2 text-dark"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Giỏ Hàng</h2>
        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $total = 0;
            echo "<table class='table'>
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                            <th>Xử lý</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($_SESSION['cart'] as $index => $item) {
                $item_total = str_replace('₫', '', str_replace('.', '', $item['price'])) * $item['quantity'];
                $total += $item_total;
                echo "<tr>
                        <td><img src='{$item['image']}' alt='{$item['name']}' style='width: 50px; height: 50px; object-fit: contain;'></td>
                        <td>{$item['name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['price']}</td>
                        <td>" . number_format($item_total, 0, ',', '.') . "₫</td>
                        <td><a href='cart.php?remove={$index}' class='btn btn-danger btn-sm'>Xóa</a></td>
                      </tr>";
            }
            echo "</tbody>
                  <tfoot>
                    <tr>
                        <td colspan='4' class='text-end fw-bold'>Tổng cộng:</td>
                        <td>" . number_format($total, 0, ',', '.') . "₫</td>
                        <td></td>
                    </tr>
                  </tfoot>
                  </table>";
            echo "<a href='index.php' class='btn btn-secondary'>Tiếp tục mua sắm</a>
                  <a href='checkout.php' class='btn btn-primary'>Thanh toán</a>";
        } else {
            echo "<p class='text-center'>Giỏ hàng trống!</p>
                  <a href='index.php' class='btn btn-primary'>Quay lại mua sắm</a>";
        }
        ?>
    </div>

    <footer class="footer text-white bg-dark pt-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">BTEC SHOP</h5>
                    <p>🏠 Địa chỉ: 123 Nguyễn Duy Tùng Lâm Thái Bình</p>
                    <p>📞 Hotline: <a href="tel:19001234" class="text-warning">1900 1234</a></p>
                    <p>📧 Email: <a href="mailto:hotro@btecshop.vn" class="text-warning">hotro@btecshop.vn</a></p>
                    <p>🕒 Làm việc: T2 - CN | 8h00 - 21h00</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Giới thiệu</a></li>
                        <li><a href="#" class="text-white">Chính sách bảo hành</a></li>
                        <li><a href="#" class="text-white">Hỗ trợ kỹ thuật</a></li>
                        <li><a href="#" class="text-white">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">Kết nối với chúng tôi</h5>
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i> Facebook</a><br>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i> Instagram</a><br>
                    <a href="#" class="text-white me-3"><i class="bi bi-youtube"></i> YouTube</a><br>
                    <a href="#" class="text-white me-3"><i class="bi bi-zalo"></i> Zalo</a><br>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center mb-0">&copy; 2025 BTEC SHOP. Đã đăng ký bản quyền.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
