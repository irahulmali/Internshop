<?php
    session_start();
    include("conn.php");
    
    if(isset($_SESSION['eid'])){
        header('Location: employer_dashboard.php');
    }
?>

<!--Main page-->

<html>
    <head>
        <title>Internshop</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        
        <!--main-->
        <main>
            <div class="index-search-bar">
                <form action="internships.php" method="GET">
                    <input class="search" type="text" name="keyword" Placeholder="What are you looking for? e.g Design, Mumbai, Infosys" >
                    <input class="search-btn" type="submit" value="&#128269;">                    
                </form>
            </div>
            <div class="slider">
                <div class="imgslider">
                    
                </div>
            </div>
            
            <div class="content">
                <h2>Internships</h2>
                <p>Apply to 10,000+ internships for free</p>
				<div class="view-all-int" >
					<a href="internships.php">View all internships<b>&ShortRightArrow;</b></a>
				</div>
            </div>
            
			<!--Popular Cities Images-->
			<div class="img-div">
				<h2 class="img-title">Popular cities</h2>
				
				<div class="images">
					<div class="box">
						<a href="internships.php?location=Remote">
							<img src="images/1wfh.png">
							<p>Work form home<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?location=Delhi">
							<img src="images/2delhi.png">
							<p>Delhi/NCR<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?location=Bangalore">
							<img src="images/3banglor.png">
							<p>Bangalore<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?location=Mumbai">
							<img src="images/4mumbai.png">
							<p>Mumbai<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?location=Chennai">
							<img src="images/5chennai.png">
							<p>Chennai<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?location=Kolkata">
							<img src="images/6kolkata.png">
							<p>Kolkata<p>
						</a>
					</div>
				</div>
			</div>

			<!--Popular Categories Images-->
			<div class="img-div">
				<h2 class="img-title">Popular categories</h2>
				
				<div class="images">
					<div class="box">
						<a href="internships.php?category=Information%20Technology">
							<img class="cards" src="images/cat1.png">
							<p>Informatino Technology<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?category=Business%20Management">
							<img src="images/cat2.png">
							<p>Business Management<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?category=Humanities">
							<img src="images/cat3.png">
							<p>Humanities<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?category=Science%20&%20Technology">
							<img src="images/cat4.png">
							<p>Science & Technology<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?category=Law">
							<img src="images/cat5.png">
							<p>Law<p>
						</a>
					</div>
					<div class="box">
						<a href="internships.php?category=Architecture">
							<img src="images/cat6.png">
							<p>Architecture<p>
						</a>
					</div>
				</div>
			</div>
            
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
    <script src="script/script.js"></script>
</html>