<?php
require_once 'Database/Database.php';
class AddProductModel
{
    private $conn;
    function __construct()
    {
        $this->conn = new Database();

    }
    function getProducts()
    {
        $users=$this->conn->query("SELECT * FROM users order by id DESC");
        return $users->fetchAll();
       

    }

    function createProduct($data)
    {
        $stmt = $this->conn->query("INSERT INTO users (name, image) VALUES (:name, :image)",[
            'image' => $data['image'],
            'name' => $data['name']
        ]);
    }
}
