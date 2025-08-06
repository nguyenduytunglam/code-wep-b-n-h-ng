<?php
session_start();
session_destroy();
header("Location: login.php?message=Đăng xuất thành công!");
exit();
?>
