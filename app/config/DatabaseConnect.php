<?php 


class DatabaseConnect {
    
    private $host = "futurewebbuilders.design";
    private $database = "ecommerce_jlucero"; //changed 
    private $dbusername = "jlucero";
    private $dbpassword = "J73cer0_2024";
    private $charset    = 'utf8mb4';
    private $conn = null; 

    /*
    $host = "futurewebbuilders.design";
    $database = "ecommerce_jlucero";
    $dbusername = "jlucero";
    $dbpassword = "J73cer0_2024";  */


    public function connectDB(){
        $dsn = "mysql: host=$this->host;dbname=$this->database;charset=" . $this->charset;
        try {
            $this->conn = new PDO($dsn, $this->dbusername, $this->dbpassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $this->conn;
        } catch (PDOException $e){
            echo "Connection Failed: " . $e->getMessage();
            return null;
        }    
    }

}
