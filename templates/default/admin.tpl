
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
	<head>
		<title>{{!title!}}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="content-language" content="cs-CZ">
		<meta name="copyright" content="(c)Kitikara">
		<meta http-equiv='Expires' content='-1'>
		<meta name='robots' content='index,follow'>
		<meta name='googlebot' content='index,follow,snippet,archive'>
		<!-- autor redakčního systému: krteczek -->
		<meta name="author" content="Uzivatel" >
		<meta name="description" content="Stránky pro fanoušky  SciFi a Fantasy literatury">
		<meta name="keywords" content="scifi, sci fi, sci-fi, fantasy, literatura, vlastní tvorba, básně, básničky, hvězdná brána, star gate, O'neil, STAR GATE SG-1, STAR GATE SG-4, Goa´uld">
		<link rel="stylesheet" href="./css/layout-styl-krt.css" type="text/css" >

                <script type="text/javascript" src="./javascript/editor.js"></script>

		<script type="text/javascript">
/* schová a zobrazí po kliknutí jednotlivé části menu */
function show_hide(el) {
    if (/(^| )hidden-js( |$)/.test(el.className)) {
        el.className = el.className.replace(/(^| )hidden-js( |$)/, '$2');
    } else {
        el.className += ' hidden-js';
    }
    return true;
}
document.write('<style>.hidden-js { display: none; }<' + '/style>');
</script>
{{!js!}}
	</head>
	<body>


		<div id="vsechno">
			<div id="hlavicka">
				<h1><span>
		www.kitikara.wz.cz/Zobrazit komentáře komentář</span></h1>
				<a id="navrat" href="./" title="Zpět na úvodní stránku tohoto webu" target="_blank"><span>Web Kitikary, fanynky SCI-FI a FANTASY</span></a>
			</div>
			<div id="kontejnervelky">

					<div id="prostrednisloupecadmin">
						<div class="clanky">


							<h2>{{!title!}}</h2><br>

{{!content!}}

						</div><!-- konec clanky -->
					</div><!-- konec prostredního širokého divu -->
					<div id="levysloupec"><!-- začátek levého menu -->
						<div class="navigace">

	<div class="kategorie">
		<div class="sekce">
			<div class="cla">
				<a href="./" title="Návrat na úvodní stránku ">Úvodní stránka</a>
			</div>
			<div class="cla">
				{{!logOut!}}
			</div>
		</div>
	</div>
{{!navigace!}}


						</div>
					</div><!-- konec levého menu -->
					<div class="patkamala"></div>

				<div class="patkamala"></div>
			</div><!-- konec kontejnervelky -->

			<div id="patkavelka"><p>&copy;Kitikara 2004 - 2011</p>
			{{!dbcount!}}

			</div>
		</div><!-- konec vse -->
	</body>
</html>
