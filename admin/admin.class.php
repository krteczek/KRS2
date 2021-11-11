<?php

/*
 * @author krteczek
 * Administrace systému
 * Last edit: 27.6.2011 14:23
 */

class admin {
    /*
     * zda je povinný obsah článků, někdy prostě jen potřebujeme uvodník, krátký článeček
     * @var bolean defalt false
     */

    public $mandatoryClanek = false;

    /*
     * nastavení názvů proměnných pro veřejnou část webu
     */
    public $clanek = 'c';
    public $sekce = 's';
    public $kategorie = 'k';
    public $file_path_menu = 'cache/menu/menu.tpl';
    public $file_path_pages = 'cache/pages/';
    public $file_path_uvod = 'cache/uvod/uvodni-clanek.serialized.tpl';
    public $file_path_uvod_config = 'cache/uvod/config.serialized.tpl';
    public $file_path_news = 'cache/menu/news.tpl';


    /*
     * html menu administrace
     */
    private $menu = '<div class="kategorie"> <div class="sekce"> <div class="cla"> <a href="admin.php"	title="úvodní	stránka	admina">Úvodní	stránka	admina</a> </div> </div> </div> <div class="kategorie"><h3>Články</h3> <div class="sekce"> <div class="cla"> <a href="admin.php?clanek=clanky&amp;naz=uprav-clanek">Uprav	článek</a> <a href="admin.php?clanek=clanky&amp;naz=napis-clanek">Napiš článek</a> </div> </div> </div> <div class="kategorie"><h3>sekce</h3> <div class="sekce"> <div class="cla"> <a href="admin.php?clanek=sekce&amp;naz=uprav-sekci">Úprava sekcí</a> <a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci">Vytvoř sekci</a> </div> </div> </div> <div class="kategorie"><h3>kategorie</h3> <div class="sekce"> <div class="cla"> <a href="admin.php?clanek=kategorie&amp;naz=uprav-kategorii">Uprav kategorii</a> <a href="admin.php?clanek=kategorie&amp;naz=vytvor-kategorii">Vytvoř kategorii</a> </div> </div> </div> <div class="kategorie"><h3>kniha návštěv &amp; komentáře k článkům</h3> <div class="sekce"> <div class="cla"> <a href="admin.php?clanek=kniha&amp;naz=uprav-knihu">Úprava příspěvků v knize návštěv</a> <a href="admin.php?clanek=komentare&amp;naz=uprav-komentare">smazání příspěvků v komentářích</a> </div> </div> </div> <div class="kategorie"><h3>statistika</h3> <div class="sekce"> <div class="cla"> <a href="admin.php?clanek=statistika&amp;naz=statistika-clanku">Počet zobrazení jednotlivých článků</a> </div> </div> </div> ';
    private $texylaJS = '

	<link rel="stylesheet" href="./texyla/texyla/css/style.css" type="text/css">
	<link rel="stylesheet" href="./texyla/themes/default/theme.css" type="text/css">

	<!-- jQuery -->
	<script src="./javascript/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="./texyla/themes/default/jquery-ui.css" type="text/css">

	<!-- Texyla core -->

	<script src="./texyla/texyla/js/texyla.min.js" type="text/javascript"></script>

	<!-- plugins -->
	<script src="./texyla/texyla/plugins/texyla.plugins.js" type="text/javascript"></script>

	<!-- init -->
	<script type="text/javascript">
		//<![CDATA[

		$.texyla.setDefaults({
			texyCfg: "admin",
			baseDir: "./texyla/texyla",
			previewPath: "admin.php?clanek=clanky&naz=texyla",//"preview.php",
			bottomRightPreviewToolbar: ["syntax"],
			buttonType: "button",
			bottomLeftToolbar: ["edit", "preview", "htmlPreview"],
			emoticonPath: "./texyla/emoticons/texy/%var%.gif"
		});

		$(function () {

			$.texyla({
				toolbar: [
					"h1", "h2", "h3", "h4",
					null,
					"bold", "italic","del",
					null,
					"center", ["left", "right", "justify"],
					null,
					"ul", "ol", ["olAlphabetSmall", "olAlphabetBig", "olRomans", "olRomansSmall"],
					null,
					{type: "label", text: "Vložit"}, "link", "img", "table", "emoticon", "symbol",
					null,
					"color", "textTransform",
					null,
					"youtube",
					null,
					"div", ["html", "blockquote", "text", "comment"],
					null,
					{type: "label", text: "Ostatní"}, ["sup", "sub", "del", "acronym", "hr", "notexy", "web"]

				],
				texyCfg: "admin",
				tabs: false
			})

		});
		//]]>
	</script>
';
    public $logged = false;

    public function __construct() {
	global $dbs;
	$this->dbs = $dbs;
    }

    /*
     * Hlavní rozhodovací metoda
     * @return array()
     */

    public function run() {
	$out['navigace'] = $this->getAdminMenu();
	$o = array();
	if (!empty($_GET['clanek'])) {

	    if (!empty($_GET['naz'])) {
		switch ($_GET['clanek']) {
		    case 'clanky':
			switch ($_GET['naz']) {
			    case 'napis-clanek':
				$o = $this->clanekVytvor();
				break;
			    case 'napis-clanek-ukaz':
				$o = $this->clanekUkaz();
				break;
			    case 'uprav-clanek':
				$o = $this->clanekVypis();
				break;
			    case 'nastav-zobrazeni-clanku':
				$o = $this->clanekNastavZobrazeni();
				break;
			    case 'texyla':
				return $this->clanekTexyla();
				break;
			    case 'uprav-clanek-uvod':
				$o = $this->clanekUvodEdit();
				break;
			    case 'uprav-clanek-uvod-ukaz':
				$o = $this->clanekUvodUkaz();
				break;
			}
			$this->menuGenerovat();

			break;
		    case 'sekce':
			switch ($_GET['naz']) {
			    case 'uprav-sekci':
				$o = $this->sekceVypis();
				break;
			    case 'vytvor-sekci':
				$o = $this->sekceVytvor();
				break;
			    case 'vytvor-sekci-ukaz':
				$o = $this->sekceUkaz();
				break;
			    case 'nastav-zobrazeni-sekce':// OK
				$o = $this->sekceNastavZobrazeni();
				break;
			}
			$this->menuGenerovat();
			break;
		    case 'kategorie':
			switch ($_GET['naz']) {
			    case 'uprav-kategorii':
				$o = $this->kategorieVypis();
				break;
			    case 'vytvor-kategorii':
				$o = $this->kategorieVytvor();
				break;
			    case 'vytvor-kategorii-ukaz':
				$o = $this->kategorieUkaz();
				break;
			    case 'nastav-zobrazeni-kategorie':// OK
				$o = $this->kategorieNastavZobrazeni();
				break;
			}
			$this->menuGenerovat();
			break;
		    case 'kniha':
			switch ($_GET['naz']) {
			    case 'uprav-knihu':
				$o = $this->knihaVypis();
				break;
			    case 'nastav-zobrazeni':
				$o = $this->knihaNastavZobrazeni();
				break;
			    case 'smazat':
				$o = $this->knihaSmazatKomentar();
				break;
			}
			break;
		    case 'komentare':
			switch ($_GET['naz']) {
			    case 'uprav-komentare':
				$o = $this->komentareVypis();
				break;
			    case 'nastav-zobrazeni':
				$o = $this->komentareNastavZobrazeni();
				break;
			    case 'smazat':
				$o = $this->komentareSmazat();
				break;
			}

			break;
		    case 'statistika':
			switch ($_GET['naz']) {
			    case 'statistika-clanku':
				$o = $this->statistikaClanku();
				break;
			}
			break;
		}
	    }
	} else if (empty($_SERVER['QUERY_STRING'])) {
	    $o = $this->clanekAdminUvod();
	}
	if (empty($o)) {
	    $o = $this->notfound();
	}
	$out = array_merge($out, $o);

	return $out;
    }

    /*
     * Vrátí jen admin menu
     */

    private function getAdminMenu() {
	return $this->menu;
    }

    /*
     * Statistika je zlikvidována na jednu jedinou metodu,
     * která vypíše všechny články v databázi a počet
     * jejich zobrazení a odkazy na web a admin stránku pro přečtení
     */

