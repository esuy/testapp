<?php 
$caregiver_id = intval( dPgetParam( $_GET, "caregiver_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );

// check permissions for this caregiver
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($caregiver_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $caregiver_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the status
$status = dPgetSysVal( 'CaregiverStatus' );

// load the status
$boolTypes = dPgetSysVal( 'YesNo' );

// load the physical condition
$conditions = dPgetSysVal( 'CaregiverCondition' );

// load the physical condition
$employmentTypes = dPgetSysVal( 'EmploymentType' );

// load the caregiver types
$caregiverTypes = dPgetSysVal( 'CaregiverType' );



// load the record data
$q  = new DBQuery;
$q->addTable('caregivers');
$q->addQuery('caregivers.*');
$q->addQuery('con.contact_first_name');
$q->addQuery('con.contact_last_name');
$q->addJoin('contacts', 'con', 'caregivers.caregiver_contact = con.contact_id');
$q->addWhere('caregivers.caregiver_id = '.$caregiver_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CCaregiver();
if (!db_loadObject( $sql, $obj ) && $caregiver_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// collect all the users for the company owner list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();

// setup the title block
$ttl = $caregiver_id > 0 ? "Edit Caregiver" : "New Caregiver";
$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=contacts", "staff list" );
if ($caregiver_id != 0)
  $titleBlock->addCrumb( "?m=caregivers&a=view&caregiver_id=$caregiver_id", "view this caregiver" );
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changecaregiver;
	if (form.contact_last_name.value.length < 3) {
		alert( "<?php echo $AppUI->_('companyValidName', UI_OUTPUT_JS);?>" );
		form.contact_last_name.focus();
	} else {
		form.submit();
	}
}



</script>

<form name="changecaregiver" action="?m=caregivers" method="post">
	<input type="hidden" name="dosql" value="do_caregiver_aed" />
	<input type="hidden" name="caregiver_id" value="<?php echo $caregiver_id;?>" />
	<input type="hidden" name="contact_id" value="<?php echo $contact_id;?>" />
	<input type="hidden" name="client_id" value="<?php echo $client_id;?>" />
	<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
	<tr>
		<td align="right"><?php echo $AppUI->_('First Name');?>:</td>
		<td>
			<input type="text" class="text" name="contact_first_name" value="<?php echo dPformSafe(@$obj->contact_first_name);?>" size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
		</td>
	</tr>	
	<tr>
		<td align="right"><?php echo $AppUI->_('Other Name(s)');?>:</td>
		<td>
			<input type="text" class="text" name="contact_other_names" value="<?php echo dPformSafe(@$obj->contact_other_names);?>" size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
		</td>
	</tr>	
	<tr>
		<td align="right"><?php echo $AppUI->_('Last Name');?>:</td>
		<td>
			<input type="text" class="text" name="contact_last_name" value="<?php echo dPformSafe(@$obj->contact_last_name);?>" size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Year of Birth');?>:</td>
		<td>
			<input type="text" class="text" name="caregiver_yob" value="<?php echo dPformSafe(@$obj->caregiver_yob);?>" size="30" maxlength="255" />
		</td>
	</tr>		
	<tr>
		<td align="right"><?php echo $AppUI->_('ID No');?>:</td>
		<td>
			<input type="text" class="text" name="caregiver_idno" value="<?php echo dPformSafe(@$obj->caregiver_idno);?>" size="30" maxlength="255" />
		</td>
	</tr>	
	<tr>
		<td align="right"><?php echo $AppUI->_('Email');?>:</td>
		<td>
			<input type="text" class="text" name="contact_email" value="<?php echo dPformSafe(@$obj->contact_email);?>" size="30" maxlength="255" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Phone');?>:</td>
		<td>
			<input type="text" class="text" name="contact_phone1" value="<?php echo dPformSafe(@$obj->contact_phone1);?>" maxlength="30" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Phone');?>2:</td>
		<td>
			<input type="text" class="text" name="contact_phone2" value="<?php echo dPformSafe(@$obj->contact_phone2);?>" maxlength="50" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Fax');?>:</td>
		<td>
			<input type="text" class="text" name="contact_fax" value="<?php echo dPformSafe(@$obj->contact_fax);?>" maxlength="30" />
		</td>
	</tr>
	<tr>
		<td colspan=2 align="left">
			<?php echo $AppUI->_('Address');?><br />
			<hr width="500" align="left" size=1 />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Address');?>1:</td>
		<td><input type="text" class="text" name="contact_address1" value="<?php echo dPformSafe(@$obj->contact_address1);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Address');?>2:</td>
		<td><input type="text" class="text" name="contact_address2" value="<?php echo dPformSafe(@$obj->contact_address2);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('City');?>:</td>
		<td><input type="text" class="text" name="contact_city" value="<?php echo dPformSafe(@$obj->contact_city);?>" size=50 maxlength="50" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('State');?>:</td>
		<td><input type="text" class="text" name="contact_state" value="<?php echo dPformSafe(@$obj->contact_state);?>" maxlength="50" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Zip');?>:</td>
		<td><input type="text" class="text" name="contact_zip" value="<?php echo dPformSafe(@$obj->contact_zip);?>" maxlength="15" /></td>
	</tr>
    <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Caregiver Status');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($status, "caregiver_status", 'onclick=toggleButtons()', $obj->caregiver_status? $obj->caregiver_status :1); ?>
        </td>
	 </tr>	
    <tr>
	  <td>
	    <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Highest education level achieved');?>:</td>
			<td><input type="text" class="text" name="contact_zip" value="<?php echo dPformSafe(@$obj->caregiver_education);?>" maxlength="150" size="50" /></td>
		</tr>
	   </td>
	 </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Raising Child?');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "caregiver_raising_child", 'onclick=toggleButtons()', $obj->caregiver_raising_child? $obj->caregiver_raising_child : -1); ?>
        </td>
	 </tr>		 
	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Employment type');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelect($employmentTypes, "caregiver_employment", 'class="text"', $obj->caregiver_employment? $obj->caregiver_employment : -1); ?>
        </td>

	</tr>	
<?php
	if ($client_id > 0)
	{
?>	
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Caregiver type');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelect($caregiverTypes, "caregiver_client_caregiver_type", 'class="text"', $obj->caregiver_client_caregiver_type); ?>
        </td>

	</tr>	
<?php
	}
?>
	<tr>	
		<td align="left" valign=top><?php echo $AppUI->_('Description');?>:</td>
		<td valign="top">
					<textarea cols="30" rows="5" class="textarea" name="caregiver_notes"><?php echo @$obj->caregiver_notes;?></textarea>
		</td>
	</tr>		
</table>


</td>
 
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->contact_id, "edit" );
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
