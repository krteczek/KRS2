<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class adminLogin {

    public $users = array(
	'user' => array(
	    'pass' => 'heslo',
	    'mail' => 'email@email.cz',
	    'role' => 5,
	    'name' => 'User Name',
	),
    );
    private $sessArray = array(
	'loged' => 'USER_PRIHLASEN',
	'name' => 'USER_JMENO',
	'email' => 'USER_EMAIL',
	'role' => 'USER_PRAVA',
    );
    public $nameSesAntispam = 'mnjsdfghjhg';
    public $namePostAntispam = 'bublak';
    /**
     * požadované práva pro tu stránku
     * @var integer
     */
    public $requireRole = 0;
    /**
     * název odhlašovací proměnné
     * @var string
     */
    private $getLogout = 'logout';

    protected $logged = false;
    /**
     * vrací zprávy,
     * @var array(title, content)
     */
    public $msg = array();
    private $la = array(
	'cs' => array(
	    // formulář
	    'LOGIN_FORM_TITLE' => 'Přihlášení uživatele do systému',
	    'LOGIN_FORM_TXT_MSG2' => '<p>Zde se můžete přihlásit na Váš účet.</p>',
	    'LOGIN_FORM_TXT_SUBMIT' => ' Přihlásit ',
	    'LOGIN_FORM_TXT_PASS' => 'Heslo: ',
	    'LOGIN_FORM_TXT_NAME' => 'Jméno: ',
	    'LOGIN_ERR_LOGIN' => '<p>Lituji, přihlášení se nezdařilo, zadali jste špatné přihlašovací údaje</p>',
	    'ROLE_LEVEL_IS_LOW_TITLE' => 'Nedostatečné oprávnění',
	    'ROLE_LEVEL_IS_LOW_CONTENT' => '<p>Lituji, jste sice přihlášen, ale Vaše oprávnění není dostačující pro tento dokument</p>',
	    'HLAVA' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
	    'LOGIN_INFO_PRIHLASENY' => 'Přihlášený uživatel: %s. %s',
	    'LOGIN_ODKAZ_ODHLASENI' => '<a href="%s" title="Odhlásit se z účtu">Odhlásit</a>',
	    'rOutTitle' => '<h1>Probíhá přesměrování</h1>',
	    'rOutObsah' => '<p>Právě probíhá automatické přesměrování na %s. Pokud náhodou k přesměrování nedojde, použijte prosím <a href="%s">tento odkaz</a>',
	),
	'sk' => array(
	    // formulář
	    'LOGIN_FORM_TITLE' => 'Přihlášení uživatele do systému',
	    'LOGIN_FORM_TXT_MSG2' => '<p>Zde se můžete přihlásit na Váš účet.</p>',
	    'LOGIN_FORM_TXT_SUBMIT' => ' Přihlásit ',
	    'LOGIN_FORM_TXT_PASS' => 'Heslo: ',
	    'LOGIN_FORM_TXT_NAME' => 'Jméno: ',
	    'LOGIN_ERR_LOGIN' => '<p>Lituji, přihlášení se nezdařilo, zadali jste špatné přihlašovací údaje</p>',
	    'ROLE_LEVEL_IS_LOW_TITLE' => 'Nedostatečné oprávnění',
	    'ROLE_LEVEL_IS_LOW_CONTENT' => '<p>Lituji, jste sice přihlášen, ale Vaše oprávnění není dostačující pro tento dokument</p>',
	    'HLAVA' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
	    'LOGIN_INFO_PRIHLASENY' => 'Přihlášený uživatel: %s. %s',
	    'LOGIN_ODKAZ_ODHLASENI' => '<a href="%s" title="Odhlásit se z účtu">Odhlásit</a>',
	    'rOutTitle' => '<h1>Probíhá přesměrování</h1>',
	    'rOutObsah' => '<p>Právě probíhá automatické přesměrování na %s. Pokud náhodou k přesměrování nedojde, použijte prosím <a href="%s">tento odkaz</a>',
	),
	'en' => array(
	    // formulář
	    'LOGIN_FORM_TITLE' => 'Přihlášení uživatele do systému',
	    'LOGIN_FORM_TXT_MSG2' => '<p>Zde se můžete přihlásit na Váš účet.</p>',
	    'LOGIN_FORM_TXT_SUBMIT' => ' Přihlásit ',
	    'LOGIN_FORM_TXT_PASS' => 'Heslo: ',
	    'LOGIN_FORM_TXT_NAME' => 'Jméno: ',
	    'LOGIN_ERR_LOGIN' => '<p>Lituji, přihlášení se nezdařilo, zadali jste špatné přihlašovací údaje</p>',
	    'ROLE_LEVEL_IS_LOW_TITLE' => 'Nedostatečné oprávnění',
	    'ROLE_LEVEL_IS_LOW_CONTENT' => '<p>Lituji, jste sice přihlášen, ale Vaše oprávnění není dostačující pro tento dokument</p>',
	    'HLAVA' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
	    'LOGIN_INFO_PRIHLASENY' => 'Přihlášený uživatel: %s. %s',
	    'LOGIN_ODKAZ_ODHLASENI' => '<a href="%s" title="Odhlásit se z účtu">Odhlásit</a>',
	    'rOutTitle' => '<h1>Probíhá přesměrování</h1>',
	    'rOutObsah' => '<p>Právě probíhá automatické přesměrování na %s. Pokud náhodou k přesměrování nedojde, použijte prosím <a href="%s">tento odkaz</a>',
	),
	'de' => array(
	    // formulář
	    'LOGIN_FORM_TITLE' => 'Přihlášení uživatele do systému',
	    'LOGIN_FORM_TXT_MSG2' => '<p>Zde se můžete přihlásit na Váš účet.</p>',
	    'LOGIN_FORM_TXT_SUBMIT' => ' Přihlásit ',
	    'LOGIN_FORM_TXT_PASS' => 'Heslo: ',
	    'LOGIN_FORM_TXT_NAME' => 'Jméno: ',
	    'LOGIN_ERR_LOGIN' => '<p>Lituji, přihlášení se nezdařilo, zadali jste špatné přihlašovací údaje</p>',
	    'ROLE_LEVEL_IS_LOW_TITLE' => 'Nedostatečné oprávnění',
	    'ROLE_LEVEL_IS_LOW_CONTENT' => '<p>Lituji, jste sice přihlášen, ale Vaše oprávnění není dostačující pro tento dokument</p>',
	    'HLAVA' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
	    'LOGIN_INFO_PRIHLASENY' => 'Přihlášený uživatel: %s. %s',
	    'LOGIN_ODKAZ_ODHLASENI' => '<a href="%s" title="Odhlásit se z účtu">Odhlásit</a>',
	    'rOutTitle' => '<h1>Probíhá přesměrování</h1>',
	    'rOutObsah' => '<p>Právě probíhá automatické přesměrování na %s. Pokud náhodou k přesměrování nedojde, použijte prosím <a href="%s">tento odkaz</a>',
	),
    );

    public function __construct() {
	$this->lng = $this->la['cs'];

	$this->lng['LOGIN_ODKAZ_ODHLASENI'] = vsprintf($this->lng['LOGIN_ODKAZ_ODHLASENI'], array($_SERVER['PHP_SELF'] . '?' . $this->getLogout,));

	if (isset($_GET[$this->getLogout])) {
	    $this->logout();
	}
    }

    public function loged() {
	if (!empty($_SESSION[$this->sessArray['loged']])) {
	    if (!empty($_SESSION[$this->sessArray['role']]) && $_SESSION[$this->sessArray['role']] >= $this->requireRole) {
		$this->logged = true;
		$this->msg = vsprintf($this->lng['LOGIN_INFO_PRIHLASENY'], array($this->users[$_SESSION[$this->sessArray['name']]]['name'], $this->lng['LOGIN_ODKAZ_ODHLASENI']));
		return true;
	    }
	    $this->msg = array('title' => $this->lng['ROLE_LEVEL_IS_LOW_TITLE'], 'content' => $this->lng['ROLE_LEVEL_IS_LOW_CONTENT']);
	    return false;
	}
	$this->msg = $this->login();
	return false;
    }

    public function status() {
	return $this->logged;
    }

    public function logout() {
	foreach ($this->sessArray as $f) {
	    $this->deleteSession($f);
	}
	$this->refresh($_SERVER['PHP_SELF']);
    }

    private function login() {
	$tpl = new tpl;
	$t = $this->lng['LOGIN_FORM_TITLE'];
	$msg = '';
	$s['path'] = $_SERVER['PHP_SELF'] . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
	if (!empty($_POST['ok']) && ($this->antiXSSControl() === true)) {
	    $name = !empty($_POST['name']) ? $_POST['name'] : '';
	    $pass = !empty($_POST['pass']) ? $_POST['pass'] : '';
	    if (array_key_exists($name, $this->users)) {
		if (!empty($this->users[$name]['pass']) && ($this->users[$name]['pass'] === $pass)) {

		    session_regenerate_id();
		    $_SESSION[$this->sessArray['loged']] = true;
		    $_SESSION[$this->sessArray['role']] = $this->users[$name]['role'];
		    $_SESSION[$this->sessArray['mail']] = $this->users[$name]['mail'];
		    $_SESSION[$this->sessArray['name']] = $name;

		    $this->refresh($s['path']);
		}
	    }
	    $msg .= $this->lng['LOGIN_ERR_LOGIN'];
	}

	$s['content'] = $this->lng['LOGIN_FORM_TXT_MSG2'] . $msg;
	$s['lngName'] = $this->lng['LOGIN_FORM_TXT_NAME'];
	$s['lngPass'] = $this->lng['LOGIN_FORM_TXT_PASS'];
	$s['lngSubmit'] = $this->lng['LOGIN_FORM_TXT_SUBMIT'];
	$s['antispam'] = $this->antiXSSInput();

	$tp = <<< EEE
<form method="post" action="{{!path!}}">
{{!content!}}
	<label for="name">{{!lngName!}}</label><input type="text" name="name" id="name" size="10" maxlength="12"><br>
	<label for="pass">{{!lngPass!}}</label><input type="password" name="pass" id="pass" size="10"><br>
	<input type="submit" name="ok" value="{{!lngSubmit!}}">{{!antispam!}}
</form>
EEE;
	$tpl->content = $s;
	$s = array('title' => $t, 'content' => $tpl->parseTpl($tp));
	unset($tpl);
	return $s;
    }

    public function randomKeys($lenght = 32) {
# randomkeys generuje náhodné texty z připraveného zdroje znaků
	$pattern = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
	$key = '';
	for ($i = 0; $i < $lenght; $i++) {
	    $key .= $pattern{rand(0, (strlen($pattern) - 1))};
	}
	return $key;
    }

    /*
     * Ochrana před kros site scriptingem
     */

    public function antiXSSInput() {
	# vytvoření antispam input tagu
	$key = $this->randomKeys();
	$_SESSION[$this->nameSesAntispam] = $key;
	return '<input type="hidden" name="' . $this->namePostAntispam . '" value="' . $key . '">';
    }

    /*
     * kontroluje obsah antiXss Postu a antiXss session
     * @return boolean true/false
     */

    public function antiXSSControl() {
	// kontrola antiXSS
	if ((!empty($_POST[$this->namePostAntispam])) && (!empty($_SESSION[$this->nameSesAntispam]))) {
	    if (($_POST[$this->namePostAntispam] == $_SESSION[$this->nameSesAntispam])
		    &&
		    (strlen($_POST[$this->namePostAntispam])) == (strlen($_SESSION[$this->nameSesAntispam]) )
	    ) {
		$this->deleteSession($this->nameSesAntispam);
		return true;
	    }
	    $this->deleteSession($this->nameSesAntispam);
	}
	return false;
    }

    public function refresh($kam) {
	$path = 'http://' . $_SERVER['SERVER_NAME'] . $kam;
	header('location: ' . $path);
	die($this->lng['rOutTitle'] . vsprintf($this->lng['rOutObsah'], array($path, $path)));
    }

    /*
     * pomocná metoda pro metodu odhlaseni(),
     * zde se provede zruseni poslaných  _SESSION promennych
     * @param string name
     *
     */

    private function deleteSession($name) {
	if (isset($_SESSION[$name])) {
	    $_SESSION[$name] = NULL;
	    unset($_SESSION[$name]);

	    if (isset($_SESSION[$name])) {
		$this->deleteSession($name);
	    }
	}
	return;
    }

}