    private function statistikaClanku() {
	$tpl = new tpl;
	$out['title'] = 'Statistika zobrazení článků';
	$out['content'] = <<< EEE
<p>Zde vidíte statistiku čtenosti článků od nejčtenějších po méně čtené.</p><p>Je to jen jednoduché počítadlo, které nebere v potaz jak dlouho je článek publikován.</p>

EEE;
	$table = <<< EEE
<table style="border:1px solid black;margin: 10px auto;">
    <tr style="border:1px solid black"><th style="width:50px;">počet zobrazení</th><th style="width:250px;">název článku</th><th>akce</th></tr>
    {{!rows!}}
</table>
EEE;

	$row = '<tr><td>{{!pocitadlo!}}</td><td>{{!nazev!}}</td><td>    <a href="admin.php?clanek=clanky&naz=napis-clanek-ukaz&id={{!id!}}" title="Zobrazit tento článek v Administraci"  target="_blank"><img src="icons/reada.png" width="16" height="16" alt="zobrazit článek"></a>     <a href="index.php?' . $this->sekce . '={{!sekce!}}&amp;' . $this->clanek . '={{!id!}}" title="Zobrazit tento článek na webu"  target="_blank"><img src="icons/readw.png" width="16" height="16" alt="zobrazit článek"></a></td></tr>';


	$v = $this->dbs->s("select id, sekce, nazev, pocitadlo from " . Tabulka_clanky . "  order by pocitadlo desc");
//echo $this->dbs->getLastQuery();
	if (is_array($v)) {
	    $o = '';
	    foreach ($v as $f) {
		$tpl->content = $f;
		$o .= $tpl->parseTpl($row);
	    }
	    $tpl->content = array('rows' => $o);
	    $o = $tpl->parseTpl($table);
	} else {
	    $o .= $v; // . $dbs->getLastQuery();
	}
	$out['content'] .= $o;
	return $out;
    }

    /*
     * Metoda smaže Komentář k článku
     */

    private function komentareSmazat() {


	$out['title'] = 'Smazání komentáře';
	$out['content'] = '<p>Tato stránka maže komentáře z databáze. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě.</p>';

	if (!empty($_GET["id"])) {
	    $v = $this->dbs->d("DELETE FROM " . Tabulka_komentare . " WHERE `id` = " . $this->dbs->o($_GET["id"]) . " LIMIT 1");
	    if (is_bool($v)) {
		header('location: admin.php?clanek=komentare&naz=uprav-komentare');
		exit;
	    } else {
		$out['content'] .= $v;
	    }
//echo $this->dbs->getLastQuery();
	}
	return $out;
    }

    /*
     * Metoda mění v databázi ve sloupci zobrazovat 1 <> 0
     */

