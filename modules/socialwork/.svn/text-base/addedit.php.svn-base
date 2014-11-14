<?php 
$social_id = intval( dPgetParam( $_GET, "social_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );
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
$q->addTable('social_work');
$q->addQuery('social_work.*');
$q->addWhere('social_work.social_id = '.$social_id);
$q->clear();

$obj = new CSocialWork();
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

if ( $clientObj->load($obj->social_client_id ? $obj->social_client_id : $client_id) )
{
	$ttl = $social_id > 0 ? "Edit Social Work Log : " . $clientObj->getFullName() : "New Social Work Log Entry: " . $clientObj->getFullName();

}
else
{
   $ttl = $social_id > 0 ? "Edit Social Work Log  " : "New Social Work Log Entry";

}

$client_detail = array("social_client_id" => $clientObj->client_id, "client_name" => $clientObj->getFullName());

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($social_id != 0)
  $titleBlock->addCrumb( "?m=socialwork&a=view&social_id=$social_id", "View" );
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

var calendarField = '';
var calWin = null;


function popCalendar( field ){
	calendarField = field;
	idate = eval( 'document.editFrm.log_' + field + '.value' );
	window.open( 'index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin', 'top=250,left=250,width=251, height=220, scollbars=false, resizable' );
}

function setCalendar( idate, fdate ) 
{
	fld_date = eval( 'document.editFrm.log_' + calendarField );
	fld_fdate = eval( 'document.editFrm.client_' + calendarField );
	fld_date.value = idate;
	fld_fdate.value = fdate;
}

function checkDate()
{
           if (document.frmDate.log_start_date.value == "" || document.frmDate.log_end_date.value== ""){
                alert("<?php echo $AppUI->_('You must fill fields', UI_OUTPUT_JS) ?>");
                return false;
           } 
           return true;
}

</script>
<?php
$date_reg = date("Y-m-d");
$entry_date = intval( $date_reg) ? new CDate( dPgetParam($_REQUEST, "client_entry_date", date("Y-m-d") ) ) : null;
$df = $AppUI->getPref('SHDATEFORMAT');
?>
<form name="changesocial" action="?m=socialwork" method="post">
	<input type="hidden" name="dosql" value="do_socialwork_aed" />
	<input type="hidden" name="social_id" value="<?php echo $social_id;?>" />
	<input type="hidden" name="social_date_entered" value="<?php echo date('Y-m-d h:i:s A'); ?>" />

<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
		   <tr>
			 <td align="right" nowrap><?php echo $AppUI->_('Registration Date');?>: </td>
			<td>
				<input type="hidden" name="log_entry_date" value="<?php echo $entry_date ? $entry_date->format( FMT_TIMESTAMP_DATE ) : "" ;?>" />
				<input type="text" name="client_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text" readonly disabled="disabled" />
				<a href="#" onClick="popCalendar('entry_date')">
				<img src="./images/calendar.gif" width="24" height="12" alt="<?php echo $AppUI->_('Calendar');?>" border="0" ></a>
			</td>
		   </tr>
		  <tr>
			<td align="left" width="100"><?php echo $AppUI->_("Child's Name");?>:</td>
			<td nowrap align="right">
				<input type="text" class="text" name="social_client" value="<?php 
					echo $client_detail['client_name'];
					?>" maxlength="100" size="25" />
				<input type="button" class="button" value="<?php echo $AppUI->_('select client...');?>..." onclick="popClinic()" />
				<input type='hidden' name='social_client_id' value="<?php echo $client_detail['social_client_id']; ?>">
				</td>
		</tr> 
        <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Needs Assessment');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_needs_assessment', 'size="1" class="text"',@$obj->social_needs_assessment ); ?>
 			</td>
		</tr>
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Supported Needs');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_supported_needs', 'size="1" class="text"', @$obj->social_supported_needs ); ?>
 			</td>
		</tr>	  
    	<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Food Support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_food_support', 'size="1" class="text"', @$obj->social_food_support ); ?>
 			</td>
		</tr>   	
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Permanency Plan');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_permanency_plan', 'size="1" class="text"', @$obj->social_permanency_plan ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Nurse & Pal. Care');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_nurse_care', 'size="1" class="text"', @$obj->social_nurse_care ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Hospital Visit');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_hospital_visit', 'size="1" class="text"', @$obj->social_hospital_visit ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Home Visit');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_home_visit', 'size="1" class="text"', @$obj->social_home_visit ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('I.G.A / Microfin.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_microfin', 'size="1" class="text"', @$obj->social_microfin ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Medical Support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_medical_support', 'size="1" class="text"', @$obj->social_medical_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Transport Support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_transport_support', 'size="1" class="text"', @$obj->social_transport_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Education support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_education_support', 'size="1" class="text"', @$obj->social_education_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Clothing & bedding');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_clothing', 'size="1" class="text"', @$obj->social_clothing ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Solidarity support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_solidarity_support', 'size="1" class="text"', @$obj->social_solidarity_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Rent Support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_rent_support', 'size="1" class="text"', @$obj->social_rent_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Other material support');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'social_other_support', 'size="1" class="text"', @$obj->social_other_support ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('# Supported');?>:</td>
			
			<td align="right">
				<input type="text" class="text" name="clinical_age_yrs" value="<?php echo dPformSafe(@$obj->social_no_support);?>" maxlength="150" size="20" />
			</td>

		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Gender (M/F)');?>:</td>
			<td align="right">
				<input type="text" class="text" name="clinical_age_yrs" value="<?php echo dPformSafe(@$obj->social_gender);?>" maxlength="150" size="20" />
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
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Notes on log entry');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="social_notes"><?php echo dPformSafe(@$obj->social_notes);?></textarea>
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
