<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author krteczek
 * @version 0.4
 * @time 30.06.2011 00:25
 */
/*
  $dbs = new pager();
  $dbs->tagStartStatic = '<span class="oo">|&#171;</span>';
  $dbs->tagStartDynamic = '<a href="%s?%s" title="%s" class="tip"><span class="oo">|&#171;</span></a>';
  $dbs->tagFirstDynamic = '<a href="%s?%s" title="%s" class="tip"><span class="oo">&lt;</span></a>';
  $dbs->tagFirstStatic = '<span class="oo" class="tip">&lt;</span>';
  $dbs->tagSecondDynamic = '<a href="%s?%s" title="%s" class="tip"><span class="oo">&gt;</span></a>';
  $dbs->tagSecondStatic = '<span class="oo" class="tip">&gt;</span>'; // konec
  $dbs->tagEndDynamic = '<a href="%s?%s" title="%s" class="tip"><span class="oo">&#187;|</span></a>';
  $dbs->tagEndStatic = '<span class="oo" class="tip">&#187;|</span>';
  $dbs->tagOdkazyDynamic = '<a href="%s?%s" title="%s %s - %s" class="tip"><span class="oo">%s</span></a>';
  $dbs->tagAktualniStrana = '<span class="oo" class="tip">%s</span>';
  // zobrazovat odkaz na předchozí stránku
  $dbs->zobrazovatPredchozi = true;

  $dbs->debug = false; //nastavení logování
  //$lang = !empty($lang) ? $lang : 'sk';
  $dbs->setLang(!empty($lang) ? $lang : 'cs');
  $dbs->logPath = ROOT_WEBU . '/logsDB/'; // kam se mají logy ukládat
  $dbs->connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DBNAME, 'utf8', 'krteczek01@gmail.com');
 */
// připojení

class db {

