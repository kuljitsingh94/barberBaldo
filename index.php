<?php session_start(); ?>
<?php require_once('connect.php'); ?>
<?php require_once('timeFunctions.php'); ?>
<?php

   if (isset($_POST) && !empty($_POST)) {
        $namePOST = $db->real_escape_string(sani($_POST['name']));
        $phonePOST = $db->real_escape_string(sani($_POST['phone']));
        $emailPOST = $db->real_escape_string(sani($_POST['email']));
        $emailPOST = strtolower($emailPOST);
        $datePOST = $db->real_escape_string($_POST['date']);
        $timePOST = $db->real_escape_string($_POST['time']);
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            if (!preg_match('/^[a-zA-Z ]+$/' , $_POST['name'])) {
                $err['name'] = 'Invalid name';
            } else {
                $namePOST = $db->real_escape_string(sani($_POST['name']));
            }
        } else {
                $err['name'] = 'Invalid name';
        }
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            if (!preg_match('/^[0-9]+$/' , $_POST['phone'])) {
                $err['phone'] = 'Invalid phone number';
            } else {
                if (strlen($_POST['phone']) == 10 || strlen($_POST['phone']) == 11) {
                    $phonePOST = $db->real_escape_string(sani($_POST['phone']));
                } else {
                    $err['phone'] = 'Invalid phone number';
                }
            }
        } else {
                $err['phone'] = 'Invalid phone number';
        }
		if (isset($_POST['email']) && !empty($_POST['email'])) {
            if (!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9]+(.[a-zA-Z0-9]+)+$/' , $_POST['email'])) {
                $err['email'] = 'Invalid email';
            } else {
                $emailPOST = $db->real_escape_string(sani($_POST['email']));
                $emailPOST = strtolower($emailPOST);
            }
        } else {
                $err['email'] = 'Invalid email';
        }
        if (isset($_POST['date']) && !empty($_POST['date'])) {
            if (!preg_match('/^[0-1][0-9]\/[0-3][0-9]\/[2][0-9][0-9][0-9]$/', $_POST['date'])) {
                $err['date'] = 'Invalid date';
            } else {
                $datePOST = $db->real_escape_string($_POST['date']);
                list($monthPOST, $dayPOST, $yearPOST) = explode('/', $datePOST);
            }
        } else {
                $err['date'] = 'Invalid date';
        }
        if (isset($_POST['time']) && !empty($_POST['time'])) {
            if (!preg_match('/^[0-1]?[0-9]:[0-5][0] [ap]m$/', $_POST['time'])) {
                $err['time'] = 'Invalid time';
            } else {
                $timePOST = $db->real_escape_string($_POST['time']);
                list($hourPOST, $minPOST) = explode(':', $timePOST);
                list($minPOST, $ampmPOST) = explode(' ', $minPOST);
                if ($ampmPOST == "pm" && $hourPOST < 12) {
                    $hourPOST +=12;
                }
            }
        } else {
                $err['time'] = 'Invalid time';
        }

        if (empty($err['date']) && empty($err['time'])) {
            $apptTime = mktime($hourPOST, $minPOST, 0, $monthPOST, $dayPOST, $yearPOST);
            if ($apptTime < time() || $apptTime > (time() + 86400*14)) {
                $err['appointment'] = 'Invalid appointment';
            }
        }

        if (!isset($err)) {
            $sql = "SELECT user_id FROM Users WHERE user_email = '$emailPOST' AND user_phone = '$phonePOST'";
            $res = $db->query($sql);
            if ($res->num_rows == 1) {
                $row = $res->fetch_array(MYSQLI_ASSOC);
                $useridPOST = (int)$row['user_id'];
            } else {
                $useridPOST = (int)NULL;
            }

            $sql = "SELECT appt_time FROM Appts WHERE appt_time = $apptTime";
            $res = $db->query($sql);
            if ($res->num_rows>0) {
                $err['appointment'] = 'Appointment time is already taken';
            } 
            if (!isset($err)) {
                $sql = "INSERT INTO Appts
                        (appt_time, appt_userid, appt_name, appt_phone, appt_email)
                        VALUES 
                        ($apptTime, $useridPOST, '$namePOST', '$phonePOST', '$emailPOST')";
                if ($db->query($sql)) {
                    $insertion=true;
                } else {
                    $err[] = "Appointment reservation failed. Please try again later.";
                }
            }

        }
   
   }

?>

<?php 
    include_once("header.php");
    generateHeader('Barber Baldo'); 
