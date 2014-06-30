<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Botangle Billing Page</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ebebf1;
            padding-top: 1em;
        }
        form {
            padding-top: 1em;
        }
        .btn-add {
            padding-right: 0;
        }
        .btn-warning {
            background-color: #f4790d;
        }
        .btn-cancel {
            padding-left: 0;
        }
        .btn-cancel button {
            background-color: #505257;
            color: white;
        }
    </style>
    <?php /* we need this loaded prior to any Stripe js stuff on the page */ ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<div id="main-content">
	<?php
	echo $this->fetch('content') ?>
</div>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>