    private $sqlHost = '';
    private $sqlUser = '';
    private $sqlPass = '';
    private $sqlDb = '';
    private $sqlKodovani = '';
    private $emailAdmina = '';
    public $connect = '';
    public $logPath = '../log/';
    public $debug = false;
    public $error = false;
    public $errMsg = '';
    public $lng = 'cs';
    private $q = '';
    //zde je uložen dotaz
    // počítá množství dotazů
    private $countQuery = 0;
    public $texty = array(
	/* */
	'cs' => array(
	    'dbErrDuplicateValue' => 'Litujeme. Pokoušíte se uložit do databáze s indexem <b>unique</b> položku, která už v ní je. ',
	    'dbErrSelectDb' => 'Litujeme, vybraná databáze na připojeném serveru neexistuje.',
	    'dbErrMsg' => 'Litujeme, došlok chybě při práci s databázi. Chcete-li, kontaktujte prosím správce:',
	    'dbErrConnectDb' => 'Litujeme, nelze se připojit k databázovému serveru. Prosíme zkontrolujte pečlivě vámi zadané údaje, případné chyby opravte a znovu se pokuste připojit k databázi.',
	    'dbNotDeleted' => 'Litujeme, požadovaný záznam se nepodařilo smazat, nejspíš v databázi není.',
	    'dbErrValueNotInDb' => 'Litujeme, požadovaný záznam nebyl nalezen.',
	    'dbCountQuery' => 'Celkový počet databázových dotazů: ',
	),
	'sk' => array(
	    'dbErrDuplicateValue' => 'Ľutujeme. Pokúšate sa uložiť do databázy s indexom <b>unique</b> položku, ktorá sa v nej už nachádza. ',
	    'dbErrSelectDb' => 'Ľutujeme, vybraná databáza na pripojenom serveri neexistuje.',
	    'dbErrMsg' => 'Ľutujeme, došlo k chybe pri práci s databázou. Ak máte záujem, kontaktujte prosím správcu:',
	    'dbErrConnectDb' => 'Ľutujeme, nie je možné sa pripojiť k databázovému serveru. Prosím pozorne skontrolujte vami zadané údaje, prípadné chyby opravte a znovu sa pokúste pripojiť k databáze.',
	    'dbNotDeleted' => 'Ľutujeme, požadovaný záznam sa nepodarilo zmazať, pravdepodobne sa v databáze nenachádza.',
	    'dbErrValueNotInDb' => 'Ľutujeme, požadovaný záznam nebol nájdený.',
	    'dbCountQuery' => 'Celkový počet databázových dotazů: ',
	),
	'en' => array(
	    'dbErrDuplicateValue' => 'We are sorry. You would like to add an <b>unique</b> item to the database which already exist.',
	    'dbErrSelectDb' => 'We are sorry, chosen database has not been found on this server.',
	    'dbErrMsg' => 'We are sorry, we have noticed an error during working with the database. If you would like, please contact an administrator:',
	    'dbErrConnectDb' => 'We are sorry, it is not possible to make a connection with database server. Please check your added information carefully and try it again after your filled information are corrected.',
	    'dbNotDeleted' => 'We are sorry, requested item has not been deleted. The item does not exis in the database probably.',
	    'dbErrValueNotInDb' => 'We are sorry, requested item has not been found.',
	    'dbCountQuery' => 'Celkový počet databázových dotazů: ',
	),
	'de' => array(
	    'dbErrDuplicateValue' => 'de Litujeme. Pokoušíte se uložit do databáze s indexem <b>unique</b> položku, která už v ní je. ',
	    'dbErrSelectDb' => 'de Litujeme, vybraná databáze na připojeném serveru neexistuje.',
	    'dbErrMsg' => 'de Litujeme, došlok chybě při práci s databázi. Chcete-li, kontaktujte prosím správce:',
	    'dbErrConnectDb' => 'de Litujeme, nelze se připojit k databázovému serveru. Prosíme zkontrolujte pečlivě vámi zadané údaje, případné chyby opravte a znovu se pokuste připojit k databázi.',
	    'dbNotDeleted' => 'de Litujeme, požadovaný záznam se nepodařilo smazat, nejspíš v databázi není.',
	    'dbErrValueNotInDb' => 'de Litujeme, požadovaný záznam nebyl nalezen.',
	    'dbCountQuery' => 'Celkový počet databázových dotazů: ',
	),
	    /* */	    );
    public function __construct() {
	//require(ROOT . '/texty/texty.db.inc.php');
	$this->lang = $this->texty['cs'];
	date_default_timezone_set('Europe/Prague');
    }

    public function getLastQuery() {
	return '<p>' . $this->q . '</p>';
    }

    /**
     * Nastavení jazyka ve kterém má db vracet hlášky
     * @param string $lang
     */
    public function setLang($lang) {
	switch ($lang) {
	    case 'sk':
		$this->lang = $this->texty['sk'];
		$this->lng = $lang;
		break;
	    case 'en':
		$this->lang = $this->texty['en'];
		$this->lng = $lang;

		break;
	    case 'de':
		$this->lang = $this->texty['de'];
		$this->lng = $lang;

		break;
	    default:
		$this->lang = $this->texty['cs'];
		$this->lng = 'cs';
	}
    }

    /**
     * Připojení k databázi. Je použito perzistentní připojení kvůli rychlejšímu
     * zpracování db dotazů (není třeba čekat na další připojení k db)
     * @staticvar resource $connect
     * @return resource
     */
    public function connect($host = false, $user = false, $pass = false, $db = false, $kodovani = false, $email = false) {
	$this->emailAdmina = $email;
	static $connect = false;
	if ($connect !== false) {
	    // už jsme připojení
	    return $connect;
	}
	$connect = mysqli_connect($host, $user, $pass);
	if (!$connect) {
	    $this->error = true;
	    return $this->error($this->lang['dbErrConnectDb']);
	}

	if (!@mysql_select_db($db)) {
	    $this->error = true;
	    echo "tady";
	    return $this->error($this->lang['dbErrSelectDb']);
	}
	//mysql_query("SET NAMES '" . $kodovani . "'");
	mysql_query("SET CHARACTER SET '" . $kodovani . "'");
	$this->connect = $connect;
	return $connect;
    }

