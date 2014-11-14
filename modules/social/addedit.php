<?php
$social_id = intval( dPgetParam( $_GET, "social_id", 0 ) );
$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('admission'));

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
$q->addTable('social_visit');
$q->addQuery('social_visit.*');
$q->addWhere('social_visit.social_id = '.$social_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CSocialVisit();
if (!db_loadObject( $sql, $obj ) && $social_id > 0)
{
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}

if ($social_id > 0)
{
	$entry_date = intval( $obj->social_entry_date ) ? new CDate( $obj->social_entry_date ) : null;
	$next_date =  $obj->social_next_visit ? new CDate( $obj->social_next_visit ) : null;
}
else
{
	$entry_date = new CDate( $date );
}
$death_date =  intval($obj->social_death_date) ? new CDate( $obj->social_death_date ) : null;;

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();


//load clinics

$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');

$clinics = arrayMerge(array(0=> '-Select Center -'),$q->loadHashList());

$q=new DBQuery();
$q->addTable('social_visit');
$q->addWhere('social_client_id="'.$client_id.'"');
$q->setLimit(1);
$q->addQuery('min(social_id)');
$firstVisit=$q->loadResult();


if(!is_null($firstVisit) && (int)$firstVisit > 0){
	if($firstVisit == $social_id ){
		$openEdit=true;
	}else{
		$openEdit=false;
	}
	$q = new DBQuery();
	$q->addTable('social_visit');
	$q->addQuery('social_nhf,social_nhf_n,social_nhf_y,social_immun,social_immun_y,social_immun_n');
	$q->addWhere('social_id="'.$firstVisit.'"');
	$q->setLimit(1);
	$aSet=$q->loadList();
	$oldSet=$aSet[0];
}else{
	$openEdit=true;
	$oldSet=array();
}

$statusTypes = dPgetSysVal('ClientStatus');
$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );
$visitTypes = dPgetSysVal('SocialVisitTypes');
$deathTypes = dPgetSysVal('DeathTypes');
$caregiverChangeTypes = dPgetSysVal('CaregiverChangeTypes');
$caregiverHealthStatus = dPgetSysVal('CaregiverHealthStatus');
$caregiverHealthChanges =  dPgetSysVal('CaregiverHealthChanges');
$educationLevels =  dPgetSysVal('EducationLevel');
$genderTypes = arrayMerge(array(-1 => '-- Select --' ),dPgetSysVal('GenderType'));
$employmentTypes =  dPgetSysVal('EmploymentType');
$socialstatusTypes = arrayMerge(array(0=>'-Select Client Status-'),dPgetSysVal('SocialClientStatus'));
$serviceTypes = arrayMerge(array(0=>'-Select Service-'),dPgetSysVal('ServiceTypes'));

$incomeLevels =  dPgetSysVal('IncomeLevels');
$relocationTypes = dPgetSysVal('RelocationType');
$reasonsNotAttendingSchool = dPgetSysVal('ReasonsNotAttendingSchool');
$igaTypes = dPgetSysVal('IGAOptions');
$placementTypes = dPgetSysVal('PlacementType');
$successionPlanningTypes = dPgetSysVal('SuccessionPlanningTypes');
$legalIssues = dPgetSysVal('LegalIssues');
$nursingCareTypes = dPgetSysVal('NursingCareTypes');
$transportNeeds = dPgetSysVal('TransportNeeds');
$educationNeeds = dPgetSysVal('EducationNeeds');
$foodNeeds = dPgetSysVal('FoodNeeds');
$rentNeeds = dPgetSysVal('RentNeeds');
$solidarityNeeds = dPgetSysVal('SolidarityNeeds');
$directSupportNeeds = dPgetSysVal('DirectSupportNeeds');
$medicalSupportNeeds = dPgetSysVal('MedicalSupportNeeds');
$childSchoolLevels = dPgetSysVal('ChildSchoolLevels');
$childSchoolStatus = dPgetSysVal('ChildSchoolStatus');
$clientHealth = dPgetSysVal('ClientHealth');
$trainingSupport = dPgetSysVal('TrainingSupport');
$positionOptions = arrayMerge(array(0=>'--Select Position--'),  dPgetSysVal('PositionOptions'));

$medical_support_options = explode(",",$obj->social_medical_support);

$direct_support_options = explode(",", $obj->social_direct_support);

$solidarity_options = explode(",", $obj->social_solidarity);


$rent_options = explode(",", $obj->social_rent);

$food_options = explode(",", $obj->social_food);

$education_options = explode(",", $obj->social_education);

$transport_options = explode(",", $obj->social_transport);

$nursing_options = explode(",", $obj->social_nursing);

$legal_options = explode(",", $obj->social_legal);


$succession_planning_options = explode(",", $obj->social_succession_planning);

$placement_options = explode(",", $obj->social_placement);

$iga_options = explode(",", $obj->social_iga);

$relocation_options = explode(",", $obj->social_relocation);

// format dates
$df = $AppUI->getPref('SHDATEFORMAT');

// setup the title block

//load client

if (!empty($client_id))
{
   $clientObj = new CClient();

   if (!$clientObj->load($client_id))
   {
		$AppUI->setMsg('Client ID');
		$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
		$AppUI->redirect("?m=clients");
   }
   $client_name =  $clientObj->getFullName();
}

$client_id = $client_id ? $client_id : $obj->social_client_id;

$careInfo = array('primary'=>array(),'secondary'=>array(),'new'=>array());
$q = new DBQuery();
$q->addTable('admission_caregivers');
$q->addOrder('id desc');
$q->setLimit(1);
$q->addWhere('client_id='.$client_id);

