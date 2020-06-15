<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        delete.php
 * Author:      Your name
 * Date:        2020-06-15
 * Version:     1.0.0
 * Description:
 *******************************************************/

include_once '../../config/Database.php';
include_once '../../classes/Utils.php';
$database = new Database();
$conn = $database->getConnection();

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>PDO - Delete Record - PHP CRUD Tutorial</title>
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
                <a class="nav-link" href="../">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="../product" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Product
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
            <h1>Delete Category</h1>
        </div>
    </div>

    <?php
    $messages = [];
    if (!isset($_POST['id']) && !isset($_GET['id'])) {
        $messages[] = ['Danger' => 'Record Id not found.'];
    }
    else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        // Check if form was submitted
        if ($_POST) {
        }

        $performDelete = $_POST['performDelete'] ?? false;
        if (!$performDelete) {
            try {
                $query = "SELECT id, code, name, description FROM categories WHERE id = :ID LIMIT 0,1";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":ID", $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                ?>
                <!--we have our html table here where the record will be displayed-->
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Code</td>
                        <td><?= Utils::sanitize($row->code) ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?= Utils::sanitize($row->name) ?></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><?= Utils::sanitize($row->description) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <form action='delete.php' method='post'>
                                <input type='hidden' id='id' name='id'
                                       value="<?= $row->id ?>"/>
                                <input type='hidden' id='performDelete' name='performDelete'
                                       value="<?= $row->id ?>"/>
                                <button type='submit' value='submit' class='btn btn-danger'>
                                    Confirm Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
                <?php
            } // show error
            catch (PDOException $exception) {
                $messages[] = ['Danger' => 'Error locating the category. Please contact Admin.'];
                $messages[] = ['Secondary ' => $exception->getMessage()];
            }
        }
            try {
                $query = "DELETE FROM categories WHERE id = :deleteThis";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':deleteThis', $id, PDO::PARAM_INT);
                $stmt->execute();
                $messages[] = ['Success' => "Category {$performDelete} deleted."];
            } // show error
            catch (PDOException $exception) {
                $messages[] = ['Danger' => 'Error deleting the category. Please contact Admin.'];
                $messages[] = ['Secondary ' => $exception->getMessage()];
            }

    if (count($messages) > 0) {
        Utils::messages($messages);
    }

    }

    ?>





    <p><a href='browse.php' class='btn btn-primary'>Browse categories</a></p>
</div> <!-- end .container -->
<script src="/app/assets/jquery/jquery-3.5.1.min.js"></script>
<script src="/app/assets/popper/popper.min.js"></script>
<script src="/app/assets/bs/js/bootstrap.min.js"></script>
</body>
</html>