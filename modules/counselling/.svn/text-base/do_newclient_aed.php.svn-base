<?php


require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('counselling'));
//print_r($_POST);


$del = isset($_POST['del']) ? $_POST['del'] : 0;

$sub_form = isset($_POST['sub_form']) ? $_POST['sub_form'] : 0;

//counselling info
$counsellinginfo = setItem("counselling");

//handle new clients
$contact_unique_update = setItem("insert_id");



//print_r($_POST);
$client_id  = setItem("client_id", 0);

$del = setItem("del", 0);

$AppUI->setMsg('Client');

$clientObj = new CClient();

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
	foreach (findTabModules('companies', 'addedit') as $mod) 
	{
		$fname = dPgetConfig('root_dir') . "/modules/$mod/client_dosql.addedit.php";
		dprint(__FILE__, __LINE__, 3, "checking for $fname");
		if (file_exists($fname))
			require_once $fname;
	}
	
	// If we have an array of pre_save functions, perform them in turn.
	if (isset($pre_save)) 
	{
		foreach ($pre_save as $pre_save_function)
			$pre_save_function();
	} 
	else 
	{
		dprint(__FILE__, __LINE__, 1, "No pre_save functions.");
	}
	
	/*if (($company_id) && ($company_id > 0))
		$companyObj->load($company_id);

	if ( isset($company)) 
	{
		$companyObj->bind($company);
	}*/
	
	if ($del) 
	{
		if (!$clientObj->load($client_id))
		{
			$AppUI->setMsg( $clientObj->getError(), UI_MSG_ERROR );
			$AppUI->redirect();

		}
		if (!$clientObj->canDelete( $msg ))
		{
			$AppUI->setMsg( $msg, UI_MSG_ERROR );
			$AppUI->redirect();
		}
		if (($msg = $clientObj->delete()))
		{
			$AppUI->setMsg( $msg, UI_MSG_ERROR );
			$AppUI->redirect();
		}
		else
		{
			$AppUI->setMsg( 'deleted', UI_MSG_ALERT, true );
			$AppUI->redirect( "index.php?m=clients" );
		}
	} 
	else 
	{	
		if (!$clientObj->bind( $client)) 
		{
			$AppUI->setMsg( $clientObj->getError(), UI_MSG_ERROR );
			$AppUI->redirect();
		}
		

		$date = new CDate( $clientObj->client_entry_date );
		$clientObj->client_entry_date = $date->format( FMT_DATETIME_MYSQL );
	
		if (($msg = $clientObj->store())) 
		{
			$AppUI->setMsg( $msg, UI_MSG_ERROR );
			$AppUI->redirect(); // Store failed don't continue?
		}
		else
		{
						
			if (!empty($counsellinginfo ) && (count($counsellinginfo) > 0))
			{
				$counsellingInfoObject = new CCounsellingIntake();
				$counsellinginfo['counselling_client_id'] = $clientObj->client_id;
				$date = new CDate( $counsellinginfo['counselling_entry_date'] );
				$counsellingInfoObject->counselling_entry_date = $date->format( FMT_DATETIME_MYSQL );
				$counsellingInfoObject->bind($counsellinginfo);
				$counsellingInfoObject->store();
			}

			//handle new clients
			if (!empty($contact_unique_update))
			{
				$sql = "UPDATE client_contacts SET client_contacts_client_id = $clientObj->client_id WHERE client_contacts_client_id = \"$contact_unique_update\"";
				//print_r($sql);
				db_exec($sql);
				
				//check for CRM
				/*if (empty($convert))
				{
					if (!empty($company['crm_contact']) && ($company['crm_contact'] > 0))
					{
						$sql = "INSERT INTO company_contacts (company_contacts_contact_id, company_contacts_company_id, company_contacts_contact_type) ";
						$sql .= " VALUES (" .$company['crm_contact'] . ", $companyObj->company_id, 13)";
						db_exec($sql);
					}
				}*/

			}
		
			//handle new clients
			/*
			if (!empty($contact_unique_update))
			{
				$sql = "UPDATE company_contacts SET company_contacts_company_id = $companyObj->company_id WHERE company_contacts_company_id = \"$contact_unique_update\"";
				//print_r($sql);
				db_exec($sql);
				
				//check for CRM
				if (empty($convert))
				{
					if (!empty($company['crm_contact']) && ($company['crm_contact'] > 0))
					{
						$sql = "INSERT INTO company_contacts (company_contacts_contact_id, company_contacts_company_id, company_contacts_contact_type) ";
						$sql .= " VALUES (" .$company['crm_contact'] . ", $companyObj->company_id, 13)";
						db_exec($sql);
					}
				}

			}*/




		}

		if (isset($post_save)) 
		{
			foreach ($post_save as $post_save_function) 
			{
				$post_save_function();
			}
		}

		if ($notify) 
		{
			if ($msg = $clientObj->notify($comment)) 
			{
				$AppUI->setMsg( $msg, UI_MSG_ERROR );
			}
		}
		
		$AppUI->setMsg(' added', UI_MSG_OK, true);	
		$AppUI->redirect($clientObj->getUrl());
	}
//} // end of if subform
?>
