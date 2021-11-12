<?php

ob_start();
error_reporting(E_ALL | E_STRICT);
//error_reporting(0);
session_start();

require_once dirname(__FILE__) . "/config.php";

//soubor s nastavením datrabáze atd
$configPath = ROOT_WEBU . '/cache/config/config.php';
/*
 * ověření, že je systém nainstalován (existuje soubor ./cache/config/config.php)
 * pokud není, jdeme instalovat
 */
if (!file_exists($configPath)) {
	//echo"jo";
    require_once(ROOT_WEBU . '/admin/install.php');
    $clanek = new install;
    $clanek->cachePaths = $cachePaths;
    $clanek->ROOT = ROOT_WEBU;
    $s = $clanek->run();
} else {

    require_once ($configPath);
    /*
     * připojíme k databázi...
     */
    $dbs = new pager();
    /*
     * zatím nepotřebné...
     * /
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

     /*
     */
    $dbs->debug = true; //nastavení logování

    $dbs->setLang('cs');
    $dbs->logPath = ROOT_WEBU . $cachePaths['logsDB'];; // kam se mají logy ukládat
    $dbs->connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DBNAME, 'utf8', adminMail);


    /*
     * generování obsahu proměnných na bindování do templatu
     */
    $clanek = new clanky;
    $clanek->cl = 'c';
    $clanek->sl = 's';
    $clanek->kl = 'k';
    $clanek->kniha = 'book';
    $clanek->file_path_uvod = ROOT_WEBU . $cachePaths['uvod'] . 'uvodni-clanek.serialized.tpl'; //úvodní článek
    $clanek->file_path_menu = ROOT_WEBU . $cachePaths['menu'] . 'menu.tpl'; // menu
    $clanek->file_path_pages = ROOT_WEBU . $cachePaths['pages'] . ''; //cache článků

    $s = $clanek->run();

}

// to co je nabindované hodíme na web
$tpl = new tpl;
$tpl->debug = true; //true
$tpl->pathToTemplate = ROOT_WEBU . '/templates/';
$s['tplSrc'] = 'obsah';

// pokud není nainstalováno, nemůžeme se ptát na počet dotazů...
if (!empty($dbs)) {
    $s['dbcount'] = $dbs->getCountQuery();
}

echo $tpl->generate($s);
ob_end_flush();


