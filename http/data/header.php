<?php 
session_start();
$uploaddir_bid_files = $_SERVER['DOCUMENT_ROOT'].'/file/uploads/bids/';
$uploaddir_bid_files_short = '/file/uploads/bids/';
$file_size_max = 20971520;

echo '
<!DOCTYPE HTML>
<!--
https://github.com/GrGcmd/helpdesk
-->
<HTML lang="RU">
<head>
 <title>IT Help Desk</title>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta http-equiv="Refresh" content="600" />
 <link rel="shortcut icon" href="/file/images/favicon.ico" type="image/x-icon">
 <link rel="stylesheet" href="/css/bootstrap.css">
 <link rel="stylesheet" href="/css/style.css">
 <link rel="stylesheet" href="/css/trix.css">
 <script src="/js/jquery-3.7.1.min.js"></script>
 <script src="/js/popper.min.js"></script>
 <script src="/js/bootstrap.bundle.min.js"></script>
 <script src="/js/other.js"></script>
 <script src="/js/trix.umd.min.js"></script>
 <link rel="stylesheet" href="/file/icons/bootstrap-icons.css">

 <!-- Graphs -->
 <script src="/css/diagrams/chart.js"></script>
</head>
<body onload="refresh();">
';
?>