    private function komentareNastavZobrazeni() {

	$out['title'] = 'Nastavení zobrazení komentáře';
	$out['content'] = '<p>Tato stránka mění nastavení zobrazení jednotlivých příspěvků v komentářích u článků. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě.</p>';
	if (!empty($_GET["id"])) {
	    // smažeme soubor v cache
	    $this->delPageFromCacheByCommentId($_GET["id"]);

	    $v = $this->dbs->u(Tabulka_komentare, ' `zobrazovat` = NOT(`zobrazovat`) ', ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
	    if (is_bool($v)) {
		header('location: admin.php?clanek=komentare&naz=uprav-komentare');
		exit;
	    } else {
		$out['content'] .= $v;
	    }
	}
//echo $dbs->getLastQuery();
	return $out;
    }

    /*
     * Metoda vypíše všechny komentáře v databázi od nejnovějšího k nejstaršímu a
     * zobrazí odkazy pro jejich schování, zobrazení a nebo smazání,
     */

    private function komentareVypis() {

	$tpl = new tpl;
	$out['title'] = 'Úprava komentářů ke článkům';
	$o = '';
	$out['js'] = <<< EEE
<script type="text/javascript">
function vystraha(id) {
    var answer = confirm("Opravdu chcete smazat tento záznam?");
    if (answer){
	window.location = "admin.php?clanek=komentare&naz=smazat&id=" + id;
    } else{
	//alert("Thanks for sticking around!")
    }
}
</script>
EEE;

	$out['content'] = <<<EEE
<p>Zde můžete schovávat a mazat komentáře u článků. Slouží k tomu odkazy v podobě ikonek.</p>
<ul>
    <li><img src="icons/yes.png"> Povolí komentář</li>
    <li><img src="icons/not.png"> Zakáže komentář</li>
    <li><img src="icons/delete.png"> Smaže komentář</li>
</ul>
EEE;

	$form = <<< EEE
<fieldset>
    <legend>
	<a href="admin.php?clanek=komentare&amp;naz=nastav-zobrazeni&amp;id={{!id!}}" title="{{!zobrazovat!}}"><img src="icons/{{!ico!}}.png" width="16" height="16"></a> {{!delete!}} | <{{!format!}}>{{!titulek!}}</{{!format!}}></legend>
    <div class="{{!class!}}">
<p>jmeno: {{!jmeno!}} | <a href="mailto: {{!email!}}" title=" upravená umailová adresa"> email</a>  | <span class="drobne"> datum: {{!cas!}}</span></p>

{{!komentar!}}
    </div>
</fieldset>

EEE;

	$v = $this->dbs->s("SELECT  id, jmeno, email, web, ip_adr, komentar, DATE_FORMAT(" . Tabulka_komentare . ".cas, '%e.%b %Y  %H:%i:%s') AS cas, titulek, zobrazovat FROM " . Tabulka_komentare . " ORDER BY id desc");
	if (is_array($v)) {

	    foreach ($v as $f1) {
		if ($f1['zobrazovat'] == 1) {
		    $f1['zobrazovat'] = 'Zakázat';
		    $f1['format'] = 'b';
		    $f1['ico'] = 'not';
		    $f1['class'] = '';
		    $f1['delete'] = '';
		} else {
		    $f1['zobrazovat'] = 'Povolit';
		    $f1['format'] = 's';
		    $f1['ico'] = 'yes';
		    $f1['class'] = 'hide';
		    $f1['delete'] = '<a href="#" title="Smazat tuto zprávu" onclick="vystraha(\'' . $f1['id'] . '\');"><img src="icons/delete.png" width="16" height="16"></a>';
		}
		$tpl->content = $f1;
		$o .= $tpl->parseTpl($form);
	    }
	} else {
	    $o .= $v; // . $dbs->getLastQuery();
	}
	$out['content'] .= '<div class="novinky">' . $o . '</div>';
	return $out;
    }

    /*
     * Metoda maže jednotlivé příspěvky v knize návštěv
     */

    private function knihaSmazatKomentar() {


	$out['title'] = 'Smazání komentáře z knihy';
	$out['content'] = '<p>Tato stránka maže komentáře z databáze. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě.</p>';

	if (!empty($_GET["id"])) {
	    $v = $this->dbs->d("DELETE FROM " . Tabulka_kniha . " WHERE `id` = " . $this->dbs->o($_GET["id"]) . " LIMIT 1");
	    if (is_bool($v)) {
		header('location: admin.php?clanek=kniha&naz=uprav-knihu');
		exit;
	    } else {
		$out['content'] .= $v;
	    }
//echo $dbs->getLastQuery();
	}
	return $out;
    }

    /*
     * Metoda mění hodnotu ve sloupci zobrazovat 1 <> 0 pro jednotlivé příspěvky knihy
     */

    private function knihaNastavZobrazeni() {


	$out['title'] = 'Nastavení zobrazení knihy';
	$out['content'] = '<p>Tato stránka mění nastavení zobrazení jednotlivých příspěvků v knize návštěv. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě.</p>';

	if (!empty($_GET["id"])) {
	    $v = $this->dbs->u(Tabulka_kniha, ' `zobrazovat` = NOT(`zobrazovat`) ', ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
	    if (is_bool($v)) {
		header('location: admin.php?clanek=kniha&naz=uprav-knihu');
		exit;
	    } else {
		$out['content'] .= $v;
	    }
//echo $dbs->getLastQuery();
	}
	return $out;
    }

    /*
     * metoda vypíše všechny komentáře v knize návštěv a zobrazí odkazy na schování zobrazení a smazání příspěvků
     */

    private function knihaVypis() {


	$out['title'] = 'Úprava komentářů v knize návštěv';
	$out['content'] = <<< EEE
<p>Můžete schovávat a zobrazovat komentáře v knize návštěv, nejnovější jsou první, nejstarší poslední (na konci stránky)</p>
<ul>
    <li><img src="icons/yes.png"> Povolí komentář</li>
    <li><img src="icons/not.png"> Zakáže komentář</li>
    <li><img src="icons/delete.png"> Smaže komentář</li>
</ul>

EEE;
	$tpl = new tpl;
	$out['js'] = <<<EEE
<script type="text/javascript">
function vystraha(id) {
    var answer = confirm("Opravdu chcete smazat tento záznam?");
    if (answer){
	window.location = "admin.php?clanek=kniha&naz=smazat&id=" + id;
    } else{
	//alert("Thanks for sticking around!")
    }
}
</script>
EEE;

	$form = <<< EEE
<fieldset>
    <legend><a href="admin.php?clanek=kniha&amp;naz=nastav-zobrazeni&amp;id={{!id!}}" title="{{!zobrazovat!}}"><img src="icons/{{!ico!}}.png" width="16" height="16"></a> {{!delete!}} | <{{!format!}}>{{!titulek!}}</{{!format!}}></legend>
    <div class="{{!class!}}">
<p>jmeno: {{!jmeno!}} | <a href="mailto: {{!email!}}" title=" upravená umailová adresa"> email</a>  | <span class="drobne"> datum: {{!cas!}}</span></p>

{{!komentar!}}
    </div>
</fieldset>

EEE;

	$o = '';
	$v = $this->dbs->s("select id, jmeno, email, komentar, DATE_FORMAT(" . Tabulka_kniha . ".cas, '%e.%b %Y  %H:%i:%s') as cas, titulek, zobrazovat from " . Tabulka_kniha . " order by id desc ");
	if (is_array($v)) {
	    foreach ($v as $f1) {
		if ($f1['zobrazovat'] == 1) {
		    $f1['zobrazovat'] = 'Zakázat';
		    $f1['format'] = 'b';
		    $f1['ico'] = 'not';
		    $f1['class'] = '';
		    $f1['delete'] = '';
		} else {
		    $f1['zobrazovat'] = 'Povolit';
		    $f1['format'] = 's';
		    $f1['ico'] = 'yes';
		    $f1['class'] = 'hide';
		    $f1['delete'] = '<a href="#" title="Smazat tuto zprávu" onclick="vystraha(\'' . $f1['id'] . '\');"><img src="icons/delete.png" width="16" height="16"></a>';
		}
		$tpl->content = $f1;
		$o .= $tpl->parseTpl($form);
	    }
	} else {
	    $o .= $v; // . $dbs->getLastQuery();
	}
	$out['content'] .= '<div class="novinky">' . $o . '</div>';
	return $out;
    }

    /*
     * Script mění v tabulce pro Kategorie hodnotu zobrazovat na opačnou 1 <> 0
     */

    private function kategorieNastavZobrazeni() {
	$err = 0;
	$this->dbs->debug = true;
	$out['title'] = 'Nastavení zobrazení kategorie článků';
	$out['content'] = '<p>Tato stránka mění nastavení zobrazení jednotlivých článků. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě, nebo není nastaveno id.</p>';

	if (!empty($_GET["id"])) {
	    $v = $this->dbs->u(Tabulka_kategorie, ' `zobrazovat` = NOT(`zobrazovat`) ', ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
	    if (is_bool($v)) {
// nastavíme blokování na základě hodnoty zobrazovat u kategorie pro sekci i pro články
		$v0 = $this->dbs->s("SELECT `zobrazovat` FROM " . Tabulka_kategorie . " WHERE `id` = " . $this->dbs->o($_GET["id"]));
		if (is_array($v0)) {
		    $set = $v0[0]['zobrazovat'] == 1 ? 1 : 0;
		    $v1 = $this->dbs->u(Tabulka_sekce, ' `blokall` = ' . $set . ' ', ' WHERE `kategorie` = ' . $this->dbs->o($_GET["id"]));

// zjistíme které sekce to jsou a nastavíme i články uvnitř
		    $v2 = $this->dbs->s("SELECT id FROM " . Tabulka_sekce . " WHERE `kategorie` = " . $this->dbs->o($_GET["id"]));
		    if (is_array($v2)) {
			foreach ($v2 as $f2) {
			    $v3 = $this->dbs->u(Tabulka_clanky, ' `blokall` = ' . $set . ' ', ' WHERE `sekce` = ' . $f2["id"]);
			    if (!is_bool($v3)) {
				$out['content'] .= $this->dbs->getLastQuery() . '<p>' . $v3 . '</p>';
				$err = 1;
			    }
			}
		    } else if (is_bool($v2)) {
			// je to ok, nemá žádné sekce
		    } else {
			$out['content'] .= $this->dbs->getLastQuery() . '<p>' . $v2 . '</p>';
			$err = 1;
		    }
		} else {
		    $out['content'] .= $this->dbs->getLastQuery() . '<p>' . $v0 . '</p>';
		    $err = 1;
		}
	    } else {
		$out['content'] .= $this->dbs->getLastQuery() . '<p>' . $v3 . '</p>';
		$err = 1;
	    }
	} else {
	    $err = 1;
	}
	if ($err == 0) {
	    header('location: admin.php?clanek=kategorie&naz=uprav-kategorii');
	    exit;
	}
	return $out;
    }

    /*
     * zobrazí právě editovanou kategorii
     */

    private function kategorieUkaz() {

	$out['title'] = 'Náhled kategorie článků';
	$out['content'] = '';
	$txt = <<< EEE

<p>Kategorii článků <strong>{{!nazev!}}</strong> se podařilo úspěšně uložit na server.</p>

<p>Pokud byste chtěli, <a href="admin.php?clanek=kategorie&amp;naz=vytvor-kategorii&amp;upravit=ano&amp;id={{!id!}}">můžete upravit tuto kategorii článků</a><p>

EEE;

//           "select nazev, clanek, sekce, uvodnik from ".Tabulka_clanky." where id='{$cislo_id}'";

	$v = $this->dbs->s("SELECT id, nazev FROM " . Tabulka_kategorie . " WHERE id = " . $this->dbs->o($_GET['id']));
	if (is_array($v)) {

	    $var = $v[0];
	    $tpl = new tpl;
	    $tpl->content = $var;
	    $out['content'] .= $tpl->parseTpl($txt);

	    return $out;
	}
	$out = $this->notfound();
//$out['content'] .= $v . $dbs->getLastQuery();
	return $out;
    }

    /*
     * Metoda pro vytvoření katagorii
     */

    private function kategorieVytvor() {

	$tpl = new tpl;

	$var['adresa'] = $_SERVER['QUERY_STRING'];
	$var['nazev'] = '';
	$out['title'] = 'Vytvoření kategorie článků';

	$err = 0;

	$form = <<< EEE
<form name="pridej" method="post" action="admin.php?{{!adresa!}}">
    {{!errnazev!}}
    <label>Název kategorie, používejte prosím češtinu s diakritikou:<br>
    <input type="text" name="nazev" size="40" maxlength="40" value="{{!nazev!}}"><label><br><br>
    <input  name="uloz" value="Uložit" type="submit"  style="color:#ff0000;"><br>
</form>
EEE;
	if (!empty($_POST['uloz'])) {
	    if (!empty($_POST['nazev'])) {
		$var['nazev'] = strip_tags($_POST['nazev']);
	    }
	    if (empty($var['nazev'])) {
		$var['errnazev'] = '<p class="err">Nevyplnili jste název kategorie, prosím vyplňte ho.</p>';
		$err = 1;
	    }
	    if ($err == 0) {
		if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
		    $v = $this->dbs->u(Tabulka_kategorie, array(
//`id`, `nazev`, `zobrazovat`
				'nazev' => $var['nazev'],
				    ),
				    'WHERE `id` = ' . $this->dbs->o($_GET['id'])
		    );
		    if (is_bool($v)) {
			header("location: admin.php?clanek=kategorie&naz=vytvor-kategorii-ukaz&id=" . $_GET['id']);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		} else {
////`id`, `nazev`, `zobrazovat`
		    $v = $this->dbs->i(Tabulka_kategorie, array(
				'nazev' => $var['nazev'],
				    )
		    );
		    if (is_int($v)) {
			header("location: admin.php?clanek=kategorie&naz=vytvor-kategorii-ukaz&id=" . $v);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		}
	    }
	}

	if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
// nazev,sekce,clanek,zobrazovat,uvodnik,datum
	    $v = $this->dbs->s("SELECT `id`, `nazev` FROM " . Tabulka_kategorie . " WHERE `id` = " . $this->dbs->o($_GET['id']));
	    if (is_array($v)) {
		$var['nazev'] = $v[0]['nazev'];
	    } else {
		return array('title' => 'Napiš článek', 'content' => '<p class="err">Litujeme, požadovaná kategorie neexistuje, chcete-li, <a href="admin.php?clanek=kategorie&amp;naz=vytvor-kategorii" title="Vytvořit novou kategorii článků...">můžete vytvořit novou</a></p> ');
	    }
	}
	$tpl->content = $var;
	$out['content'] = $tpl->parseTpl($form);
	return $out;
    }

    /*
     * Metoda pro vypsání kategorii článků s odkazy na jejich editaci, schování/zobrazení a ukázání
     */

    private function kategorieVypis() {

	$tpl = new tpl;
	$form = <<< EEE
<li>
    <a href="admin.php?clanek=kategorie&amp;naz=vytvor-kategorii&amp;upravit=ano&amp;id={{!id!}}" title="Upravit tuto kategorii"><img src="icons/edit.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=kategorie&naz=vytvor-kategorii-ukaz&id={{!id!}}" title="Zobrazit tuto kategorii v Administraci"  target="_blank"><img src="icons/reada.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=kategorie&amp;naz=nastav-zobrazeni-kategorie&amp;id={{!id!}}" title="{{!zobrazovat!}} zobrazení kategorie článku"><img src="icons/{{!ico!}}.png" width="16" height="16"></a>
    <a href="index.php?{{!katName!}}={{!id!}}" title="Zobrazit tuto kategorii článků na webu"  target="_blank"><img src="icons/readw.png" width="16" height="16" alt="zobrazit článek"></a>
    <{{!format!}}>{{!nazev!}}</{{!format!}}>
</li>
EEE;

	$out['title'] = 'Úprava kategorií článků';
	$out['content'] = <<<EEE
<p>Zde je hlavní rozhraní pro práci se kategoriemi článků. Proti původní verzi je zjednodušeno, zrušil jsem mazání článků,
vlastně stačí kategorii nastavit jako nezobrazovanou. Má to výhodu v tom, že ani omylem nemůžete článek smazat ;)</p>
<ul>
    <li><img src="icons/edit.png">Upravit článek</li>
    <li><img src="icons/yes.png"> Povolit článek</li>
    <li><img src="icons/not.png"> Zakázat článek</li>
    <li><img src="icons/reada.png">Číst článek v Administraci</li>
    <li><img src="icons/readw.png">Číst článek na Webu</li>
    <li><span class="hidden">Takto označené</span> jsou blokované o úroveň výš (sekce, kategorie), znamená to, že celá kategorie či sekce je na veřejném webu nedostupná..</li>
</ul><br>
EEE;
	$o = '';
	$v1 = $this->dbs->s("SELECT `id`, `nazev`, `zobrazovat` FROM " . Tabulka_kategorie . " ORDER BY `nazev`");
	if (is_array($v1)) {
	    $o .= "\n<ul>";

	    foreach ($v1 as $f1) {
		$f1['katName'] = $this->kategorie;
		if ($f1['zobrazovat'] == 1) {
		    $f1['zobrazovat'] = 'Zakázat';
		    $f1['format'] = 'b';
		    $f1['ico'] = 'not';
		} else {
		    $f1['zobrazovat'] = 'Povolit';
		    $f1['format'] = 's';
		    $f1['ico'] = 'yes';
		}
		$tpl->content = $f1;
		$o .= $tpl->parseTpl($form);
	    }
	    $o .= "\n</ul>";
	} else if (is_bool($v1)) {
	    $o .= $this->dbs->errMsg;
	} else {
	    $o .= $v1; // . $dbs->getLastQuery();
	}
	$out['content'] .= $o;
	return $out;
    }

    /*
     * Funkce vygeneruje seznam sekcí a pomocí odkazů budeme měnit jejich vlastnosti
     */

    private function sekceVypis() {
	$tpl = new tpl;

	$out['title'] = 'Úprava sekcí článků';
	$out['content'] = '<p>Zde je hlavní rozhraní pro práci se sekcemi článků. Proti původní verzi je zjednodušeno, zrušil jsem mazání článků,
	vlastně stačí článek nastavit jako nezobrazovaný. Má to výhodu v tom, že ani omylem nemůžete článek smazat ;)</p>
<ul>
    <li><img src="icons/edit.png">Upravit článek</li>
    <li><img src="icons/yes.png"> Povolit článek</li>
    <li><img src="icons/not.png"> Zakázat článek</li>
    <li><img src="icons/reada.png">Číst článek v Administraci</li>
    <li><img src="icons/readw.png">Číst článek na Webu</li>
    <li><span class="hidden">Takto označené</span> jsou blokované o úroveň výš (sekce, kategorie), znamená to, že celá kategorie či sekce je na veřejném webu nedostupná..</li>
</ul><br>


';
	$o = '';
	$v1 = $this->dbs->s("SELECT `id`, `nazev`, `zobrazovat` FROM " . Tabulka_kategorie . " ORDER BY `nazev`");
	if (is_array($v1)) {
	    $o .= "\n<ul>";
	    foreach ($v1 as $f1) {
		$katBlokClass = $f1['zobrazovat'] == 1 ? 'visible' : 'hidden';
		$o .= "\n\t" . '<li class="' . $katBlokClass . '">Kategorie: ' . $f1['nazev'] . ' ';
		$v2 = $this->dbs->s("SELECT `id`, `nazev`, `kategorie`, `zobrazovat` FROM " . Tabulka_sekce . " WHERE `kategorie` = " . $f1['id'] . " ORDER BY `nazev`");
		if (is_array($v2)) {
		    $o .= "\n\t\t<ul>";
		    foreach ($v2 as $f2) {
//$o .= "\n\t\t\t" . '<li>Sekce: ' . $f2['nazev'] . ' ';
			$f2['sekName'] = $this->sekce;
			if ($f2['zobrazovat'] == 1) {
			    $f2['zobrazovat'] = 'Zakázat';
			    $f2['format'] = 'b';
			    $f2['ico'] = 'not';
			} else {
			    $f2['zobrazovat'] = 'Povolit';
			    $f2['format'] = 's';
			    $f2['ico'] = 'yes';
			}
			$form = <<< EEE
<li>
    <a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci&amp;upravit=ano&amp;id={{!id!}}" title="Upravit tuto sekci"><img src="icons/edit.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=sekce&naz=vytvor-sekci-ukaz&id={{!id!}}" title="Zobrazit tuto sekci článeků v Administraci"  target="_blank"><img src="icons/reada.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=sekce&amp;naz=nastav-zobrazeni-sekce&amp;id={{!id!}}" title="{{!zobrazovat!}} zobrazení sekce"><img src="icons/{{!ico!}}.png" width="16" height="16"></a>
    <a href="index.php?{{!sekName!}}={{!id!}}" title="Zobrazit tento článek na webu"  target="_blank"><img src="icons/readw.png" width="16" height="16" alt="zobrazit článek"></a>
    <{{!format!}}>{{!nazev!}}</{{!format!}}>
</li>
EEE;
			$tpl->content = $f2;
			$o .= $tpl->parseTpl($form);
		    }
		    $o .= "\n\t\t</ul>";
		} else {
		    $o .= $v2; // . $dbs->getLastQuery();
		}
	    }
	    $o .= "\n</ul>";
	} else {
	    $o .= $v1; // . $dbs->getLastQuery();
	}
	$out['content'] .= $o;
	return $out;
    }

    /*
     * metoda pomocí které vytváříme a editujeme sekce článků
     */

    private function sekceVytvor() {

	$tpl = new tpl;
	$out['js'] = $this->texylaJS;
	$out['title'] = 'Vytvoř sekci článků';
	$out['content'] = '';

	$var['seznamSekci'] = '';
	$var['nazev'] = '';
//$var['uvodnik'] = '';
	$var['vstup'] = '';
	$var['adresa'] = $_SERVER['QUERY_STRING'];
	$var['seznamKategorii'] = 0;
	$err = 0;
	if (!empty($_POST['uloz'])) {

	    $texy = new AdminTexy;
// název článku
	    if (!empty($_POST['nazev'])) {
		$var['nazev'] = strip_tags($_POST['nazev']);
	    }
	    if (empty($var['nazev'])) {
		$var['errnazev'] = '<p class="err">Nevyplnili jste název sekce, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// vstup neboli obsah článku
	    if (!empty($_POST['vstup'])) {
		$var['vstup'] = $_POST['vstup'];
		$var['htmlvstup'] = $texy->process($_POST['vstup']);
	    }
	    if (empty($var['vstup'])) {
		$var['errvstup'] = '<p class="err">Nevyplnili jste obsah článku sekce, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// vstup neboli obsah článku
	    if (!empty($_POST['seznamKategorii'])) {
		$var['seznamKategorii'] = intval($_POST['seznamKategorii']);
	    }
	    if (empty($var['seznamKategorii'])) {
		$var['errseznamKategoriii'] = '<p class="err">Nevybrali jste kategorii, do které chcete umístit článek, prosím, vyplňte ho.</p>';
		$err = 1;
	    }


// pokus o uložení dat do db
	    if ($err == 0) {
		if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
		    $v = $this->dbs->u(Tabulka_sekce, array(
//`id`, `nazev`, `kategorie`, `claneksekce`, `zobrazovat`
				'nazev' => $var['nazev'],
				'kategorie' => $var['seznamKategorii'],
				'claneksekce' => $var['htmlvstup'],
				'texyclaneksekce' => $var['vstup']
				    ),
				    'WHERE `id` = ' . $this->dbs->o($_GET['id'])
		    );
		    if (is_bool($v)) {
			header("location: admin.php?clanek=sekce&naz=vytvor-sekci-ukaz&id=" . $_GET['id']);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		} else {
//nazev,sekce,clanek,zobrazovat,uvodnik,datum) values ('{$nazev}','{$sekce}','{$vstup}','{$zobrazovat}','{$uvodnik}',CURRENT_TIMESTAMP())";
		    $v = $this->dbs->i(Tabulka_sekce, array(
				'nazev' => $var['nazev'],
				'kategorie' => $var['seznamKategorii'],
				'claneksekce' => $var['htmlvstup'],
				'texyclaneksekce' => $var['vstup']
				    )
		    );
		    if (is_int($v)) {
			header("location: admin.php?clanek=sekce&naz=vytvor-sekci-ukaz&id=" . $v);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		}
	    }
	}

// jdeme zjistit, jestli se jedná o úpravy nebo nový článek
// pokud úprava, tak vytáhneme z db článek a vložíme do proměnných
	if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
// nazev,sekce,clanek,zobrazovat,uvodnik,datum
	    $v = $this->dbs->s("SELECT `id`, `nazev`, `kategorie`, `texyclaneksekce`, `zobrazovat` FROM " . Tabulka_sekce . " WHERE `id` = " . $this->dbs->o($_GET['id']));
	    if (is_array($v)) {
		$var['nazev'] = $v[0]['nazev'];
		$var['seznamKategorii'] = $v[0]['kategorie'];
		$var['vstup'] = $v[0]['texyclaneksekce'];
	    } else {
		return array('title' => 'Napiš článek', 'content' => '<p class="err">Litujeme, požadovaná sekce neexistuje, chcete-li, <a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci" title="Vytvořit novou sekci článků...">můžete vytvořit novou</a></p> ');
	    }
	}

	$var['seznamKategorii'] = $this->sekceGenerujSeznamKategorii($var['seznamKategorii']);

	$form = <<< EEE
<p>V tomhle editoru mmůžete vytvářet obsah vašich html stránek. </p>
{{!errSaveMessage!}}
<form name="form" method="post" action="admin.php?{{!adresa!}}">
    <label for="nazev">Zde napište název sekce článků</label><br>
	<span id="pocitadlo1">0/50</span><br>
	{{!errnazev!}}
	<input type="text" name="nazev" onkeydown="start_omez_text(50,document.form.nazev,'pocitadlo1')"size="40" maxlength="50" value="{{!nazev!}}"><br><br>
    <label for="vstup">Zde, v okně pod tímto textem, mapište obsah článku, můžete použít html tagy kliknutím na jejich název nad tímto textem, nebo kliknutím na obrázek nahoře vložíte kod pro zobrazení obrázku na stránce. V modrém poli uvidíte náhled toho co píšete dolů.</label>
	{{!errvstup!}}
	<textarea type="text" name="vstup" class="vstup" wrap="soft" rows="20" cols="65">{{!vstup!}}</textarea>
	{{!errseznamKategoriii!}}
	{{!seznamKategorii!}}
<input type="submit" name="uloz" value="Ulož" style="color:#ff0000;">
EEE;


	$tpl->content = $var;
	$out['content'] .= $tpl->parseTpl($form);
	return $out;
    }

    /*
     * Script mění v tabulce hodnotu zobrazovat na opačnou 1 <> 0
     */

    private function sekceNastavZobrazeni() {
	$err = 0;


	$out['title'] = 'Nastavení zobrazení sekce';
	$out['content'] = '<p>Tato stránka mění nastavení zobrazení jednotlivých sekcí. Pokud vidíte tento text, došlo při změně obsahu databáze k nějaké chybě.</p>';

	if (!empty($_GET["id"])) {
	    $v = $this->dbs->u(Tabulka_sekce, ' `zobrazovat` = NOT(`zobrazovat`) ', ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
	    if (is_bool($v)) {
		$v0 = $this->dbs->s("SELECT `zobrazovat` FROM " . Tabulka_sekce . ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
		if (is_array($v0)) {
		    $set = $v0[0]['zobrazovat'] == 1 ? 1 : 0;
		    $v1 = $this->dbs->u(Tabulka_clanky, ' `blokall` = ' . $set, ' WHERE `sekce` = ' . $this->dbs->o($_GET["id"]));
		} else {
		    $err = 1;
		}
	    } else {
		$out['content'] .= $v;
		$err = 1;
	    }
//echo $dbs->getLastQuery();
	} else {
	    $err = 1;
	}
	if ($err == 0) {
	    header('location: admin.php?clanek=sekce&naz=uprav-sekci');
	    exit;
	}
	return $out;
    }

    /*
     * Metoda zobrazí seznam Sekcí v databází a nabídne možnost jejich editace, schování/zobrazení, a čtení v adminu i na Webu
     */

    private function sekceUkaz() {

	$out['title'] = 'Náhled sekce článků';
	$out['content'] = '';
	$txt = <<< EEE

<p>Sekce článků <strong>{{!nazev!}}</strong> se podařilo úspěšně uložit na server.</p>

<p>Sekci článků jste zařadili do kategorie: <strong>{{!kategorie!}}</strong><br></p>
<p>Náhled článku vidíte níže:</p><br>
<h2>{{!nazev!}}</h2>
<p>{{!uvodnik!}}</p>
{{!claneksekce!}}
<p>Pokud byste chtěli, <a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci&amp;upravit=ano&amp;id={{!id!}}">můžete upravit tuto sekci článků</a><p>

EEE;

//           "select nazev, clanek, sekce, uvodnik from ".Tabulka_clanky." where id='{$cislo_id}'";
	$v = $this->dbs->s("select id, nazev, claneksekce, kategorie from " . Tabulka_sekce . " where id=" . $this->dbs->o($_GET["id"]));
	if (is_array($v)) {
	    $v1 = $this->dbs->s("SELECT nazev FROM " . Tabulka_kategorie . " WHERE id = " . $v[0]['kategorie']);
	    if (is_array($v1)) {
		$v[0]['kategorie'] = $v1[0]['nazev'];
	    }
	    $var = $v[0];
	    $tpl = new tpl;
	    $tpl->content = $var;
	    $out['content'] .= $tpl->parseTpl($txt);

	    return $out;
	}
	$out = $this->notfound();
	$out['content'] .= $v . $dbs->getLastQuery();
	return $out;
    }

    /*
     * Pokusí se vygenerovat seznam sekcí pro výběr sekce
     */

    private function sekceGenerujSeznamKategorii($sel = 0) {

//$dotaz="select nazev, id from ".Tabulka_sekce." where kategorie='{$row['id']}' order by id asc";
	$d = "SELECT `nazev`, `id` FROM " . Tabulka_kategorie . " ORDER BY `nazev` asc";
	$v = $this->dbs->s($d);
	$out = '';
	if (is_array($v)) {
	    $out .= '<select name="seznamKategorii"><option value="0">--Vyberte kategorii článků--</option>';
	    foreach ($v as $f) {
//$out .= '<br>' . $f['nazev'] . ' - ' . ;
		$to = '';
		if ($sel == $f['id']) {
		    $to = ' selected="selected"';
		}
		$out .= '<option value="' . $f['id'] . '"' . $to . '>' . $f['nazev'] . '</option>';
	    }
	    $out .= '</select>';
	} elseif (is_bool($v)) {
	    $out .= '<p style="color:red; font-size:20px;">Nejprve vytvořte Sekce článků, pak teprve můžete přidávat články!!!<a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci">Přidat Sekci článků</a></p>';
	}
	return $out;
//echo $dbs->getLastQuery();
    }

    /*
     * Pomocí této metody vytváříme a editujeme články
     */

    private function clanekVytvor() {

	$tpl = new tpl;
	$out['js'] = $this->texylaJS;
	$out['title'] = 'Napiš článek';
	$out['content'] = '';

	$var['seznamSekci'] = '';
	$var['nazev'] = '';
	$var['errnazev'] = '';
	$var['uvodnik'] = '';
	$var['erruvodnik'] = '';
	$var['htmluvodnik'] = '';
	$var['vstup'] = '';
	$var['htmlvstup'] = '';
	$var['errvstup'] = '';
	$var['adresa'] = $_SERVER['QUERY_STRING'];
	$textMaxLen = 40000;
	$err = 0;
	if (!empty($_POST['uloz'])) {
	    $texy = new AdminTexy;

// název článku
	    if (!empty($_POST['nazev'])) {
		$var['nazev'] = strip_tags($_POST['nazev']);
		$var['nazev'] = mb_substr($var['nazev'], 0, 250);
	    }
	    if (empty($var['nazev'])) {
		$var['errnazev'] = '<p class="err">Nevyplnili jste název článku, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// úvodník
	    if (!empty($_POST['uvodnik'])) {
		$var['uvodnik'] = $_POST['uvodnik'];
		$var['htmluvodnik'] = $texy->process($_POST['uvodnik']);
		$len = mb_strlen($var['htmluvodnik'], 'utf8');
		if ($len > $textMaxLen) {
		    $err = 1;
		    $var['erruvodnik'] .= '<p class="err">Úvodník je příliš dlouhý, po převedení na HTML obsahuje: ' . $len . ' znaků. Systém povoluje pouze ' . $textMaxLen . ' znaků. Prosím opravte.</p>';
		}
		$len = mb_strlen($var['uvodnik'], 'utf8');
		if ($len > $textMaxLen) {
		    $err = 1;
		    $var['erruvodnik'] .= '<p class="err">Úvodník je příliš dlouhý, obsahuje: ' . $len . ' znaků. Systém povoluje pouze ' . $textMaxLen . ' znaků. Prosím opravte.</p>';
		}
	    }
	    if (empty($var['uvodnik'])) {
		$var['erruvodnik'] .= '<p class="err">Nevyplnili jste úvodník článku, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// vstup neboli obsah článku
	    if (!empty($_POST['vstup'])) {
		$var['vstup'] = $_POST['vstup'];
		$var['htmlvstup'] = $texy->process($_POST['vstup']);
		$len = mb_strlen($var['vstup'], 'utf8');
		if ($len > $textMaxLen) {
		    $err = 1;
		    $var['errvstup'] .= '<p class="err">Obsah článku je příliš dlouhý, obsahuje: ' . $len . ' znaků. Systém povoluje pouze ' . $textMaxLen . ' znaků. Prosím opravte.</p>';
		}
		$len = mb_strlen($var['htmlvstup'], 'utf8');
		if ($len > $textMaxLen) {
		    $err = 1;
		    $var['errvstup'] .= '<p class="err">Obsah článku je příliš dlouhý,  po převedení na HTML obsahuje: ' . $len . ' znaků. Systém povoluje pouze ' . $textMaxLen . ' znaků. Prosím opravte.</p>';
		}
	    }
	    if (empty($var['vstup']) && ($this->mandatoryClanek === true)) {
		$var['errvstup'] = '<p class="err">Nevyplnili jste obsah článku, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// vstup neboli obsah článku
	    if (!empty($_POST['seznamSekci'])) {
		$var['seznamSekci'] = intval($_POST['seznamSekci']);
	    }
	    if (empty($var['seznamSekci'])) {
		$var['errseznamSekci'] = '<p class="err">Nevybrali jste sekci, do které chcete umístit článek, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// pokus o uložení dat do db
	    if ($err == 0) {
		if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
		    $v = $this->dbs->u(Tabulka_clanky, array(
				'nazev' => $var['nazev'],
				'sekce' => $var['seznamSekci'],
				'clanek' => $var['htmlvstup'],
				'texyclanek' => $var['vstup'],
				'uvodnik' => $var['htmluvodnik'],
				'texyuvodnik' => $var['uvodnik'],
				'datum' => 'NOW()'
				    ),
				    'WHERE `id` = ' . $this->dbs->o($_GET['id'])
		    );
		    if (is_bool($v)) {
			header("location: admin.php?clanek=clanky&naz=napis-clanek-ukaz&id=" . $_GET['id']);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		} else {
//nazev,sekce,clanek,zobrazovat,uvodnik,datum) values ('{$nazev}','{$sekce}','{$vstup}','{$zobrazovat}','{$uvodnik}',CURRENT_TIMESTAMP())";
		    $v = $this->dbs->i(Tabulka_clanky, array(
				'nazev' => $var['nazev'],
				'sekce' => $var['seznamSekci'],
				'clanek' => $var['htmlvstup'],
				'texyclanek' => $var['vstup'],
				'uvodnik' => $var['htmluvodnik'],
				'texyuvodnik' => $var['uvodnik'],
				'datum' => 'NOW()'
				    )
		    );
		    if (is_int($v)) {
			header("location: admin.php?clanek=clanky&naz=napis-clanek-ukaz&id=" . $v);
			exit;
		    } else {
// nepodařilo se to uložit, zobrazíme formulář s chybovou zprávou z databáze
			$var['errSaveMessage'] = $v;
		    }
		}
	    }
	}

// jdeme zjistit, jestli se jedná o úpravy nebo nový článek
// pokud úprava, tak vytáhneme z db článek a vložíme do proměnných
	if (!empty($_GET['upravit']) && $_GET['upravit'] === 'ano') {
// nazev,sekce,clanek,zobrazovat,uvodnik,datum
	    $v = $this->dbs->s("SELECT `nazev`, `sekce`, `texyclanek`, `texyuvodnik` FROM " . Tabulka_clanky . " WHERE `id` = " . $this->dbs->o($_GET['id']));
	    if (is_array($v)) {
		$var['seznamSekci'] = $v[0]['sekce'];
		$var['nazev'] = $v[0]['nazev'];
		$var['uvodnik'] = $v[0]['texyuvodnik'];
		$var['vstup'] = $v[0]['texyclanek'];
	    } else {
		return array('title' => 'Napiš článek', 'content' => '<p class="err">Litujeme, požadovaný článek neexistuje, chcete-li, <a href="admin.php?clanek=clanky&amp;naz=napis-clanek" title="Napsat nový článek...">můžete napsat nový</a></p> ');
	    }
	}

	$var['seznamSekci'] = $this->clanekGenerujSeznamSekci($var['seznamSekci']);

	$form = <<< EEE
<p>V tomhle editoru mmůžete vytvářet obsah vašich html stránek. </p>
{{!errSaveMessage!}}
<form name="form" method="post" action="admin.php?{{!adresa!}}">
    <label for="nazev">Zde napište název článku, bude použit zároveň jako nadpis článku:</label><br>
	{{!errnazev!}}
	<input type="text" name="nazev" size="60" maxlength="150" value="{{!nazev!}}"><br><br>
    <label for="uvodnik">Úvodník: zde napište úvod článku, bude se zobrazovat v novinkách. Vlastně je to povinná položka a její velikost je omezena nastavením na {$textMaxLen} znaků. Používejte Texy syntaxi, editor Texyla Vám budiž nápomocen.</label><br>
	
	{{!erruvodnik!}}
	<textarea name="uvodnik" id="uvodnik1" class="uvodnik" rows="10" cols="65">{{!uvodnik!}}</textarea><br><br>
    <label for="vstup">Obsah článku, povinný není, jeho maximální délka je {$textMaxLen} znaků.  Používejte Texy syntaxi, editor Texyla Vám budiž nápomocen.</label>
	{{!errvstup!}}
	<textarea type="text" id="vstup1" name="vstup" class="vstup" autocomplete="off" wrap="off" rows="20" cols="65">{{!vstup!}}</textarea>
	{{!errseznamSekci!}}
	{{!seznamSekci!}}
<input type="submit" name="uloz" value="Ulož" style="color:#ff0000;">
EEE;


	$tpl->content = $var;
	$out['content'] .= $tpl->parseTpl($form);
	return $out;
    }

    /*
     * Pokusí se vygenerovat seznam sekcí pro výběr sekce
     */

    private function clanekGenerujSeznamSekci($sel = 0) {

//$dotaz="select nazev, id from ".Tabulka_sekce." where kategorie='{$row['id']}' order by id asc";
	$d = "SELECT `nazev`, `id` FROM " . Tabulka_sekce . " ORDER BY `nazev` asc";
	$v = $this->dbs->s($d);
	$out = '';
	if (is_array($v)) {
	    $out .= '<select name="seznamSekci"><option value="0">--Vyberte sekci článků--</option>';
	    foreach ($v as $f) {
//$out .= '<br>' . $f['nazev'] . ' - ' . ;
		$to = '';
		if ($sel == $f['id']) {
		    $to = ' selected="selected"';
		}
		$out .= '<option value="' . $f['id'] . '"' . $to . '>' . $f['nazev'] . '</option>';
	    }
	    $out .= '</select>';
	} elseif (is_bool($v)) {
	    $out .= '<p style="color:red; font-size:20px;">Nejprve vytvořte Sekce článků, pak teprve můžete přidávat články!!!<a href="admin.php?clanek=sekce&amp;naz=vytvor-sekci">Přidat Sekci článků</a></p>';
	}
	return $out;
//echo $dbs->getLastQuery();
    }

    /*
     * zobrazí právě přidaný/editovaný článek
     */

    private function clanekUkaz() {

	$txt = <<< EEE

<p> Článek se podařilo úspěšně nahrát na server
Jeho název je: <strong>{{!nazev!}}</strong><br>
Článek jste zařadili do sekce: <strong>{{!nazev!}}</strong><br>
Náhled článku vidíte níže:</p>
<h2>{{!nazev!}}</h2>
<p>{{!uvodnik!}}</p>
{{!clanek!}}
<p>Pokud byste chtěli článek ještě upravit, klikněte
    <a href="admin.php?clanek=clanky&amp;naz=napis-clanek&amp;upravit=ano&amp;id={{!id!}}">upravit článek</a><p>

EEE;

//           "select nazev, clanek, sekce, uvodnik from ".Tabulka_clanky." where id='{$cislo_id}'";
	$v = $this->dbs->s("select id, nazev, clanek, sekce, uvodnik from " . Tabulka_clanky . " where id=" . $this->dbs->o($_GET["id"]));
	if (is_array($v)) {
	    $var = $v[0];
	    $tpl = new tpl;
	    $tpl->content = $var;
	    $out['content'] = $tpl->parseTpl($txt);
	    $out['title'] = 'Náhled článku';
	    return $out;
	}
	$out = $this->notfound();
	return $out;
    }

    /*
     * metoda ukáže seznam článků a odkaty pro jejich editaci, schování/zobrazení, čtení na webu a v administraci
     */

    private function clanekVypis() {
	$tpl = new tpl;

	$out['title'] = 'Výpis článků';
	$out['content'] = <<< EEE
    <p>Zde je hlavní rozhraní pro práci s články. Proti původní verzi je zjednodušeno, zrušil jsem mazání článků,
	vlastně stačí článek nastavit jako nezobrazovaný. Má to výhodu v tom, že ani omylem nemůžete článek smazat ;)</p>
<ul>
    <li><img src="icons/edit.png">Upravit článek</li>
    <li><img src="icons/yes.png"> Povolit článek</li>
    <li><img src="icons/not.png"> Zakázat článek</li>
    <li><img src="icons/reada.png">Číst článek v Administraci</li>
    <li><img src="icons/readw.png">Číst článek na Webu</li>
    <li><span class="hidden">Takto označené</span> jsou blokované o úroveň výš (sekce, kategorie), znamená to, že celá kategorie či sekce je na veřejném webu nedostupná..</li>
</ul><br>
<p>
    <a href="admin.php?clanek=clanky&amp;naz=uprav-clanek-uvod" title="Upravit tento článek"><img src="icons/edit.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=clanky&naz=uprav-clanek-uvod-ukaz" title="Zobrazit tento článek v Administraci"  target="_blank"><img src="icons/reada.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="index.php" title="Zobrazit tento článek na webu"  target="_blank"><img src="icons/readw.png" width="16" height="16" alt="zobrazit článek"></a>
    <b>Úvodní článek webu</b>
</p>
EEE;
	$o = '';
	$v1 = $this->dbs->s("SELECT `id`, `nazev`, `zobrazovat` FROM " . Tabulka_kategorie . " ORDER BY `nazev`");
	if (is_array($v1)) {
	    $o .= "<ul>";
	    foreach ($v1 as $f1) {
		$katBlokClass = $f1['zobrazovat'] == 1 ? 'visible' : 'hidden';
		$o .= "" . '<li class="' . $katBlokClass . '">Kategorie: ' . $f1['nazev'] . ' ';
		$v2 = $this->dbs->s("SELECT `id`, `nazev`, `kategorie`, `zobrazovat` FROM " . Tabulka_sekce . " WHERE `kategorie` = " . $f1['id'] . " ORDER BY `nazev`");
		if (is_array($v2)) {
		    $o .= "<ul>";
		    foreach ($v2 as $f2) {
			$sekBlokClass = ($katBlokClass == 'visible' && $f2['zobrazovat'] == 1) ? 'visible' : 'hidden';
			$o .= "" . '<li class="' . $sekBlokClass . '">Sekce: ' . $f2['nazev'] . ' ';
			$v3 = $this->dbs->s("SELECT `id`, `nazev`, `sekce`, `zobrazovat` FROM " . Tabulka_clanky . " WHERE `sekce` = " . $f2['id'] . " ORDER BY `id`");
			if (is_array($v3)) {
			    $o .= "" . '<ul>';
			    foreach ($v3 as $f3) {
				$f3['blokAllClass'] = $katBlokClass;
				if ($f3['zobrazovat'] == 1) {
				    $f3['zobrazovat'] = 'Zakázat';
				    $f3['format'] = 'b';
				    $f3['ico'] = 'not';
				} else {
				    $f3['zobrazovat'] = 'Povolit';
				    $f3['format'] = 's';
				    $f3['ico'] = 'yes';
				}
				$f3['sekName'] = $this->sekce;
				$f3['claName'] = $this->clanek;
				$form = <<< EEE
<li>
    <a href="admin.php?clanek=clanky&amp;naz=napis-clanek&amp;upravit=ano&amp;id={{!id!}}" title="Upravit tento článek"><img src="icons/edit.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=clanky&naz=napis-clanek-ukaz&id={{!id!}}" title="Zobrazit tento článek v Administraci"  target="_blank"><img src="icons/reada.png" width="16" height="16" alt="zobrazit článek"></a>
    <a href="admin.php?clanek=clanky&amp;naz=nastav-zobrazeni-clanku&amp;id={{!id!}}" title="{{!zobrazovat!}} zobrazení článku"><img src="icons/{{!ico!}}.png" width="16" height="16"></a>
    <a href="index.php?{{!sekName!}}={{!sekce!}}&amp;{{!claName!}}={{!id!}}" title="Zobrazit tento článek na webu"  target="_blank"><img src="icons/readw.png" width="16" height="16" alt="zobrazit článek"></a>
    <{{!format!}}>{{!nazev!}}</{{!format!}}>
</li>
EEE;
				$tpl->content = $f3;
				$o .= $tpl->parseTpl($form);
			    }
			    $o .= "</ul>";
			} else {
			    $o .= $v3;   // . $this->dbs->getLastQuery();
			}
		    }
		    $o .= "</ul>";
		} else {
		    $o .= $v2; // . $dbs->getLastQuery();
		}
	    }
	    $o .= "</ul>";
	} else {
	    $o .= $v1; // . $dbs->getLastQuery();
	}
	$out['content'] .= $o;
	return $out;
    }

    /*
     * Script mění v tabulce hodnotu zobrazovat na opačnou 1 <> 0
     */

    private function clanekNastavZobrazeni() {
	$out['title'] = 'Nastavení zobrazení článků';
	$out['content'] = '<p>Tato stránka mění nastavení zobrazení jednotlivých článků. Pokud vidíte tento text došlo při změně obsahu databáze k nějaké chybě.</p>';

	if (!empty($_GET["id"])) {
	    $this->delPageFromCacheByClanekId($_GET["id"]);
	    $v = $this->dbs->u(Tabulka_clanky, ' `zobrazovat` = NOT(`zobrazovat`) ', ' WHERE `id` = ' . $this->dbs->o($_GET["id"]));
	    if (is_bool($v)) {
		header('location: admin.php?clanek=clanky&naz=uprav-clanek');
		exit;
	    } else {
		$out['content'] .= $v;
	    }
//echo $dbs->getLastQuery();
	}
	return $out;
    }

    /*
     * Vrací název stránky v cache i s cestou k ní
     */

    private function getCachePageName($clanek, $sekce) {
	return $this->file_path_pages . md5('' . $this->sekce . '=' . $sekce . '&' . $this->clanek . '=' . $clanek);
    }

    /*
     * Provede smazání stránky v cache podle id komentáře a vrátí true nebo selže a vrátí false
     * možná trochu prasácké ale funkční :D
     */

    private function delPageFromCacheByCommentId($idComment) {
	$v1 = $this->dbs->s("SELECT `id_clanku` FROM " . Tabulka_komentare . " WHERE `id` = " . $this->dbs->o($idComment));
	if (is_array($v1)) {
	    $v2 = $this->dbs->s("SELECT `sekce` FROM " . Tabulka_clanky . " WHERE `id` = " . $v1[0]['id_clanku'] . '');
	    if (is_array($v2)) {
		$path = $this->getCachePageName($v1[0]['id_clanku'], $v2[0]['sekce']);
		@unlink($path);
		return true; //jednoduše bez kontoly, možná někdy nějakou zavedeme....
	    } else {
		echo $v2;
	    }
	} else {
	    echo $v1;
	}
	exit;
	//return false;
    }

    /*
     * smaže článek v cache podle id článku nebo hodí chybu a exit
     */

    private function delPageFromCacheByClanekId($id) {
	$v = $this->dbs->s("SELECT `sekce` FROM " . Tabulka_clanky . " WHERE `id` = " . $this->dbs->o($id));
	if (is_array($v)) {
	    @unlink($this->getCachePageName($id, $v[0]['sekce']));
	    return true;
	} else {
	    print_r($v);
	}
	exit;
    }

    /*
     * metoda generuje nové menu vždy když dojde ke změně v databázi
     */

    Private function menuGenerovat() {
	$out = '';
	$this->dbs->debug = true;
	$vk = $this->dbs->s("SELECT `nazev`, `id` FROM " . Tabulka_kategorie . " WHERE `zobrazovat` = 1 ORDER BY `id` ASC");
	if (is_array($vk)) {
	    $kli = array();
	    foreach ($vk as $fk) {

		$vs = $this->dbs->s("SELECT `id`, `nazev` FROM " . Tabulka_sekce . " WHERE `kategorie`=" . $fk["id"] . " AND `zobrazovat` = 1 AND `blokall` = 1 ORDER BY `id` ASC");
		if (is_array($vs)) {
		    $sli = array();
		    foreach ($vs as $fs) {
			$vc = $this->dbs->s("SELECT `id`, `nazev` FROM " . Tabulka_clanky . " WHERE `sekce`=" . $fs["id"] . " AND `zobrazovat` = 1 AND `blokall` = 1 ORDER BY `id` ASC");
			if (is_array($vc)) {
			    $cli = array();
			    foreach ($vc as $fc) {
				$cli[] = '<li><a href="index.php?s=' . $fs['id'] . '&amp;c=' . $fc['id'] . '">' . $fc['nazev'] . '</a></li>';
			    }
			    if (!empty($cli)) {
				$sli[] = '<li><b class="menu" title="Kliknutím sem zobrazíte články v sekci ' . $fs['nazev'] . '" onclick="return !show_hide(document.getElementById(\'schovany-' . $fs["id"] . '\'));">' . $fs['nazev'] . '</b><ul class="cla hidden-js" id="schovany-' . $fs["id"] . '">' . join("\n", $cli) . '</ul></li>';
			    }
			} else if (is_bool($vc)) {

			    // nelze zobrazovat na webu!!
			    //$sli[] = '<p>V sekci ' . $fs['nazev'] . ' nic není...</p>';
			} else {
			    $sli[] = $vc . $this->dbs->getLastQuery();
			}
		    }
		    if (!empty($sli)) {
			$kli[] = '<div class="kategorie"><h3>' . $fk['nazev'] . "</h3>\n<ul>" . join("\n", $sli) . '</ul></div>';
//$kli[] = '<li>' . $fk['nazev'] . '<ul>' . join('', $sli) . '</ul></li>';
		    }
		} else if (is_bool($vs)) {
		    // nelze zobrazovat na webu!!
		    //$kli[] = '<p>V kategorii ' . $fk['nazev'] . ' nic není...</p>';
		} else {
		    $out .= $vs . $this->dbs->getLastQuery();
		}
	    }
	    $out .= '' . join($kli) . '';
	} else if (is_bool($vk)) {
	    // nelze zobrazovat na webu!!
	    //$kli[] = '<p>V kategorii ' . $fk['nazev'] . ' nic není...</p>';
	} else {
	    $out .= $vk;
	}
	if (!$this->saveFile($this->file_path_menu, $out)) {
	    print_r(error_get_last());
	}

	$v = $this->dbs->s("SELECT `id`,`nazev`,`sekce` FROM " . Tabulka_clanky . " WHERE `zobrazovat` = 1 AND `blokall` = 1 ORDER BY `id` DESC LIMIT 0, 10");
	if (is_array($v)) {
	    foreach ($v as $f) {
		$o[] = '<li><a href="index.php?' . $this->sekce . '=' . $f['sekce'] . '&amp;' . $this->clanek . '=' . $f['id'] . '">' . $f['nazev'] . '</a></li>';
	    }
	    $out = '<div class="kategorie"><h3>Novinky</h3><ul><li><ul class="cla">' . join("", $o) . '</ul></li></ul></div>';
	} else if (is_bool($v)) {
	    //echo ''
	    $out = '';
	} else {
	    $out .= $v;
	}
	if (!$this->saveFile($this->file_path_news, $out)) {
	    print_r(error_get_last());
	}
    }

    /*
     * Zpracování textu poslaného z texyly pomocí texy
     */

    private function clanekTexyla() {
	if (!empty($_POST['texy'])) {
	    $texy = new AdminTexy;

	    header("Content-Type: text/html; charset=UTF-8");

	    $code = get_magic_quotes_gpc() ? stripslashes($_POST["texy"]) : $_POST["texy"];
	    die($texy->process($code));
	}
	return $this->notfound();
    }

    private function clanekAdminUvod() {
	$out['title'] = 'Administrace';
	$out['content'] = <<<EEE
<p>Nacházíte se v administraci Redakčního systému. Najdete zde nástroje pro přidávání a editaci článků, sekcí a kategorií.</p>
    <h2>Vysvětlení zdánlivých nelogičností</h2>
    <ul>
    <li>Menu
	<ul>
	    <li>Zobrazují se jen ty položky které obsahují článek.</li>
	</ul>
    </li>
    <li>Články
	<ul>
	    <li>Po napsání nového článku je nutno k jeho publikaci schovat a zobrazit sekci, do které je přidán.</li>
	</ul>
    </li>
</ul>


EEE;
	return $out;
    }

    private function clanekUvodEdit() {
	$tpl = new tpl;
	$out['js'] = $this->texylaJS;
	$out['title'] = 'Vytvoř sekci článků';
	$out['content'] = '';
	$var['selCount'] = 0;

	$var['nazev'] = '';
	$var['vstup'] = '';
	$var['adresa'] = $_SERVER['QUERY_STRING'];
	$err = 0;
	// pokud neexistuje, vytvoříme defaultní úvodní článek
	if (!file_exists($this->file_path_uvod)) {
	    $a = new admin;
	    $a->clanekUvodCreate($this->file_path_uvod);
	}
	$v = unserialize(file_get_contents($this->file_path_uvod));
	//print_r($v);
	if (is_array($v)) {
	    $var['nazev'] = $v['title'];
	    $var['vstup'] = $v['texycontent'];
	    $var['selCount'] = $v['selCount'];
	} else {
	    print_r(error_get_last());
	}
	if (!empty($_POST['uloz'])) {

	    $texy = new AdminTexy;
// název článku
	    if (!empty($_POST['nazev'])) {
		$var['nazev'] = strip_tags($_POST['nazev']);
	    }
	    if (empty($var['nazev'])) {
		$var['errnazev'] = '<p class="err">Nevyplnili jste název sekce, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

// vstup neboli obsah článku
	    if (!empty($_POST['vstup'])) {
		$var['vstup'] = $_POST['vstup'];
		$var['htmlvstup'] = $texy->process($_POST['vstup']);
	    }
	    if (empty($var['vstup'])) {
		$var['errvstup'] = '<p class="err">Nevyplnili jste obsah článku sekce, prosím, vyplňte ho.</p>';
		$err = 1;
	    }

	    if (!empty($_POST['selCount'])) {
		$var['selCount'] = (int) $_POST['selCount'];
	    }

	    if ($err == 0) {
		if ($this->saveFile($this->file_path_uvod, serialize(array('title' => $var['nazev'], 'content' => $var['htmlvstup'], 'texycontent' => $var['vstup'], 'selCount' => $var['selCount'])))) {
		    //uloženo
		    header("location: admin.php?clanek=clanky&naz=uprav-clanek-uvod-ukaz");
		    exit;
		}
	    }
	}
	//print_r($err);
	$var['selCount'] = $this->clanekUvodGetSelectCount($var['selCount']);
	$form = <<< EEE
<p>Zde můžete upravit úvodní článek portálu.</p>
{{!errSaveMessage!}}
<form name="form" method="post" action="admin.php?{{!adresa!}}">
    <label for="nazev">Zde napište název Portálu</label><br>
	{{!errnazev!}}
	<input type="text" name="nazev" size="40" maxlength="50" value="{{!nazev!}}"><br><br>
    <label for="vstup">Zde, v okně pod tímto textem, mapište obsah úvodního článku, formátování textu zajišťuje <a href="http://texy.info">Texy.</a></label>
	{{!errvstup!}}
	<textarea type="text" name="vstup" class="vstup" autocomplete="off" rows="20" cols="65">{{!vstup!}}</textarea>
<br><br>
	<label for="count">Počet úvodníků na úvodní stránce:</label>{{!selCount!}}
	<br><br>
<input type="submit" name="uloz" value="Ulož" style="color:#ff0000;">
EEE;
	$tpl->content = $var;
	$out['content'] .= $tpl->parseTpl($form);

	return $out;
    }

    private function clanekUvodGetSelectCount($sel) {
	$out = '<select name="selCount">';
	$a = array(0 => 0, 1 => 1, 3 => 3, 5 => 5, 10 => 10);
	foreach ($a as $f) {
	    $to = '';
	    if ($sel == $f) {
		$to = ' selected="selected"';
	    }
	    $out .= '<option value="' . $f . '"' . $to . '>' . $f . '</option>';
	}
	$out .= '</select>';
	return $out;
    }

    /*
     * zobrazení úvodního článku po editace
     */

    private function clanekUvodUkaz() {
	$var = unserialize(file_get_contents($this->file_path_uvod));
	$tpl = new tpl;
	$out['title'] = $var['title'];
	$txt = <<< EEE
<p> Úvodní Článek webu se podařilo úspěšně nahrát na server
Jeho název je: <strong>{{!title!}}</strong><br>

Náhled článku vidíte níže:</p>
<h2>{{!title!}}</h2>
{{!content!}}
<br>
<p>Pokud byste chtěli článek ještě upravit, klikněte
    <a href="admin.php?clanek=clanky&amp;naz=uprav-clanek-uvod">upravit úvodní článek</a><p>

EEE;
	$tpl->content = $var;
	$out['content'] = $tpl->parseTpl($txt);
	//echo 'joo';
	return $out;
    }

    /*
     * Pokusí se vytvořit první verzi úvodního článku webu
     */

    public function clanekUvodCreate($file_path_uvod) {
	$webUvod = array(
	    'title' => 'Úvodní článek webu',
	    'texycontent' => '
Instalace byla úspěšně dokončena. Administraci najdete "tady .(Jít do administračního rozhraní webu)":./admin.php


Defaultně vytvořený úvodní článek webu.

Jeho obsah můžete změnit v administraci v části: "Upravit úvodní článek webu":admin.php?clanek=clanky&naz=uprav-clanek-uvod.

',
	    'content' => '
<p>Instalace byla úspěšně dokončena. Administraci najdete <a
href="./admin.php" title="Jít do administračního rozhraní webu">tady</a></p>

<p>Defaultně vytvořený úvodní článek webu.</p>

<p>Jeho obsah můžete změnit v administraci v části: <a
href="admin.php?clanek=clanky&amp;naz=uprav-clanek-uvod">Upravit úvodní
článek webu</a>.</p>
',
	    'selCount' => 0
	);

	if (!file_exists($file_path_uvod)) {
	    if ($this->saveFile($file_path_uvod, serialize($webUvod))) {
		return true;
	    }
	    print_r(error_get_last());
	}
    }

    /*
     * Stránka, která se zobrazí, když nic nenajdeme
     */

    private function notfound() {
	$clanek['nazev'] = 'Nenalezeno';
	$clanek['clanek'] = '<p>Litujeme, ale požadovaný dokument nebyl na serveru nalezen.<br> Skontrolujte, prosím, ještě jednou adresu, jestli v ní není chyba.';
	$clanek['content'] = $clanek['clanek'];
	$clanek['title'] = $clanek['nazev'];
	//$clanek['menu'] = file_get_contents(FILE_NAME_MENU);

	header("HTTP/1.0 404 Not Found");
	return $clanek;
    }

    /*
     * uloží soubor a nastaví mu chmod
     */

    private function saveFile($file, $content) {
	if (!file_put_contents($file, $content)) {
	    print_r(error_get_last());
	} else if (!chmod($file, 0775)) {
	    print_r(error_get_last());
	} else {
	    return true;
	}
    }

}