    /*
     * vykoná dotaz který byl poslán, bez nějakého rozlišování o co de a vrátí výsledek bez dalšího zpracování
     * surový dotaz, surové vrácení výsledku
     */
    public function q($query) {
	return $this->query($query);
    }

    public function query($query) {
	$this->q = $query;
	$v = mysql_query($this->q, $this->connect);
	return $v;
    }

    /**
     * Sestaví a vykoná insert dotaz
     * @param string $tableName
     * @param array $var
     * @param string $add
     * @return mixed integer - ok || string - error
     */
    public function i($tableName, $var, $add = '') {
	return $this->insert($tableName, $var, $add);
    }

    /**
     * Sestaví a vykoná insert dotaz
     * @param string $tableName
     * @param array $vars z array('name' => 'value',...) vygeneruje (`name`,...) VALUES ('value', ...)
     * @param string $vars prostě jen vloží co se má vložit
     * @param string $add případná podmínka (TODO: je vůbec třeba???)
     * @return mixed integer - ok || string - error
     */
    public function insert($tableName, $var, $add = '') {
	$this->q = "INSERT INTO `" . $tableName . "` ";
	$a = 0;
	$columns = '';
	$values = '';
	if (is_array($var)) {
	    foreach ($var as $key => $foo) {
		if ($a === 1) {
		    $columns .= ', ';
		    $values .= ', ';
		}
		$columns .= '`' . $key . '`';
		if (is_array($foo)) {
		    $values .= $this->O(serialize($foo));
		} else {
		    $values .= $this->O($foo);
		}
		$a = 1;
	    }
	    $this->q .= "(" . $columns . ") VALUES (" . $values . ") ";
	} else {
	    $this->q .= $var;
	}
	// samotné vykonání dotazu
	$v = mysql_query($this->q, $this->connect);
	$this->dbLog($this->q);
	if ($v === true) {
	    // OK, vrátíme id vloženého záznamu
	    return mysql_insert_id();
	}
	if (mysql_errno() == 1062) {
	    // pokus o vložení stejného záznamu do sloupce s unique
	    return $this->lang['dbErrDuplicateValue'] . ($this->debug === true ? mysql_error() : '');
	}
	// chyba v db dotazu
	return $this->error($this->q);
    }

    /**
     * Sestaví a vykoná update dotaz
     * @param string $tableName název tabulky
     * @param array $vars z array('name' => 'value',...) vygeneruje (`name`,...) VALUES ('value', ...)
     * @param string $vars prostě jen vloží co se má vložit
     * @param string $clause Podmímky dotazu
     * @return boolean|string true/false/error message
     */
    function u($tableName, $vars, $clause) {
	return $this->update($tableName, $vars, $clause);
    }

    function update($tableName, $vars, $clause) {
	$a = 0;
	$d = '';
	if (is_array($vars)) {
	    foreach ($vars as $key => $foo) {
		if ($a === 1) {
		    $d .= ', ';
		}
		if (is_array($foo)) {
		    $update = true;
		    foreach ($foo as $k => $f) {
			if (!in_array($k, array('COLUM', 'MATH', 'FOO'))) {
			    $update = false;
			}
		    }
		    if ($update === true) {
			// jedná se o počítání bindovaných proměnných
			$d .= "`" . $key . '` = `' . $foo['COLUM'] . '` ' . $foo['MATH'] . ' ' . $this->O($foo['FOO']);
		    } else {
			$d .= "`" . $key . '` = ' . $this->O(serialize($foo));
		    }
		} else {
		    $d .= "`" . $key . '` = ' . $this->O($foo);
		}
		$a = 1;
	    }
	} else {
	    $d = $vars;
	}
	$this->q = "UPDATE `" . $tableName . "` SET " . $d . ' ' . $clause;

	$v = mysql_query($this->q, $this->connect);

	$this->dbLog($this->q);
	if ($v) {
	    // pokud ke změně nedojde (nová data jsou stejná jako puvodní data) vrátí se false
	    $p = mysql_affected_rows();
	    if ($p > 0) {
		return true;
	    }
	    return false;
	} else if (mysql_errno() == 1062) {
	    // pokus o vložení stejného záznamu do sloupce s unique
	    return $this->lang['dbErrDuplicateValue'] . ($this->debug === true ? mysql_error() : '');
	}
	// chyba v db dotazu
	return $this->error($this->q);
    }

