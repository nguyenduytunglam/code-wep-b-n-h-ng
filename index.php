<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!-- Sau đó ở HTML bạn có thể hiển thị -->
<?php if ($user): ?>
    
<?php else: ?>
    <a href="login.php">Đăng nhập</a> | <a href="register.php">Đăng ký</a>
<?php endif; ?>

<?php
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart']) && isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $product_name) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => 1
        ];
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang Chủ - BTEC SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar, .footer { background-color: #f8f9fa; }
        .product-card img { height: 200px; object-fit: cover; }
        .banner { background-color: #ffe0b2; padding: 20px; text-align: center; }
        .banner img { max-height: 200px; }
        .hotline-icon { font-size: 1.2rem; color: #dc3545; }
        .info-icons i { font-size: 1.5rem; margin-right: 8px; color: #f39c12; }
        .nav-icons i { font-size: 1.2rem; }
        .product-row { padding: 10px 0; }
        .product-item { flex: 0 0 auto; width: 180px; }
        .product-card { border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); }
        .card-img-top { padding: 10px; background-color: #fff; }
        .card-body { padding: 10px; }
        .category-filter { margin-bottom: 20px; }
        .category-filter .btn { margin-right: 10px; margin-bottom: 10px; }
        @media (max-width: 768px) {
            .product-item { width: 150px; }
            .card-img-top { max-height: 120px; }
        }
        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            text-align: center;
            font-size: 12px;
            line-height: 18px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">BTEC SHOP</a>
            <form class="d-flex w-50" action="index.php" method="GET" id="searchForm">
                <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm điện thoại..." aria-label="Search" id="searchInput" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-primary" type="submit">Tìm</button>
            </form>
            <div class="d-flex align-items-center nav-icons position-relative">
                <a href="#" class="mx-2 text-dark" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <i class="bi bi-cart4"></i>
                    <?php
                    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    if ($cart_count > 0) {
                        echo "<span class='cart-count'>$cart_count</span>";
                    }
                    ?>
                </a>
                <a href="logout.php" class="mx-2 text-dark"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Chào mừng đến với BTEC SHOP, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        <?php if (isset($_GET['message'])) echo "<div class='alert alert-info'>" . htmlspecialchars($_GET['message']) . "</div>"; ?>

        <!-- Banner quảng cáo - Carousel -->
        <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-inner">
                <div class="carousel-item active text-center" style="background-color: #ffe0b2; padding: 30px;">
                    <h2 class="mb-3">Xiaomi Redmi Note 13 Pro - Ưu đãi cực sốc!</h2>
                    <p>Mua ngay - Trả góp 0% - Bảo hành chính hãng</p>
                    <img src="https://cdn.tgdd.vn/Products/Images/42/319670/Slider/xiaomi-redmi-note-13-pro-5g-thumb-yt-1020x570.jpg" class="img-fluid" style="max-height: 400px;" alt="Banner 1">
                </div>
                <div class="carousel-item text-center" style="background-color: #e1f5fe; padding: 30px;">
                    <h2 class="mb-3">iPhone 15 Pro Max - Đỉnh cao công nghệ</h2>
                    <p>Giảm ngay 2 triệu - Hỗ trợ đổi cũ lấy mới</p>
                    <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:0:0/q:90/plain/https://cellphones.com.vn/media/wysiwyg/Phone/Apple/iphone_15/dien-thoai-iphone-15-pro-max-5.jpg" class="img-fluid" style="max-height: 400px;" alt="Banner 2">
                </div>
                <div class="carousel-item text-center" style="background-color: #ede7f6; padding: 30px;">
                    <h2 class="mb-3">Samsung S24 Ultra - Camera siêu Zoom</h2>
                    <p>Giảm giá 1 triệu - Tặng bao da chính hãng</p>
                    <img src="https://images.samsung.com/is/image/samsung/assets/vn/smartphones/galaxy-s24-ultra/buy/01_S24Ultra-Group-KV_MO_0527_final.jpg" class="img-fluid" style="max-height: 400px;" alt="Banner 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <!-- Info icons -->
        <div class="container my-4 text-center info-icons">
            <div class="row g-3">
                <div class="col-md-3"><i class="bi bi-shield-check"></i> Cam kết chính hãng</div>
                <div class="col-md-3"><i class="bi bi-truck"></i> Giao nhanh toàn quốc</div>
                <div class="col-md-3"><i class="bi bi-currency-dollar"></i> Giá tốt nhất thị trường</div>
                <div class="col-md-3"><i class="bi bi-headset"></i> Hỗ trợ 24/7</div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="category-filter">
            <h4 class="mb-3">Danh mục sản phẩm</h4>
            <a href="index.php" class="btn btn-outline-primary <?php echo $category == '' ? 'active' : ''; ?>">Tất cả</a>
            <a href="index.php?category=Apple" class="btn btn-outline-primary <?php echo $category == 'Apple' ? 'active' : ''; ?>">Apple</a>
            <a href="index.php?category=Samsung" class="btn btn-outline-primary <?php echo $category == 'Samsung' ? 'active' : ''; ?>">Samsung</a>
            <a href="index.php?category=Xiaomi" class="btn btn-outline-primary <?php echo $category == 'Xiaomi' ? 'active' : ''; ?>">Xiaomi</a>
            <a href="index.php?category=OPPO" class="btn btn-outline-primary <?php echo $category == 'OPPO' ? 'active' : ''; ?>">OPPO</a>
            <a href="index.php?category=Vivo" class="btn btn-outline-primary <?php echo $category == 'Vivo' ? 'active' : ''; ?>">Vivo</a>
            <a href="index.php?category=Asus" class="btn btn-outline-primary <?php echo $category == 'Asus' ? 'active' : ''; ?>">Asus</a>
            <a href="index.php?category=Honor" class="btn btn-outline-primary <?php echo $category == 'Honor' ? 'active' : ''; ?>">Honor</a>
            <a href="index.php?category=Realme" class="btn btn-outline-primary <?php echo $category == 'Realme' ? 'active' : ''; ?>">Realme</a>
            <a href="index.php?category=Tecno" class="btn btn-outline-primary <?php echo $category == 'Tecno' ? 'active' : ''; ?>">Tecno</a>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="container mb-5">
            <h4 class="mb-4">📱 Sản Phẩm Mới Nhất</h4>
            <div class="product-row d-flex flex-nowrap overflow-auto">
                <?php
                $products = [
                    ["name" => "iPhone 15 Pro Max", "price" => "33.990.000₫", "old_price" => "36.990.000₫", "image" => "https://cdn.xtmobile.vn/vnt_upload/news/09_2023/28/hinh-anh-mo-hop-iphone-15-pro-max-titan-tu-nhien-xtmobile.jpg", "category" => "Apple", "description" => "iPhone 15 Pro Max với chip A17 Pro, khung titan, camera 48MP, và hỗ trợ USB-C. Hiệu năng vượt trội và thiết kế tinh tế."],
                    ["name" => "Galaxy S24 Ultra", "price" => "31.990.000₫", "old_price" => "34.990.000₫", "image" => "https://smartviets.com/upload/Galaxy%20SS-%20Series/S24%20Series/S24%20Ultra%20tim.png", "category" => "Samsung", "description" => "Samsung Galaxy S24 Ultra với camera 200MP, S Pen, màn hình Dynamic AMOLED 2X, và hiệu năng mạnh mẽ."],
                    ["name" => "Xiaomi 15 Ultra", "price" => "27.990.000₫", "old_price" => "30.990.000₫", "image" => "https://cdn.tgdd.vn/Products/Images/42/313889/xiaomi-14-ultra-white-thumbnew-600x600.jpg", "category" => "Xiaomi", "description" => "Xiaomi 15 Ultra với camera Leica, pin 5000mAh, và chip Snapdragon 8 Gen 3, mang lại trải nghiệm cao cấp."],
                    ["name" => "OPPO Find X7 Ultra", "price" => "29.490.000₫", "old_price" => "32.490.000₫", "image" => "https://sonpixel.vn/wp-content/uploads/2024/07/oppo-find-x7-ultra-13.webp", "category" => "OPPO", "description" => "OPPO Find X7 Ultra với camera Hasselblad, màn hình 120Hz, và sạc nhanh 100W, lý tưởng cho nhiếp ảnh."],
                    ["name" => "Redmi Note 13 Pro", "price" => "9.990.000₫", "old_price" => "12.990.000₫", "image" => "https://cdn.tgdd.vn/Products/Images/42/319670/xiaomi-redmi-note-13-pro-5g-xanhla-thumb-600x600.jpg", "category" => "Xiaomi", "description" => "Redmi Note 13 Pro với màn hình AMOLED 120Hz, chip Snapdragon, và camera 108MP, giá trị tuyệt vời."],
                    ["name" => "Vivo X100 Ultra", "price" => "25.990.000₫", "old_price" => "28.990.000₫", "image" => "https://cdn.viettablet.com/images/detailed/59/vivo-x100-ultra-1.jpg", "category" => "Vivo", "description" => "Vivo X100 Ultra với camera Zeiss, pin 5500mAh, và hiệu năng vượt trội, phù hợp cho mọi nhu cầu."],
                    ["name" => "ROG Phone 8 Pro", "price" => "26.990.000₫", "old_price" => "29.990.000₫", "image" => "https://cdn.xtmobile.vn/vnt_upload/product/01_2024/thumbs/(600x600)_crop_asus-rog-phone-8-pro-16gb-512gb-xtmobile.png", "category" => "Asus", "description" => "ROG Phone 8 Pro với chip Snapdragon 8 Gen 3, màn hình 165Hz, và thiết kế tối ưu cho game thủ."],
                    ["name" => "Honor Magic6 Pro", "price" => "28.490.000₫", "old_price" => "31.490.000₫", "image" => "https://sonpixel.vn/wp-content/uploads/2024/12/honor-magic-6-pro-17.webp", "category" => "Honor", "description" => "Honor Magic6 Pro với camera 50MP, màn hình OLED cong, và pin 5600mAh, mang lại trải nghiệm cao cấp."],
                    ["name" => "Realme GT Neo6", "price" => "10.490.000₫", "old_price" => "13.490.000₫", "image" => "https://cdn.viettablet.com/images/detailed/58/realme-gt-neo6-1.jpg", "category" => "Realme", "description" => "Realme GT Neo6 với chip MediaTek, màn hình 144Hz, và sạc nhanh 65W, phù hợp với người dùng trẻ."],
                    ["name" => "Tecno Phantom V Flip", "price" => "17.990.000₫", "old_price" => "20.990.000₫", "image" => "https://cdn.mobilecity.vn/mobilecity-vn/images/2023/09/tecno-phantom-v-flip-5g-tim.jpg.webp", "category" => "Tecno", "description" => "Tecno Phantom V Flip với thiết kế gập, màn hình AMOLED, và hiệu năng ổn định, thời thượng."],
                    ["name" => "Xiaomi 14 Ultra", "price" => "27.990.000₫", "old_price" => "30.990.000₫", "image" => "https://cdn.tgdd.vn/Products/Images/42/313889/xiaomi-14-ultra-white-thumbnew-600x600.jpg", "category" => "Xiaomi", "description" => "Xiaomi 14 Ultra với camera Leica, chip Snapdragon 8 Gen 3, và màn hình AMOLED, đỉnh cao công nghệ."],
                    ["name" => "iPhone 14 Pro Max", "price" => "33.990.000₫", "old_price" => "36.990.000₫", "image" => "https://cdn2.cellphones.com.vn/insecure/rs:fill:0:0/q:90/plain/https://cellphones.com.vn/media/wysiwyg/Phone/Apple/iphone_15/dien-thoai-iphone-15-pro-max-5.jpg", "category" => "Apple", "description" => "iPhone 14 Pro Max với chip A16 Bionic, camera 48MP, và thiết kế Dynamic Island, sang trọng và mạnh mẽ."],
                    ["name" => "Galaxy S24", "price" => "31.990.000₫", "old_price" => "34.990.000₫", "image" => "https://images.samsung.com/is/image/samsung/assets/vn/smartphones/galaxy-s24-ultra/buy/01_S24Ultra-Group-KV_MO_0527_final.jpg", "category" => "Samsung", "description" => "Samsung Galaxy S24 với màn hình AMOLED, chip Exynos, và camera cải tiến, hiệu năng vượt trội."],
                    ["name" => "POCO F6 Pro", "price" => "14.990.000₫", "old_price" => "17.990.000₫", "image" => "https://cdn.viettablet.com/images/detailed/61/xiaomi-poco-f6-pro-1.jpg", "category" => "Xiaomi", "description" => "POCO F6 Pro với chip Snapdragon, màn hình 120Hz, và sạc nhanh 120W, hiệu năng mạnh mẽ với giá hợp lý."],
                    ["name" => "Oppo Find X7 Ultra", "price" => "29.490.000₫", "old_price" => "32.490.000₫", "image" => "https://sonpixel.vn/wp-content/uploads/2024/07/oppo-find-x7-ultra-13.webp", "category" => "OPPO", "description" => "OPPO Find X7 Ultra với camera Hasselblad, màn hình 120Hz, và sạc nhanh 100W, lý tưởng cho nhiếp ảnh."]
                ];

                $counter = 1;
                foreach ($products as $index => $product) {
                    if ((empty($search_query) || stripos($product['name'], $search_query) !== false) && 
                        (empty($category) || $product['category'] == $category)) {
                        echo "<div class='product-item me-3'>
                                <div class='card product-card h-100'>
                                    <div class='d-flex flex-column align-items-center'>
                                        <img src='{$product['image']}' class='card-img-top' alt='{$product['name']}' style='max-height: 150px; object-fit: contain;'>
                                        <div class='card-body text-center'>
                                            <span class='badge bg-secondary'>{$counter}</span>
                                            <h6 class='card-title'><a href='product_detail.php?name=" . urlencode($product['name']) . "' class='text-decoration-none text-dark'>{$product['name']}</a></h6>
                                            <p><s class='text-muted'>{$product['old_price']}</s> <span class='text-danger'>{$product['price']}</span></p>
                                            <form method='POST' action='' style='display:inline;'>
                                                <input type='hidden' name='product_name' value='{$product['name']}'>
                                                <input type='hidden' name='product_price' value='{$product['price']}'>
                                                <input type='hidden' name='product_image' value='{$product['image']}'>
                                                <button type='submit' name='add_to_cart' class='btn btn-sm btn-primary mt-2'>Thêm vào giỏ</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                              </div>";
                        $counter++;
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <footer class="footer text-white bg-dark pt-4">
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

    <!-- Modal Cart -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Giỏ Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $item_total = str_replace('₫', '', str_replace('.', '', $item['price'])) * $item['quantity'];
                            $total += $item_total;
                            echo "<div class='d-flex justify-content-between align-items-center mb-3'>
                                    <img src='{$item['image']}' alt='{$item['name']}' style='width: 50px; height: 50px; object-fit: contain;'>
                                    <div>
                                        <h6>{$item['name']}</h6>
                                        <p>Giá: {$item['price']} x {$item['quantity']}</p>
                                    </div>
                                    <p>" . number_format($item_total, 0, ',', '.') . "₫</p>
                                  </div>";
                        }
                        echo "<hr><p class='text-end fw-bold'>Tổng cộng: " . number_format($total, 0, ',', '.') . "₫</p>";
                    } else {
                        echo "<p>Giỏ hàng trống!</p>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="cart.php" class="btn btn-primary">Xem giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const searchInput = document.getElementById('searchInput').value.trim();
            window.location.href = `index.php?search=${encodeURIComponent(searchInput)}`;
        });
    </script>
</body>
</html>
