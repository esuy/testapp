<?php
$admission_id = intval( dPgetParam( $_GET, "admission_id", 0 ) );

$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('social'));
require_once ($AppUI->getModuleClass('counsellinginfo'));

// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($admission_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $admission_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );

// load the record data
$q  = new DBQuery;
$q->addTable('admission_info');
$q->addQuery('admission_info.*');
$q->addWhere('admission_info.admission_id = '.$admission_id);
$sql = $q->prepare();

$obj = new CAdmissionRecord();
if (!db_loadObject( $sql, $obj ) && $admission_id > 0)
{
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

// collect all the users for the clinic owner list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();

// collect all the users with CHW
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addWhere('contact_type="10"');
$q->addOrder('contact_last_name');
$chws = $q->loadHashList();

$chws = arrayMerge(array(0=>'Select CHW'), $chws);

//load clinics
$q  = new DBQuery;
$q->addTable('clinics','c');
$q->addQuery('clinic_id');
$q->addQuery('clinic_name');
$q->addOrder('clinic_name');
$clinics = $q->loadHashList();
$clinicArray = arrayMerge(array(-1=> '-Select Center -'),$clinics);
//var_dump($clinics);

//load social and counselling info

if (!empty($client_id))
{
	$q  = new DBQuery;
	$q->addTable('social_visit');
	$q->addQuery('social_visit.*');
	$q->addWhere('social_visit.social_client_id = '.$client_id);
	$sql = $q->prepare();
	//var_dump($sql);
	$q->clear();
	$socialObj = new CSocialVisit();
	db_loadObject( $sql, $socialObj );

	$q  = new DBQuery;
	$q->addTable('counselling_info');
	$q->addQuery('counselling_info.*');
	$q->addWhere('counselling_info.counselling_client_id = '.$client_id);
	$sql = $q->prepare();
	//var_dump($sql);
	$q->clear();
	$counsellingObj = new CCounsellingInfo();
	db_loadObject( $sql, $counsellingObj );

}
$locations= array();
if (intval($counsellingObj->counselling_clinic) > 0){
	$q  = new DBQuery;
	$q->addTable('clinic_location');
	$q->addQuery('clinic_location.clinic_location_id, clinic_location.clinic_location');
	$q->addWhere('clinic_location.clinic_location_clinic_id = "'.$counsellingObj->counselling_clinic.'"');
	$locations = $q->loadHashList();
}
$locations = arrayMerge(array(0=>'Select Location'), $locations);


$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$ageType = dPgetSysVal('AgeType');
$genderTypes = dPgetSysVal('GenderType');
$maritalStatus = dPgetSysVal('MaritalStatus');
$educationLevels = dPgetSysVal('EducationLevel');
$childEducationLevels = dPgetSysVal('ChildEducationLevel');
$reasonsNoSchool = dPgetSysVal('AdmissionReasonsNotAttendingSchool');
$caregiverStatus = dPgetSysVal('CaregiverStatus');
$caregiverHealthStatus = dPgetSysVal('CaregiverHealthStatus');
$incomeLevels = dPgetSysVal('IncomeLevels');
$employmentType = dPgetSysVal('EmploymentType');
$dehydrationTypes = dPgetSysVal('DehydrationType');
$tbTypes = dPgetSysVal('TBType');
$malnutrionTypes = dPgetSysVal('MalnutritionType');
$earTypes = dPgetSysVal('EarType');
$arvTypes = dPgetSysVal('ARVType');
$tbDrugsTypes = dPgetSysVal('TBDrugsType');
$locationOptions = dPgetSysVal('LocationOptions');
$locationOptions = arrayMerge(array (0=>'- Select Location -'), $locationOptions);
$nutritionTypes = dPgetSysVal('NutritionType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );
$enclosures = dPgetSysVal('Enclosures');
$carePTypes=dPgetSysVal('CaregiverPreTypes');

$genderTypesPre = arrayMerge(array(-1=>'- Select gender -'),$genderTypes);

$client_id = $client_id ? $client_id : $obj->admission_client_id;

$clientObj = new CClient();
if ($clientObj->load($client_id)){
	$ttl = $admission_id > 0 ? "Edit Admission Record : " . $clientObj->getFullName() : "New Admission Record: " . $clientObj->getFullName();
}
else{
   $ttl = $admission_id > 0 ? "Edit Admission Record " : "New Admission Record ";
}

//load family members
if ($admission_id > 0){
	$q = new DBQuery();
	$q->addTable("household_info");
	$q->addQuery("household_info.*");
	//$q->addWhere("household_info.household_admission_id = " . $obj->admission_id);
	$q->addWhere('household_client_id="'.$client_id.'"');
	$rows = $q->loadList();
}
// setup the title block

//load client
$rowcount = 0;

$age_years = 0;
$age_months = 0;
$age_years = $obj->admission_age_yrs;
$age_months = $obj->admission_age_months;

if ($admission_id>0)
{
  if (isset($clientObj))
  {
	$clientObj->getAge($age_years,$age_months);
  }
}

$caregivers=null;
$tablePre=null;
$careofs=null;

$obj->getCare(true);


$date_reg = date("Y-m-d");
//$entry_date = intval( $obj->admission_entry_date) ? new CDate( $obj->admission_entry_date ) : new CDate( $date_reg );
//$entry_date = !is_null( $counsellingObj->counselling_admission_date) ? new CDate($counsellingObj->counselling_admission_date ) : new CDate( $date_reg );
$dob = intval( $counsellingObj->counselling_dob) ? new CDate( $counsellingObj->counselling_dob ) : NULL;
$entry_date = (!is_null( $obj->admission_entry_date) && (int)$obj->admission_entry_date > 0) ? new CDate($obj->admission_entry_date ) : new CDate($clientObj->client_doa /*$counsellingObj->counselling_admission_date */);
$df = $AppUI->getPref('SHDATEFORMAT');

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeAdmission'])", "Clear All Selections" );

if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName()  );
/*
if ($admission_id != 0)
  $titleBlock->addCrumb( "?m=admission&a=view&admission_id=$admission_id", "View" );
  */

$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeAdmission ;
	var count = 0;
	form.household_num_rows.value = document.getElementById('family').rows.length;
	if(!manField("staff_id")){
		alert("Please select Staff!");
		return false;
	}
	if(!manField("clinic_id")){
		alert("Please select Center!");
		return false;
	}
	if (form.admission_dob && form.admission_dob.value.length > 0)
	{
		errormsg = checkValidDate(form.admission_dob.value);

		if (errormsg.length > 1)
		{
			alert("Invalid date of birth" );
			form.admission_dob.focus();
			exit;
		}
	}
	if (form.admission_entry_date && form.admission_entry_date.value.length > 0)
	{
		errormsg = checkValidDate(form.admission_entry_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.admission_entry_date.focus();
			exit;
		}
	}
	 if (form.admission_age_yrs && form.admission_age_yrs.value.length > 0)
	{
		if (isNaN(parseInt(form.admission_age_yrs.value,10)) )
		{
			alert(" Invalid Age (years)");
			form.admission_age_yrs.focus();
			exit;

		}
	}
	if (form.admission_age_months && form.admission_age_months.value.length > 0)
	{
		if (isNaN(parseInt(form.admission_age_months.value,10)) )
		{
			alert(" Invalid Age (months)");

			form.admission_age_months.focus();
			exit;

		}
	}
	if (form.admission_father_age && form.admission_father_age.value.length > 0)
	{
		if (isNaN(parseInt(form.admission_father_age.value,10)) )
		{
			alert(" Invalid Age");
			form.admission_father_age.focus();
			exit;

		}
	}
	if (form.admission_mother_age && form.admission_mother_age.value.length > 0)
	{
		if (isNaN(parseInt(form.admission_mother_age.value,10)) )
		{
			alert(" Invalid Age");
			form.admission_mother_age.focus();
			exit;

		}
	}
	if (form.admission_caregiver_age && form.admission_caregiver_age.value.length > 0)
	{
		if (isNaN(parseInt(form.admission_caregiver_age.value,10)) )
		{
			alert(" Invalid Age");
			form.admission_caregiver_age.focus();
			exit;

		}
	}

	//loop thro table of family members and check for valid yob
	for (count = 1; count < document.getElementById('family').rows.length; count++)
	{
		var elementtocheck = document.getElementById('yob_'+ count)
		if (elementtocheck && elementtocheck.value.length > 0)
		{
			if(checkValidDate(elementtocheck.value) == ''){
				var tyr=elementtocheck.value.split('/');
				elementtocheck.value=tyr[2];
			}
			errormsg = checkValidYear(elementtocheck.value);
			if (errormsg.length > 1)
			{
				alert("Invalid YOB (Row " + count + ")" );
				elementtocheck.focus();
				exit;
			}
		}
	}
	form.submit();
}
// Given a tr node and row number (newid), this iterates over the row in the
// DOM tree, changing the id attribute to refer to the new row number.
function rowrenumber(newrow, newid)
{
	var oldid;
	$j(newrow)
	.find("input[type='text']:eq(0)").attr("name",function(i,x){
		oldid = x.match(/(\d+)$/);
	}).end()
/*	.find('input[name^="'+key+'"]').attr('name',function(i,x){
		oldid=x.replace(key+'_','');
		return x;
	}).end()*/
	.html(function(i,x){
			var xr = new RegExp('_'+oldid[0],"g");
			return x.replace(xr,'_'+newid);
	});

}

