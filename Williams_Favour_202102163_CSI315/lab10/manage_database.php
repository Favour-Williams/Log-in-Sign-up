<?php
session_start();

// Database connection
$dbhost = "10.0.19.74";
$dbuser = "wil02163";
$dbpass = "wil02163";
$dbname = "db_wil02163";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}
function test($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// CRUD operations
if (isset($_SESSION['username'])) {
    // Display CRUD operations here
    echo "<style>h2{color:white;}</style>";
    echo "<h2>Welcome, ".$_SESSION['username']."!</h2><br><br>";

    // Add record
    if (isset($_POST['add'])) {
        $id = test($_POST['id']);
        $name = test($_POST['name']);

        $stmt = $conn->prepare("INSERT INTO test (id, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $id, $name);

        if ($stmt->execute()) {
            echo "<style>h2{color:white;}</style>";
            echo "<h2>Record added successfully</h2><br>";
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }

    // Update record
    if (isset($_POST['update'])) {
        $id = test($_POST['id']);
        $name = test($_POST['name']);

        $stmt = $conn->prepare("UPDATE test SET name=? WHERE id=?");
        $stmt->bind_param("ss", $name, $id);

        if ($stmt->execute()) {
            echo "<style>h2{color:white;}</style>";
            echo "<h2>Record updated successfully</h2><br>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    }

    // Delete record
    if (isset($_POST['delete'])) {
        $id = test($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM test WHERE id=?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo "<style>h2{color:white;}</style>";
            echo "<h2>Record deleted successfully</h2><br>";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    }
    echo "
        <style>
        
            table {
                width: 60%;
                border-collapse: collapse;
                margin-top: 20px;
                margin-bottom: 25px;
                margin-left: auto;
                margin-right: auto;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            tr:nth-child(even) {
               
                background-color: #f2f2f2;
            }
            tr:nth-child(odd){
                color: white;
            }
        </style>
    ";

   // Retrieve records
   $id = test($_POST['id']);
    $name = test($_POST['name']);
    $stmt = $conn->prepare("SELECT * FROM test");
    $stmt->bind_param("ss", $id, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th></tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No records found";
    }
   
    $stmt->close();

    echo "
    <style>
        body {
            background: linear-gradient(to right, blue, green);
            background-size: 200% 200%;
            animation: gradient 5s ease infinite;
        }
    
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            animation: fadeIn 1s ease-in-out;
            width: 40%;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    
        input[type='text'] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    
        input[type='submit'] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    
        input[type='submit']:hover {
            background-color: #45a049;
        }
        a{ 
            color:white;
            position:absolute;
            top:0;
            right:0;
            font-size:25px;
        }
        a:hover{
            color:red;
            transition: 0.8s ease-in;
        }

    </style>
    ";
    // Add record form
    echo "
    <br><br>
    <form method='post' action='manage_database.php'>
        <input type='text' name='id' placeholder='ID' required><br>
        <input type='text' name='name' placeholder='Name' required><br>
        <input type='submit' name='add' value='Add Record'>
    </form>
    ";

    // Update record form
    echo "
    <br><br>
    <form method='post' action='manage_database.php'>
        <input type='text' name='id' placeholder='ID' required><br>
        <input type='text' name='name' placeholder='New Name' required><br>
        <input type='submit' name='update' value='Update Record'>
    </form>
    ";

    // Delete record form
    echo "
    <br><br>
    <form method='post' action='manage_database.php'>
        <input type='text' name='id' placeholder='ID' required><br>
        <input type='submit' name='delete' value='Delete Record'>
    </form>
    ";

    // Logout link
    echo "<br><br><a href='manage_database.php?logout=true'>Logout</a>";
}

$conn->close();
?>
