<?php
require_once ('Models/Product.php');
require_once ('Models/Category.php');
require 'vendor/autoload.php';

class DBContext
{

    private $pdo;



    function __construct()
    {

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '../../');
        $dotenv->load();

        $host = $_ENV['host'];
        $db = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];

        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->initIfNotInitialized();
        $this->seedfNotSeeded();
    }


    function getAllCategories()
    {
        return $this->pdo->query('SELECT * FROM category')->fetchAll(PDO::FETCH_CLASS, 'Category');

    }
    function getProductsByCategory($categoryTitle, $sortCol = null, $sortOrder = null)
    {
        if ($sortCol == null) {
            $sortCol = "Id";
        }
        if ($sortOrder == null) {
            $sortOrder = "asc";
        }

        // Get the category by title
        $category = $this->getCategoryByTitle($categoryTitle);
        if (!$category) {
            // If the category doesn't exist, return an empty array
            return [];
        }

        // Fetch products based on the category ID
        $sql = "SELECT * FROM products WHERE categoryId = :categoryId ORDER BY $sortCol $sortOrder";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':categoryId', $category->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }



    function getAllProducts($sortCol, $sortOrder)
    {
        if ($sortCol == null) {
            $sortCol = "Id";
        }
        if ($sortOrder == null) {
            $sortOrder = "asc";
        }
        $sql = "SELECT * FROM products ORDER BY $sortCol $sortOrder";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS, 'Product');
    }

    function getProduct($id)
    {
        $prep = $this->pdo->prepare('SELECT * FROM products where id=:id');
        $prep->setFetchMode(PDO::FETCH_CLASS, 'Product');
        $prep->execute(['id' => $id]);
        return $prep->fetch();
    }
    function getProductByTitle($title, $sortCol = null, $sortOrder = null)
    {
        if ($sortCol == null) {
            $sortCol = "Id";
        }
        if ($sortOrder == null) {
            $sortOrder = "asc";
        }

        $sql = "SELECT * FROM products WHERE title LIKE :title ORDER BY $sortCol $sortOrder";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':title', "%$title%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }



    function getCategoryByTitle($title): Category|false
    {
        $prep = $this->pdo->prepare('SELECT * FROM category where title=:title');
        $prep->setFetchMode(PDO::FETCH_CLASS, 'Category');
        $prep->execute(['title' => $title]);
        return $prep->fetch();
    }


    function seedfNotSeeded()
    {
        static $seeded = false;
        if ($seeded)
            return;
        $this->createIfNotExisting('Chai', 18, 39, 'Beverages', 51);
        $this->createIfNotExisting('Chang', 19, 17, 'Beverages', 78);
        $this->createIfNotExisting('Aniseed Syrup', 10, 13, 'Condiments', 64);
        $this->createIfNotExisting('Chef Antons Cajun Seasoning', 22, 53, 'Condiments', 92);
        $this->createIfNotExisting('Chef Antons Gumbo Mix', 21, 0, 'Condiments', 30);
        $this->createIfNotExisting('Grandmas Boysenberry Spread', 25, 120, 'Condiments', 45);
        $this->createIfNotExisting('Uncle Bobs Organic Dried Pears', 30, 15, 'Produce', 83);
        $this->createIfNotExisting('Northwoods Cranberry Sauce', 40, 6, 'Condiments', 17);
        $this->createIfNotExisting('Mishi Kobe Niku', 97, 29, 'Meat/Poultry', 75);
        $this->createIfNotExisting('Ikura', 31, 31, 'Seafood', 42);
        $this->createIfNotExisting('Queso Cabrales', 21, 22, 'Dairy Products', 56);
        $this->createIfNotExisting('Queso Manchego La Pastora', 38, 86, 'Dairy Products', 88);
        $this->createIfNotExisting('Konbu', 6, 24, 'Seafood', 29);
        $this->createIfNotExisting('Tofu', 22, 35, 'Produce', 60);
        $this->createIfNotExisting('Genen Shouyu', 18, 39, 'Condiments', 37);
        $this->createIfNotExisting('Pavlova', 12, 29, 'Confections', 83);
        $this->createIfNotExisting('Alice Mutton', 39, 0, 'Meat/Poultry', 5);
        $this->createIfNotExisting('Carnarvon Tigers', 231, 42, 'Seafood', 94);
        $this->createIfNotExisting('Teatime Chocolate Biscuits', 213, 25, 'Confections', 72);
        $this->createIfNotExisting('Sir Rodneys Marmalade', 81, 40, 'Confections', 88);
        $this->createIfNotExisting('Sir Rodneys Scones', 10, 3, 'Confections', 12);
        $this->createIfNotExisting('Gustafs Knäckebröd', 21, 104, 'Grains/Cereals', 36);
        $this->createIfNotExisting('Tunnbröd', 9, 61, 'Grains/Cereals', 77);
        $this->createIfNotExisting('Guaraná Fantástica', 231, 20, 'Beverages', 65);
        $this->createIfNotExisting('NuNuCa Nuß-Nougat-Creme', 14, 76, 'Confections', 42);
        $this->createIfNotExisting('Gumbär Gummibärchen', 312, 15, 'Confections', 91);
        $this->createIfNotExisting('Schoggi Schokolade', 213, 49, 'Confections', 53);
        $this->createIfNotExisting('Rössle Sauerkraut', 132, 26, 'Produce', 28);
        $this->createIfNotExisting('Thüringer Rostbratwurst', 231, 0, 'Meat/Poultry', 77);
        $this->createIfNotExisting('Nord-Ost Matjeshering', 321, 10, 'Seafood', 63);
        $this->createIfNotExisting('Gorgonzola Telino', 321, 0, 'Dairy Products', 41);
        $this->createIfNotExisting('Mascarpone Fabioli', 32, 9, 'Dairy Products', 55);
        $this->createIfNotExisting('Geitost', 12, 112, 'Dairy Products', 76);
        $this->createIfNotExisting('Sasquatch Ale', 14, 111, 'Beverages', 88);
        $this->createIfNotExisting('Steeleye Stout', 18, 20, 'Beverages', 54);
        $this->createIfNotExisting('Inlagd Sill', 19, 112, 'Seafood', 67);
        $this->createIfNotExisting('Gravad lax', 26, 11, 'Seafood', 72);
        $this->createIfNotExisting('Côte de Blaye', 1, 17, 'Beverages', 45);
        $this->createIfNotExisting('Chartreuse verte', 18, 69, 'Beverages', 83);
        $this->createIfNotExisting('Boston Crab Meat', 2, 123, 'Seafood', 98);
        $this->createIfNotExisting('Jacks New England Clam Chowder', 2, 85, 'Seafood', 79);
        $this->createIfNotExisting('Singaporean Hokkien Fried Mee', 14, 26, 'Grains/Cereals', 37);
        $this->createIfNotExisting('Ipoh Coffee', 46, 17, 'Beverages', 72);
        $this->createIfNotExisting('Gula Malacca', 2, 27, 'Condiments', 24);
        $this->createIfNotExisting('Rogede sild', 3, 5, 'Seafood', 18);
        $this->createIfNotExisting('Spegesild', 12, 95, 'Seafood', 56);
        $this->createIfNotExisting('Zaanse koeken', 4, 36, 'Confections', 68);
        $this->createIfNotExisting('Chocolade', 6, 15, 'Confections', 81);
        $this->createIfNotExisting('Maxilaku', 5, 10, 'Confections', 90);
        $this->createIfNotExisting('Valkoinen suklaa', 1, 65, 'Confections', 73);
        $this->createIfNotExisting('Manjimup Dried Apples', 53, 20, 'Produce', 62);
        $this->createIfNotExisting('Filo Mix', 7, 38, 'Grains/Cereals', 41);
        $this->createIfNotExisting('Perth Pasties', 4, 0, 'Meat/Poultry', 15);
        $this->createIfNotExisting('Tourtière', 7, 21, 'Meat/Poultry', 34);
        $this->createIfNotExisting('Pâté chinois', 24, 115, 'Meat/Poultry', 89);
        $this->createIfNotExisting('Gnocchi di nonna Alice', 38, 21, 'Grains/Cereals', 72);
        $this->createIfNotExisting('Ravioli Angelo', 7, 36, 'Grains/Cereals', 49);
        $this->createIfNotExisting('Escargots de Bourgogne', 7, 62, 'Seafood', 57);
        $this->createIfNotExisting('Raclette Courdavault', 55, 79, 'Dairy Products', 86);
        $this->createIfNotExisting('Camembert Pierrot', 34, 19, 'Dairy Products', 42);
        $this->createIfNotExisting('Sirop dérable', 7, 113, 'Condiments', 79);
        $this->createIfNotExisting('Tarte au sucre', 7, 17, 'Confections', 68);
        $this->createIfNotExisting('Vegie-spread', 7, 24, 'Condiments', 54);
        $this->createIfNotExisting('Wimmers gute Semmelknödel', 7, 22, 'Grains/Cereals', 37);
        $this->createIfNotExisting('Louisiana Fiery Hot Pepper Sauce', 7, 76, 'Condiments', 83);
        $this->createIfNotExisting('Louisiana Hot Spiced Okra', 17, 4, 'Condiments', 28);
        $this->createIfNotExisting('Laughing Lumberjack Lager', 14, 52, 'Beverages', 69);
        $this->createIfNotExisting('Scottish Longbreads', 8, 6, 'Confections', 46);
        $this->createIfNotExisting('Gudbrandsdalsost', 8, 26, 'Dairy Products', 57);
        $this->createIfNotExisting('Outback Lager', 15, 15, 'Beverages', 33);
        $this->createIfNotExisting('Flotemysost', 8, 26, 'Dairy Products', 72);
        $this->createIfNotExisting('Mozzarella di Giovanni', 8, 14, 'Dairy Products', 79);
        $this->createIfNotExisting('Röd Kaviar', 15, 101, 'Seafood', 95);
        $this->createIfNotExisting('Longlife Tofu', 10, 4, 'Produce', 21);
        $this->createIfNotExisting('Rhönbräu Klosterbier', 9, 125, 'Beverages', 88);
        $this->createIfNotExisting('Lakkalikööri', 9, 57, 'Beverages', 53);
        $this->createIfNotExisting('Original Frankfurter grüne Soße', 13, 32, 'Condiments', 41);
        $this->createIfNotExisting('Tidningen Buster', 13, 32, 'Tidningar', 65);

        $seeded = true;

    }

    function createIfNotExisting($title, $price, $stockLevel, $categoryName, $popularity)
    {
        $existing = $this->getProductByTitle($title);
        if ($existing) {
            return;
        }
        ;
        return $this->addProduct($title, $price, $stockLevel, $categoryName, $popularity);

    }

    function addCategory($title)
    {
        $prep = $this->pdo->prepare('INSERT INTO category (title) VALUES(:title )');
        $prep->execute(["title" => $title]);
        return $this->pdo->lastInsertId();
    }


    function addProduct($title, $price, $stockLevel, $categoryName, $popularity)
    {

        $category = $this->getCategoryByTitle($categoryName);
        if ($category == false) {
            $this->addCategory($categoryName);
            $category = $this->getCategoryByTitle($categoryName);
        }


        //insert plus get new id 
        // return id             
        $prep = $this->pdo->prepare('INSERT INTO products (title, price, stockLevel, categoryId, popularity) VALUES(:title, :price, :stockLevel, :categoryId, :popularity )');
        $prep->execute(["title" => $title, "price" => $price, "stockLevel" => $stockLevel, "categoryId" => $category->id, "popularity" => $popularity]);
        return $this->pdo->lastInsertId();

    }

    function initIfNotInitialized()
    {

        static $initialized = false;
        if ($initialized)
            return;


        $sql = "CREATE TABLE IF NOT EXISTS `category` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `title` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
            ) ";

        $this->pdo->exec($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `products` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `title` varchar(200) NOT NULL,
            `price` INT,
            `stockLevel` INT,
            `categoryId` INT NOT NULL,
            `popularity` INT,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`categoryId`)
                REFERENCES category(id)
            ) ";

        $this->pdo->exec($sql);

        $initialized = true;
    }


}


?>