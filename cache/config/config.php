
<?php

// administrátor: jméno a heslo
define("adminUser", "Krteczek");
define("adminPass", "heslo");
define("adminMail", "krteczek01@gmail.com");

define("SQL_HOST", "localhost"); //název serveru na kterém běží databáze
define("SQL_DBNAME", "krs");  //název databáze ve které budou vytvořeny tabulky
define("SQL_USERNAME", "user1");  //přihlašovací jméno k databázi
define("SQL_PASSWORD", "heslo");   //přihlašovací heslo k databázi

$prefix = "<!--NEZNÁMÝ: prefix //-->";

define("Tabulka_clanky", $prefix . "kiti_clanky");
//define("Tabulka_pristupy", $prefix."kiti_pristupy");
define("Tabulka_sekce", $prefix . "kiti_sekce");
define("Tabulka_komentare", $prefix . "kiti_komentare");
define("Tabulka_kniha", $prefix . "kiti_kniha");
define("Tabulka_kategorie", $prefix . "kiti_kategorie");


