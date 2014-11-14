<?php

global $AppUI,$dPconfig,$loadFromTab;
global $client_id, $obj,$tab, $df;

require_once $AppUI->getModuleClass('socialwork');
require_once $AppUI->getModuleClass('contacts');

$q = new DBQuery;
$q->addTable('social_work');
$q->addQuery ('social_work.*');
$q->addWhere('social_work.social_client_id = '.$client_id);
$q->addOrder('social_work.social_entry_date desc');

$title = 'new social services log entry';
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add counselling visit...";
	$url = "./index.php?m=socialwork&a=addedit&client_id=$client_id";

}
else
{

?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Visit Date' );?></td>
	<th><?php echo $AppUI->_( 'Provider' );?></td>
	<th><?php echo $AppUI->_( 'Child/Caretaker' );?></td>
	<th><?php echo $AppUI->_( 'Child ID' );?></td>
	<th><?php echo $AppUI->_( 'Needs Assess. Act' );?></td>
	<th><?php echo $AppUI->_( 'Actualized Needs' );?></td>
	<th><?php echo $AppUI->_( 'Ind. Prevent. Educ.' );?></td>
	<th><?php echo $AppUI->_( 'Permanency Plan' );?></td>
	<th><?php echo $AppUI->_( 'Nurse & Pal. Care' );?></td>
	<th><?php echo $AppUI->_( 'Hospital Visit' );?></td>
	<th><?php echo $AppUI->_( 'Home Visit' );?></td>
	<th><?php echo $AppUI->_( 'I.G.A/Microfin' );?></td>
	<th><?php echo $AppUI->_( 'Medical Support' );?></td>
	<th><?php echo $AppUI->_( 'Transport Support' );?></td>
	<th><?php echo $AppUI->_( 'Education Support' );?></td>
	<th><?php echo $AppUI->_( 'Clothing & Bedding' );?></td>
	<th><?php echo $AppUI->_( 'Solidarity Support' );?></td>
	<th><?php echo $AppUI->_( 'Rent Support' );?></td>
	<th><?php echo $AppUI->_( 'Other material spt.' );?></td>
	<th><?php echo $AppUI->_( '# Supported' );?></td>
	<th><?php echo $AppUI->_( '# Males' );?></td>
	<th><?php echo $AppUI->_( '# Females' );?></td>
</tr>
<?php
    
	foreach ($rows as $row)
    {
		$w = '';
		//create social work object
		$socialObj = new CSocialWork();
		//format date
		$entry_date = intval($socialObj->social_entry_date) ? new CDate($socialObj->social_entry_date) :  null;

		//$standalone = $ntwk_info->getNetworkType($row["network_standalone"]);
		$url = "./index.php?m=socialwork&a=addedit&client_id=$client_id&social_id=".$row["social_id"];
		
		$contactObj = new CContact();
		$contactObj->load($counsellingObj->social_counsellor_id);

	
		
		$w .= '<tr>';
		$w .= '<td>'. intval ($entry_date) > 0 ? $entry_date->format($df) : ''. '</td>';
		$w .= '<td>'. $AppUI->_($contactObj->getFullName()) . '</td>';
		$w .= '<td>'. $obj->getFullName() . '</td>';
		$w .= '<td>'. $obj->client_adm_no . '</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_needs_assessment) . '</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_food_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_permanency_plan) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_nurse_care) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_hospital_visit) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_home_visit) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_microfin) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_medical_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_transport_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_education_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_clothing) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_solidarity_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_rent_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_other_support) .'</td>';
		$w .= '<td>'. getBoolDesc($wocialObj->social_no_support) .'</td>';
		$w .= '<td>'. $socialObj->social_gender .'</td>';
		//$w .= '<td>'. $wocialObj-> .'</td>';
		//$w .= '<td>'. $wocialObj-> .'</td>';
		$w .= '</tr>';
	}
}
	$w .= '<tr><td colspan="22" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$w .= '</td></tr>';
	echo $w;
?>
</table>