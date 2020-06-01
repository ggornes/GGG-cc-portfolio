<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        Category.php
 * Author:      Your name
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

include_once 'Utils.php';

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
    public $name;
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
        $query = "SELECT * FROM {$this->tableName} ORDER BY created_at DESC";

        // prepare, bind and execute
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Create
     * @return bool
     */
    public function create()
    {
        // query to insert record
        $query = "INSERT INTO {$this->tableName}(`id`, `code`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`)
                  VALUES (NULL, :catCode, :catName, :catDescription, now(), NULL, NULL)";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->code = Utils::sanitize($this->code);
        $this->name = Utils::sanitize($this->name);
        $this->description = Utils::sanitize($this->description);

        // bind values
        $stmt->bindParam(":catCode", $this->code, PDO::PARAM_STR);
        $stmt->bindParam(":catName", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":catDescription", $this->description, PDO::PARAM_STR);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne($id) {
        // Select by id query
        $query = "SELECT * FROM {$this->tableName} WHERE id = :catId ORDER BY created_at DESC";

        // prepare, bind and execute
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':catId', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

}