    /**
     * Select z databáze. Vstup je kompletní dotaz, výstup je:
     * @param string $query
     * @return array (úspěch): $array[0 >>> n][nazvy_jednotlivych_polozek]
     * @return bool (nic nenašlo): true
     * @return string (chyba v dotaze): zpráva
     * @return mixed array|bool|string
     */
    public function s($query) {
	return $this->select($query);
    }

    public function select($query) {
	/*	 * *
	 *  funkce pro vytáhnutí dat z databáze
	 *  vykoná dotaz, a zapíše do pole vrácené záznamy, ty vrátí skriptu
	 *  v podobě pole $nazev[pocet_zaznamu_od_nuly][nazvy_jednotlivych_polozek]
	 *  Pokud není nalezen záznam, vrátí true
	 * * */
	$this->q = $query;
	$v = mysql_query($this->q, $this->connect);
	$this->dbLog($this->q);
	if ($v) {
	    $p = mysql_num_rows($v);
	    if ($p > 0) {
		$out = array();
		for ($i = 1; $i <= $p; $i++) {
		    $r = mysql_fetch_assoc($v);
		    foreach ($r as $key => $foo) {
			$r[$key] = $foo;
		    }
		    $out[] = $r;
		}
		mysql_free_result($v);
		return $out;
	    }
	    $this->errMsg = $this->lang['dbErrValueNotInDb'] . ($this->debug === true ? $this->q : '');
	    return true;
	}
	return $this->error($this->q);
    }

    /**
     * Funkce pro smazání položky v db
     * @param string $dotaz
     * @return boolean true při vykonání
     * @return string text při chybě
     * @return mixed true || string
     */
    public function d($query) {
	return $this->delete($query);
    }

    public function delete($query) {
	$this->q = $query;
	$v = mysql_query($this->q, $this->connect);
	// TODO: doladit to zde, podle toho co by bylo lepší, jestli toto, is_bool, ...
	$this->dbLog($this->q);
	if ($v === true) {
	    return true;
	} else if ($v === false) {
	    return $this->lang['dbNotDeleted'] . $this->debug === true ? $this->q : ''; //. mysql_error() . '<br>' . $query;
	}
	return $this->error($this->q);
    }

    /**
     * metoda slouží k ošetření vstupních řetězců použitých v db dotazech
     * je ji možné zavolat až po inicializaci
     * metoda na ošetření používá funkci mysql_real_escape_string,
     * pokud není dostupná, použije mysql_escape_string, pokud
     * ani tato není dostupná, použije addslashes.
     * string umístí do apostrofu
     * @param string $polozka
     * @return string
     */
    public function o($polozka) {
	//alias k Osetri
	return $this->osetri($polozka);
    }

