<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'resumebuilder';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$action = isset($_GET['action']) ? $_GET['action'] : 'view';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $regid = mysqli_real_escape_string($conn, $_POST['regid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $action = $_POST['action'];

    $sql = "SELECT * FROM users WHERE regid='$regid' AND password='$pwd'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $_SESSION['regid'] = $regid;

       
        if ($action == 'view') {
            header("Location: view.php");
        } elseif ($action == 'edit') {
            header("Location: edit.php");
        } elseif ($action == 'create') {
            header("Location: create.php");
        } else {
            echo "<p style='color:red;'>Unknown action specified.</p>";
        }
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Invalid Register ID or Password.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Resume Builder</title>
   <style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Manrope',sans-serif;
}

body{

background:#ECECEC;
color:#222;
min-height:100vh;

}

.container{

width:90%;
max-width:900px;
margin:60px auto;
background:white;
padding:40px;
border-radius:18px;
box-shadow:0 15px 40px rgba(0,0,0,.08);

}

h1,h2,h3{

text-align:center;
font-weight:700;
margin-bottom:25px;
letter-spacing:.5px;

}

p{

color:#555;
line-height:1.8;

}

input,
textarea,
select{

width:100%;
padding:14px;
margin-top:8px;
margin-bottom:18px;

border:none;

background:#F5F5F5;

border-radius:10px;

font-size:15px;

transition:.3s;

}

input:focus,
textarea:focus,
select:focus{

outline:none;

background:white;

box-shadow:0 0 0 2px #888;

}

textarea{

resize:vertical;
min-height:120px;

}

button,
input[type=submit]{

background:#3A3A3A;
color:white;

border:none;

padding:14px 25px;

border-radius:10px;

cursor:pointer;

font-size:15px;

font-weight:600;

transition:.3s;

}

button:hover,
input[type=submit]:hover{

background:#1F1F1F;

transform:translateY(-2px);

}

a{

text-decoration:none;

color:#333;

transition:.3s;

}

a:hover{

color:black;

}

table{

width:100%;

border-collapse:collapse;

}

td{

padding:12px;

vertical-align:top;

}

.label{

font-weight:600;

color:#333;

}

.resume-section{

margin-top:35px;

padding-top:15px;

border-top:1px solid #ddd;

}

.resume-section h3{

text-align:left;

margin-bottom:15px;

}

.success{

padding:15px;

border-radius:10px;

background:#E6F4EA;

color:#256029;

margin-bottom:20px;

}

.error{

padding:15px;

border-radius:10px;

background:#FDEAEA;

color:#B42318;

margin-bottom:20px;

}

</style>
</head>
<body>
    <h2>Login to Resume Builder</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="<?php echo htmlspecialchars($action); ?>">
        <input type="text" name="regid" placeholder="Register ID" required><br>
        <input type="password" name="pwd" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
