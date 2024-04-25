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
            <?php echo $_GET['title']; ?>
        </h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo $_GET['title']; ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?php echo $_GET['price']; ?> kr
                </h6>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>