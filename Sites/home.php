<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css" >
</head>
<body>
<?php
  include 'index.php';
?>
<center>
<h1>HOME PAGE</h1>
</center>

<script>
$(document).ready(function () {
  $(".topnav a").removeClass("active");
  $('#home').addClass('active');

});
</script>
</body>
</html>
