<?php

if(!isset($_SESSION)){
    session_start();
}

require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
include("../config/DatabaseConnect.php"); //to access database connection

$db = new DatabaseConnect(); //make a new database instance

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $productId      = htmlspecialchars($_POST["id"]);
    $productName    = htmlspecialchars($_POST["productName"]);
    $productDesc    = htmlspecialchars($_POST["description"]);
    $category       = htmlspecialchars($_POST["category"]);
    $basePrice      = htmlspecialchars($_POST["basePrice"]);
    $numberOfStocks = htmlspecialchars($_POST["numberOfStocks"]);
    $unitPrice      = htmlspecialchars($_POST["unitPrice"]);
    $totalPrice     = htmlspecialchars($_POST["totalPrice"]);
    $productImage2   = htmlspecialchars($_POST["productImage2"]);

    if(trim($productName) == "" || empty($productName)){
        $_SESSION["error"] = "Product Name field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if(trim($productDesc) == "" || empty($productDesc)){
        $_SESSION["error"] = "Product Description field is empty";
            
        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
     }
     
    if(trim($category) == "" || empty($category)){
        $_SESSION["error"] = "Product Category field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if(trim($basePrice) == "" || empty($basePrice)){
        $_SESSION["error"] = "Base Price field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if(trim($numberOfStocks) == "" || empty($numberOfStocks)){
        $_SESSION["error"] = "Number of Stocks field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if(trim($unitPrice) == "" || empty($unitPrice)){
        $_SESSION["error"] = "Unit Price field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if(trim($totalPrice) == "" || empty($totalPrice)){
        $_SESSION["error"] = "Total Price field is empty";

        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    if (!isset($productImage2) || empty($productImage2)) {
        $_SESSION["error"] = "No image attached";
    
        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }
  
  
    try {
    $conn = $db->connectDB();
    $sql = "UPDATE products SET products.product_name = :p_product_name,
                    products.product_description = :p_product_description,
                    products.category_id = :p_category_id,
                    products.base_price = :p_base_price,
                    products.stocks = :p_stocks,
                    products.unit_price = :p_unit_price,
                    products.total_price = :p_total_price,
                    products.updated_at = NOW()
                    WHERE products.id = :p_id";   
    
    $stmt = $conn->prepare($sql);
    $data = [':p_product_name'    => $productName,
         ':p_product_description' => $productDesc,
         ':p_category_id'         => $category,
         ':p_base_price'          => $basePrice,
         ':p_stocks'              => $numberOfStocks,
         ':p_unit_price'          => $unitPrice,
         ':p_total_price'         => $totalPrice, 
         ':p_id'                  => $productId];
    
    if(!$stmt->execute($data)){
        $SESSION["error"] = "Failed to update the record";
        header("location: ".BASE_URL."views/admin/products/edit.php");
        exit;
    }

    $lastId = $productId;
    
    if(isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0){
        $error = processImage($lastId);
        if($error){
            $_SESSION["error"] = $error;
            header("location: ".BASE_URL."views/admin/products/edit.php");
            exit;
        }
    }
    
     
    $_SESSION["success"] = "Product updated successfully";
    header("location: ".BASE_URL."views/admin/products/index.php");
    exit;
    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        $db = null;
    }


}

function processImage($id){
    global $db;


    $path         = $_FILES['productImage']['tmp_name'];
    $fileName     = $_FILES['productImage']['name'];
    $fileType     = mime_content_type($path); 

 
    if($fileType != 'image/jpeg' && $fileType != 'image/png'){
        return "File is not a jpg/png file";
    }

    $newFileName = md5(uniqid($fileName,true));
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $hashedName = $newFileName.'.'.$fileExt;


    $destination = ROOT_DIR.'public/uploads/products/'.$hashedName;
    if(!move_uploaded_file($path, $destination)){
        return "Transferring of image returns an error";
    }


    $imageUrl = 'public/uploads/products/'.$hashedName;

    $conn = $db->connectDB();
    $sql = "UPDATE products  SET image_url = :p_image_url WHERE id = :p_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':p_image_url', $imageUrl);
    $stmt->bindParam(':p_id', $id);
    
 
    if(!$stmt->execute()){
        return "Failed to update the image url field";
    };

 
    return null;
}
