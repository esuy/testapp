<?php 
$del = dPgetParam( $_POST, 'del', 0 );
$obj = new CClinic();
$msg = '';

if (!$obj->bind( $_POST )) 
{

	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

require_once("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg( 'Clinic' );
if ($del) 
{
	if (!$obj->canDelete( $msg )) 
	{
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	if (($msg = $obj->delete())) 
	{
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} 
	else 
	{
		$AppUI->setMsg( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect( 'm=clinics' );
	}
} 
else 
{
	if (($msg = $obj->store())) 
	{

		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} 
	else 
	{
 		$custom_fields = New CustomFields( $m, 'addedit', $obj->clinic_id, "edit" );
 		$custom_fields->bind( $_POST );
 		$sql = $custom_fields->store( $obj->clinic_id ); // Store Custom Fields
		$AppUI->setMsg( @$_POST['clinic_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}

	$AppUI->redirect('m=clinics&a=view&clinic_id='.$obj->clinic_id);
}
?>
