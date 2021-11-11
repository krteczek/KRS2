<?php

//tento soubor slouľí k nastavení základních proměnných systému

define("ROOT_WEBU", dirname(__FILE__));

// nastavení cest pro ukládání souborů
$cachePaths = array(
	'cache' => '/cache/',
	'config' => '/cache/config/',
	'menu' => '/cache/menu/',
	'pages' => '/cache/pages/',
	'uvod' => '/cache/uvod/',
	'logsDB' => '/cache/logsDB/'
    );


//**********************************************************************************************
//**********************************************************************************************
//					EMAIL ADMINA WEBU
//definice emailu, na který budou odesílány zprávy o inzerátech a podobně
define("EMAIL_ADMINA", "Kitikara@seznam.cz");

require_once (ROOT_WEBU . "/admin/admin.class.php"); //soubor s funkcemi, na něm běží celý web
require_once (ROOT_WEBU . "/admin/login.class.php"); //soubor s funkcemi, na něm běží celý web
require_once (ROOT_WEBU . "/admin/db.class.php"); //soubor s funkcemi, na něm běží celý web
require_once (ROOT_WEBU . "/admin/tpl.class.php"); //soubor s administrátorskými nastaveními, funkcemi,...
require_once (ROOT_WEBU . "/admin/clanky.class.php"); //soubor s administrátorskými nastaveními, funkcemi,...
require_once (ROOT_WEBU . '/admin/texy.min.php');
require_once (ROOT_WEBU . '/admin/texyla.class.php');



