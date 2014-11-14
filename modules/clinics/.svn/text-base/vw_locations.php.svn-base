<?php
global $AppUI, $clinic_id, $obj;

require_once $AppUI->getModuleClass('locations');

$title = 'new location...';

$q = new DBQuery;
$q->addTable('clinic_location');
$q->addQuery ('clinic_location.*');
$q->addWhere('clinic_location.clinic_location_clinic_id = '.$clinic_id);
$q->addOrder('clinic_location.clinic_location');
$w ='';
$df = $AppUI->getPref('SHDATEFORMAT');
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add location...";
	$url = "./index.php?m=locations&a=addedit&clinic_id=$clinic_id";

}
else
{

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


?>
<table width="75%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Location' );?></td>
</tr>
<?php

    foreach ($rows as $row) 
    {
		$url = "./index.php?m=locations&a=addedit&clinic_id=$clinic_id&location=".$row["clinic_location_id"];
		$locationObj = new CClinicLocation();
		$locationObj->load($row["clinic_location_id"]);
		
		$entry_date = intval( $locationObj->location_entry_date ) ? new CDate( $locationObj->location_entry_date ) : NULL;
		$date_entry = ($entry_date != NULL) ? $entry_date->format($df) : "";
		//var_dump($entry_date->format($df));	
		$w .= '<tr>';
		$w .= '<td><a href="./index.php?m=locations&a=view&clinic_location_id='.$locationObj->clinic_location_id.'&clinic_id='.$clinic_id. '">'. $locationObj->clinic_location.'</a></td>';
		$w .= '</tr>';
	}
}

	$w .= '<tr><td colspan="3" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new location' ).'" onClick="javascript:window.location=\'./index.php?m=locations&a=addedit&clinic_id='.$clinic_id.'&location_id='.$obj->clinic_location_id.'\'">';
	$w .= '</td></tr>';
	echo $w;
		
?>

</table>