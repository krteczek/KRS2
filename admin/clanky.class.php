<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class clanky {

    public $commentIdName = 'commentid';
    public $dbs = '';
    public $cl = 'c';
    public $sl = 's';
    public $kl = 'k';
    private $sid = 0;
    private $cid = 0;
    public $kniha = 'book';
    public $file_path_uvod = 'cache/uvod/uvodni-clanek.serialized.tpl';
    public $file_path_menu = 'cache/menu/menu.tpl';
    public $file_path_pages = 'cache/pages/';
    public $file_path_news = 'cache/menu/news.tpl';
    private $menuKniha = '<div class="kategorie"><h3>Kniha návštěv</h3><ul><li><ul class="cla "><li><a href="index.php?{{!linkKnihaList!}}" title="přečtěte si co nám píší druzí">Počteníčko</a></li><li><a href="index.php?{{!linkKnihaAdd!}}">Napište nám</a></li></ul></li></ul></div>';
    private $texyla = '
	<link rel="stylesheet" href="./texyla/texyla/css/style.css" type="text/css"><link rel="stylesheet" href="./texyla/themes/default/theme.css" type="text/css">
	<!-- jQuery --><script src="./javascript/jquery.js" type="text/javascript"></script><link rel="stylesheet" href="./texyla/themes/default/jquery-ui.css" type="text/css">
	<!-- Texyla core --><script src="./texyla/texyla/js/texyla.min.js" type="text/javascript"></script>
	<!-- plugins --><script src="./texyla/texyla/plugins/texyla.plugins.js" type="text/javascript"></script>
	<script type="text/javascript">
		$.texyla.setDefaults({
			texyCfg: "forum",
			baseDir: "./texyla/texyla",
			previewPath: "./texyla.php",
			bottomRightPreviewToolbar: ["syntax"],
			buttonType: "button",
			bottomLeftToolbar: ["edit", "preview"],
			emoticonPath: "./texyla/emoticons/texy/%var%.gif"
		});

		$(function () {
			$.texyla({
				toolbar: [
					"bold", "italic","del", "emoticon", "symbol"
				],
				texyCfg: "forum",
				tabs: false
			})

		});
	</script>
	';

    public function __construct() {
	global $dbs;
	$this->dbs = $dbs;
    }

    public function run() {
	// kontrola sekce
	$this->cid = !empty($_GET[$this->cl]) ? $_GET[$this->cl] : 0;
	$this->sid = !empty($_GET[$this->sl]) ? $_GET[$this->sl] : 0;
	$out = false;

	if (!empty($this->sid)) {
	    // kontrola článků

	    if (isset($this->cid) && !empty($this->cid)) {
		//je nastavena sekce i článek a článek není 0
		if (empty($_POST)) {
		    // pokusíme se vytáhnout data z cache, to jde jen tehdy,
		    // když není odeslán post, jinak se generuje nová stránka
		    $out = $this->getPageFromCache();
		} else {
		    $out = $this->getClanek();
		}
	    } else if (isset($_GET[$this->cl])) {
		//je nastavena sekce i článek a článek JE 0
		// musí být kontrola $_GET[$this->cl]!!!
		$out = $this->notFound();
	    } else {
		//Je nastavena jen sekce článků
		$out = $this->getSekci();
	    }

	    $out['olderPageLink'] = $this->olderPageLink();
	    $out['newerPageLink'] = $this->newerPageLink($this->sid, $this->cid);
	} else if (empty($_SERVER['QUERY_STRING'])) {

	    // v adrese není nic, jdeme pro úvodní článek
	    $out = $this->getUvodniClanek();
	} else if (!empty($_GET[$this->kniha])) {
	    switch ($_GET[$this->kniha]) {
		case 'list':
		    $out = $this->knihaList();
		    break;
		case 'add':
		    $out = $this->knihaAdd();
		    break;
	    }
	}
	$out['menu'] = $this->getMenu();
	$out['db_count'] = $this->dbs->getCountQuery();

	return $out;
    }

    /*
     * vrátí článek (pokud existuje) a komentáře k tomuto článku (pokud nějaké jsou)
     * a nabídne i komentářový formulář
     */

    private function getClanek() {
	$this->dbs->debug = true;
	$v = $this->dbs->s("SELECT * FROM " . Tabulka_clanky . " WHERE `sekce` = " . $this->dbs->o($this->sid) . " AND `id` = " . $this->dbs->o($this->cid) . " AND `zobrazovat` = 1 AND `blokall` = 1 LIMIT 1");
	if (is_array($v)) {
	    $out['title'] = $v[0]['nazev'];
	    $out['uvodnik'] = $v[0]['uvodnik'];
	    $out['content'] = $v[0]['clanek'];
	    $out['komentareList'] = $this->komentareList();
	    $out['komentareForm'] = $this->komentareAdd();
	    $out['js'] = $this->texyla;

	    $this->counter();

	    $this->setPageToCache($out);
	} else if (is_bool($v)) {
	    $out = $this->notFound();
	} else {
	    print_r($v);
	}
	return $out;
    }

    /*
     * pokusí se získat data z cache, pokud to nepůjde,
     * tak vygeneruje novou stránku přes getClanek
     */

    private function getPageFromCache() {
	$page = $this->getCachePagename();
	if (file_exists($page) && is_readable($page)) {
	    $out = unserialize(file_get_contents($page));
	    $this->counter();
	} else {
	    $out = $this->getClanek();
	}
	return $out;
    }

    /*
     * uloží stránku, vlastně proměnné jí tvořící, serializovaně do souboru
     */

    private function setPageToCache($array) {
	$page = $this->getCachePagename();
	if (!$this->saveFile($page, serialize($array))) {
	    print_r(error_get_last());
	}
    }

    /*
     * Smaže článek v cache (například kvůli přidávání komentářů k článkům
     */
    private function delPageFromCache() {
	$page = $this->getCachePagename();
	if(!unlink($page)) {
	    print_r(error_get_last());
	}
    }
    
    /*
     * tady generujeme názvy souborů v cache článků
     */

    private function getCachePagename() {
	return $this->file_path_pages . md5($_SERVER['QUERY_STRING']);
    }

    /*
     * Přičteme v db přečtení článku a zároven ošetříme duplikaci vkládání pomocí session
     * **********************************************************************************
     */

    private function counter() {
	if (empty($_SESSION['clanekid'][$this->cid])) {
	    $v = $this->dbs->q("UPDATE " . Tabulka_clanky . " SET `pocitadlo` = `pocitadlo` + 1 where id= " . $this->dbs->o($this->cid));
	    $_SESSION['clanekid'][$this->cid] = true;
	}
    }

    /*
     * Získá menu,
     * TODO: postupně rozšířit o další položky...
     */

    private function getMenu() {
	$menu = '';
	$novinky = '';
	if (file_exists($this->file_path_menu)) {
	    $menu = file_get_contents($this->file_path_menu);
	}
	if (file_exists($this->file_path_news)) {
	    $novinky = file_get_contents($this->file_path_news);
	}
	$tpl = new tpl;
	$tpl->content = array('linkKnihaList' => $this->kniha . '=list', 'linkKnihaAdd' => $this->kniha . '=add');
	$kniha = $tpl->parseTpl($this->menuKniha);

	return $novinky . $menu . $kniha;
    }

    /*
     * Vygeneruje odkaz na předchozí stránku sekce
     */

    private function olderPageLink() {
	//$this->sid, $this->cid
	$dotaz = "
		SELECT
		    `id`,
		    `nazev`
		FROM
		    " . Tabulka_clanky . "
		WHERE
		    (`id` < " . $this->dbs->o($this->cid) . ")
		AND
		    (`sekce`=" . $this->dbs->o($this->sid) . " )
		AND
		    `zobrazovat` =1
		AND
		    `blokall` = 1
		ORDER BY `id` DESC limit 0,1";
	$v = $this->dbs->s($dotaz);
	$out = '';
	if (is_array($v)) {
	    $levy = $v[0];
	    $levy['sid'] = $this->sid;
	    $levy['cid'] = $this->cid;
	    $levy['sekce'] = $this->sl;
	    $levy['clanek'] = $this->cl;
	    $tpl = new tpl;
	    $tpl->content = $levy;
	    $txt = <<< EEE
<div class="levy">
    <a href="./index.php?{{!sekce!}}={{!sid!}}&amp;{{!clanek!}}={{!id!}}" title="Jít na předchozí článek v téhle sekci: {{!nazev!}}" >&lt;&lt;&lt; {{!nazev!}}</a>
</div>
EEE;
	    $out = $tpl->parseTpl($txt);
	} else if (is_bool($v) && $this->cid > 0) {
	    $levy['sid'] = $this->sid;
	    $levy['sekce'] = $this->sl;
	    $tpl = new tpl;
	    $tpl->content = $levy;
	    $txt = <<< EEE
<div class="levy">
    <a href="./index.php?{{!sekce!}}={{!sid!}}" title="Jít na úvodní článek této sekce..." > &lt;&lt;&lt; Úvodní článek této sekce</a>
</div>
EEE;
	    $out = $tpl->parseTpl($txt); // . $this->dbs->getLastQuery();
	} else if (is_bool($v) && $this->cid === 0) {
	    $out = '';
	} else {
	    $out .= $v;
	}
	return $out;
    }

    /*
     * odkaz na následující článek sekce
     * 
     */

    private function newerPageLink() {
	$d = "
	    SELECT
		`id`,
		`nazev`
	    FROM
		" . Tabulka_clanky . "
	    WHERE 
		(`id` > " . $this->dbs->o($this->cid) . " and `sekce`=" . $this->dbs->o($this->sid) . ")
	    AND
		`zobrazovat` =1
	    AND
		`blokall` = 1
	    ORDER BY
		`id`
	    LIMIT 1 ";
	$out = '';
	$v = $this->dbs->s($d);
	if (is_array($v)) {
	    $pravy = $v[0];
	    $pravy['sid'] = $this->sid;
	    $pravy['cid'] = $this->cid;
	    $pravy['sekce'] = $this->sl;
	    $pravy['clanek'] = $this->cl;
	    $tpl = new tpl;
	    $tpl->content = $pravy;

	    $txt = <<< EEE
<div class="pravy">
    <a href="./index.php?{{!sekce!}}={{!sid!}}&amp;{{!clanek!}}={{!id!}}" title="Následující článek v téhle sekci: {{!nazev!}}" > {{!nazev!}} &gt;&gt;&gt; </a>
</div>
EEE;
	    $out = $tpl->parseTpl($txt);
	} else if (is_bool($v)) {
	    $txt = <<< EEE
<div class="pravy">
    Žádný novější článek...
</div>
EEE;
	    $out = $txt;
	}
	return $out;
    }

    /*
     * Předdefinovaná stránka NENALEZENO
     */

    private function notFound() {

	$clanek['clanek'] = '<p>Litujeme, ale požadovaný dokument nebyl na serveru nalezen. Je možné, že byl smazán, nebo přesunut.<br> Zkontrolujte, prosím, ještě jednou zadanou adresu, zdali v ní není chyba.';
	$clanek['nazev'] = 'Nenalezeno';
	$clanek['content'] = $clanek['clanek'];
	$clanek['title'] = $clanek['nazev'];
	//$clanek['menu'] = file_get_contents(FILE_NAME_MENU);

	header("HTTP/1.0 404 Not Found");
	return $clanek;
    }

    /*
     * vrátí sekci článků
     */

    private function getSekci() {
	//echo 'jooo';
	$v = $this->dbs->s("
	    SELECT
		`nazev`,
		`claneksekce`
	    FROM
		" . Tabulka_sekce . "
	    WHERE
		`id` = " . $this->dbs->o($this->sid) . "
	    AND
		`zobrazovat` = 1
	    AND
		`blokall` = 1
	    LIMIT 0,1
"
	);
	if (is_array($v)) {
	    $out['title'] = $v[0]['nazev'];
	    $out['content'] = $v[0]['claneksekce'];
	} else {
	    $out['content'] = $v;
	}
	//print_r($out);
	return $out;
    }

    /*
     * vrátí úvodní článek webu
     */

    private function getUvodniClanek() {
	if (file_exists($this->file_path_uvod)) {
	    $out = unserialize(file_get_contents($this->file_path_uvod));

	    // pokud je nastaveno, že se mají vypsat nejnovější články, tak je vypíšeme
	    if (!empty($out['selCount'])) {
		// zobrazíme úvodníky podle požadavku admina
		$v = $this->dbs->s("SELECT `id`, `nazev`, `uvodnik`, `sekce` FROM " . Tabulka_clanky . " WHERE `zobrazovat` = 1 AND `blokall` = 1 ORDER BY `id` DESC LIMIT 0, " . $out['selCount']);
		if (is_array($v)) {
		    $out['content'] .= '<h3>Nejnovější články</h3>';
		    $tpl = new tpl;
		    $txt = <<< EEE
<div class="novinky">
    <fieldset>
	<legend><a href="index.php?{{!sekName!}}={{!sekce!}}&amp;{{!claName!}}={{!id!}}" title="kliknutím na tento odkaz zobrazíte celý článek">{{!nazev!}}</a></legend>
	{{!uvodnik!}}
	<hr>
	<p class="linkKoment"><a href="index.php?{{!sekName!}}={{!sekce!}}&amp;{{!claName!}}={{!id!}}#komentare" title="Jít na komentáře k tomuto článku..."><img src="./icons/comments.png" alt="komentáře"></a></p>
    </fieldset>
</div>

EEE;
		    foreach ($v as $f) {
			$f['sekName'] = $this->sl;
			$f['claName'] = $this->cl;
			
			$tpl->content = $f;
			$out['content'] .= $tpl->parseTpl($txt);
		    }
		}
		//$out['content'] .= 'Budeme';
	    }
	} else {
	    require_once(ROOT_WEBU . '/admin/admin.class.php');
	    $a = new admin;
	    $a->clanekUvodCreate($this->file_path_uvod);
	    $out = unserialize(file_get_contents($this->file_path_uvod));
	}
	return $out;
    }

    /*
     * Vypíše příspěvky v knize návštěv
     */

    private function knihaList() {
	$out['title'] = 'Kniha návštěv';
	$out['content'] = '<p>Zde si můžete přečíst, co mi píší návštěvníci mých stránek, nebo mi <a href="index.php?' . $this->kniha . '=add" title="napište mi">napište taky.</a></p>';
	$txt = <<< EEE
<fieldset>
    <legend>{{!titulek!}}</legend>
<p>jmeno: {{!jmeno!}} | datum: {{!cas!}}</p>
{{!komentar!}}
</fieldset>
EEE;
	$o = '';
	$tpl = new tpl;
	$v = $this->dbs->s("SELECT `jmeno`, `email`, `komentar`, DATE_FORMAT(" . Tabulka_kniha . ".cas, '%e.%b %Y  %H:%i:%s') as cas, `titulek` FROM " . Tabulka_kniha . " WHERE `zobrazovat` = 1 ORDER BY `id` DESC");
	if (is_array($v)) {
	    foreach ($v as $f) {
		$tpl->content = $f;
		$o .= $tpl->parseTpl($txt);
	    }
	}
	$out['content'] .= '<div class="novinky">' . $o . '</div>';
	return $out;
    }

    /*
     * pro přidání příspěvku do knihy návštěv
     */

    private function knihaAdd() {
	$tpl = new tpl;
	$out['title'] = 'Kniha návštěv';
	$out['content'] = '<p>Zde mi můžete sdělit Vaše názory na tyto stránky, Vaše náměty a podobně.<br><br>Sprosté, urážlivé či jinak nevhodné příspěvky budou smazány...</p><br>';
	$var['titulek'] = '';
	$var['jmeno'] = '';
	$var['email'] = '';
	$var['komentar'] = '';
	$var['errtitulek'] = '';
	$var['errjmeno'] = '';
	$var['erremail'] = '';
	$var['errkomentar'] = '';
	$out['js'] = $this->texyla;
	$err = 0;
	$var['js'] = $this->texyla;
	if (!empty($_POST['ok'])) {
	    if (!empty($_POST['titulek'])) {
		$var['titulek'] = trim(strip_tags($_POST['titulek']));
		$var['titulek'] = substr($var['titulek'], 0, 50);
	    }
	    if (empty($var['titulek'])) {
		//$err = 1;
		//$var['errtitulek'] = '<p class="err">Nevyplnili jste titulek příspěvku, prosím, vyplňte jej.</p>';
	    }

	    if (!empty($_POST['jmeno'])) {
		$var['jmeno'] = trim(strip_tags($_POST['jmeno']));
		$var['jmeno'] = substr($var['jmeno'], 0, 15);
	    }
	    if (empty($var['jmeno'])) {
		$var['jmeno'] = 'Anonymní';
		//$err = 1;
		//$var['errjmeno'] = '<p class="err">Nevyplnili jste Vaše jméno v příspěvku, prosím, vyplňte jej.</p>';
	    }

	    if (!empty($_POST['email'])) {
		$var['email'] = trim(strip_tags($_POST['email']));
		$var['email'] = substr($var['email'], 0, 50);
		if (!$this->jeemail($var['email'])) {
		    //$var['errEmail'] = '<p class="err">Email neodpovídá definici emailu. prosím opravte ho</p>';
		    $var['email'] = '';
		}
	    }
	    if (empty($var['email'])) {
		//$err = 1;
		//$var['erremail'] = '<p class="err">Nevyplnili jste Váš email v příspěvku, prosím, vyplňte jej.</p>';
	    }
	    $texy = new ForumTexy;
	    if (!empty($_POST['komentar'])) {
		$var['htmlkomentar'] = $texy->process($_POST['komentar']);
		$var['komentar'] = $_POST['komentar'];
	    }
	    if (empty($var['komentar'])) {
		$err = 1;
		$var['errkomentar'] = '<p class="err">Nevyplnili jste příspěvek, prosím, vyplňte jej.</p>';
	    }
	    $anti = $this->antispam(2);
	    if (!is_bool($anti)) {
		$var['erranti'] = $anti;
		$err = 1;
	    }
	    if ($err === 0) {
		$v = $this->dbs->i(Tabulka_kniha, array(
			    'jmeno' => $var['jmeno'],
			    'email' => $var['email'],
			    'ip_adr' => $_SERVER['REMOTE_ADDR'],
			    'komentar' => $var['htmlkomentar'],
			    //'texykomentar' => $var['komentar'],
			    'zobrazovat' => 1,
			    'cas' => 'NOW()',
			    'titulek' => $var['titulek']
			));
		if (is_int($v)) {
		    //print_r($_SERVER);
		    header('location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '?' . $this->kniha . '=list');
		    exit;
		}
	    }
	}
	$a = $this->antispam();
	$var['label'] = $a['label'];
	$var['input'] = $a['input'];
	$var['knihaName'] = $this->kniha;
	$txt = <<< EEE
<div class="formular">
    <form name="form" method="post" action="index.php?{{!knihaName!}}=add">
	
	    <table>
			<tr><td class="nazvy">Titulek:	{{!errtitulek!}}</td>	<td class="policka"><input type="text" name="titulek" size="30"  value="{{!titulek!}}"> 		</td></tr>
			<tr><td class="nazvy">Jméno: {{!errjmeno!}}</td>	<td class="policka"><input type="text" name="jmeno" size="30"  value="{{!jmeno!}}"> 		</td></tr>
			<tr><td class="nazvy">vzkaz:  {{!errkomentar!}}</td>	<td class="policka"><textarea name="komentar" cols="34" rows="5" >{{!komentar!}}</textarea>	</td></tr>
			<tr><td class="nazvy">{{!label!}} {{!erranti!}}</td>	<td class="policka">{{!input!}}</td></tr>
			<tr><td colspan="2">														</td>	<td class="tlacitka"><input type="submit" name="ok" value="Odeslat" >&nbsp;&nbsp;</td></tr>
	    </table>
    </form>
    <p><a href="index.php?{{!knihaName!}}=list" title="Přečtěte si, co nám píší druzí...">Číst knihu</a></p>
</div>

EEE;
	$tpl->content = $var;
	$out['content'] .= $tpl->parseTpl($txt);
	return $out;
    }

    /*
     * MUSÍ BÝT ZAPLÉ SESSION!!! session_start()
     * Použití ve scriptu:
     * 	kfw_antispam(1): pri $mod = 1:
     * 	    Funkce podle zadaného  $mod vygeneruje cislo a text cislu odpovidajici
     * 	    cislo ulozi do $_SESSION a text spolu s vygenerovanym label (textem k formulari)
     * 	    vrátí returnem jako array(label, input)
     * 	    
     *  kfw_antispam(2): pri $mod = 2:
     * 	    funkce overi jestli existuje $_POST[antispam_post]  a v nem cislo, ktere odpovida
     * 	    nekteremu klici v poli pokud ano zkontroluje jestli existuje $_SESSION[$nazev_sess]
     * 	    a navzájem je porovná. Pokud je to v porádku vrátí true, pokud ne tak false
     * 	    pri špatne zadanem $mod (chyba programátora?), vrátí chybový text a ukonci skript
     * 
     */

    private function antispam($mod = 1) {
	$nazev_postu = 'antispam_post_book';
	$nazev_sess = 'antispam_ses_book';
	$ck = array(1 => 'jedna', 2 => 'dvě', 3 => 'tři', 4 => 'čtyři', 5 => 'pět', 6 => 'šest', 7 => 'sedm', 8 => 'osm', 9 => 'devět');

	switch ($mod) {
	    case 1;
		/**
		  vygeneruje číslo i s textem a pošle ho v poli zpět
		 * */
		$cislo = rand(1, count($ck));
		$_SESSION[$nazev_sess] = $cislo;
		$form['label'] = '<label for="' . $nazev_postu . '">Prosím napište číslo: ' . $ck[$cislo] . '<small><br>(Ochrana proti spamu)</small></label>';
		$form['input'] = '<input type="text" name="' . $nazev_postu . '" id="' . $nazev_postu . '" maxlength="10" size="10">';

		return $form;
		break;
	    case 2;
		/**
		  zkontroluje jestli je poslane cislo z POSTu v poli a jestli jsou shodná pak vrátí true/false pri chybě
		 * */
		//echo '$_POST[$nazev_postu] == ' . $_POST[$nazev_postu] ;

		if ((!empty($_POST[$nazev_postu])) && (array_key_exists($_POST[$nazev_postu], $ck))) {
		    if ((!empty($_SESSION[$nazev_sess])) && ($_POST[$nazev_postu] == $_SESSION[$nazev_sess])) {
			return true;
		    }
		}
		return '<p class="err">Nevyplnili jste, nebo neopravili kontrolní číslo, při každém nahrání stránky je vygenerované nové. Prosím doplňte ho.</p>';
		break;
	}
	echo 'vnitrni chyba generatoru cisel';
	exit;
    }

    /*
     * primitivní test jestli to co je poslané by mohlo být emailem
     */

    private function jeemail($mail) {
	if (ereg("^.+@.+\\..+$", $mail)) {
	    return true;
	} else {
	    return false;
	}
    }

    /*
     * metoda najde komentáře k článku a vypíše je
     * komentáře budou u jednotlivých článků, nebudou zvlášť
     */

    private function komentareList() {
	//select  id, jmeno, email, web, ip_adr, komentar, DATE_FORMAT(" . Tabulka_komentare . ".cas, '%e.%b %Y  %H:%i:%s') as cas,titulek from " . Tabulka_komentare . " where id_clanku=" . $dbs->o($id) . " and zobrazovat = 'ano' order by id desc";
	$v = $this->dbs->s("SELECT * FROM " . Tabulka_komentare . " WHERE `zobrazovat` = 1 AND `id_clanku` = " . $this->dbs->o($this->cid) . " ORDER BY `id` ASC");

	$form = <<< EEE
<fieldset id="{$this->commentIdName}{{!id!}}">
    <legend>{{!titulek!}}</legend>
    <p>jmeno: {{!jmeno!}} | datum: {{!cas!}}</p>
    {{!komentar!}}
</fieldset>

EEE;
	$out = '';
	if (is_array($v)) {
	    $tpl = new tpl;
	    foreach ($v as $f) {
		/*
		 * zrušené emaily...
		  if ($f['email'] != "") {
		  $f['email'] = str_replace(".", " ( tečka ) ", $f['email']);
		  $f['email'] = str_replace("@", " ( závináč ) ", $f['email']);
		  } */
		$f['email'] = '';
		$tpl->content = $f;
		$out .= $tpl->parseTpl($form);
	    }
	} else if (is_bool($v)) {
	    $out .= '<p>Momentálně žádné nejsou...</p>';
	}
	return '<div id="komentare"><h3>Komentáře</h3>' . $out . '</div>';
    }

    private function komentareAdd() {
	$tpl = new tpl;
	$var['titulek'] = '';
	$var['jmeno'] = '';
	$var['email'] = '';
	$var['komentar'] = '';
	$var['errtitulek'] = '';
	$var['errjmeno'] = '';
	$var['erremail'] = '';
	$var['errkomentar'] = '';
	$var['adresa'] = $_SERVER['QUERY_STRING'];
	$err = 0;
	$txt = <<< EEE

<div class="formular">
<p>Zde můžete přidat Váš názor k tomuto článku. Html značky jsou zakázány, o formátování textu se stará <a href="http://texy.info">Texy2</a></p>
<p>{{!error!}}
    <form name="form" method="post" action="index.php?{{!adresa!}}#addcomment" id="addcomment">
	    <table>
			<tr><td class="nazvy" >Titulek:	{{!errtitulek!}}</td>	<td class="policka"><input type="text" name="titulek" size="30"  value="{{!titulek!}}"> 		</td></tr>
			<tr><td class="nazvy" >Jméno: {{!errjmeno!}}</td>	<td class="policka"><input type="text" name="jmeno" size="30"  value="{{!jmeno!}}"> 		</td></tr>
			<tr><td class="nazvy" >vzkaz:  {{!errkomentar!}}</td>	<td class="policka"><textarea name="komentar" cols="50" rows="5" >{{!komentar!}}</textarea>	</td></tr>
			<tr><td class="nazvy">{{!label!}} {{!erranti!}}</td>	<td class="policka">{{!input!}}</td></tr>
			<tr><td>	</td>	<td class="tlacitka"><input type="submit" name="ok" value="Uložit" >&nbsp;&nbsp;</td></tr>
	    </table>
    </form>
</div>

EEE;
	if (!empty($_POST['ok'])) {

	    // odstraníme co je v cache
	    $this->delPageFromCache();
	    
	    if (!empty($_POST['titulek'])) {
		$var['titulek'] = trim(strip_tags($_POST['titulek']));
		$var['titulek'] = mb_substr($var['titulek'], 0, 50, 'utf-8');
	    }
	    if (empty($var['titulek'])) {
		//$err = 1;
		//$var['errtitulek'] = '<p class="err">Nevyplnili jste titulek příspěvku, prosím, vyplňte jej.</p>';
	    }

	    if (!empty($_POST['jmeno'])) {
		$var['jmeno'] = trim(strip_tags($_POST['jmeno']));
		$var['jmeno'] = mb_substr($var['jmeno'], 0, 15, 'utf-8');
	    }
	    if (empty($var['jmeno'])) {
		$var['jmeno'] = 'anonym';
		//$err = 1;
		//$var['errjmeno'] = '<p class="err">Nevyplnili jste Vaše jméno v příspěvku, prosím, vyplňte jej.</p>';
	    }

	    if (!empty($_POST['email'])) {
		$var['email'] = trim(strip_tags($_POST['email']));
		$var['email'] = mb_substr($var['email'], 0, 50, 'utf-8');
		if (!$this->jeemail($var['email'])) {
		    //$var['errEmail'] = '<p class="err">Email neodpovídá definici emailu. prosím opravte ho</p>';
		    $var['email'] = '';
		}
	    }
	    if (empty($var['email'])) {
		//$err = 1;
		//$var['erremail'] = '<p class="err">Nevyplnili jste Váš email v příspěvku, prosím, vyplňte jej.</p>';
	    }
	    $texy = new ForumTexy;
	    if (!empty($_POST['komentar'])) {
		$var['komentar'] = mb_substr($_POST['komentar'], 0, 1000, 'utf-8');
		$var['htmlkomentar'] = $texy->process($var['komentar']);
		//$var['komentar'] = $_POST['komentar'];
	    }
	    if (empty($var['komentar'])) {
		$err = 1;
		$var['errkomentar'] = '<p class="err">Nevyplnili jste příspěvek, prosím, vyplňte jej.</p>';
	    }
	    $anti = $this->antispam(2);
	    if (!is_bool($anti)) {
		$var['erranti'] = $anti;
		$err = 1;
	    }
	    if ($err === 0) {
		$v = $this->dbs->i(Tabulka_komentare, array(
			    'jmeno' => $var['jmeno'],
			    'email' => $var['email'],
			    'ip_adr' => $_SERVER['REMOTE_ADDR'],
			    'komentar' => $var['htmlkomentar'],
			    //'texykomentar' => $var['komentar'],
			    'zobrazovat' => 1,
			    'cas' => 'NOW()',
			    'titulek' => $var['titulek'],
			    'id_clanku' => $this->cid
			));
		if (is_int($v)) {
		    //print_r($_SERVER);
		    header('location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] . '#' . $this->commentIdName . $v);
		    exit;
		} else {
		    $var['error'] = $v;
		}
	    }
	}
	$a = $this->antispam();
	$var['label'] = $a['label'];
	$var['input'] = $a['input'];

	$tpl->content = $var;
	$out = $tpl->parseTpl($txt);
	return $out;
    }

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

