<h1>MOMENTÁLNĚ NEPOUŽITO</h1>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>{{!title!}}</title>
		<meta name="description" content="{{!description!}}">
		<meta name="keywords" content="{{!keywords!}}">
		<meta http-equiv="Content-Language" content="{{!lang!}}">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="copyright" content="© 2006 Motil">
		<link rel="stylesheet" type="text/css" href="{{!WWWROOT!}}design.css">
		<link rel="favico icon" type="image/gif" href="{{!WWWROOT!}}icon.gif">
		<!-- load JQuery mbtooltip -->
		<script type="text/javascript" src="{{!WWWROOT!}}texyla/jquery/jquery.js"></script>
		<script type="text/javascript" src="{{!WWWROOT!}}texyla/jquery/jquery.timers.js"></script>
		<script type="text/javascript" src="{{!WWWROOT!}}texyla/jquery/jquery.dropshadow.js"></script>
		<script type="text/javascript" src="{{!WWWROOT!}}texyla/jquery/mbTooltip.js"></script>
		<link rel="stylesheet" type="text/css" href="{{!WWWROOT!}}texyla/jquery/css/mbTooltip.css" title="style1"  media="screen">

		<script>
			$(function(){
				$("[title]").mbTooltip({ // also $([domElement])..mbTooltip  >>  in this case only children element are involved
					opacity : .97,       //opacity
					wait:200,//1200,           //before show
					cssClass:"default",  // default = default
					timePerWord:100,      //time to show in milliseconds per word
					hasArrow:false,			// if you whant a little arrow on the corner
					imgPath:"{{!WWWROOT!}}texyla/jquery/images/",
					ancor:"mouse", //"parent" "mouse" you can ancor the tooltip to the mouse position or at the bottom of the element
					shadowColor:"white" //the color of the shadow
				});
			})
		</script>
	</head>
	<body>
		<div id="hlavnydiv">
		<div id="hlavicka_{{!lang!}}">
				<a id="odkaz_hlavicka" href="{{!WWWROOT!}}{{!lang!}}/"></a>
				<menu id="menu_hlavicka">
				{{!pagesLinkTop!}}
				</menu>
			</div>
			<p id="logo">
				<a class="en" href="{{!WWWROOT!}}en/{{!langLinkEn!}}" title="Egnlish version"><span>EN</span></a>
				<a class="d" href="{{!WWWROOT!}}de/{{!langLinkDe!}}" title="Deutche Version"><span>D</span></a>
				<a class="sk" href="{{!WWWROOT!}}sk/{{!langLinkSk!}}" title="Slovenská verzia"><span>SK</span></a>
			</p>
			<h1>{{!title!}}</h1>
			{{!content!}}
			<div id="pata">
<!--[if !IE]> -->
<object type="application/x-shockwave-flash"
  data="{{!WWWROOT!}}image/flash_bottom_{{!lang!}}.swf" width="850" height="70">
<!-- <![endif]-->

<!--[if IE]>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="850" height="70" id="flash_bottom_1" align="middle">
  <param name="movie" value="{{!WWWROOT!}}image/flash_bottom_{{!lang!}}.swf">
<!--><!--dgx-->
  <param name="loop" value="true">
  <param name="menu" value="false">
  <img src="{{!WWWROOT!}}image/nahrada_flashe_bottom.png"  width="850" height="70">
</object>
<!-- <![endif]-->
			</div>
  			<ul id="odkazy_spodne">
  				{{!pagesLinkBottom!}}
  			</ul>
			<div class="clearleft"></div>
		</div>
		<p id="odkaz">© Ing.arch.Robert Motil ČŽr:103-10050&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Design by <a href="http://www.pkdesign.sk" title="Tvorba web stránok, optimalizácia pre vyhľadávače">PK Design - tvorba web stránok, otimalizácia pre vyhľadávače</a></p>

<!--počítadlo prístupov-->
<a href="http://www.toplist.cz/">
	<script language="JavaScript" type="text/javascript">
		<!--
			document.write('<img src="http://toplist.cz/dot.asp?id=265775&http=\'+escape(document.referrer)+\'&wi=\'+escape(window.screen.width)+\'&he=\'+escape(window.screen.height)+\'&cd=\'+escape(window.screen.colorDepth)+\'&t=\'+escape(document.title)+\'" width="1" height="1" border=0 alt="TOPlist">');
		//-->
	</script>
</a>
<noscript>
	<a href="http://www.toplist.cz/"><img src="http://toplist.cz/dot.asp?id=265775" border="0" alt="TOPlist" width="1" height="1"></a>
</noscript>

{{!countQuery!}}{{!cas!}}
	</body>
</html>
