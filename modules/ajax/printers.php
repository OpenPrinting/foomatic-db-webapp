<?php
include('../../inc/siteconf.php');
include('../../inc/db.php');
$CONF = new SiteConfig();
$DB = DB::getInstance();


$array = [];
if ($_GET['_name'] == 'manufacturer') {
	
	$resModel = $DB->query("
            SELECT printer.id AS id, make, model
            FROM printer
            WHERE printer.make = ?
            ORDER BY make, model", $_GET['_value']);
	
	while($rModel = $resModel->getRow()){

		 if ( $_GET['_value'] == $rModel['make'] )
		 {
			$array[] = [$rModel['id'] => $rModel['model']];
	
		  } else
		  {
			$array[] = ['0' => 'No model found'];
		  }		
	}

} 
//tertiary select chain
//dont need right now, leave as example

/*elseif ($_GET['_name'] == 'model') {
	 if ( $_GET['_value'] == 2 )//some parent val
	 {
		$array[] = ['1' => 'some val'];
		$array[] = ['2' => 'Another val'];	
	  } else
	  {
		$array[] = ['0' => 'No city'];
	}
} 
else {
	$array[] = ['1' => 'Data 1'];
	$array[] = ['2' => 'Data 2'];	
	$array[] = ['3' => 'Data 3'];	
}*/

echo json_encode( $array );

?>
