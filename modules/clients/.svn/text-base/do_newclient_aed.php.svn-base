<?php

require_once ($AppUI->getModuleClass ( 'socialinfo' ));
require_once ($AppUI->getModuleClass ( 'counsellinginfo' ));

$del = isset ( $_POST ['del'] ) ? $_POST ['del'] : 0;
$changestatus = isset ( $_POST ['changestatus'] ) ? $_POST ['changestatus'] : 0;

$sub_form = isset ( $_POST ['sub_form'] ) ? $_POST ['sub_form'] : 0;
//social info
$socialinfo = setItem ( "social" );
//counselling info
$counsellinginfo = setItem ( "counselling" );

//handle new clients
$contact_unique_update = setItem ( "insert_id" );

$client = setItem ( "client" );

//print_r($_POST);
$client_id = setItem ( "client_id", 0 );
$new_status = setItem ( "status", 0 );

$del = setItem ( "del", 0 );

$AppUI->setMsg ( 'Client' );

$clientObj = new CClient ( );

//take care of deleting
/*if ($del)
{
	$companyObj->load($company_id);

  	if (!$companyObj->canDelete( $msg ))
    {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	if (($msg = $companyObj->delete()))
    {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
    else
    {
		$AppUI->setMsg( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect( '', -1 );
	}

}
*/
/*
if ($sub_form)
{
	print($sub_form);
	// in add-edit, so set it to what it should be
	$AppUI->setState('CompanyAeTabIdx', $_POST['newTab']);
	if (isset($_POST['subform_processor']))
	{
		$root = $dPconfig['root_dir'];
		print "subform_processor!";
		if (isset($_POST['subform_module']))
			$mod = $AppUI->checkFileName($_POST['subform_module']);
		else
			$mod = 'companies';
		$proc = $AppUI->checkFileName($_POST['subform_processor']);
		include "$root/modules/$mod/$proc.php";
		print "$root/modules/$mod/$proc.php";
	}
} */
//else
//{


// Include any files for handling module-specific requirements
foreach ( findTabModules ( 'companies', 'addedit' ) as $mod ) {
	$fname = dPgetConfig ( 'root_dir' ) . "/modules/$mod/client_dosql.addedit.php";
	dprint ( __FILE__, __LINE__, 3, "checking for $fname" );
	if (file_exists ( $fname ))
		require_once $fname;
}

// If we have an array of pre_save functions, perform them in turn.
if (isset ( $pre_save )) {
	foreach ( $pre_save as $pre_save_function )
		$pre_save_function ();
} else {
	dprint ( __FILE__, __LINE__, 1, "No pre_save functions." );
}

/*if (($company_id) && ($company_id > 0))
		$companyObj->load($company_id);

	if ( isset($company))
	{
		$companyObj->bind($company);
	}*/