$q1 = clone $q;
$q->addWhere('role="pri"');
if((int)$obj->social_caregiver_pri > 0){
	$q->addWhere('id='.$obj->social_caregiver_pri);
}else{
	$q->addWhere('datesoff is null');
}
$tar = $q->loadList();
//if we have not found caregiver in admission_caregiver table, then lets search in admission,
// maybe we have case of parent as caregiver
if(count($tar) === 0){
	$q3 = new DBQuery();
	$q3->addTable('admission_info');	
	$q3->setLimit(1);
	$q3->addQuery('ac.*');
	$q3->addWhere('admission_client_id='.$client_id);
	$q3->addJoin('admission_caregivers','ac','admission_caregiver_pri = ac.id');
	$q3->addWhere('ac.datesoff is null');
	$tar = $q3->loadList();
}
if(count($tar) === 1){
	$careInfo['primary']=$tar[0];
	unset($tar);
}
if((int)$obj->social_caregiver_sec > 0){
	$q1->addWhere('id='.$obj->social_caregiver_sec);
}else{
	$q1->addWhere('datesoff is null');
}
$q1->addWhere('role="sec"');
$tar = $q1->loadList();
if(count($tar) === 0){
	$q3 = new DBQuery();
	$q3->addTable('admission_info');	
	$q3->setLimit(1);
	$q3->addQuery('ac.*');
	$q3->addWhere('admission_client_id='.$client_id);
	$q3->addJoin('admission_caregivers','ac','admission_caregiver_sec = ac.id');
	$q3->addWhere('ac.datesoff is null');
	$tar = $q3->loadList();
}
if(count($tar) == 1){
	$careInfo['secondary']=$tar[0];
	unset($tar);
}

$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $social_id > 0 ? "Edit Social Visit : " . $clientObj->getFullName() : "New Social Visit: " . $clientObj->getFullName();

}
else
{
   $ttl = $social_id > 0 ? "Edit Social Visit " : "New Social Visit ";

}

//load family members
if ($client_id > 0){
	$q = new DBQuery();
	$q->addTable("household_info");
	$q->addQuery("household_info.*");
	//$q->addWhere("household_info.household_social_id = " . $social_id);
	$q->addWhere('household_client_id="'.$client_id.'"');
	$rows = $q->loadList();
}

if ($social_id > 0)
{
	$q = new DBQuery();
	$q->addTable("social_services");
	$q->addQuery("social_services.*");
	$q->addWhere("social_services.social_services_social_id = " . $social_id);
	$servicerows = $q->loadList();
}

if (!empty($client_id))
{
	$q  = new DBQuery;
	$q->addTable('admission_info');
	$q->addQuery('admission_info.*');
	$q->addWhere('admission_info.admission_client_id = '.$client_id);
	$sql = $q->prepare();
	//var_dump($sql);
	$q->clear();
	$admissionObj = new CAdmissionRecord();
	db_loadObject( $sql, $admissionObj );
}


$nhif=$clientObj->getParts('nhif');
$immun=$clientObj->getParts('immun');

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeSocial'])", "Clear All Selections" );
if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName());
/*
if ($social_id != 0)
  $titleBlock->addCrumb( "?m=social&a=view&social_id=$social_id", "View" );*/

$titleBlock->show();
?>
<style>
.new_pri,.new_sec {	display:none;}
</style>
<script language="javascript">
function submitIt() {
	var form = document.changeSocial ;
	var count = 0;
	form.household_num_rows.value = document.getElementById('family').rows.length;
	form.service_num_rows.value = document.getElementById('services').rows.length;
	if(!manField("staff_id")){
		alert("Please select Staff!");
		return false;
	}
	if(!manField("clinic_id")){
		alert("Please select Center!");
		return false;
	}	
	if (form.social_death_date && form.social_death_date.value.length > 0)
	{
		errormsg = checkValidDate(form.social_death_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid date" );
			form.social_death_date.focus();
			exit;
		}
	}
	if (form.social_entry_date && form.social_entry_date.value.length > 0)
	{
		errormsg = checkValidDate(form.social_entry_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid Entry date" );
			form.social_entry_date.focus();
			exit;
		}
	}
	 if (form.social_caregiver_age && form.social_caregiver_age.value.length > 0)
	{
		if (isNaN(parseInt(form.social_caregiver_age.value,10)) )
		{
			alert(" Invalid Age");
			form.social_caregiver_age.focus();
			exit;

		}
	}
	//validate yobs
	for (count = 1; count < document.getElementById('family').rows.length; count++)
	{
		var elementtocheck = document.getElementById('yob_'+ count)
		if (elementtocheck && elementtocheck.value.length > 0)
		{
			var lval=elementtocheck.value;
			if(checkValidDate(lval) == 0){
				var tyr=lval.split("/");
				lval=tyr[2];
				elementtocheck.value=lval;
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
	//validate service dates
	for (count = 1; count < document.getElementById('services').rows.length; count++)
	{
		var elementtocheck = document.getElementById('date_'+ count)
		if (elementtocheck && elementtocheck.value.length > 0)
		{
			errormsg = checkValidDate(elementtocheck.value);
			if (errormsg.length > 1)
			{
				alert("Invalid Service Date (Row " + count + ")" );
				elementtocheck.focus();
				exit;
			}
		}
	}
	if (form.social_permanency_value && form.social_permanency_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_permanency_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_permanency_value.focus();
			exit;

		}
	}
	if (form.social_succession_value && form.social_succession_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_succession_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_succession_value.focus();
			exit;

		}
	}
	if (form.social_legal_value && form.social_legal_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_legal_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_legal_value.focus();
			exit;

		}
	}
	if (form.social_nursing_value && form.social_nursing_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_nursing_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_nursing_value.focus();
			exit;

		}
	}
	if (form.social_transport_value && form.social_transport_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_transport_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_transport_value.focus();
			exit;

		}
	}
	if (form.social_education_value && form.social_education_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_education_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_education_value.focus();
			exit;

		}
	}
	if (form.social_food_value && form.social_food_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_food_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_food_value.focus();
			exit;

		}
	}
	if (form.social_rent_value && form.social_rent_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_rent_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_rent_value.focus();
			exit;

		}
	}
	if (form.social_solidarity_value && form.social_solidarity_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_solidarity_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_solidarity_value.focus();
			exit;

		}
	}
	if (form.social_directsupport_value && form.social_directsupport_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_directsupport_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_directsupport_value.focus();
			exit;

		}
	}
	if (form.social_medicalsupport_value && form.social_medicalsupport_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_medicalsupport_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_medicalsupport_value.focus();
			exit;

		}
	}
	if (form.social_othersupport_value && form.social_othersupport_value.value.length > 0)
	{
		if (isNaN(parseInt(form.social_othersupport_value.value,10)) )
		{
			alert(" Invalid Value");
			form.social_othersupport_value.focus();
			exit;

		}
	}
	/*if(form.social_risk_level.value < 0){
		alert("Please select Risk level");
		form.social_risk_level.focus();
		exit;
	}*/

	if($j("#prev_status").val() != $j("#new_status").val()){
		alert('As you have changed the client status consider whether a discharge or transfer form should be now completed');
	}
	form.submit();
}
// Given a tr node and row number (newid), this iterates over the row in the
// DOM tree, changing the id attribute to refer to the new row number.
function rowrenumber(newrow, newid, key){

	var oldid;
	$j(newrow)
	.find('input[name^="'+key+'"]').attr('name',function(i,x){
		oldid=x.replace(key+'_','');
		return x;
	}).end()
	.html(function(i,x){
			var xr = new RegExp('_'+oldid,"g");
			return x.replace(xr,'_'+newid);
	});

}
// Appends a row to the given table, at the bottom of the table.

