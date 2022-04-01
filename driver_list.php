<?php
include('inc/common.php');

$drivertypes = array(
    "ghostscript" => 'Ghostscript&nbsp;built-in',
    "uniprint" => 'Ghostscript&nbsp;Uniprint',
    "filter" => 'Filter',
    "ijs" => 'IJS',
    "cups" => 'CUPS&nbsp;Raster',
    "opvp" => 'OpenPrinting&nbsp;Vector',
    "postscript" => 'PostScript');

$SMARTY->assign("drivertypes",$drivertypes);

$PAGE->setPageTitle('Printer Driver List');
$PAGE->setActiveID('driver');
$PAGE->addBreadCrumb('Drivers',$CONF->baseURL.'drivers/');

$res = $DB->query("
	SELECT dra.id AS id, name, execution, shortdescription, printerCount,
	       package FROM
	       (SELECT id, dr.name AS name, execution, shortdescription,
		       printerCount, driver_package.name AS package FROM
		       (SELECT id, name, execution, shortdescription,
			       pj.count as printerCount FROM
		       driver 
		       LEFT JOIN 
		       (SELECT driver_id, count(printer_id) as count 
			FROM driver_printer_assoc
			GROUP BY driver_id) AS pj
		       ON pj.driver_id = driver.id) AS dr
	       LEFT JOIN driver_package 
	       ON dr.id=driver_package.driver_id) AS dra
	LEFT JOIN driver_approval
	ON dra.id=driver_approval.id
	WHERE (driver_approval.id IS NULL OR
	(driver_approval.approved IS NOT NULL AND
	driver_approval.approved!=0 AND driver_approval.approved!='' AND
	(driver_approval.rejected IS NULL OR driver_approval.rejected=0 OR
	driver_approval.rejected='') AND
	(driver_approval.showentry IS NULL OR
	driver_approval.showentry='' OR
	driver_approval.showentry=1 OR
	driver_approval.showentry<=CAST(NOW() AS DATE))))
	ORDER BY name
	");
$r = $res->toArray('id');

$SMARTY->assign("drivers",$r);

$SMARTY->display('drivers/list.tpl');

?>
