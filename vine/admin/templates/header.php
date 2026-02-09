<?php

// Get template details
$login       = new Login_Admin();
$page        = $this->_getPage();
$title       = $this->_getTitle();
$javaScripts = $this->_getJavaScripts();
$styleSheets = $this->_getStyleSheets();

// Removes IE backwards compatibility junk
header('X-UA-Compatible: IE=edge,chrome=1');

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <title>Dashboard - <?php echo htmlentities($title); ?></title>

    <meta charset="utf-8" />
    <link href="../common/vine/reset.css" rel="stylesheet" />
    <link href="../common/vine/vine.css" rel="stylesheet" />
    <script src="../common/vine/jquery-2.2.3.min.js"></script>
    <script src="../common/vine/vine.js"></script>
    <script src="../common/js/jquery-ui.min.js"></script>
    <link href="../common/vine/themes/dashboard/theme.css" rel="stylesheet" />
    <link href="css/theme.css" rel="stylesheet" />
    <script src="js/theme.js"></script>

    <?php if ($styleSheets): ?>

        <?php foreach ($styleSheets as $css): ?>

            <link href="<?php echo $css; ?>" rel="stylesheet" />

        <?php endforeach; ?>

    <?php endif; ?>

    <?php if ($javaScripts): ?>

        <?php foreach ($javaScripts as $js): ?>

            <script src="<?php echo $js; ?>"></script>

        <?php endforeach; ?>

    <?php endif; ?>

    <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="vine vine-responsive flavor-left">

<header id="header">

    <div class="inner">

        <div id="logo" class="col-8 col-sm-9 pad ellipsis">
            DASHBOARD DEMO
        </div>

        <div id="main-menu-toggle" class="col-sm-3 sm-pad md-hide lg-hide">
            <i class="fa fa-navicon"></i>
        </div>

        <div id="login-info" class="sm-hide col-4 text-right">

            <i id="login-icon" class="fa fa-user hang-right pad mar-right"></i>

            <div id="login-name" class="text-right hang-right pad-left">
                <?php echo Vine_Html::output($login->getName()); ?>
            </div>

            <nav id="login-menu" class="mar-right">
                <ul class="strip">
                    <li>
                        <a href="main">
                            <i class="fa fa-home"></i>
                            Main
                        </a>
                    </li>
                    <li>
                        <a href="logout">
                            <i class="fa fa-sign-out"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

    </div>

</header>

<div id="wrapper">

    <div class="inner">

        <nav id="main-menu" class="col-sm-12 col-md-3 col-lg-2">
            <ul class="strip">
                <li>
                    <a href="main">
                        <i class="fa fa-home"></i>
                        Main
                    </a>
                </li>
                <li>
                    <a href="demo-empty">
                        <i class="fa fa-file-o"></i>
                        Empty Page
                    </a>
                </li>
                <li>
                    <a href="demo-textual">
                        <i class="fa fa-align-left"></i>
                        Textual
                    </a>
                </li>
                <li>
                    <a href="demo-helpers">
                        <i class="fa fa-code"></i>
                        Helpers
                    </a>
                </li>
                <li>
                    <a href="demo-grid">
                        <i class="fa fa-columns"></i>
                        Grids
                    </a>
                </li>
                <li>
                    <a href="demo-forms">
                        <i class="fa fa-edit"></i>
                        Forms
                    </a>
                </li>
                <li>
                    <a href="demo-tables">
                        <i class="fa fa-table"></i>
                        Tables
                    </a>
                </li>
                <li>
                    <a href="demo-widgets">
                        <i class="fa fa-cubes"></i>
                        Widgets
                    </a>
                </li>
                <li>
                    <a href="logout">
                        <i class="fa fa-sign-out"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content" class="col-sm-12 col-md-9 col-lg-10 sm-pad md-pad lg-pad-h pad-bottom sm-size-less-2 md-size-less-2 lg-size-less-1">