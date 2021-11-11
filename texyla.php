<?php
require dirname(__FILE__) . '/admin/texy.min.php';
require dirname(__FILE__) . '/admin/texyla.class.php';
if (!empty($_POST['texy'])) {
    $texy = new ForumTexy;
    header("Content-Type: text/html; charset=UTF-8");

    $code = get_magic_quotes_gpc() ? stripslashes($_POST["texy"]) : $_POST["texy"];
    die($texy->process($code));
}

