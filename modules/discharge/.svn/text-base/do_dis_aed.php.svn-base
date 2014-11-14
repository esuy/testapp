<?php
$del = dPgetParam ( $_POST, 'del', 0 );
$obj = new CDischarge();
$msg = '';

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}

require_once ("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Discharge Entry' );

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
	$obj->dis_entry_date = storeDate($_POST['dis_entry_date']);

	$obj->dis_status_delta_date = storeDate($_POST['dis_status_delta_date']);

	$obj->dis_status_mdt_date = storeDate($_POST['dis_status_mdt_date']);

	$obj->dis_status_next_date = storeDate($_POST['dis_status_next_date']);

	$obj->dis_client_health_date = storeDate($_POST['dis_client_health_date']);

	$obj->dis_client_psy_date = storeDate($_POST['dis_client_psy_date']);

	$obj->dis_client_social_date = storeDate($_POST['dis_client_social_date']);

	if($obj->dis_client_status == 7){
		$obj->dis_form_type = 'ltp';
	}else{
		$obj->dis_form_type = 'dis';
	}

	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		//change client status to deceased
	} else {
		/*
		*	Update clients table and set new client status and make new entry into status log
			UPD> All client status actions are accessible only through social visits
		*/
		/*if($obj->dis_client_status > 0){
			$sql = 'update clients set client_status="'.(int)$obj->dis_client_status.'" where client_id="'.$obj->dis_client_id.'"';
			$res=mysql_query($sql);

			$sql='insert into status_client (social_client_id,social_client_status,social_entry_date,mode) values ("'.$obj->dis_client_id.'","'.$obj->dis_client_status.'","'.$obj->dis_entry_date.'","status")';
			$res=mysql_query($sql);
		}*/
		db_exec("update clients set client_lvd = '".$obj->dis_entry_date."',client_lvd_form='discharge_info' where client_id = '".$obj->clinical_client_id."' and client_lvd < '".$obj->clinical_entry_date."'");
		$custom_fields = New CustomFields ( $m, 'addedit', $obj->dis_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->dis_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['dis_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->dis_client_id );
}

?>
