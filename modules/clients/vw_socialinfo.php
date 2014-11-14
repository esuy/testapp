<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('socialinfo');


$q = new DBQuery;
$q->addTable('social_info');
$q->addQuery ('social_info.*');
$q->addWhere('social_info.social_client_id = '.$client_id);
$s='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add social intake info...";
	$url = "./index.php?m=socialinfo&a=addedit&client_id=$client_id";

}
else
{
	$title="edit social intake info...";
	
	$boolTypes = dPgetSysVal('YesNo');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

//load all sales reps
$q  = new DBQuery;
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 13');
$q->addOrder('c.contact_first_name');

//load contacts
$chw_contacts = arrayMerge(array(0=> '-Select CHW -'),$q->loadHashList());
$q->clear();
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 14');
$q->addOrder('c.contact_first_name');


?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Entry Date' );?></td>
	<th><?php echo $AppUI->_( 'Total Orphan?' );?></td>
	<th><?php echo $AppUI->_( 'Risk Level' );?></td>
	<th><?php echo $AppUI->_( 'Notes' );?></td>
</tr>

<?php
	foreach ($rows as $row)
    {
		$social_info =& new CSocialInfo;
		$social_info->bind($row);
		
		//format date
		$entry_date = intval($social_info->social_entry_date) ? new CDate($social_info->social_entry_date ) :  null;


		//$standalone = $ntwk_info->getNetworkType($row["network_standalone"]);
		$url = "./index.php?m=socialinfo&a=addedit&client_id=$client_id&social_id=".$row["social_id"];
		$totalOrphan = intval($row["social_total_orphan"]);
		
		$s .= '<tr>';
		$s .= '<td><a href="./index.php?m=socialinfo&a=addedit&client_id='. $client_id .'&social_id='. $row["social_id"] . '">'. $entry_date->format($df) . '</a></td>';
		$s .= '<td><a href="./index.php?m=socialinfo&a=addedit&client_id='. $client_id .'&social_id=' .$row["social_id"].'">'. getBoolDesc($row["social_total_orphan"]) .'</a></td>';
		$s .= '<td><a href="./index.php?m=socialinfo&a=addedit&client_id='. $client_id .'&social_id='.$row["social_id"].'">'. $row["social_risk_level"]. '</a></td>';
		$s .= '<td><a href="./index.php?m=socialinfo&a=addedit&client_id='. $client_id .'&social_id='.$row["social_id"].'">'. $row["social_notes"]. '</a></td>';
		$s .= '</tr>';
	}
}

	$s .= '<tr><td colspan="4" align="right" valign="top" style="background-color:#ffffff">';
	$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$s .= '</td></tr>';
	echo $s;
?>
</table>