?>

<body class="animsition">

    <?php include_once("nav.php"); ?>

<?php if ($_SESSION['isActive'] == true) {
        titleImage("Welcome {$_SESSION['fname']}");
    } else {
        titleImage('Welcome');
    } ?>    

	<!-- Booking -->
	<section class="section-booking bg1-pattern p-t-100 p-b-110">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 p-b-30">
                    <?php if($insertion) { ?>
                        <h3 class="tit7 t-center p-b-62 p-t-105">
<?php echo "Thank you $namePOST!"; ?>
                        </h3>                        
                        <h3 class="tit7 t-center">
<?php echo "Your appointment has been made on";?> 
                        </h3>                        
                        <h3 class="tit7 t-center">
<?php echo "$datePOST"; ?> 
                        </h3>                        
                        <h3 class="tit7 t-center">
<?php echo "at"; ?>
                        </h3>                        
                        <h3 class="tit7 t-center">
<?php echo "$timePOST"; ?>
                        </h3>                        
                    <?php } else { ?> 
                    <div class="t-center">
						<span class="tit2 t-center">
							Quick Appointment
                        </span>

                        <?php if (isset($err)) {
                                foreach($err as $val) { ?>
                        <h3 class="tit7 colore t-center p-b-32 p-t-32">
                        <?php echo $val; ?>
                        </h3>
                        <?php } } ?>

					</div>

					<form action='./index.php' method='POST' class="wrap-form-booking">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date -->
                                <span class="txt9">
                                    Date
                                </span>

                                <div class="wrap-inputdate pos-relative txt10 size12 bo2 bo-rad-10 m-t-3 m-b-23">
                                    <select class="selection-1" name="date">
                                        <option>--/--/----</option>
                                        <?php
                                            for ($i = 0; $i <14; $i++) {
                                                echo "<option>".date("m/d/Y", time()+$i*86400)."</option>\n";
                                            }    
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Time -->
                                <span class="txt9">
                                    Time
                                </span>

                                <div class="wrap-inputtime size12 bo2 bo-rad-10 m-t-3 m-b-23">
                                    <!-- Select2 -->
                                    <select class="selection-1" name="time">
                                        <option>--:--</option>
                                        <option>8:00 am</option>
                                        <option>8:20 am</option>
                                        <option>8:40 am</option>
                                        <option>9:00 am</option>
                                        <option>9:20 am</option>
                                        <option>9:40 am</option>
                                        <option>10:00 am</option>
                                        <option>10:20 am</option>
                                        <option>10:40 am</option>
                                        <option>11:00 am</option>
                                        <option>11:20 am</option>
                                        <option>11:40 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:20 pm</option>
                                        <option>12:40 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:20 pm</option>
                                        <option>1:40 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:20 pm</option>
                                        <option>2:40 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:20 pm</option>
                                        <option>3:40 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:20 pm</option>
                                        <option>4:40 pm</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <span class="txt9">
                                    Name
                                </span>

                                <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                                    <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="name" placeholder="Name"
                                    <?php if (!empty($_POST['name'])) {
                                            echo "value='{$namePOST}'";
                                    } else {
                                        if(isset($_SESSION['isActive']) && $_SESSION['isActive'] == true){
                                            echo "value='{$_SESSION['fname']} {$_SESSION['lname']}'";
                                        }
                                    } ?>>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Phone -->
                                <span class="txt9">
                                    Phone
                                </span>

                                <div class="wrap-inputphone size12 bo2 bo-rad-10 m-t-3 m-b-23">
                                    <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="phone" placeholder="Phone"
                                    <?php if (!empty($_POST['phone'])) {
                                            echo "value='{$phonePOST}'";
                                    } else {
                                        if(isset($_SESSION['isActive']) && $_SESSION['isActive'] == true){
                                            echo "value='{$_SESSION['phone']}'";
                                        }
                                    } ?>>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-12'>
								<!-- Email -->
								<span class="txt9">
									Email
								</span>

								<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
									<input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="email" placeholder="Email"
									<?php if (!empty($_POST['email'])) {
                                    		echo "value='{$emailPOST}'";
                                    } else {
                                        if(isset($_SESSION['isActive']) && $_SESSION['isActive'] == true){
                                            echo "value='{$_SESSION['email']}'";
                                        }
                                    } ?>>
                                </div>
                            </div>
                        </div>

						<div class="wrap-btn-booking flex-c-m m-t-6">
							<!-- Button3 -->
							<button type="submit" class="btn3 flex-c-m size13 txt11 trans-0-4">
								Book it!
							</button>
						</div>
                    </form>
                <?php } ?>
				</div>

				<div class="col-lg-6 p-b-30 p-t-18">
					<div class="wrap-pic-booking size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<img src="images/booking-01.jpg" alt="IMG-OUR">
					</div>
				</div>
            </div>
