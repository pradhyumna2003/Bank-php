<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ABC</title>
</head>
<body>
    <h1>Transaction</h1>
    <?php
    $error=0;
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bankDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        error();
        die("Connection failed: " . mysqli_connect_error());
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $from=test_input($_POST['idf']);
        $to=test_input($_POST['idto']);
        $amt=test_input($_POST['ammount']);
        if(chkFrom($from,$amt,$conn) && chkTo($to,$conn) && $from!=$to){
            if(transact($from,$to,$amt,$conn)){
                echo '<script>alert("Transaction Successfull!");</script>';
                echo "<div class='box'><h1>Transaction Successfull!</h1>
                    <form method=\"get\" action=\"login.php\">
                    <input type=\"submit\" value=\"Logout!\">
                    </form></div>";
            }
            else{
                error();
            }
        }
        else{
            error();
        }
    }
    else{
       error();
    }
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    ?>
    <?php 
    function transact($from,$to,$amt,$conn){
        $sql= "UPDATE users SET ammount=ammount+$amt WHERE id=$to;";

        if ($conn->query($sql) === TRUE) {
        // echo "Record updated successfully";
            // return true;
        } else {
        // echo "Error updating record: " . $conn->error;
            return false;
        }
        $sql= "UPDATE users SET ammount=ammount-$amt WHERE id=$from ;";
        if ($conn->query($sql) === TRUE) {
            // echo "Record updated successfully";
                // return true;
        } else {
        // echo "Error updating record: " . $conn->error;
            return false;
        }
        return true;
    }
    function chkTo($to,$conn){
        $sql = "SELECT id,ammount FROM users WHERE id=$to ;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
            return true;
        }
        return false;
    }
    function chkFrom($from,$amt,$conn){
        $sql = "SELECT id,ammount FROM users WHERE id=$from ;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
       
            while($row = $result->fetch_assoc()) {
                
                if($row['ammount']-$amt>50){
                    return true;
                }
            }
        }
        return false;
        
        // $conn->close();
    }
    ?>
    <?php function error(){
        echo "<div class='box'><h1>Error</h1>
        <form action=\"login.php\" method=\"get\">
          Login Back:<input type=\"submit\" value=\"Login\">
        </form></div>";
    }
    ?>
</body>
</html>