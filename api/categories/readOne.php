<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        readOne.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database and Category class
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // instantiate database and get database connection
    $database = new Database();
    $db = $database->getConnection();

    // initialize category object
    $category = new Category($db);

    // query categories with the given ID
    $stmt = $category->readOne($id);
    $num = $stmt->rowCount();

    // check if more than 0 records found
    if($num > 0) {
        $categoryList["records"] = [];

        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $categoryItem = array(
                "id" => $row->id,
                "code" => $row->code,
                "name" => $row->name,
                "description" => $row->description,
                "createdAt" => $row->created_at,
                "updatedAt" => $row->updated_at,
                "deletedAt" => $row->deleted_at
            );
        }

        // store records (push into array)
        $categoryList["records"][] = $categoryItem;

        // set response code 200 ok
        http_response_code(200);

        // send categories to JSON response
        echo json_encode($categoryList);
    }

    else {
        // set response code 404 not found
        http_response_code(404);

        // display message
        echo json_encode(
            array("message" => "No category found")
        );
    }
}

else {
    // No id found
    http_response_code(404);

    echo json_encode(
      array("message" => "No id provided.")
    );
}