<?php if($_SESSION['isActive'] == true) { ?>
            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                        <h3 class="tit7 t-center p-b-62 p-t-105">
                            Reserved Appointment Times
                        </h3>
                        <?php 
                            $sql = "SELECT * FROM Appts WHERE appt_time > ";
                            $sql.= time();
                            $sql.= " ORDER BY appt_time ASC";
                            $res = $db->query($sql);
                            if ($res->num_rows > 0 ) {
                                echo "<ul class='list-group'>\n";
                                while($row = $res->fetch_assoc()) {
                                    $out = "<li class='list-group-item t-center'>";
                                    $out.= date("m/d/Y h:i a", $row['appt_time']);
                                    $out.= "</li>\n";
                                    echo $out;
                                }
                                echo "</ul>\n";    
                            } else {
                                echo "None";
                            }
                        ?>                        
                </div>
                <div class="col-md-4">
                </div>
            </div>
<?php } ?>
		</div>
	</section>

	<!-- Review -->
	<section class="section-review p-t-115">
		<!-- - -->
		<div class="title-review t-center m-b-2">
			<span class="tit2 p-l-15 p-r-15">
				Customers Say
			</span>

			<h3 class="tit8 t-center p-l-20 p-r-15 p-t-3">
				Review
			</h3>
		</div>

		<!-- - -->
		<div class="wrap-slick3">
			<div class="slick3">
				<div class="item-slick3 item1-slick3">
					<div class="wrap-content-slide3 p-b-50 p-t-50">
						<div class="container">
							<div class="pic-review size14 bo4 wrap-cir-pic m-l-r-auto animated visible-false" data-appear="zoomIn">
								<img src="images/avatar-01.jpg" alt="IGM-AVATAR">
							</div>

							<div class="content-review m-t-33 animated visible-false" data-appear="fadeInUp">
								<p class="t-center txt12 size15 m-l-r-auto">
									“ We are lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tellus sem, mattis in pre-tium nec, fermentum viverra dui ”
								</p>

								<div class="star-review fs-18 color0 flex-c-m m-t-12">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
								</div>

								<div class="more-review txt4 t-center animated visible-false m-t-32" data-appear="fadeInUp">
									Marie Simmons ˗ New York
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="item-slick3 item2-slick3">
					<div class="wrap-content-slide3 p-b-50 p-t-50">
						<div class="container">
							<div class="pic-review size14 bo4 wrap-cir-pic m-l-r-auto animated visible-false" data-appear="zoomIn">
								<img src="images/avatar-04.jpg" alt="IGM-AVATAR">
							</div>

							<div class="content-review m-t-33 animated visible-false" data-appear="fadeInUp">
								<p class="t-center txt12 size15 m-l-r-auto">
									“ We are lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tellus sem, mattis in pre-tium nec, fermentum viverra dui ”
								</p>

								<div class="star-review fs-18 color0 flex-c-m m-t-12">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
								</div>

								<div class="more-review txt4 t-center animated visible-false m-t-32" data-appear="fadeInUp">
									Marie Simmons ˗ New York
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="item-slick3 item3-slick3">
					<div class="wrap-content-slide3 p-b-50 p-t-50">
						<div class="container">
							<div class="pic-review size14 bo4 wrap-cir-pic m-l-r-auto animated visible-false" data-appear="zoomIn">
								<img src="images/avatar-05.jpg" alt="IGM-AVATAR">
							</div>

							<div class="content-review m-t-33 animated visible-false" data-appear="fadeInUp">
								<p class="t-center txt12 size15 m-l-r-auto">
									“ We are lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tellus sem, mattis in pre-tium nec, fermentum viverra dui ”
								</p>

								<div class="star-review fs-18 color0 flex-c-m m-t-12">
									<i class="fa fa-star" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
									<i class="fa fa-star p-l-1" aria-hidden="true"></i>
								</div>

								<div class="more-review txt4 t-center animated visible-false m-t-32" data-appear="fadeInUp">
									Marie Simmons ˗ New York
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="wrap-slick3-dots m-t-30"></div>
		</div>
	</section>

<?php include_once('footer.php'); ?>
