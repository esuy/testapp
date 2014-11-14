<?php /* CAREGIVER $Id: do_caregiver_aed.php,v 1.13.10.1 2005/08/10 07:38:41 ajdonnison Exp $ */
include $AppUI->getModuleClass('contacts');
$del = isset($_REQUEST['del']) ? $_REQUEST['del'] : FALSE;

$obj = new CCaregiver();


if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}
require_once("./classes/CustomFields.class.php");        

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg( 'Caregiver ' );

// !Caregivers's contact information not deleted - left for history.
if ($del) {
	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
		$AppUI->setMsg( "deleted", UI_MSG_ALERT, true );
		$AppUI->redirect();
	}
	return;
}
	if (!empty($_POST["caregiver_entry_date"]))
	{
			$entry_date = new CDate( $_POST["caregiver_entry_date"] );
					//var_dump($dob);
			$obj->caregiver_entry_date = $entry_date->format( FMT_DATETIME_MYSQL );
	}	
    if (($msg = $obj->store())) 
	{
        $AppUI->setMsg( $msg, UI_MSG_ERROR );
    }
    else
    {
		$custom_fields = New CustomFields( $m, 'addedit', $obj->caregiver_id, "edit" );
 		$custom_fields->bind( $_POST );
 		$sql = $custom_fields->store( $obj->caregiver_id ); // Store Custom Fields
		$AppUI->setMsg( @$_POST['caregiver_id'] ? 'updated' : 'added', UI_MSG_OK, true );

			

		}
	}
	//$AppUI->redirect();
	$AppUI->redirect('m=clients&a=view&client_id='.$obj->caregiver_client_id);

?>
