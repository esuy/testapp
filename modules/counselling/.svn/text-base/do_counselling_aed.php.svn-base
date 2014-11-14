<?php /* COUNSELLING INFO $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam ( $_POST, 'del', 0 );
$obj = new CCounsellingVisit ( );
$msg = '';

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}
if ((count ( $_POST ['counselling_child_issues'] )) > 0) {
	$obj->counselling_child_issues = implode ( ",", $_POST ['counselling_child_issues'] );
}
if ((count ( $_POST ['counselling_caregiver_issues'] )) > 0) {
	$obj->counselling_caregiver_issues = implode ( ",", $_POST ['counselling_caregiver_issues'] );
}
if ((count ( $_POST ['counselling_caregiver_issues2'] )) > 0) {
	$obj->counselling_caregiver_issues2 = implode ( ",", $_POST ['counselling_caregiver_issues2'] );
}
if ((count ( $_POST ['counselling_counselling_services'] )) > 0) {
	$obj->counselling_counselling_services = implode ( ",", $_POST ['counselling_counselling_services'] );
}
require_once ("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Counselling Visit' );
if ($del) {
	if (! $obj->canDelete ( $msg )) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	}
	if (($msg = $obj->delete ())) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	} else {
		$AppUI->setMsg ( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect ( 'm=clients' );
	}
} else {

	/*if (! empty ( $_POST ["counselling_dob"] )) {
		$dob = new CDate ( $_POST ["counselling_dob"] );
		//var_dump($dob);
		$obj->counselling_dob = $dob->format ( FMT_DATETIME_MYSQL );
	}*/
	if (! empty ( $_POST ["counselling_entry_date"] )) {
		$entry_date = new CDate ( $_POST ["counselling_entry_date"] );
		//var_dump($dob);
		$obj->counselling_entry_date = $entry_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_next_visit"] )) {
		$next_date = new CDate ( $_POST ["counselling_next_visit"] );
		//var_dump($dob);
		$obj->counselling_next_visit = $next_date->format ( FMT_DATETIME_MYSQL );
	}
	/*if (! empty ( $_POST ["counselling_child_nvp_date"] )) {
		$nvp_date = new CDate ( $_POST ["counselling_child_nvp_date"] );
		//var_dump($dob);
		$obj->counselling_child_nvp_date = $nvp_date->format ( FMT_DATETIME_MYSQL );
	}

	if (! empty ( $_POST ["counselling_mother_date_art"] )) {
		$mother_art_date = new CDate ( $_POST ["counselling_mother_date_art"] );
		//var_dump($dob);
		$obj->counselling_mother_date_art = $mother_art_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_mother_date_cd4"] )) {
		$mother_cd4_date = new CDate ( $_POST ["counselling_mother_date_cd4"] );
		//var_dump($dob);
		$obj->counselling_mother_date_cd4 = $mother_cd4_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_child_azt_date"] )) {
		$child_azt_date = new CDate ( $_POST ["counselling_child_azt_date"] );
		//var_dump($dob);
		$obj->counselling_child_azt_date = $child_azt_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_determine_date"] )) {
		$determine_date = new CDate ( $_POST ["counselling_determine_date"] );
		//var_dump($dob);
		$obj->counselling_determine_date = $determine_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_bioline_date"] )) {
		$bioline_date = new CDate ( $_POST ["counselling_bioline_date"] );
		//var_dump($dob);
		$obj->counselling_bioline_date = $bioline_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_unigold_date"] )) {
		$unigold_date = new CDate ( $_POST ["counselling_unigold_date"] );
		//var_dump($dob);
		$obj->counselling_unigold_date = $unigold_date->format ( FMT_DATETIME_MYSQL );
	}

	if (! empty ( $_POST ["counselling_elisa_date"] )) {
		$elisa_date = new CDate ( $_POST ["counselling_elisa_date"] );
		//var_dump($dob);
		$obj->counselling_elisa_date = $elisa_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_pcr1_date"] )) {
		$pcr1_date = new CDate ( $_POST ["counselling_pcr1_date"] );
		//var_dump($dob);
		$obj->counselling_pcr1_date = $pcr1_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_pcr2_date"] )) {
		$pcr2_date = new CDate ( $_POST ["counselling_pcr2_date"] );
		//var_dump($dob);
		$obj->counselling_pcr2_date = $pcr2_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_rapid12_date"] )) {
		$rapid12_date = new CDate ( $_POST ["counselling_rapid12_date"] );
		//var_dump($dob);
		$obj->counselling_rapid12_date = $rapid12_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["counselling_rapid18_date"] )) {
		$rapid18_date = new CDate ( $_POST ["counselling_rapid18_date"] );
		//var_dump($dob);
		$obj->counselling_rapid18_date = $rapid18_date->format ( FMT_DATETIME_MYSQL );
	}
	if(isset($_POST['move_active']) && (int)$_POST['move_active'] == 1 && (int)$_POST['client[client_id]'] > 0){
		$sql='update clients set client_status="1" where client_id= "'.(int)$_POST['client[client_id]'].'"';
		mysql_query($sql);

	}*/

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

	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
	} else {
		//db_exec("update clients set client_lvd = '".$obj->counselling_entry_date."',client_lvd_form='counselling_visit' where client_id = '".$obj->counselling_client_id."' and client_lvd < '".$obj->counselling_entry_date."'");
		updateLVD('counselling_visit',$obj->counselling_client_id,$obj->counselling_entry_date,isset($_POST['force_lvd_update']));
		$custom_fields = New CustomFields ( $m, 'addedit', $obj->counselling_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->counselling_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['counselling_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->counselling_client_id );
}
?>
