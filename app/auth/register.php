<?php

    session_start();
    //receive user input
    $fullName = $_POST["fullName"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(trim($password) == trim($confirmPassword))
        {
            //connect database 
            
           /*  
            $host = "localhost";
            $database = "ecommerce";
            $dbusername = "root";
            $dbpassword = ""; */

           
            $host = "futurewebbuilders.design";
            $database = "ecommerce_jlucero";
            $dbusername = "jlucero";
            $dbpassword = "J73cer0_2024";

            $dsn = "mysql: host=$host;dbname=$database;";
            try 
            {
                $conn = new PDO($dsn, $dbusername, $dbpassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, created_at, updated_at) VALUES(:p_fullname, :p_username, :p_password, NOW(), NOW())");
                $stmt->bindParam(':p_fullname', $fullName); 
                $stmt->bindParam(':p_username', $username); 
                $stmt->bindParam(':p_password', $password); 

                $password = password_hash(trim($password), PASSWORD_BCRYPT);
           
                if($stmt->execute())
                {
                    header("location: /registration.php");
                    $_SESSION["success"] = "Registration Successful";
                    exit;
                }else
                {
                    header("location: /registration.php");
                    $_SESSION["error"] = "Insert Error";
                    exit;
                }

            } catch (Exception $e)
            {
                header("location: /registration.php");
                $_SESSION["error"] = "Username already exist";
            }
            
            
        }else
        {
            header("location: /registration.php");
            $_SESSION["error"] = "Password Mismatch";
            exit;
        }
            
    }
        
             //insert record
?>