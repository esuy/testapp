<?php 
$social_id = intval( dPgetParam( $_GET, "social_id", 0 ) );
require_once ($AppUI->getModuleClass('clients'));


// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($social_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $social_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );

// load the record data
$q  = new DBQuery;
$q->addTable('social_info');
$q->addQuery('social_info.*');
$q->addWhere('social_info.social_id = '.$social_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CSocialInfo();
if (!db_loadObject( $sql, $obj ) && $social_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// collect all the users for the company owner list
$q  = new DBQuery;
$q->addTable('users','u');
$q->addTable('contacts','con');
$q->addQuery('user_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$q->addWhere('u.user_contact = con.contact_id');
$owners = $q->loadHashList();


$boolTypes = dPgetSysVal('YesNo');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

//load all sales reps
$q  = new DBQuery;
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 13');
$q->addOrder('c.contact_first_name');

//load contacts
$chw_contacts = arrayMerge(array(0=> '-Select CHW -'),$q->loadHashList());
$q->clear();
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 14');
$q->addOrder('c.contact_first_name');

$shw_contacts = arrayMerge(array(0=> '-Select SHW -'),$q->loadHashList());

// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($obj->social_client_id))
{
	$ttl = $social_id > 0 ? "Edit Social Info : " . $clientObj->getFullName() : "New Social Info : " . $clientObj->getFullName();

}
else
{
   $ttl = $social_id > 0 ? "Edit Social Info  " : "New Social Info ";

}

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($social_id != 0)
  $titleBlock->addCrumb( "?m=socialinfo&a=view&social_id=$social_id", "View" );
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changesocial;
	if (form.company_name.value.length < 3) {
		alert( "<?php echo $AppUI->_('companyValidName', UI_OUTPUT_JS);?>" );
		form.company_name.focus();
	} else {
		form.submit();
	}
}

function testURL( x ) {
	var test = "document.changeclient.company_primary_url.value";
	test = eval(test);
	if (test.length > 6) {
		newwin = window.open( "http://" + test, 'newwin', '' );
	}
}
</script>

<form name="changesocial" action="?m=socialinfo" method="post">
	<input type="hidden" name="dosql" value="do_socialinfo_aed" />
	<input type="hidden" name="social_id" value="<?php echo $social_id;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
	  <tr>
         <td align="left" nowrap><?php echo $AppUI->_('Is the child a total orphan');?>:</td>
		 <td align="right">
	    &nbsp;&nbsp;<input type="text" class="text" name="social_total_orphan" value="<?php echo dPformsafe(@$row->social_total_orphan);?>" maxlength="150" size="20" />
		 </td>
      </tr>
		  <tr>
			<td align="left" width="100"><?php echo $AppUI->_('Clinic');?>:</td>
			<td nowrap align="right">
				<input type="text" class="text" name="client_clinic_name" value="<?php 
					echo $clinic_detail['clinic_name'];
					?>" maxlength="100" size="25" />
				<input type="button" class="button" value="<?php echo $AppUI->_('select clinic...');?>..." onclick="popClinic()" />
				<input type='hidden' name='client_clinic' value="<?php echo $clinic_detail['clinic_id']; ?>">
				</td>
		</tr> 
        <tr>
			<td align="left" nowrap><?php echo $AppUI->_('CHW Rep.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $chw_contacts, 'chw_contact', 'size="1" class="text"',@$obj->chw_contact ); ?>
 			</td>
		</tr>
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('SHW Rep.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $shw_contacts, 'shw_contact', 'size="1" class="text"', @$obj->shw_contact ); ?>
 			</td>
		</tr>	  
      <tr>
         <td align="left" nowrap><?php echo $AppUI->_('School Education');?>:</td>
		 <td align="right">
		 <?php
            if ($AppUI->isActiveModule('contacts') && $perms->checkModule('contacts', 'view')) 
		{
			echo "<input type='button' class='button' value='".$AppUI->_("enter education info...")."' onclick='javascript:popFWContacts(selected_fw_contacts_id);' />";
		}?>
		 </td>
	  </tr>
</table>


</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->social_id, "edit" );
 			$custom_fields->printHTML();
		?>		
	</td>
</tr>
<tr>
<td width="50%">
   <table border="0" cellpadding="1" cellspacing="1" class="details" width="100%">
    <tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Social Worker Assessment'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Risk Level');?>:</td>
			<td align="left" valign="top">
			<?php echo arraySelect( $riskLevels, 'social_risk_level', 'size="1" class="text"', dPformSafe(@$obj->social_risk_level) ); ?>
 			</td>
	  </tr>		
      <tr>
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Explanation of risk level assessment');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="social_risk_level_description"><?php echo dPformSafe(@$obj->social_risk_level_description);?></textarea>
		</td>

      </tr>
	</table>
 </td>
</tr>

<tr>
	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>

</table>
</form>
