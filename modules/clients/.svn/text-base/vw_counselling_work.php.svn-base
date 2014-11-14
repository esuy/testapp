<?php

global $AppUI,$dPconfig,$loadFromTab;
global $client_id, $obj,$tab, $df;

require_once $AppUI->getModuleClass('counsellingwork');
require_once $AppUI->getModuleClass('contacts');

$q = new DBQuery;
$q->addTable('counselling_work');
$q->addQuery ('counselling_work.*');
$q->addWhere('counselling_work.counselling_client_id = '.$client_id);
$q->addOrder('counselling_work.counselling_entry_date', 'desc');

$title = 'new counselling visit log entry';

if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add counselling visit...";
	

}
else
{
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Visit Date' );?></td>
	<th><?php echo $AppUI->_( 'Provider' );?></td>
	<th><?php echo $AppUI->_( 'Child/Caretaker' );?></td>
	<th><?php echo $AppUI->_( 'Admission No.' );?></td>
	<th><?php echo $AppUI->_( 'Support Couns.' );?></td>
	<th><?php echo $AppUI->_( 'Child Couns.' );?></td>
	<th><?php echo $AppUI->_( 'Ind. Prevent. Educ.' );?></td>
	<th><?php echo $AppUI->_( 'Adherence Couns.' );?></td>
	<th><?php echo $AppUI->_( 'Ind. Disclose Couns.' );?></td>
	<th><?php echo $AppUI->_( 'Lifeskiss Training' );?></td>
	<th><?php echo $AppUI->_( 'Recreational therapy' );?></td>
	<th><?php echo $AppUI->_( 'Hospital Visit' );?></td>
	<th><?php echo $AppUI->_( 'Home Visit' );?></td>
</tr>
<?php
    $s = '';
	$url = "./index.php?m=counsellingwork&a=addedit&client_id=$client_id";
	foreach ($rows as $row) 
    {
		
		//create counselling visit object
		$counsellingObj = new CCounsellingWork();
		$counsellingObj->load($row["counselling_id"]);
		//format date
		$entry_date = new CDate($counsellingObj->counselling_date);
	
		//$standalone = $ntwk_info->getNetworkType($row["network_standalone"]);
		//$url = "./index.php?m=counsellingwork&a=addedit&client_id=$client_id&counselling_id=".$row["counselling_id"];
		
		$contactObj = new CContact();
		$contactObj->load($counsellingObj->counselling_counsellor_id);
		
		
		$w .= '<tr>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. $entry_date->format($df) . '</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. $AppUI->_($contactObj->getFullName()) . '</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. $obj->getFullName() . '</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. $obj->client_code . '</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. $obj->client_adm_no . '</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_support_counselling) .'</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_child_counselling) .'</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_ind_prev_educ) .'</td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_adherence_counselling) .'</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_ind_disc_counselling) .'</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_lifeskiss_training) .'</a></td>';
		$w .= '<td>'. getBoolDesc($counsellingObj->counselling_rec_therapy) .'</a></td>';
		$w .= '<td><a href="./index.php?m=counsellingwork&a=view&counselling_id='.$counsellingObj->counselling_id.'&client_id='.$client_id. '">'. getBoolDesc($counsellingObj->counselling_hospital_visit) .'</a></td>';
		$w .= '</tr>';
	}
}
	$w .= '<tr><td colspan="13" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$w .= '</td></tr>';
	echo $w;
?>
</table>