<?php session_start(); ?>

<?php 
    define(DEBUG, false);
?>

    <!-- Header -->
	<header>
		<!-- Header desktop -->
		<div class="wrap-menu-header gradient1 trans-0-4">
			<div class="container h-full">
				<div class="wrap_header trans-0-3">
					<!-- Logo -->
					<div class="logo">
						<a href="index.php">
							<img src="images/icons/logo.png" alt="IMG-LOGO" data-logofixed="images/icons/logo2.png">
						</a>
					</div>

					<!-- Menu -->
					<div class="wrap_menu p-l-45 p-l-0-xl">
						<nav class="menu">
							<ul class="main_menu">
								<li>
									<a href="index.php">Home</a>
								</li>
                                
								<li>
									<a href="contact.php">Contact</a>
								</li>
<?php  
    if (isset($_SESSION['isActive']) && $_SESSION['isActive'] == true) { ?>

								<li>
									<a href="myAccount.php">My Account</a>
								</li>

								<li>
									<a href="logout.php">Logout</a>
                                </li>

<?php } else { //user not logged in ?>
								<li>
									<a href="register.php">Register</a>
								</li>

								<li>
									<a href="login.php">Login</a>
                                </li>
<?php } ?>

							</ul>
						</nav>
					</div>

					<!-- Social -->
					<div class="social flex-w flex-l-m p-r-20">
						<a href="https://www.instagram.com/barberbaldo/"><i class="fa fa-instagram" aria-hidden="true"></i></a>

						<button class="btn-show-sidebar m-l-33 trans-0-4"></button>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Sidebar -->
	<aside class="sidebar trans-0-4">
		<!-- Button Hide sidebar -->
		<button class="btn-hide-sidebar ti-close color0-hov trans-0-4"></button>

		<!-- - -->
		<ul class="menu-sidebar p-t-95 p-b-70">
			<li class="t-center m-b-13">
				<a href="index.php" class="txt19">Home</a>
			</li>

			<li class="t-center m-b-13">
				<a href="contact.php" class="txt19">Contact</a>
			</li>

<?php  
    if (isset($_SESSION['isActive']) && $_SESSION['isActive'] == true) { ?>

			<li class="t-center m-b-13">
                <a href="logout.php" class="txt19">Logout</a>
            </li>
			<li class="t-center">
				<!-- Button3 -->
				<a href="myAccount.php" class="btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
					My Account
				</a>
			</li>

<?php } else { //user not logged in ?>
			<li class="t-center m-b-13">
                <a href="register.php" class="txt19">Register</a>
            </li>

			<li class="t-center">
				<!-- Button3 -->
				<a href="login.php" class="btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
					Login
				</a>
			</li>
<?php } ?>

		</ul>

		<!-- - -->
        <div class="gallery-sidebar t-center p-l-60 p-r-60 p-b-40">
            <!-- - -->
            <h4 class="txt20 m-b-33">
                Gallery
            </h4>

            <!-- Gallery -->
            <div class="wrap-gallery-sidebar flex-w">
                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-01.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-01.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-02.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-02.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-03.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-03.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-05.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-05.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-06.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-06.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-07.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-07.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-09.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-09.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-10.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-10.jpg" alt="GALLERY">
                </a>

                <a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-11.jpg" data-lightbox="gallery-footer">
                    <img src="images/photo-gallery-thumb-11.jpg" alt="GALLERY">
                </a>
            </div>
    </aside>
