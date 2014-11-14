<?php 
$clinic_id = intval( dPgetParam( $_GET, "clinic_id", 0 ) );

// check permissions for this clinic
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($clinic_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $clinic_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'ClinicType' );

// load the record data
$q  = new DBQuery;
$q->addTable('clinics');
$q->addQuery('clinics.*');
$q->addQuery('con.contact_first_name');
$q->addQuery('con.contact_last_name');
$q->addJoin('users', 'u', 'u.user_id = clinics.clinic_owner');
$q->addJoin('contacts', 'con', 'u.user_contact = con.contact_id');
$q->addWhere('clinics.clinic_id = '.$clinic_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CClinic();
if (!db_loadObject( $sql, $obj ) && $clinic_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


// setup the title block
$ttl = $clinic_id > 0 ? "Edit Center" : "New Center";
$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clinics", "Centers" );
if ($clinic_id != 0)
  $titleBlock->addCrumb( "?m=clinics&a=view&clinic_id=$clinic_id", "View" );
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeclient;
	if (form.clinic_name.value.length < 3) {
		alert( "<?php echo $AppUI->_('clinicValidName', UI_OUTPUT_JS);?>" );
		form.clinic_name.focus();
	} else {
		form.submit();
	}
}

function testURL( x ) {
	var test = "document.changeclient.clinic_primary_url.value";
	test = eval(test);
	if (test.length > 6) {
		newwin = window.open( "http://" + test, 'newwin', '' );
	}
}
</script>

<form name="changeclient" action="?m=clinics" method="post">
	<input type="hidden" name="dosql" value="do_clinic_aed" />
	<input type="hidden" name="clinic_id" value="<?php echo $clinic_id;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
	<tr>
		<td align="right"><?php echo $AppUI->_('Center Name');?>:</td>
		<td>
			<input type="text" class="text" name="clinic_name" value="<?php echo dPformSafe(@$obj->clinic_name);?>" size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Email');?>:</td>
		<td>
			<input type="text" class="text" name="clinic_email" value="<?php echo dPformSafe(@$obj->clinic_email);?>" size="30" maxlength="255" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Phone');?>:</td>
		<td>
			<input type="text" class="text" name="clinic_phone1" value="<?php echo dPformSafe(@$obj->clinic_phone1);?>" maxlength="30" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Phone');?>2:</td>
		<td>
			<input type="text" class="text" name="clinic_phone2" value="<?php echo dPformSafe(@$obj->clinic_phone2);?>" maxlength="50" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Fax');?>:</td>
		<td>
			<input type="text" class="text" name="clinic_fax" value="<?php echo dPformSafe(@$obj->clinic_fax);?>" maxlength="30" />
		</td>
	</tr>
	<tr>
		<td colspan=2 align="center">
			<img src="images/shim.gif" width="50" height="1" /><?php echo $AppUI->_('Address');?><br />
			<hr width="500" align="center" size=1 />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Address');?>1:</td>
		<td><input type="text" class="text" name="clinic_address1" value="<?php echo dPformSafe(@$obj->clinic_address1);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Address');?>2:</td>
		<td><input type="text" class="text" name="clinic_address2" value="<?php echo dPformSafe(@$obj->clinic_address2);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('City');?>:</td>
		<td><input type="text" class="text" name="clinic_city" value="<?php echo dPformSafe(@$obj->clinic_city);?>" size=50 maxlength="50" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('State');?>:</td>
		<td><input type="text" class="text" name="clinic_state" value="<?php echo dPformSafe(@$obj->clinic_state);?>" maxlength="50" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Zip');?>:</td>
		<td><input type="text" class="text" name="clinic_zip" value="<?php echo dPformSafe(@$obj->clinic_zip);?>" maxlength="15" /></td>
	</tr>
	<tr>
		<td align="right">
			URL http://<A name="x"></a></td><td><input type="text" class="text" value="<?php echo dPformSafe(@$obj->clinic_primary_url);?>" name="clinic_primary_url" size="50" maxlength="255" />
			<a href="#x" onClick="testURL('CompanyURLOne')">[<?php echo $AppUI->_('test');?>]</a>
		</td>
	</tr>
	
	<tr>
		<td align="right"><?php echo $AppUI->_('Center Owner');?>:</td>
		<td>
	<?php
		echo arraySelect( $owners, 'clinic_owner', 'size="1" class="text"', @$obj->clinic_owner ? $obj->clinic_owner : $AppUI->user_id );
	?>
		</td>
	</tr>
	
	<tr>
		<td align="right"><?php echo $AppUI->_('Type');?>:</td>
		<td>
	<?php
		echo arraySelect( $types, 'clinic_type', 'size="1" class="text"', @$obj->clinic_type, true );
	?>
		</td>
	</tr>
	
	<tr>
		<td align="right" valign=top><?php echo $AppUI->_('Description');?>:</td>
		<td align="left">
			<textarea cols="70" rows="10" class="textarea" name="clinic_description"><?php echo @$obj->clinic_description;?></textarea>
		</td>
	</tr>
</table>


</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->clinic_id, "edit" );
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
