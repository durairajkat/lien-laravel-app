<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/index.php';

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>Dashboard Login</title>
    <meta charset="utf-8" />
    <link href="../common/vine/reset.css" rel="stylesheet" />
    <link href="../common/vine/vine.css" rel="stylesheet" />
    <script src="../common/vine/jquery-2.2.3.min.js"></script>
    <script src="../common/vine/vine.js"></script>
    <link href="../common/vine/themes/dashboard/theme.css" rel="stylesheet" />
    <link href="css/theme.css" rel="stylesheet" />
    <link href="css/login.css" rel="stylesheet" />
    <script src="js/theme.js"></script>
    <script src="js/login.js"></script>
    <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="vine vine-responsive vine-rounded login">

    <form <?php echo $form->getHeaders(); ?>>

        <div id="login-wrap" class="sm-pad-all">

            <?php echo Vine_Ui::getMessages(); ?>

            <h1 class="h3 halt line-bottom">Dashboard Login</h1>

            <div class="row">
                <label>
                    Email Address
                    <a class="hang-right no-underline hover-underline" href="!#">
                        Not Registered?
                    </a>
                </label>
                <input
                    type="text"
                    name="email"
                    value=""
                />
            </div>

            <div class="row">
                <label>
                    Password
                    <a class="hang-right no-underline hover-underline" href="!#">
                        Forgot Password?
                    </a>
                </label>
                <input
                    type="password"
                    name="password"
                    value=""
                />
            </div>

            <div class="row flow">

                <div class="hang-left pad-right">
                    <button type="submit" class="button">
                        Login
                    </button>
                </div>

                <div class="hang-left pad-left">
                    <input
                        type="checkbox"
                        name="remember_me"
                        value="Y"
                    />
                </div>

                <div id="remember-me" class="hang-left">
                    Remember Me
                </div>

            </div>

        </div>

    </form>

</body>
</html>