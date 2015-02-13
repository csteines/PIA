<?php
#Include the connect.php file
include('connect.php');
#Connect to the database
//connection String
$connect = mysql_connect($hostname, $username, $password)
or die('Could not connect: ' . mysql_error());
//Select The database
$bool = mysql_select_db($database, $connect);
if ($bool === False){
   print "can't find $database";
}

if(isset($_POST['f'])){$f = $_POST['f'];}

else{
    // get data and store in a json array
    $query = "SELECT * FROM items WHERE `PID` = '1' ORDER BY `number` DESC";

    // SELECT COMMAND
	$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$members[] = array(
                        'ID' => $row['ID'],
			'number' => $row['number'],
			'brand' => $row['brand'],
			'model' => $row['model'],
			'description' => $row['description'],
			'qty' => $row['qty'],
			'cost' => $row['cost'],
			'src' => $row['src']
		  );
	}
	 
	echo json_encode($members);
}

if ($f == "add")
{
    //GET all variable values
    $PID = $_POST['PID'];
    $number = $_POST['number'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $description = $_POST['description'];
    $qty = $_POST['qty'];
    $cost = $_POST['cost'];
    $src = $_POST['src'];
    
    // INSERT COMMAND 
    $insert_query = "INSERT INTO `items`(`PID`, `number`, `brand`, `model`, `description`, `qty`, `cost`, `src`) VALUES ('".$PID."','".$number."','".$brand."','".$model."','".$description."','".$qty."','".$cost."','".$src."')";
	
   $result = mysql_query($insert_query) or die("SQL Error 1: " . mysql_error());
   //$result = $PID ." ".$number." ".$brand." ".$model." ".$description." ".$qty." ".$cost." ".$src;
    echo $result;
}
else if ($f == "update")
{
    $column = $_POST['column'];
    $value = $_POST['value'];
    $id = $_POST['id'];

    // UPDATE COMMAND 
    $update_query = "UPDATE `items` SET `".$column."`='".$value."' WHERE `ID`='".$id."'";
     $result = mysql_query($update_query) or die("SQL Error 1: " . mysql_error());
     echo $result;
}
//else if (isset($_GET['delete']))
//{
//	// DELETE COMMAND 
//	$delete_query = "DELETE FROM `members` WHERE `idnumber`='".$_GET['idnumber']."'";	
//	$result = mysql_query($delete_query) or die("SQL Error 1: " . mysql_error());
//    echo $result;
//}
//else
//{
?>