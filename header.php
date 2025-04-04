<?php 
include_once('session.php'); 


ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('config/db.php');  
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <!-- Site Title -->
        <title>Query Buddy </title>

        <!-- Place favicon.ico in the root directory -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

        <!-- CSS here -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/css/venobox.min.css">
        <link rel="stylesheet" href="assets/css/animate.min.css">
        <link rel="stylesheet" href="assets/css/keyframe-animation.css">
        <link rel="stylesheet" href="assets/css/odometer.min.css">
        <link rel="stylesheet" href="assets/css/nice-select.css">
        <link rel="stylesheet" href="assets/css/daterangepicker.css">
        <link rel="stylesheet" href="assets/css/swiper.min.css">
        <link rel="stylesheet" href="assets/css/main.css">

        <!-- JS here -->
        <script src="assets/js/vendor/jquary-3.6.0.min.js"></script>
        
<style>
        header ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 15px;
    }

    header ul li {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background: #fff;
        list-style: none;
        padding: 10px;
        border: 1px solid #ccc;
        width: 150px;
    }

    .dropdown-menu li {
        padding: 5px;
        width: 100%;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }
        </style>
    </head>

    <body>
        <!-- header-area-start -->
        <header class="header header-2 sticky-active">
           
            <div class="primary-header">
                <div class="container">
                    <div class="primary-header-inner">
                        <div class="header-logo d-lg-block">
                            <a href="index.php">
                                <img src="assets/img/logo/logo-2.png" alt="Logo">
                            </a>
                        </div>
                        <div class="header-right-wrap">
                            <div class="header-menu-wrap">
                                <div class="mobile-menu-items">
                                    <ul class="sub-menu">
                                        <li><a href="index.php">Home</a></li>
                                        <li><a href="about-us.php">About us</a></li>
                                        <li><a href="chatbot.php">Chatbot</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.header-menu-wrap -->
                            <div class="header-right">

                            <?php if (isset($_SESSION['user_id'])): ?>
                                 <!-- My Account Dropdown -->
                                <li class="dropdown" style="list-style-type: none;">
                                    <a href="#" class="dropdown-toggle text-white" id="accountDropdown">My Account</a>
                                    <ul class="dropdown-menu" id="dropdownMenu">
                                        <li><a href="account.php">Profile</a></li>
                                        <li><a href="logout.php">Logout</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <a href="login.php" class="ed-primary-btn header-btn">Login <i class="fa-sharp fa-regular fa-arrow-right"></i></a>
                            <?php endif; ?>

                                <div class="header-logo d-none d-lg-none">
                                    <a href="index.php">
                                        <img src="assets/img/logo/logo-1.png" alt="Logo">
                                    </a>
                                </div>
                                <div class="header-right-item d-lg-none d-md-block">
                                    <a href="javascript:void(0)" class="mobile-side-menu-toggle"
                                        ><i class="fa-sharp fa-solid fa-bars"></i
                                    ></a>
                                </div>
                            </div>
                            <!-- /.header-right -->
                        </div>
                    </div>
                    <!-- /.primary-header-inner -->
                </div>
            </div>
        </header>
        <!-- /.Main Header -->