class LoginOld {

    private $la = array(
	'cs' => array(
	    'TXT_ERR_TIME_LIMIT' => '<p class="err">Lituji vypršel časový limit. Prosím zopakujte odeslání.</p>',
	    'LOGIN_ERR_LOGIN' => '<p>Lituji, přihlášení se nezdařilo, zadali jste špatné přihlašovací údaje</p>',
	    'LOGIN_TXT_UVITANI' => 'Vítejte uživateli %s, právě jste byli úspěšně přihlášeni na Váš účet.',
	    'LOGIN_ODKAZ_ODHLASENI' => '<a href="%s?%s" title="Odhlásit se z účtu">Odhlásit</a>',
	    'LOGIN_ODKAZ_PRIHLASENI' => '<a href="./login.php" title="Přihlásit se" class="lll"><span>Přihlásit se</span></a>',
	    'LOGIN_INFO_PRIHLASENY' => 'Přihlášený uživatel: %s. %s',
	    'LOGIN_INFO_ODHLASENY' => '<p>Právě jste byl úspěšně odhlášen.</p>',
	    'LOGIN_FORM_TXT_MSG2' => '<p>Zde se můžete přihlásit na Váš účet.</p>',
	    'LOGIN_FORM_TXT' => '<form method="post" action="%s">%s<label for="name">Jméno: </label><input type="text" name="name" id="name" size="10" maxlength="10"><br><label for="pass">Heslo: </label><input type="password" name="pass" id="pass" size="10"><br><input type="submit" name="ok" value="%s">%s</form>',
	    'LOGIN_FORM_TXT_SUBMIT' => ' Přihlásit ',
	    'HLAVA' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
	    'LNG_PRESMEROVANI' => '<p>Probíhá přesměrování na stránku: %s. Pokud k přesměrování nedojde, prosím následujte tento odkaz: <a href="%s">%s</a>',
	),
    );
    public $lng = array();
# proměnná do které se ukládají hlášky
    public $errMsg = '';

# chyba vypršení platnosti session
//var $errTimeLimit = TXT_ERR_TIME_LIMIT;
# špatné přihlašovací údaje
//var $errLogin = LOGIN_ERR_LOGIN;
# přivítání přihlášeného uživatele
    var $msgLogin = LOGIN_TXT_UVITANI;

# veřejné proměnné, informace ostavu uživatele, tagy pro přhlášení/odhlášení...
    var $infoDoMenu = '';
    var $infoDoObsahu = '';

# vnitřní proměnné obsahující texty
    var $tagOdhlaseni = LOGIN_ODKAZ_ODHLASENI;
    var $tagPrihlaseni = LOGIN_ODKAZ_PRIHLASENI;
    var $infoPrihlaseny = LOGIN_INFO_PRIHLASENY;
    var $infoOdhlaseny = LOGIN_INFO_ODHLASENY;

# texty k formuláři
    var $txtFormMsg2 = LOGIN_FORM_TXT_MSG2;
    var $txtForm = LOGIN_FORM_TXT;

# proměnná v které se nese adresa aktualní stránky
    var $aktualniSoubor = '';

# Názvy $_POST proměnných pro přihlašovací formulář
    var $nameLogin = 'jmeno';
    var $namePass = 'heslo';
    var $nameSubmit = 'prihlasit';
    var $valueSubmit = LOGIN_FORM_TXT_SUBMIT;

# Jméno $_GET proměnné pro odhlášení z administrace
    var $nameGetOdhlasit = 'odhlasit';

# jména session proměnných
    var $sessArray = array(
	1 => 'USER_PRIHLASEN',
	2 => 'USER_JMENO',
	3 => 'USER_EMAIL',
	4 => 'USER_PRAVA',
	5 => 'USER_IDENTIFY',
	6 => 'USER_ODHLASEN',
	7 => 'MSG_PO_LOGINU',
	8 => 'ANTISPAM',
    );
# přihlašovací údaje, je to array,
#  klíč je jméno a hodnota je array s informacemi o uživatelích
    var $usersArray = array(
	'krteczek' => array(
	    'pass' => 'krteczek',
	    'fullName' => 'Petr Vaněk',
	    'email' => 'krteczek@jaknato.com',
	    'prava' => 3,
	),
    );
# ******************
# zavrženo!
# proměnné na které je citlivý přihlašovací formulář
# (proste pokud toto v adrese, tak musí být user přihlášen,
# klíč je název hledané proměnné a hodnota je úroveň práv kterou musí dotyčný na této stránce mít
# array
//
//
# zde se uchovávají informace o uživatelích (přetahují se z sessions, při znovunačtení stránky)
    var $userLogin = false;
    var $userPrava = false;
    var $userJmeno = false;
    var $userEmail = false;
    var $userHashIdentify = false;

# porovnají se práva a podle toho se zde uloží výsledek
    var $ukazatStranku = false;

############################################################xx
# Část věnovaná ochraně formuláře
# Antispam input
    var $antispamInput = '<input type="hidden" name="%s" value="%s">';
# název antispam inputu
    var $nameAntispam = 'antispam';
# délka antispam textu
    var $lenAntiText = 16;
    var $pozadovanaPrava = 0;
    function __construct() {
	$this->lng = $this->la['cs'];
# nastavíme adresu aktualního scriptu pro přesměrování
	$this->aktualniSoubor = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

# předání názvů session z array do proměnných
	$this->nameSesUserLogin = $this->sessArray[1];
	$this->nameSesUserJmeno = $this->sessArray[2];
	$this->nameSesUserEmail = $this->sessArray[3];
	$this->nameSesUserPrava = $this->sessArray[4];
	$this->nameSesUserOdhlasen = $this->sessArray[5];
	$this->nameSesHashIdentify = $this->sessArray[6];
	$this->nameSesUserPoLoginu = $this->sessArray[7];
	$this->nameSesAntispam = $this->sessArray[8];

# předání obsahu session proměnných do vnitřních proměnných
	$this->userHashIdentify = (!empty($_SESSION[$this->nameSesHashIdentify]) ? $this->kontrolaIdentifikace($_SESSION[$this->nameSesHashIdentify]) : false);
	$this->userLogin = (!empty($_SESSION[$this->nameSesUserLogin]) ? $_SESSION[$this->nameSesUserLogin] : false);
	$this->userPrava = (!empty($_SESSION[$this->nameSesUserPrava]) ? $_SESSION[$this->nameSesUserPrava] : false);
	$this->userJmeno = (!empty($_SESSION[$this->nameSesUserJmeno]) ? $_SESSION[$this->nameSesUserJmeno] : false);
	$this->userEmail = (!empty($_SESSION[$this->nameSesUserEmail]) ? $_SESSION[$this->nameSesUserEmail] : false);
	$this->ukazatStranku = $this->porovnaniPrav();

# odhlášení uživatele
	if (isset($_GET[$this->nameGetOdhlasit])) {
	    $this->odhlaseni();
	}
# doplnění adresy do odhlašovacího tagu
	$this->tagOdhlaseni = ($this->userLogin == 1 ? vsprintf($this->tagOdhlaseni, array($this->aktualniSoubor, $this->nameGetOdhlasit)) : '');

# kontrola, zda jsou odeslané přhlašovací údaje
	if ((!empty($_POST[$this->nameSubmit])) && ($_POST[$this->nameSubmit] == $this->valueSubmit)) {
	    if ($this->antispamControl() === true) {
		if ((!empty($_POST[$this->nameLogin])) && (!empty($_POST[$this->namePass]))) {
		    $this->prihlaseni($_POST[$this->nameLogin], $_POST[$this->namePass]);
		}
	    }
	    $this->errMsg .= $this->lng['LOGIN_ERR_LOGIN'];
	}


# podle stavu uživatele nastavíme hlášky do menu a obsahu
	$this->__userInfo();
    }

