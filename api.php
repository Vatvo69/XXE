<?php
#error_reporting(0);
$result = array();

if(isset($_POST['api'])){
		$api = $_POST['api'];
		$city = $_POST['city'];
		$api = str_replace('\#', '', $api);
		$api = str_replace('\?', '', $api);
		$p = substr($api, 0,7);
		$d = substr($api, strripos($api, '.'),strlen($api)-strripos($api, '.'));

		if($p === 'http://' && $d ==='.xml'){
			$city= strtolower(str_replace('\'', '', $city));
			$api = str_replace('city', $city, $api);
			$xml = file_get_contents($api);
			libxml_disable_entity_loader(false);
			try{
				$dom = new DOMDocument();
				$dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
				$weather = simplexml_import_dom($dom);

			}catch(Exception $e){
				$result = $e->getMessage();
			}
       		$tq = '';
       		$f = '';
       		foreach($weather->children() as $child)
			{
				if(strstr($child['cityname'],'市')){
					$city = $child['cityname'];
					$tq = $child['stateDetailed'];
					$f = $child['windState'];
				}
			}
			$result['success'] = true;
			$result['msg'] = '来自'.$city.'的道友,你那里现在是'.$tq.' 风向为'.$f;
        
		}else{
			$result['success'] = true;
       		$result['msg'] = '<!-- how do you do?-->';
       		#$result['msg'] = $api;
		}
echo json_encode($result);
}

?>
