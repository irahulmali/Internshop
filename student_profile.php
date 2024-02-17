<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['sid'])){
        header("Location: index.php");
    }else{
        $sid = $_SESSION['sid'];
        $query= "SELECT stu_fname, stu_lname, gender, city, stu_email, stu_phone, dp_path FROM student_info WHERE sid=$sid";
            
        $result = mysqli_query($conn,$query);
        
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $oldfname = $row['stu_fname'];
            $oldlname = $row['stu_lname'];
            $oldgender = $row['gender'];
            $oldcity = $row['city'];
            $oldemail = $row['stu_email'];
            $oldphone = $row['stu_phone'];
            $oldDP = $row['dp_path'];

            $studName = strtolower($sid.$oldfname.$oldlname);
        }
    }
?>

<!--Student Resume-->

<html>
    <head>
        <title>Internshop | Profile</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/student_profile_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        
        <?php
            if(isset($_POST['save']) && !empty($_POST['save'])){
                
                $stringRegex = "/^[A-Za-z]{2,30}$/";
                $phoneRegex = "/^[789][0-9]{9}$/";
                $genderRegex = "/^(Male|Female)$/";

                
                // Creating variables with respective names.
                foreach ($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);
                }
                
                // All validations will come here.


                if(!preg_match($stringRegex, $fname)) {
                    $msg = "<script>showNotify('Invalid first name.',1);</script>";
                }else if(!preg_match($stringRegex, $lname)) {
                    $msg = "<script>showNotify('Invalid last name.',1);</script>";
                }else if(!preg_match($phoneRegex, $phone)) {
                    $msg = "<script>showNotify('Invalid phone number.',1);</script>";
                }else if(!preg_match($genderRegex, $gender)) {
                    $msg = "<script>showNotify('Invalid gender.',1);</script>";
                }else if(!preg_match($stringRegex, $city)) {
                    $msg = "<script>showNotify('Invalid city.',1);</script>";
                }else{
                    
                    // Query to insert all data.
                    $query = "UPDATE student_info SET stu_fname='$fname', stu_lname='$lname', stu_phone='$phone', gender='$gender', city='$city' WHERE sid=$sid";
                    $result = mysqli_query($conn, $query);
                    
                    if($result){
                        $msg = "<script>
                                    showNotify('Profile saved successfully.',3);
                                    setTimeout(function(){
                                        location.replace('student_profile.php');  
                                    },2000);
                                </script>";
                    }else{
                        $msg = "<script>showNotify('Failed to save profile.',1);</script>";
                    }
                }
                echo $msg;
            }


            if (isset($_POST['dpUpload']) && !empty($_POST['dpUpload'])) {
                if (isset($_FILES['dpFile']) && !empty($_FILES['dpFile'])){
                    
                    // Setting file upload variables.
                    $targetDir = "uploads/dp/";
                    $baseFileName = basename($_FILES['dpFile']['name']);
                    $fileType = strtolower(pathinfo($baseFileName,PATHINFO_EXTENSION));
                    $fileSize = $_FILES['dpFile']['size'];
                    $allowedFileTypes = array('jpg', 'jpeg', 'png');
                    

                    // Filename should be customized for every student.
                    $fileName = $studName.time();
                    $targetFilePath = $targetDir.$fileName.'.'.$fileType;
                    
                    if ($fileSize > 1153433){
                        $msg = "<script>showNotify('File size should be less than 1 MB.',1);</script>";
                    }else if(!in_array($fileType,$allowedFileTypes)){
                        $msg = "<script>showNotify('Upload profile picture only in JPG or JPEG format.',1);</script>";
                    }else{
                        $query = "UPDATE student_info SET dp_path='$targetFilePath' WHERE sid=$sid";
                        if (mysqli_query($conn, $query)) {
                            if(move_uploaded_file($_FILES['dpFile']['tmp_name'], $targetFilePath)){
                                unlink($oldDP);    // Deleting previous image file.
                                $_SESSION['dp_path'] = $targetFilePath;
                                $msg = "<script>
                                    showNotify('Profile picture has been uploaded.',2);
                                    setTimeout(function(){
                                        location.replace('student_profile.php');  
                                    },2000);                                
                                </script>";
                            }
                        }else{
                            $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
                        } 
                    }
                }else{
                    $msg = "<script>showNotify('Invalid file format or size.',1);</script>";
                }
                echo $msg;
            }            
        ?>
        
        <!--main-->
        <main>
            <div class="profile-container">
                <div class="title">
                    <h2>Personal details</h2>
                </div>
                <form method="POST">
                    <div class="stu-profile-container">
                        <div class="dp-div">
                            <img class='dp-img' src='<?php echo $oldDP;?>' onerror='this.src="uploads/dp/blank.jpg"'>
                        </div>
                        <div class="border1">
                            <label class="profile-label">First name</label><br>
                            <input class="profile-input" type="text" placeholder="Raj" name="fname" value="<?php if(isset($oldfname)){echo $oldfname;} ?>" required pattern="^[A-Za-z]{2,30}$"><br>
                            <label class="profile-label">Email</label><br>
                            <input class="profile-input" type="email" placeholder="e.g rajkumar@gmail.com" name="email" value="<?php if(isset($oldemail)){echo $oldemail;} ?>" readonly disabled><br>
                            <label class="profile-label">Current City</label><br>
                            <input class="profile-input" type="text" placeholder="e.g Mumbai, Pune, Kolkata" name="city" value="<?php if(isset($oldcity)){echo $oldcity;} ?>" required pattern="^[A-Za-z]{2,30}$"><br>
                        </div>
                        <div class="border2">
                            <label class="profile-label">Last name</label><br>
                            <input class="profile-input" type="text" placeholder="Kumar" name="lname" value="<?php if(isset($oldlname)){echo $oldlname;} ?>" required pattern="^[A-Za-z]{2,30}$"><br>
                            <label class="profile-label">Phone No.</label><br>
                            <input class="profile-input" type="text" placeholder="e.g 9874563729" name="phone" value="<?php if(isset($oldphone)){echo $oldphone;} ?>" required pattern="^[789][0-9]{9}$"><br>
                            <label class="profile-label">Gender</label><br>
                                <select class="profile-input" name="gender" required><br>
                                    <option value="Male" <?php if(isset($oldgender) && $oldgender=='Male'){echo "selected";} ?>>Male</option>
                                    <option value="Female" <?php if(isset($oldgender) && $oldgender=='Female'){echo "selected";} ?>>Female</option>
                                </select>
                        </div>
                        <div class="">
                            <input class="edit" type="submit" value="Save" name="save">
                        </div>
                        </form>
                        <div class="dp-form-div">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="file" name="dpFile">    
                                <input type="submit" class="btn-dp" name="dpUpload" value="Upload" >
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
</html>