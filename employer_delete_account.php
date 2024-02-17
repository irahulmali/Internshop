<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['eid'])){
        header("Location: index.php");
    }

    if ($_SESSION['status'] == 'unverified') {
        header("Location: company_profile.php");
    }   

    $eid = $_SESSION['eid'];
    $query= "SELECT emp_email, emp_fname, emp_lname FROM employer_info WHERE eid=$eid";
        
    $result = mysqli_query($conn,$query);
    
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $email = $row['emp_email'];
        $name = $row['emp_fname']." ".$row['emp_lname'];
    }
    
?>

<!--Student Delete Account-->

<html>
    <head>
        <title>Internshop | Student Delete Account</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/delete_account_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        <?php
            if(isset($_POST['delete']) && !empty($_POST['delete'])){

                // Creating all variables from POST data.
                foreach($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);
                }
                    
                $query1 = "DELETE FROM employer_info WHERE emp_email='$email' AND emp_pass='$pass'";
                $result1 = mysqli_query($conn,$query1);
                
                if(mysqli_affected_rows($conn) > 0){
                    session_destroy();
                    $msg = "<script>
                            showNotify('Account deleted successfully.',3);
                            setTimeout(function(){
                                location.replace('index.php');  
                            },1000);                                
                        </script>";
                }else{
                    $msg = "<script>
                            showNotify('Invalid password. Please check again.',1);                               
                        </script>";
                }
                echo $msg;
            }
        ?>
        <!--main-->
        <main>
            <div class="delete-account-container">
                <h2>Delete my account</h2>
                <form method="POST">
                    <div class="delete-account-text">
                        <p class="messege">
                            Hello <?php if(!empty($name)){ echo $name;} ?>, we’re sorry to see you go.
                        </p>
                        <p class="messege">
                            Please note that deleting your account is irreversible and all the data associated with your
                            <b><?php if(isset($email)){echo $email;} ?></b> account (including access to trainings) will be permanently deleted.      
                        </p>
                    </div>
                    <div class="delete-account">
                        <div class="label-input">
                            <p class="messege-bold">
                                Before you leave, please tell us why you'd like to delete your Internshop account.
                                This information will help us improve.
                            </p>
                            <textarea placeholder="Your feedback matters. Max 500 characters." class="delete-account-textarea" name="feedback" style="resize: none;"><?php if(isset($_POST['feedback'])){echo $_POST['feedback'];} ?></textarea>
                        </div>
                        <div class="label-input">
                            <label>Password</label><br>
                            <input class="delete-account-input" type="password" placeholder="Confirm password to delete account." name="pass" required>
                        </div>
                        <div class="label-input">
                            <input class="delete-account-sub-btn" type="submit" value="Bye! Bye! Internshop" name="delete" onclick='confirmDelete(this)'>
                        </div>
                    </div>
                </form>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
        <script type="text/javascript">
            function confirmDelete(){
                var response = confirm("Do you really want to delete your account?");

                if (response) {
                    // Do nothing when "Yes" is clicked.
                }else{
                    this.event.preventDefault();
                }
            }
        </script>
    </body>
</html>