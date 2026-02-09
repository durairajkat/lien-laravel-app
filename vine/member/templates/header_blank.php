<?php

// Get template details
$login       = new Login_Member();
$page        = $this->_getPage();
$title       = $this->_getTitle();
$javaScripts = $this->_getJavaScripts();
$styleSheets = $this->_getStyleSheets();

// Removes IE backwards compatibility junk
header('X-UA-Compatible: IE=edge,chrome=1');

?>

<meta charset="utf-8" />
<!--<link href="/vine/common/vine/reset.css" rel="stylesheet" />-->
<link href="/vine/common/vine/vine.css" rel="stylesheet" />
<link href="/vine/common/vine/css/dialogs.css?r=<?=time()?>" rel="stylesheet" />
<!--<script src="/vine/common/vine/jquery-2.2.3.min.js"></script>-->
<!--<script src="/vine/common/vine/vine.js?r=--><?php //echo time();?><!--"></script>-->
<!--<script src="/vine/common/js/jquery-ui.min.js"></script>-->
<link href="/vine/common/vine/themes/dashboard/theme.css" rel="stylesheet" />
<link href="/vine/member/css/theme.css" rel="stylesheet" />
<!--    <script src="/vine/member/js/theme.js"></script> -->




    <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->