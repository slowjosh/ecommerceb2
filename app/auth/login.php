<?php 
//recevived user input
$username = $_POST["username"];
$password = $_POST["password"];

session_start(); //para kung ano yung username na ininput, magappear don sa part ni John Doe

include('../config/DatabaseConnect.php');

if($_SERVER["REQUEST_METHOD"] == "POST")
{
        //database connection      
        $db = new DatabaseConnect();
        $conn = $db->connectDB();

        try {
       
            $stmt = $conn->prepare("SELECT * from users WHERE username = :p_username");
            $stmt -> bindParam(':p_username', $username);
            $stmt -> execute();
            $users = $stmt->fetchAll();
        
        if($users)
        {
            if(password_verify($password, $users[0]["password"]))
            {
                $_SESSION = [];
                session_regenerate_id(true);
                $_SESSION['user_id'] = $users[0]['id'];
                $_SESSION['username'] = $users[0]['username'];
                $_SESSION['fullname'] = $users[0]['fullname'];
                $_SESSION['is_admin'] = $users[0]['is_admin'];

                header("location: /index.php");
                exit;
            }else
            {
                header("location: /login.php");
                $_SESSION["error"] = "Password not match";
                exit;
            } 
        }
        else{
            header("location: /login.php");
            $_SESSION["error"] = "User not found";
            exit;
        }

        }catch (Exception $e){
            echo "Connection Failed: " . $e->getMessage();
        }

    }

?>