    /**
     * metoda slouží k ošetření vstupních řetězců použitých v db dotazech
     * je ji možné zavolat až po inicializaci
     * metoda na ošetření používá funkci mysql_real_escape_string,
     * pokud není dostupná, použije mysql_escape_string, pokud
     * ani tato není dostupná, použije addslashes.
     * string umístí do apostrofu
     * @param string $polozka
     * @return string
     */
    public function osetri($var) {
	if (is_array($var)) {
	    $var = serialize($var);
	}
	if (!is_numeric($var) && !is_bool($var)) {
	    if (strtoupper($var) == 'NULL') {
		$var = 'NULL';
	    } else if (strtolower(trim($var)) == 'now()') {
		$var = 'NOW()';
	    } else {
		$var = "'" . (function_exists('mysql_real_escape_string') ? mysql_real_escape_string($var, $this->connect) : (function_exists('mysql_escape_string') ? mysql_escape_string($var) : addslashes($var)) ) . "'";
	    }
	}
	return $var;
    }

    /*
     * zachytává chybové hlášky databáze, jednotlivé hlášky ukládá do souboru
     */

    private function error($query) {
	$log = $query . "\n\n" . mysql_errno() . "\n\n" . mysql_error();
	$this->saveQueryToFile($log, true);
	$this->errMsg = !empty($this->errMsg) ? $this->errMsg : $this->lang['dbErrMsg'] . $this->emailAdmina . ($this->debug === true ? '<p>' . nl2br($log) . '</p>' : '');
	return $this->errMsg;
    }

    /*
     * pokud je povolené logování, uloží informace o dotazech do souboru
     */
    private function saveQueryToFile($query, $err = false) {
	if ($this->debug === true) {
	    $date = date("Y_m_d-H");

	    $file = $this->logPath . $date . '_log.db.txt';

	    $r = "\t\t\t\t\t\t";
	    $typLogu = ($err === true ? 'E R R O R' : 'log');
	    $script = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' );
	    $database = $this->sqlDb;
	    $log = "\n" . date("d.m.Y-H:i:s") . $r . $typLogu . $r . $script . $r . $this->sqlDb . $r . $this->sqlHost . $r . $query . "";
	    if (((file_exists($file)) && (filesize($file) < (1073741824 * 4))) || (!file_exists($file))) {
		file_put_contents($file, $log, FILE_APPEND);
		chmod($file, 0777);
	    }
	}
    }

    /**
     * ukládá do logu všechny db dotazy a zároveň je počítá
     * @param string $query databázový dotaz
     */
    private function dbLog($query) {
	// připočteme počet dotazů
	$this->countQuery++;
	if ($this->debug === true) {
	    $this->saveQueryToFile($query);
	}
    }

    public function getCountQuery() {
	return '<p class="dbCountQuery">' . $this->lang['dbCountQuery'] . $this->countQuery . '</p>';
    }

}

final class pager extends db {

