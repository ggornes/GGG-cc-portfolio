<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        Category.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

class Category
{
    /**
     * Database connection and table name
     */
    /** @var */
    private $conn;
    /** @var string */
    private $tableName = "categories";

    /**
     * Category object properties
     */

    public $id;
    public $code;
    public $description;
    public $icon;
    public $createdAt;
    public $updatedAt;
    public $deletedAt;

    /**
     * Category constructor
     * Take a database connection as a parameter
     * Use: `$categories = new Category($db)`
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }


    /**
     * Read and return category data
     * Use: `$categoryList = $category->read();`
     */
    public function read()
    {
        // Select all query
        $query = "SELECT * FROM {$this->tableName} ORDER BY created_at";

        // prepare, bind and execute
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

}