    function __userInfo() {
# slouží k zobrazení informace o přihlášeném
# uživateli (jeho jméno) a odkazu pro odhlášení
	$text = ''; //'<h3>Login</h3>';
	if ($this->userLogin == 1) {
	    $this->infoDoMenu = '<ul><li>' . $this->tagOdhlaseni . '</li></ul>';
	    $this->infoDoObsahu = vsprintf($this->infoPrihlaseny, array($this->userJmeno, $this->tagOdhlaseni));
	    $this->tagPrihlaseni = $this->tagOdhlaseni;
	} else {
	    $this->infoDoMenu = '<ul><li>' . $this->tagPrihlaseni . '</li></ul>';
	    if (!empty($_SESSION[$this->nameSesUserOdhlasen])) {
		$this->infoDoObsahu = $this->infoOdhlaseny;
		$this->smazejSession($this->nameSesUserOdhlasen);
	    }
	}
	return $text;
    }

    function prihlaseni($login, $pass) {
	if (array_key_exists($login, $this->usersArray)) {
	    if ($pass == $this->usersArray[$login]['pass']) {
		session_regenerate_id();
		$_SESSION[$this->nameSesUserLogin] = 1;
		$_SESSION[$this->nameSesUserJmeno] = $this->usersArray[$login]['fullName'];
		$_SESSION[$this->nameSesUserEmail] = $this->usersArray[$login]['email'];
		$_SESSION[$this->nameSesUserPrava] = $this->usersArray[$login]['prava'];
		$this->prava = $this->usersArray[$login]['prava'];
# tato session je kvuli vypsani přihlašovací zpravy
		$_SESSION[$this->nameSesUserPoLoginu] = 1;
		$this->presmeruj();
	    }
	}
	$this->errMsg .= $this->lng['LOGIN_ERR_LOGIN'];
	return false;
    }

