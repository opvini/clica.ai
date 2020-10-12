<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Com o clica.ai você encurta suas URL's, visualiza estatísticas profissionais sobre o seu público, e recebe muito mais cliques!" />
<meta name="keywords" content="encurtador, clicai, clica ai, estatísticas, cliques, twitter, URL, link, encurtar, diminuir" />

<title>Clica aí: encurte URL's, receba mais cliques.</title>

<link href="includes/css/estilos.css" rel="stylesheet" type="text/css" />
<link href="includes/css/button_styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="includes/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="includes/zeroclipboard/ZeroClipboard.js"></script>
<script type="text/javascript" src="includes/js/index.js"></script>

</head>

<body>

<div id="principal">
    <div id="topo_form"><h1>Clia aí: encurte URL's, receba mais cliques.</h1></div>
    <div id="meio_form">
        <div id="formulario">
            <b>cole o endereço e clique em <em>encurtar:</em></b>
            
            <div id="botao_url">
                <input name="url" type="text" id="url" value="http://" />
                <input type="image" id="botao_gera_url" class="round_right" value=" " />
            </div>
            
            <div id="cont_adulto">
                <input name="maior" type="checkbox" id="maior" />conteúdo adulto
                <a id="url_avancado" href="#">avançado</a>
            </div>
        </div>
    
        <div id="urls_criadas">
         <?php
			if( isset($_COOKIE['clicaiUrlsCriadas']) ){
				$urls = explode(",",$_COOKIE['clicaiUrlsCriadas']);
				for($i=(count($urls)-1); $i >=0; $i--){
		?>
        
        <div class="url_criada">
        	<input type="hidden" id="url_salva_<?php echo $i; ?>" value="http://clica.ai/<?php echo $urls[$i]; ?>" />
            <a href="http://clica.ai/<?php echo $urls[$i]; ?>" target=_blank>http://clica.ai/<?php echo $urls[$i]; ?></a>
            <span class="url_criada_opts">
                <span class="url_nova_copiar" id="copiar_<?php echo $i; ?>">copiar</span>
                <span class="url_nova_stats"><a href="http://clica.ai/<?php echo $urls[$i]; ?>+">estatísticas(+)</a></span>
            </span>
        </div>
		
        <?php }}	else echo "&nbsp;";	?>
        
        </div>
   
    </div>
    <div id="botom_form"></div>
</div>


</body>
</html>
