

var dataPost = "";

$(document).ready(function(){
	$('#botao_gera_url').click( function(){ geraUrl(); } );	
	ZeroClipboard.setMoviePath( 'includes/zeroclipboard/ZeroClipboard10.swf' );
	//geraClipBoard();
});


function geraClipBoard(){
	$('#urls_criadas .url_criada').each(function(i){
		alert($(this).html());
	});
}

function geraUrl(){
		
	$("#url").attr("disabled", true); 
	$("#botao_gera_url").attr("disabled", true); 
	
	dataPost = "url="+escape($('#url').val())+"&conteudo_adulto=0"
	$("#url").val("carregando...");
	
	$.ajax({
	  url: "encurtar.php",
	  type:"POST",
	  dataType: 'json',
	  data: dataPost,
	  success: function(msg){
		  			if( msg.sucesso ){ 
					
                		$('#urls_criadas').prepend('<div class="url_criada_agora"><input type="hidden" name="url_salva_" value="'+msg.url_curta+'" /><a href="'+msg.url_curta+'" target=_blank>'+msg.url_curta+'</a><span class="url_criada_opts"><span class="url_nova_copiar" id="copiar_">copiar</span><span class="url_nova_stats"><a href="'+msg.url_curta+'+">estatisticas(+)</a></span></span></div>');
				
						$('.url_criada_agora').fadeIn(1000); 
					}
					else alert( msg.erro );
					
					$("#url").val("http://");
					$("#url").removeAttr('disabled');
					$("#botao_gera_url").removeAttr('disabled');
		}
	});
}


function criaCopys(tot){
	for(i=1; i<=contaCopies; i++){
		var clip = new ZeroClipboard.Client();
		clip.setHandCursor( true );
		clip.setText( document.getElementById('url_salva_'+i).value );
		clip.glue( 'copiar_'+i );
		//clip.addEventListener( 'mouseDown', function(client) { clip.setText( document.getElementById('copiar_'+i). ); } );
		alert('copiar_'+i+"  "+ document.getElementById('url_salva_'+i).value);
	}
	alert('oi');
}