<?php

include "includes/_ClassGerencia.php";

$str = ereg_replace("[^A-Za-z0-9]","",$_GET['url']);

$clicai = new Clicai;
$clicai->redireciona( $str );
$clicai->close();


?>