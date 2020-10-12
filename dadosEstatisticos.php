<?php

include "includes/_ClassGerencia.php";

$url = $_POST['url'];
$cfg = $_POST['cfg'];

$clicai = new Clicai;

switch($cfg){
	case 0:
		echo $clicai->getCensoNavegadores($url);
	break;
	
	case 1:
		echo $clicai->getCensoPais($url);
	break;
	
	case 2:
		echo $clicai->getCensoEstados($url);
	break;
	
	case 3:
		echo $clicai->getCensoCidades($url);
	break;
	
	case 4:
		echo $clicai->getCensoReferer($url);
	break;

	case 5:
		echo $clicai->getCensoCliquesHora($url);
	break;

	case 6:
		echo $clicai->getCensoCliquesMinutos($url);
	break;
}

$clicai->close();

?>