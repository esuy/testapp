<?php 
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );
require_once ($AppUI->getModuleClass('clients'));


// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($counselling_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $counselling_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_work');
$q->addQuery('counselling_work.*');
$q->addWhere('counselling_work.counselling_id = '.$counselling_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CCounsellingWork();
if (!db_loadObject( $sql, $obj ) && $counselling_id > 0) {
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

if ( $clientObj->load($obj->counselling_client_id ? $obj->counselling_client_id : $client_id) )
{
	$ttl = $counselling_id > 0 ? "Edit Counselling Work Log : " . $clientObj->getFullName() : "New Counselling Work Log : " . $clientObj->getFullName();

}
else
{
   $ttl = $counselling_id > 0 ? "Edit Counselling Work Log  " : "New Counselling Work Log ";

}

$counsellor_id = $obj->counselling_counsellor_id ? $obj->counselling_counsellor_id : 1;
var_dump($counsellor_id);
$client_detail = array("counselling_client_id" => $clientObj->client_id, "client_name" => $clientObj->getFullName(), "client_code" => $clientObj->client_adm_no);
$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=counsellingwork", "counselling services log" );
if ($counselling_id != 0)
  $titleBlock->addCrumb( "?m=counsellingwork&a=view&counselling_id=$counselling_id", "view this log entry" );
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changecounselling;
	/*if (form.company_name.value.length < 3) {
		alert( "<?php echo $AppUI->_('companyValidName', UI_OUTPUT_JS);?>" );
		form.company_name.focus();
	} else {
		form.submit();
	}*/
	form.submit();
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
$entry_date = intval( $obj->counselling_entry_date) ? new CDate($obj->counselling_entry_date) : null;
$df = $AppUI->getPref('SHDATEFORMAT');
?>
<form name="changecounselling" action="?m=counsellingwork" method="post">
	<input type="hidden" name="dosql" value="do_counsellingwork_aed" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
	<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


<table>
		   <tr>
			 <td align="left" nowrap><?php echo $AppUI->_('Counsellor Name');?>: </td>
			 	<td nowrap align="right">
				<input type="text" class="text" name="counselling_counsellor" value="<?php 
					echo $counsellor_detail['counsellor_name'];
					?>" maxlength="100" size="25" />
				<input type="button" class="button" value="<?php echo $AppUI->_('select counsellor...');?>..." onclick="popCounsellor()" />
				<input type='hidden' name='counselling_counsellor_id' value="<?php echo $counsellor_id; ?>" />
				</td>
		   </tr> 
		   <tr>
		      <td align="left" nowrap><?php echo $AppUI->_('Staff code');?>: </td>

				<td>
					<input type="text" name="counselling_staff_code" value="<?php echo $counsellor_detail['counsellor_code'] ;?>" class="text" readonly disabled="disabled" />
			</td>
		   </tr>
		   <tr>
			 <td align="left" nowrap><?php echo $AppUI->_('Visit Date');?>: </td>
			<td>
				<input type="hidden" name="log_entry_date" value="<?php echo $entry_date ? $entry_date->format( FMT_TIMESTAMP_DATE ) : "" ;?>" />
				<input type="text" name="counselling_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text" readonly disabled="disabled" />
				<a href="#" onClick="popCalendar('entry_date')">
				<img src="./images/calendar.gif" width="24" height="12" alt="<?php echo $AppUI->_('Calendar');?>" border="0" ></a>
			</td>
		   </tr>
		<tr>
			<td align="left" width="100"><?php echo $AppUI->_('Name/Caregiver Name');?>:</td>
			<td nowrap align="right">
				<input type="text" class="text" name="counselling_client" value="<?php 
					echo $client_detail['client_name'];
					?>" maxlength="100" size="25" />
				<input type="button" class="button" value="<?php echo $AppUI->_('select client...');?>..." onclick="popClinic()" />
				<input type='hidden' name='counselling_client_id' value="<?php echo $client_detail['counselling_client_id']; ?>">
				</td>
		</tr> 
		<tr>
			<td align="left" width="100"><?php echo $AppUI->_('Admission No:');?>:</td>
			<td nowrap align="right">
				<input type="text" class="text" name="counselling_client_code" value="<?php 
					echo $client_detail['client_code'];
					?>" maxlength="100" size="25" readonly disabled="disabled" />
				</td>
		</tr> 
        <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Support Couns.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_support_counselling', 'size="1" class="text"',@$obj->counselling_support_counselling ); ?>
 			</td>
		</tr>
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Child Couns.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_child_counselling', 'size="1" class="text"', @$obj->counselling_child_counselling ); ?>
 			</td>
		</tr>	  
    	<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Ind. Prevent. Educ.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_ind_prev_educ', 'size="1" class="text"', @$obj->counselling_ind_prev_educ ); ?>
 			</td>
		</tr>   	
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Adherence Couns.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_adherence_counselling', 'size="1" class="text"', @$obj->counselling_adherence_counselling ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Ind. Disclose Couns.');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_ind_disc_counselling', 'size="1" class="text"', @$obj->counselling_ind_disc_counselling ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Lifeskiss Training');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_lifeskiss_training', 'size="1" class="text"', @$obj->counselling_lifeskiss_training ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Recreational Therapy');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_rec_therapy', 'size="1" class="text"', @$obj->counselling_rec_therapy ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Hospital Visit');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_hospital_visit', 'size="1" class="text"', @$obj->counselling_hospital_visit ); ?>
 			</td>
		</tr>	 
		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Home Visit');?>:</td>
			<td align="right">
			<?php echo arraySelect( $boolTypes, 'counselling_home_visit', 'size="1" class="text"', @$obj->counselling_home_visit ); ?>
 			</td>
		</tr>	 
	 

</table>


</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "edit" );
 			$custom_fields->printHTML();
		?>		
	</td>
<td>
   <table border="0" cellpadding="1" cellspacing="1" class="details" width="100%">
	
      <tr>
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Notes on log entry');?>:</td>
		<td align="left" valign="top">
		<textarea cols="50" rows="10" class="textarea" name="counselling_notes"><?php echo dPformSafe(@$obj->counselling_notes);?></textarea>
		</td>

      </tr>
	</table>
 </td>


</tr>
<tr>
</tr>

<tr >
	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td colspan="2" align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>

</table>
</form>
