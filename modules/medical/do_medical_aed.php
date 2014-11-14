<?php /* MEDICAL ASSESSMENT $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
$del = dPgetParam ( $_POST, 'del', 0 );

$medical_num_rows = dPgetParam ( $_POST, 'medical_num_rows', 0 );
$drugs_num_rows = dPgetParam ( $_POST, 'drugs_num_rows', 0 );

function findHospital ($var){
	if(strstr($var,'hospital_')){
		return true;
	}
}

function findDrug ($var){
	if(strstr($var,'drug_')){
		return true;
	}
}

$obj = new CMedicalAssessment ( );
$msg = '';
//var_dump($_POST);
//exit;
if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}

require_once ("./classes/CustomFields.class.php");
require_once ($AppUI->getModuleClass ( "medicalhistory" ));
require_once ($AppUI->getModuleClass ( "medicationhistory" ));

if (! empty ( $_POST ["medical_conditions"] )) {
	$obj->medical_conditions = implode ( ",", $_POST ["medical_conditions"] );
}
if (! empty ( $_POST ["medical_sensory_motor_ability"] )) {
	$obj->medical_sensory_motor_ability = implode ( ",", $_POST ["medical_sensory_motor_ability"] );
}
if (! empty ( $_POST ["medical_lymph"] )) {
	$obj->medical_lymph = implode ( ",", $_POST ["medical_lymph"] );
}
// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Medical Assessment' );
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

	if (! empty ( $_POST ["medical_entry_date"] )) {
		$medical_date = new CDate ( $_POST ["medical_entry_date"] );
		//var_dump($dob);
		$obj->medical_entry_date = $medical_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_tb_date1"] )) {
		$medical_tb_date1 = new CDate ( $_POST ["medical_tb_date1"] );
		//var_dump($dob);
		$obj->medical_tb_date1 = $medical_tb_date1->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_next_visit"] )) {
		$medical_next = new CDate ( $_POST ["medical_next_visit"] );
		//var_dump($dob);
		$obj->medical_next_visit = $medical_next->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_tb_date_diagnosed"] )) {
		$medical_tb_date_diagnosed = new CDate ( $_POST ["medical_tb_date_diagnosed"] );
		//var_dump($dob);
		$obj->medical_tb_date_diagnosed = $medical_tb_date_diagnosed->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_tb_date2"] )) {
		$medical_tb_date2 = new CDate ( $_POST ["medical_tb_date2"] );
		//var_dump($dob);
		$obj->medical_tb_date2 = $medical_tb_date2->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_tb_date3"] )) {
		$medical_tb_date3 = new CDate ( $_POST ["medical_tb_date3"] );
		//var_dump($dob);
		$obj->medical_tb_date3 = $medical_tb_date3->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_arv2_startdate"] )) {
		$medical_arv2_startdate = new CDate ( $_POST ["medical_arv2_startdate"] );
		//var_dump($dob);
		$obj->medical_arv2_startdate = $medical_arv2_startdate->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_arv2_enddate"] )) {
		$medical_arv2_enddate = new CDate ( $_POST ["medical_arv2_enddate"] );
		//var_dump($dob);
		$obj->medical_arv2_enddate = $medical_arv2_enddate->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_salvage_startdate"] )) {
		$medical_sal_startdate = new CDate ( $_POST ["medical_salvage_startdate"] );
		//var_dump($dob);
		$obj->medical_salvage_startdate = $medical_sal_startdate->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_salvage_enddate"] )) {
		$medical_sal_enddate = new CDate ( $_POST ["medical_salvage_enddate"] );
		//var_dump($dob);
		$obj->medical_salvage_enddate = $medical_sal_enddate->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_arv1_startdate"] )) {
		$medical_arv1_startdate = new CDate ( $_POST ["medical_arv1_startdate"] );
		//var_dump($dob);
		$obj->medical_arv1_startdate = $medical_arv1_startdate->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["medical_arv1_enddate"] )) {
		$medical_arv1_enddate = new CDate ( $_POST ["medical_arv1_enddate"] );
		//var_dump($dob);
		$obj->medical_arv1_enddate = $medical_arv1_enddate->format ( FMT_DATETIME_MYSQL );
	}
	if(! empty ( $_POST ["medical_request_opts"] )){
		$obj->medical_request_opts = implode(',',$_POST['medical_request_opts']);
	}
	//var_dump($obj);
	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
	} else //store medical history
	{
		$post_keys = array_keys($_POST);
		//db_exec("update clients set client_lvd = '".$obj->medical_entry_date."',client_lvd_form='medical_assessment' where client_id = '".$obj->medical_client_id."' and client_lvd < '".$obj->medical_entry_date."'");
		updateLVD('medical_assessment',$obj->medical_client_id,$obj->medical_entry_date,isset($_POST['force_lvd_update']));
		if (($medical_num_rows > 0) /*&& (! empty ( $_POST ["hospital_1"] ))*/) {
			//var_dump($medical_num_rows);
			//need to delete the current medical history records cos delete on client form is not reflected on database - ugly hack but what can i do

			$sql = 'DELETE FROM medical_history WHERE medical_history_medical_id = ' . $obj->medical_id;
			db_exec ( $sql );
			$mkeys= array_filter($post_keys,"findHospital");
			if(count($mkeys) > 0){
				foreach ($mkeys as $pkey) {
					$xcnt= preg_match('/_(\d+)$/',$pkey,$countz);
					$count=$countz[1];
					//for($count = 1; $count < $medical_num_rows; $count ++) {
					if(is_numeric($count) && $count > 0){
						$medicalHistoryObj = new CMedicalHistory ( );
						//$medicalHistoryObj->medical_history_id = $_POST["medical_history_id_$count"] ? $_POST["medical_history_id_$count"] : NULL;
						$medicalHistoryObj->medical_history_client_id = $obj->medical_client_id;
						$medicalHistoryObj->medical_history_medical_id = $obj->medical_id;
						$medicalHistoryObj->medical_history_hospital = $_POST ["hospital_$count"];
						if (! empty ( $_POST ["date_$count"] )) {
							$diagnosis_date = new CDate ( $_POST ["date_$count"] );
							$medicalHistoryObj->medical_history_date = $diagnosis_date->format ( FMT_DATETIME_MYSQL );
						}
						$medicalHistoryObj->medical_history_diagnosis = $_POST ["reason_$count"];
						$medicalHistoryObj->store ();
					}
				}
			}
		}
	}
	if (($drugs_num_rows > 0) /*&& (! empty ( $_POST ["drug_1"] ))*/) {
		//need to delete the current medication records cos delete on client form is not reflected on database - ugly hack but what can i do
		$sql = 'DELETE FROM medications_history WHERE medications_history_medical_id = ' . $obj->medical_id;
		db_exec ( $sql );
		$dkeys= array_filter($post_keys,"findDrug");
		if(count($dkeys) > 0){
			foreach ($dkeys as $pkey) {
				$xcnt= preg_match('/_(\d+)$/',$pkey,$countz);
				$count=$countz[1];
				if(is_numeric($count) && $count > 0){
					//for($count = 1; $count < $drugs_num_rows; $count ++) {
					$medicationObj = new CMedicationHistory ( );
					//$medicationObj->medications_history_id = $_POST["medications_history_id_$count"] ? $_POST["medications_history_id_$count"] : NULL;
					$medicationObj->medications_history_client_id = $obj->medical_client_id;
					$medicationObj->medications_history_medical_id = $obj->medical_id;
					$medicationObj->medications_history_drug = $_POST ["drug_$count"];
					$medicationObj->medications_history_dose = $_POST ["dose_$count"];
					$medicationObj->medications_history_frequency = $_POST ["frequency_$count"];
					$medicationObj->store ();
				}
			}
		}

		$custom_fields = New CustomFields ( $m, 'addedit', $obj->medical_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->medical_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['medical_id'] ? 'updated' : 'added', UI_MSG_OK, true );

	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->medical_client_id );
}
?>
