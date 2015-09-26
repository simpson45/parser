<?php
require('phpQuery/phpQuery.php');

function get_content($url) {
	$uagent = "Googlebot-Image (Google) Googlebot-Image/1.0";
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cook.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"cook.txt");

	$content = curl_exec( $ch );
	$err = curl_errno( $ch );
	$errmsg = curl_error( $ch );
	$header = curl_getinfo( $ch );
	curl_close( $ch );

	$header['errno'] = $err;
	$header['errmsg'] = $errmsg;
	$header['content'] = $content;
	return $content;
}
	
	
$data = file_get_contents('page73.html');
//$data = get_content('http://www.komus.ru/catalog/73/');

$results = phpQuery::newDocument($data);

//$elements = $results->find('');
//print_r ($elements);
$title = $results->find('h1.header-gray--text')->text();

echo iconv("UTF-8", "CP1251", $title);

foreach (pq('div.goods-tableitems--item') as $mark){
			$mark_name = pq($mark)->find('div.goods-table--name-block > a.goods-table--name-link')->text();
			$mark_name = iconv("UTF-8", "CP1251", $mark_name);
			$marks[$mark_name]['articul'] = iconv("UTF-8", "CP1251",(pq($mark)->find('div.goods-table--articul')->text()));
			$marks[$mark_name]['price'] = iconv("UTF-8", "CP1251",(pq($mark)->find('div.goods-table--price > span.goods-table--price-now > span.goods-table--price-now-value')->text()));
			$marks[$mark_name]['img'] = pq($mark)->find('img.goods-table--picture-img')->attr('src');
			$marks[$mark_name]['descr']= iconv("UTF-8", "CP1251",(pq($mark)->find('div.goods-table__browse--features-item')->text()));
			$marks[$mark_name]['url'] = pq($mark)->find('a.goods-table--picture-link')->attr('href');
		}
			
echo '<pre>';
print_r($marks);
echo '</pre>';


    $file_pointer = $_SERVER['DOCUMENT_ROOT']."/temp/fput.csv";
    if (!$file_handle = fopen($file_pointer, 'wb')) exit;
    flock($file_handle, LOCK_EX);
	
	$title = iconv('utf-8','windows-1251', $title);
	fwrite($file_handle, $title);
	
	foreach($marks as $k=>$v){
		$line=$k;
		foreach($v as $value){
			$line.="\n".$value;
		}
		fputs($file_handle,$line."\n");
	}
    
    flock($file_handle, LOCK_UN);
    fclose($file_handle);
    echo '<h2>Fputcsv success!</h2>';


    if (($file_handle = fopen($file_pointer, "r")) !== false) {
        $myarrayecho = array();
        while (($val = fgetcsv($file_handle, 1000, ';')) !== false) {
			$key = $val[0]; array_shift($val);
			$myarrayecho[$key] = $val;
		}
        fclose($handle);
    }

?>