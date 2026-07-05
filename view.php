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


// echo "<b>Session regid:</b> " . htmlspecialchars($regid) . "<br>";


$stmt = $conn->prepare("SELECT * FROM resumes WHERE regid = ?");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $regid);
$stmt->execute();

$result = $stmt->get_result();

//echo "<b>Rows found:</b> " . $result->num_rows . "<br><br>";

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";

} else {
    echo "<h3>No resume found for this Register ID.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Resume</title>
    <style>

*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;

}

body{

background:#e2e8f0;
padding:50px;

}

.resume-container{

max-width:850px;
margin:auto;
background:white;
padding:50px;
border-radius:15px;
box-shadow:0 15px 40px rgba(0,0,0,.15);

}

h1{

text-align:center;
color:#1e293b;
margin-bottom:10px;

}

.contact{

text-align:center;
margin-bottom:30px;
color:#475569;

}

.section{

margin-top:30px;

}

.section h3{

border-bottom:2px solid #2563eb;
padding-bottom:8px;
margin-bottom:15px;
color:#2563eb;

}

.section p{

line-height:1.8;

}

</style>
</head>

<body>

<div class="resume-container">

<h2><?php echo htmlspecialchars($data['fullname']); ?></h2>

<p><span class="label">Email:</span>
<?php echo htmlspecialchars($data['email']); ?>
</p>

<p><span class="label">Phone:</span>
<?php echo htmlspecialchars($data['phone']); ?>
</p>

<p><span class="label">Address:</span>
<?php echo nl2br(htmlspecialchars($data['address'])); ?>
</p>

<h3>Objective</h3>
<p><?php echo nl2br(htmlspecialchars($data['objective'])); ?></p>

<h3>Skills</h3>
<p><?php echo nl2br(htmlspecialchars($data['skills'])); ?></p>

<h3>Education</h3>
<p><?php echo nl2br(htmlspecialchars($data['education'])); ?></p>

</div>

</body>
</html>