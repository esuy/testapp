<?php /* COMPANIES $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$caregiver_id = intval( dPgetParam( $_GET, "caregiver_id", 0 ) );

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $caregiver_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $caregiver_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// retrieve any state parameters
if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'CaregiverVwTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'CaregiverVwTab' ) !== NULL ? $AppUI->getState( 'CaregiverVwTab' ) : 0;

// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCaregiver();
$canDelete = $obj->canDelete( $msg, $caregiver_id );

// load the record data
$q  = new DBQuery;
$q->addTable('caregivers');
$q->addQuery('caregivers.*');
$q->addQuery('con.contact_first_name');
$q->addQuery('con.contact_last_name');
$q->addJoin('contacts', 'con', 'caregivers.caregiver_contact = con.contact_id');
$q->addWhere('caregivers.caregiver_id = '.$caregiver_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Caregiver' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$types = dPgetSysVal( 'CaregiverType' );

// setup the title block
$titleBlock = new CTitleBlock( 'View Caregiver', '', $m, "$m.$a" );
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new caregiver').'" />', '',
		'<form action="?m=caregivers&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=contacts", "contacts list" );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=caregivers&a=addedit&caregiver_id=$caregiver_id", "edit this caregiver" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete caregiver', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Caregiver').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=caregivers" method="post">
	<input type="hidden" name="dosql" value="do_caregiver_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="caregiver_id" value="<?php echo $caregiver_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Details');?></strong>
		<table cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Organisation');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->company_name;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Email');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->company_email;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Phone');?>:</td>
			<td class="hilite"><?php echo @$obj->company_phone1;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Phone');?>2:</td>
			<td class="hilite"><?php echo @$obj->company_phone2;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Fax');?>:</td>
			<td class="hilite"><?php echo @$obj->company_fax;?></td>
		</tr>
		<tr valign=top>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Address');?>:</td>
			<td class="hilite"><?php
						echo @$obj->company_address1
							.( ($obj->company_address2) ? '<br />'.$obj->company_address2 : '' )
							.( ($obj->company_city) ? '<br />'.$obj->company_city : '' )
							.( ($obj->company_state) ? '<br />'.$obj->company_state : '' )
							.( ($obj->company_zip) ? '<br />'.$obj->company_zip : '' )
							;
			?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('URL');?>:</td>
			<td class="hilite">
				<a href="http://<?php echo @$obj->company_primary_url;?>" target="Company"><?php echo @$obj->company_primary_url;?></a>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Type');?>:</td>
			<td class="hilite"><?php echo $AppUI->_($types[@$obj->company_type]);?></td>
		</tr>
		</table>

	</td>
	<td width="50%" valign="top">
		<strong><?php echo $AppUI->_('Description');?></strong>
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
		<tr>
			<td class="hilite">
				<?php echo str_replace( chr(10), "<br />", $obj->company_description);?>&nbsp;
			</td>
		</tr>
		
		</table>
		<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->company_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
</tr>
</table>

<?php
// tabbed information boxes
$moddir = $dPconfig['root_dir'] . '/modules/caregivers/';
$tabBox = new CTabBox( "?m=caregivers&a=view&caregiver_id=$caregiver_id", "", $tab );
$tabBox->add( $moddir . 'vw_clients', 'Clients linked to this caregiver' );
$tabBox->loadExtras($m);
$tabBox->show();

?>
