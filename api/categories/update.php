<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        update.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database and Category class
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// instantiate database and Category object
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"), false);

// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->code) &&
    !empty($data->name) &&
    !empty($data->description)
){
    // set props
    $category->id = $data->id;
    $category->code = $data->code;
    $category->name = $data->name;
    $category->description = $data->description;

    // update category
    if($category->update()) {

        http_response_code(200);
        echo json_encode(array("message" => "Category was updated"));
    }

    else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to update the category"));
    }

}

//
else {
    http_response_code(400);
    echo json_encode(array("message" => "unable to update category. Data is incomplete"));

}