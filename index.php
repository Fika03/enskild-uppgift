<?php

// fetch
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://localhost:4242/data");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);


$productsData = json_decode($response, true);

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$searchQuery = $_GET['searchproduct'] ?? "";
$selectedCategory = $_GET['selectedCategory'] ?? "All Categories";

// Filter 
if ($selectedCategory && $selectedCategory !== "All Categories") {
    $allProducts = array_filter($productsData, function ($product) use ($selectedCategory) {
        return $product['categoryId'] === $selectedCategory;
    });
} else {
    $allProducts = $productsData;
}

// Search 
if (!empty($searchQuery)) {
    $allProducts = array_filter($allProducts, function ($product) use ($searchQuery) {
        return stripos($product['title'], $searchQuery) !== false;
    });
}

// Sort 
if ($sortCol && in_array($sortCol, ['title', 'categoryId', 'price', 'stockLevel', 'popularity'])) {
    usort($allProducts, function ($a, $b) use ($sortCol, $sortOrder) {
        if ($a[$sortCol] == $b[$sortCol]) {
            return 0;
        }
        if ($sortOrder === 'desc') {
            return ($a[$sortCol] < $b[$sortCol]) ? 1 : -1;
        }
        return ($a[$sortCol] > $b[$sortCol]) ? 1 : -1;
    });
}

// CSV export
function exportCsv($allProducts)
{
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=products.csv");

    $output = fopen('php://output', 'w');
    $header_args = array('id', 'title', 'price', 'stockLevel', 'categoryId', 'popularity');
    fputcsv($output, $header_args);

    foreach ($allProducts as $product) {
        fputcsv($output, $product);
    }

    fclose($output);
    exit();
}

// XML Export
function exportXml($allProducts)
{
    $xml = new SimpleXMLElement('<productList/>');
    foreach ($allProducts as $product) {
        $productNode = $xml->addChild('product');
        $productNode->addChild('id', $product["id"]);
        $productNode->addChild('title', $product["title"]);
        $productNode->addChild('price', $product["price"]);
        $productNode->addChild('stockLevel', $product["stockLevel"]);
        $productNode->addChild('categoryId', $product["categoryId"]);
        $productNode->addChild('popularity', $product["popularity"]);
    }

    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    header('Content-Type: text/xml');
    header("Content-Disposition: attachment; filename=products.xml");
    echo $dom->saveXML();
    exit();
}

// Export action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['csvbutton'])) {
        exportCsv($allProducts);
    }
    if (isset($_POST['xmlbutton'])) {
        exportXml($allProducts);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Homepage - Start Bootstrap Template</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="/">Fikas Webbshop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Categorier</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="?selectedCategory=">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <?php
                            $categories = array_unique(array_column($productsData, 'categoryId'));
                            foreach ($categories as $category) {
                                echo "<li><a class='dropdown-item' href='?selectedCategory={$category}'>$category</a></li>";
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#!">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Create account</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="/addproduct.php">Add a Product!</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <header class="py-5" style="background-color: #d8b9ff;">


        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h3 class="text-white">Våra populära produkter!</h3>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $popularProducts = array_slice(array_filter($productsData, function ($product) {
                    return $product['popularity'] > 70;
                }), 0, 10);

                foreach ($popularProducts as $product) {
                    ?>
                    <div class="col">
                        <a href="product.php?id=<?php echo $product['id']; ?>&title=<?php echo urlencode($product['title']); ?>&price=<?php echo urlencode($product['price']); ?>"
                            class="text-decoration-none text-dark">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $product['title']; ?>
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <form method="GET" onchange="submit()">
                <select name="selectedCategory" class="form-select mb-3">
                    <option <?php if ($selectedCategory === "All Categories")
                        echo 'selected'; ?>>All Categories</option>
                    <?php
                    foreach ($categories as $category) {
                        $selected = ($selectedCategory == $category) ? 'selected' : '';
                        echo "<option value='{$category}' $selected>{$category}</option>";
                    }
                    ?>
                </select>
            </form>

            <form method="GET" class="mb-3">
                <input type="hidden" name="sortCol" value="<?php echo htmlspecialchars($sortCol); ?>">
                <input type="hidden" name="sortOrder" value="<?php echo htmlspecialchars($sortOrder); ?>">
                <?php if ($selectedCategory !== ""): ?>
                    <input type="hidden" name="selectedCategory" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                <?php endif; ?>
                <input placeholder="Search" name="searchproduct" value="<?php echo htmlspecialchars($searchQuery); ?>"
                    class="form-control">
            </form>

            <form method="POST" class="mb-3">
                <input type="submit" name="csvbutton" value="export csv" class="btn btn-outline-primary">
            </form>
            <form method="POST" class="mb-3">
                <input type="submit" name="xmlbutton" value="export xml" class="btn btn-outline-primary">
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><a
                                    href="?sortCol=title&sortOrder=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Product</a>
                            </th>
                            <th><a
                                    href="?sortCol=categoryId&sortOrder=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Category</a>
                            </th>
                            <th><a
                                    href="?sortCol=price&sortOrder=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Price</a>
                            </th>
                            <th><a
                                    href="?sortCol=stockLevel&sortOrder=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Stock
                                    Level</a></th>
                            <th><a
                                    href="?sortCol=popularity&sortOrder=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Popularity</a>
                            </th>
                            <th>Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($allProducts)) {
                            foreach ($allProducts as $product) {
                                echo "<tr>";
                                echo "<td>{$product['title']}</td>";
                                echo "<td>{$product['categoryId']}</td>";
                                echo "<td>{$product['price']}</td>";
                                echo "<td>{$product['stockLevel']}</td>";
                                echo "<td>{$product['popularity']}</td>";
                                echo "<td><a href='product.php?id={$product['id']}&title=" . urlencode($product['title']) . "&price=" . urlencode($product['price']) . "' class='btn btn-primary'>Buy</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No products available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2021</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>