    public $getName = 'pager';
    public $countNumberFirst = 4;
    public $scriptName = '';
    public $start = 0;
    /**
     * nastavení jednotlivých částí odkazů v pageru
     * @var string pro všechny tyto proměnné
     */
    // začátek
    public $tagStartStatic = ' |&#171; ';
    public $tagStartDynamic = ' <a href="%s?%s" title="%s">|&#171;</a> ';
    // předchozí
    public $tagFirstDynamic = ' <a href="%s?%s" title="%s">%s&lt;</a> ';
    public $tagFirstStatic = ' %s&lt; ';
    // následující
    public $tagSecondDynamic = ' <a href="%s?%s" title="%s">&gt;</a> ';
    public $tagSecondStatic = ' &gt;%s ';
    // konec
    public $tagEndDynamic = ' <a href="%s?%s" title="%s">&#187;|</a> ';
    public $tagEndStatic = ' &#187;| ';
    public $tagOdkazyDynamic = ' <a href="%s?%s" title="%s %s - %s">%s</a> ';
    public $tagAktualniStrana = ' %s ';
    public $tagObaleniStart = '<p class="strankovani">';
    public $tagObaleniEnd = '</p>';
    public $oddelovac = ' &middot; ';
    public $pouzitOddelovac = false;
    public $texts = array(
	'cs' => array(
	    'goStartList' => 'Jít na začátek výpisu',
	    'goEndList' => 'Jít na konec výpisu',
	    'goBeforeList' => 'Jít na předchozí stránku výpisu',
	    'goNextList' => 'Jít na následující stranu výpisu',
	    'listItem' => 'Výpis položek ',
	    'outTitle' => 'Probíhá přesměrování',
	    'outObsah' => '<p>Právě probíhá automatické přesměrování na %s. Pokud náhodou k přesměrování nedojde, použijte prosím <a href="%s">tento odkaz</a>',
	),
	'sk' => array(
	    'goStartList' => 'Ísť na začiatok výpisu',
	    'goEndList' => 'Ísť na koniec výpisu',
	    'goBeforeList' => 'Ísť na predchádzajúcu stranu výpisu',
	    'goNextList' => 'Ísť na nasledujúcu stranu výpisu',
	    'listItem' => 'Výpis položiek ',
	    'outTitle' => 'Prebieha presmerovanie',
	    'outObsah' => '<p>Práve prebieha automatické presmerovanie na %s. Pokiaľ náhodou k presmerovaniu nedôjde, kliknite prosím <a href="%s">tento odkaz</a>',
	),
	'en' => array(
	    'goStartList' => 'Go on the beginning of the listing',
	    'goEndList' => 'Go at the end of the listing',
	    'goBeforeList' => 'Go to the previous page of the listing',
	    'goNextList' => 'Go to the next page of the listing',
	    'listItem' => 'Items listing ',
	    'outTitle' => 'Redirecting in progress',
	    'outObsah' => '<p>The page is going to be automaticly recirecting into page %s. If you occure some error during redirecting, please use <a href="%s">this link</a>',
	),
	'de' => array(
	    'goStartList' => 'Gehen Sie an den Anfang der Erklärung',
	    'goEndList' => 'Auf die letzte Seite der Preisliste',
	    'goBeforeList' => 'Gehe zur vorherigen Seite der Preisliste',
	    'goNextList' => 'Auf die nächste Seite der Preisliste',
	    'listItem' => 'Liste der Positionen',
	    'outTitle' => 'Laufende Re-Routing',
	    'outObsah' => '<p>The page is going to be automaticly recirecting into page %s. If you occure some error during redirecting, please use <a href="%s">this link</a>',
	),
    );
    public $l = 0;
    private $display = true;

    # mají se zobrazovat odkazy na předchozí/následující stránku?
    public $zobrazovatPredchozi = false;

    public function __construct() {
	parent::__construct();

	// spojíme texty do jedné array
	$this->texty = array_merge_recursive($this->texty, $this->texts);
    }

    public function start($tableName, $clause, $rows = 5, $scriptName = '') {
	/*
	  - předá parametry vnitřním proměným,
	  - zjistí název scriptu a ošetří přidání proměnné pro stránkování
	  - zjistí kolik je v databázi (tabulce) položek podle podmínek
	  - nastaví rozsah stránek, a správně dopočítá vše co je třeba (počáteční a koncovou stránku a vše mezi)
	 */

	$this->start = 0;

	# kolik má být záznamů na stránce
	$this->rows = $rows;

	# zjistíme co nám poslali v $l pokud je to číslo, necháme to číslem, jinak 0
	if ((!empty($_GET[$this->getName])) && (intval($_GET[$this->getName]) > 0)) {
	    $this->l = intval($_GET[$this->getName]);
	} else {
	    $this->l = 0;
	}

	$pozadovanaStrana = $this->l;
	# echo '<br>$pozadovanaStrana' . $pozadovanaStrana;
	# zjistíme počet řádků odpovídajících zaslané podmínce
	$pocetRadku = $this->countRows($tableName, $clause);
	# echo '<br>$pocetRadku' . $pocetRadku;
	# pokud je položek mín než je třeba na dvě stránky výpisu nebude se zobrazovat stránkování
	if ($pocetRadku <= $this->rows) {
	    $this->display = false;
	    return;
	}

	# sestavujeme adresu na kterou se bude odkazovat/přesměrovávat
	$this->scriptName = $this->scriptName($scriptName, 0);
	# echo '<br>$this->scriptName' . $this->jmenoScriptu;
	# Spočítáme kolik je celkem stránek výpisu
	$this->stranekVypisu = ceil($pocetRadku / $this->rows);

	# echo '<br>$this->stranekVypisu' . $this->stranekVypisu;
	# pokud je $pozadovanaStrana větší, než je $this->stranekVypisu, tak přesměrujeme 301 na $this->stranekVypisu
	if ($pozadovanaStrana > $this->stranekVypisu && $this->display <> false) {
	    $this->refreshTo($this->stranekVypisu);
	}

	# pokud je $pozadovanaStrana menší než 1, tak přesměrujeme 301 na $this->stranekVypisu
	if ($pozadovanaStrana < 1) {
	    $this->refreshTo(1);
	}

	# zjistíme první položku požadované stránky (odkud se má začít vypisovat)
	$this->start = ($this->l - 1) * $this->rows;

	#
	$this->start = ($this->start > $pocetRadku ? $pocetRadku : $this->start );
    }

