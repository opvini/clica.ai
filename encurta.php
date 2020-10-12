<?php

include "includes/_ClassGerencia.php";

$clicai = new Clicai;
echo "clica.ai/".$clicai->criaUrl($_POST['url']);
$clicai->close();

?>