<?php /* CLINICAL INFO $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam( $_POST, 'del', 0 );
$obj = new CClinicalVisit();
$msg = '';

if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

require_once("./classes/CustomFields.class.php");

if ((count($_POST['clinical_vitamins'])) > 0)
{
	$obj->clinical_vitamins = implode(",", $_POST['clinical_vitamins']);
}
if ((count($_POST['clinical_nutritional_support'])) > 0)
{
	$obj->clinical_nutritional_support = implode(",", $_POST['clinical_nutritional_support']);
}
if ((count($_POST['clinical_arv_drugs'])) > 0)
{
	$obj->clinical_arv_drugs = implode(",", $_POST['clinical_arv_drugs']);
}
if ((count($_POST['clinical_tb_drugs'])) > 0)
{
	$obj->clinical_tb_drugs = implode(",", $_POST['clinical_tb_drugs']);
}
// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg( 'Clinical Info' );
if ($del) {
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
		$AppUI->setMsg( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect( 'm=clients' );
	}
}
else
{
			if (!empty($_POST["clinical_entry_date"]))
			{
					$entry_date = new CDate( $_POST["clinical_entry_date"] );
					//var_dump($dob);
					$obj->clinical_entry_date = $entry_date->format( FMT_DATETIME_MYSQL );
			}
			if (!empty($_POST["clinical_next_date"]))
			{
					$next_date = new CDate($_POST["clinical_next_date"], DATE_FORMAT_ISO);
					$obj->clinical_next_date = $next_date->format( FMT_DATETIME_MYSQL );
			}
			if (!empty($_POST["clinical_tb_treatment_date"]))
			{
					$tb_treatment_date = new CDate( $_POST["clinical_tb_treatment_date"] );
					//var_dump($dob);
					$obj->clinical_tb_treatment_date = $tb_treatment_date->format( FMT_DATETIME_MYSQL );
			}
			if (!empty($_POST["clinical_bloodtest_date"]))
			{
					$bloodtest_date = new CDate( $_POST["clinical_bloodtest_date"] );
					//var_dump($dob);
					$obj->clinical_bloodtest_date = $bloodtest_date->format( FMT_DATETIME_MYSQL );
			}
			//var_dump($obj->clinical_muac );
			$obj->clinical_age_yrs = intval($obj->clinical_age_yrs);
			$obj->clinical_age_months = intval($obj->clinical_age_months);

			if(count($_POST['clinical_dss']) > 0){
				$obj->clinical_dss=implode(',',$_POST['clinical_dss']);
			}
			if(count($_POST['clinical_request_list']) > 0){
				$obj->clinical_request_list=implode(',',$_POST['clinical_request_list']);
			}
/*
		if ($obj->clinical_next_date == NULL)
		{
			$obj->clinical_next_date = '0';
		}
		if ($obj->clinical_bloodtest_date == NULL)
		{
			$obj->clinical_bloodtest_date = '0' ;
		}
		if ($obj->clinical_tb_treatment_date == NULL)
		{
			$obj->clinical_tb_treatment_date = '0';
		}
*/
	if ($msg = $obj->store())
	{
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	}
	else
	{
		//db_exec("update clients set client_lvd = '".$obj->clinical_entry_date."',client_lvd_form='clinical_visits' where client_id = '".$obj->clinical_client_id."' and client_lvd < '".$obj->clinical_entry_date."'");
		updateLVD('clinical_visits',$obj->clinical_client_id,$obj->clinical_entry_date,isset($_POST['force_lvd_update']));
 		$custom_fields = New CustomFields( $m, 'addedit', $obj->clinical_id, "edit" );
 		$custom_fields->bind( $_POST );
 		$sql = $custom_fields->store( $obj->clinical_id ); // Store Custom Fields
		$AppUI->setMsg( @$_POST['clinical_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect('m=clients&a=view&client_id='. $obj->clinical_client_id);
}
?>