    function getPrihlasen() {
	if ($this->userLogin == true) {
	    return true;
	}
	return false;
    }

    /**
      function kontrolaPrav1()
      {
      # kontrolujeme zda má uživatel dostatečná
      # práva pro prohlížení aktualní stránky
      # toto půjde odstranit, bude nazrazeno porovnaniPrav()
      reset($this->getName);
      foreach($this->getName as $k => $f)
      {
      if(isset($_GET[$k]))
      {
      if($this->userPrava < $f)
      {
      # nemá dostatečná práva
      return false;
      }
      }
      }
      return true;
      }
     * */
    function porovnaniPrav() {
# metoda porovná práva uživatele s požadovanými právy scriptu
	if ($this->pozadovanaPrava <= $this->userPrava) {
	    return true;
	}
	return false;
    }

    function kontrolaIdentifikace($id) {
	$ip = !empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';
	$prohlizec = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER["HTTP_USER_AGENT"] : '';
	$forward = !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : '';
	$remote = !empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';
	$aktId = sha1($ip . $prohlizec . $forward . $remote);
	if ($aktId == $id) {
	    return $id;
	}
# je to špatne, odhlasime usera
	$this->odhlaseni();
    }

    function odhlaseni() {
# metoda provede smazání nastavených session proměnných uvedených v array $sessArray
	if (!empty($_SESSION[$this->nameSesUserLogin])) {
	    reset($this->sessArray);
	    foreach ($this->sessArray as $key => $foo) {
		$this->smazejSession($foo);
	    }
	    $_SESSION[$this->nameSesUserOdhlasen] = 1;
	}
	$this->presmeruj();
    }

