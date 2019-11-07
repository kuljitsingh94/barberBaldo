<?php session_start(); ?>
<?php require_once('connect.php'); ?>
<?php
if (isset($_POST) && !empty($_POST)) {
        $namePOST = $db->real_escape_string(sani($_POST['name']));
        $numberPOST = $db->real_escape_string(sani($_POST['phone']));
        $emailPOST = $db->real_escape_string(sani($_POST['email']));
        $emailPOST = strtolower($emailPOST);
        $messagePOST = $db->real_escape_string(($_POST['message']));
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
                    $numberPOST = $db->real_escape_string(sani($_POST['phone']));
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
	    if (isset($_POST['message']) && !empty($_POST['message']) ) {
            if (!empty($_POST['message'])) {
                $messagePOST = $db->real_escape_string(($_POST['message']));
            } else {
                $err['message'] = 'Invalid message';
            }
        } else {
                $err['message'] = "Invalid message";
        }

        $out = "New Message from BarberBaldo.com!\n";
        $out.= "From: $namePOST\n";
        $out.= "Number: $numberPOST\n";
        $out.= "Email: $emailPOST\n";
        $out.= "Message: $messagePOST\n";

        if(!isset($err)) {
            if(mail('ksingh9@csub.edu', 'Barber Baldo Contact', $out)){
                header('Location: ./contact.php?contact=success');
                exit;
            }
            $err['contact'] = "Error sending contact message. Please try again later.";
        } 


}
?>
<?php 
    include_once('header.php');
    generateHeader('Barber Baldo - Contact');
?>
<body class="animsition">

    <?php include_once('nav.php'); ?>

    <?php titleImage('Contact'); ?>

	<!-- Contact form -->
	<section class="section-contact bg1-pattern p-t-90 p-b-113">
		<!-- Map -->
		<div class="container">
			<div class="map bo8 bo-rad-10 of-hidden">
				<div class="contact-map size37" id="google_map" data-map-x="35.789606" data-map-y="-119.249644" data-pin="images/icons/logo2.png" data-scrollwhell="0" data-draggable="1"></div>
			</div>
		</div>

		<div class="container">
<?php if ($_GET['contact'] == 'success') { ?>
            <h3 class="tit7 t-center p-b-62 p-t-105">
				Message sent. I will get back to you shortly.
			</h3>
<?php } else { ?>
            <h3 class="tit7 t-center p-b-62 p-t-105">
				Send me a Message
			</h3>
<?php if (isset($err)) {
        foreach($err as $val) { ?>

            <h3 class="tit7 colore t-center p-b-62">
<?php echo $val; ?>
            </h3>
<?php }} ?>

			<form action='./contact.php' method='POST' class="wrap-form-reservation size22 m-l-r-auto">
				<div class="row">
					<div class="col-md-4">
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

					<div class="col-md-4">
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

					<div class="col-md-4">
						<!-- Phone -->
						<span class="txt9">
							Phone
						</span>

						<div class="wrap-inputphone size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="phone" placeholder="Phone"
                            <?php if (!empty($_POST['phone'])) {
                                    echo "value='{$numberPOST}'";
                                    } else {
                                        if(isset($_SESSION['isActive']) && $_SESSION['isActive'] == true){
                                            echo "value='{$_SESSION['phone']}'";
                                        }
                                    } ?>>
						</div>
					</div>

					<div class="col-12">
						<!-- Message -->
						<span class="txt9">
							Message
						</span>
                        <textarea class="bo-rad-10 size35 bo2 txt10 p-l-20 p-t-15 m-b-10 m-t-3" name="message" placeholder="Message"><?php if (!empty($_POST['message'])) {echo "{$messagePOST}";}?></textarea>
					</div>
				</div>

				<div class="wrap-btn-booking flex-c-m m-t-13">
					<!-- Button3 -->
					<button type="submit" class="btn3 flex-c-m size36 txt11 trans-0-4">
						Submit
					</button>
				</div>
			</form>

			<div class="row p-t-135">
				<div class="col-sm-8 col-md-4 col-lg-4 m-l-r-auto p-t-30">
					<div class="dis-flex m-l-23">
						<div class="p-r-40 p-t-6">
							<img src="images/icons/map-icon.png" alt="IMG-ICON">
						</div>

						<div class="flex-col-l">
							<span class="txt5 p-b-10">
								Location
							</span>

							<span class="txt23 size38">
								2343 Girard St, Delano, CA 93215
							</span>
						</div>
					</div>
				</div>

				<div class="col-sm-8 col-md-3 col-lg-4 m-l-r-auto p-t-30">
					<div class="dis-flex m-l-23">
						<div class="p-r-40 p-t-6">
							<img src="images/icons/phone-icon.png" alt="IMG-ICON">
						</div>


						<div class="flex-col-l">
							<span class="txt5 p-b-10">
								Call/Text Me
							</span>

							<span class="txt23 size38">
								(661) 586 - 2628
							</span>
						</div>
					</div>
				</div>

				<div class="col-sm-8 col-md-5 col-lg-4 m-l-r-auto p-t-30">
					<div class="dis-flex m-l-23">
						<div class="p-r-40 p-t-6">
							<img src="images/icons/instagram.png" alt="IMG-ICON">
						</div>


						<div class="flex-col-l">
							<span class="txt5 p-b-10">
								Instagram
							</span>

							<span class="txt23 size38">
								@barberbaldo
							</span>
						</div>
					</div>
				</div>
			</div>
<?php } ?>
        </div>
	</section>

<?php include_once('footer.php'); ?>
