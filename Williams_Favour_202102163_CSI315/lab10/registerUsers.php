<?php
    // Database connection configuration
    $dbhost = "10.0.19.74";
    $dbuser = "wil02163";
    $dbpass = "wil02163";
    $dbname = "db_wil02163";
    $rnameErr = $usernameErr = $emailErr = $passErr = "";
    $rname = $username = $email = $pass = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $rname = test($_POST["rname"]);
        $username = test($_POST["uname"]);
        $email = test($_POST["uemail"]);
        $pass = test($_POST["psw"]);

        // REAL NAME validation
        if(empty($_POST["rname"])){
            $rnameErr = "Name is Required"; 
        }else{
            $rname = test($_POST["rname"]);
        }

        // USER NAME validation
        if(empty($_POST["uname"])){
            $usernameErr = "User Name is Required"; 
        }else{
            $username = test($_POST["uname"]);
            //i
            $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            $check_username_sql = "SELECT * FROM Users WHERE userName = ?";
            $check_username_stmt = $conn->prepare($check_username_sql);
            $check_username_stmt->bind_param("s", $username);
            $check_username_stmt->execute();
            $check_username_result = $check_username_stmt->get_result();
            if ($check_username_result->num_rows > 0) {
                $usernameErr = "Username already exists";
            }
            $check_username_stmt->close();
            $conn->close();
        }

        // USER EMAIL validation
        if(empty($_POST["uemail"])){
            $emailErr = "Email is Required"; 
        }else{
            $email = test($_POST["uemail"]);
            // Check if email already exists
            $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
            $check_email_sql = "SELECT * FROM Users WHERE email = ?";
            $check_email_stmt = $conn->prepare($check_email_sql);
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_result = $check_email_stmt->get_result();
            if ($check_email_result->num_rows > 0) {
                $emailErr = "Email already exists";
            }
            $check_email_stmt->close();
            $conn->close();
        }

        // PASSWORD validation
        if(empty($_POST["psw"])){
            $passErr = "Password is Required"; 
        }else{
            $pass = test($_POST["psw"]); 
        }
    }
    function test($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(empty($rnameErr) && empty($usernameErr) && empty($passErr) && empty($emailErr)){
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        // Check the database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO Users (realname, userName, email, userpassword) VALUES (?, ?, ?, ?)";

        // Create a prepared statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $rname, $username, $email, $pass);
        if($stmt->execute()){
            echo "Registration Successful";
            header("location: RegisStatus");
        }else{
            echo "Error: " . $stmt->error;
        }
        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        include 'register.html';
    }
?>
