<?php
error_reporting(E_ALL);

/*
 * last mod: 9.10.2011
 * popis:
 * - přidány poznámky do kódu
 * - upraven instalační proces:
 *  - připojení k db jen jednou
 *  - pokud je to bez chyb, tak vložíme první článek, to je kvůli zrušení úvodního článkuv textáku...
 *  - TODO!: s tím souvisí nutnost přepsání volání úvodního článku TODO!
 *  - TODO!: vytvoření zvláštního konfiguráku pro nastavení vzhledu úvodní stránky
 *
 */

class install {

    public $ROOT = '';
    private $tables = array(
	/*
	 * struktura tabulky články
	 */
	"
CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_clanky` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sekce` int(11) DEFAULT NULL,
  `nazev` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `pocitadlo` int(10) NOT NULL DEFAULT '0',
  `uvodnik` text COLLATE utf8_czech_ci NOT NULL,
  `clanek` text COLLATE utf8_czech_ci,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `datum` datetime DEFAULT NULL,
  `blokall` tinyint(1) NOT NULL DEFAULT '0',
  `texyuvodnik` text COLLATE utf8_czech_ci NOT NULL,
  `texyclanek` text COLLATE utf8_czech_ci NOT NULL,
  `uvodniclanek` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `texyuvodnik` (`texyuvodnik`,`texyclanek`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci ;
",
	/*
	 * Struktura tabulky `kategorie`
	 */
	"
CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_kategorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
",
	/*
	 *  Struktura tabulky `kniha`
	 */
	"
CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_kniha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(15) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `ip_adr` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `komentar` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `cas` datetime NOT NULL,
  `titulek` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
",
	/*
	 * Struktura tabulky `komentare`
	 */
	"
CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_komentare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clanku` int(11) NOT NULL DEFAULT '0',
  `jmeno` varchar(30) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `web` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `ip_adr` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `komentar` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `cas` datetime NOT NULL,
  `titulek` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
",
	/*
	 * Struktura tabulky `pristupy`
	 * momentálně vynechána v systému, nepotřebná
	 */
	/*
	  "
	  --CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_pristupy` (
	  --  `id` int(11) NOT NULL AUTO_INCREMENT,
	  --  `kdo_ip` varchar(255) COLLATE utf8_czech_ci NOT NULL,
	  -- `odkud` varchar(255) COLLATE utf8_czech_ci NOT NULL,
	  --  `prohlizec` varchar(255) COLLATE utf8_czech_ci NOT NULL,
	  --  `skript` varchar(255) COLLATE utf8_czech_ci NOT NULL,
	  --  `cas` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  --  PRIMARY KEY (`id`)
	  --) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
	  ",
	 */

	/*
	 * Struktura tabulky `sekce`
	 */
	"
CREATE TABLE IF NOT EXISTS `{{!prefix!}}kiti_sekce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(30) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `kategorie` int(3) DEFAULT NULL,
  `claneksekce` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `blokall` tinyint(1) NOT NULL DEFAULT '0',
  `texyclaneksekce` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
");
    public $cachePaths = array(
	'cache' => '/cache/',
	'config' => '/cache/config/',
	'menu' => '/cache/menu/',
	'pages' => '/cache/pages/',
	'uvod' => '/cache/uvod/',
	'logsDB' => '/cache/logsDB/'
    );

    public function __construct() {
	;
    }

    public function run() {
	// 1. získáme informace
	if (!empty($_GET['install']) && $_GET['install'] == 'go') {
	    $out = $this->getInfoForDatabase();
	} else {
	    $out = $this->getInfoForPath();
	}
	return $out;
    }

    private function getInfoForPath() {
	$out['title'] = 'Instalace: ověření cest';
	$out['content'] = '<p>Pro instalaci systému potřebujeme ověřit některé informace, jako je možnost zápisu do určitých adresářů a zjisit přistupové údaje k databázi MySql. Níže můžete vidět stav zapisovatelnosti do potřebnách adresářů.</p>';
	$tpl = '<li class="{{!class!}}">{{!path!}}</li>';
	$tpl = '<tr><td>{{!path!}}</td><td>{{!exists!}}</td><td>{{!read!}}</td><td>{{!write!}}</td></tr>';
	$o = '';
	foreach ($this->cachePaths as $foo) {
	    $f['path'] = $this->ROOT . $foo;
	    $error = 0;
	    $err = '';
	    if (file_exists($f['path'])) {
		$f['exists'] = '<span class="green">ANO</span>';
		if (is_readable($f['path'])) {
		    $f['read'] = '<span class="green">ANO</span>';
		} else {
		    $f['read'] = '<span class="red">NE</span>';
		    $error = 1;
		}
		if (is_writable($f['path'])) {
		    $f['write'] = '<span class="green">ANO</span>';
		} else {
		    $f['write'] = '<span class="red">NE</span>';
		    $error = 1;
		}
	    } else {
		$f['exists'] = '<span class="red">NE</span>';
		$f['read'] = '<span class="red">NE</span>';
		$f['write'] = '<span class="red">NE</span>';
		$error = 1;
	    }
	    //print_r($f);
	    $t = new tpl();
	    $t->content = $f;
	    $o = array();
	    $o[] = $t->parseTpl($tpl);
	}
	if ($error == 1) {
	    $err = '<p style="color:black; font-weight: bolder;border:1px solid red;padding: 15px; margin:5px;width:90%;  background-color: white; font-size:14px;">Došlo k chybě, do jednoho nebo více adresářů nelze číst a/nebo zapisovat. Prosím nastavte práva pro čtení/zápis do adresářů označených <span class="red">NE</span>';
	} else {
	    $err = '<p><a href="index.php?install=go">Pokračovat v instalaci</a></p>';
	}

	$out['content'] = '<table style="text-align:left" border="1"><tr><th>cesta</th><th>existuje</th><th>čtení</th><th>zápis</th></tr>' . implode ('', $o) . '</table>' . $err;
	return $out;
    }

    private function getInfoForDatabase() {
	$out['title'] = 'Instalace: zjištění údajů o databázi';
	$out['content'] = '';
	$err = 0;
	$pr = '';

	$var['sqlHost'] = '';
	$var['errsqlHost'] = '';
	$var['sqlName'] = '';
	$var['errsqlName'] = '';
	$var['sqlUser'] = '';
	$var['errsqlUser'] = '';
	$var['sqlPass'] = '';
	$var['errsqlPass'] = '';
	$var['userName'] = '';
	$var['erruserName'] = '';
	$var['userPass'] = '';
	$var['erruserPass'] = '';
	$var['userMail'] = '';
	$var['erruserMail'] = '';

	$var['errSql'] = '';

	$tpl = <<< EEE
<form method="post" action="index.php?install=go">
    <p>Pro přihlášení do administrace systému potřebujete vytvořit uživatele Administrátora. </p>
<table>
    <tr><td><label for="userName">Jméno administrátora: </label>{{!erruserName!}}</td>	    <td><input id="userName" type="text" name="userName" value="{{!userName!}}"></td></tr>
    <tr><td><label for="userPass">Heslo administrátora: </label>{{!erruserPass!}}</td>	    <td><input id="userPass" type="text" name="userPass" value="{{!userPass!}}"></td></tr>
    <tr><td><label for="userMail">Email administrátora: </label>{{!erruserMail!}}</td>	    <td><input id="userMail" type="text" name="userMail" value="{{!userMail!}}"></td></tr>
</table>
<p>Pro ukládání dat používá tento systém mimo jiné i databázi MySql. Pro práci s ní potřebuje znát tyto údaje: </p>
<ul>
    <li>adresu databázového serveru</li>
    <li>jméno databáze</li>
    <li>Vaše uživatelské jméno</li>
    <li>Vaše uživatelsko heslo</li>
    <li>Prefix pro názvy tabulek. Pro případnou kolizi názvů tabulek různých systémů. 
    Povoleny jsou jen písmena anglické abecedy a maximální déla je 6 znaků.</li>
</ul>
<p>Tyto údaje vám dodá Váš poskytovatel hostingu.</p>
<br>
{{!errSql!}}
<table>
    <tr><td><label for="sqlHost">Adresa databáze: </label>{{!errsqlHost!}}</td>	    <td><input id="sqlHost" type="text" name="sqlHost" value="{{!sqlHost!}}"></td></tr>
    <tr><td><label for="sqlName">Jméno databáze: </label>{{!errsqlName!}}</td>	    <td><input id="sqlName" type="text" name="sqlName" value="{{!sqlName!}}"></td></tr>
    <tr><td><label for="sqlUser">Uživatelské jméno: </label>{{!errsqlUser!}}</td>   <td><input id="sqlUser" type="text" name="sqlUser" value="{{!sqlUser!}}"></td></tr>
    <tr><td><label for="sqlPass">Uživatelské heslo: </label>{{!errsqlPass!}}</td>   <td><input id="sqlPass" type="text" name="sqlPass" value="{{!sqlPass!}}"></td></tr>
    <tr><td><label for="prefix">Prefix: </label>{{!errprefix!}}</td>		    <td><input id="prefix" type="text" name="prefix" value="{{!prefix!}}"></td></tr>
    <tr><td cols="2"><input type="submit" name="ok" value="Nastavit a vytvořit tabulky"></td></tr>
</table>
</form>

EEE;
	if (!empty($_POST['ok'])) {
	    if (!empty($_POST['sqlHost'])) {
		$var['sqlHost'] = $_POST['sqlHost'];
	    }
	    if (empty($var['sqlHost'])) {
		$var['errsqlHost'] = '<p class="err">Nevyplnili jste adresu mysql serveru.</p>';
		$err = 1;
	    }
	    if (!empty($_POST['sqlName'])) {
		$var['sqlName'] = $_POST['sqlName'];
	    }
	    if (empty($var['sqlName'])) {
		$var['errsqlName'] = '<p class="err">Nevyplnili jste jméno databáze.</p>';
		$err = 1;
	    }
	    if (!empty($_POST['sqlUser'])) {
		$var['sqlUser'] = $_POST['sqlUser'];
	    }
	    if (empty($var['sqlUser'])) {
		$var['errsqlUser'] = '<p class="err">Nevyplnili jste uživatelské jméno.</p>';
		$err = 1;
	    }
	    if (!empty($_POST['sqlPass'])) {
		$var['sqlPass'] = $_POST['sqlPass'];
	    }
	    if (empty($var['sqlPass'])) {
		$var['errsqlPass'] = '<p class="err">Nevyplnili jste uživatelské heslo.</p>';
		$err = 1;
	    }

	    if (!empty($_POST['userName'])) {
		$var['userName'] = $_POST['userName'];
	    }
	    if (empty($var['userName'])) {
		$var['erruserName'] = '<p class="err">Nevyplnili jste jméno administrátora.</p>';
		$err = 1;
	    }
	    if (!empty($_POST['userPass'])) {
		$var['userPass'] = $_POST['userPass'];
	    }
	    if (empty($var['userPass'])) {
		$var['erruserPass'] = '<p class="err">Nevyplnili jste heslo administrátora.</p>';
		$err = 1;
	    }
	    if (!empty($_POST['userMail'])) {
		$var['userMail'] = $_POST['userMail'];
	    }
	    if (empty($var['userMail'])) {
		$var['erruserMail'] = '<p class="err">Nevyplnili jste email administrátora.</p>';
		$err = 1;
	    }

	    if (!empty($_POST['prefix'])) {
		$var['prefix'] = $_POST['prefix'];
		if (!preg_match("~^([a-zA-Z]){1,6}$~", $var['prefix'])) {
		    $var['errprefix'] = '<p class="err">Prefix obsahuje zakázané znaky nebo je příliš dlouhý.</p>';
		    $err = 1;
		}
	    }
	    if (empty($var['prefix'])) {
		// prefix není povinný!
		//$var['errprefix'] = '<p class="err">Nevyplnili jste prefix.</p>';
		//$err = 1;
	    }

	    /*
	     * pokud je vše ok, tak se pokusíme údaje uložit
	     */
	    if ($err === 0) {
		// vytvoření prefixu názvu tabulek
		if (!empty($var['prefix'])) {
		    $pr = $var['prefix'];
		    $var['prefix'] = $var['prefix'] . '_';
		}

		// templat pro konfigurační soubor
		$tpl1 = '
<?php

// administrátor: jméno a heslo
define("adminUser", "{{!userName!}}");
define("adminPass", "{{!userPass!}}");
define("adminMail", "{{!userMail!}}");

define("SQL_HOST", "{{!sqlHost!}}"); //název serveru na kterém běží databáze
define("SQL_DBNAME", "{{!sqlName!}}");  //název databáze ve které budou vytvořeny tabulky
define("SQL_USERNAME", "{{!sqlUser!}}");  //přihlašovací jméno k databázi
define("SQL_PASSWORD", "{{!sqlPass!}}");   //přihlašovací heslo k databázi

$prefix = "{{!prefix!}}";

define("Tabulka_clanky", $prefix . "kiti_clanky");
//define("Tabulka_pristupy", $prefix."kiti_pristupy");
define("Tabulka_sekce", $prefix . "kiti_sekce");
define("Tabulka_komentare", $prefix . "kiti_komentare");
define("Tabulka_kniha", $prefix . "kiti_kniha");
define("Tabulka_kategorie", $prefix . "kiti_kategorie");


';
		// proženeme to template systémem
		$t = new tpl();
		$t->content = $var;
		$save = $t->parseTpl($tpl1);

		// sestavíme cestu k souboru s konfigurací
		$file = $this->ROOT . $this->cachePaths['config'] . 'config.php';

		// uložíme konfigurák
		if (file_put_contents($file, $save) && chmod($file, 0777)) {
		    // máme uloženo, jdeme to použít :D
		    require_once $file;

		    // připojíme se k databázi
		    $dbs = new pager();
		    $dbs->logPath = $this->ROOT . $this->cachePaths['logsDB'];
		    $dbs->connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DBNAME, 'utf8', adminMail);
		    $t->content = array('prefix' => $var['prefix']);

		    // pokusíme se vytvořit všechny tabulky
		    foreach ($this->tables as $f) {
			$f = $t->parseTpl($f);
			if ($dbs->q($f)) {
			    $var['errSql'] .= '<p class="err">Povedlo se...</p>';
			} else {
			    $var['errSql'] .= '<p class="err">' . mysql_error() . '</p>';
			    $err = 1;
			}
		    }

		    // pokud jsme bez chyby, jdeme na novou stránku
		    if ($err === 0) {
			// vložíme úvodní článek...
			$v = $dbs->i(Tabulka_clanky, array(
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
			    'uvodniclanek' => 1
				    )
			);

			header("location: http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
			exit;
		    }
		} else {

		    // uložení konfiguráku se nepovedlo a proč
		    print_r(error_get_last());
		}
		// nastavíme prefix podle zadání uživatele
		$var['prefix'] = $pr;
	    }
	}
	$t = new tpl();
	$t->content = $var;
	$out['content'] = $t->parseTpl($tpl);
	return $out;
    }

}

