<?php 
$clinic_id = intval( dPgetParam( $_GET, "clinic_id", 0 ) );

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $clinic_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $clinic_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// retrieve any state parameters
if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'ClinicVwTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'ClinicVwTab' ) !== NULL ? $AppUI->getState( 'ClinicVwTab' ) : 0;

// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CClinic();
$canDelete = $obj->canDelete( $msg, $clinic_id );

// load the record data
$q  = new DBQuery;
$q->addTable('clinics');
$q->addQuery('clinics.*');
$q->addWhere('clinics.clinic_id = '.$clinic_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Center' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

// load the list of project statii and company types
$pstatus = dPgetSysVal( 'ProjectStatus' );
$types = dPgetSysVal( 'ClinicType' );

// setup the title block
$titleBlock = new CTitleBlock( 'View Center', '', $m, "$m.$a" );
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new center').'" />', '',
		'<form action="?m=clinics&a=addedit" method="post">', '</form>'
	);
}
$titleBlock->addCrumb( "?m=clinics", "Centers" );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=clinics&a=addedit&clinic_id=$clinic_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete center', $canDelete, $msg );
	}
}
$titleBlock->show();
?>
<script language="javascript">
<?php
// security improvement:
// some javascript functions may not appear on client side in case of user not having write permissions
// else users would be able to arbitrarily run 'bad' functions
if ($canDelete) {
?>
function delIt() {
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Center').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=clinics" method="post">
	<input type="hidden" name="dosql" value="do_clinic_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="clinic_id" value="<?php echo $clinic_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Details');?></strong>
		<table cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Center');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->clinic_name;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Email');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->clinic_email;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Phone');?>:</td>
			<td class="hilite"><?php echo @$obj->clinic_phone1;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Phone');?>2:</td>
			<td class="hilite"><?php echo @$obj->clinic_phone2;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Fax');?>:</td>
			<td class="hilite"><?php echo @$obj->clinic_fax;?></td>
		</tr>
		<tr valign=top>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Address');?>:</td>
			<td class="hilite"><?php
						echo @$obj->clinic_address1
							.( ($obj->clinic_address2) ? '<br />'.$obj->clinic_address2 : '' )
							.( ($obj->clinic_city) ? '<br />'.$obj->clinic_city : '' )
							.( ($obj->clinic_state) ? '<br />'.$obj->clinic_state : '' )
							.( ($obj->clinic_zip) ? '<br />'.$obj->clinic_zip : '' )
							;
			?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('URL');?>:</td>
			<td class="hilite">
				<a href="http://<?php echo @$obj->clinic_primary_url;?>" target="Company"><?php echo @$obj->clinic_primary_url;?></a>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Type');?>:</td>
			<td class="hilite"><?php echo $AppUI->_($types[@$obj->clinic_type]);?></td>
		</tr>
		</table>

	</td>
	<td width="50%" valign="top">
		<strong><?php echo $AppUI->_('Description');?></strong>
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
		<tr>
			<td class="hilite">
				<?php echo wordwrap(str_replace( chr(10), "<br />", $obj->clinic_description), 75,"<br />", true);?>&nbsp;
			</td>
		</tr>
		
		</table>
		<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->clinic_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
</tr>
</table>

<?php
// tabbed information boxes
$moddir = $dPconfig['root_dir'] . '/modules/clinics/';
$tabBox = new CTabBox( "?m=clinics&a=view&clinic_id=$clinic_id", "", $tab );
$tabBox->add( $moddir . 'vw_locations', 'Center Locations' );
$tabBox->loadExtras($m);
$tabBox->show();
?>
