<?php
    session_start();
    include("conn.php");

    if(!isset($_SESSION['sid'])){
        header('Location: index.php');
    }
    
?>

<!--Student Dashboard-->
<html>
    <head>
        <title>Internshop | Student Dashboard</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/student_dashboard_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
    
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        <?php
            if(isset($_GET['rem_app_ip_id']) && !empty($_GET['rem_app_ip_id'])){
                $rem_app_ip_id = mysqli_real_escape_string($conn,$_GET['rem_app_ip_id']);
                $sid = $_SESSION['sid'];

                $query1 = "DELETE FROM applications WHERE sid=$sid AND ip_id=$rem_app_ip_id";   
                $result1 = mysqli_query($conn,$query1);

                if ($result1) {
                    $msg = "<script>showNotify('Your application has been deleted',3);
                                setTimeout(function(){
                                    location.replace('student_dashboard.php'); 
                                },2000);                                
                            </script>"; 
                }else{
                    $msg = "<script>showNotify('Failed to delete bookmark.',1);</script>";
                } 
                echo $msg;
            }
        ?>
        <?php
            $sid = $_SESSION['sid'];
            $applications = array();

            $query = "SELECT * FROM applications WHERE sid=$sid";
            $result = mysqli_query($conn,$query);
            $count = mysqli_num_rows($result);
            
            if (mysqli_num_rows($result) > 0) {
                while ($in = mysqli_fetch_assoc($result)) {
                    $applications[] = $in['ip_id'];     
                }
            }

        ?>
        <!--main-->
        <main>
            <div class="dash-container">
                <div class="dash-content">
                    <p>My applications</p>
                </div>
                <?php
                    if ($count > 0){
                        echo'
                            <div class="dashboard-table">
                                <table>
                                    <tr class="table-head">
                                        <th>Applied On</th>
                                        <th>Profile</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                        <th class="th-msg">Message</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                    ';
                
                        foreach ($applications as $ip_id) {
                            
                            $query = "SELECT id.ip_id, id.eid, id.profile, id.location, id.start_date, a.applied_on, a.status, e.com_name FROM internship_details id, applications a, employer_info e WHERE id.ip_id=a.ip_id AND e.eid=a.eid AND a.ip_id=$ip_id";

                            $result = mysqli_query($conn,$query);

                            if (mysqli_num_rows($result) > 0) {
                                $internship = mysqli_fetch_assoc($result);
                                    //var_dump($internship);
                                    $status = $internship['status'];
                                    switch ($status) {
                                        case 'New':
                                            $status = "Applied";
                                            $message = "Your application is under process.";
                                            break;
                                        case 'Shortlisted':
                                            $message = 'Your application has been shortlisted. Employer will get in touch with on your email.';
                                            break;
                                        case 'Hired':
                                            $message = 'Congratulations!!! You have been hired for this internship. All the best for your future.';
                                            break;
                                        case 'Rejected':
                                            $status = 'Not selected';
                                            $message = 'Your application didn\'t meet the employer requirements. Apply to more internships.';
                                            break;
                                    }

                                    echo "
                                        <tr class='table-data'>
                                            <td class='p-data'>".$internship['applied_on']."</td>
                                            <td class='p-data'>".$internship['profile']."</td>
                                            <td class='p-data'>".$internship['com_name']."</td>
                                            <td class='p-data'>".$status."</td>
                                            <td class='p-data'>".$message."</td>
                                            <td>
                                                <a href='review_application.php?ip_id=".$internship['ip_id']."&sid=".$sid."' target='_blank'>
                                                    <img class='btnAction' src='images/icons/cv.png' title='Show application.'>
                                                </a>
                                            </td>
                                            <td>
                                                <a href='student_dashboard.php?rem_app_ip_id=".$internship['ip_id']."' onclick='confirmDelete()'>
                                                    <img src='images/icons/trash.png' height='40px' width='40px' title='Delete Application'>
                                                </a>
                                            </td>
                                        </tr>
                                    ";
        
                            }
                        }
                        echo "</table></div>";
                    }else{
                        echo "<img class='err-img' src='images/dash_img1.png'>";
                    }
                ?>
                
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>