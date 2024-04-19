<?php
    session_start(); 
    // Database connection configuration
    $dbhost = "10.0.19.74";
    $dbuser = "wil02163";
    $dbpass = "wil02163";
    $dbname = "db_wil02163";

    $usernameErr = $passErr = "";
    $username = $pass = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = test($_POST["uname"]);
        $pass = test($_POST["psw"]);

        // USER NAME validation
        if(empty($_POST["uname"])){
            $usernameErr = "User Name is Required"; 
        }else{
            $username = test($_POST["uname"]);
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

    if(empty($usernameErr) && empty($passErr)){
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        // Check the database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("SELECT * FROM Users WHERE userName=? AND userpassword=?");
        $stmt->bind_param("ss", $username, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) { // User found
            $row = $result->fetch_assoc(); // Fetch the row
            $_SESSION['username'] = $username; // Set session variable
            echo $row["realname"] . " " . $row["email"]; // Redirect to dashboard or any other page
            header("Location: manage_database.php");
            exit();
        } else {
            echo "Invalid username or password"; // Or set an appropriate error message
        }

        $stmt->close();
        $conn->close();
    }
    else {
        include 'login.html';
    }
?>