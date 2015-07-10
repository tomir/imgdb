<?php

include_once realpath(dirname(__FILE__) . '/../') . '/config/config.php';
include("classConnectDB.php");
include("Admin/classConfig.php");

set_time_limit(3600);
error_reporting(E_ERROR);
ini_set('display_errors', 1);


define('COOKIE_FILE',"./cookies.".uniqid().".cookie");
define('COOKIE_FILE2',"./cookies.".uniqid().".cookie_2");
define('ILOSC', 100);

class OfferFilmweb {
 
	public $updated = 0;
	public $added = 0;
	public $lost = 0;
	
	public $objLogiFile;
	public $logId;
	
	public $start;
	
	public function __construct() {
		$this->objLogiFile = new Admin_Import_Log();
	}
	
	public function startLog() {
		$this->start = time();
		$this->logId = $this->objLogiFile->insert(array(
			'film_pochodzenie' => 1,
			'filename' => basename($_SERVER["SCRIPT_FILENAME"]),
			'date_start' => date("Y-m-d H:i:s", $this->start)
		));
	}
	
	public function stopLog() {
		$stop = time();
		$this->objLogiFile->update(array(
			'date_stop' => date("Y-m-d H:i:s", $stop),
			'duration' => $stop - $this->start,
			'added' => $this->added,
			'updated' => $this->updated,
			'lost' => $this->lost,
		), $this->logId);
	}
	
