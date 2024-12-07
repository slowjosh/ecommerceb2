<?php

if(!isset($_SESSION)){
    session_start();
}

require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
include("../config/DatabaseConnect.php"); //to access database connection


//force the user to login if not currently signed in
if(!isset($_SESSION["user_id"])){
    header("location: ".BASE_URL."login.php");
    exit;
}

$db = new DatabaseConnect(); //make a new database instance

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //retrieve user input
    $productId      = htmlspecialchars($_POST["id"]);
    $quantity       = htmlspecialchars($_POST["quantity"]);
    $userId         = $_SESSION["user_id"];

    //validate user input
    if(trim($productId) == "" || empty($productId)){
        $_SESSION["error"] = "Product Id field is empty";

        header("location: ".BASE_URL."views/product/product.php?id=" .$productId);
        exit;
    }

    if(trim($quantity) == "" || empty($quantity) || $quantity < 1){
        $_SESSION["error"] = "Quantity field is empty";
            
        header("location: ".BASE_URL."views/product/product.php?id=" .$productId);
        exit;
     }
     
    if(trim($userId) == "" || empty($userId)){
        $_SESSION["error"] = "User ID field is empty";

        header("location: ".BASE_URL."views/product/product.php?id=" .$productId);
        exit;
    }

    
  
    //update record to database
    try {
    $conn = $db->connectDB();
    $sql = "SELECT * FROM products WHERE products.id = :p_product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':p_product_id', $productId);
    if(!$stmt->execute()){
       
    }
    $product = $stmt->fetch(); //return only 1 record
    
    
    $computedPrice = ($product["unit_price"] * $quantity);
    $sql = "INSERT INTO carts (user_id, product_id, quantity, unit_price, total_price,created_at, updated_at)
            VALUES (:p_user_id, :p_product_id, :p_quantity, :p_unit_price, :p_total_price, NOW(), NOW())"; 
    $stmt = $conn->prepare($sql);
    $data = [':p_user_id'     => $userId,
             ':p_product_id'  => $productId,
             ':p_quantity'    => $quantity,
             ':p_unit_price'  => $product["unit_price"],
             ':p_total_price' => $computedPrice];      
    
    
  
    if(!$stmt->execute($data)){
        $SESSION["error"] = "Failed to update the record";
        header("location: ".BASE_URL."views/product/product.php?id=" .$productId);
        exit;
    }

    $lastId = $productId;

    
    
     
    $_SESSION["success"] = "Added to cart successfully";
    header("location: ".BASE_URL."views/product/product.php?id=" .$productId);
    exit;

    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        $db = null;
    }

    //process image

    //redirect back to product/index.php once successful
}
