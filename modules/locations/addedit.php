<?php 
$clinic_location_id = intval( dPgetParam( $_GET, "clinic_location_id", 0 ) );
$clinic_id = intval( dPgetParam( $_GET, "clinic_id", 0 ) );

require_once($AppUI->getModuleClass('clinics'));

// check permissions for this location
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($clinic_location_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $clinic_location_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


// load the record data
$q  = new DBQuery;
$q->addTable('clinic_location');
$q->addQuery('clinic_location.*');
$q->addWhere('clinic_location.clinic_location_id = '.$clinic_location_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CClinicLocation();
if (!db_loadObject( $sql, $obj ) && $clinic_location_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// load the clinic data
$q  = new DBQuery;
$q->addTable('clinics');
$q->addQuery('clinics.*');
$q->addWhere('clinics.clinic_id = '.$clinic_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$clinicObj = new CClinic();
if (!db_loadObject( $sql, $clinicObj ) && $clinic_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// setup the title block
$ttl = $clinic_location_id > 0 ? "Edit Center Location :: ". $clinicObj->clinic_name : "New Center Location ::" . $clinicObj->clinic_name;
$titleBlock = new CTitleBlock( $ttl, NULL, $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clinics", "center list" );
if ($clinic_location_id != 0)
  $titleBlock->addCrumb( "?m=clinics&a=view&clinic_id=$clinic_id", "view this center" );
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changelocation;
	if (form.clinic_location.value.length < 3) {
		alert( "<?php echo $AppUI->_('Invalid Location Name', UI_OUTPUT_JS);?>" );
		form.clinic_location.focus();
	} else {
		form.submit();
	}
}
</script>

<form name="changelocation" action="?m=locations" method="post">
	<input type="hidden" name="dosql" value="do_location_aed" />
	<input type="hidden" name="clinic_location_id" value="<?php echo $clinic_location_id;?>" />
	<input type="hidden" name="clinic_location_clinic_id" value="<?php echo $clinic_id;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
	<tr>
		<td align="right"><?php echo $AppUI->_('Location');?>:</td>
		<td>
			<input type="text" class="text" name="clinic_location" value="<?php echo dPformSafe(@$obj->clinic_location);?>" size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
		</td>
	</tr>
	<tr>
		<td align="right" valign=top><?php echo $AppUI->_('Description');?>:</td>
		<td align="left">
			<textarea cols="70" rows="10" class="textarea" name="clinic_location_notes"><?php echo @$obj->clinic_location_notes;?></textarea>
		</td>
	</tr>
</table>


</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->clinic_location_id, "edit" );
 			$custom_fields->printHTML();
		?>		
	</td>
</tr>
<tr>
	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>
</table>
</form>
