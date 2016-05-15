<?php  

/**
 * @author Fagner Fernandes <fagner.ti@gmail.com>
 * @link http://about.me/fagnerfernandes
 * @since 29/04/2015
 * @copyright Copyright (c) 2014, Fagner Fernandes (http://www.fagnerfernandes.com.br)
 */

date_default_timezone_set('America/Sao_Paulo');

class geradorImagens {
	
	private $imagem;
	private $caminhoImagem;
	private $w;
	private $h;
	private $c;
	private $width_orig;
	private $height_orig;
	private $width;
	private $height;
	private $tipoImagem;
	private $qualidade;


	public function __construct() { 
		
		//header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		header("Last-Modified: Mon, 29 Nov 2015 05:00:00 GMT");
		header("Cache-Control: public");
		header("Content-Disposition: inline; filename=".$_GET['cod']."");
		header("Pragma: public");
		header("Expires: " . date(DATE_RFC822,strtotime("30 day")));
		
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			// send the last mod time of the file back
			//header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT', true, 304); //is it cached?
			header('Last-Modified: Mon, 29 Nov 2015 05:00:00 GMT', true, 304); //is it cached?
			header("Cache-Control: public");
			header("Pragma: public");
			header("Expires: " . date(DATE_RFC822,strtotime("30 day")));
			die();
		} 

		if (filter_input(INPUT_GET, 'cod') != NULL) {
			$url = explode("/", filter_input(INPUT_GET, 'cod'));

			$this->imagem = $url[0];
			
		} else {
			$this->noImagem();
		}
		
		if(filter_input(INPUT_GET, 'i') != NULL){
			$this->caminhoImagem = str_replace(' ', '%20', filter_input(INPUT_GET, 'i'));
			//$this->caminhoImagem = urldecode(filter_input(INPUT_GET, 'i'));
		} else {
			$this->noImagem();
		}

		//pega e define o tamanho original da imagem
		//e caso nao consiga ja gera uma imagem padrao
		list($this->width_orig, $this->height_orig) = getimagesize($this->caminhoImagem) or $this->noImagem();
		
		if(filter_input(INPUT_GET, 'w') != NULL){
			$this->w = filter_input(INPUT_GET, 'w');
			
			if($this->width_orig >= $this->w){
				$this->width = $this->w;
			} else {
				$this->width = $this->width_orig;
			}
			
		} else {
			$this->w		= null;
			$this->width	= $this->width_orig;
		}
		
		if(filter_input(INPUT_GET, 'h')  != NULL){
			$this->h = filter_input(INPUT_GET, 'h');
			
			if($this->height_orig >= $this->h){
				$this->height = $this->h;
			} else {
				$this->height = $this->height_orig;
			}
			
		} else {
			$this->h		= null;
			$this->height	= $this->height_orig;
		}
		
		if(filter_input(INPUT_GET, 'c') != NULL){
			$this->c = array();
			$this->c = $this->html2rgb(filter_input(INPUT_GET, 'c'));
		} else {
			$this->c = array();
			$this->c[0] = 255;
			$this->c[1] = 255;
			$this->c[2] = 255;
		}
		
		/*
		1, // [] gif 
		2, // [] jpg 
		3 // [] png 
		*/
		$this->tipoImagem = $this->verificaTipoImagem($this->caminhoImagem);

