<?php
    session_start();
    include('conn.php');
    if(!isset($_SESSION['eid'])){
        header("Location: index.php");
    }else{
        $eid = $_SESSION['eid'];
    }

?>

<!--Employer Internship Form-->
<html>
    <head>
        <title>Internshop |  Internship</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/employer_internship_form_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
    
        <!--header-->
        <header>
            <?php include("nav.php"); ?>

        </header>
        <?php
            if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])) {
                $ip_id = mysqli_real_escape_string($conn, $_GET['ip_id']);

                

                $query = "SELECT * FROM internship_details WHERE ip_id=$ip_id AND eid=$eid";
                $result = mysqli_query($conn,$query);

                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);

                    foreach ($data as $key => $value){
                        $$key = mysqli_real_escape_string($conn,$value);
                    }
                }else{
                    $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
                }
            }else{
                $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
            }

            echo $msg;
        ?>
        <!--main-->
        <main>
            <div class="emp-internship-container">
                <h1>View Internship</h1>
                    <form method="POST" action="">
                        <h3>Internship details</h3>
                        <div class="internship-form">
                            <div class="label-input">
                                <label>Category</label><br>
                                <select name="category" class="emp-internship-form-input" disabled>
                                  <option value="Information Technology">Information Technology</option>
                                  <option value="Web Development">Web Development</option>
                                  <option value="Cyber Security">Cyber Security</option>
                                  <option value="Human Resource">Human Resource</option>
                                </select>
                            </div>
                            <div class="label-input">
                                <label>Internship profile</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Android App Development" name="profile" required value="<?php echo $profile;?>" disabled>
                            </div>
                            <div class="label-input">
                                <label>Skills requird</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Java, Html, Php"name="skills" required value="<?php echo $skills;?>" disabled>
                            </div>
                            <div class="label-input">
                                <label>Location</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Mumbai, Delhi, Remote" name="location" required value="<?php echo $location;?>" disabled>
                            </div>
                            <div class="label-input">
                                <label>Number of openings</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. 4 (Max 99)" name="openings" required value="<?php echo $openings;?>" disabled>
                            </div>
                            <div class="label-input">
                                <label>Internship start date</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="start_date" required value="<?php echo $start_date;?>" disabled>
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Apply by</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="apply_by" required value="<?php echo $apply_by;?>" disabled>
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Internship duration</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="Enter number of months" name="duration" required value="<?php echo $duration;?>" disabled>
                            </div>
                            <div class="label-input">
                                <label>Intern's day-to-day responsibilities include</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="1." name="role1" required value="<?php echo $role1;?>" disabled><br>
                                <input class="emp-internship-form-input" type="text" placeholder="2." name="role2" value="<?php echo $role2;?>" disabled><br>
                                <input class="emp-internship-form-input" type="text" placeholder="3." name="role3" value="<?php echo $role3;?>" disabled><br>
                                <input class="emp-internship-form-input" type="text" placeholder="4." name="role4" value="<?php echo $role4;?>" disabled><br>
                                <input class="emp-internship-form-input" type="text" placeholder="5." name="role5" value="<?php echo $role5;?>" disabled><br>
                            </div>
                        </div>
                        <h3 class="s-p">Stipend & perks</h3>
                        <div class="internship-form">
                            <div>
                                <label>Stipend</label><br>
                                    <div class="label-input">
                                        <label>₹</label>
                                        <input class="stipent-amount" type="text" placeholder="e.g. 100000 (Min Rs. 5000)" name="stipend" required value="<?php echo $stipend;?>" disabled>
                                        <label>per month</label><br>
                                        <div class="label-input">
                                            <label>Perks (Optional)</label>
                                            <div class="emp-check-box">
                                                <div class="emp-int-radio-btn1"> 
                                                    <input type="checkbox" name="perk1" value="Certificate of completion." checked><label> Certificate of completion.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk2" value="Letter of Recommendation." <?php if(isset($perk2)){echo "checked";}?>><label> Letter of Recommendation.</label>
                                                </div>
                                                <div class="emp-int-radio-btn1">
                                                    <input type="checkbox" name="perk3" value="Flexible work hours." <?php if(isset($perk3)){echo "checked";}?>><label> Flexible work hours.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk4" value="5 days a week." <?php if(isset($perk4)){echo "checked";}?>><label> 5 days a week.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <h3 class="s-p">Cover letter, availability & assignment question</h3>
                        <div class="internship-form">
                            <div class="cover-letter-text">
                                <p class="cover-letter-p">
                                    Cover letter to be asked to applicant by default. If you wish,
                                    you may ask upto three assessment questions. 
                                </p><br>
                            </div>
                            <input class="emp-internship-form-input" type="text" placeholder="1." name="q1"  value="Why should be you hired for this role?" readonly required disabled><br>
                            <input class="emp-internship-form-input" type="text" placeholder="1." name="q2" value="<?php echo $q2;?>" disabled><br>
                            <input class="emp-internship-form-input" type="text" placeholder="2." name="q3" value="<?php echo $q3;?>" disabled><br>
                            <input class="emp-internship-form-input" type="text" placeholder="3." name="q4" value="<?php echo $q4;?>" disabled><br>
                        </div>
                    </form>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php") ?>
        </footer>
    </body>
</html>