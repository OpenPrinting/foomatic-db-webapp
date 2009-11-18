<?php
include('../../inc/siteconf.php');
include('../../inc/db.php');
$CONF = new SiteConfig();
$DB = DB::getInstance();


$array = array();
if ($_GET['_name'] == 'manufacturer') {
	
	$resModel = $DB->query("SELECT id, make, model FROM printer WHERE make = '".$_GET['_value']."' ORDER BY make, model");
	
	while($rModel = $resModel->getRow()){

		 if ( $_GET['_value'] == $rModel['make'] )
		 {
			$array[] = array($rModel['model'] => $rModel['model']);
	
		  } else
		  {
			$array[] = array('0' => 'No model found');
		  }		
	}

} 
//tertiary select chain
//dont need right now, leave as example

/*elseif ($_GET['_name'] == 'model') {
	 if ( $_GET['_value'] == 2 )//some parent val
	 {
		$array[] = array('1' => 'some val');
		$array[] = array('2' => 'Another val');	
	  } else
	  {
		$array[] = array('0' => 'No city');
	}
} 
else {
	$array[] = array('1' => 'Data 1');
	$array[] = array('2' => 'Data 2');	
	$array[] = array('3' => 'Data 3');	
}*/

echo json_encode( $array );

?>
