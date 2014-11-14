<?php /* MEDICAL ASSESSMENT $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam( $_POST, 'del', 0 );
$obj = new CMedicalAssessment();
$msg = '';
//var_dump($_POST);
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

$obj->medical_conditions = implode(",", $_POST["medical_conditions"]);

require_once("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg( 'Medical Assessment' );
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

		if (!empty($_POST["medical_tb_date1"]))
		{
			$medical_tb_date1 = new CDate( $_POST["medical_tb_date1"] );
					//var_dump($dob);
			$obj->medical_tb_date1 = $medical_tb_date1->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_tb_date2"]))
		{
			$medical_tb_date2 = new CDate( $_POST["medical_tb_date2"] );
					//var_dump($dob);
			$obj->medical_tb_date2 = $medical_tb_date2->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_tb_date3"]))
		{
			$medical_tb_date3 = new CDate( $_POST["medical_tb_date3"] );
					//var_dump($dob);
			$obj->medical_tb_date3 = $medical_tb_date3->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_arv2_startdate"]))
		{
			$medical_arv2_startdate = new CDate( $_POST["medical_arv2_startdate"] );
					//var_dump($dob);
			$obj->medical_arv2_startdate = $medical_arv2_startdate->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_arv2_enddate"]))
		{
			$medical_arv2_enddate = new CDate( $_POST["medical_arv2_enddate"] );
					//var_dump($dob);
			$obj->medical_arv2_enddate = $medical_arv2_enddate->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_arv1_startdate"]))
		{
			$medical_arv1_startdate = new CDate( $_POST["medical_arv1_startdate"] );
					//var_dump($dob);
			$obj->medical_arv1_startdate = $medical_arv1_startdate->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["medical_arv1_enddate"]))
		{
			$medical_arv1_enddate = new CDate( $_POST["medical_arv1_enddate"] );
					//var_dump($dob);
			$obj->medical_arv1_enddate = $medical_arv1_enddate->format( FMT_DATETIME_MYSQL );
		}
  



		/*if ($obj->counselling_date_mothers_status_known == NULL) 
		{
			$obj->counselling_date_mothers_status_known = '0';
		}		
		if ($obj->counselling_mother_date_art == NULL) 
		{
			$obj->counselling_mother_date_art = '0' ;
		}		
		if ($obj->counselling_mother_date_cd4 == NULL) 
		{
			$obj->counselling_mother_date_cd4 = '0';
		}		
		if ($obj->counselling_date_pcr == NULL) 
		{
			$obj->counselling_date_pcr = '0';
		}*/
	if ($msg = $obj->store())
	{
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} 
	else 
	{
 		$custom_fields = New CustomFields( $m, 'addedit', $obj->medical_id, "edit" );
 		$custom_fields->bind( $_POST );
 		$sql = $custom_fields->store( $obj->medical_id ); // Store Custom Fields
		$AppUI->setMsg( @$_POST['medical_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect('m=clients&a=view&client_id='.$obj->medical_client_id);
}
?>