function AppendRow(table_id){
  var row = document.getElementById(table_id).rows.item(1);  // 1st row
  var newid = row.parentNode.rows.length;  // Since this includes the header row, we don't need to add one
  var newrow = row.cloneNode(true);

  rowrenumber(newrow, newid,'household_id');


    // Clear out data from new row.

  $j(newrow)
  	.find("input").val("").end()
  	.find("img").remove().end()
  	.find("#yob_"+newid).attr("class","text").val("").end()
  	.find("select").val("").end()
  	.find("#delete_"+newid + " #delete_1").html("X");
  row.parentNode.appendChild(newrow);      // Attach to table
  attachPicker($j("#yob_"+newid),'');
}

function AppendServiceRow(table_id){
  var row = document.getElementById(table_id).rows.item(1);  // 1st row
  var newid = row.parentNode.rows.length;  // Since this includes the header row, we don't need to add one
  var newrow = row.cloneNode(true);

  rowrenumber(newrow, newid,'social_services_id');
  $j(newrow)
  	.find("img").remove().end()  	
  	.find("input").val("").end()
  	.find("#date_"+newid).attr("class","text").val("").end()
  	.find("select").val("").end()
  	.find("#delete_"+newid + " #delete1").html("X");
  row.parentNode.appendChild(newrow);      // Attach to table
  attachPicker($j("#date_"+newid),'');
    // Clear out data from new row.

}
// Give a node within a row of the table (one level down from the td node),
// this deletes that row, renumbers the other rows accordingly, updates
// the Grand Total, and hides the delete button if there is only one row
// left.
function DeleteRow(el){
  var row = el.parentNode.parentNode;   // tr node
  var rownum = row.rowIndex;            // row to delete
  var tbody = row.parentNode;           // tbody node
  var numrows = tbody.rows.length - 1;  // don't count header row!
  if (numrows == 1)                     // can't delete when only one row left
    return false;

  var node = row;
  tbody.removeChild(node);
  var newid = -1;



    // Loop through tr nodes and renumber - only rows numbered
    // higher than the row we just deleted need renumbering.

  row = tbody.firstChild;
  while (row) {
    if (row.tagName == 'TR') {
      newid++;
      if (newid >= rownum){
    	  var key=$j("td:eq(0)  input",row).attr("name").replace(/_\d*$/,'');
          rowrenumber(row, newid,key);
      }
    }
    row = row.nextSibling;
  }
  if (numrows == 2) {  // 2 rows before deleting - only 1 left now, so 'hide' delete button
    var delbutton = document.getElementById('delete_1');
    //delbutton.innerHTML = ' ';
  }
}

function YNflipper(){
	var x=$j("input[name='social_change']:checked").val();
	if(x == 1){
		$j(".acare").show();
	}else{
		$j(".acare").hide();
	}
}

function dataJob(obj){
	var pref=what(obj),state=$j(obj).find("input:checked").val(),nstate=true;
	if(state == "1" ){
		if(parseInt($j("#"+pref[1]+"_dbId",$tab).val()) == 0){
			$j(obj).find("input:eq(1)").attr("checked",true);
		}else{
			nstate=false;
		}
	}
	fielder(pref[1],nstate);
}

function fielder (pref,state){
	var adds='';
	if(pref != "new"){
		adds=':gt(0)';
	}
	$j("."+pref+"_class"+adds,$tab).find("input").attr("disabled",state);
}

function dws (){
	$tab=$j("#ftab");	
	var self = this,poss=['pri','sec','new'];
	$j(poss).each(function(){
		$j(this).val("2");
		dataJob($j("."+this+"_class:eq(0)",$tab));
	});
	YNflipper();
}

function turn (obj){
	var ccase=$j(obj).attr("name").match(/_(.*)_type/);
	$j("#new_block_"+ccase).show();
	dataJob($j(obj).closest("tr"));
}

function what (x){
	return x.attr("class").match(/\s(.*)_class/);
}

function olds (obj){
	var $bv=$j(obj),
		nv=$bv.val(),
		//pref=careZ[nv],
		pstr='social_caregiver_'+nv+'_change',
		choice=$j("input[name='"+pstr+"']:checked",$tab).length,
		//vpref=what(obj),
		dstate=false,
		tfv=parseInt($j("#"+nv+"_dbId").val());
		if(choice == 0 && tfv > 0) {
			alert("Please define reason of status change of previous caregiver!");
			dstate=true;
			$bv.attr("checked",false);
		}
		fielder('new',dstate);
}

</script>

<form name="changeSocial" action="?m=social" method="post">
	<input type="hidden" name="dosql" value="do_social_aed" />
	<input type="hidden" name="social_id" value="<?php echo $social_id;?>" />
	<input type="hidden" name="social_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" id="household_num_rows" name="household_num_rows" value="" />
	<input type="hidden" id="service_num_rows" name="service_num_rows" value="" />
	<input type="hidden" id="prev_status" value="<?php echo $clientObj->client_status;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td valign="top" width="100%">


