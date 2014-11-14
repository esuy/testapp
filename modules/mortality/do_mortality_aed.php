<?php /* MORTALITY INFO $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam ( $_POST, 'del', 0 );
$obj = new CMortality ();
$msg = '';

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}

require_once ("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Mortality Entry' );
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
	if (! empty ( $_POST ["mortality_entry_date"] )) {
		$entry_date = new CDate ( $_POST ["mortality_entry_date"] );
		$obj->mortality_entry_date = $entry_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_relative_report_date"] )) {
		$illness_date = new CDate ( $_POST ["mortality_relative_report_date"] );
		$obj->mortality_relative_report_date = $illness_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_date"] )) {
		$mortality_date = new CDate ( $_POST ["mortality_date"] );
		$obj->mortality_date = $mortality_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_hospital_adm_date"] )) {
		$admission_date = new CDate ( $_POST ["mortality_hospital_adm_date"] );
		$obj->mortality_hospital_adm_date = $admission_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_clinical_officer_date"] )) {
		$admission_date = new CDate ( $_POST ["mortality_clinical_officer_date"] );
		$obj->mortality_clinical_officer_date = $admission_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_tb_start"] )) {
		$tb_date = new CDate ( $_POST ["mortality_tb_start"] );
		$obj->mortality_tb_start = $tb_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_clinical_date"] )) {
		$clin_date = new CDate ( $_POST ["mortality_clinical_date"] );
		$obj->mortality_clinical_date = $clin_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_nutrition_date"] )) {
		$nutr_date = new CDate ( $_POST ["mortality_nutrition_date"] );
		$obj->mortality_nutrition_date = $nutr_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_enroll_date"] )) {
		$in_date = new CDate ( $_POST ["mortality_enroll_date"] );
		$obj->mortality_enroll_date = $in_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["mortality_arv_dateon"] )) {
		$arv_date = new CDate ( $_POST ["mortality_arv_dateon"] );
		$obj->mortality_arv_dateon = $arv_date->format ( FMT_DATETIME_MYSQL );
	}
	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		//change client status to deceased
	} else {
		//db_exec("update clients set client_lvd = '".$obj->mortality_entry_date."',client_lvd_form='mortality_info' where client_id = '".
		//$obj->clinical_client_id."' and client_lvd < '".$obj->clinical_entry_date."'");
		updateLVD('mortality_info',$obj->mortality_client_id,$obj->mortality_entry_date,isset($_POST['force_lvd_update']));
		
		$custom_fields = New CustomFields ( $m, 'addedit', $obj->mortality_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->mortality_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['clinical_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->mortality_client_id );
}
?>
