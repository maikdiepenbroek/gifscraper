<?php
require_once('libs/simple_html_dom.php');
/**
* This class scrapes the given website and saves all the gifs to a folder.
* It depends on simple_html_dom library (http://simplehtmldom.sourceforge.net/)
* @since 20-04-2013
* @author Maik Diepenbroek
*
*/
class GifGrabber{
	private $file_extension = "gif";
	private $gifUrls = array();
	private $gifFolder = "gifs";
	private $pagesAmount = 2;
	private $websiteUrl = "http://thejoysofcode.com/page/";

	public function __construct() {
		for($i = 1; $i <= $this->pagesAmount; $i++) {
			$this->gifUrls[$i] = $this->getGifsFromPage( file_get_html($this->websiteUrl.$i), 
				$this->getTitleFromGif(file_get_html($this->websiteUrl.$i)) );
		}		
		$this->saveGifsToFolder();
	}
		
	

	private function getTitleFromGif($gif) {
		$page = $gif->find('h2 a');
		$hrefs = [];
		foreach($page as $href) {
	    	$hrefs[] =  ucfirst(str_replace("-", " ", substr($href->href,42))) ;    
		}
		return $hrefs;
	}



	private function getGifsFromPage($page, $gifTitles) {
		$gifsFromPage = array();
		$i = 0;
		foreach($page->find('img') as $element) {
			if($this->get_file_extension($element->src) == "gif") {
				$gifInGifs = array();

				$gifInGifs['title'] = $gifTitles[$i];
				$gifInGifs["gifUrl"] = $element->src;
				$gifsFromPage[] = $gifInGifs;
				$i++;
			}
			
		}
		array_pop($gifsFromPage);
		return $gifsFromPage;
	}    

	private function saveGifsToFolder() {
		foreach($this->gifUrls as $gifs) {
				foreach($gifs as $gifInGifs) {
					file_put_contents('gifs/'.$gifInGifs['title'].'.gif', file_get_contents($gifInGifs['gifUrl']));
				}
			}	
	}


	private function get_file_extension($file_name) {
		return substr(strrchr($file_name,'.'),1);
	}
} 
$grabber = new GifGrabber();
?>