<table id="ftab">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left">
		 	<?php echo arraySelect($clinics, "social_clinic_id", 'class="text" id="clinic_id"', $obj->social_clinic_id ? $obj->social_clinic_id : -1 ); ?>
         </td>
	</tr>
	<tr>
		<td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
		<td align="left">
			<?php echo  drawDateCalendar('social_entry_date',($entry_date ? $entry_date->format( $df ) : ""),false,20);?>
			&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
		</td>
	</tr>
    <tr>
    	<td align="left">1c.<?php echo $AppUI->_('Social Worker');?>:</td>
		<td align="left">
			<?php echo arraySelect( $owners, 'social_staff_id', 'id="staff_id" size="1" class="text"', @$obj->social_staff_id ? $obj->social_staff_id:-1); ?>
		</td>
    </tr>
    <tr>	
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="social_client_code" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
	 <tr>
         <td align="left">2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="social_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>       
		<tr>
         <td align="left">3a.<?php echo $AppUI->_('Type of Visit');?>:</td>
		<td>
		<?php echo arraySelectRadio($visitTypes, "social_visit_type", 'onclick=toggleButtons()', $obj->social_visit_type ? $obj->social_visit_type : -1, $identifiers ); ?>
		</td>
       </tr>
       <tr>
		<td align="left">3b.<?php echo $AppUI->_('Client Health');?>: </td>
			<td>
				<?php echo arraySelectRadio( $clientHealth, 'social_client_health', 'size="1" class="text"', @$obj->social_client_health ? $obj->social_client_health:''); ?>
			</td>
		</tr>
       <tr>
			<td align="left">4.<?php echo $AppUI->_('Client Status');?>: </td>
			<td>
				<?php echo arraySelect( $statusTypes, 'social_client_status', 'size="1" class="text" id="new_status"', @$obj->social_client_status ? $obj->social_client_status:$clientObj->client_status); ?>
			</td>
		</tr>

		
	 <!--
       <tr>
         <td align="left"><?php //echo $AppUI->_('Client Status');?>:</td>
		 <td align="left">
				<?php //echo arraySelect( $socialstatusTypes, 'social_client_status', 'size="1" class="text"', @$obj->social_client_status ? $obj->social_client_status:-1); ?>
			</td>
       </tr>
	 -->
    
	<!-- <tr>
	     <td align="left"><?php echo $AppUI->_('NHF #');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($boolTypes, "social_nhf", /*$openEdit ? '' : 'disabled="disabled"'*/'', $nhif['bool']/*$obj->social_nhf$oldSet['social_nhf']*/ , $identifiers );?>&nbsp;
		 </td>
	 </tr> -->
	 <tr>
	     <td align="left">5a...<?php echo $AppUI->_('NHF, #');?>:</td>
		 <td><input type="text" class="text" name="social_nhf" value="<?php echo dPformSafe(@/*$obj->social_nhf_y*//*$oldSet['social_nhf_y']*/ $nhif['bool']);?>" <?php echo $openEdit ? '' : /*'disabled="disabled"'*/'';?> maxlength="150" size="20"/> </td>
	 </tr>
	 <tr>
	     <td align="left">5b...<?php echo $AppUI->_('If no, why');?>:</td>
		 <td><input type="text" class="text" name="social_nhf_n" value="<?php echo dPformSafe(@/*$obj->social_nhf_n*//*$oldSet['social_nhf_n']*/$nhif['n']);?>" <?php echo $openEdit ? '' : /*'disabled="disabled"'*/'';?> maxlength="150" size="20"/> </td>
	 </tr>
	<!-- <tr>
	     <td align="left"><?php echo $AppUI->_('Immun Card.');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($boolTypes, "social_immun",/*$openEdit ? '' : 'disabled="disabled"' */ '', /*$obj->social_immun*/ /*$oldSet['social_immun']*/ $immun['bool'] , $identifiers );?>&nbsp;
		 </td>
	 </tr> -->
	 <tr>
	     <td align="left">5c...<?php echo $AppUI->_('Immun Card #');?>:</td>
		 <td><input type="text" class="text" name="social_immun" value="<?php echo dPformSafe(@/*$obj->social_immun_y*/ /*$oldSet['social_immun_y']*/$immun['bool']);?>" <?php echo $openEdit ? '' : /*'disabled="disabled"'*/ '';?>  maxlength="150" size="20"/> </td>
	 </tr>
	 <tr>
	     <td align="left">5d...<?php echo $AppUI->_('If no, why');?>:</td>
		 <td><input type="text" class="text" name="social_immun_n" value="<?php echo dPformSafe(@/*$obj->social_immun_n*/ /*$oldSet['social_immun_n']*/ $immun['n']);?>" <?php echo $openEdit ? '' : /* 'disabled="disabled"'*/'';?>  maxlength="150" size="20"/> </td>
	 </tr> 
	<tr>
	     <td align="left">6.<?php echo $AppUI->_('Any Life Events');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($boolTypes, "social_change", 'class="ynsel" onclick="YNflipper();"', $obj->social_change ? $obj->social_change : "2" , $identifiers );?>&nbsp;
		 </td>
	 </tr>
	<tr>
		<td colspan="2" align="left">
			<strong><?php echo $AppUI->_('Life Events'); ?><br /></strong>
			<hr width="500" align="left" size=1 />
		</td>
	</tr>
	 <tr class="acare">
         <td align="left">7.<?php echo $AppUI->_('Death');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($deathTypes, "social_death", 'onclick=toggleButtons()', $obj->social_death , $identifiers );?>&nbsp;
		 </td>
	 </tr>
	 <tr class="acare">
         <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		 <td align="left"><input type="text" class="text" name="social_death_notes" value="<?php echo dPformSafe(@$obj->social_death_notes);?>" maxlength="40" size="40" />
		 </td>
	 </tr>

	 <tr class="acare">
         <td align="left">7b...<?php echo $AppUI->_('Date');?>:</td>
		 <td align="left">
		 	<?php echo drawDateCalendar('social_death_date',($death_date ? $death_date->format( $df ) : "" ),false);	 ?>
		 </td>
       </tr>
       <?php
       $careHidden ='';
       	foreach ($careInfo as $cname => $cinfo){
       		if(preg_match("/^new/",$cname)){
       			$len=7;
       		}else{
       			$len=3;
       		}
       		$briefName=substr($cname,0,$len);
       		$briefClass=$briefName.'_class';
       		$careHidden.='<input type="hidden" id="'.$briefName.'_dbId" name="social_caregiver_'.$briefName.'" value="'.(int)$cinfo['id'].'">';
       		if($cname !='new'){
       ?>
	  <tr class="acare <?php echo  $briefClass?>">
        <td align="left" valign="top"><b><?php echo $AppUI->_("Change in ".$cname. " caregiver");?>:</b></td>
        <td><?php echo arraySelectRadio(/*$caretypes*/$boolTypes,'social_caregiver_'.$briefName.'_type','onchange="turn(this);"',$obj->{'social_caregiver_'.$briefName.'_type'} ? $obj->{'social_caregiver_'.$briefName.'_type'}  : 2);?>
        </td>
	  </tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		b...<?php echo $AppUI->_("Reason");?>:
		</td>
		<td>
		<?php echo arraySelectRadio($caregiverChangeTypes, "social_caregiver_".$briefName."_change", 'onclick=toggleButtons()', $obj->{'social_caregiver_'.$briefName.'_change'} ? $obj->{'social_caregiver_'.$briefName.'_change'} : -1, $identifiers );	?>
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		c...<?php echo $AppUI->_("Other");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_change_notes" value="<?php echo dPformSafe(@$obj->{'social_caregiver'.$briefName.'_change_notes'});?>" maxlength="40" size="40" />
		</td>
		</tr>
		<?php
		}else{
		?>
		<tr class="acare">
		<td><b><?php echo $AppUI->_("Role of new caregiver");?>:</b></td>
		<td><?php echo arraySelectRadio($caretypes,'caregiver_'.$briefName.'_type','onchange="olds(this);" id="careNewType"',2);?></td>
		</tr>
		<?php
		}
		?>

		<tr class="acare <?php echo $briefClass?>">
		<td>
		d...<?php echo $AppUI->_("First Name");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_fname" value="<?php echo dPformSafe( $cinfo['fname']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		e...<?php echo $AppUI->_("Last Name");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_lname" value="<?php echo dPformSafe(@$cinfo['lname']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		f...<?php echo $AppUI->_("Age");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_age" value="<?php echo dPformSafe(@$cinfo['age']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		g...<?php echo $AppUI->_("Health Status");?>:
		</td>
		<td>
            <?php echo arraySelectRadio($caregiverHealthStatus, "social_caregiver_".$briefName."_health_status", 'onclick=toggleButtons()', $cinfo['health_status'] ? $cinfo['health_status'] : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		h...<?php echo $AppUI->_("Relationship to Child");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_relationship" value="<?php echo dPformSafe(@$cinfo['relationship']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		i...<?php echo $AppUI->_("Education level");?>:
		</td>
		<td>
            <?php echo arraySelectRadio($educationLevels, "social_caregiver_".$briefName."_educ_level", 'onclick=toggleButtons()', $cinfo['educ_level'] ? $cinfo['educ_level'] : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		j...<?php echo $AppUI->_("Employment");?>:
		</td>
		<td>
		 <?php echo arraySelectRadio($employmentTypes, "social_caregiver_".$briefName."_employment", 'onclick=toggleButtons()', $cinfo['employment'] ? $cinfo['employment'] : -1, $identifiers ); ?>

		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		i...<?php echo $AppUI->_("ID #");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_idno" value="<?php echo dPformSafe(@$cinfo['idno']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
		<td>
		m...<?php echo $AppUI->_("Mobile #");?>:
		</td>
		<td>
            <input type="text" class="text" name="social_caregiver_<?php echo $briefName;?>_mobile" value="<?php echo dPformSafe(@$cinfo['mobile']);?>" maxlength="30" size="20" />
		</td>
		</tr>
		<?php
			if($cname != 'new'){
		?>
	  <tr class="acare <?php echo $briefClass?>">
        <td align="left" valign="top"><?php echo $AppUI->_("Change in health of ".$cname." caregiver");?>:</td>
	   </tr>
		<tr class="acare <?php echo $briefClass?>">
			<td>
			9a...<?php echo $AppUI->_("Health");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($caregiverHealthChanges, "social_caregiver_".$briefName."_health", 'onclick=toggleButtons()', $obj->{'social_caregiver_'.$briefName.'_health'} ? $obj->{'social_caregiver_'.$briefName.'_health'} : -1, $identifiers ); ?></td>
		</tr>
		<tr class="acare <?php echo $briefClass?>">
			<td>
			9b...<?php echo $AppUI->_("Condition is hindrance on care for the child");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($boolTypesND, "social_caregiver_".$briefName."_health_child_impact", 'onclick=toggleButtons()', $obj->{'social_caregiver_'.$briefName.'_health_child_impact'} ? $obj->{'social_caregiver_'.$briefName.'_health_child_impact'} : -1, $identifiers ); ?>
			</td>
		</tr>
	<?php
			}
		}
	?>
	   <tr>
        <td align="left" valign="top" ><b><?php echo $AppUI->_("Change of Contacts");?>:</b></td>
		</tr>
		<tr>
			<td>
			10a...<?php echo $AppUI->_("Mobile #");?>:
			</td>
			<td>
			<input type="text" class="text" name="social_residence_mobile" value="<?php echo dPformSafe(@$obj->social_residence_mobile);?>" maxlength="30" size="20" />
			<?php echo $careHidden;?>
			</td>
		</tr>
		<tr>
			<td>
			10b...<?php echo $AppUI->_("physical address/landmarks");?>:
			</td>
			<td>
				<textarea cols="70" rows="2" class="textarea" name="social_residence"><?php echo dPformSafe(@$obj->social_residence);?></textarea>
			</td>
		</tr>
   <tr>
         <td align="left" nowrap="nowrap" valign="top">11.<?php echo $AppUI->_('Change in household composition');?>:</td>
		 <td align="left" class="std">
		 <table>
		   <tr>
		    <td>
				 <table id="family">
					 <th><?php echo $AppUI->_('Name');?></th>
					 <th><?php echo $AppUI->_('Year of Birth');?></th>
					 <th><?php echo $AppUI->_('Gender');?></th>
					 <th><?php echo $AppUI->_('Relationship to child');?></th>
					 <!-- <th><?php echo $AppUI->_('If registered, ADM #');?></th> -->
					 <th><?php echo $AppUI->_('Comments');?></th>
					 <th>&nbsp;</th>

					 <?php
					 $rowcount = 1;
					 if (count($rows) > 0 )
					 {
						foreach ($rows as $row)
						{
							/*if(!is_null($row['household_yob']) && (int)$row['household_yob'] > 0){
								$row['household_yob']='01/11/'.(int)$row['household_yob'];
							}*/

					 ?>
					 <tr>
						 <td align="left">
						 <input type="hidden" name="household_id_<?php echo $rowcount; ?>" value="<?php echo @$row["household_id"]?>" />
						 <input type="text" class="text" id="name_<?php echo $rowcount; ?>" name="name_<?php echo $rowcount; ?>" value="<?php echo @$row["household_name"]?>" maxlength="150" size="20" />
						 </td>
						 <td align="left">
						 	<?php
						 	echo drawDateCalendar('yob_'.$rowcount,($row['household_yob'] != '' ? 'new Date(\''.$row['household_yob'].'\',1,1)' : '' ),false,'id="yob_'.$rowcount.'"',true);						 	
						 	?>

						 </td>
						 <td align="left">
						 <?php echo arraySelect( $genderTypes, "gender_$rowcount", 'size="1" class="text" id="gender_'.$rowcount.'"', @$row["household_gender"] ); ?>

						 </td>
						 <td align="left"><input type="text" class="text" id="relationship_<?php echo $rowcount; ?>" name="relationship_<?php echo $rowcount; ?>" value="<?php echo @$row["household_relationship"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="notes_<?php echo $rowcount; ?>" name="notes_<?php echo $rowcount; ?>" value="<?php echo @$row["household_notes"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="custom_<?php echo $rowcount; ?>" name="custom_<?php echo $rowcount; ?>" value="<?php echo @$row["household_custom"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					 <?php
							$rowcount++;
						} //end for
					  }
					  else
					  {
					  ?>
					  			 <tr>
						 <td align="left">
						 <input type="hidden" name="household_id_<?php echo $rowcount; ?>" value="<?php echo @$row["household_id"]?>" />
						 <input type="text" class="text" id="name_<?php echo $rowcount; ?>" name="name_<?php echo $rowcount; ?>" value="<?php echo @$row["household_name"]?>" maxlength="150" size="20" />
						 </td>
						 <td align="left">
						 	<?php
						 	echo drawDateCalendar('yob_'.$rowcount,@$row['household_yob'],false,'id="yob_'.$rowcount.'"',true);
						 	//<input type="text" class="text" id="yob_<?php echo $rowcount; " name="yob_<?php echo $rowcount; " value="<?php echo @$row["household_yob"];" maxlength="150" size="20" />
						 	?>

						 </td>
						 <td align="left">
						 	<?php echo arraySelect( $genderTypes, "gender_$rowcount", 'size="1" class="text" id="gender_'.$rowcount.'"', @$row["household_gender"] ); ?>
						 </td>
						 <td align="left"><input type="text" class="text" id="relationship_<?php echo $rowcount; ?>" name="relationship_<?php echo $rowcount; ?>" value="<?php echo @$row["household_relationship"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="notes_<?php echo $rowcount; ?>" name="notes_<?php echo $rowcount; ?>" value="<?php echo @$row["household_notes"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					  <?php
					  }//end if
					 ?>
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
        <td align="left" valign="top"><?php echo $AppUI->_("Change in household income level");?>:</td>
        </tr>
		<tr>
			<td>
			12a...<?php echo $AppUI->_("Change due to employment type of primary caregiver");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "social_caregiver_employment_change", 'onclick=toggleButtons()', $obj->social_caregiver_employment_change ? $obj->social_caregiver_employment_change : -1, $identifiers ); ?>
			</td>

		</tr>
		<tr>
			<td>
			12b...<?php echo $AppUI->_("If yes, new employment");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($employmentTypes, "social_caregiver_new_employment", 'onclick=toggleButtons()', $obj->social_caregiver_new_employment ? $obj->social_caregiver_new_employment : -1, $identifiers ); ?>
			</td>
		</tr>
		<tr>
			<td>
			...<?php echo $AppUI->_("Other");?>:
			</td>
			<td>
			<input type="text" class="text" name="social_caregiver_new_employment_desc" value="<?php echo dPformSafe(@$obj->social_caregiver_new_employment_desc);?>" maxlength="40" size="40" />
			</td>
		</tr>
		<tr>
			<td>
			12c...<?php echo $AppUI->_("New income range");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($incomeLevels, "social_caregiver_income", 'onclick=toggleButtons()', $obj->social_caregiver_income ? $obj->social_caregiver_income : -1, $identifiers );
			?>
			</td>
		</tr>
		
	   <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in schooling");?>:</td>
        </tr>
		<tr>
			<td>
			13a...<?php echo $AppUI->_("Attendance");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($childSchoolStatus, "social_school_attendance", 'onclick=toggleButtons()', $obj->social_school_attendance ? $obj->social_school_attendance : -1, $identifiers ); ?>

			</td>
		</tr>
		<tr>
			<td>
			13b...<?php echo $AppUI->_("New school level");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($childSchoolLevels, "social_school", 'onclick=toggleButtons()', $obj->social_school ? $obj->social_school : -1, $identifiers ); ?></td>
		</tr>
		<tr>
			<td>
			13c...<?php echo $AppUI->_("Current class / form");?>:
			</td>
			<td>
				<input type="text" class="text" name="social_class_form" value="<?php echo dPformSafe(@$obj->social_class_form);?>" maxlength="40" size="40" />
			</td>

		</tr>
		<tr>
			<td>
			13d...<?php echo $AppUI->_("If not attending, why");?>:
			</td>
			<td>
			<?php echo arraySelectRadio($reasonsNotAttendingSchool, "social_reason_not_attending", 'onclick=toggleButtons()', $obj->social_reason_not_attending ? $obj->social_reason_not_attending : -1, $identifiers ); ?></td>
		</tr>
		<tr>
			<td>
			13e...<?php echo $AppUI->_("Other");?>:
			</td>
			<td>
				<input type="text" class="text" name="social_reason_not_attending_notes" value="<?php echo dPformSafe(@$obj->social_reason_not_attending_notes);?>" maxlength="40" size="40" />
			</td>
		</tr>
		

	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs supported'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

   <tr>
         <td align="left" nowrap="nowrap" valign="top">14.<?php echo $AppUI->_('Services Rendered');?>:</td>
		 <td align="left" class="std">
		 <table>
		   <tr>
		    <td>
				 <table id="services">
					 <th><?php echo $AppUI->_('Service');?></th>
					 <th><?php echo $AppUI->_('Service (old)');?></th>
					 <th><?php echo $AppUI->_('Date ');?></th>
					 <th><?php echo $AppUI->_('Comments');?></th>
					 <th><?php echo $AppUI->_('Value');?></th>
					 <th>&nbsp;</th>

					 <?php
					 $rowcount = 1;
					 $serviceOld=dPgetSysVal('ServiceRenderedOld');
					 if (count($servicerows) > 0 )
					 {
						foreach ($servicerows as $servicerow)
						{
						//var_dump($servicerow);
					 ?>
					 <tr>
						 <td align="left">
						 <input type="hidden" name="social_services_id_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_id"]?>" />
						 <?php echo arraySelect( $serviceTypes, "service_$rowcount", 'size="1" class="text" id="service_'.$rowcount.'"', @$servicerow["social_services_service_id"] ); ?>
						 </td>
						 <td align="left">						 
						 	<?php echo @$serviceOld[$servicerow["social_services_service_id"]]; ?>&nbsp;
						 </td>
						 <td align="left">
						 <?php
						 	$service_date = new CDate( @$servicerow["social_services_date"] );
						 	echo drawDateCalendar('date_'.$rowcount,@$service_date->format( $df ),false,'id="date_'.$rowcount.'"');
						 	//<input type="text" class="text" id="date_<?php echo $rowcount; " name="date_<?php echo $rowcount; " value="<?php $service_date = new CDate( @$servicerow["social_services_date"] );  echo  @$service_date->format( $df );" maxlength="150" size="20" />
						 ?>

						 	</td>
						 <td align="left"><input type="text" class="text" id="serv_notes_<?php echo $rowcount; ?>" name="serv_notes_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_notes"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="value_<?php echo $rowcount; ?>" name="value_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_value"];?>" maxlength="150" size="5" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					 <?php
							$rowcount++;
						} //end for
					  }
					  else
					  {

					  ?>
					  <tr>
						 <td align="left">
						 <input type="hidden" name="social_services_id_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_id"]?>" />
						 <?php echo arraySelect( $serviceTypes, "service_$rowcount", 'size="1" class="text" id="service_'.$rowcount.'"', @$servicerow["social_services_service_id"] ); ?>
						 </td>
						 <td align="left">&nbsp;</td>
						 <td align="left">
						 <?php
						 	intval (@$servicerow["social_services_date"]) > 0 ? $service_date = new CDate( @$servicerow["social_services_date"] ) : NULL;  $vval= ($service_date) ? $service_date->format( $df ) : "";
						 	echo drawDateCalendar('date_'.$rowcount,$vval,false,'id="date_'.$rowcount.'"');
						 	//<input type="text" class="text" id="date_<?php echo $rowcount; " name="date_<?php echo $rowcount; " value="<?php intval (@$servicerow["social_services_date"]) > 0 ? $service_date = new CDate( @$servicerow["social_services_date"] ) : NULL;  echo  ($service_date) ? $service_date->format( $df ) : "";" maxlength="150" size="20" /></td>
						 ?>
						 <td align="left"><input type="text" class="text" id="serv_notes_<?php echo $rowcount; ?>" name="serv_notes_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_notes"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="value_<?php echo $rowcount; ?>" name="value_<?php echo $rowcount; ?>" value="<?php echo @$servicerow["social_services_value"];?>" maxlength="150" size="5" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					  <?php
					  }//end if
					 ?>
				</table>
			  </td>
            </tr>
		 <tr>
			<td>
				<input class="button" type="button" name="append" value="new entry" onclick="AppendServiceRow('services'); return false;"/>
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
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs assessment'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr >
        <td align="left" valign="top">15.<b><?php echo $AppUI->_("Any needs");?>:</b></td>
        <td><?php echo arraySelectRadio($boolTypes,'social_any_needs','',$obj->{'social_any_needs'} ? $obj->{'social_any_needs'}  : -1);?>
        </td>
	  </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Permanency Planning');?>:</td>
	  </tr>
	 <tr>
			<td>
			16a...<?php echo $AppUI->_("Relocation");?>:
			</td>
			<td>
			<?php echo arraySelectCheckbox($relocationTypes, "social_relocation[]", NULL, $relocation_options ); ?>
			</td>
	</tr>
	<tr>
			<td>
			16b...<?php echo $AppUI->_("IGA");?>:
			</td>
			<td>
	        	<?php echo arraySelectCheckbox($igaTypes, "social_iga[]", NULL, $iga_options ); ?>

			</td>

	</tr>
	<tr>
			<td>
			16c...<?php echo $AppUI->_("Placement");?>:
			</td>
			<td>
	          <?php echo arraySelectCheckbox($placementTypes, "social_placement[]", NULL, $placement_options ); ?>
			</td>

	</tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_permanency_value" value="<?php echo dPformSafe(@$obj->social_permanency_value);?>" maxlength="30" size="20" />

			</td>

	</tr>
	<tr>
			<td align="left">17.<?php echo $AppUI->_('Succession Planning');?>:</td>
			<td align="left" valign="top">
	          <?php echo arraySelectCheckbox($successionPlanningTypes, "social_succession_planning[]", NULL, $succession_planning_options ); ?>
			</td>
      </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_succession_value" value="<?php echo dPformSafe(@$obj->social_succession_value);?>" maxlength="30" size="20" />

			</td>

	</tr>
	  <tr>
			<td align="left">18.<?php echo $AppUI->_('Legal');?>:</td>
			<td align="left" valign="top">
				          <?php echo arraySelectCheckbox($legalIssues, "social_legal[]", NULL, $legal_options ); ?>
			</td>
      </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_legal_value" value="<?php echo dPformSafe(@$obj->social_legal_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
	  <tr>
			<td align="left">19.<?php echo $AppUI->_('Nursing/Palliative Care');?>:</td>
			<td align="left" valign="top">
			<?php echo arraySelectCheckbox($nursingCareTypes, "social_nursing[]", NULL, $nursing_options ); ?>
			</td>
      </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_nursing_value" value="<?php echo dPformSafe(@$obj->social_nursing_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
	  <tr>
			<td align="left">20.<?php echo $AppUI->_('Transport');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectCheckbox($transportNeeds, "social_transport[]", NULL, $transport_options ); ?>
			</td>
      </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_transport_value" value="<?php echo dPformSafe(@$obj->social_transport_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">21.<?php echo $AppUI->_('Education');?>:</td>
		<td>
		<?php echo arraySelectCheckbox($educationNeeds, "social_education[]", NULL, $education_options ); ?>
		</td>
       </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_education_value" value="<?php echo dPformSafe(@$obj->social_education_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">22.<?php echo $AppUI->_('Food');?>:</td>
		<td>
		<?php echo arraySelectCheckbox($foodNeeds, "social_food[]", NULL, $food_options ); ?>
		</td>
       </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_food_value" value="<?php echo dPformSafe(@$obj->social_food_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">23.<?php echo $AppUI->_('Rent');?>:</td>
		<td>
			<?php echo arraySelectCheckbox($rentNeeds, "social_rent[]", NULL, $rent_options ); ?>
		</td>
       </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_rent_value" value="<?php echo dPformSafe(@$obj->social_rent_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">24.<?php echo $AppUI->_('Solidarity');?>:</td>
		<td>
			<?php echo arraySelectCheckbox($solidarityNeeds, "social_solidarity[]", NULL, $solidarity_options ); ?>
		</td>
       </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_solidarity_value" value="<?php echo dPformSafe(@$obj->social_solidarity_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">25.<?php echo $AppUI->_('Direct Support');?>:</td>
		<td>
		  <?php echo arraySelectCheckbox($directSupportNeeds, "social_direct_support[]", NULL, $direct_support_options ); ?>
		</td>
       </tr>
       <tr>
         <td align="left">25b.<?php echo $AppUI->_('Direct Support - other');?>:</td>
		<td>
			<input type="text" class="text" name="social_direct_support_desc" value="<?php echo dPformSafe(@$obj->social_direct_support_desc);?>" maxlength="30" size="20" />
		</td>
       </tr>
       
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_directsupport_value" value="<?php echo dPformSafe(@$obj->social_directsupport_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">26.<?php echo $AppUI->_('Medical Support');?>:</td>
		<td>
		   <?php echo arraySelectCheckbox($medicalSupportNeeds, "social_medical_support[]", NULL, $medical_support_options ); ?>
		</td>
       </tr>
       <tr>
			<td>
			...<?php echo $AppUI->_("Medical Support Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_medicalsupport_value" value="<?php echo dPformSafe(@$obj->social_medicalsupport_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
       <tr>
        <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		<td>
		<input type="text" class="text" name="social_medical_support_desc" value="<?php echo dPformSafe(@$obj->social_medical_support_desc);?>" maxlength="40" size="40" />
		</td>
       </tr>
       <tr>
         <td align="left">27.<?php echo $AppUI->_('Training Support');?>:</td>
		<td>
		   <?php echo arraySelectRadio($trainingSupport, "social_training",'onclick=toggleButtons()', $obj->social_training ? $obj->social_training : -1, $indentifiers ); ?>
		</td>
       </tr>
       <tr>
        <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		<td>
		<input type="text" class="text" name="social_training_desc" value="<?php echo dPformSafe(@$obj->social_training_desc);?>" maxlength="40" size="40" />
		</td>
       </tr>
		<tr>
        <td align="left">...<?php echo $AppUI->_('Training Support - Value');?>:</td>
		<td>
		<input type="text" class="text" name="social_training_value" value="<?php echo dPformSafe(@$obj->social_training_value);?>" maxlength="40" size="40" />
		</td>
       </tr>
	   <tr>
        <td align="left">28.<?php echo $AppUI->_('Other Needs Assessed');?>:</td>
		<td>
		<input type="text" class="text" name="social_other_support" value="<?php echo dPformSafe(@$obj->social_other_support);?>" maxlength="40" size="40" />
		</td>
       </tr>
	<tr>
			<td>
			...<?php echo $AppUI->_("Value");?>:
			</td>
			<td>
	           <input type="text" class="text" name="social_othersupport_value" value="<?php echo dPformSafe(@$obj->social_othersupport_value);?>" maxlength="30" size="20" />

			</td>
	</tr>
		<tr>
         <td align="left">29a.<?php echo $AppUI->_('New Risk Level');?>:</td>
		<td>
		<?php echo arraySelect($riskLevels, "social_risk_level", 'class="text"', $obj->social_risk_level  ? $obj->social_risk_level : -1, $identifiers ); ?>
		</td>
       </tr>
       <tr>
         <td align="left">29b.<?php echo $AppUI->_('Next Appointment Date');?>:</td>
		<td>
		<?php echo drawDateCalendar('social_next_visit',$next_date ? $next_date->format( $df ) : "",false); ?>
		</td>
       </tr>
       <tr>
         <td align="left">30.<?php echo $AppUI->_('Referral to');?>:</td>
		<td>
		<?php echo arraySelect($positionOptions, "social_referral", 'class="text"', $obj->social_referral  ? $obj->social_referral : '', $identifiers );?>
		</td>
       </tr>
	   <tr>
	   <td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->social_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
       </tr>
		<tr>
		 <td align="left" valign="top">31.<?php echo $AppUI->_('Comments');?>:</td>
<td valign="top">
		<textarea cols="70" rows="2" class="textarea" name="social_notes"><?php echo @$obj->social_notes;?></textarea>
		</td>
		</tr>

	   </table>
</td>


</td>
</tr>


<tr>
	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>

</table>
</form>

<script>
window.onload = dws;
var $tab;
</script>
