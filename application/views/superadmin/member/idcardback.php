<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Id</title>
<style>
@font-face {
    font-family: myFirstFont;
    src: url(FtraBk_1.ttf);
}

.logo {
	background: url(progle_logo.png) center center no-repeat;
	width: 166px;
	height: 27px;
	background-size: 70%;
	margin: 1px auto;
	
}
body {
	font-size: 8.9px;
	letter-spacing: -0.1px !important;
	
}
.wrapper {
	width: 259px;
	height: 151px;
	margin: 0 auto;
	font-family: myFirstFont;
	background-color: #fefefel;
	background: url(bg.png) 102% -3% no-repeat;/* border:1px solid #CCC*/
}
.header {
	height: 17px;
	background: #952f45;
	background: -moz-linear-gradient(left, #86A366 0%, #86A366 100%);
	background: -webkit-gradient(left top, right top, color-stop(0%, #86A366), color-stop(100%, #86A366));
	background: -webkit-linear-gradient(left, #86A366 0%, #86A366 100%);
	background: -o-linear-gradient(left, #86A366 0%, #86A366 100%);
	background: -ms-linear-gradient(left, #86A366 0%, #86A366 100%);
	background: linear-gradient(to right, #86A366 0%, #86A366 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#86A366', endColorstr='#86A366', GradientType=1 );
}
.header h3 {
	color: #fff;
	text-transform: uppercase;
	margin: 0px;
	padding: 0px;
	text-align: center;
	font-size: 9px;
	color: #FFF;
	line-height: 15px;
	font-weight: lighter
}
.clr {
	clear: both
}
.list {
	text-align: justify;
	font-size:9px;
	line-height: 8px;
}
.list ul {
	margin: 0px;
	padding: 0px 0px 0px 10px
}
.list li { list-style-image:url(list.png)
}
.card-hldr {
	width: 231px;
	height: 25px;
	border: 1px solid #CCC;
	border-radius: 6px;
	margin: 5px auto 0 
}
.card-hldr h5 {
	text-align: right;
	margin: 0px;
	padding: 15px 5px 0px 0px;
	font-weight: lighter;
	font-size: 8px;
}
.adrs p {
	margin: 0px;
	text-align: center;
	font-size: 6.2px;
	line-height: 9px;
}
</style>
</head>

<body>
<div class="wrapper">
  <div class="list">
    <ul>
      <li>This card is to certify that the bearer, whose name appears on the front of this card, is an independent Business Associate of <?php echo WEBSITE; ?>. and is committed to comply with the Rules & Regulations as published in brochures and <?php echo WEBSITE; ?> website, whichever is latest, will govern all issues.</li>
      <li>Validity of this card is subject to continuity of Consultant ship. This card is non - transferable. </li>
    </ul>
  </div>
  <div class="card-hldr">
    <h5>Card Holder's Signature</h5>
  </div>
  <div class="logo"></div>
  <div class="adrs">
    <p>Head Office: Near Suwan Puliya Anand Bagh Balrampur 271201 Ph. : +91 7518988056 E-mail : info@growonlineshop.in </p>
  </div>
  <div class="header">
    <h3><?php echo WEBSITE; ?></h3>
  </div>
</div>
</body>
</html>