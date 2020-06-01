<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        read.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

// require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here
// include database and object files
include_once '../../config/Database.php';
include_once '../../classes/Category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$category = new Category($db);

// read categories
// query categories
$stmt = $category->read();

// number of records
$num = $stmt->rowCount();

// if records found
if ($num>0) {

    // store categories records in an array
    $categories_arr["categories"]=[];

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        $category_item = array(
            "id" => $row->id,
            "code" => $row->code,
            "name" => $row->name,
            "description" => $row->description,
            "createdAt" => $row->created_at
        );
        // array_push($categories_arr["records"], $category_item); slower than
        $categories_arr['records'][] = $category_item;
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($categories_arr);
}

else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );

}