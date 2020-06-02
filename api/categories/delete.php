<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        delete.php
 * Author:      Your name
 * Date:        2020-06-02
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include Database and Category class
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

$data = json_decode(file_get_contents("php://input"), false);

if (isset($data->id)) {
    $id = $data->id;

    // instantiate database and get database connection
    $database = new Database();
    $db = $database->getConnection();

    // initialize product object
    $category = new Category($db);

    $stmt = $category->delete($id);
    $num = $stmt->rowCount();

    // Check records
    if ($num > 0) {
        // set response code - 200 OK
        http_response_code(200);

        // show category data in json format
        echo json_encode(array("message" => "Category deleted."));
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no category found
        echo json_encode(
            array("message" => "Category not deleted.")
        );
    }
}
else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no id provided
    echo json_encode(
        array("message" => "No id provided.")
    );
}