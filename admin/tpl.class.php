<?php

class tpl {

    /**
     * použití:
     * 	$tpl = new tpl;
     * 	$tpl->debug = false;//true
     * 	$tpl->pathToTemplate = ROOT . '/core/templates/;
     *
     *
     */
    private $tplName = 'default';
    /**
     * uvidíme jestli to necháme
     */
    private $tplVars = array(
    );
    public $content = array();
    public $pathToTemplate = './template/';
    public $debug = true;
    private $lang = array(
	'errorTplNotExists' => 'Chyba! Neexistuje soubor s templatem: ',
	'errorTplIsEmpty' => 'Chyba! Soubor s templatem je prázdný. Cesta k souboru: ',
    );
    public $tpl = '';
    public $oneline = false;
    public function __construct() {
	//$this->lang = array();
    }

    /**
     * načte požadovaný templat z adresáře template
     */
    private function getTpl() {
	// pokusí se získat název templatu
	$file = !empty($this->content['tplSrc']) ? $this->content['tplSrc'] : '';
	$file = (empty($file) ? 'obsah' : $file) . '.tpl';
	// cesta k templatu
	$path = $this->pathToTemplate . (empty($this->content['tpl']) ? 'default' : $this->content['tpl']) . '/' . $file;
	// Načtení templatu
	if (!file_exists($path)) {
	    trigger_error($this->lang['errorTplNotExists'] . $path, E_USER_ERROR);
	} else {
	    $this->tpl = file_get_contents($path);
	}
	if (trim($this->tpl) == '') {
	    trigger_error($this->lang['errorTplIsEmpty'] . $path, E_USER_ERROR);
	}
    }

    private function replaceContent($array) {
	/*
	  pokud poslaný text existuje v array bude nahrazen obsahem, jinak
	  bude vrácen text <!-- NEZNÁMÝ -> a značka
	  volají se části kódu, které jsou již vygenerované v ukaz clanok a hozené do array
	 */
	//echo '<br>' . $array[1];
	return (!empty($this->content[$array[1]]) ? $this->content[$array[1]] : ($this->debug === true ? (!isset($this->content[$array[1]]) ? '<!--NEZNÁMÝ: ' . $array[1] . ' //-->' : '') : ''));
	/*
	  if(array_key_exists($array[1], $this->vars)) {
	  return (!empty($this->str[$this->vars[$$array[1]]]) ? $this->str[$this->vars[$$array[1]]] : '<!--NEZNÁMÝ -> ' . $$array[1] . ' //-->');
	  } */
    }

    /**
     * vyhledá značky a zavolá callback funkci která provede náhrady
     * @return string
     */
    public function parseTpl($tpl) {
	$pattern = "~\{\{\!([a-zA-Z_]{1,})\!\}\}~i";
	return preg_replace_callback($pattern, array($this, 'replaceContent'), $tpl);
    }

    /**
     * Načte templat a z poslané array vloží její obsah do templatu, a tu vrátí
     * @param array $content
     * @return string
     */
    public function generate($content) {
	$this->content = $content;
	$this->getTpl();
	$tpl = $this->parseTpl($this->tpl);
	if ($this->oneline === true) {
	    $tpl = $this->oneline($tpl);
	}
	return $tpl;
    }

    public function oneline($txt) {
	$txt = strtr($txt, array("\r" => "", "\n" => "", "  " => ' ', "\t" => ''));
	return $txt;
    }

}