    public function blokCisel() {
	// nutné kvůli generování více jazykových verzí najednou!
	//$language = empty($language) ? $this->lng : $language;
	//$lang = $this->texty[$language];
	/*
	  Tato část se stará o vytvoření systémů odkazů ve tvaru:
	  |<< < 1 · 2 · 3 · 4 · 5 · 6 · 7 · 8 · 9 > >>|
	 */
	if ($this->display === false) {
	    return '';
	}

	$aktScript = $_SERVER['SCRIPT_NAME'];
	$blok = '';
	$a = 0;
	$pocetOdkazu = (2 * $this->countNumberFirst) + 1;
	$pocetOdkazu = ($this->stranekVypisu < $pocetOdkazu ? $this->stranekVypisu : $pocetOdkazu);
	$prvnizBloku = $this->l - $this->countNumberFirst;
	$prvnizBloku = ($prvnizBloku < 1 ? 1 : $prvnizBloku);
	$poslednizBloku = $this->l + $this->countNumberFirst;
	$poslednizBloku = ($poslednizBloku > $this->stranekVypisu ? $this->stranekVypisu : $poslednizBloku);
	$poslednizBloku = ($prvnizBloku == 1 ? $pocetOdkazu : $poslednizBloku);
	/*
	  'goStartList' => 'Jít na začátek výpisu',
	  'goEndList' => 'Jít na konec výpisu',
	  'goBeforeList' => 'Jít na předchozí stránku výpisu',
	  'goNextList' => 'Jít na následující stranu výpisu',
	  'listItem' => 'Výpis položek ',
	 */
	$txtZacatekNeOdkaz = vsprintf($this->tagStartStatic, array('1'));
	$txtZacatexOdkaz = vsprintf($this->tagStartDynamic, array($aktScript, $this->scriptName . $this->getName . '=1', $this->lang['goStartList'], '1'));

	$txtKonecNeOdkaz = vsprintf($this->tagEndStatic, array($this->stranekVypisu));
	$txtKonecOdkaz = vsprintf($this->tagEndDynamic, array($aktScript, $this->scriptName . $this->getName . '=' . $this->stranekVypisu, $this->lang['goEndList'], $this->stranekVypisu));

	$txtPredchoziNeOdkaz = vsprintf($this->tagFirstStatic, array($this->l - 1));
	$txtPredchoziOdkaz = vsprintf($this->tagFirstDynamic, array($aktScript, $this->scriptName . $this->getName . '=' . ($this->l - 1), $this->lang['goBeforeList'], ($this->l - 1)));

	$txtNasledujNeOdkaz = vsprintf($this->tagSecondStatic, array($this->l + 1));
	$txtNasledujOdkaz = vsprintf($this->tagSecondDynamic, array($aktScript, $this->scriptName . $this->getName . '=' . ($this->l + 1), $this->lang['goNextList']));

	$naZacatek = ($this->l < 2 ? $txtZacatekNeOdkaz : $txtZacatexOdkaz);
	$naKonec = ($this->l == $this->stranekVypisu ? $txtKonecNeOdkaz : $txtKonecOdkaz);
	$predchozi = ($this->zobrazovatPredchozi === true ? ($this->l < 2 ? $txtPredchoziNeOdkaz : $txtPredchoziOdkaz) : '');
	$nasledujici = ($this->zobrazovatPredchozi === true ? ($this->l < $this->stranekVypisu ? $txtNasledujOdkaz : $txtNasledujNeOdkaz) : '');
	/*
	  zobrazování aktuálně nejbližších (-4 a +4) stránek výpisu
	  je nutno ošetřit příliš malé a příliš velké hodnoty
	 */
	for ($i = $prvnizBloku; $i <= $poslednizBloku; $i++) {
	    $pocatek = ($i * $this->rows) + 1 - $this->rows;
	    $konec = ($i * $this->rows);
	    $blok .= ( (($a == 1) && ($this->pouzitOddelovac === true) && (!empty($this->oddelovac))) ? $this->oddelovac : '');
	    if ($this->l == $i) {
		$blok .= vsprintf($this->tagAktualniStrana, array($i));
	    } else {
		$adresa = $this->scriptName . $this->getName . '=' . $i;
		$blok .= vsprintf($this->tagOdkazyDynamic, array($aktScript, $adresa, $this->lang['goNextList'], $pocatek, $konec, $i));
	    }
	    $a = 1;
	}
	$this->blok = $this->tagObaleniStart . $naZacatek . $predchozi . $blok . $nasledujici . $naKonec . $this->tagObaleniEnd;
	return "\n" . $this->blok;
    }

