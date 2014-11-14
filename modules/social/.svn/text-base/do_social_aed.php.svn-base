<?php /* SOCIAL VISIT $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */
require_once ($AppUI->getModuleClass ( "household" ));
require_once ($AppUI->getModuleClass ( "services" ));
require_once ($AppUI->getModuleClass ( "admission" ));
require_once ($AppUI->getModuleClass ( "clients" ));

$del = dPgetParam ( $_POST, 'del', 0 );
$household_num_rows = dPgetParam ( $_POST, 'household_num_rows', 0 );
$service_num_rows = dPgetParam ( $_POST, 'service_num_rows', 0 );
//var_dump($service_num_rows)
$obj = new CSocialVisit ( );
$msg = '';

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}
require_once ("./classes/CustomFields.class.php");

$careType = array_keys ( $caretypes );
$careFields = array ('fname', 'lname', 'age', 'status', 'health_status', 'relationship', 'educ_level', 'employment','idno', 'mobile' );
$cleanFields = array('health','health_child_impact','change_notes','new_employment','new_employment_desc');
// 'health_child_impact',
/* 'social_caregiver_employment_change',// 'social_caregiver_income',
					'social_caregiver_new_employment',
					'social_caregiver_new_employment_desc' */



// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Social Visit' );
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

	/*
	* Work with clients table to store nhif and immun fields
	*/

	if((int)$_POST['social_client_id'] > 0){
		$sql='update clients set client_nhif="'.mysql_real_escape_string($_POST['social_nhf']).'",
			client_nhif_n="'.mysql_real_escape_string($_POST['social_nhf_n']).'",
			client_immun="'.mysql_real_escape_string($_POST['social_immun']).'",
			client_immun_n="'.mysql_real_escape_string($_POST['social_immun_n']).'"
			where client_id="'.(int)$_POST['social_client_id'].'"';
		db_exec($sql);
	}

	if ((count ( $_POST ['social_medical_support'] )) > 0) {
		$obj->social_medical_support = implode ( ",", $_POST ['social_medical_support'] );
	}

	if ((count ( $_POST ['social_direct_support'] )) > 0) {
		$obj->social_direct_support = implode ( ",", $_POST ['social_direct_support'] );
	}

	if ((count ( $_POST ['social_solidarity'] )) > 0) {
		$obj->social_solidarity = implode ( ",", $_POST ['social_solidarity'] );
	}

	if ((count ( $_POST ['social_rent'] )) > 0) {
		$obj->social_rent = implode ( ",", $_POST ['social_rent'] );
	}

	if ((count ( $_POST ['social_food'] )) > 0) {
		$obj->social_food = implode ( ",", $_POST ['social_food'] );
	}

	if ((count ( $_POST ['social_education'] )) > 0) {
		$obj->social_education = implode ( ",", $_POST ['social_education'] );
	}

	if ((count ( $_POST ['social_transport'] )) > 0) {
		$obj->social_transport = implode ( ",", $_POST ['social_transport'] );
	}

	if ((count ( $_POST ['social_legal'] )) > 0) {
		$obj->social_legal = implode ( ",", $_POST ['social_legal'] );
	}

	if ((count ( $_POST ['social_nursing'] )) > 0) {
		$obj->social_nursing = implode ( ",", $_POST ['social_nursing'] );
	}
	if ((count ( $_POST ['social_succession_planning'] )) > 0) {
		$obj->social_succession_planning = implode ( ",", $_POST ['social_succession_planning'] );
	}
	if ((count ( $_POST ['social_placement'] )) > 0) {
		$obj->social_placement = implode ( ",", $_POST ['social_placement'] );
	}
	if ((count ( $_POST ['social_nursing'] )) > 0) {
		$obj->social_nursing = implode ( ",", $_POST ['social_nursing'] );
	}
	if ((count ( $_POST ['social_iga'] )) > 0) {
		$obj->social_iga = implode ( ",", $_POST ['social_iga'] );
	}
	if ((count ( $_POST ['social_relocation'] )) > 0) {
		$obj->social_relocation = implode ( ",", $_POST ['social_relocation'] );
	}

	if (! empty ( $_POST ["social_entry_date"] )) {
		$entry_date = new CDate ( $_POST ["social_entry_date"] );
		$obj->social_entry_date = $entry_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["social_next_visit"] )) {
		$next_date = new CDate ( $_POST ["social_next_visit"] );
		$obj->social_next_visit = $next_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["social_death_date"] )) {
		$death_date = new CDate ( $_POST ["social_death_date"] );
		//var_dump($dob);
		$obj->social_death_date = $death_date->format ( FMT_DATETIME_MYSQL );
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
	if (( int ) $_POST ['social_change'] == 1) {
		/**
		 * We need to change caregiver info
		 */
		$migrate=array();
		$newAdded=array();
		foreach ( $careType as $cname ) {
			if ($cname == 'pri') {
				$oppo = 'sec';
			} else {
				$oppo = 'pri';
			}
			$reason = $_POST ['social_caregiver_' . $cname . '_change'];
			//Case of updating current caregiver
			if ($_POST ['social_caregiver_' . $cname . '_type'] == '1') {
				//$obj->{'social_caregiver_'.$cname.'_change'}=1;
				//we have marked changes for this caregiver
				if (! isset ( $reason ) || $reason == 5) {
					if ($obj->{'social_caregiver_' . $cname} > 0) {
						//it means that we just make update of info for existing caregiver
						$cleans = getCareInfo ( 'social_caregiver_' . $cname, $careFields, $_POST );
						$sql = 'update admission_caregivers set
							age="%s",fname="%s",lname="%s",employment="%s",educ_level="%s",idno="%s",mobile="%s",relationship="%s"
							where id="%d"';
						$sql = sprintf ( $sql, $cleans ['age'], $cleans ['fname'], $cleans ['lname'], $cleans ['employment'], $cleans ['educ_level'], $cleans ['idno'], $cleans ['mobile'], $cleans ['relationship'], ( int ) $_POST ['social_caregiver_' . $cname] );
						mysql_query ( $sql );
						if (( int ) $reason == 5) {
							//Change Role of caregiver
							$obj->{'social_caregiver_' . $oppo} = $obj->changeRole ( $_POST ['social_caregiver_' . $cname], $oppo );
							if (count ( $migrate ) == 0) {
								$obj->{'social_caregiver_' . $cname} = null;
							}
							$migrate [] = $oppo;
						}
					}elseif(!isset($reason) && $_POST['social_caregiver'.$cname.'_lname'] != ''){
						//we have case of adding new caregiver through standart caregiver Block
						//collect info and insert it into db
						$newid=insertCaregiver('social_caregiver_'.$cname,$cname);
						if($newid > 0){
							$obj->{'social_caregiver_'.$cname}=$newid;
							$newAdded[]=$cname;
						}
					}

				} elseif (( int ) $reason > 0 && ( int ) $reason < 5 && ( int ) $obj->{'social_caregiver_' . $cname} > 0) {
					//disconnect caregiver from child
					$sql = 'update admission_caregivers set datesoff=now() where id="' . $obj->{'social_caregiver_' . $cname} . '"';
					mysql_query ( $sql );
					$obj->{'social_caregiver_' . $cname} = null;
				}
			}else{
				//$obj->{'social_caregiver_'.$cname.'_change'}=2;
				foreach ($cleanFields as $cfl){
					unset($obj->{'social_caregiver_'.$cname.'_'.$cfl});
				}
			}
			unset ( $reason );

		}
		$newMan = $_POST ['caregiver_new_type'];
		if (isset ( $newMan ) && in_array ( $newMan, $careType ) && $_POST ['social_caregiver_new_fname'] != '' && !in_array($newMan,$newAdded)) {
			$newid=insertCaregiver('social_caregiver_new',$newMan,true);
			if ($newid > 0) {
				$obj->{'social_caregiver_' . $newMan} = $newid;
			}
		}

	} else {
		foreach ( $careFields as $cfd ) {
			foreach ( $careType as $cname ) {
				unset ( $obj->{'social_caregiver_' . $cname . '_' . $cfd} );
			}
		}
	}

	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
	} else {

		//load admission info (caregivers)

		if($obj->social_client_status == 7)	{
			//we have ltp client
			//1.We have to remove all possible copies of this client and any status
			$sql = 'delete from ltp_transfers where client_id="'.$obj->social_client_id.'"';
			db_exec($sql);
			//2.Insert fresh command for transfer
			$sql='insert into ltp_transfers (client_id,status) values("'.$obj->social_client_id.'","0")';
			db_exec($sql);
		}

		//Update LVD
		updateLVD('social_visit',$obj->social_entry_date,$obj->social_client_id,isset($_POST['force_lvd_update']));
		$q = new DBQuery();
		$q->addTable ( 'admission_info' );
		$q->addQuery ( 'admission_info.*' );
		$q->addWhere ( 'admission_info.admission_client_id = ' . $obj->social_client_id );
		$sql = $q->prepare ();
		//var_dump($sql);
		$q->clear ();
		$admissionObj = new CAdmissionRecord ( );
		$admissionObj->admission_client_id = $obj->social_client_id;
		$caretypes = array ('pri', 'sec' );
		if (db_loadObject ( $sql, $admissionObj ) && $_POST ['caregiver_type'] != '' && in_array ( $_POST ['caregiver_type'], $caretypes )) {
			//update admission info (caregivers)
			$pre = 'admission_caregiver_' . $_POST ['caregiver_type'] . '_';
			$admissionObj->{$pre . 'fname'} = $obj->social_caregiver_fname;
			$admissionObj->{$pre . 'lname'} = $obj->social_caregiver_lname;
			$admissionObj->{$pre . 'age'} = $obj->social_caregiver_age;
			$admissionObj->store ();
		}
		//now store family members
		//var_dump ($household_num_rows );
		if (($household_num_rows > 0)) {

			//need to delete the current household members cos delete on client form is not reflected on database
			//$sql = 'DELETE FROM household_info WHERE household_social_id = ' . $obj->social_id;
			$sql='delete from household_info where household_client_id = "'.$obj->social_client_id.'"';
			//var_dump($sql);
			db_exec ( $sql );

			for($count = 1; $count < $household_num_rows; $count ++) {
				if (strlen ( $_POST ["name_$count"] ) > 0) {
					$householdObj = new CHouseholdMember ( );
					//$householdObj->household_id = $_POST["household_id_$count"] ? $_POST["household_id_$count"] : NULL;
					$householdObj->household_client_id = $obj->social_client_id;
					$householdObj->household_social_id = $obj->social_id;
					$householdObj->household_name = $_POST ["name_$count"];
					$householdObj->household_yob = $_POST ["yob_$count"];
					$householdObj->household_relationship = $_POST ["relationship_$count"];
					$householdObj->household_gender = $_POST ["gender_$count"];
					$householdObj->household_notes = $_POST ["notes_$count"];
					$householdObj->household_admission_id = $admissionObj->admission_id;
					$householdObj->household_custom = $_POST["custom_".$count];

					$householdObj->store ();
				}
			}
		}
		if (($service_num_rows > 0)) {

			//need to delete the current household members cos delete on client form is not reflected on database
			$sql = 'DELETE FROM social_services WHERE social_services_social_id = ' . $obj->social_id;
			//var_dump($sql);
			db_exec ( $sql );
			//var_dump($service_num_rows);
			for($count = 1; $count < $service_num_rows; $count ++) {
				//var_dump($_POST["date_$count"]);
				//var_dump($_POST["date_2"]);
				//var_dump($_POST["service_$count"]);
				//var_dump($count);
				if (strlen ( $_POST ["date_$count"] ) > 0) {

					$serviceObj = new CSocialServiceEntry ( );
					//$householdObj->household_id = $_POST["household_id_$count"] ? $_POST["household_id_$count"] : NULL;
					$serviceObj->social_services_client_id = $obj->social_client_id;
					$serviceObj->social_services_social_id = $obj->social_id;
					$serviceObj->social_services_service_id = $_POST ["service_$count"];
					if (! empty ( $_POST ["date_$count"] )) {
						$service_date = new CDate ( $_POST ["date_$count"] );
						//var_dump($dob);
						$serviceObj->social_services_date = $service_date->format ( FMT_DATETIME_MYSQL );
					}

					//$serviceObj->social_services_date = $_POST["date_$count"];
					$serviceObj->social_services_notes = $_POST ["serv_notes_$count"];
					$serviceObj->social_services_value = $_POST ["value_$count"];
					//var_dump($serviceObj);
					$serviceObj->store ();
				}
			}
		}
		//update client status field
		// only for last social visit - not any

		$qs= new DBQuery();
		$qs->addQuery('social_id');
		$qs->addTable('social_visit');
		$qs->addWhere('social_client_id="'.$obj->social_client_id.'"');
		$qs->setLimit(1);
		$qs->addOrder('social_entry_date DESC');
		$isr=$qs->loadResult();

		if ($isr == $obj->social_id) {
			$clientObj = new CClient ();
			$clientObj->load ( $obj->social_client_id );
			$clientObj->client_status = $obj->social_client_status;
			$clientObj->store ();

			$qs= new DBQuery();
			$qs->addQuery('social_client_status');
			$qs->addTable('status_client');
			$qs->addWhere('social_client_id="'.$obj->social_client_id.'"');
			$qs->addOrder('social_entry_date DESC');
			$qs->setLimit(1);
			$lastStatus=$qs->loadResult();

			if($lastStatus != $obj->social_client_status){
				$sql='insert into status_client (social_client_id,social_client_status,social_entry_date,mode) values ("'.$obj->social_client_id.'","'.$obj->social_client_status.'","'.$obj->social_entry_date.'","status")';
				$res=mysql_query($sql);
			}
		}

		if ($clientObj->client_status == 4) {

			//check if we have filled out mortality form for this client, if so, dont redirect to
			$q = new DBQuery ( );
			$q->addTable ( 'mortality_info' );
			$q->addQuery ( 'count(*)' );
			$q->addWhere ( 'mortality_info.mortality_client_id = ' . $clientObj->client_id );
			$count = $q->loadResult ();

			if ($count <= 0) {
				$AppUI->setMsg ( 'Please fill in mortality form', UI_MSG_ALERT, true );
				$AppUI->redirect ( "m=mortality&a=addedit&client_id=" . $clientObj->client_id );
			}
		}

		$custom_fields = New CustomFields ( $m, 'addedit', $obj->social_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->social_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['social_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ();
}
?>
