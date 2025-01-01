<?php
require_once '../lib/product.php';
require_once '../templates/header.php';

if (isset($_GET['delete'])) {
    deleteProduct($conn, $_GET['delete']);
    header("Location: index.php");
    exit;
}

$products = fetchAllProducts($conn);
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Product Management</h1>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h2 class="text-primary col-12 col-md-8 mb-2 mb-md-0">Product List</h2>
        <a href="add_product.php" class="btn btn-primary col-12 col-md-auto">Add Product</a>
    </div>

    <div class="table-responsive shadow-sm mb-5">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; // Initialize counter ?>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $counter++ ?></td> <!-- Display and increment the counter -->
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>₹<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td class="text-center">
                            <a href="update_product.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="?delete=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        <h2 class="text-primary text-center mb-4">Items per Category</h2>
        <div class="chart-container mx-auto" style="max-width: 100%; width: 600px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script>
    async function fetchChartData() {
        const response = await fetch('chart_category_data.php');
        const data = await response.json();
        return {
            labels: data.map(item => item.category),
            datasets: [{
                label: 'Count of Items',
                data: data.map(item => item.count),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        };
    }

    async function initChart() {
        const data = await fetchChartData();
        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    }

    initChart();
</script>

<?php require_once '../templates/footer.php'; ?>