    /**
     * Zjistí, kolik je v databázi požadovaných záznamů
     * @param string $tableName
     * @param string $clause
     * @return integer
     */
    private function countRows($tableName, $clause) {
	$q = "SELECT COUNT(*) AS `pocet` FROM `" . $tableName . "` " . $clause;
	$v = $this->s($q);
	if (is_array($v)) {
	    return $v[0]['pocet'];
	} else if (is_bool($v)) {
	    return 0;
	} else {
	    return $this->error($q);
	}
    }

    /**
     * funkce vrací limit clausuli db dotazu
     * @return string
     */
    public function limit() {
	# Vrátí klauzuli LIMIT pro stránkování
	return " LIMIT " . $this->start . ", " . $this->rows;
    }

    public function refreshTo($kam) {
	# sestaví adresu pro přesměrování
	$jmenoScriptu = strtr($this->scriptName, array('&amp;' => '&'));
	$path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '?' . $jmenoScriptu . $this->getName . '=' . $kam;
	header("HTTP/1.1 301 Moved Permanently");
	header('location: ' . $path);
	die($this->lang['outTitle'] . vsprintf($this->lang['outObsah'], array($path, $path)));
    }

    /**
     * vrátí adresu bez stránkovací proměnné v ní
     * @param string $scriptName
     * @param integer $mod
     * @return string
     */
    private function scriptName($scriptName = '', $mod = 0) {
	$url = array();
	$scriptName = strtr($scriptName, array('&amp;' => '&'));
	if (!empty($scriptName)) {
	    $arr = explode('&', $scriptName);
	    foreach ($arr as $f) {
		$k = explode('=', $f);
		if ($k[0] <> $this->getName) {
		    $url[] = $k[0] . (!empty($k[1]) ? '=' . $k[1] : '');
		}
	    }
	}
	$amp = ($mod == 0 ? '&amp;' : '&');
	if (!empty($url)) {
	    $url = join($amp, $url) . $amp;
	    if ($mod !== 0) {
		return $url;
	    }
	}
	return (!empty($url) ? $url : '');
    }

}