/*function rowrenumber(newrow, newid)
{
  var curnode = newrow.firstChild;      // td node
  while (curnode) {
    var curitem = curnode.firstChild;   // input node (or whatever)
    while (curitem) {
      if (curitem.id) {  // replace row number in id
        var idx = 0,
			spl = curitem.id.split('_'),
			baseid = spl[0];
        curitem.id = baseid + '_' + newid;
        if (curitem.name)
          curitem.name = baseid + '_' + newid;
        if (baseid == 'catno')
          curitem.tabIndex = newid;
      }
      curitem = curitem.nextSibling;
    }
    curnode = curnode.nextSibling;
  }
}*/
// Appends a row to the given table, at the bottom of the table.
var topCount=0;
function AppendRow(table_id) {
var $tbody = $j("#"+table_id).find("tbody"),
	curamnt=$j("tr",$tbody).length;
	if(topCount === 0 ){
		topCount=curamnt + 1;
	}else{
		++topCount;
	}
/*if(curamnt === 3){
	return false;
}*/
	var row = $j("tr:eq(0)",$tbody);  // 1st row
	var newid = topCount;  // Since this includes the header row, we don't need to add one
	var newrow = $j(row).clone(true);

  rowrenumber(newrow, newid);

    // Clear out data from new row.

  $j(newrow)
	//.find(".nmb").text(28 + curamnt).end()
  	.find("img").remove().end()
  	.find("input").val("").end()
  	.find("#yob_"+newid).attr("class",'text').end()
  	.find("#delete_"+newid+" #delete_1").html();
  //row.parentNode.appendChild(newrow);      // Attach to table
  $tbody.append(newrow);      // Attach to table_id
  attachPicker($j("#yob_" + newid),'');
    // Clear out data from new row.
	/*$j("tr",$tbody).each(function(ind){
		rowrenumber(this,(ind+1));
		attachPicker($j("#yob_" + (ind +1)),'');
	});*/


}

