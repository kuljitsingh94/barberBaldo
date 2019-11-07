<?php session_start(); ?>

<?php require_once('connect.php'); ?>

<?php 

if (!isset($_SESSION['isActive']) || $_SESSION['isActive'] == false) {
    //user is not logged in
    header('Location: ./index.php');
    exit;
} else {
    if (isset($_POST) && !empty($_POST)) {
        $fnamePOST = $db->real_escape_string(sani($_POST['first']));
        $lnamePOST = $db->real_escape_string(sani($_POST['last']));
        $numberPOST = $db->real_escape_string(sani($_POST['phone']));
        $oldEmailPOST = $_SESSION['email'];
        $oldEmailPOST = strtolower($oldEmailPOST);
        $newEmailPOST = $db->real_escape_string(sani($_POST['newMail']));
        $newEmailPOST = strtolower($newEmailPOST);
        $oldPasswordPOST = $db->real_escape_string(sani($_POST['oldPass']));
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
        if (isset($_POST['newMail']) && !empty($_POST['newMail'])) {
            if (!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9]+(.[a-zA-Z0-9]+)+$/' , $_POST['newMail'])) {
                $err['mail'] = 'Invalid email';
            } else {
                if($_POST['newMail'] == $_POST['confirmMail']) {
                    $newEmailPOST = $db->real_escape_string(sani($_POST['newMail']));
                    $newEmailPOST = strtolower($newEmailPOST);
                } else {
                    $err['mail'] = 'Emails did not match';
                }
            }
        } else {
                $newEmailPOST = $_SESSION['email'];
        }
        if (isset($_POST['oldPass']) && !empty($_POST['oldPass'])) {
            $oldPasswordPOST = $db->real_escape_string(sani($_POST['oldPass']));
        } else {
            $err['oldPass'] = "Must enter password to update information";
        }
        if (isset($_POST['newPass']) && !empty($_POST['newPass'])) {
            if (strlen($_POST['newPass']) < 6) {
                $err['pass'] = 'New password length must be at least 6';
            } else {
                if($_POST['newPass'] == $_POST['confirmPass']) {
                    $newPasswordPOST = $db->real_escape_string(sani($_POST['newPass']));
                } else {
                    $err['pass'] = 'Passwords did not match';
                }
            }
        } else {
                $newPasswordPOST = $oldPasswordPOST;
        }

        if(!isset($err)) {
            $res = $db->query("SELECT * FROM Users WHERE user_email='$oldEmailPOST'");
            if ($res->num_rows == 1) {
                $row = $res->fetch_assoc();
				if($row['user_password'] == $oldPasswordPOST){
                    //good pass, can update
                } else {
					$err['oldPass'] = "Incorrect Password";
				}
            }
        }

        if(empty($err['mail']) && $newEmailPOST != $oldEmailPOST) { 
            $res = $db->query("SELECT * FROM Users WHERE user_email='$newEmailPOST'");
            if($res->num_rows > 0) {
                //email exists already
                $err['mail'] = 'Account with this new email exists already';
            }
        }

        if(!isset($err)){
            $sql = "UPDATE Users SET
                    user_email = '$newEmailPOST', 
                    user_password = '$newPasswordPOST', 
                    user_fname = '$fnamePOST', 
                    user_lname = '$lnamePOST', 
                    user_phone = '$numberPOST'
                    WHERE user_email = '$oldEmailPOST'";
            if ($db->query($sql)) {
                $_SESSION['fname'] = $fnamePOST;
                $_SESSION['lname'] = $lnamePOST;
                $_SESSION['phone'] = $numberPOST;
                $_SESSION['email'] = $newEmailPOST;

                header('Location: ./myAccount.php?update=success');
                exit;
            } else {
                $err['creation'] = 'Error updating account. Please try again later.';
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
    generateHeader('Barber Baldo - My Account');
?>
<body class="animsition">

    <?php include_once('nav.php'); ?>

    <?php titleImage('My Account'); ?>
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
            <?php if($_GET['update'] == "success") { ?>
			<h3 class="tit9 t-center p-b-62">
				Account information succesfully updated
            </h3>
            <?php } ?>
			<h3 class="tit7 t-center p-b-62">
				Update account information
            </h3>
    
            <form action='myAccount.php' method='POST' class="wrap-form-reservation size22 m-l-r-auto">
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
                                } else if (isset($_SESSION['fname'])) {
                                    echo "value='{$_SESSION['fname']}'";    
                                }?>>
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
                                } else if (isset($_SESSION['lname'])){
                                    echo "value='{$_SESSION['lname']}'";
                                }?>>
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
                                } else if (isset($_SESSION['phone'])){
                                    echo "value='{$_SESSION['phone']}'";
                                }?>>
						</div>
					</div>
                </div>
				<div class="row">
					<div class="col-md-4">
						<!-- Email -->
                        <span class="txt9">
							Old Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="oldMail" placeholder="Email"
                                <?php 
                                if(isset($_SESSION['email'])){
                                    echo "value='{$_SESSION['email']}'";
                                } ?> readonly>
						</div>
					</div>
					<div class="col-md-4">
						<!-- Email -->
                        <span class="txt9 <?php if (isset($err['mail'])) echo 'colore';?>">
							New Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="newMail" placeholder="New Email"
                                <?php 
                                if(!empty($_POST['newMail'])){
                                    echo "value='{$newEmailPOST}'";
                                } ?>>
						</div>
					</div>
					<div class="col-md-4">
						<!-- Email -->
                        <span class="txt9 <?php if (isset($err['mail'])) echo 'colore';?>">
							Confirm New Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="confirmMail" placeholder="Confirm New Email">
						</div>
					</div>
                </div>
                <div class="row">
					<div class="col-md-4">
						<!-- Password -->
                        <span class="txt9 <?php if (isset($err['oldPass'])) echo 'colore';?>">
							Current Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="oldPass" placeholder="Current Password">
						</div>
					</div>
					<div class="col-md-4">
						<!-- Password -->
                        <span class="txt9 <?php if (isset($err['newPass'])) echo 'colore';?>">
							New Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="newPass" placeholder="Length must be at least 6">
						</div>
					</div>
					<div class="col-md-4">
						<!-- Password -->
                        <span class="txt9 <?php if (isset($err['newPass'])) echo 'colore';?>">
							Confirm New Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="confirmPass" placeholder="Confirm New Password">
						</div>
					</div>

				</div>

				<div class="wrap-btn-booking flex-c-m m-t-13">
					<!-- Button3 -->
					<button type="submit" class="btn3 flex-c-m size36 txt11 trans-0-4">
						Update
					</button>
				</div>
			</form>

		</div>
	</section>

<?php include_once('footer.php'); ?>

<?php

}

?>
