<?php
$productId = $_GET['id'] ?? null;

// Fetch product data
if ($productId) {
    $url = "http://localhost:4242/data/{$productId}";
    $response = file_get_contents($url);
    $product = json_decode($response, true);

    if (!$product) {
        die('Product not found.');
    }
} else {
    die('No product ID provided.');
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteProduct'])) {
    deleteProduct($productId);
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProduct'])) {
    $updatedProduct = [
        "title" => $_POST['title'] ?? $product['title'],
        "price" => $_POST['price'] ?? $product['price'],
        "stockLevel" => $_POST['stockLevel'] ?? $product['stockLevel'],
        "categoryId" => $_POST['categoryId'] ?? $product['categoryId'],
        "popularity" => $_POST['popularity'] ?? $product['popularity']
    ];
    editProduct($productId, $updatedProduct);
}

function deleteProduct($productId)
{
    $url = "http://localhost:4242/data/{$productId}";
    $context = stream_context_create([
        'http' => [
            'method' => 'DELETE',
            'header' => 'Content-Type: application/json',
        ]
    ]);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
        die('Error deleting product.');
    }
    echo "Product deleted successfully.";
    header("Refresh:2; url=index.php");
    exit();
}

function editProduct($productId, $updatedProduct)
{
    $url = "http://localhost:4242/data/{$productId}";
    $context = stream_context_create([
        'http' => [
            'method' => 'PUT',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($updatedProduct),
        ]
    ]);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
        die('Error updating product.');
    }
    echo "Product updated successfully.";
    header("Refresh:2; url=index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">
            <?php echo htmlspecialchars($product['title']); ?>
        </h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo htmlspecialchars($product['title']); ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?php echo htmlspecialchars($product['price']); ?> kr
                </h6>
                <p class="card-text">Stock Level:
                    <?php echo htmlspecialchars($product['stockLevel']); ?>
                </p>
                <p class="card-text">Category:
                    <?php echo htmlspecialchars($product['categoryId']); ?>
                </p>
                <p class="card-text">Popularity:
                    <?php echo htmlspecialchars($product['popularity']); ?>
                </p>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="mb-4">
            <h3>Edit Product</h3>
            <form method="POST" action="">
                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo htmlspecialchars($product['title']); ?>">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price"
                        value="<?php echo htmlspecialchars($product['price']); ?>">
                </div>
                <div class="mb-3">
                    <label for="stockLevel" class="form-label">Stock Level</label>
                    <input type="number" class="form-control" id="stockLevel" name="stockLevel"
                        value="<?php echo htmlspecialchars($product['stockLevel']); ?>">
                </div>
                <div class="mb-3">
                    <label for="categoryId" class="form-label">Category</label>
                    <input type="text" class="form-control" id="categoryId" name="categoryId"
                        value="<?php echo htmlspecialchars($product['categoryId']); ?>">
                </div>
                <div class="mb-3">
                    <label for="popularity" class="form-label">Popularity</label>
                    <input type="number" class="form-control" id="popularity" name="popularity"
                        value="<?php echo htmlspecialchars($product['popularity']); ?>">
                </div>
                <button type="submit" name="editProduct" class="btn btn-primary">Update Product</button>
            </form>
        </div>

        <!-- Delete Form -->
        <div class="mb-4">
            <h3>Delete Product</h3>
            <form method="POST" action="">
                <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">
                <button type="submit" name="deleteProduct" class="btn btn-danger">Delete Product</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>