<?php
	session_start();
	if(isset($_POST['edit'])){
	$dbhost='localhost';
	$dbuser='root';
	$dbpwd='';
	$dbname='Laba2';
	$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
	if($con){
		$id;
		if (isset($_SESSION['edit_id'])) {
			$id = mysqli_real_escape_string($con,trim($_SESSION['edit_id']));
		}else
			$id = mysqli_real_escape_string($con,trim($_SESSION['id']));
		$sql = "SELECT * FROM users WHERE id='$id'";
		$result = mysqli_query($con,$sql);
		$row=mysqli_fetch_assoc($result);

		$new_login=$_POST['login'];
		$fname=$_POST['first_name'];
		$lname=$_POST['last_name'];
		$password=$_POST['password'];
		$name_img;
		$role=$row['role'];
		if (isset($_POST['role']) && (strlen($_POST['role'])!=0)) {
			$role=$_POST['role'];
		}

		$user_contains=FALSE;
		if (strlen($new_login)==0) {
			$new_login=$row['login'];
		}elseif (strcasecmp($new_login, $row['login'])) {
			$sql="SELECT * FROM users";
			$datab=mysqli_query($con,$sql);
			while($row1=mysqli_fetch_assoc($datab)){
				if (!strcasecmp($new_login, $row1['login'])) {
    						$user_contains=TRUE;
    						$new_login=$row['login'];
					}
				}
		}
		if (strlen($fname)==0) {
			$fname=$row['fname'];
		}
		if (strlen($lname)==0) {
			$lname=$row['lname'];
		}
		if (strlen($password)==0) {
			$password=$row['pwd'];
		}

		if ($_FILES && $_FILES['image']['error']== UPLOAD_ERR_OK){
        		$name_img = 'imgs/'.$id.'.jpeg';
        		move_uploaded_file($_FILES['image']['tmp_name'], $name_img);
        }else
        	$name_img=$row['img'];


		$sql = "UPDATE users SET login='$new_login', fname='$fname', lname='$lname', pwd='$password', img='$name_img',role='$role' WHERE id='$id'";
		if(!mysqli_query($con,$sql))
		echo "Error*";
		mysqli_close($con);
		if ($user_contains) {
			echo "User with such login already exists!";
		}
		echo "Data has already changed!";
	}
}
?>
<html>
<head>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
    $(document).ready(function(){
        $("#data").validate({
            submitHandler: function(form){
                var form = document.forms[0],
                    formData = new FormData(form),
                    xhr = new XMLHttpRequest();
                formData.append('model','user');
                formData.append('action','edit');
                console.log(document.forms);
                xhr.open("POST", "Route.php");
                xhr.responseType = 'text';
                 xhr.onreadystatechange = function() {
                     if (xhr.readyState == 4) {
                         if(xhr.status == 200) {
                             if (xhr.responseText)
                            	 alert(xhr.responseText);
                         	else
                         		location.href='index.php';
                         }
                     }
                 };
                xhr.send(formData);
            }
        });
    })
	</script>
	<title></title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<a href="index.php">On main page</a>
	<?php
	$dbhost='localhost';
	$dbuser='root';
	$dbpwd='';
	$dbname='Laba2';
	$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
	if($con){
		$id;
		if (isset($_SESSION['edit_id'])) {
			$id = mysqli_real_escape_string($con,trim($_SESSION['edit_id']));
		}else
			$id = mysqli_real_escape_string($con,trim($_SESSION['id']));
		$sql = "SELECT * FROM users WHERE id='$id'";
		$result = mysqli_query($con,$sql);
		$row=mysqli_fetch_assoc($result);
			echo '<center>
			<div class="cont3" width="300" height="500">
			<form id="data" action="change_info.php" method="POST" enctype="multipart/form-data">
			<p>
				<img width="200" height="100" src="'.$row["img"].'">
			</p>
			<p>
				<input type="file" name="image">
			</p>
			<p>
			login
			<input type="text" name="login" value="'.$row["login"].'">
			</p>
			<p>
			first name
			<input type="text" name="first_name" value="'.$row["fname"].'">
			</p>
			<p>
			last name
			<input type="text" name="last_name" value="'.$row["lname"].'">
			</p>
			<p>
			change password
			<input type="password" name="password">
			</p>';
			if (isset($_SESSION['role']) && !strcasecmp($_SESSION['role'], "admin")) {
				echo "<p>
					change role
					<input type='text' name='role' value='".$row["role"]."'>
					</p>";
			}
			echo '<p>
			<input type="submit" value="Edit profile" name="edit">
			</p>
			</form>
			</div>
			</center>';
			mysqli_close($con);
	}
?>
</body>
