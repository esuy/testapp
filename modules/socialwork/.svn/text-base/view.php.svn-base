<?php /* Social Info $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$social_id = intval( dPgetParam( $_GET, "social_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));


// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $social_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $social_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CSocialInfo();
$canDelete = $obj->canDelete( $msg, $social_id );

// load the record data
$q  = new DBQuery;
$q->addTable('social_info');
$q->addQuery('social_info.*');
$q->addWhere('social_info.social_id = '.$social_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Social Info' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

// load the list of project statii and company types
$pstatus = dPgetSysVal( 'ProjectStatus' );
$types = dPgetSysVal( 'CompanyType' );


// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($obj->social_client_id))
{
	$ttl = "View Social Info : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Social Info ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new entry').'" />', '',
		'<form action="?m=socialinfo&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=socialinfo&a=addedit&social_id=$social_id", "edit this entry" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete entry', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Social Info').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=socialinfo" method="post">
	<input type="hidden" name="dosql" value="do_socialinfo_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="social_id" value="<?php echo $social_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Details');?></strong>
		<table cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Reg. Date');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->social_entry_date;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Total Orphan?');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->social_total_orphan;?></td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Risk Level');?>:</td>
			<td class="hilite"><?php echo @$obj->social_risk_level;?></td>
		</tr>


	</td>
	<td width="50%" valign="top">
		<strong><?php echo $AppUI->_('Explanation of risk level');?></strong>
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
		<tr>
			<td class="hilite">
				<?php echo str_replace( chr(10), "<br />", $obj->social_notes);?>&nbsp;
			</td>
		</tr>
		
		</table>
		<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->social_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
</tr>
</table>


