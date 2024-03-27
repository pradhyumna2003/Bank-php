<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ABC</title>
</head>
<body>
<?php
// define variables and set to empty values
$idErr = $pwdErr= "";
$id = $pwd = "";
$loginErr=0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["id"])) {
    $idErr = "ID is required";
  } else {
    $id = test_input($_POST["id"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[0-9' ]*$/",$id)) {
      $idErr = "Only Numbers";
    }
  }
  
  if (empty($_POST["pwd"])) {
    $pwdErr = "Password is required";
  } else {
    $pwd= test_input($_POST["pwd"]);
    // check if e-mail address is well-formed
    if (!preg_match("/^[0-9a-zA-Z-' ]*$/",$pwd)) {
      $pwdErr = "Password wrong";
    }
  }
  if($idErr=="" && $pwdErr==""){
    $loginErr=login($id,$pwd);
  }else{
    $loginErr=1;
  }
  
    
}
else{
  $loginErr=1;
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function login($id,$pwd){
  $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bankDB";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // echo "Connected successfully";
    $sql = "SELECT id,ammount FROM users WHERE id=$id  and pwd='$pwd'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "Id: " . $row["id"]. " - Ammount : " . $row["ammount"] ;
      }
      $conn->close();
      return 0;
    } else {
      $conn->close();
      return 1;
    }
    
}
?>

<?php
  if($loginErr==1){
    echo "<h1>Error</h1>
    <form action=\"login.php\" method=\"get\">
      Login Back:<input type=\"submit\" value=\"Login\">
    </form>";
  }else{
    echo "<script>alert('login success')</script>";
    
    echo "<h1>Login Successfull!</h1>
    <form method=\"get\" action=\"login.php\">
  <input type=\"submit\" value=\"Logout!\">
</form>";
  }
?>

<?php 
  if($loginErr==0){
    echo '<h1>Transaction:</h1>
<form action="transaction.php" method="post">
From:<input type="number" name="idf" value="'. $id . '" columns=20 readonly>
To:<input type="number" name="idto" columns=20>
Ammount<input type="number" name="ammount" id="ammount" columns=20>
<input type="submit" value="send">';
  }

?>

<?php

?>


</body>
</html>