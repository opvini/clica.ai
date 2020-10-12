<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Com o clica.ai você encurta suas URL's, visualiza estatísticas profissionais sobre o seu público, e recebe muito mais cliques!" />
<meta name="keywords" content="encurtador, clicai, clica ai, estatísticas, cliques, twitter, URL, link, encurtar, diminuir" />

<title>Clica aí: encurte URL's, receba mais cliques.</title>

<link href="includes/css/jCarGraficos.css" type="text/css" rel="stylesheet" />
<link href="includes/css/stats.css" type="text/css" rel="stylesheet" />

<script src="includes/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="includes/js/highcharts.js" type="text/javascript"></script>
<script src="includes/js/exporting.js" type="text/javascript"></script> 
<script src="includes/js/jquery.jcarousel.min.js" type="text/javascript"></script>
<script src="includes/js/jCarGraficos.js" type="text/javascript"></script>
<script src="includes/js/graficosStats.js" type="text/javascript"></script>

<?php 

	include "includes/_ClassGerencia.php";
	
	$url = $_GET['url'];
	$clicai = new Clicai;
	$info = $clicai->getInfo($url);
	
?>

<script type="text/javascript">
$(document).ready( function(){
							
	geraGrafico('<?php echo $url; ?>', 'grafico1', 0); // navegadores
	geraGrafico('<?php echo $url; ?>', 'grafico2', 1); // paises
	geraGrafico('<?php echo $url; ?>', 'grafico3', 2); // estados
	geraGrafico('<?php echo $url; ?>', 'grafico4', 3); // cidades
	geraGrafico('<?php echo $url; ?>', 'grafico5', 4); // referers
	geraGrafico('<?php echo $url; ?>', 'grafico6', 5); // hoje(horas)
	geraGrafico('<?php echo $url; ?>', 'grafico7', 6); // agora(minutos)
	
});
</script>


</head>

<body>

<h1><a href="http://clica.ai/">Clica aí: encurte URL's, receba mais cliques.</a></h1>



<div id="graficos">
      <ul id="destrol" class="jcarousel-skin-graficos"> 
          <li id="grafico1"></li> 
          <li id="grafico2"></li> 
          <li id="grafico3"></li> 
          <li id="grafico4"></li> 
          <li id="grafico5"></li> 
          <li id="grafico6"></li> 
          <li id="grafico7"></li> 
      </ul> 
</div>

<div id="ControlGraficos">
    <ul>
        <li id="S1"><a></a></li>
        <li id="S2"><a></a></li>
        <li id="S3"><a></a></li>
        <li id="S4"><a></a></li>
        <li id="S5"><a></a></li>
        <li id="S6"><a></a></li>
        <li id="S7"><a></a></li>
    </ul>
</div>
    

</body>
</html>