	public function repeatFilmweb($y1, $y2) {

		
		$aData = array();
		
		for($j=1; $j<ILOSC; $j++) {
			$ch = curl_init("http://filmweb.pl/search?PageSize=20&Page=" . $j . "&LotTypes=V&YearFrom=".$y1."&YearTo=".$y2."&Distance=99999"); //Common::debug($link);
			echo 'jjj'.$j.' ';
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,CURLOPT_COOKIEFILE,COOKIE_FILE);
			curl_setopt($ch,CURLOPT_COOKIEJAR,COOKIE_FILE);
			$output = curl_exec($ch);
			curl_close($ch);

			if( file_exists(COOKIE_FILE) )
				unlink(COOKIE_FILE);

			$stop = strpos($output, '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="results search-color">');
			$html = substr($output,  $stop, strlen($output));

			$stop = strpos($html, '</table>');
			$html = substr($html,  0, $stop+8);

		
			try {
				$dom= new DOMDocument();
				$dom->strictErrorChecking = FALSE;
				$dom->validateOnParse = true;
				@$dom->loadHTML($html);

				$xpath= new DOMXPath($dom);
				$tx= $xpath->evaluate('//table/tbody/tr');

			} catch (Exception $e) {
				echo $e->getMessage();
				return false;
			}

			if($tx->length > 2) {

				for( $a=0; $a< $tx->length; $a++ ) {
					echo " <".$a."> ".
					$id = $tx->item($a)->getAttribute("data-lotnumber");
				
					if($movie_id = Admin_movie::checkmovie((int)$id, 1)) {
						$tmp = explode('&amt=', $tx->item($a)->getElementsByTagName("td")->item(0)->getElementsByTagName("div")->item(0)->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getAttribute('data-url'));
						
						try {
							$iterator = new \FilesystemIterator(APPLICATION_DIR."pliki/movies/". $movie_id ."/");
							if(!$iterator->valid()) {
								echo "brak zdjec\n";

								$obmovie = new Admin_movie();
								$obmovie->deletemovie($movie_id);
								@rmdir(APPLICATION_DIR."pliki/movies/". $movie_id ."/");
							} else {

								$aData['movie_price'] = $tmp[1];
								$aData['movie_id'] = $movie_id;
								ConnectDB::subAutoExec(MyConfig::getValue("dbPrefix")."movie", $aData, 'UPDATE', ' movie_id = '.$movie_id);

								$this->updated++;
							}
						} catch(\Exception $e) {
							$obmovie = new Admin_movie();
							$obmovie->deletemovie($movie_id);
							@rmdir(APPLICATION_DIR."pliki/movies/". $movie_id ."/");
							
						}
						$aData = null;
					} else {

						$aData['movie_price_type'] = 2;
						$aData['movie_zew_id'] = $id;
						
						$main = $tx->item($a)->getElementsByTagName("td")->item(0)->getElementsByTagName("div")->item(0)->getElementsByTagName("div")->item(2);
						
						$movieInfo = explode(" ", $main->getElementsByTagName("div")->item(0)->getElementsByTagName("ul")->item(0)->getElementsByTagName("li")->item(0)->getElementsByTagName("a")->item(0)->textContent);
						$aData['movie_year'] = $movieInfo[0];
						
						if(strstr($movieInfo[1], '(')) {
							$jj = 2;
						} else {
							$jj = 1;
						}
						$aData['marka'] = $movieInfo[$jj];
						
						
						$aData['movie_mileage'] = str_replace(',', '', strip_tags(trim(str_replace('Mileage:', '', $main->getElementsByTagName("div")->item(1)->getElementsByTagName("ul")->item(0)->getElementsByTagName("li")->item(3)->textContent))));
						$aData['movie_mileage'] = 1.6*(int)$aData['movie_mileage'];
						$tmp = explode('&amt=', $tx->item($a)->getElementsByTagName("td")->item(0)->getElementsByTagName("div")->item(0)->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getAttribute('data-url'));
						
						$aData['movie_price'] = $tmp[1];
						$aData['movie_link'] = 'http://filmweb.pl/movie/'.$id;


						$aData['producer_id'] = $obOtomoto->findMarkalId($aData['marka'], 'movie', '');
						if($aData['producer_id'] > 0 && isset($aData['producer_id'])) {
							$aModel = explode(" ", $aData['model']);
							if($aData['producer_id'] == 63)
								$aModel[0] .= " ".$aModel[1];
							$aData['model_id'] = $obOtomoto->findModelId($aModel[0], $aData['producer_id']);
						} else {
							$aData['producer_id'] = 122;
						}	

						if($aData['model_id'] == NULL) {
							$aData['model_id'] = 0;
						}

						$aData['movie_model'] = $aData['model'];

						$aData['movie_pochodzenie'] = 1;
						$aData['sale_date'] = strip_tags(trim($main->getElementsByTagName("div")->item(1)->getElementsByTagName("ul")->item(2)->getElementsByTagName("li")->item(2)->getElementsByTagName("span")->item(0)->getAttribute('data-original-time')));

						$aDate = explode(" ", $aData['sale_date']);
						
						$aDate2 = explode("/", $aDate[0]);
						$aData['sale_date'] = $aDate2[2].'-'.$aDate2[1].'-'.$aDate2[0].' '.$aDate[1];

						//pobieranie pozostałych dsanych
						echo $aData['movie_link']."\n";
						$ch2 = curl_init($aData['movie_link']);
					
						curl_setopt($ch2, CURLOPT_HEADER, 0);
						curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch2,CURLOPT_COOKIEFILE,COOKIE_FILE2);
						curl_setopt($ch2,CURLOPT_COOKIEJAR,COOKIE_FILE2);
						$output2 = curl_exec($ch2);
						curl_close($ch2);

						if( file_exists(COOKIE_FILE2) )
							unlink(COOKIE_FILE2);

						$stop2 = strpos($output2, '<div class="lot-display">');
						$html2 = substr($output2,  $stop2, strlen($output2));
						
						try {
							$dom= new DOMDocument();
							$dom->strictErrorChecking = FALSE;
							$dom->validateOnParse = true;
							@$dom->loadHTML($html2);

							$xpath= new DOMXPath($dom);
							$tx2= $xpath->evaluate('//div[contains(@class, "lot-display-list")]/div');

						} catch (Exception $e) {
							echo $e->getMessage();
							return false;
						}

						if($tx2->length > 2) {
						
							for( $b=0; $b< $tx2->length; $b++ ) {
								echo trim($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent)."\n";
								switch(trim($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent)) {
									
									case 'Category:': 
										$aData['kategoria'] = $tx2->item($b)->textContent;
										$aData['kategoria'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['kategoria']);
									break;
									case 'Actors:':
										if($aData['actors'] != '') {
											$aData['actors'] .= '<br />'.trim($tx2->item($b)->textContent);
											$aData['actors'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['actors']);
										} else {
											$aData['actors'] = trim($tx2->item($b)->textContent);
											$aData['actors'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['actors']);
										}
									break;
									case 'Highlights:':
										$aData['actors'] = trim($tx2->item($b)->textContent);
										$aData['actors'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['actors']);
									break;
									case 'Info:':
										$aData['info'] = trim($tx2->item($b)->textContent);
										$aData['info'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['info']);
									break;
									case 'VIN:':
										$aData['vin'] = $tx2->item($b)->textContent;
										$aData['vin'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['vin']);
									break;
									case 'Body Style:':
										$aData['body'] = $tx2->item($b)->textContent;
										$aData['body'] = str_replace($tx2->item($b)->getElementsByTagName("label")->item(0)->textContent, "", $aData['body']);
										
									break;
									
								}
							}
						}
						
						$aData['movie_type'] = 'movie';

						$id = ConnectDB::subAutoExec(MyConfig::getValue("dbPrefix")."movie", $aData, 'INSERT');
						echo "Add new rekord -> ".$id." (".$aData['film']." ".$aData['movie'].") - ".date("Y-m-d H:i:s")."\n";

						$this->added++;
					
						/**
						 * Dodajemy zdjęcia
						 */
						echo $aData['movie_link']."/Photos\n";
						$ch3 = curl_init($aData['movie_link'].'/Photos');
					
						curl_setopt($ch3, CURLOPT_HEADER, 0);
						curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch3,CURLOPT_COOKIEFILE,COOKIE_FILE2);
						curl_setopt($ch3,CURLOPT_COOKIEJAR,COOKIE_FILE2);
						$output3 = curl_exec($ch3);
						curl_close($ch3);

						if( file_exists(COOKIE_FILE2) )
							unlink(COOKIE_FILE2);

						$stop3 = strpos($output3, '<div id="content" class="content container">');
						$html3 = substr($output3,  $stop3, strlen($output3));
						
						try {
							$dom = new DOMDocument();
							$dom->strictErrorChecking = FALSE;
							$dom->validateOnParse = true;
							@$dom->loadHTML($html3);

							$xpath = new DOMXPath($dom);
							$tx3 = $xpath->evaluate('//div[contains(@class, "lot-photos")]/div/ul/li/img');

						} catch (Exception $e) {
							echo $e->getMessage();
							return false;
						}
						
						if($tx3->length > 0) {
							$old = umask(0);
							mkdir( APPLICATION_DIR."pliki/movies/". $id ."/", 0777);
							chmod( APPLICATION_DIR."pliki/movies/". $id ."/", 0777);
							umask($old);
							
							$k = 1;
							for( $p=0; $p< $tx3->length; $p++ ) {
								$newImg = $tx3->item($p)->getAttribute("src");
								echo "http:".$newImg."\n";
								
								if($img = file_get_contents('http:'.$newImg, "r")) {
									
									$aPhoto['movie_id'] = $id;
									$aPhoto['photo_order'] = $k;
									$aPhoto['photo_filename'] = 'jpg';

									$photo_id = ConnectDB::subAutoExec(MyConfig::getValue("dbPrefix")."movie_photo", $aPhoto, 'INSERT');

									$fp = fopen(APPLICATION_DIR."pliki/movies/". $id ."/".$photo_id."_new.jpg", 'w');

									flock($fp, LOCK_EX);
									fwrite($fp, $img);
									flock($fp, LOCK_UN);
									fclose($fp);

									$img = null;
								}
								
							
								$k++;
							}
						}
					}
					$aData = null;
				}
			} else {
				echo "stop ".$j."<br />";
				break;
			}

			$dom = null;
			$xpath = null;
		}
	}
	
	public function run() {
		
		$this->startLog();
		$this->repeatFilmweb(1937, 2002);
		$this->repeatFilmweb(2003, 2005);
		$this->repeatFilmweb(2006, 2011);
		$this->repeatFilmweb(2012, 2015);
		$this->stopLog();
	}
}

$objCopartOffer = new OfferFilmweb();
$objCopartOffer->run();
