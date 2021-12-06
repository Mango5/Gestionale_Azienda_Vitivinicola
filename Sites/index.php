<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="style.css" >
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="topnav fixed-top ">
  <a id="home" class="tablinks " onclick="openTab(event, 'home')">Home</a>
  <a id="massaVino" class="tablinks" onclick="openTab(event, 'massaVino')">Vino</a>
  <a id="magazzino" class="tablinks" onclick="openTab(event, 'magazzino')">Magazzino</a>
  <a id="costi" class="tablinks hiddenTab" onclick="openTab(event, 'costi')">Costi</a>
  <a id="dettaglio" class="tablinks hiddenTab" onclick="openTab(event, 'subMassaVino')">Dettaglio</a>
</div>
<div id="includedContent"></div>

<script>
 
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
    window.location.href = tabName + ".php";
    //$("#includedContent").load(tabName + ".php");
}
</script>
</body>
</html>