if ($del) {
	if (! $clientObj->load ( $client_id )) {
		$AppUI->setMsg ( $clientObj->getError (), UI_MSG_ERROR );
		$AppUI->redirect ();

	}
	if (! $clientObj->canDelete ( $msg )) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	}
	if (($msg = $clientObj->delete ())) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	} else {
		$AppUI->setMsg ( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect ( "index.php?m=clients" );
	}
} elseif ($changestatus) {
	if (! $clientObj->load ( $client_id )) {
		$AppUI->setMsg ( $clientObj->getError (), UI_MSG_ERROR );
		$AppUI->redirect ();

	}
	$clientObj->client_status = $new_status;

	if(isset($_POST['counselling']['counselling_dob'])){
		$clientObj->client_dob = $_POST['counselling']['counselling_dob'];
	}
	if(isset($_POST['counselling']['counselling_gender'])){
		$clientObj->client_gender = $_POST['counselling']['counselling_gender'];
	}
	$clientObj->client_center = $_POST['counselling']['counselling_clinic'];

	if (($msg = $clientObj->store ())) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	} else {
		$AppUI->setMsg ( 'status updated.', UI_MSG_ALERT, true );
		//var_dump($clientObj->client_status);
		if ($clientObj->client_status == 4) {

			//check if we have filled out mortality form for this client, if so, dont redirect to
			$q = new DBQuery ( );
			$q->addTable ( 'mortality_info' );
			$q->addQuery ( 'count(*)' );
			$q->addWhere ( 'mortality_info.mortality_client_id = ' . $clientObj->client_id );
			$count = $q->loadResult ();

			if ($count <= 0) {
				$AppUI->setMsg ( 'Please fill in mortality form', UI_MSG_ALERT, true );
				$AppUI->redirect ( "m=mortality&a=addedit&client_id=$client_id" );
			}
		}
		$AppUI->redirect ();
	}

} else {
	if($client['client_id'] > 0){
		$clientObj->load($client['client_id']);
	}
	if (! $clientObj->bind ( $client )) {
		$AppUI->setMsg ( $clientObj->getError (), UI_MSG_ERROR );
		$AppUI->redirect ();
	}


	//var_dump($clientObj->client_entry_date);
	$entry_date = new CDate ( $_POST ['counselling'] ["counselling_entry_date"] );
	if($_POST['counselling']['counselling_admission_date']){
	    $addd=new CDate($_POST['counselling']['counselling_admission_date']);
	    $clientObj->client_doa = $addd->format ( FMT_DATETIME_MYSQL );
	    $clientObj->client_entry_date = $addd->format ( FMT_DATETIME_MYSQL );
	}
	if(intval($_POST['move_active']) == 1){
		$clientObj->client_status = 1;
	}elseif ($clientObj->client_status === null ){
		$clientObj->client_status = 9;
	}

	if(isset($_POST['counselling']['counselling_dob'])){
		$dob = new CDate ( $counsellinginfo ["counselling_dob"] );
		$clientObj->client_dob = $dob->format ( FMT_DATETIME_MYSQL );
	}
	if(isset($_POST['counselling']['counselling_gender'])){
		$clientObj->client_gender = $_POST['counselling']['counselling_gender'];
	}
	$clientObj->client_center = $_POST['counselling']['counselling_clinic'];
	$clientObj->client_lvd = storeDate($_POST['counselling']['counselling_entry_date']);
	$clientObj->client_lvd_form = 'counselling_info';
	if (($msg = $clientObj->store ())) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect (); // Store failed don't continue?
	} else {

		if (! empty ( $counsellinginfo ) && (count ( $counsellinginfo ) > 0)) {
			$counsellingInfoObject = new CCounsellingInfo ( );
			$counsellinginfo ['counselling_client_id'] = $clientObj->client_id;
			$counsellingInfoObject->bind ( $counsellinginfo );
			//var_dump($counsellingInfoObject);
			$date = new CDate ( $counsellinginfo ['counselling_entry_date'] );
			$counsellingInfoObject->counselling_entry_date = $date->format ( FMT_DATETIME_MYSQL );

			if((int)$_POST['old_clinic'] !== (int)$_POST['counselling']['counselling_clinic']){
				$sql='insert into status_client (social_client_id,social_client_status,social_entry_date,mode)
					values ("'.$clientObj->client_id.'","'.$counsellingInfoObject->counselling_clinic.'","'.$counsellingInfoObject->counselling_entry_date.'","center")';
				$res=mysql_query($sql);
			}

			//var_dump($counsellinginfo["counselling_dob"]);
			if (! empty ( $counsellinginfo ["counselling_dob"] ) && $dob) {
				//$dob = new CDate ( $counsellinginfo ["counselling_dob"] );
				$counsellingInfoObject->counselling_dob = $dob->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_admission_date"] )) {
				$admd = new CDate ( $counsellinginfo ["counselling_admission_date"] );
				$counsellingInfoObject->counselling_admission_date = $admd->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_child_nvp_date"] )) {
				$nvp_date = new CDate ( $counsellinginfo ["counselling_child_nvp_date"] );
				$counsellingInfoObject->counselling_child_nvp_date = $nvp_date->format ( FMT_DATETIME_MYSQL );
			}

			if (! empty ( $counsellinginfo ["counselling_mother_date_art"] )) {
				$mother_art_date = new CDate ( $counsellinginfo ["counselling_mother_date_art"] );
				$counsellingInfoObject->counselling_mother_date_art = $mother_art_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_mother_date_cd4"] )) {
				$mother_cd4_date = new CDate ( $counsellinginfo ["counselling_mother_date_cd4"] );
				$counsellingInfoObject->counselling_mother_date_cd4 = $mother_cd4_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_child_azt_date"] )) {
				$child_azt_date = new CDate ( $counsellinginfo ["counselling_child_azt_date"] );
				$counsellingInfoObject->counselling_child_azt_date = $child_azt_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_determine_date"] )) {
				$determine_date = new CDate ( $counsellinginfo ["counselling_determine_date"] );
				$counsellingInfoObject->counselling_determine_date = $determine_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_bioline_date"] )) {
				$bioline_date = new CDate ( $counsellinginfo ["counselling_bioline_date"] );
				$counsellingInfoObject->counselling_bioline_date = $bioline_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_unigold_date"] )) {
				$unigold_date = new CDate ( $counsellinginfo ["counselling_unigold_date"] );
				$counsellingInfoObject->counselling_unigold_date = $unigold_date->format ( FMT_DATETIME_MYSQL );
			}

			if (! empty ( $counsellinginfo ["counselling_elisa_date"] )) {
				$elisa_date = new CDate ( $counsellinginfo ["counselling_elisa_date"] );
				$counsellingInfoObject->counselling_elisa_date = $elisa_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_pcr1_date"] )) {
				$pcr1_date = new CDate ( $counsellinginfo ["counselling_pcr1_date"] );
				$counsellingInfoObject->counselling_pcr1_date = $pcr1_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_pcr2_date"] )) {
				$pcr2_date = new CDate ( $counsellinginfo ["counselling_pcr2_date"] );
				$counsellingInfoObject->counselling_pcr2_date = $pcr2_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_rapid12_date"] )) {
				$rapid12_date = new CDate ( $counsellinginfo ["counselling_rapid12_date"] );
				$counsellingInfoObject->counselling_rapid12_date = $rapid12_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_rapid18_date"] )) {
				$rapid18_date = new CDate ( $counsellinginfo ["counselling_rapid18_date"] );
				$counsellingInfoObject->counselling_rapid18_date = $rapid18_date->format ( FMT_DATETIME_MYSQL );
			}
			if (! empty ( $counsellinginfo ["counselling_other_date"] )) {
				$other_date = new CDate ( $counsellinginfo ["counselling_other_date"] );
				$counsellingInfoObject->counselling_other_date = $other_date->format ( FMT_DATETIME_MYSQL );
			}

			//var_dump($counsellingInfoObject);
			//exit;
			$counsellingInfoObject->store ();
		}

		//handle status update


		if (isset ( $post_save )) {
			foreach ( $post_save as $post_save_function ) {
				$post_save_function ();
			}
		}

		if ($notify) {
			if ($msg = $clientObj->notify ( $comment )) {
				$AppUI->setMsg ( $msg, UI_MSG_ERROR );
			}
		}
		$AppUI->setMsg ( ' added', UI_MSG_OK, true );
		$AppUI->redirect ( 'm=clients&a=view&tab=0&client_id=' . $clientObj->client_id );
	}
} // end of if subform
?>