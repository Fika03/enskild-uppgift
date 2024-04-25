<?php
// include --  OK även om filen inte finns
//include_once("Models/Products.php");
require_once ("Models/Database.php");

$dbContext = new DBContext();

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$searchQuery = $_GET['searchproduct'] ?? "";
$selectedCategory = $_GET['selectedCategory'] ?? "All Categories";


if ($selectedCategory) {
    $allProducts = $dbContext->getProductsByCategory($selectedCategory !== 'All Categories' ? $selectedCategory : null, $sortCol, $sortOrder, $searchQuery);
} else {
    $allProducts = $dbContext->getAllProducts($sortCol, $sortOrder);
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
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
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
                            foreach ($dbContext->getAllCategories() as $category) {
                                echo "<li><a class='dropdown-item' href='?selectedCategory={$category->title}'>$category->title</a></li> ";
                            }
                            ?>

                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#!">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Create account</a></li>
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
    <!-- Header-->
    <header class="py-5" style="background-color: #d8b9ff;">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h3 class="text-white">Våra populära produkter!</h3>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $popularProducts = $dbContext->getTopPopularProducts();
                foreach ($popularProducts as $product) {
                    ?>
                    <div class="col">
                        <a href="product.php?id=<?php echo $product->id; ?>&title=<?php echo urlencode($product->title); ?>&price=<?php echo urlencode($product->price); ?>"
                            class="text-decoration-none text-dark">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $product->title; ?>
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
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <form method="GET" onchange="submit()">
                <select name="selectedCategory">
                    <option <?php if (!$selectedCategory)
                        echo 'selected'; ?>>All Categories</option>
                    <?php
                    foreach ($dbContext->getAllCategories() as $category) {
                        $selected = ($selectedCategory == $category->title) ? 'selected' : '';
                        echo "<option value='{$category->title}' $selected>{$category->title}</option>";
                    }
                    ?>
                </select>
            </form>

            <form method="GET">
                <input type="hidden" name="sortCol" value="<?php echo htmlspecialchars($sortCol); ?>">
                <input type="hidden" name="sortOrder" value="<?php echo htmlspecialchars($sortOrder); ?>">
                <?php if ($selectedCategory !== ""): ?>
                    <input type="hidden" name="selectedCategory" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                <?php endif; ?>
                <input placeholder="Search" name="searchproduct" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </form>


            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a
                                href="?sortCol=title&sortOrder=<?php echo ($sortCol === 'title' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode($searchQuery); ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>">
                                Name
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=categoryId&sortOrder=<?php echo ($sortCol === 'categoryId' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode($searchQuery); ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>">
                                Category
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=price&sortOrder=<?php echo ($sortCol === 'price' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode($searchQuery); ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>">
                                Price
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=stockLevel&sortOrder=<?php echo ($sortCol === 'stockLevel' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode($searchQuery); ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>">
                                Stock level
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=popularity&sortOrder=<?php echo ($sortCol === 'popularity' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode($searchQuery); ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>">
                                Popularity
                            </a>
                        </th>

                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($allProducts)) {
                        foreach ($allProducts as $product) {
                            echo "<tr>";
                            echo "<td>{$product->title}</td>";
                            echo "<td>{$product->categoryId}</td>";
                            echo "<td>{$product->price}</td>";
                            echo "<td>{$product->stockLevel}</td>";
                            echo "<td>{$product->popularity}</td>";
                            echo "<td><a href='product.php?id={$product->id}&title=" . urlencode($product->title) . "&price=" . urlencode($product->price) . "' class='btn btn-primary'>Buy</a></td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No products available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            </table>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>