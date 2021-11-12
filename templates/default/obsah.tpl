<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
	   <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
	   <meta name="robots" content="index,follow">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<title>{{!title!}}</title>
	</head>
	<body>
		<div id="vsechno">
			<div id="hlavicka">

			</div>
			<div id="menu">
			{{!menu!}}
			</div>
			<div id="siroky">
				<h1>{{!title!}}</h1>
				{{!olderPageLink!}}{{!newerPageLink!}}
				{{!uvodnik!}}
			</div>{{!content!}}
			<!-- konec siroky -->
			<!-- konec úzkého sloupce s menu -->
			<div id="paticka">
			<p>Created Timeshock Prague 2016</p>
		<!-- Piwik -->
		<script type="text/javascript">
		  var _paq = _paq || [];
		  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
		  _paq.push(["setCookieDomain", "*.www.restaurovani-bunkajosef.cz"]);
		  _paq.push(["setDomains", ["*.www.restaurovani-bunkajosef.cz"]]);
		  _paq.push(['trackPageView']);
		  _paq.push(['enableLinkTracking']);
		  (function() {
		    var u="//www.restaurovani-bunkajosef.cz/piwik/";
		    _paq.push(['setTrackerUrl', u+'piwik.php']);
		    _paq.push(['setSiteId', 1]);
		    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		  })();
		</script>
		<noscript>
			<p><img src="//www.restaurovani-bunkajosef.cz/piwik/piwik.php?idsite=1" style="border:0;" alt="piwik"></p>
		</noscript>
		<!-- End Piwik Code -->
		<!-- Piwik Image Tracker-->
		<img src="http://www.restaurovani-bunkajosef.cz/piwik/piwik.php?idsite=1&rec=1&action_name=Restaurovani" style="border:0" alt="Piwik">
		<!-- End Piwik -->
			</div>
		</div>
{{!dbcount!}}
	</body>
</html>