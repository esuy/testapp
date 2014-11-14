<?php /* LOCATIONS $Id: view.php,v 1.14.4.3 2005/11/26 02:11:35 cyberhorse Exp $ */
$clinic_location_id = intval( dPgetParam( $_GET, 'clinic_location_id', 0 ) );
$clinic_id = intval( dPgetParam( $_GET, 'clinic_id', 0 ) );
$AppUI->savePlace();

require_once ($AppUI->getModuleClass('clinics'));
// check permissions for this record
//$canEdit = !getDenyEdit( $m, $clinic_location_id );
//if (!$canEdit) {
//	$AppUI->redirect( "m=public&a=access_denied" );
//}

// load the record data
$msg = '';
$row = new CClinicLocation();
$canDelete = $row->canDelete( $msg, $clinic_location_id );

$canEdit = $perms->checkModuleItem($m, "edit", $clinic_location_id);

if (!$row->load( $clinic_location_id ) && $clinic_location_id > 0) {
	$AppUI->setMsg( 'Location' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} 
//load center
if ($clinic_location_id)
{
	$q  = new DBQuery;
	$q->addTable('clinics');
	$q->addQuery('clinics.*');
	$q->addWhere('clinics.clinic_id = '.$row->clinic_location_clinic_id);
	$sql = $q->prepare();
	//var_dump($sql);
	$q->clear();
	$clinicObj = new CClinic();	
	db_loadObject( $sql, $clinicObj); 
}
// setup the title block
$ttl = $clinic_location_id ? $clinicObj->clinic_name . "::" . "View Location" : "View Location";
$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clinics", "Centers" );

if ($clinic_id)
	$titleBlock->addCrumb( "?m=clinics&a=view&clinic_id=$clinic_id", "view $clinicObj->clinic_name" );
	
if ($canEdit && $clinic_location_id)
        $titleBlock->addCrumb( "?m=locations&a=addedit&clinic_location_id=$clinic_location_id", 'Edit' );
	/*$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new project').'" />', '',
		'<form action="?m=projects&a=addedit&company_id='.$row->contact_company.'&clinic_location_id='.$clinic_location_id.'" method="post">', '</form>'
	);*/
if ($canDelete && $clinic_location_id) {
	$titleBlock->addCrumbDelete( 'delete location', $canDelete, $msg );
}
$titleBlock->show();
?>
<form name="changelocation" action="?m=locations" method="post">
        <input type="hidden" name="dosql" value="do_location_aed" />
        <input type="hidden" name="del" value="0" />
        <input type="hidden" name="clinic_location_id" value="<?php echo $clinic_location_id;?>" />
        <input type="hidden" name="clinic_id" value="<?php echo $clinic_id;?>" />
</form>
<script language="JavaScript">
function delIt(){
        var form = document.changelocation;
        if(confirm( "<?php echo $AppUI->_('Are you sure you want to delete this location', UI_OUTPUT_JS);?>" )) {
                form.del.value = "<?php echo $clinic_location_id;?>";
                form.submit();
        }
}
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">
<tr>
	<td colspan="2">
		<table border="0" cellpadding="1" cellspacing="1">
		<tr>
			<td align="right"><?php echo $AppUI->_('Location');?>:</td>
			<td class="hilite"><?php echo @$row->clinic_location;?></td>
		</tr>
		<tr>
			<td align="right" width="100"><?php echo $AppUI->_('Center Name');?>: </td>
			<td class="hilite"><?php echo @$clinicObj->clinic_name;?></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Notes');?></strong><br />
		<?php echo @nl2br($row->clinic_location_notes);?>
	</td>
</tr>
<tr>
	<td>
		<input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:window.location='./index.php?m=clinics';" />
	</td>
</tr>
</form>
</table>
