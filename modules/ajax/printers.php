<?php
include('../../inc/siteconf.php');
include('../../inc/db.php');
$CONF = new SiteConfig();
$DB = DB::getInstance();


$array = array();
if ($_GET['_name'] == 'manufacturer') {
	
	$resModel = $DB->query("
            SELECT printer.id AS id, make, model
            FROM printer LEFT JOIN printer_approval
            ON printer.id=printer_approval.id
            WHERE printer.make = '".$_GET['_value']."' AND
            (printer_approval.id IS NULL OR
             ((printer_approval.rejected IS NULL OR
               printer_approval.rejected=0 OR
               printer_approval.rejected='') AND
              (printer_approval.showentry IS NULL OR
               printer_approval.showentry='' OR
               printer_approval.showentry=1 OR
               printer_approval.showentry<=CAST(NOW() AS DATE))))
            ORDER BY make, model");
	
	while($rModel = $resModel->getRow()){

		 if ( $_GET['_value'] == $rModel['make'] )
		 {
			$array[] = array($rModel['id'] => $rModel['model']);
	
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
