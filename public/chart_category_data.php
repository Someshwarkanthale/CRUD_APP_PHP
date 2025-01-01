<?php
require_once '../lib/product.php';
header('Content-Type: application/json');
echo json_encode(fetchCategoryCounts($conn));
?>
