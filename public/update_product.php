<?php
require_once '../lib/product.php';
require_once '../templates/header.php';

$errors = [];
$product = null;

// Fetch product details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $errors[] = "Product not found.";
    }
} else {
    $errors[] = "Invalid product ID.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    // Server-side validation
    if (empty($name) || strlen($name) < 3 || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Product name must contain at least 3 alphabets and spaces only.";
    }

    if (empty($price) || !filter_var($price, FILTER_VALIDATE_FLOAT) || $price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    if (empty($category) || strlen($category) < 3 || !preg_match("/^[a-zA-Z\s]+$/", $category)) {
        $errors[] = "Category must contain at least 3 alphabets and spaces only.";
    }

    if (empty($errors)) {
        if (updateProduct($conn, $id, $name, $price, $category)) {
            header('Location: index.php'); // Redirect on success
            exit;
        } else {
            $errors[] = "Error updating product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional Icon Library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f6f9;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-secondary {
            margin-top: 10px;
        }

        .text-center {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <i class="bi bi-pencil-square"></i> Update Product
            </div>
            <div class="card-body">
                <a href="index.php" class="btn btn-secondary mb-3">Back to Products</a>

                <!-- Display Errors -->
                <?php if ($errors): ?>
                    <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
                <?php endif; ?>

                <?php if ($product): ?>
                    <!-- Product Update Form -->
                    <form method="POST" action="" id="update-product-form">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (₹)</label>
                            <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" min="0.01" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-side validation -->
    <script>
        document.getElementById('update-product-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const category = document.getElementById('category').value.trim();

            let errors = [];
            const alphaRegex = /^[a-zA-Z\s]+$/;

            if (!name || name.length < 3 || !alphaRegex.test(name)) {
                errors.push("Product name must contain at least 3 alphabets and spaces only.");
            }

            if (!price || price <= 0) {
                errors.push("Price must be a positive number.");
            }

            if (!category || category.length < 3 || !alphaRegex.test(category)) {
                errors.push("Category must contain at least 3 alphabets and spaces only.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    </script>

</body>

</html>