<?php

date_default_timezone_set("Brazil/East");
require_once("conexao.php");


class Clicai{
	
	protected $info;
	protected $estrutura;
	protected $conexao;
	
	function __construct(){
		$this->info = new Informacoes;
		$this->estrutura = new Estrutura;
		$this->conexao = new Conecta;
	}
		
	
	public function criaUrl($url_real){
		$url_real = $this->estrutura->trabalhaStr($url_real);
		$url_real = $this->estrutura->trabalhaUrl($url_real);
		$this->info->carregaInfoPagina($url_real);
		
		$SQL = "INSERT INTO urls SET
					url = '$url_real',
					data_criacao = '".date("Y-m-d H:i:s")."',
					ip_criador = '".$_SERVER['REMOTE_ADDR']."',
					titulo_pagina = '".$this->estrutura->trabalhaStr($this->info->titulo)."';";
		$SQL = mysql_query($SQL, $this->conexao->con);
		return $this->estrutura->DEC2BX( mysql_insert_id() );
	} // fim criaUrl



	public function redireciona($url_curta){
		$SQL = "SELECT id_url, url FROM urls WHERE id_url = ".$this->estrutura->BX2DEC( $url_curta ).";";
		$SQL = mysql_query($SQL, $this->conexao->con);
		if(mysql_num_rows($SQL) <= 0) header("Location: http://clica.ai");
		else{
			$row = mysql_fetch_object($SQL);
			$this->somaClique($row->id_url);
			$this->salvaInfos($row->id_url);
			//$this->salvaInfosLocal($row->id_url);
			$this->close();
			header("Location: ".$row->url);
		}
	} // fim redireciona
	
	
	
	protected function salvaInfos($id){
		
		include_once("geolocalizacao/geoipcity.php");
		include_once("geolocalizacao/geoipregionvars.php");
		
		$gi = geoip_open("geolocalizacao/GeoIP.dat",GEOIP_STANDARD);
		$record = geoip_record_by_addr($gi, $_SERVER['REMOTE_ADDR']);
		
		$SQL = "INSERT INTO cliques SET
					fgk_url = $id,
					data = '".date("Y-m-d H:i:s")."',
					navegador = '".$_SERVER['HTTP_USER_AGENT']."',
					navegador_titulo = '".$this->estrutura->trabalhaNavegador($_SERVER['HTTP_USER_AGENT'])."',
					lingua = '".$_SERVER['HTTP_ACCEPT_LANGUAGE']."',
					referer = '".$this->estrutura->trabalhaReferer($_SERVER['HTTP_REFERER'])."',
					accept = '".$_SERVER['HTTP_ACCEPT']."',
					charset = '".$_SERVER['HTTP_ACCEPT_CHARSET']."',
					pais = '".( $record->country_name == "Brazil" ? "Brasil" : $record->country_name)."',
					cod_pais = '".$record->country_code."',
					cod2_pais = '".$record->country_code3."',
					estado = '".$GEOIP_REGION_NAME[$record->country_code][$record->region]."',
					cod_estado = ".$record->region.",
					cidade = '".$record->city."',
					latitude = ".$record->latitude.",
					longitude = ".$record->longitude.",
					ip = '".$_SERVER['REMOTE_ADDR']."';";
		
		geoip_close($gi);
		$SQL = mysql_query($SQL, $this->conexao->con);
		return 1;
		
	} // fim salvaInfos
	
	
	protected function salvaInfosLocal($id){
		
		
		$SQL = "INSERT INTO cliques SET
					fgk_url = $id,
					data = '".date("Y-m-d H:i:s")."',
					navegador = '".$_SERVER['HTTP_USER_AGENT']."',
					navegador_titulo = '".$this->estrutura->trabalhaNavegador($_SERVER['HTTP_USER_AGENT'])."',
					lingua = '".$_SERVER['HTTP_ACCEPT_LANGUAGE']."',
					referer = '".$_SERVER['HTTP_REFERER']."',
					accept = '".$_SERVER['HTTP_ACCEPT']."',
					charset = '".$_SERVER['HTTP_ACCEPT_CHARSET']."',
					ip = '".$_SERVER['REMOTE_ADDR']."';";
		
		$SQL = mysql_query($SQL, $this->conexao->con);
		return 1;
		
	} // fim salvaInfos

	
	
	protected function somaClique($id){
		$SQL = "UPDATE urls SET tot_cliques = (tot_cliques + 1) WHERE id_url = $id;";
		$SQL = mysql_query($SQL, $this->conexao->con);
		return 1;
	}
	