    function smazejSession($name) {
# pomocná metoda pro metodu odhlaseni(),
# zde se provede zruseni vsech _SESSION promennych
	if (isset($_SESSION[$name])) {
	    $_SESSION[$name] = NULL;
	    unset($_SESSION[$name]);

# pojistka, kdyby to napoprvé nezebralo?
# sněkde jsem to viděl, nemužu to najit
# hledej šmudlo
	    if (isset($_SESSION[$name])) {
		$this->smazejSession($name);
	    }
	}
    }

    function presmeruj() {
# metoda slouží k přesměrování na novou stránku (přihlášení/odhlášení)
	header("Location: " . $this->aktualniSoubor);
	die($this->lng['HLAVA'] . vsprintf($this->lng['LNG_PRESMEROVANI'], array($this->aktualniSoubor, $this->aktualniSoubor, $this->aktualniSoubor)));
    }

    function form() {
	$form = '';
# kontrola jestli je ser přihlášen , tak bude vrácen prázdný řetězec
	if ($this->userLogin === false) {
	    $adresa = $this->aktualniSoubor;
	    $kecy = $this->txtFormMsg2;
	    $form .= vsprintf($this->txtForm, array($adresa, $kecy, $this->nameLogin, $this->nameLogin, $this->nameLogin, $this->namePass, $this->namePass, $this->namePass, $this->nameSubmit, $this->valueSubmit, $this->antispamInput()));
	}
	return $form;
    }

    function randomKeys($lenght = 32) {
# randomkeys generuje náhodné texty z připraveného zdroje znaků

	$pattern = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
	$key = '';
	for ($i = 0; $i < $lenght; $i++) {
	    $key .= $pattern{rand(0, (strlen($pattern) - 1))};
	}
	return $key;
    }

    function antispamInput() {
# vytvoření antispam input tagu
	$key = $this->randomKeys($this->lenAntiText);
	$_SESSION[$this->nameSesAntispam] = $key;
	return vsprintf($this->antispamInput, array($this->nameAntispam, $key));
    }

    function antispamControl() {
# kontrola antispamu
	if (!empty($_POST[$this->nameAntispam])) {
	    if (!empty($_SESSION[$this->nameSesAntispam])) {
		if (($_POST[$this->nameAntispam] == $_SESSION[$this->nameSesAntispam]) && (strlen($_POST[$this->nameAntispam]) === $this->lenAntiText) && (strlen($_POST[$this->nameAntispam])) == (strlen($_SESSION[$this->nameSesAntispam]))) {
		    $this->smazejSession($this->nameSesAntispam);
		    return true;
		}
		$this->smazejSession($this->nameSesAntispam);
	    }
	}
	return false;
    }

}
?>