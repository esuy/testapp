<?php /* ADMISSION RECORD $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
require_once ($AppUI->getModuleClass ( "household" ));
require_once ($AppUI->getModuleClass ( "caregivers" ));

function findRelation ($var){
	if(strstr($var,'relationship_')){
		return true;
	}
}

$del = dPgetParam ( $_POST, 'del', 0 );

$household_num_rows = dPgetParam ( $_POST, 'household_num_rows', 0 );

$obj = new CAdmissionRecord ( );

$msg = '';

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}

require_once ("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Admission Record' );
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

	if (! empty ( $_POST ["admission_entry_date"] )) {
		$entry_date = new CDate ( $_POST ["admission_entry_date"] );
		//var_dump($dob);
		$obj->admission_entry_date = $entry_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["admission_dob"] )) {
		$dob = new CDate ( $_POST ["admission_dob"] );
		//var_dump($dob);
		$obj->admission_dob = $dob->format ( FMT_DATETIME_MYSQL );
	}
	if(isset($_POST['admission_enclosures']) && count($_POST['admission_enclosures']) > 0){
		$obj->admission_enclosures = join(',',$_POST['admission_enclosures']);
	}
	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
	} else {
		//db_exec("update clients set client_lvd = '".$obj->admission_entry_date."',client_lvd_form='admission_info' where 
		//client_id = '".$obj->admission_client_id."' and client_lvd < '".$obj->admission_entry_date."'");
		updateLVD('admission_info',$obj->admission_client_id,$obj->admission_entry_date,isset($_POST['force_lvd_update']));
		//store family members
		if (($household_num_rows > 0)) {
			$pkeys = array_keys($_POST);
			$needKeys = array_filter($pkeys,'findRelation');
			//need to delete the current household members cos delete on client form is not reflected on database
			$sql = 'DELETE FROM household_info WHERE household_client_id = ' . $obj->admission_client_id;
			db_exec ( $sql );
			if(count($needKeys) > 0){
				foreach ($needKeys as $nkey) {

					$xcnt= preg_match('/_(\d+)$/',$nkey,$countz);
					$count=$countz[1];
					if(is_numeric($count) && intval($count) > 0){
						//now add household members
						//for($count = 1; $count < $household_num_rows; $count ++) {
						$householdObj = new CHouseholdMember ( );
						//$householdObj->household_id = $_POST["household_id_$count"] ? $_POST["household_id_$count"] : NULL;
						$householdObj->household_client_id = $obj->admission_client_id;
						$householdObj->household_admission_id = $obj->admission_id;
						$householdObj->household_name = $_POST ["name_$count"];
						$householdObj->household_yob = $_POST ["yob_$count"];
						$householdObj->household_relationship = $_POST ["relationship_$count"];
						$householdObj->household_gender = $_POST ["gender_$count"];
						$householdObj->household_notes = $_POST ["notes_$count"];
						$householdObj->household_custom = $_POST ["custom_$count"];
						$householdObj->store ();
					}
				}
			}
		}

		/**
		 * There was here db insert into caregiver_client table 
		 * Now this data is moved towards admission_caregivers
		 */

		$custom_fields = New CustomFields ( $m, 'addedit', $obj->admission_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->admission_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['admission_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->admission_client_id );
}
?>
