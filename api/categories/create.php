<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        create.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

// require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include Database and Category classes
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// instantiate database and Category object
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"), false);

// make sure all data required is present
if(
    !empty($data->code) &&
    !empty($data->name) &&
    !empty($data->description)
) {
    // set category property values
    $category->code = $data->code;
    $category->name = $data->name;
    $category->description = $data->description;

    // create category
    if ($category->create()) {
        // set response code 201 (created)
        http_response_code(201);

        // display success message to user
        echo json_encode(array("message" => "Category was created"));
    } // if unable to create the category
    else {
        // set response code 500 (Internal server error)
        http_response_code(500);
        // display error message
        echo json_encode(array("message" => "Unable to create category"));
    }
} else {
    // data is not valid or incomplete
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create product. Data is incomplete"));
}