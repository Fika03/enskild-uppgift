<?php
// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addProduct'])) {
    // Generate a random numeric ID between a specified range (e.g., 1000 to 9999)
    $randomId = rand(1000, 9999); // Adjust range as needed

    $newProduct = [
        "id" => $randomId,
        "title" => $_POST['title'],
        "price" => $_POST['price'],
        "stockLevel" => $_POST['stockLevel'],
        "categoryId" => $_POST['categoryId'],
        "popularity" => $_POST['popularity']
    ];
    addProduct($newProduct);
}

function addProduct($newProduct)
{
    $url = "http://localhost:4242/data"; // Adjust the URL to your actual API endpoint for adding products
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($newProduct),
        ]
    ]);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
        die('Error adding product.');
    }
    echo "Product added successfully.";
    header("Refresh:2; url=index.php"); // Redirect after adding
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Add New Product</h1>

        <!-- Add Form -->
        <div class="mb-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="stockLevel" class="form-label">Stock Level</label>
                    <input type="number" class="form-control" id="stockLevel" name="stockLevel" required>
                </div>
                <div class="mb-3">
                    <label for="categoryId" class="form-label">Category</label>
                    <input type="text" class="form-control" id="categoryId" name="categoryId" required>
                </div>
                <div class="mb-3">
                    <label for="popularity" class="form-label">Popularity</label>
                    <input type="number" class="form-control" id="popularity" name="popularity" required>
                </div>
                <button type="submit" name="addProduct" class="btn btn-success">Add Product</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>