<html>
<head>
    <title>Registration Form</title>
    <style type="text/css">
        body {
            background-color: #fff;
            border-top: solid 10px #000;
            color: #333;
            font-size: .85em;
            margin: 20px;
            padding: 20px;
            font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
        }
        h1, h2, h3 {
            color: #000;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        h2 {
            font-size: 1.75em;
        }
        h3 {
            font-size: 1.2em;
        }
        table {
            margin-top: 0.75em;
        }
        th {
            font-size: 1.2em;
            text-align: left;
            border: none;
            padding-left: 0;
        }
        td {
            padding: 0.25em 2em 0.25em 0em;
            border: none;
        }
    </style>
</head>
<body>
<h1>Register here!</h1>
<p>Fill in your name and email address, then click <strong>Submit</strong> to register.</p>
<form method="post" action="index.php" enctype="multipart/form-data">
    Name <input type="text" name="name" id="name" /><br/>
    Email <input type="text" name="email" id="email" /><br/>
    <input type="submit" name="submit" value="Submit" />
</form>

<?php
// DB connection info
$host = "ruap-lv3-server-1.mysql.database.azure.com";
$user = "nummgfxzue";
$pwd = '12ruap34lv3-56';
$db = "ruap-lv3-server-1";

// Connect to MySQL
$conn = mysqli_connect($host, $user, $pwd);

// Check connection
if (mysqli_connect_errno()) {
    die("<h3>Failed to connect to MySQL: </h3>" . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sql) === TRUE) {
    echo "<h3>Database ensured successfully.</h3>";
} else {
    echo "<h3>Error creating database: </h3>" . $conn->error;
}

// Select the database
mysqli_select_db($conn, $db);

// Create table if not exists
$sql_create = "CREATE TABLE IF NOT EXISTS registration_tbl (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30),
    email VARCHAR(30),
    date DATE
)";
if ($conn->query($sql_create) === TRUE) {
    echo "<h3>Table ensured successfully.</h3>";
} else {
    echo "<h3>Error creating table: </h3>" . $conn->error;
}

// Handle form submission
if (!empty($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = date("Y-m-d");

    // Insert data
    $sql_insert = "INSERT INTO registration_tbl (name, email, date)
                   VALUES ('$name','$email','$date')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "<h3>You're registered!</h3>";

        // Retrieve and display all registrants
        $sql_select = "SELECT * FROM registration_tbl";
        $registrants = $conn->query($sql_select);

        if ($registrants->num_rows > 0) {
            echo "<h2>People who are registered:</h2>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Email</th><th>Date</th></tr>";
            while ($registrant = $registrants->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($registrant['name']) . "</td>";
                echo "<td>" . htmlspecialchars($registrant['email']) . "</td>";
                echo "<td>" . htmlspecialchars($registrant['date']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<h3>No one is currently registered.</h3>";
        }
    } else {
        echo "<h3>Insert Failed: </h3>" . $conn->error;
    }
}

$conn->close();
?>
</body>
</html>