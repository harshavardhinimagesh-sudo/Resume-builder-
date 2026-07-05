<?php
session_start();

if (!isset($_SESSION['regid'])) {
    header("Location: login.php");
    exit;
}

$regid = $_SESSION['regid'];

$host = "localhost";
$user = "root";
$password = "";
$database = "resumebuilder";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $objective = trim($_POST['objective']);
    $skills = trim($_POST['skills']);
    $education = trim($_POST['education']);

    $stmt = $conn->prepare("UPDATE resumes SET fullname=?, email=?, phone=?, address=?, objective=?, skills=?, education=? WHERE regid=?");
    $stmt->bind_param(
        "ssssssss",
        $fullname,
        $email,
        $phone,
        $address,
        $objective,
        $skills,
        $education,
        $regid
    );

    if ($stmt->execute()) {
        $success = "Resume updated successfully!";
    } else {
        echo "Error updating resume: " . $stmt->error;
    }

    $stmt->close();
}


$stmt = $conn->prepare("SELECT * FROM resumes WHERE regid=?");
$stmt->bind_param("s", $regid);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $data = $result->fetch_assoc();
} else {
    echo "No resume found. <a href='create.php'>Create one here</a>";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Resume</title>

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
input[type="submit"]{
    background-color: #1a1c20;
}

</style>
</head>

<body>

<h2>Edit Your Resume</h2>

<?php
if($success!=""){
    echo "<p class='success'>$success</p>";
}
?>

<form method="POST">

<table>

<tr>
<td>Full Name</td>
<td>
<input
type="text"
name="fullname"
value="<?php echo htmlspecialchars($data['fullname']); ?>"
required>
</td>
</tr>

<tr>
<td>Email</td>
<td>
<input
type="email"
name="email"
value="<?php echo htmlspecialchars($data['email']); ?>"
required>
</td>
</tr>

<tr>
<td>Phone</td>
<td>
<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($data['phone']); ?>"
required>
</td>
</tr>

<tr>
<td>Address</td>
<td>
<textarea name="address" required><?php echo htmlspecialchars($data['address']); ?></textarea>
</td>
</tr>

<tr>
<td>Objective</td>
<td>
<textarea name="objective" required><?php echo htmlspecialchars($data['objective']); ?></textarea>
</td>
</tr>

<tr>
<td>Skills</td>
<td>
<textarea name="skills" required><?php echo htmlspecialchars($data['skills']); ?></textarea>
</td>
</tr>

<tr>
<td>Education</td>
<td>
<textarea name="education" required><?php echo htmlspecialchars($data['education']); ?></textarea>
</td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" value="Update Resume" >
</td>
</tr>

</table>

</form>

</body>
</html>