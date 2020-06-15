<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        read.php
 * Author:      Your name
 * Date:        2020-06-15
 * Version:     1.0.0
 * Description:
 *******************************************************/

include '../../config/Database.php';
include '../../classes/Utils.php';
//$targetDirectory = "../../{$targetDirectory}/";

?>
<!DOCTYPE HTML>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>App Practice | Categories | Read</title>

    <!-- CSS required -->
    <!-- Bootstrap 4.x -->
    <link rel="stylesheet" href="/app/assets/bs/css/bootstrap.min.css">
    <!-- FontAwesome 5.x -->
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
<main role="main" class="container">

    <div class="row">
        <div class="col-sm">
            <h1>Show one Category</h1>
        </div>
    </div>


    <?php
    $messages = [];
    if(isset($_GET['id'])) {
        $id = isset($_GET['id'])
            ?
            $_GET['id'] : die('ERROR: Record ID not found.');
    }

    // read current record data
    try {
        $readQuery = "SELECT id, code, name, description FROM categories WHERE id = :ID LIMIT 0,1;";
        $database = new Database();
        $conn = $database->getConnection();

        $stmt = $conn->prepare($readQuery);
        $stmt->bindParam(':ID', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$row){
            $messages[] = ['Warning' => 'Category not found'];
        } else {
            // values to fill up our details
            $name = Utils::sanitize($row->name);
            $code = Utils::sanitize($row->code);
            $description = Utils::sanitize($row->description);


        }
    } // show error
    catch (PDOException $exception){
        $messages[] = ['Danger' => 'Critical Error. Please contact site admin.'];
        $messages[] = ['Secondary ' => $exception->getMessage()];
    }


    if (count($messages) == 0) {
    ?>

    <div class="row">
        <div class="col-sm-8 col-md-6">

            <div clas="row">
                <p class="col-sm-6 col-md-3"><strong>Code:</strong></p>
                <p class="col"><?= $code ?></p>
            </div>

            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Name:</strong></p>
                <p class="col"><?= $name ?></p>
            </div>

            <div clas="row">
                <p class="col-sm-6 col-md-3"><strong>Description:</strong></p>
                <p class="col"><?= $description ? $description : "<i>No Description</i>" ?></p>
            </div>

            <div class="row">
                <div class="col">
                    <form action="delete.php" method="post">
                        <input type="hidden" id="id" name="id" value="<?= $row->id ?>"/>
                        <a href="../product/update.php?id=<?= $row->id ?>" class="btn btn-primary mr-1">Edit</a>
                        <button type="submit" value="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php
        }

    if (count($messages) > 0) {
        Utils::messages($messages);
    }
    ?>


    <p class="mt-3"><a href="browse.php" class="btn btn-primary">Back to Browse Categories</a> </p>

</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="/app/assets/jquery/jquery-3.5.1.min.js"></script>
<script src="/app/assets/popper/popper.min.js"></script>
<script src="/app/assets/bs/js/bootstrap.min.js"></script>

</body>
</html>