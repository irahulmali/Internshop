<?php 
	session_start();
	include("conn.php");

	if(isset($_SESSION['sid'])){
		header('Location: student_dashboard.php');
	}else if(isset($_SESSION['eid'])){
		header('Location: employer_dashboard.php');
	}
	
?>

<!--Student and Employer Login Form-->

<html>
	<head>
		<title>Internshop | Employer Login</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/login_form_style.css">
		<script type="text/javascript" src="script/errorMessage.js"></script>
	</head>
	<body>
		<!--header-->
		<header>
			<?php include("nav.php"); ?>
		</header>
		<?php 
			
			$emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$/";
			$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";

			if(isset($_POST['login']) && !empty($_POST['login'])){

				foreach($_POST as $key => $value){
		            $$key = mysqli_real_escape_string($conn,$value);
		        }

		        if (preg_match($emailRegex, $email) && preg_match($passRegex, $pass)){
					// Checking if credentials match.
					$query= "SELECT * FROM employer_info WHERE emp_email='$email' AND emp_pass='$pass'";
					$result = mysqli_query($conn, $query);
					$num = mysqli_num_rows($result);
					if($num > 0){
						$row = mysqli_fetch_assoc($result); 						
						$_SESSION['eid'] = $row['eid'];	
						$_SESSION['N'] = strtoupper(substr($row['emp_fname'], 0, 1));
						$_SESSION['status'] = $row['status'];
						$_SESSION['dp_path'] = $row['com_dp_path']; 
						if ($_SESSION['status'] == 'unverified') {
							echo"<script>
									showNotify('Login successful. Please update your company profile.',2);
									setTimeout(function(){
										location.replace('company_profile.php');	
									},1000);								
								</script>";
						}else if($_SESSION['status'] == 'verified'){
							echo"<script>
									showNotify('Login successful. Redirecting to dashboard. . . ',2);
									setTimeout(function(){
										location.replace('employer_dashboard.php');	
									},1000);								
								</script>";
						}			
						
					}else{
						$msg = "<script>showNotify('Invalid email or password.',1);</script>";	
					}	
					echo $msg;
				}
			}					
		?>
		<!--login Form-->
		<main>
			<div class="login-form-background">
				<div class="login-form-overly">
					<div class="login-form-style">
						<form action="" method="POST">
							<div class="login-form">
								<div class="login-form-text">
									<h1>Employer Login</h1>
								</div>
								<div class="login-form-col">
									<label for="login-email" class="login-form-label">Official Email Id</label><br>
									<input class="login-form-input" id="login-email" type="email" placeholder="e.g. john@gmail.com" name="email" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" required><br>
								</div>
								<div class="login-form-col">
									<label for="login-pass" class="login-form-label">Password</label><br>
									<input class="login-form-input" id="login-pass" type="password" placeholder="Must be atleast 6 characters" name="pass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" value="<?php if(isset($_POST['email'])){echo $_POST['pass'];}?>" required><br>
								</div>
								<div class="login-form-col">
									<a href="#">Forgot password?</a><br>
								</div>
								<div class="login-sub-btn-hover">
									<input class="login-form-sub-btn" type="submit" value="Login" name="login">
								</div>
								<div class="new-to-internshop">
									<p>New to Internshop? Register (<a href="student_register.php">Student</a> / <a href="employer_register.php">Company</a>)</p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</main>
		<!--footer-->
		<footer>
			<?php include("footer.php"); ?>
		</footer>
	</body>
	<script src="script/script.js" ></script>
</html>