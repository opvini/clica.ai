<?php

include "includes/_ClassGerencia.php";

$url = $_POST['url'];

$clicai = new Clicai;
$url_curta = $clicai->criaUrl($url);
$clicai->close();


if( isset($_COOKIE['clicaiUrlsCriadas']) ) setcookie("clicaiUrlsCriadas", $_COOKIE['clicaiUrlsCriadas'].",".$url_curta);
else setcookie("clicaiUrlsCriadas", $url_curta);

echo '{"sucesso":1, "url_curta": "http://clica.ai/'.$url_curta.'"}';

?>