<?php /* Social Info $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('caregivers'));


// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $counselling_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $counselling_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCounsellingWork();

$counsellorObj = new CContact();

$canDelete = $obj->canDelete( $msg, $counselling_id );

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_work');
$q->addQuery('counselling_work.*');
$q->addWhere('counselling_work.counselling_id = '.$counselling_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Counselling Services Log' );
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
if ($clientObj->load($obj->counselling_client_id))
{
	$ttl = "View Counselling Services Log Entry : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Counselling Services Log Entry ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new entry').'" />', '',
		'<form action="?m=counsellingwork&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=counsellingwork", "counselling services log" );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=counsellingwork&a=addedit&counselling_id=$counselling_id", "edit this entry" );
	
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Counselling Services Log Entry').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=counsellingwork" method="post">
	<input type="hidden" name="dosql" value="do_counsellingwork_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Details');?></strong>
		<table cellspacing="1" cellpadding="2" width="100%">
		<tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_("Counsellor's Name");?>:</td>
			<td class="hilite" width="100%"><?php echo $counsellorObj->getFullName();?></td>
		</tr>
		<tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Staff Code');?>:</td>
			<td class="hilite" width="100%"><?php echo $counsellorObj->counsellor_code;?></td>
		</tr>
		<tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Visit Date');?>:</td>
			<td class="hilite" width="100%"><?php if (isset ($visit_date)) echo $visit_date->format($df);?>&nbsp;</td>
		</tr>
		<tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Provider');?>:</td>
			<td class="hilite" width="100%"><?php echo $obj->counselling_provider_type;?></td>
		</tr>
		<tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Admission No');?>:</td>
			<td class="hilite"><?php echo @$clientObj->client_code;?></td>
		</tr>
		</table>

	</td>
	<td valign="top" width="50%">
		
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Support Couns.');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_support_counselling);?> </td>
		</tr>		
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Child Couns.');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_child_counselling);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Ind. Prevent. Educ.');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_ind_prev_educ);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Adherence Couns.');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_adherence_counselling);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Ind. Disclose Couns.');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_ind_disc_counselling);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Lifeskiss Training');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_lifeskiss_training);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Recreational Therapy');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_rec_therapy);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Hospital Visit');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_hospital_visit);?> </td>
		</tr>
		<tr>
			<td nowrap = "nowrap"><?php echo $AppUI->_('Home Visit');?>:</td>
			<td class="hilite"><?php echo getBoolDesc($obj->counselling_home_visit);?> </td>
		</tr>

		<tr>
			<td nowrap = "nowrap"><strong><?php echo $AppUI->_('Log entry notes');?></strong></td>		
			<td class="hilite">
				<?php echo str_replace( chr(10), "<br />", $obj->counselling_notes);?>&nbsp;
			</td>
		</tr>
		
		</table>
		<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
</tr>
</table>