		// Content type
		switch ($this->tipoImagem) {
			case 1 :
				$this->qualidade = 70;
				$this->exibeGIF();
				break;
			case 2 : 
				$this->qualidade = 70;
				$this->exibeJPG();
				break;
			case 3 :
				$this->qualidade = 7;
				$this->exibePNG();
				break;
		}
		
	}
	
	public function __destruct() {

	}
	
	public function exibeGIF(){
		header('Content-type: image/gif');
		
		$image_p	= imagecreatetruecolor($this->width, $this->height);
		$image		= imageCreateFromGif($this->caminhoImagem);
		$corfundo	= imagecolorallocate($image_p, $this->c[0], $this->c[1], $this->c[2]);
		imagefill($image_p, 0, 0, $corfundo);

		$dif_x = $this->width;
		$dif_y = $this->height;

		// verifica altura e largura
		if ($this->width_orig > $this->height_orig) {
			$this->height = ( ( $this->height_orig * $this->width ) / $this->width_orig );
		} elseif ($this->width_orig <= $this->height_orig) {
			$this->width = ( ( $this->width_orig * $this->height ) / $this->height_orig );
		}

		// copia com o novo tamanho, centralizando
		$dif_x = ( $dif_x - $this->width ) / 2;
		$dif_y = ( $dif_y - $this->height ) / 2;
		
		imagecolortransparent($image_p, imagecolorallocatealpha($image_p, 0, 0, 0, 127));
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		
		imagecopyresampled($image_p, $image, $dif_x, $dif_y, 0, 0, $this->width, $this->height, $this->width_orig, $this->height_orig);
		
		//header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		header("Last-Modified: Mon, 29 Nov 2015 05:00:00 GMT");
		header("Cache-Control: public");
		header("Content-Disposition: inline; filename=".$_GET['cod']."");
		header("Pragma: public");
		header("Expires: " . date(DATE_RFC822,strtotime("30 day")));
		
		imagegif($image_p, null, $this->qualidade);
		
		imagedestroy($image_p);
	}
	
	public function exibeJPG(){
		header('Content-type: image/jpeg');
		//header("Content-Disposition: attachment; filename=image_name.jpg");

		$image_p	= imagecreatetruecolor($this->width, $this->height);
		$image		= imageCreateFromJpeg($this->caminhoImagem);
		$corfundo	= imagecolorallocate($image_p, $this->c[0], $this->c[1], $this->c[2]);
		imagefill($image_p, 0, 0, $corfundo);

		$dif_x = $this->width;
		$dif_y = $this->height;

		// verifica altura e largura
		if ($this->width_orig > $this->height_orig) {
			$this->height = ( ( $this->height_orig * $this->width ) / $this->width_orig );
		} elseif ($this->width_orig <= $this->height_orig) {
			$this->width = ( ( $this->width_orig * $this->height ) / $this->height_orig );
		}

		// copia com o novo tamanho, centralizando
		$dif_x = ( $dif_x - $this->width ) / 2;
		$dif_y = ( $dif_y - $this->height ) / 2;
		
		imagecopyresampled($image_p, $image, $dif_x, $dif_y, 0, 0, $this->width, $this->height, $this->width_orig, $this->height_orig);
		
		//header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		header("Last-Modified: Mon, 29 Nov 2015 05:00:00 GMT");
		header("Cache-Control: public");
		header("Content-Disposition: inline; filename=".$_GET['cod']."");
		header("Pragma: public");
		header("Expires: " . date(DATE_RFC822,strtotime("30 day")));

		imagejpeg($image_p, null, $this->qualidade);
			
		imagedestroy($image_p);	
		

	}
	
	public function exibePNG(){
		header('Content-type: image/png');
		
		$image_p	= imagecreatetruecolor($this->width, $this->height);
		$image		= imageCreateFromPng($this->caminhoImagem);
		$corfundo	= imagecolorallocate($image_p, $this->c[0], $this->c[1], $this->c[2]);
		imagefill($image_p, 0, 0, $corfundo);

		$dif_x = $this->width;
		$dif_y = $this->height;

		// verifica altura e largura
		if ($this->width_orig > $this->height_orig) {
			$this->height = ( ( $this->height_orig * $this->width ) / $this->width_orig );
		} elseif ($this->width_orig <= $this->height_orig) {
			$this->width = ( ( $this->width_orig * $this->height ) / $this->height_orig );
		}

		// copia com o novo tamanho, centralizando
		$dif_x = ( $dif_x - $this->width ) / 2;
		$dif_y = ( $dif_y - $this->height ) / 2;
		
		imagecolortransparent($image_p, imagecolorallocatealpha($image_p, 0, 0, 0, 127));
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		
		imagecopyresampled($image_p, $image, $dif_x, $dif_y, 0, 0, $this->width, $this->height, $this->width_orig, $this->height_orig);
		
		//header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
		header("Last-Modified: Mon, 29 Nov 2015 05:00:00 GMT");
		header("Cache-Control: public");
		header("Content-Disposition: inline; filename=".$_GET['cod']."");
		header("Pragma: public");
		header("Expires: " . date(DATE_RFC822,strtotime("30 day")));
		
		imagepng($image_p, null, $this->qualidade);
		
		imagedestroy($image_p);
	}
	
	public function verificaTipoImagem($filepath) { 
		$type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 

		$allowedTypes = array(
			1, // [] gif 
			2, // [] jpg 
			3 // [] png 
		); 
		if (!in_array($type, $allowedTypes)) {
			return false;
		}

		return $type;
	}
	
	public function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0] . $color[1],
				$color[2] . $color[3],
				$color[4] . $color[5]);
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		} else {
			return false;
		}

		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return array($r, $g, $b);
	}
	
	public function noImagem(){
		header('Location: http://fagnerfernandes.com.br/exemplos/red-imagens/no-image.jpg?i=http://fagnerfernandes.com.br/exemplos/red-imagens/no-image.jpg');
		exit();die();
	}
	
	function print_gzipped_output() {
		
		$HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
		if( headers_sent() ){
			$encoding = false;
		} else if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ) {
			$encoding = 'x-gzip';
		} else if( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ) {
			$encoding = 'gzip';
		} else {
			$encoding = false;
		}

		if( $encoding ) { 
			$contents = ob_get_clean();
			$_temp1 = strlen($contents);
			if ($_temp1 < 0) {    // no need to waste resources in compressing very little data
				print($contents); 
			} else { die($encoding);
				header('Content-Encoding: '.$encoding); 
				print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
				$contents = gzcompress($contents, 9);
				$contents = substr($contents, 0, $_temp1);
				print($contents);
			}
		} else {
			ob_end_flush();
		}
	} 

}

new geradorImagens();
