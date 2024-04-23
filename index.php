<?php
// include --  OK även om filen inte finns
//include_once("Models/Products.php");
require_once ("Models/Database.php");

$dbContext = new DBContext();

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";


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
            <a class="navbar-brand" href="#!">SuperShoppen</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Categorier</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <?php
                            foreach ($dbContext->getAllCategories() as $category) {
                                echo "<li><a class='dropdown-item' href='#!'>$category->title</a></li> ";
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
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <?php
                $hour = date('h');
                if ($hour >= 9) {
                    ?>
                    <h1 class="display-4 fw-bolder">Super shoppen</h1>
                    <?php
                }
                ?>
                <p class="lead fw-normal text-white-50 mb-0">Handla massa onödigt hos oss!</p>
            </div>
        </div>
    </header>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <select>
                <?php
                foreach ($dbContext->getAllCategories() as $category) {
                    echo "<option>$category->title</option> ";
                }
                ?>

            </select>

            <form method="GET">
                <input placeholder="Search" name="searchproduct">
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a
                                href="?sortCol=title&sortOrder=<?php echo ($sortCol === 'title' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode(isset($_GET['searchproduct']) ? $_GET['searchproduct'] : ''); ?>">
                                Name
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=categoryId&sortOrder=<?php echo ($sortCol === 'categoryId' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode(isset($_GET['searchproduct']) ? $_GET['searchproduct'] : ''); ?>">
                                Category
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=price&sortOrder=<?php echo ($sortCol === 'price' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode(isset($_GET['searchproduct']) ? $_GET['searchproduct'] : ''); ?>">
                                Price
                            </a>
                        </th>
                        <th>
                            <a
                                href="?sortCol=stockLevel&sortOrder=<?php echo ($sortCol === 'stockLevel' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&searchproduct=<?php echo urlencode(isset($_GET['searchproduct']) ? $_GET['searchproduct'] : ''); ?>">
                                Stock level
                            </a>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the search results and create table rows -->
                    <?php
                    // Assuming you have already included your Database class and created an instance of it ($dbContext)
                    
                    // Check if a search query is provided and display search results if applicable
                    if (isset($_GET['searchproduct'])) {
                        $searchQuery = $_GET['searchproduct'];
                        $searchResults = $dbContext->getProductByTitle($searchQuery, $sortCol, $sortOrder);

                        // Display search results
                        if (!empty($searchResults)) {
                            echo "<h3>Search Results</h3>";
                            echo "<table class='table'>";
                            echo "<tbody>";
                            foreach ($searchResults as $result) {
                                echo "<tr>";
                                echo "<td>{$result->title}</td>";
                                echo "<td>{$result->categoryId}</td>";
                                echo "<td>{$result->price}</td>";
                                echo "<td>{$result->stockLevel}</td>";
                                echo "<td><a href='product.php?id={$result->id}' class='btn btn-primary'>Edit</a></td>"; // Edit button with product ID as parameter
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No products found matching your search query.</p>";
                        }
                    }
                    ?>
                </tbody>

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