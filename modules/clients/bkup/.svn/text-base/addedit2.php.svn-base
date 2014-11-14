<?php
$company_id = intval( dPgetParam( $_GET, "company_id", 0 ));
$company_type = intval( dPgetParam( $_GET, "company_type", 0 ));

$perms = & $AppUI->acl();

if ($company_id)
  $canEdit = $perms->checkModuleItem( $m, "edit", $company_id );
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit)
{
  $AppUI->redirect("m=public&a=access_denied");
}

$types = dPgetSysVal('CompanyType');

$q  = new DBQuery;
$q->addTable('companies');
$q->addQuery('companies.*');
$q->addWhere('companies.company_id = '.$company_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject($sql, $obj) && ($company_id > 0))
{
  	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}
//load users for company contact list
$q  = new DBQuery;
//$q->addTable('users','u');
$q->addTable('contacts','con');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
//$q->addWhere('u.user_contact = con.contact_id');
$owners = $q->loadHashList();
//print_r($owners);

$ttl = $company_id > 0 ? "Edit Company" : "Add Company";
$titleBlock = new CTitleBlock( $ttl, 'handshake.png', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=companies", "client list" );
if ($company_id != 0)
  $titleBlock->addCrumb( "?m=companies&a=view&company_id=$company_id", "view this company" );
$titleBlock->show();

?>
<script language="javascript">
var selected_contacts_id = "<?php echo $obj->main_contacts; ?>";
var company_id = '<?php echo $obj->company_id;?>';
var company_name_msg = "<?php echo $AppUI->_('Please enter a name for the company');?>";
/*function submitIt()
{
  var form=document.editFrm;

  if (form.company_name.value.length < 3)
  {
    alert("<?php echo $AppUI->_('companyValidName', UI_OUTPUT_JS);?>");
    form.company_name.focus;
  }
  else
  {
      form.submit(); 
  }
}*/
function testURL( x )
{
	var test = "document.editFrm.company_primary_url.value";
	test = eval(test);
	if (test.length > 6)
    {
		newwin = window.open( "http://" + test, 'newwin', '' );
	}
}
</script>
 <form name="editFrm" action="?m=companies&company_id=<?php echo $company_id; ?>" method="post">
   <input type="hidden" name="dosql" value="do_newcompany_aed" />
   <input type="hidden" name="insert_id" value="<?php echo uniqid(""); ?>" />
   <input type="hidden" name="company[company_id]" value="<?php echo $company_id; ?>" />
   <input type="hidden" name="company[company_type]" value="<?php echo $company_type; ?>" />
   <input type="hidden" name="company[main_contacts]" value="<?php echo $obj->main_contacts; ?>" />
   <table cellspacing="1" cellpadding="1" border="0" width="100%" class="std">
   <td align="left">
	<tr>
	  <td>
		<table>
			<tr>
			 <td align="right"><?php echo $AppUI->_('Company Name');?>: </td>
			  <td>
					<input type="text" class="text" name="company[company_name]" id="company_name" value="<?php echo dPformSafe(@$obj->company_name);?>"
					size="50" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
			</td>
		   </tr>
		  <tr>
			<td align="right"><?php echo $AppUI->_('Email');?> </td>
			<td>
			<input type="text" class="text" name="company[company_email]" value="<?php echo dPformSafe(@$obj->company_email);?>"
            size="30" maxlength="255" />
			</td>
		  </tr>
		 <tr>
   		<td align="right"><?php echo $AppUI->_('Phone');?>:</td>
		<td>
			<input type="text" class="text" name="company[company_phone1]" value="<?php echo dPformSafe(@$obj->company_phone1);?>" maxlength="30" />
		</td>
     </tr>
 	 <tr>
		<td align="right"><?php echo $AppUI->_('Phone');?>2:</td>
		<td>
			<input type="text" class="text" name="company[company_phone2]" value="<?php echo dPformSafe(@$obj->company_phone2);?>" maxlength="50" />
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Fax');?>:</td>
		<td>
			<input type="text" class="text" name="company[company_fax]" value="<?php echo dPformSafe(@$obj->company_fax);?>" maxlength="30" />
		</td>
	</tr>
    <tr>
       <td colspan="2" align="center">
          <img src="images/shim.gif" width="50" height="1" /><?php echo $AppUI->_('Address'); ?><br />
          <hr width="500" align="center" size=1 />
       </td>
    </tr>
    	<tr>
		<td align="right"><?php echo $AppUI->_('Postal Address');?></td>
		<td><input type="text" class="text" name="company[company_address1]" value="<?php echo dPformSafe(@$obj->company_address1);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Physical Address');?></td>
		<td><input type="text" class="text" name="company[company_address2]" value="<?php echo dPformSafe(@$obj->company_address2);?>" size=50 maxlength="255" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('City');?>:</td>
		<td><input type="text" class="text" name="company[company_city]" value="<?php echo dPformSafe(@$obj->company_city);?>" size=50 maxlength="50" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo $AppUI->_('Zip');?>:</td>
		<td><input type="text" class="text" name="company[company_zip]" value="<?php echo dPformSafe(@$obj->company_zip);?>" maxlength="15" /></td>
	</tr>
	<tr>
		<td align="right">
			URL http://<A name="x"></a></td><td><input type="text" class="text" value="<?php echo dPformSafe(@$obj->company_primary_url);?>" name="company[company_primary_url]" size="50" maxlength="255" />
			<a href="#x" onClick="testURL('CompanyURLOne')">[<?php echo $AppUI->_('test');?>]</a>
		</td>
	</tr>
  </td>	
<!--
	<tr>
		<td align="right"><?php //echo $AppUI->_('Type');?>:</td>
		<td>
	<?php
//		echo arraySelect( $types, 'company_type', 'size="1" class="text" onChange="javascript:changeRecordType(this.value);"', @$obj->company_type, true );
	?>
		</td>
	</tr>
-->
	</td>
   </table>

   </td>
   <td align="right" valign=top><?php echo $AppUI->_('Description');?>:</td>
	 <td align="left">
		<textarea cols="50" rows="10" class="textarea" name="company[company_description]"><?php echo @$obj->company_description;?></textarea>
	</td>
   </tr>
  </table>

   <tr>
   		<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
		<td colspan="5" align="right"><input type="button" name="btnFuseAction" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt(document.editFrm)" /></td>
   </tr>

<?php
	if (isset($_GET['tab']))
		$AppUI->setState('CompanyAeTabIdx', dPgetParam($_GET, 'tab', 0));
		
	$tab = $AppUI->getState('CompanyAeTabIdx', 0);
	$tabBox =& new CTabBox("?m=companies&a=addedit2&company_id=$company_id", "", $tab, "");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_contacts", "Contacts");
    $tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_telkom", "Telkom Circuit Info.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_custnet", "Cust. Ntwk Info.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_voip", "Cust. VoIP Info.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_term_equip", "Term. Equip.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_customer_ip", "Cust. IP Info.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_ak_config", "AccessKenya Config.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_domain_info", "Domain Info.");
	$tabBox->add("{$dPconfig['root_dir']}/modules/companies/ae_training", "Training");
	$tabBox->loadExtras('companies', 'addedit_kenstream');
	$tabBox->show('', true);
?>

</table>
</form>