function NewHistoryRow(table_id)
{
  var row = document.getElementById(table_id).rows.item(1);  // 1st row
  var newid = row.parentNode.rows.length;  // Since this includes the header row, we don't need to add one
  var newrow = row.cloneNode(true);

  rowrenumber(newrow, newid);

  $j(newrow)
  	.find("input").val("").end()
  	.find("#delete_"+newid+" #delete_1").html("X");

  row.parentNode.appendChild(newrow);      // Attach to table

    // Clear out data from new row.

}

// Give a node within a row of the table (one level down from the td node),
// this deletes that row, renumbers the other rows accordingly, updates
// the Grand Total, and hides the delete button if there is only one row
// left.
function DeleteRow(el)
{
  var row = el.parentNode.parentNode,   // tr node
	rownum = row.rowIndex,            // row to delete
	tbody = row.parentNode,           // tbody node
	ctable = tbody.parentNode,
	numrows = tbody.rows.length - 1;  // don't count header row!
  if (numrows == 0)                     // can't delete when only one row left
    return false;

  var node = row;
  tbody.removeChild(node);
  var newid = -1;

    // Loop through tr nodes and renumber - only rows numbered
    // higher than the row we just deleted need renumbering.

  row = tbody.firstChild;
  /*while (row) {
    if (row.tagName == 'TR') {
      newid++;
      if (newid >= rownum)
        rowrenumber(row, newid);
    }
    row = row.nextSibling;
  }*/
  if (numrows == 2) {  // 2 rows before deleting - only 1 left now, so 'hide' delete button
    var delbutton = document.getElementById('delete_1');
    //delbutton.innerHTML = ' ';
  }
	/*if($j(ctable).attr("id") == 'family'){
		var $tbody=$j(tbody);
		$j("tr",$tbody).each(function(i){
			$j(this).find("span.nmb").text(28 + i);
			rowrenumber(this,(i+1));
		});
	}*/
}

function enclT(obj){
	var nv=$j(obj).val(),zv=$j(obj).find("option[value=" + nv + "]").text().toLowerCase();
	if(zv == 'other'){
		$j(obj).next("input").show();
	}else{
		$j(obj).next("input").hide();
		document.changeAdmission.admission_enclosures_other.value='';
	}
}
</script>

<form name="changeAdmission" action="?m=admission" method="post">
	<input type="hidden" name="dosql" value="do_admission_aed" />
	<input type="hidden" name="admission_id" value="<?php echo $admission_id;?>" />
	<input type="hidden" name="admission_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" id="household_num_rows" name="household_num_rows" value="" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td valign="top" width="50%">
