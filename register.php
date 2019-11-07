<?php session_start(); ?>

<?php require_once('connect.php'); ?>

<?php 

if (isset($_SESSION['isActive']) && $_SESSION['isActive'] == true) {
    //user is logged in
    header('Location: ./index.php');
    exit;
} else {
	if (isset($_POST) && !empty($_POST)) {
        $fnamePOST = $db->real_escape_string(sani($_POST['first']));
        $lnamePOST = $db->real_escape_string(sani($_POST['last']));
        $numberPOST = $db->real_escape_string(sani($_POST['phone']));
        $emailPOST = $db->real_escape_string(sani($_POST['mail']));
        $emailPOST = strtolower($emailPOST);
        $passwordPOST = $db->real_escape_string(sani($_POST['pass']));
        if (isset($_POST['first']) && !empty($_POST['first'])) {
            if (!preg_match('/^[a-zA-Z]+$/' , $_POST['first'])) {
                $err['first'] = 'Invalid first name';
            } else {
                $fnamePOST = $db->real_escape_string(sani($_POST['first']));
            }
        } else {
                $err['first'] = 'Invalid first name';
        }
        if (isset($_POST['last']) && !empty($_POST['last'])) {
            if (!preg_match('/^[a-zA-Z]+$/' , $_POST['last'])) {
                $err['last'] = 'Invalid last name';
            } else {
                $lnamePOST = $db->real_escape_string(sani($_POST['last']));
            }
        } else {
                $err['last'] = 'Invalid last name';
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
        if (isset($_POST['mail']) && !empty($_POST['mail'])) {
            if (!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9]+(.[a-zA-Z0-9]+)+$/' , $_POST['mail'])) {
                $err['mail'] = 'Invalid email';
            } else {
                if($_POST['mail'] == $_POST['confirmMail']) {
                    $emailPOST = $db->real_escape_string(sani($_POST['mail']));
                    $emailPOST = strtolower($emailPOST);
                } else {
                    $err['mail'] = 'Emails did not match';
                }
            }
        } else {
                $err['mail'] = 'Invalid email';
        }
        if (isset($_POST['pass']) && !empty($_POST['pass'])) {
            if (strlen($_POST['pass']) < 6) {
                $err['pass'] = 'Password length must be at least 6';
            } else {
                if($_POST['pass'] == $_POST['confirmPass']) {
                    $passwordPOST = $db->real_escape_string(sani($_POST['pass']));
                } else {
                    $err['pass'] = 'Passwords did not match';
                }
            }
        } else {
                $err['pass'] = 'Invalid password';
        }

        if(empty($err['mail'])) { 
            $res = $db->query("SELECT * FROM Users WHERE user_email='$emailPOST'");
            if($res->num_rows > 0) {
                //email exists already
                $err['mail'] = 'Account with this email exists already';
            }
        }
        if(!isset($err)){
            $sql = "INSERT INTO Users
                    (user_email, user_password, user_fname, user_lname, user_phone)
                    VALUES
                    ('$emailPOST', '$passwordPOST', '$fnamePOST', '$lnamePOST', '$numberPOST')";
            if ($db->query($sql)) {
                header('Location: ./login.php?registration=success');
                exit;
            } else {
                $err['creation'] = 'Error creating account. Please try again later.';
            }
        }
            /*$row = $res->fetch_assoc();
            if($row['user_password'] == $passPOST){
                $_SESSION['isActive'] = true;
                $_SESSION['user'] = $row['user_fname'];

                session_regenerate_id(true);
                header("Location: ./index.php");
                exit;
            } else {
                $err = "<h5>Invalid login credentials</h5>";
            }
        } else {
            //invalid username/email or too many rows, possible SQL injection
            //var_dump($res);
            $err = "<h5>Invalid login credentials</h5>";
        }
         */

    }
?>

<?php 
    include_once('header.php');
    generateHeader('Barber Baldo - Register');
?>
<body class="animsition">

    <?php include_once('nav.php'); ?>

    <?php titleImage('Register'); ?>
	<!-- Contact form -->
	<section class="section-contact bg1-pattern p-t-90 p-b-113">

		<div class="container">
<?php if (isset($err)) {
        foreach($err as $val) { ?>
                     
			<h3 class="tit7 colore t-center p-b-62">
<?php echo $val; ?>
            </h3>
<?php } ?>
			<h3 class="tit7 colore t-center p-b-62">
				Please try again.
			</h3>
<?php }
?>
            <form action='register.php' method='POST' class="wrap-form-reservation size22 m-l-r-auto">
				<div class="row">
					<div class="col-md-4">
						<!-- First Name -->
                        <span class="txt9 <?php if (isset($err['first'])) echo 'colore';?>">
							First Name
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="first" placeholder="First Name"
                                <?php 
                                if(isset($_POST['first'])){
                                    echo "value='{$fnamePOST}'";
                                } ?>>
						</div>
					</div>
					<div class="col-md-4">
						<!-- Last Name -->
                        <span class="txt9 <?php if (isset($err['last'])) echo 'colore';?>">
							Last Name
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="last" placeholder="Last Name"
                                <?php 
                                if(isset($_POST['last'])){
                                    echo "value='{$lnamePOST}'";
                                } ?>>
						</div>
					</div>
					<div class="col-md-4">
						<!-- Last Name -->
                        <span class="txt9 <?php if (isset($err['phone'])) echo 'colore';?>">
							Phone Number
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="phone" placeholder="Phone Number: 1234567890"
                                <?php 
                                if(isset($_POST['phone'])){
                                    echo "value='{$numberPOST}'";
                                } ?>>
						</div>
					</div>
                </div>
				<div class="row">
					<div class="col-md-6">
						<!-- Email -->
                        <span class="txt9 <?php if (isset($err['mail'])) echo 'colore';?>">
							Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="mail" placeholder="Email"
                                <?php 
                                if(isset($_POST['mail'])){
                                    echo "value='{$emailPOST}'";
                                } ?>>
						</div>
					</div>
					<div class="col-md-6">
						<!-- Email -->
                        <span class="txt9 <?php if (isset($err['mail'])) echo 'colore';?>">
							Confirm Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="confirmMail" placeholder="Confirm Email">
						</div>
					</div>
                </div>
                <div class="row">
					<div class="col-md-6">
						<!-- Password -->
                        <span class="txt9 <?php if (isset($err['pass'])) echo 'colore';?>">
							Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="pass" placeholder="Password length must be at least 6">
						</div>
					</div>
					<div class="col-md-6">
						<!-- Password -->
                        <span class="txt9 <?php if (isset($err['pass'])) echo 'colore';?>">
							Confirm Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="confirmPass" placeholder="Confirm Password">
						</div>
					</div>

				</div>

				<div class="wrap-btn-booking flex-c-m m-t-13">
					<!-- Button3 -->
					<button type="submit" class="btn3 flex-c-m size36 txt11 trans-0-4">
						Submit
					</button>
				</div>
			</form>

		</div>
	</section>

<?php include_once('footer.php'); ?>

<?php

}

?>
