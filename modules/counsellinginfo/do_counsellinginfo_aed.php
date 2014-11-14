<?php /* COUNSELLING INFO $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam( $_POST, 'del', 0 );
$obj = new CCounsellingInfo();
$msg = '';

if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

require_once("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg( 'Intake & PCR' );
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

  

		if (!empty($_POST["counselling_dob"]))
		{
			$dob = new CDate( $_POST["counselling_dob"] );
					//var_dump($dob);
			$obj->counselling_dob = $dob->format( FMT_DATETIME_MYSQL );
		}	
		
		if (!empty($_POST["counselling_entry_date"]))
		{
			$entry_date = new CDate( $_POST["counselling_entry_date"] );
								//var_dump($dob);
			$obj->counselling_entry_date = $entry_date->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_child_nvp_date"]))
		{
			$child_nvp_date = new CDate( $_POST["counselling_child_nvp_date"] );
					//var_dump($dob);
			$obj->counselling_child_nvp_date = $child_nvp_date->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_child_azt_date"]))
		{
			$child_azt_date = new CDate( $_POST["counselling_child_azt_date"] );
					//var_dump($dob);
			$obj->counselling_child_azt_date = $child_azt_date->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_mother_date_art"]))
		{
			$mother_date_art = new CDate( $_POST["counselling_mother_date_art"] );
					//var_dump($dob);
			$obj->counselling_mother_date_art = $mother_date_art->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_mother_date_cd4"]))
		{
			$mother_date_cd4 = new CDate( $_POST["counselling_mother_date_cd4"] );
					//var_dump($dob);
			$obj->counselling_mother_date_cd4 = $mother_date_cd4->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_rapid18_date"]))
		{
			$rapid18_date = new CDate( $_POST["counselling_rapid18_date"] );
					//var_dump($dob);
			$obj->counselling_rapid18_date = $rapid18_date->format( FMT_DATETIME_MYSQL );
		}		
		if (!empty($_POST["counselling_determine_date"]))
		{
			$determine_date = new CDate( $_POST["counselling_determine_date"] );
					//var_dump($dob);
			$obj->counselling_determine_date = $determine_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_bioline_date"]))
		{
			$bioline_date = new CDate( $_POST["counselling_bioline_date"] );
					//var_dump($dob);
			$obj->counselling_bioline_date = $bioline_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_unigold_date"]))
		{
			$unigold_date = new CDate( $_POST["counselling_unigold_date"] );
					//var_dump($dob);
			$obj->counselling_unigold_date = $unigold_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_elisa_date"]))
		{
			$elisa_date = new CDate( $_POST["counselling_elisa_date"] );
					//var_dump($dob);
			$obj->counselling_elisa_date = $elisa_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_pcr1_date"]))
		{
			$pcr1_date = new CDate( $_POST["counselling_pcr1_date"] );
					//var_dump($dob);
			$obj->counselling_pcr1_date = $pcr1_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_pcr2_date"]))
		{
			$pcr2_date = new CDate( $_POST["counselling_pcr2_date"] );
					//var_dump($dob);
			$obj->counselling_pcr2_date = $pcr2_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_rapid12_date"]))
		{
			$rapid12_date = new CDate( $_POST["counselling_rapid12_date"] );
					//var_dump($dob);
			$obj->counselling_rapid12_date = $rapid12_date->format( FMT_DATETIME_MYSQL );
		}
		if (!empty($_POST["counselling_other_date"]))
		{
			$other_date = new CDate( $_POST["counselling_other_date"] );
					//var_dump($dob);
			$obj->counselling_other_date = $other_date->format( FMT_DATETIME_MYSQL );
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
		db_exec("update clients set client_lvd = '".$obj->counselling_entry_date."' where client_id = '".$obj->counselling_client_id."' and client_lvd < '".$obj->counselling_entry_date."'");
 		$custom_fields = New CustomFields( $m, 'addedit', $obj->counselling_id, "edit" );
 		$custom_fields->bind( $_POST );
 		$sql = $custom_fields->store( $obj->counselling_id ); // Store Custom Fields
		$AppUI->setMsg( @$_POST['counselling_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect('m=clients&a=view&client_id='.$obj->counselling_client_id);
}
?>
