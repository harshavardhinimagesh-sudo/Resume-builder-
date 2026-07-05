<?php
session_start();


if (!isset($_SESSION['regid'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'resumebuilder';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$regid = $_SESSION['regid'];
$success = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $objective = $_POST['objective'];
    $skills = $_POST['skills'];
    $education = $_POST['education'];

    $check = $conn->query("SELECT * FROM resumes WHERE regid='$regid'");
    if ($check->num_rows > 0) {
        $error = "Resume already exists for this user.";
    } else {
        $stmt = $conn->prepare("INSERT INTO resumes (regid, fullname, email, phone, address, objective, skills, education) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $regid, $fullname, $email, $phone, $address, $objective, $skills, $education);

        if ($stmt->execute()) {
            $success = "Resume created successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Resume</title>
    <style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f1f5f9;
padding:40px;
}

h2{
text-align:center;
margin-bottom:30px;
color:#1e293b;
}

table{
margin:auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,.15);
width:700px;
}

td{
padding:12px;
}

input,
textarea{

width:100%;
padding:12px;
border:1px solid #cbd5e1;
border-radius:8px;
font-size:15px;

}

textarea{
height:90px;
resize:vertical;
}

input:focus,
textarea:focus{
outline:none;
border:1px solid #2563eb;
}

input[type=submit]{

width:220px;
background:#2563eb;
color:white;
border:none;
cursor:pointer;
font-weight:bold;
transition:.3s;

}

input[type=submit]:hover{

background:#1d4ed8;
transform:translateY(-2px);

}
input[type="submit"]{
    background-color: #1a1c20;
}

.success{

text-align:center;
color:green;
margin-bottom:20px;
font-weight:bold;

}

.error{

text-align:center;
color:red;
margin-bottom:20px;
font-weight:bold;

}

</style>
</head>
<body>

<h2>Create Your Resume</h2>

<div class="msg">
    <?php
    if ($success) echo "<p class='success'>$success</p>";
    if ($error) echo "<p class='error'>$error</p>";
    ?>
</div>

<form method="POST">
    <table border="1">
        <tr>
            <td>Full Name:</td>
            <td><input type="text" name="fullname" required></td>
        </tr>
        <tr>
            <td>Email ID:</td>
            <td><input type="email" name="email" required></td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td><input type="text" name="phone" required></td>
        </tr>
        <tr>
            <td>Address:</td>
            <td><textarea name="address" required></textarea></td>
        </tr>
        <tr>
            <td>Objective:</td>
            <td><textarea name="objective" required></textarea></td>
        </tr>
        <tr>
            <td>Skills:</td>
            <td><textarea name="skills" placeholder="Separate with commas (e.g. HTML, CSS, PHP)" required></textarea></td>
        </tr>
        <tr>
            <td>Education:</td>
            <td><textarea name="education" placeholder="Include degree, institution, year" required></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="Save Resume"></td>
        </tr>
    </table>
</form>

</body>
</html>