<table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left">
         	<?php
         		echo arraySelect($clinicArray,'admission_clinic_id','class="text" id="clinic_id"',$obj->admission_clinic_id,$identifiers);
         		//<input type="text" class="text" name="admission_clinic_id" value="<?php echo dPformSafe($clinics[$counsellingObj->counselling_clinic]);" maxlength="150" size="20" disabled  readonly="readonly" />
         	?>			
         </td>
		 </tr>
     	<tr>
			 <td align="left" nowrap="nowrap">1b.<?php echo $AppUI->_('Date of admission');?>: </td>
			<td align="left">
				<input type="text" readonly="readonly" name="admission_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text">
				&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
			</td>
		   </tr>
		<tr>
         <td align="left">1c.<?php echo $AppUI->_('Officer');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'admission_staff_id', 'size="1" class="text" id="staff_id"', @$obj->admission_staff_id ? $obj->admission_staff_id:-1); ?>
		</td>

       </tr>
	   <tr>
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="admission_client_code" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
		<tr>
         <td align="left">2b,2c<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="admission_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>

		 <tr>
		  <td align="left">...<?php echo $AppUI->_('Gender');?>:</td>
		   <td align="left">
		   <?php echo @$genderTypes[$clientObj->client_gender];
		   	//arraySelectRadio($genderTypes, "admission_gender", 'onclick=toggleButtons() disabled="disabled"', $obj->admission_gender? $obj->admission_gender : -1, $identifiers );
		   ?></td>
		 </tr>


     <tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top">
			<?php
			echo //drawDateCalendar("admission_dob",$dob ? $dob->format($df) : "" ,false,'id="admission_dob"');
			$dob ? $dob->format($df) : '';
			//<input type="text" class="text" name="admission_dob" id="admission_dob" value="<?php echo $dob ? $dob->format($df) : "";" maxlength="150" size="20" readonly disabled="disabled"/>&nbsp;dd/mm/yyyy
			?>

			</td>

	  </tr>
	  <!--  <tr>
         <td valign="top"><?php echo $AppUI->_('Age (years)');?>:</td>
		   <td>
	       <input type="text" disabled class="text" name="admission_age_yrs" value="<?php echo dPformSafe(@$age_years);?>" maxlength="30" size="20" readonly />
		    </td>
          </tr>
		<tr>
         <td valign="top"><?php echo $AppUI->_('Age (months)');?>:</td>
		  <td>
	         <input type="text" disabled class="text" name="admission_age_months" value="<?php echo dPformSafe(@$age_months);?>" maxlength="30" size="20" readonly />
    	   </td>
		 </tr>
		<tr> -->

		<td>&nbsp;</td>
		<td><?php echo arraySelectRadio($ageType, "admission_age_status", 'onclick=toggleButtons(); readonly disabled="disabled"', $counsellingObj->counselling_age_status ? $counsellingObj->counselling_age_status : -1, $identifiers ); ?></td>
		</tr>
      <tr>
         <td align="left" nowrap="nowrap">3a.<?php echo $AppUI->_('School Level');?>:</td>
		 <td align="left">
	   <?php echo arraySelectRadio($childEducationLevels, "admission_school_level", 'onclick=toggleButtons()', $obj->admission_school_level ? $obj->admission_school_level : -1, $identifiers ); ?>
		 </td>
      </tr>
	  <tr>
	     <td align="left" nowrap="nowrap">3b.<?php echo $AppUI->_('If not attending,why');?>:</td>
		 <td align="left" nowrap="nowrap">
	   <?php echo arraySelectRadio($reasonsNoSchool, "admission_reason_not_attending", 'onclick=toggleButtons()', $obj->admission_reason_not_attending ? $obj->admission_reason_not_attending : -1, $identifiers ); ?>
		 </td>
	   </tr>
	  <tr>
         <td align="left" nowrap="nowrap">3c.<?php echo $AppUI->_('Other reason');?>:</td>
		 <td align="left">
	    <input type="text" class="text" name="admission_reason_not_attending_notes" value="<?php echo @$obj->admission_reason_not_attending_notes;?>" maxlength="150" size="40" />
		 </td>
	 </tr>
	  <tr>
         <td align="left" nowrap="nowrap">4.<?php echo $AppUI->_('Current Residence');?>:</td>
		 <td align="left">
	    <input type="text" class="text" name="admission_residence" value="<?php echo @$obj->admission_residence;?>" maxlength="150" size="40" />
		 </td>

      </tr>
		  <tr>
			<td align="left" width="100">5a.<?php echo $AppUI->_('Location');?>:</td>
			<td nowrap="nowrap" align="left">
			<?php echo arraySelect( $locations, 'admission_location', 'size="1" class="text"', @$obj->admission_location ? $obj->admission_location:-1); ?>
			</td>
		</tr>

		  <tr>
			<td align="left" width="100">5b.<?php echo $AppUI->_('CHW');?>:</td>
			<td nowrap="nowrap" align="left">
			<?php echo arraySelect( $chws, 'admission_chw', 'size="1" class="text"', @$obj->admission_chw ? $obj->admission_chw:-1); ?>
			</td>
		</tr>

        <tr>
			<td align="left" nowrap="nowrap" valign="top"><?php echo $AppUI->_('Rural Home');?>:</td>
		</tr>
		<tr>
			  <td align="left" nowrap="nowrap">6a.<?php echo $AppUI->_('Province');?>:</td>
		 <td align="left">
	    <input type="text" class="text" name="admission_province" value="<?php echo @$obj->admission_province;?>" maxlength="150" size="20" />
		 </td>
		   </tr>
		<tr>
			  <td align="left" nowrap="nowrap">6b.<?php echo $AppUI->_('District');?>:</td>
		 <td align="left">
	    <input type="text" class="text" name="admission_district" value="<?php echo @$obj->admission_district;?>" maxlength="150" size="20" />
		 </td>
		   </tr>
			<tr>
			  <td align="left" nowrap="nowrap">6c.<?php echo $AppUI->_('Village');?>:</td>
			<td align="left">
	    <input type="text" class="text" name="admission_village" value="<?php echo @$obj->admission_village;?>" maxlength="150" size="20" />
		 </td>
		   </tr>
	      <tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Caregiver Information'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	   <tr>
         <td align="left" nowrap="nowrap" valign="top"><b><?php echo $AppUI->_('Father');?>:</b></td>
		 </tr>
		 <tr>
		   <td align="left">7a...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_father_fname" value="<?php echo @$obj->admission_father_fname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left">7b...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_father_lname" value="<?php echo @$obj->admission_father_lname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left">7c...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_father_age" value="<?php echo @$obj->admission_father_age;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left" valign="top">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left">8a
			<?php echo arraySelectRadio($caregiverStatus, "admission_father_status", 'onclick=toggleButtons()', $obj->admission_father_status ? $obj->admission_father_status  : NULL, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
		   <td align="left" valign="top">&nbsp;</td>
		   <td align="left">8b.
			<?php echo arraySelectRadio($caregiverHealthStatus, "admission_father_health_status", 'onclick=toggleButtons()', $obj->admission_father_health_status ? $obj->admission_father_health_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">8c...<?php echo $AppUI->_('Raising Child');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($boolTypes, "admission_father_raising_child", 'onclick=toggleButtons()', $obj->admission_father_raising_child ? $obj->admission_father_raising_child  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">8d...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($maritalStatus, "admission_father_marital_status", 'onclick=toggleButtons()', $obj->admission_father_marital_status ? $obj->admission_father_marital_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		 <tr>
		   <td align="left">9...<?php echo $AppUI->_('Education Level');?>:</td>

		   <td align="left">
		   <?php echo arraySelectRadio($educationLevels, "admission_father_educ_level", 'onclick=toggleButtons()', $obj->admission_father_educ_level ? $obj->admission_father_educ_level  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">10...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left">
		   <?php echo arraySelectRadio($employmentType, "admission_father_employment", 'onclick=toggleButtons()', $obj->admission_father_employment ? $obj->admission_father_employment  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left" valign="top"><?php echo $AppUI->_('Other Details');?>:</td>
		   </tr>
		    <tr>
			  <td align="left">11a...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left"><input type="text" class="text" name="admission_father_idno" value="<?php echo @$obj->admission_father_idno;?>" maxlength="150" size="20" /></td>
			</tr>
		    <tr>
			  <td align="left">11b...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left"><input type="text" class="text" name="admission_father_mobile" value="<?php echo @$obj->admission_father_mobile;?>" maxlength="150" size="20" /></td>
			</tr>
		 <tr>
         <td align="left" nowrap="nowrap" valign="top"><b><?php echo $AppUI->_('Mother');?>:</b></td>
		 </tr>
		 <tr>
		   <td align="left">12a...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_mother_fname" value="<?php echo @$obj->admission_mother_fname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left">12b...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_mother_lname" value="<?php echo @$obj->admission_mother_lname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left">12c...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left"><input type="text" class="text" name="admission_mother_age" value="<?php echo @$obj->admission_mother_age;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td align="left" valign="top">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left">13a.
	   <?php echo arraySelectRadio($caregiverStatus, "admission_mother_status", 'onclick=toggleButtons()', $obj->admission_mother_status ? $obj->admission_mother_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
		   <td align="left" valign="top">&nbsp;</td>
		   <td align="left">13b.
			<?php echo arraySelectRadio($caregiverHealthStatus, "admission_mother_health_status", 'onclick=toggleButtons()', $obj->admission_mother_health_status ? $obj->admission_mother_health_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">13c...<?php echo $AppUI->_('Raising Child');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($boolTypes, "admission_mother_raising_child", 'onclick=toggleButtons()', $obj->admission_mother_raising_child ? $obj->admission_mother_raising_child  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">13d...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($maritalStatus, "admission_mother_marital_status", 'onclick=toggleButtons()', $obj->admission_mother_marital_status ? $obj->admission_mother_marital_status  : -1, $identifiers ); ?>
		 </td>
		  </tr>

		 <tr>
		   <td align="left">14...<?php echo $AppUI->_('Education Level');?>:</td>

		   <td align="left">
		   <?php echo arraySelectRadio($educationLevels, "admission_mother_educ_level", 'onclick=toggleButtons()', $obj->admission_mother_educ_level ? $obj->admission_mother_educ_level  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">15...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left">
		   <?php echo arraySelectRadio($employmentType, "admission_mother_employment", 'onclick=toggleButtons()', $obj->admission_mother_employment ? $obj->admission_mother_employment  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left" valign="top"><?php echo $AppUI->_('Other Details');?>:</td>
		   </tr>
		    <tr>
			  <td align="left">16a...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left"><input type="text" class="text" name="admission_mother_idno" value="<?php echo @$obj->admission_mother_idno;?>" maxlength="150" size="20" /></td>
			</tr>
		    <tr>
			  <td align="left">16b...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left"><input type="text" class="text" name="admission_mother_mobile" value="<?php echo @$obj->admission_mother_mobile;?>" maxlength="150" size="20" /></td>
			</tr>
<tr>
         <td align="left" nowrap="nowrap" valign="top"><b><?php echo $AppUI->_('Primary Caregiver');?>:</b></td>
         <td><?php echo arraySelect($carePTypes,'pri_mode','class="text"',$obj->guessPerson('pri'));?></td>
	</tr>
		 <tr>
		   <td>17a...<?php echo $AppUI->_('First Name');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_pri_fname" value="<?php echo @$obj->admission_caregiver_pri_fname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td>17b...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_pri_lname" value="<?php echo @$obj->admission_caregiver_pri_lname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td>17c...<?php echo $AppUI->_('Age');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_pri_age" value="<?php echo @$obj->admission_caregiver_pri_age;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td valign="top">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left">18a.
	   <?php echo arraySelectRadio($caregiverStatus, "admission_caregiver_pri_status", 'onclick=toggleButtons()', $obj->admission_caregiver_pri_status ? $obj->admission_caregiver_pri_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
		   <td></td>
		   	<td>18b.
			<?php echo arraySelectRadio($caregiverHealthStatus, "admission_caregiver_pri_health_status", 'onclick=toggleButtons()', $obj->admission_caregiver_pri_health_status ? $obj->admission_caregiver_pri_health_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td>18c...<?php echo $AppUI->_('Relationship to child');?>:</td>
		   <td align="left">
	   <input type="text" class="text" name="admission_caregiver_pri_relationship" value="<?php echo @$obj->admission_caregiver_pri_relationship;?>" maxlength="150" size="20" />
		 </td>
		   </tr>
		   <tr>
			<td>18d...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($maritalStatus, "admission_caregiver_pri_marital_status", 'onclick=toggleButtons()', $obj->admission_caregiver_pri_marital_status ? $obj->admission_caregiver_pri_marital_status  : -1, $identifiers ); ?>
		 </td>
		 </tr>
		 <tr>
		   <td>19...<?php echo $AppUI->_('Education Level');?>:</td>

		   <td>
		   <?php echo arraySelectRadio($educationLevels, "admission_caregiver_pri_educ_level", 'onclick=toggleButtons()', $obj->admission_caregiver_pri_educ_level ? $obj->admission_caregiver_pri_educ_level  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td>20...<?php echo $AppUI->_('Employment');?>:</td>
		   <td>
		   <?php echo arraySelectRadio($employmentType, "admission_caregiver_pri_employment", 'onclick=toggleButtons()', $obj->admission_caregiver_pri_employment ? $obj->admission_caregiver_pri_employment  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td valign="top"><?php echo $AppUI->_('Other Details');?>:</td>
		   </tr>
		    <tr>
			  <td>21a...<?php echo $AppUI->_('ID #');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_pri_idno" value="<?php echo @$obj->admission_caregiver_pri_idno;?>" maxlength="150" size="20" /></td>
			</tr>
		    <tr>
			  <td>21b...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_pri_mobile" value="<?php echo @$obj->admission_caregiver_pri_mobile;?>" maxlength="150" size="20" /></td>
			</tr>
			<tr>
			  <td>21c...<?php echo $AppUI->_('Residence');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_pri_residence" value="<?php echo @$obj->admission_caregiver_pri_residence;?>" maxlength="150" size="20" /></td>
			</tr>
	<tr>
         <td align="left" nowrap="nowrap" valign="top"><b><?php echo $AppUI->_('Secondary Caregiver');?>:</b></td>
         <td><?php echo arraySelect($carePTypes,'sec_mode','class="text"',$obj->guessPerson('sec'));?></td>
	</tr>
		 <tr>
		   <td>22a...<?php echo $AppUI->_('First Name');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_sec_fname" value="<?php echo @$obj->admission_caregiver_sec_fname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td>22b...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_sec_lname" value="<?php echo @$obj->admission_caregiver_sec_lname;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td>22c...<?php echo $AppUI->_('Age');?>:</td>
		   <td><input type="text" class="text" name="admission_caregiver_sec_age" value="<?php echo @$obj->admission_caregiver_sec_age;?>" maxlength="150" size="20" /></td>
		 </tr>
		 <tr>
		   <td valign="top">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left">23a.
	   <?php echo arraySelectRadio($caregiverStatus, "admission_caregiver_sec_status", 'onclick=toggleButtons()', $obj->admission_caregiver_sec_status ? $obj->admission_caregiver_sec_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
		   <td></td>
		  <td>23b.
			<?php echo arraySelectRadio($caregiverHealthStatus, "admission_caregiver_sec_health_status", 'onclick=toggleButtons()', $obj->admission_caregiver_sec_health_status ? $obj->admission_caregiver_sec_health_status  : -1, $identifiers ); ?>
		 </td>
		   </tr>
		   <tr>
			<td>...<?php echo $AppUI->_('Relationship to child');?>:</td>
		   <td align="left">
	   <input type="text" class="text" name="admission_caregiver_sec_relationship" value="<?php echo @$obj->admission_caregiver_sec_relationship;?>" maxlength="150" size="20" />
		 </td>
		   </tr>
		   <tr>
			<td>23d...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left">
	   <?php echo arraySelectRadio($maritalStatus, "admission_caregiver_sec_marital_status", 'onclick=toggleButtons()', $obj->admission_caregiver_sec_marital_status ? $obj->admission_caregiver_sec_marital_status  : -1, $identifiers ); ?>
		 </td>
		 </tr>
		 <tr>
		   <td>24...<?php echo $AppUI->_('Education Level');?>:</td>

		   <td>
		   <?php echo arraySelectRadio($educationLevels, "admission_caregiver_sec_educ_level", 'onclick=toggleButtons()', $obj->admission_caregiver_sec_educ_level ? $obj->admission_caregiver_sec_educ_level  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td>25...<?php echo $AppUI->_('Employment');?>:</td>
		   <td>
		   <?php echo arraySelectRadio($employmentType, "admission_caregiver_sec_employment", 'onclick=toggleButtons()', $obj->admission_caregiver_sec_employment ? $obj->admission_caregiver_sec_employment  : -1, $identifiers ); ?>
		   </td>
		 </tr>
		 <tr>
		   <td valign="top"><?php echo $AppUI->_('Other Details');?>:</td>
		   </tr>
		    <tr>
			  <td>26a...<?php echo $AppUI->_('ID #');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_sec_idno" value="<?php echo @$obj->admission_caregiver_sec_idno;?>" maxlength="150" size="20" /></td>
			</tr>
		    <tr>
			  <td>26b...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_sec_mobile" value="<?php echo @$obj->admission_caregiver_sec_mobile;?>" maxlength="150" size="20" /></td>
			</tr>
		<tr>
			  <td>26c...<?php echo $AppUI->_('Residence');?>:</td>
			  <td><input type="text" class="text" name="admission_caregiver_sec_residence" value="<?php echo @$obj->admission_caregiver_sec_residence;?>" maxlength="150" size="20" /></td>
		</tr>
	  	<tr>
			  <td>27...<?php echo $AppUI->_('Total Household Income');?>:</td>
			  <td>
			  <?php echo arraySelectRadio($incomeLevels, "admission_family_income", 'onclick=toggleButtons()', $obj->admission_family_income ? $obj->admission_family_income  : -1, $identifiers ); ?>
			  </td>
		</tr>
      <tr>
         <td align="left" nowrap="nowrap" valign="top"><?php echo $AppUI->_('C. Other Household Members');?>:</td>
		 <td align="left" class="std">
		 <table>
		   <tr>
		    <td>
				 <table id="family" class="ortho">
					<thead>
					<tr>
					 <th><?php echo $AppUI->_('Name');?></th>
					 <th><?php echo $AppUI->_('Year of Birth');?></th>
					 <th><?php echo $AppUI->_('Gender');?></th>
					 <th><?php echo $AppUI->_('Relationship to child');?></th>
					 <th><?php echo $AppUI->_('Registered in LTP- ADM#');?></th>
					 <!-- <th><?php echo $AppUI->_('Comments');?></th> -->
					 <th>&nbsp;</th>
					 </tr>
					 </thead>
					 <tbody>

					 <?php
					 $rowcount = 1;
					 if (count($rows) > 0 )
					 {
						foreach ($rows as $row){

					 ?>
					 <tr>
						 <td  align="left">a.&nbsp;<input type="hidden" name="household_id_<?php echo $rowcount; ?>" value="<?php echo @$row["household_id"]?>" /><input type="text" class="text" id="name_<?php echo $rowcount; ?>" name="name_<?php echo $rowcount; ?>" value="<?php echo @$row["household_name"]?>" maxlength="150" size="20" />
						 </td>
						 <td  align="left">b.&nbsp;<?php echo drawDateCalendar('yob_'.$rowcount,@$row["household_yob"],false,'id="yob_'.$rowcount.'"',false,12); ?></td>
						 <td  align="left">c.&nbsp;<?php echo arraySelect( $genderTypesPre, "gender_$rowcount", 'size="1" class="text" id="gender_'.$rowcount.'"', @$row["household_gender"] ); ?>
						 </td>
						 <td  align="left">d.&nbsp;<input type="text" class="text" id="relationship_<?php echo $rowcount; ?>" name="relationship_<?php echo $rowcount; ?>" value="<?php echo @$row["household_relationship"];?>" maxlength="150" size="20" />
						</td>
						 <td  align="left">e.&nbsp;<input type="text" class="text" id="notes_<?php echo $rowcount; ?>" name="notes_<?php echo $rowcount; ?>" value="<?php echo @$row["household_notes"];?>" maxlength="150" size="20" />
						</td>

						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>

					 </tr>
					 <?php
					 /* <td align="left"><input type="text" class="text" id="custom_<?php echo $rowcount; ?>" name="custom_<?php echo $rowcount; ?>" value="<?php echo @$row["household_custom"];?>" maxlength="150" size="20" /></td>*/
						 $rowcount++;
					     } //end for
					  }
					  else
					  {
					  ?>
					  	<tr>
						 <td  align="left">a.&nbsp;<input type="hidden" name="household_id_<?php echo $rowcount; ?>" value="<?php echo @$row["household_id"]?>" /><input type="text" class="text" id="name_<?php echo $rowcount; ?>" name="name_<?php echo $rowcount; ?>" value="<?php echo @$row["household_name"]?>" maxlength="150" size="20" />
						 </td>
						 <td  align="left">b.<?php echo drawDateCalendar('yob_'.$rowcount,@$row["household_yob"],false,'id="yob_'.$rowcount.'"',false,12);?></td>
						 <td  align="left">c.<?php echo arraySelect( $genderTypesPre, "gender_$rowcount", 'size="1" class="text" id="gender_'.$rowcount.'"', @$row["household_gender"] ); ?>
						 </td>
						 <td  align="left">d.<input type="text" class="text" id="relationship_<?php echo $rowcount; ?>" name="relationship_<?php echo $rowcount; ?>" value="<?php echo @$row["household_relationship"];?>" maxlength="150" size="20" />
						</td>
						 <td  align="left">e.<input type="text" class="text" id="notes_<?php echo $rowcount; ?>" name="notes_<?php echo $rowcount; ?>" value="<?php echo @$row["household_notes"];?>" maxlength="150" size="20" />
						</td>

						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					  <?php
						/*<td align="left"><input type="text" class="text" id="custom_<?php echo $rowcount; ?>" name="custom_<?php echo $rowcount; ?>" value="<?php echo @$row["household_custom"];?>" maxlength="150" size="20" /></td>*/
					  }//end if
					 ?>
					</tbody>
				</table>
			  </td>
            </tr>
		 <tr>
			<td>
				<input class="button" type="button" name="append" value="new entry" onclick="AppendRow('family'); return false;"/>
			</td>
		</tr>
		 </table>
		 <?php
            /*if ($AppUI->isActiveModule('relatives') && $perms->checkModule('relatives', 'view'))
		{
			echo "<input type='button' class='button' value='".$AppUI->_("enter household info...")."' onclick='javascript:popRelatives();' />";
		}*/
		?>
		 </td>
	  </tr>
	<tr>
		<td align="left"><?php echo $AppUI->_('31a.Total Orphan');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($boolTypes, "admission_total_orphan", '', $obj->admission_total_orphan? $obj->admission_total_orphan : -1, $identifiers );
		?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">31b.<?php echo $AppUI->_('Risk Level');?>:</td>
			<td align="left" valign="top">
			<?php echo arraySelect( $riskLevels, 'admission_risk_level', 'size="1" class="text"', @$obj->admission_risk_level ); ?>
 			</td>
	 </tr>
     <tr>
		<td align="left" valign="top"><b><?php echo $AppUI->_('Enclosures');?></b></td>
	</tr>
	<tr>
		  <td>32a.<?php echo $AppUI->_('Birth certificate #');?>:</td>
		  <td><input type="text" class="text" name="admission_birth_cert" value="<?php echo @$obj->admission_birth_cert;?>" maxlength="150" size="20" /></td>
	</tr>
	<tr>
		  <td>32b.<?php echo $AppUI->_('ID number #');?>:</td>
		  <td><input type="text" class="text" name="admission_id_no" value="<?php echo @$obj->admission_id_no;?>" maxlength="150" size="20" /></td>
	</tr>
	<tr>
		  <td>32c.<?php echo $AppUI->_('NHF #');?>:</td>
		  <td><input type="text" class="text" name="admission_nhf" value="<?php echo @$obj->admission_nhf;?>" maxlength="150" size="20" /></td>
	</tr>
	<tr>
		  <td>32d.<?php echo $AppUI->_('Med Records');?>:</td>
		  <td>
		  	<?php  echo arraySelectRadio($boolTypes,'admission_med_recs','',$obj->admission_med_recs ? $obj->admission_med_recs : '',$identifiers);?>
		  </td>
	</tr>
	<tr>
		  <td>32e.<?php echo $AppUI->_('Immun. Card #');?>:</td>
		  <td><input type="text" class="text" name="admission_immun" value="<?php echo @$obj->admission_immun;?>" maxlength="150" size="20" /></td>
	</tr>
	<tr>
		  <td>32f.<?php echo $AppUI->_('Death Certificate #');?>:</td>
		  <td><input type="text" class="text" name="admission_death_cert" value="<?php echo @$obj->admission_death_cert;?>" maxlength="150" size="20" /></td>
	</tr>
	<tr>
		  <td>32g...<?php echo $AppUI->_('Other');?>:</td>
		  <td><input type="text" class="text" name="admission_enclosures_other" value="<?php echo @$obj->admission_enclosures_other;?>" maxlength="150" size="20" /></td>
	</tr>

      <tr>
		<td align="left" valign="top" nowrap="nowrap">33.<?php echo $AppUI->_('Social Worker Assessment');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="admission_risk_level_description"><?php echo @$obj->admission_risk_level_description;?></textarea>
		</td>

      </tr>
	</table>
</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->admission_id, "edit" );
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