	public function getInfo($url_curta){
		return $this->info->carregaInfoBd( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function getCensoPais($url_curta){
		return $this->info->getCensoPais( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function getCensoEstados($url_curta){
		return $this->info->getCensoEstados( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}

	public function getCensoCidades($url_curta){
		return $this->info->getCensoCidades( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function getCensoReferer($url_curta){
		return $this->info->getCensoReferer( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function getCensoNavegadores($url_curta){
		return $this->info->getCensoNavegadores( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function getCensoCliquesMinutos($url_curta){
		return $this->info->getCensoCliquesMinutos( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}

	public function getCensoCliquesHora($url_curta){
		return $this->info->getCensoCliquesHora( $this->estrutura->trabalhaStr($url_curta), $this->conexao->con, $this->estrutura);
	}
	
	public function close(){
		$this->conexao->close();
	}
	
}


class Informacoes{
	public $titulo;
	public $descricao;
	public $palavrasChave;
	
	protected $pais, $estados, $cidades, $referers, $navegadores, $horas, $minutos;
	
	function __construct(){
		$this->resetaDados();
	}
	
	protected function resetaDados(){
		$this->pais = array("tipo"=>"column", "titulo"=>"Paises", "legenda"=>"visitas", "categorias"=>array("Total"), "series"=>array() );
		$this->estados = array("tipo"=>"column", "titulo"=>"Estados", "legenda"=>"visitas", "categorias"=>array("Total"), "series"=>array() );
		$this->cidades = array("tipo"=>"column", "titulo"=>"Cidades", "legenda"=>"visitas", "categorias"=>array("Total"), "series"=>array() );
		$this->referers = array("tipo"=>"column", "titulo"=>"Origem", "legenda"=>"visitas", "categorias"=>array("Total"), "series"=>array() );
		$this->navegadores = array("tipo"=>"pie", "titulo"=>"Navegador", "legenda"=>"visitas", "categorias"=>array("Total"), "series"=>array( array("data"=>array())) );
		$this->horas = array("tipo"=>"line", "titulo"=>"Cliques hoje", "legenda"=>"visitas", "categorias"=>array(), "series"=>array( array("name"=>"Cliques", "data"=>array())) );
		$this->minutos = array("tipo"=>"line", "titulo"=>"Cliques agora", "legenda"=>"visitas", "categorias"=>array(), "series"=>array( array("name"=>"Cliques", "data"=>array())) );
	}
	
	public function carregaInfoPagina($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		$cod = curl_exec ($ch);
		curl_close ($ch);
		
		if (  preg_match( '/<title>([^<]++)/', $cod, $results ) == FALSE ) $this->titulo = "";
		else $this->titulo = $results[1];
	} // fim getInformacoes
	
	
	public function carregaInfoBd($url_curta, $con, $objEstrutura){
		$SQL = "SELECT * FROM urls WHERE id_url = ".$objEstrutura->BX2DEC( $url_curta ).";";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) return "";
		else{
			$row = mysql_fetch_object($SQL);
			return $row;
		}
	} // fim carregaInfoBd
	
	
	public function getCensoPais($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(pais) as total, pais FROM cliques WHERE fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY pais ORDER BY total DESC LIMIT 0,10;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->pais["series"][] = array("name"=>utf8_encode($row->pais), "data"=>intval($row->total));
			}	
		}
		
		return json_encode($this->pais);
	} // fim getCenso Pais


	public function getCensoEstados($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(estado) as total, estado FROM cliques WHERE fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY estado ORDER BY total DESC  LIMIT 0,10;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->estados["series"][] = array("name"=>utf8_encode($row->estado), "data"=>intval($row->total));
			}	
		}
		
		return json_encode($this->estados);
	} // fim getCenso Estados
	
	
	public function getCensoCidades($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(cidade) as total, cidade FROM cliques WHERE fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY cidade ORDER BY total DESC  LIMIT 0,10;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->cidades["series"][] = array("name"=>utf8_encode($row->cidade), "data"=>intval($row->total));
			}	
		}
		
		return json_encode($this->cidades);
	} // fim getCenso Cidades


	public function getCensoReferer($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(referer) as total, referer
				FROM cliques WHERE fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY referer ORDER BY total DESC  LIMIT 0,10;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->referers["series"][] = array("name"=>$row->referer, "data"=>intval($row->total));
			}	
		}
		
		return json_encode($this->referers);
	} // fim getCenso Origem


	public function getCensoNavegadores($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(navegador_titulo) as total, navegador_titulo
				FROM cliques WHERE fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY navegador_titulo ORDER BY total DESC LIMIT 0,10;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->navegadores["series"][0]["data"][] = array($row->navegador_titulo, intval($row->total));
			}	
		}
		
		return json_encode($this->navegadores);
	} // fim getCenso Navegadores
	
	
	public function getCensoCliquesHora($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(data) as total, HOUR(data) as hora, data FROM cliques
				WHERE (data BETWEEN '".date("Y-m-d H:i", mktime( date("H")-24 ) )."' AND '".date("Y-m-d H:i")."')
				AND fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY hora ORDER BY data;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->horas["categorias"][] = $row->hora.':00h';
				$this->horas["series"][0]["data"][] = intval($row->total);
			}	
		}
		
