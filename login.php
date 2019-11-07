<?php session_start(); ?>

<?php require_once('connect.php'); ?>

<?php 

if (isset($_SESSION['isActive']) && $_SESSION['isActive'] == true) {
    //user is logged in
    header('Location: ./index.php');
    exit;
} else {
	if (isset($_POST['user']) && isset($_POST['pass'])) {
        $userPOST = $db->real_escape_string(sani($_POST['user']));
        $passPOST = $db->real_escape_string(sani($_POST['pass']));
        $userPOST = strtolower($userPOST);
        //var_dump($userPOST); 
        //var_dump($passPOST); 
        $res = $db->query("SELECT * FROM Users WHERE user_uname='$userPOST' or user_email='$userPOST'");
        if($res->num_rows == 1) {
            //sql statement does username check already, just have to check pass
            $row = $res->fetch_assoc();
            if($row['user_password'] == $passPOST){
                $_SESSION['isActive'] = true;
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['fname'] = $row['user_fname'];
                $_SESSION['lname'] = $row['user_lname'];
                $_SESSION['phone'] = $row['user_phone'];
                $_SESSION['email'] = $row['user_email'];

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

    }
?>

<?php 
    include_once('header.php');
    generateHeader('Barber Baldo - Login');
?>
<body class="animsition">

    <?php include_once('nav.php'); ?>

    <?php titleImage('Login'); ?>
	<!-- Contact form -->
	<section class="section-contact bg1-pattern p-t-90 p-b-113">

        <div class="container">
<?php if ($_GET['registration'] == 'success') { ?>
			<h3 class="tit7 t-center p-b-62">
				Registration was successful. Please log in.
			</h3>
<?php } ?>
<?php if (isset($err)) { ?>                     
			<h3 class="tit7 colore t-center p-b-62">
				Your email or password is incorrect. Please try again.
			</h3>
<?php }
?>
            <form action='login.php' method='POST' class="wrap-form-reservation size22 m-l-r-auto">
				<div class="row">
					<div class="col-12">
						<!-- Username/Email -->
						<span class="txt9">
							Email
						</span>

						<div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                            <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="user" placeholder="Email"
                                <?php 
                                if(isset($_POST['user'])){
                                    echo "value='{$userPOST}'";
                                } ?>>
						</div>
					</div>
                </div>
                <div class="row">
					<div class="col-md-12">
						<!-- Password -->
						<span class="txt9">
							Password
						</span>

						<div class="wrap-inputemail size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="password" name="pass" placeholder="Password">
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
