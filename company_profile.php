<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['eid'])){
        header("Location: index.php");
    }else{
        $eid = $_SESSION['eid'];
        $query= "SELECT emp_fname, emp_lname, emp_email, emp_phone, com_name, com_link, com_about, com_dp_path, status FROM employer_info WHERE eid=$eid";
            
        $result = mysqli_query($conn,$query);
        
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $oldfname = $row['emp_fname'];
            $oldlname = $row['emp_lname'];
            $oldemail = $row['emp_email'];
            $oldphone = $row['emp_phone'];
            $oldcomname = $row['com_name'];
            $oldcomlink = $row['com_link'];
            $oldcomabout = $row['com_about'];
            $oldDP = $row['com_dp_path'];
            $status = $row['status'];

            $compDPName = strtolower($eid.$oldfname.$oldlname);
        }
    }
?>

<!--Company Profile-->

<html>
    <head>
        <title>Internshop | Company profile</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/company_profile_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php 
                include("nav.php");
                if ($_SESSION['status'] == 'unverified') {
                    echo"<script>
                            showNotify('Complete your company profile to access other features.',3);                               
                        </script>";
                }   
            ?>
        </header>
        
        <?php
            if(isset($_POST['save']) && !empty($_POST['save'])){
                
                $stringRegex = "/[a-zA-Z\'\",?@\. ]*/";
                $nameRegex = "/^[A-Za-z]{2,30}$/";
                $phoneRegex = "/^[789][0-9]{9}$/";
                
                // Creating variables with respective names.
                foreach ($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);
                }
                
                // All validations will come here.

                if(!preg_match($nameRegex, $fname)) {
                    $msg = "<script>showNotify('Invalid first name.',1);</script>";
                }else if(!preg_match($nameRegex, $lname)) {
                    $msg = "<script>showNotify('Invalid last name.',1);</script>";
                }else if(!preg_match($phoneRegex, $phone)) {
                    $msg = "<script>showNotify('Invalid phone number.',1);</script>";
                }else if(!preg_match($stringRegex, $com_name) || strlen($com_name)>50 || strlen($com_name)<0 ) {
                    $msg = "<script>showNotify('Invalid Company name.',1);</script>";
                }else if(!preg_match($stringRegex, $com_link)) {
                    $msg = "<script>showNotify('Invalid website.',1);</script>";
                }else if(!preg_match($stringRegex, $com_about) || strlen($com_about)>500 || strlen($com_about)<0) {
                    $msg = "<script>showNotify('Invalid about your company.',1);</script>";
                }else{
                    
                    // Query to insert all data.
                    $query = "UPDATE employer_info SET com_name='$com_name', com_link='$com_link', com_about='$com_about', emp_fname='$fname', emp_lname='$lname', emp_phone='$phone', status='verified' WHERE eid=$eid";
                    $result = mysqli_query($conn, $query);
                    
                    //echo $result;
                    
                    if($result){
                        $_SESSION['status'] = 'verified';
                        $msg = "<script>
                                    showNotify('Profile saved successfully.',3);
                                    setTimeout(function(){
                                        location.replace('company_profile.php');  
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
                    $targetDir = "uploads/com/";
                    $baseFileName = basename($_FILES['dpFile']['name']);
                    $fileType = strtolower(pathinfo($baseFileName,PATHINFO_EXTENSION));
                    $fileSize = $_FILES['dpFile']['size'];
                    $allowedFileTypes = array('jpg', 'jpeg', 'png');
                    
                    //echo $fileType;

                    // Filename should be customized for every student.
                    $fileName = $compDPName.time();
                    $targetFilePath = $targetDir.$fileName.'.'.$fileType;
                    
                    if ($fileSize > 1153433){
                        $msg = "<script>showNotify('File size should be less than 1 MB.',1);</script>";
                    }else if(!in_array($fileType, $allowedFileTypes)){
                        $msg = "<script>showNotify('Upload company logo only in JPG, JPEG or PNG format.',1);</script>";
                    }else{
                        $query = "UPDATE employer_info SET com_dp_path='$targetFilePath' WHERE eid=$eid";
                        if (mysqli_query($conn, $query)) {
                            if(move_uploaded_file($_FILES['dpFile']['tmp_name'], $targetFilePath)){
                                unlink($oldDP);    // Deleting previous image file.
                                $_SESSION['dp_path'] = $targetFilePath;
                                $msg = "<script>
                                    showNotify('Company logo has been uploaded.',2);
                                    setTimeout(function(){
                                        location.replace('company_profile.php');  
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
            <div class="company-pro-container">
                <div class="title">
                    <h2>Company details</h2>
                </div>
                <form method="POST">
                    <div class="company-container">
                        <div class="dp-div">
                        <img class='dp-img' src='<?php echo $oldDP; ?>' onerror='this.src="uploads/com/blank.png"'>
                        </div>
                        <div class="border1">
                            <label class="company-label">First name</label><br>
                            <input class="company-input" type="text" placeholder="e.g Jack" name="fname" value="<?php if(isset($oldfname)){echo $oldfname;} ?>" required pattern="^[A-Za-z]{2,30}$"><br>
                            <label class="company-label">Company name</label><br>
                            <input class="company-input" type="text" placeholder="e.g Codeplex Pvt Ltd." name="com_name" value="<?php if(isset($oldcomname)){echo $oldcomname;} ?>" required ><br>
                            <label class="company-label">Email</label><br>
                            <input class="company-input" type="email" placeholder="e.g company_name@gmail.com" name="email" value="<?php if(isset($oldemail)){echo $oldemail;} ?>" readonly disabled><br>
                        </div>
                        <div class="border2">
                            <label class="company-label">Last name</label><br>
                            <input class="company-input" type="text" placeholder="e.g Doe" name="lname" value="<?php if(isset($oldlname)){echo $oldlname;} ?>" required pattern="^[A-Za-z]{2,30}$"><br>
                            <label class="company-label">Company website</label><br>
                            <input class="company-input" type="text" placeholder="https://www.compnay.com" name="com_link" value="<?php if(isset($oldcomlink)){echo $oldcomlink;} ?>"><br>
                            <label class="company-label">Phone No.</label><br>
                            <input class="company-input" type="text" placeholder="10-digit mobile number" name="phone" value="<?php if(isset($oldphone)){echo $oldphone;} ?>" required pattern="^[789][0-9]{9}$"><br>
                        </div>
                        <div class="border3">
                            <label class="company-label">About Company</label><br>
                            <textarea class="company-textarea" name="com_about" style="resize: none" placeholder="Max 300 characters." required><?php if(isset($oldcomabout)){echo $oldcomabout;} ?></textarea><br>
                        </div>
                        
                        <div class="edit-btn">
                            <input class="edit" type="submit" value="Save" name="save">
                        </div>
                </form>
                        <div class="dp-form-div">
                            <form method="POST" enctype="multipart/form-data">
                                <input class="choose-btn" type="file" name="dpFile">    
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