		return json_encode($this->horas);
	} // fim getCenso Horas


	public function getCensoCliquesMinutos($url_curta, $con, $objEstrutura){
		$SQL = "SELECT COUNT(data) as total, MINUTE(data) as minuto, data, HOUR(data) as hora FROM cliques
				WHERE (data BETWEEN '".date("Y-m-d H:i", mktime( date("H")-1 ) )."' AND '".date("Y-m-d H:i")."')
				AND fgk_url = ".$objEstrutura->BX2DEC( $url_curta )." GROUP BY minuto ORDER BY data;";
		$SQL = mysql_query($SQL, $con);
		if(mysql_num_rows($SQL) <= 0) $this->resetaDados();
		else{
			while($row = mysql_fetch_object($SQL)){
				$this->minutos["categorias"][] = $row->hora.':'.str_pad($row->minuto,2,'0',STR_PAD_LEFT).'h';
				$this->minutos["series"][0]["data"][] = intval($row->total);
			}	
			
		}
		
		return json_encode($this->minutos);
	} // fim getCenso Minutos
	
	
} // fim classe Informacoes




class Estrutura{
	
	protected $TABELA = array('0', 'E', 'j', 'o', 'v', 'I', 'g', 'i', 'O', 'd', 'D', '2', 'R', 'P', 'L', 'u', 'z', 'y', 't', '1', '9', 'x', 'w', 'G', 'H', 'N', 'S', '6', 'n', 'k', 'W', 's', '5', 'J', 'r', 'l', '7', 'U', 'V', 'Z', 'q', 'e', 'Q', 'm', 'h', 'B', 'c', 'K', '4', 'T', '8', 'b', 'A', 'a', 'C', 'F', 'p', '3', 'X', 'M', 'Y', 'f');


	public function DEC2BX( $numero ){
		$exp	= $numero/count($this->TABELA);
		$pnum	= intval($exp);
		$inum	= round(($exp-$pnum)*count($this->TABELA));
		$resp	= $this->TABELA[$inum];
		
		while($pnum >= count($this->TABELA)){	
			$numero	= $pnum;
	
			$exp	= $numero/count($this->TABELA);
			$pnum	= intval($exp);
			$inum	= round(($exp-$pnum)*count($this->TABELA));
			$resp	.= $this->TABELA[$inum];
		}
		
		return ltrim($this->TABELA[$pnum].strrev($resp),"0");
	} // fim DEC2BX
	
	
	public function BX2DEC( $numero ){
		$tot	= 0 ;
		
		for($i=0; $i<strlen($numero); $i++){
			$chr	= substr($numero,$i,1);
			$ich	= array_search( $chr, $this->TABELA );
			$tot	+= $ich*pow(count($this->TABELA), strlen($numero)-($i+1));
		}
		
		return $tot;
	} // fim BX2DEC


	public function trabalhaStr($str){
		$str = str_replace("'","",$str);
		$str = str_replace('"',"",$str);
		return $str;
	}
	
	public function trabalhaReferer($str){
		if(substr($str,0,7)=="http://") $str = substr($str,7);
		if(substr($str,0,4)=="www.") $str = substr($str,4);
		if(substr($str,0,7)=="mobile." ) $str = substr($str, 7);
		$str = preg_replace("/twitter.com\/([^\/]*)(.*)/i","@$1",$str);
		if($str == '@home' || $str == "@") $str = 'twitter.com';
		if($str == "") $str = "Direto";
		if(substr($str,-1,1) == "/") $str = substr($str, 0, strlen($str)-1);
		return $str;
	}
	
	public function trabalhaUrl($url){
		if(substr($url,0,7) != "http://") return "http://".$url;
		else return $url;
	}
	
	public function trabalhaNavegador($str){
		$str = strtolower($str);
		if( strpos($str, "chrome") > 0) return "Google Chrome";
		else if( strpos($str, "firefox") > 0) return "Mozilla Firefox";
		else if( strpos($str, "msie") > 0) return "Microsoft Internet Explorer";
		else if( strpos($str, "opera") > 0) return "Opera";
		else if( strpos($str, "safari") > 0) return "Apple Safari";
		else if( strpos($str, "netscape") > 0) return "Netscape";
		else if( substr($str, 0, 5) == "opera" ) return "Opera";
		else return "Outro";
	}

} // fim classe Estrutura



?>