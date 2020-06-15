<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        create.php
 * Author:      Your name
 * Date:        2020-06-15
 * Version:     1.0.0
 * Description:
 *******************************************************/


// include database connection an utils
include '../../config/database.php';
include_once '../../classes/Utils.php';
/**
 * Initialise the common variables for the add page
 */
$messages = [];
$code = "";
$name = "";
$description = "";
$price = "";

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="/app/assets/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="/app/assets/fa/css/all.min.css">
</head>
<body>
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../">Demo APP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="../product" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Product  <span class="sr-only">(current)</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../product/browse.php">Browse</a>
                    <a class="dropdown-item" href="../product/create.php">Add</a>
                </div>
            </li>

            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="../category" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Category
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../category/browse.php">Browse</a>
                    <a class="dropdown-item" href="../category/create.php">Add</a>
                </div>
            </li>
    </div>
</nav>
<!-- container -->
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h1>Create Category</h1>
        </div>
    </div>

    <?php
        if ($_POST) {
            // posted values
            $code = Utils::sanitize($_POST['code']);
            $name = Utils::sanitize($_POST['name']);
            $description = Utils::sanitize($_POST['description']);


            // this needs validation of inputs...
            if (empty($name)) {
                $messages[] = ['Warning' => "Name must not be empty."];
            }
            if (empty($code)) {
                $messages[] = ['Warning' => "Code must not be empty."];
            }


            // if no basic validation errors we can add the data to the database
            if (empty($messages)) {
                try {
                    $database = new Database();
                    $conn = $database->getConnection();

                    // insert query
                    $query = "INSERT INTO categories SET code=:code, name=:name, description=:description, created_at=:created";


                    // prepare query for execution
                    $stmt = $conn->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);


                    // specify when this record was inserted to the database
                    // (Do you need to do this?)
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    // Execute the query
                    if ($stmt->execute()) {
                        $messages[] = ['Success' => "Record saved."];

                        }
                    else {
                        $messages[] = ['Danger' => "Unable to save record."];
                    }
                }
                catch (PDOException $exception) {
                    die('ERROR: '.$exception->getMessage());
                }
            }

            Utils::messages($messages);
            if (isset($messages[0]['success'])) {
                $name = "";
                $description = "";
                $price = "";
            }
        }
    ?>
<!-- html form here where the Category information will be entered -->
    <form action="<?= Utils::sanitize($_SERVER['PHP_SELF']) ?>"
        method="post"
        enctype="multipart/form-data">

        <div class="form-group">
            <label for="code">Code</label>
            <input type='text'
                   name='code' id="code"
                   class='form-control'
                <?= ($code !== '' ? "value='{$code}'" : '') ?>
                   placeholder="Enter category code"/>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type='text'
                   name='name' id="name"
                   class='form-control'
                <?= ($name !== '' ? "value='{$name}'" : '') ?>
                   placeholder="Enter category name"/>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                    name='description' id="description"
                    class='form-control'
                <?= ($description !== '' ? "value='{$description}'" : '') ?>
                    placeholder="Enter product description"></textarea>
        </div>


        <div class="form-group">
            <label for="submit"></label>
            <input type='submit' value='Save'
                   name="submit" id="submit"
                   class='btn btn-primary'/>
        </div>
        <div class="form-group">
            <a href='browse.php'
               class='btn btn-secondary'>Browse Categories</a>
        </div>
        </form>
    </div> <!-- end .container -->
<script src="/app/assets/jquery/jquery-3.5.1.min.js"></script>
<script src="/app/assets/popper/popper.min.js"></script>
<script src="/app/assets/bs/js/bootstrap.min.js"></script>
</body>
</html>