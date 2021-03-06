<!DOCTYPE html>
<html>
	<head>
		<title>One Stop</title>
		<meta charset="UTF-8">
		<meta name="description" content="homepage of One Stop">
		<meta name="author" content="Christina Peebles, Itssel Sanchez, Joseph Sharpee, William Wang">
		<link href="homepage.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script defer src="calendar.js" language="javascript" type="text/javascript"></script>
		<script defer src="formInteract.js"></script>
		<script defer>
			jQuery(function() {
				$("#add-event-btn").click(function(){
					$(".add-event-sect").toggle();
				});
			});
		</script>
	</head>
	<?php
		session_start();
		
		include 'checkLogin.php';
		$user = "";
		$sessid = "";
		
		if (isset($_COOKIE['user'])) {
			login($_COOKIE['user']);
		}
		
		if (checkLogin()) {
			if (isset($_SESSION['LAST_REQ']) && (time() - $_SESSION['LAST_REQ'] > 1800) && !isset($_COOKIE['user'])) {
				logout();
				header("Location: login.php?timeout=true");
			}
			$_SESSION['LAST_REQ'] = time();
			$user = $_SESSION['user'];
			$sessid = session_id();
		}
		
		$submitted = !empty($_POST["submit"]);
		if ($submitted) {
			if (checkLogin()) {
				$subdate = $_POST['date'];
				$subdow = [];
				$subtime = $_POST['times'];
				$subtitle = $_POST['title'];
				$subdescrip = $_POST['description'];
				if ($_POST['description'] == "") {
					$subdescrip = "No description was provided for this event.";
				}
				
				if (isset($_POST['dow'])) {
					$subdow = $_POST['dow'];
					for ($i = 0; $i < count($subdow); $i++) {
						for ($j = 0; $j < count($subtime); $j++) {
							insertEvent("1000-01-01", $subdow[$i], $subtime[$j], $subtitle, $subdescrip);
						}
					}
				} else {
					for ($i = 0; $i < count($subtime); $i++) {
						insertEvent($subdate, "", $subtime[$i], $subtitle, $subdescrip);
					}
				}
				header("Location: homepage.php");
			} else {
				session_unset();
				session_destroy();
				header("Location: login.php");
			}
		}
		
		if (checkLogin()) {
			if (isset($_GET["logout"])) {
				logout();
				header("Location: homepage.php");
			} else if (isset($_GET["del"])) {
				deleteEvent($_GET["del"]);
			}
		}
	?>

	<body onload="generateCalendar('none', <?php echo "'$user', '$sessid'"?>)">
		<header>
			<div id="bar">
				<a href="homepage.php">
					<img id="logo" src="logo.png" alt="One Stop Logo">
				</a>
				<nav>
					<a href="homepage.php">
						<div class="nav_l">Home</div>
					</a>
					<a href="about.html">
						<div class="nav_l">About</div>
					</a>
					<a href="self_care.html">
						<div class="nav_l">Self-Care</div>
					</a>
					<a href="study_tips.html">
						<div class="nav_l">Study Tips</div>
					</a>
					<a href="contact_us.html">
						<div class="nav_l">Contact</div>
					</a>
				</nav>
			</div>
		</header>

		<div id="home">
			<div class="main">What do you have planned?</div>

			<div id="register">
				<?php
					if (checkLogin()) {
						print <<<LOGOUT
<button onclick="location.href = 'homepage.php?logout=true';">Logout</button>\n
LOGOUT;
					} else {
						print <<<LOGIN
				<div class="sub" style="font-size: 1.5em; padding: 3px 0;">Access Personal Calendar</div>
				<button onclick="location.href = 'login.php';">Login</button>
				<button onclick="location.href = 'register.php';">Register</button>\n
LOGIN;
					}
				?>
			</div>

			<div id="calendar_top">
				<div id="weekchg">
					<button onclick="generateCalendar('dec', <?php echo "'$user', '$sessid'"?>)"> < </button>
					<button onclick="generateCalendar('inc', <?php echo "'$user', '$sessid'"?>)"> > </button>
				</div>
				<div class="sub" id="weekrange"></div>
				<div id="addev">
					<button id="add-event-btn">+ Event</button>
				</div>
			</div>

			<div class="add-event-sect">
				<br>
				<div class="descrip" id="home_about">
					<form method = "post" action="" onsubmit="return checked();">
						<div>
							<button id="repeat" onclick="toggleRepeat()">Repeated Event?</button>
						</div>
						<table id="formt">
							<tr id="date">
								<td style="width: 120px;">Date:</td>
								<td><div><input type="date" name="date"></div></td>
							</tr>
							<tr id="dow" style="display: none;">
								<td style="width: 120px;">Day of week:</td>
								<td>
									<div class="left">
										<div><input type="checkbox" name="dow[]" value="sun">Sunday</div>
										<div><input type="checkbox" name="dow[]" value="mon">Monday</div>
										<div><input type="checkbox" name="dow[]" value="tue">Tuesday</div>
										<div><input type="checkbox" name="dow[]" value="wed">Wednesday</div>
									</div>
									<div class="right">
										<div><input type="checkbox" name="dow[]" value="thu">Thursday</div>
										<div><input type="checkbox" name="dow[]" value="fri">Friday</div>
										<div><input type="checkbox" name="dow[]" value="sat">Saturday</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>Time:</td>
								<td>
									<div class="left">
										<div><input type="checkbox" name="times[]" value="6a">6 a.m.</div>
										<div><input type="checkbox" name="times[]" value="7a">7 a.m.</div>
										<div><input type="checkbox" name="times[]" value="8a">8 a.m.</div>
										<div><input type="checkbox" name="times[]" value="9a">9 a.m.</div>
										<div><input type="checkbox" name="times[]" value="10a">10 a.m.</div>
										<div><input type="checkbox" name="times[]" value="11a">11 a.m.</div>
										<div><input type="checkbox" name="times[]" value="12p">12 p.m.</div>
										<div><input type="checkbox" name="times[]" value="1p">1 p.m.</div>
										<div><input type="checkbox" name="times[]" value="2p">2 p.m.</div>
									</div>
									<div class="right">
										<div><input type="checkbox" name="times[]" value="3p">3 p.m.</div>
										<div><input type="checkbox" name="times[]" value="4p">4 p.m.</div>
										<div><input type="checkbox" name="times[]" value="5p">5 p.m.</div>
										<div><input type="checkbox" name="times[]" value="6p">6 p.m.</div>
										<div><input type="checkbox" name="times[]" value="7p">7 p.m.</div>
										<div><input type="checkbox" name="times[]" value="8p">8 p.m.</div>
										<div><input type="checkbox" name="times[]" value="9p">9 p.m.</div>
										<div><input type="checkbox" name="times[]" value="10p">10 p.m.</div>
										<div><input type="checkbox" name="times[]" value="11p">11 p.m.</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>Event Title: </br> (max length 30 characters)</td>
								<td><input type="text" name="title" size="30" maxlength="30" required></td>
							</tr>
							<tr>
								<td>Event Description: (optional; max length 300 characters)</td>
								<td><textarea id="edescrip" name="description" rows="8" cols="40" maxlength="300"></textarea></td>
							</tr>
						</table>
						<div id="checkbox_note"></div>
						<input class="action" type="submit" name="submit" value="Submit">
						<input class="action" type="reset" name="reset" value="Reset">
					</form>
				</div>
				<br>
			</div>
			
			<div id="calTable">
			<table id="calendar">
				<tbody>
					<tr>
						<th style="width: 50px;"></th>
						<th>Sun.</th>
						<th>Mon.</th>
						<th>Tues.</th>
						<th>Wed.</th>
						<th>Thurs.</th>
						<th>Fri.</th>
						<th>Sat.</th>
					</tr>
					<tr id="6a">
						<td class="time">6 a.m.</td>
						<td id="sun-6a"></td>
						<td id="mon-6a"></td>
						<td id="tue-6a"></td>
						<td id="wed-6a"></td>
						<td id="thu-6a"></td>
						<td id="fri-6a"></td>
						<td id="sat-6a"></td>
					</tr>
					<tr id="7a">
						<td class="time">7 a.m.</td>
						<td id="sun-7a"></td>
						<td id="mon-7a"></td>
						<td id="tue-7a"></td>
						<td id="wed-7a"></td>
						<td id="thu-7a"></td>
						<td id="fri-7a"></td>
						<td id="sat-7a"></td>
					</tr>
					<tr id="8a">
						<td class="time">8 a.m.</td>
						<td id="sun-8a"></td>
						<td id="mon-8a"></td>
						<td id="tue-8a"></td>
						<td id="wed-8a"></td>
						<td id="thu-8a"></td>
						<td id="fri-8a"></td>
						<td id="sat-8a"></td>
					</tr>
					<tr id="9a">
						<td class="time">9 a.m.</td>
						<td id="sun-9a"></td>
						<td id="mon-9a"></td>
						<td id="tue-9a"></td>
						<td id="wed-9a"></td>
						<td id="thu-9a"></td>
						<td id="fri-9a"></td>
						<td id="sat-9a"></td>
					</tr>
					<tr id="10a">
						<td class="time">10 a.m.</td>
						<td id="sun-10a"></td>
						<td id="mon-10a"></td>
						<td id="tue-10a"></td>
						<td id="wed-10a"></td>
						<td id="thu-10a"></td>
						<td id="fri-10a"></td>
						<td id="sat-10a"></td>
					</tr>
					<tr id="11a">
						<td class="time">11 a.m.</td>
						<td id="sun-11a"></td>
						<td id="mon-11a"></td>
						<td id="tue-11a"></td>
						<td id="wed-11a"></td>
						<td id="thu-11a"></td>
						<td id="fri-11a"></td>
						<td id="sat-11a"></td>
					</tr>
					<tr id="12p">
						<td class="time">12 p.m.</td>
						<td id="sun-12p"></td>
						<td id="mon-12p"></td>
						<td id="tue-12p"></td>
						<td id="wed-12p"></td>
						<td id="thu-12p"></td>
						<td id="fri-12p"></td>
						<td id="sat-12p"></td>
					</tr>
					<tr id="1p">
						<td class="time">1 p.m.</td>
						<td id="sun-1p"></td>
						<td id="mon-1p"></td>
						<td id="tue-1p"></td>
						<td id="wed-1p"></td>
						<td id="thu-1p"></td>
						<td id="fri-1p"></td>
						<td id="sat-1p"></td>
					</tr>
					<tr id="2p">
						<td class="time">2 p.m.</td>
						<td id="sun-2p"></td>
						<td id="mon-2p"></td>
						<td id="tue-2p"></td>
						<td id="wed-2p"></td>
						<td id="thu-2p"></td>
						<td id="fri-2p"></td>
						<td id="sat-2p"></td>
					</tr>
					<tr id="3p">
						<td class="time">3 p.m.</td>
						<td id="sun-3p"></td>
						<td id="mon-3p"></td>
						<td id="tue-3p"></td>
						<td id="wed-3p"></td>
						<td id="thu-3p"></td>
						<td id="fri-3p"></td>
						<td id="sat-3p"></td>
					</tr>
					<tr id="4p">
						<td class="time">4 p.m.</td>
						<td id="sun-4p"></td>
						<td id="mon-4p"></td>
						<td id="tue-4p"></td>
						<td id="wed-4p"></td>
						<td id="thu-4p"></td>
						<td id="fri-4p"></td>
						<td id="sat-4p"></td>
					</tr>
					<tr id="5p">
						<td class="time">5 p.m.</td>
						<td id="sun-5p"></td>
						<td id="mon-5p"></td>
						<td id="tue-5p"></td>
						<td id="wed-5p"></td>
						<td id="thu-5p"></td>
						<td id="fri-5p"></td>
						<td id="sat-5p"></td>
					</tr>
					<tr id="6p">
						<td class="time">6 p.m.</td>
						<td id="sun-6p"></td>
						<td id="mon-6p"></td>
						<td id="tue-6p"></td>
						<td id="wed-6p"></td>
						<td id="thu-6p"></td>
						<td id="fri-6p"></td>
						<td id="sat-6p"></td>
					</tr>
					<tr id="7p">
						<td class="time">7 p.m.</td>
						<td id="sun-7p"></td>
						<td id="mon-7p"></td>
						<td id="tue-7p"></td>
						<td id="wed-7p"></td>
						<td id="thu-7p"></td>
						<td id="fri-7p"></td>
						<td id="sat-7p"></td>
					</tr>
					<tr id="8p">
						<td class="time">8 p.m.</td>
						<td id="sun-8p"></td>
						<td id="mon-8p"></td>
						<td id="tue-8p"></td>
						<td id="wed-8p"></td>
						<td id="thu-8p"></td>
						<td id="fri-8p"></td>
						<td id="sat-8p"></td>
					</tr>
					<tr id="9p">
						<td class="time">9 p.m.</td>
						<td id="sun-9p"></td>
						<td id="mon-9p"></td>
						<td id="tue-9p"></td>
						<td id="wed-9p"></td>
						<td id="thu-9p"></td>
						<td id="fri-9p"></td>
						<td id="sat-9p"></td>
					</tr>
					<tr id="10p">
						<td class="time">10 p.m.</td>
						<td id="sun-10p"></td>
						<td id="mon-10p"></td>
						<td id="tue-10p"></td>
						<td id="wed-10p"></td>
						<td id="thu-10p"></td>
						<td id="fri-10p"></td>
						<td id="sat-10p"></td>
					</tr>
					<tr id="11p">
						<td class="time">11 p.m.</td>
						<td id="sun-11p"></td>
						<td id="mon-11p"></td>
						<td id="tue-11p"></td>
						<td id="wed-11p"></td>
						<td id="thu-11p"></td>
						<td id="fri-11p"></td>
						<td id="sat-11p"></td>
					</tr>
				</tbody>
			</table>
			</div>

			<!-- Music Section -->
			<div id="music">
				<div class="sub">Listen to relaxing beats while you plan</div>
				<iframe id="spot" src="https://open.spotify.com/embed/playlist/1lCxPgDJMLU0rItXniVaEh" width="500" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
			</div>

			<!-- About Section -->
			<div id="aboutback">
				<div id="about-sect">
					<div class="sub">About One Stop for Students</div>
					<div id="about-img">
						<img id="notes" src="student_studying.png" alt="Student reading and taking notes."/>
						<div class="caption">Photo by cottonbro from Pexels</div>
					</div>
					<div class="descrip" id="home_about">
						<p>One Stop for Students is an interactive website where college students can plan out their week, listen to relaxing study beats, read up on the latest studying tips, and take some time to learn how they can focus on self-care during a busy week.<p>
						<a href="about.html"><button class="linkpage">Learn More</button></a>
					</div>
				</div>
			</div>
		</div>

		<footer>
			<p>Copyright 2021 Christina Peebles, Itssel Sanchez, Joseph Sharpee, William Wang</p>
			<p>Page Last Updated: 04/30/2021</p>
		</footer>
	</body>
</html>
