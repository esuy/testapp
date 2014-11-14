<?php 
$medical_id = intval( dPgetParam( $_GET, "medical_id", 0 ) );

$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('social'));
require_once ($AppUI->getModuleClass('counsellinginfo'));
require_once ($AppUI->getModuleClass('admission'));
// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($medical_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $medical_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


// load the record data
$q  = new DBQuery;
$q->addTable('medical_assessment');
$q->addQuery('medical_assessment.*');
$q->addWhere('medical_assessment.medical_id = '.$medical_id);
$sql = $q->prepare();

$obj = new CMedicalAssessment();
if (!db_loadObject( $sql, $obj ) && $admission_id > 0) 
{
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
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();


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
}

if (!empty($client_id))
{
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



$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$bornTypes = dPgetSysVal('BirthTypes');
$df = $AppUI->getPref('SHDATEFORMAT');
$genderTypes = dPgetSysVal('GenderType');
$immunizationStatus = dPgetSysVal('ImmunizationStatus');
$hivStatus = dPgetSysVal('HIVStatusTypes');
$managementhivStatus = dPgetSysVal('ManagementHIVStatusTypes');
$malnutritionType = dPgetSysVal('MalnutritionType');
$arvTreatmentTypes = dPgetSysVal('ARVTreatmentTypes');
$educProgressType = dPgetSysVal('EducationProgressType');
$motorAbilityType = dPgetSysVal('MotorAbilityType');
$dehydrationType = dPgetSysVal('DehydrationType');
$lymphType = dPgetSysVal('LymphType');
$tbPulmonaryTypes = dPgetSysVal('TBPulmonaryType');
$tbTypes = dPgetSysVal('TBType');
$throatType = dPgetSysVal('ThroatType');
$earType = dPgetSysVal('EarType');
$teethType = dPgetSysVal('TeethType');
$percussionType = dPgetSysVal('PercussionType');
$breathType = dPgetSysVal('BreathSoundsType');
$soundsType = dPgetSysVal('SoundsType');
$apexType = dPgetSysVal('NormalDisplacedType');
$precordialType = dPgetSysVal('NormalIncreasedType');
$femoralType = dPgetSysVal('FemoralPulseType');
$heartSoundType = dPgetSysVal('HeartType');
$toneType = dPgetSysVal('NormalIncReducedType');
$tendonLegsType = dPgetSysVal('NormalIncReducedType');
$tendonArmsType = dPgetSysVal('NormalIncReducedType');
$palpableType = dPgetSysVal('PalpableTypes');
$directionType = dPgetSysVal('DirectionTypes');
$umbilicalType = dPgetSysVal('UmbilicalTypes');
$conditionType = dPgetSysVal('ConditionType');
$femaleConditionType = dPgetSysVal('FemaleConditionTypes');
$examinationType = dPgetSysVal('ExaminationType');
$penisTypes = dPgetSysVal('PenisTypes');
$developmentType = dPgetSysVal('DevelopmentTypes');
$enlargementType = dPgetSysVal('EnlargementTypes');
$eyeType = dPgetSysVal('EyeStatusTypes');
$feelType = dPgetSysVal('FeelTypes');
$motorType = dPgetSysVal('MotorTypes');
$cnsType = dPgetSysVal('CNSType');

$refers=arrayMerge(array(-1=>'-- Select --'),dPgetSysVal('PositionOptions'));


$whostages = array(1=>'1st',2=>'2nd',3=>'3rd',4=>'4th');
$immunostage= $whostages;
array_pop($immunostage);

$investigations = dPgetSysVal('RequestInvestigations');
$positions = dPgetSysVal('PositionOptions');




//load medical history
if ($medical_id > 0)
{
	$q = new DBQuery();
	$q->addTable("medical_history");
	$q->addQuery("medical_history.*");
	$q->addWhere("medical_history.medical_history_medical_id = " . $obj->medical_id);
	$rows = $q->loadList();
}

//load medications history
if ($medical_id > 0)
{
	$q = new DBQuery();
	$q->addTable("medications_history");
	$q->addQuery("medications_history.*");
	$q->addWhere("medications_history.medications_history_medical_id = " . $obj->medical_id);
	$medications = $q->loadList();
}
// setup the title block

//load client
$rowcount = 0;
$client_id = $client_id ? $client_id : $obj->medical_client_id;


$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $admission_id > 0 ? "Edit Medical Assessment Record : " . $clientObj->getFullName() : "New Medical Assessment Record: " . $clientObj->getFullName();

}
else
{
   $ttl = $admission_id > 0 ? "Edit Medical Assessment Record " : "New Medical Assessment Record ";

}

$age_years = 0;
$age_months = 0;
$age_years = $obj->medical_age_yrs;
$age_months = $obj->medical_age_months;

if ($medical_id == 0)
{
  if (isset($clientObj))	
  {
	$clientObj->getAge($age_years,$age_months);
  }
}

$date_reg = date("Y-m-d");
$medical_conditions = explode(",", $obj->medical_conditions);
$medical_lymph = explode(",", $obj->medical_lymph);
$motor_ability = explode(",", $obj->medical_sensory_motor_ability);
$entry_date = intval( $obj->medical_entry_date) ? new CDate($obj->medical_entry_date ) : new CDate($date_reg );
$next_date =  $obj->medical_next_visit ? new CDate($obj->medical_next_visit)  : '';
$medical_tb_date_diagnosed = intval( $obj->medical_tb_date_diagnosed ) ? new CDate( $obj->medical_tb_date_diagnosed ) : NULL;
$medical_tb_date1 = intval( $obj->medical_tb_date1 ) ? new CDate( $obj->medical_tb_date1 ) : NULL;
$medical_tb_date2 = intval( $obj->medical_tb_date2 ) ? new CDate( $obj->medical_tb_date2 ) : NULL;
$medical_tb_date3 = intval( $obj->medical_tb_date3 ) ? new CDate( $obj->medical_tb_date3 ) : NULL;
$medical_arv2_startdate = intval( $obj->medical_arv2_startdate ) ? new CDate( $obj->medical_arv2_startdate ) : NULL;
$medical_arv2_enddate = intval( $obj->medical_arv2_enddate ) ? new CDate( $obj->medical_arv2_enddate ) : NULL;
$medical_arv1_startdate = intval( $obj->medical_arv1_startdate ) ? new CDate( $obj->medical_arv1_startdate ) : NULL;
$medical_arv1_enddate = intval( $obj->medical_arv1_enddate ) ? new CDate( $obj->medical_arv1_enddate ) : NULL;
$medical_salvage_startdate = intval( $obj->medical_salvage_startdate ) ? new CDate( $obj->medical_salvage_startdate ) : NULL;
$medical_salvage_enddate = intval( $obj->medical_salvage_enddate) ? new CDate( $obj->medical_salvage_enddate ) : NULL;
$client_age = $clientObj->age();

$boolRev = dPgetSysVal('NoYes');

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeMedical'])", "Clear All Selections" );
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
	var form = document.changeMedical;
	var count = 0;
	form.medical_num_rows.value = document.getElementById('history').rows.length;
	form.drugs_num_rows.value = document.getElementById('drugs').rows.length;
		
	if(!manField("staff_id")){
		alert("Please select valid Clinician!");
		return;
	}

	if(!manField("clinic_id")){
		alert("Please select valid Center!");
		return;
	}
	
	if (form.medical_entry_date && form.medical_entry_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_entry_date.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.medical_entry_date.focus();
			exit;
		}
	}	
	if (form.medical_age_yrs && form.medical_age_yrs.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_age_yrs.value,10)) )
		{
			alert(" Invalid Age (years)");
			form.medical_age_yrs.focus();
			exit;
			
		}
	}
	if (form.medical_age_months && form.medical_age_months.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_age_months.value,10)) )
		{
			alert(" Invalid Age (months)");
			form.medical_age_months.focus();
			exit;

		}
	}	
	if (form.medical_birth_weight && form.medical_birth_weight.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_birth_weight.value,10)) )
		{
			alert(" Invalid Birth Weight");
			form.medical_birth_weight.focus();
			exit;

		}
	}		
	if (form.medical_bf_duration && form.medical_bf_duration.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_bf_duration.value,10)) )
		{
			alert(" Invalid BF Duration");
			form.medical_bf_duration.focus();
			exit;

		}
	}	
	if (form.medical_no_siblings_alive && form.medical_no_siblings_alive.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_no_siblings_alive.value,10)) )
		{
			alert(" Invalid No of siblings alive");
			form.medical_no_siblings_alive.focus();
			exit;

		}
	}	
	if (form.medical_no_siblings_deceased && form.medical_no_siblings_deceased.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_no_siblings_deceased.value,10)) )
		{
			alert(" Invalid No of siblings deceased");
			form.medical_no_siblings_deceased.focus();
			exit;

		}
	}		
	//validate medical history dates
	for (count = 1; count < document.getElementById('history').rows.length; count++)
	{
		var elementtocheck = document.getElementById('date_'+ count)
		if (elementtocheck && elementtocheck.value.length > 0) 
		{
			errormsg = checkValidDate(elementtocheck.value);
		
			if (errormsg.length > 1)
			{
				alert("Invalid date (Row " + count + ")" );
				elementtocheck.focus();
				exit;
			}
		}
	}
	if (form.medical_tb_date1 && form.medical_tb_date1.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_tb_date1.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid TB course of treatment date 1" );
			form.medical_tb_date1.focus();
			exit;
		}
	}
	if (form.medical_tb_date2 && form.medical_tb_date2.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_tb_date2.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid TB course of treatment date 2" );
			form.medical_tb_date2.focus();
			exit;
		}
	}
	if (form.medical_tb_date3 && form.medical_tb_date3.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_tb_date3.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid TB course of treatment date 2" );
			form.medical_tb_date3.focus();
			exit;
		}
	}
	if (form.medical_arv1_startdate && form.medical_arv1_startdate.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_arv1_startdate.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid ARV 1st line start date" );
			form.medical_arv1_startdate.focus();
			exit;
		}
	}	
		if (form.medical_arv1_enddate && form.medical_arv1_enddate.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_arv1_enddate.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid ARV 1st line end date" );
			form.medical_arv1_enddate.focus();
			exit;
		}
	}
	if (form.medical_arv2_startdate && form.medical_arv2_startdate.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_arv2_startdate.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid ARV 2nd line start date" );
			form.medical_arv2_startdate.focus();
			exit;
		}
	}
	if (form.medical_arv2_enddate && form.medical_arv2_enddate.value.length > 0) 
	{
		errormsg = checkValidDate(form.medical_arv2_enddate.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid ARV 2nd line end date" );
			form.medical_arv2_enddate.focus();
			exit;
		}
	}
	if (form.medical_weight && form.medical_weight.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_weight.value,10)) )
		{
			alert(" Invalid Weight");
			form.medical_weight.focus();
			exit;

		}
	}
	if (form.medical_height && form.medical_height.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_height.value,10)) )
		{
			alert(" Invalid Height");
			form.medical_height.focus();
			exit;

		}
	}
	/*if (form.medical_zscore && form.medical_zscore.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_zscore.value,10)) )
		{
			alert(" Invalid z Score");
			form.medical_zscore.focus();
			exit;

		}
	}*/
	if (form.medical_muac && form.medical_muac.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_muac.value,10)) )
		{
			alert(" Invalid MUAC");
			form.medical_muac.focus();
			exit;

		}
	}
	if (form.medical_hc && form.medical_hc.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_hc.value,10)) )
		{
			alert(" Invalid Head Circumference");
			form.medical_hc.focus();
			exit;

		}
	}	
	if (form.medical_temp && form.medical_temp.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_temp.value,10)) )
		{
			alert(" Invalid Temperature");
			form.medical_temp.focus();
			exit;

		}
	}	
	if (form.medical_heartrate && form.medical_heartrate.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_heartrate.value,10)) )
		{
			alert(" Invalid Heart Rate");
			form.medical_heartrate.focus();
			exit;

		}
	}	
	if (form.medical_pulserate && form.medical_pulserate.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_pulserate.value,10)) )
		{
			alert(" Invalid Pulse Rate");
			form.medical_pulserate.focus();
			exit;

		}
	}	
	if (form.medical_temp && form.medical_temp.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_temp.value,10)) )
		{
			alert(" Invalid Temperature");
			form.medical_temp.focus();
			exit;

		}
	}	
	if (form.medical_liver_costal && form.medical_liver_costal.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_liver_costal.value,10)) )
		{
			alert(" Invalid Costal (liver)");
			form.medical_liver_costal.focus();
			exit;

		}
	}	
	if (form.medical_spleen_costal && form.medical_spleen_costal.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_spleen_costal.value,10)) )
		{
			alert(" Invalid Costal (spleen)");
			form.medical_spleen_costal.focus();
			exit;

		}
	}	
	if (form.medical_cd4 && form.medical_cd4.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_cd4.value,10)) )
		{
			alert(" Invalid CD4 value");
			form.medical_cd4.focus();
			exit;

		}
	}	
	if (form.medical_cd4_percentage && form.medical_cd4_percentage.value.length > 0) 
	{
		if (isNaN(parseInt(form.medical_cd4_percentage.value,10)) )
		{
			alert(" Invalid CD4% Value");
			form.medical_cd4_percentage.focus();
			exit;

		}
	}

	if(form.medical_referral.value == -1){
		$j("#botm_refs").attr("disabled",true);
	}	

	form.submit();
}
// Given a tr node and row number (newid), this iterates over the row in the
// DOM tree, changing the id attribute to refer to the new row number.
function rowrenumber(newrow, newid, key)
{
	var oldid;
	$j(newrow)
	/*.find('input[name^="'+key+'"]').attr('name',function(i,x){
		oldid=x.replace(key+'_','');
		return x;
	}).end()*/
	.find("input[type='text']:eq(0)").attr("name",function(i,x){
		oldid = x.match(/(\d+)$/);
	}).end()
	.html(function(i,x){
			var xr = new RegExp('_'+oldid[0],"g");			
			return x.replace(xr,'_'+newid);
	});

}
/*
function rowrenumber(newrow, newid)
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
}
*/
// Appends a row to the given table, at the bottom of the table.
var rcount=[];
function AppendRow(table_id,hId){

	var $tbody = $j("#"+table_id).find("tbody"),
	curamnt=$j("tr",$tbody).length,preind=0;
	if(!rcount[table_id] && isNaN(rcount[table_id])){
		rcount[table_id] = curamnt+1;
	}else{
		++rcount[table_id];
	}
	
	
	/*if((curamnt === 6 && table_id === 'drugs') || (curamnt === 3 && table_id ==='history')){
		return false;
	}
	if(table_id === 'drugs'){
		preind = 23;
	}else if(table_id === 'history'){
		preind = 13;
	}*/

	var row = $j("tr:eq(0)",$tbody);  // 1st row
	var newid = rcount[table_id];  // Since this includes the header row, we don't need to add one
	var newrow = $j(row).clone(true);  
  
  rowrenumber(newrow, newid);

  $j(newrow)
	//.find(".nmb").text(preind + curamnt).end()
  	.find("input").val("").end()
  	.find("img").remove().end()
  	.find("#date_"+newid).attr("class",'text').end()
  	.find("#delete_"+newid+" #delete_1").html();	  
  //row.parentNode.appendChild(newrow);      // Attach to table
  $tbody.append(newrow);      // Attach to table
    // Clear out data from new row.
	/*$j("tr",$tbody).each(function(ind){
		rowrenumber(this,(ind+1));
	});*/
	//if(table_id === 'history'){
		 attachPicker($j("#date_"+newid),'');
	//}    
}

function NewHistoryRow(table_id,hId){
  var row = document.getElementById(table_id).rows.item(1);  // 1st row
  var newid = row.parentNode.rows.length;  // Since this includes the header row, we don't need to add one
  var newrow = row.cloneNode(true);
  
  rowrenumber(newrow, newid,hId);
  $j(newrow)
	.find("img").remove().end()
	.find("input").val("").end()
	.find("#date_"+newid).attr("class",'text').end()
	.find("#delete_"+newid+" #delete_1").html();	  
 row.parentNode.appendChild(newrow);      // Attach to table
 attachPicker($j("#date_"+newid),'');
 // Clear out data from new row.
	
}

// Give a node within a row of the table (one level down from the td node),
// this deletes that row, renumbers the other rows accordingly, updates
// the Grand Total, and hides the delete button if there is only one row
// left.
function DeleteRow(el){
  var row = el.parentNode.parentNode,   // tr node
	rownum = row.rowIndex,            // row to delete
	tbody = row.parentNode,           // tbody node
	ctable = tbody.parentNode,
	numrows = tbody.rows.length - 1,
	preind = 0;
  if (numrows == 0)                     // can't delete when only one row left
    return false;

	/*var table_id = $j(ctable).attr("id");
	if(table_id === 'drugs'){
		preind = 23;
	}else if(table_id === 'history'){
		preind = 13;
	}*/
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
  var $tbody=$j(tbody);
	/*  $j("tr",$tbody).each(function(i){
  			$j(this).find("span.nmb").text(preind + i);
			rowrenumber(this,(i+1));
  	});	*/
}
</script>

<form name="changeMedical" action="?m=medical" method="post">
  <input type="hidden" name="dosql" value="do_medical_aed"/>
  <input type="hidden" name="del" value="0" />
  <input type="hidden" name="medical_unique_update" value="<?php echo uniqid("");?>" />
  <input type="hidden" name="medical_id" value="<?php echo $obj->medical_id;?>" />
  <input type="hidden" name="medical_client_id" value="<?php echo $client_id;?>" />
  <input type="hidden" name="medical_num_rows" value="0" />
  <input type="hidden" name="drugs_num_rows" value="0" />
  <input type="hidden" name="medical_age_yrs" value="<?php echo  intval(@$client_age[0]);?>">
  <input type="hidden" name="medical_age_months" value="<?php echo  intval(@$client_age[1]);?>">
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
          <?php echo arraySelect($clinicArray, "medical_clinic_id", 'class="text" id="center_id"', $obj->medical_clinic_id ); ?>
         </td>
	</tr>
	<tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left">
			<?php 
				echo drawDateCalendar("medical_entry_date",$entry_date ? $entry_date->format( $df ) : "" ,false);
				//<input type="text" name="medical_entry_date" class="text" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;" class="text"  />
			?>			
			&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
			</td>
       </tr>  
	<tr>
	     <td align="left">1c.<?php echo $AppUI->_('Clinician');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'medical_staff_id', 'size="1" class="text" id="staff_id"', @$obj->medical_staff_id ? $obj->medical_staff_id:-1); ?>        
			</td>		 
       </tr>    
	   
      <tr>	 
       <tr>
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="client_adm_no" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>

	 <tr>
         <td align="left">2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="medical_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
		 <tr>
		  <td align="left">3a...<?php echo $AppUI->_('Gender');?>:</td>
		   <td align="left">
				<?php echo //arraySelectRadio($genderTypes, "medical_gender", 'onclick=toggleButtons() readonly="readonly" disabled', $admissionObj->admission_gender ?$admissionObj->admission_gender : -1, $identifiers ); 
					@$genderTypes[$clientObj->client_gender];
				?>
				
		   </td>		   
		 </tr>
		 <tr>
      <tr>
         <td align="left">3c.<?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left">
	    <input type="text" class="text" name="medical_age_yrs1" disabled="disabled" readonly="readonly" value="<?php echo dPformSafe(@$client_age[0]);?>" maxlength="30" size="20" readonly />
		 </td>
	 </tr>
	 <tr>
	 <td><?php echo $AppUI->_('Age (months)');?>:</td>
	 <td align="left">
	    <input type="text" class="text" name="medical_age_months1" disabled="disabled" readonly="readonly" value="<?php echo dPformSafe(@$client_age[1]);?>" maxlength="30" size="20" readonly />
		 </td>

	 </tr>

       <tr>
         <td align="left" nowrap="nowrap">4a.<?php echo $AppUI->_('Transferred from another programme?');?></td>
		 <td align="left"><?php echo arraySelectRadio($boolTypes, "medical_transferred", 'onclick=toggleButtons()', $obj->medical_transferred? $obj->medical_transferred : -1, $identifiers ); ?></td>

       </tr>
	  <tr>	   
	      <td align="left">4b...<?php echo $AppUI->_('If Y, which?');?></td>
          <td><input type="text" class="text" name="medical_other_programme" value="<?php echo @$obj->medical_other_programme;?>" maxlength="150" size="40" />
         </td>
	   </tr>	 
	   <tr>
		<td align="left">5a.<?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_birth_weight" id="medical_birth_weight" value="<?php echo $obj->medical_birth_weight;?>" maxlength="150" size="20"/></td>
      </tr>
	 	  
	   <tr>
		<td align="left">5b.<?php echo $AppUI->_('PMTCT');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($boolTypes, "medical_pmtct", 'onclick=toggleButtons()', $obj->medical_pmtct? $obj->medical_pmtct : -1, $identifiers );?>
		</td>
      </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('ARVs given');?>:</td>
         </tr>
		 <tr>
		  <td align="left">5c...<?php echo $AppUI->_('Mother');?>:</td>
		   <td align="left"><?php echo arraySelectRadio($boolTypes, "medical_mother_arv_given", 'onclick=toggleButtons()', $obj->medical_mother_arv_given? $obj->medical_mother_arv_given : -1, $identifiers ); ?></td>
		 </tr>
		 <tr>
         <td align="left">5d...<?php echo $AppUI->_('Baby');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($boolTypes, "medical_child_arv_given", 'onclick=toggleButtons()', $obj->medical_child_arv_given? $obj->medical_child_arv_given : -1, $identifiers ); ?></td>
		</tr>
		 <tr>
         <td align="left">6a.<?php echo $AppUI->_('Born');?>:</td>
		 <td align="left"><?php echo arraySelectRadio($bornTypes, "medical_birth_location", 'onclick=toggleButtons()', $obj->medical_birth_location? $obj->medical_birth_location : -1, $identifiers ); ?></td>
		</tr>
	   <tr>
		<td align="left">6b.<?php echo $AppUI->_('Delivery');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_delivery" id="medical_delivery" value="<?php echo $obj->medical_delivery;?>" maxlength="150" size="20"/></td>
      </tr>
	   <tr>
		<td align="left">6c.<?php echo $AppUI->_('Problems at or after birth');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_birth_problems" id="medical_birth_problems" value="<?php echo $obj->medical_birth_problems;?>" maxlength="150" size="20"/></td>
      </tr>	  
	   <tr>
		<td align="left">7a.<?php echo $AppUI->_('Immunization status');?>:</td>
		<td align="left"><?php echo arraySelectRadio($immunizationStatus, "medical_immunization_status", 'onclick=toggleButtons()', $obj->medical_immunization_status ? $obj->medical_immunization_status : -1, $identifiers ); ?>
		</tr>
		<tr>
		<td align="left">7b...<?php echo $AppUI->_('Card seen?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_card_seen", 'onclick=toggleButtons()', $obj->medical_card_seen ? $obj->medical_card_seen : -1, $identifiers ); ?></td>
		</tr>
	   <tr>
		<td align="left">8a.<?php echo $AppUI->_('Breastfeeding?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_breastfeeding", 'onclick=toggleButtons()', $obj->medical_breastfeeding ? $obj->medical_breastfeeding : -1, $identifiers ); ?></td>
	   </tr>
       <tr>	   
		<td align="left">8b...<?php echo $AppUI->_('Exclusive BF?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_exclusive_breastfeeding", 'onclick=toggleButtons()', $obj->medical_exclusive_breastfeeding ? $obj->medical_exclusive_breastfeeding : -1, $identifiers ); ?></td>
	   </tr>
       <tr>	   
		<td align="left">8c...<?php echo $AppUI->_('Duration of BF');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_bf_duration" id="medical_bf_duration" value="<?php echo $obj->medical_bf_duration;?>" maxlength="150" size="20"/></td>
	   </tr>
     <tr>
			<td align="left">9a.<?php echo $AppUI->_('Father HIV Status');?>:</td>
			<td align="left"><?php echo arraySelectRadio($hivStatus, "medical_father_hiv_status", 'onclick=toggleButtons()', $obj->medical_father_hiv_status ? $obj->medical_father_hiv_status : -1, $identifiers ); ?></td>
	 </tr>
	 <tr>
			<td align="left">9b...<?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_father_arv", 'onclick=toggleButtons()', $obj->medical_father_arv ? $obj->medical_father_arv : -1, $identifiers ); ?></td>
	 </tr> 
     <tr>
			<td align="left">10a.<?php echo $AppUI->_('Mother HIV Status');?>:</td>
			<td align="left"><?php echo arraySelectRadio($hivStatus, "medical_mother_hiv_status", 'onclick=toggleButtons()', $obj->medical_mother_hiv_status ? $obj->medical_mother_hiv_status : -1, $identifiers ); ?></td>
	 </tr>
	 <tr>
			<td align="left">10b...<?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_mother_arv", 'onclick=toggleButtons()', $obj->medical_mother_arv ? $obj->medical_mother_arv : -1, $identifiers ); ?></td>
	  </tr> 
     <tr>
			<td align="left">11a.<?php echo $AppUI->_('Number of siblings alive');?>:</td>
			<td align="left"><input type="text" class="text" name="medical_no_siblings_alive" id="medical_no_siblings_alive" value="<?php echo $obj->medical_no_siblings_alive;?>" maxlength="150" size="20"/></td>
		 </tr>
	 <tr>

			<td align="left">11b.<?php echo $AppUI->_('Number of siblings deceased');?>:</td>
			<td align="left"><input type="text" class="text" name="medical_no_siblings_deceased" id="medical_no_siblings_deceased" value="<?php echo $obj->medical_no_siblings_deceased;?>" maxlength="150" size="20"/></td>
	  </tr> 
     <tr>
			<td align="left">12a.<?php echo $AppUI->_('TB: Any Household contact');?>:</td>
			<td align="left">
			<?php echo arraySelectRadio($boolTypes, "medical_tb_contact", 'onclick=toggleButtons()', $obj->medical_tb_contact ? $obj->medical_tb_contact : -1, $identifiers ); ?>
			</td>
	 </tr>
	 <tr>
			<td align="left">12b...<?php echo $AppUI->_('Who');?>:</td>
			<td align="left"><input type="text" class="text" name="medical_tb_contact_person" id="medical_tb_contact_person" value="<?php echo $obj->medical_tb_contact_person;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>

			<td align="left">12c...<?php echo $AppUI->_('When diagnosed');?>:</td>
			<td>
				<?php 
					echo drawDateCalendar("medical_tb_date_diagnosed", $medical_tb_date_diagnosed ? $medical_tb_date_diagnosed->format( $df ) : "",false,'id="medical_tb_date_diagnosed"');
					//<input type="text" class="text" name="medical_tb_date_diagnosed" id="medical_tb_date_diagnosed" value="<?php echo $medical_tb_date_diagnosed ? $medical_tb_date_diagnosed->format( $df ) : "" ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
				?>
				
			 </td>
	  </tr> 
	  
	  
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Medical History'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
  	 <tr>
		<td>&nbsp;</td>
		 <td align="left">
		 <table>
		   <tr> 
		    <td>
				 <table id="history">
				 	<thead>
				 	<tr>				 	
					 <th>a.<?php echo $AppUI->_('Hospital');?></th>
					 <th>b.<?php echo $AppUI->_('Date');?></th>
					 <th>c.<?php echo $AppUI->_('Reason/Diagnosis');?></th>
					 <th>&nbsp;</th>
					 </tr>
					</thead>
					<tbody>
					 <?php 
					 $rowcount = 1;
					 if (count($rows) > 0 )
					 {
						foreach ($rows as $row)
						{
							$history_date = intval( $row["medical_history_date"] ) ? new CDate( $row["medical_history_date"] ) : NULL;
					 ?>					 
					 <tr>
						 <td align="left">
						 <input type="hidden" name="medical_history_id_<?php echo $rowcount; ?>" value="<?php echo @$row["medical_history_id"]?>" />
						 <input type="text" class="text" id="hospital_<?php echo $rowcount; ?>" name="hospital_<?php echo $rowcount; ?>" value="<?php echo $row["medical_history_hospital"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
						 <?php 
						 	echo drawDateCalendar('date_'.$rowcount,$history_date ? $history_date->format($df) : "",false,'id="date_'.$rowcount.'"');
						 	//<td align="left"><input type="text" class="text" id="date_<?php echo $rowcount; " name="date_<?php echo $rowcount; " value="<?php echo $history_date ? $history_date->format($df) : "";" maxlength="150" size="20" /></td>
						 ?>
						 </td>						 
						 <td align="left"><input type="text" class="text" id="reason_<?php echo $rowcount; ?>" name="reason_<?php echo $rowcount; ?>" value="<?php echo $row["medical_history_diagnosis"];?>" maxlength="150" size="20" /></td>
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
						 <input type="hidden" name="medical_history_id_<?php echo $rowcount; ?>" value="<?php echo @$row["medical_history_id"]?>" />
						 <input type="text" class="text" id="hospital_<?php echo $rowcount; ?>" name="hospital_<?php echo $rowcount; ?>" value="<?php echo $row["medical_history_hospital"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
						 <?php 
						 	echo drawDateCalendar('date_'.$rowcount,$row['medical_history_date'],false,'id="date_'.$rowcount.'"');
						 	//<td align="left"><input type="text" class="text" id="date_<?php echo $rowcount; " name="date_<?php echo $rowcount; " value="<?php echo $history_date ? $history_date->format($df) : "";" maxlength="150" size="20" /></td>
						 	//<td align="left"><input type="text" class="text" id="date_<?php echo $rowcount; " name="date_<?php echo $rowcount; " value="<?php echo $row["medical_history_date"];" maxlength="150" size="20" /></td>
						 ?>
						 </td>
						 
						 <td align="left"><input type="text" class="text" id="reason_<?php echo $rowcount; ?>" name="reason_<?php echo $rowcount; ?>" value="<?php echo $row["medical_history_diagnosis"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>	
					  <?php
					  }//end if
					 ?>					 
				</tbody>
				</table>
			  </td>
            </tr>			  
		 <tr>
			<td>
				<input class="button" type="button" name="append" value="new entry" onclick="AppendRow('history','medical_history_id'); return false;"/>
			</td>
		</tr>
		 </table>
		 </td>
	  </tr>

	 <tr>
			<td align="left">16a.<?php echo $AppUI->_('TB');?>:</td>
			<td align="left"><?php echo arraySelectRadio($tbPulmonaryTypes, "medical_tb_pulmonary", 'onclick=toggleButtons()', $obj->medical_tb_pulmonary ? $obj->medical_tb_pulmonary : -1, $identifiers ); ?>
			</td>	
	 </tr>
	 <tr>
	   <td align="left">16b.<?php echo $AppUI->_('Type');?>:</td>
	   <td align="left"><?php echo arraySelectRadio($tbTypes, "medical_tb_type", 'onclick=toggleButtons()', $obj->medical_tb_type ? $obj->medical_tb_type : -1, $identifiers ); ?></td>
     </tr>	 
	 <tr>
	   <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
	   <td align="left"><input type="text" class="text" name="medical_tb_type_desc" id="medical_tb_type_desc" value="<?php echo $obj->medical_tb_type_desc;?>" maxlength="150" size="40"/></td>
     </tr>
     <tr>
			<td align="left"><?php echo $AppUI->_('Courses of treatment(dates)');?>:</td>
	 </tr>

	 <tr>
			  <td align="left">
				17a...<?php echo $AppUI->_('1st');?>:
			  </td>
			  <td>
			  	<?php 
			  		echo drawDateCalendar("medical_tb_date1",$medical_tb_date1 ? $medical_tb_date1->format( $df ) : "",false,'id="medical_tb_date1"');
			  		//<input type="text" class="text" name="medical_tb_date1" id="medical_tb_date1" value="<?php echo $medical_tb_date1 ? $medical_tb_date1->format( $df ) : "" ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
			  	?>				
			  </td>
	          </tr>
			  <tr>
			  <td>
			  17b...<?php echo $AppUI->_('2nd');?>:
			  </td>
			  <td>
			  	<?php 
			  		echo drawDateCalendar("medical_tb_date2",$medical_tb_date2 ? $medical_tb_date2->format( $df ) : "",false,'id="medical_tb_date2"');
			  	?>			  
			  </td>
	          </tr>
			 <tr>
			  <td>
			  17c...<?php echo $AppUI->_('3rd');?>:
			  </td>
			  <td>
			  	<?php 
			  		echo drawDateCalendar("medical_tb_date3",$medical_tb_date3 ? $medical_tb_date3->format( $df ) : "",false,'id="medical_tb_date3"');
			  	?>			  
	          </tr>
			 <tr>
	   <tr>
		<td align="left" valign="top">
		<?php echo $AppUI->_('Has there been a recurring history');?><br/>
		<?php echo $AppUI->_('of any of the following?');?>
		</td>
	  </tr>
		  <tr>
		  <td align="left">18a...<?php echo $AppUI->_('Pneumonia');?>:</td>
		  <td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_pneumonia", 'onclick=toggleButtons()', $obj->medical_history_pneumonia ? $obj->medical_history_pneumonia : -1, $identifiers ); ?></td>
		  </tr>
		<tr>
		<td align="left">18b...<?php echo $AppUI->_('Diarrhoeal episodes');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_diarrhoea", 'onclick=toggleButtons()', $obj->medical_history_diarrhoea ? $obj->medical_history_diarrhoea : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td align="left">18c...<?php echo $AppUI->_('Skin rashes');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_skin_rash", 'onclick=toggleButtons()', $obj->medical_history_skin_rash ? $obj->medical_history_skin_rash : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td align="left">18d...<?php echo $AppUI->_('Ear discharge');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_ear_discharge", 'onclick=toggleButtons()', $obj->medical_history_ear_discharge ? $obj->medical_history_ear_discharge : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td align="left">18e...<?php echo $AppUI->_('Fever ');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_fever", 'onclick=toggleButtons()', $obj->medical_history_fever ? $obj->medical_history_fever : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td align="left">18f...<?php echo $AppUI->_('Persistent oral thrush');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_oral_rush", 'onclick=toggleButtons()', $obj->medical_history_oral_rush ? $obj->medical_history_oral_rush : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td align="left">18g...<?php echo $AppUI->_('Mouth ulcers');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "medical_history_mouth_ulcers", 'onclick=toggleButtons()', $obj->medical_history_mouth_ulcers ? $obj->medical_history_mouth_ulcers : -1, $identifiers ); ?>
		</td>
		</tr>
	 <tr>
		<td align="left" valign="top">19a.<?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($malnutritionType, "medical_history_malnutrition", 'onclick=toggleButtons()', $obj->medical_history_malnutrition ? $obj->medical_history_malnutrition : -1, $identifiers ); ?></td>
     </tr>	  
     <tr>	
		<td align="left" valign="top">19b.<?php echo $AppUI->_('Previous nutritional rehabilitation');?></td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "medical_history_prev_nutrition", 'onclick=toggleButtons()', $obj->medical_history_prev_nutrition ? $obj->medical_history_prev_nutrition : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top">20.<?php echo $AppUI->_('Current nutritional rehabilitation');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="medical_history_notes"><?php echo @$obj->medical_history_notes;?></textarea>
		</td>
     </tr>
	 
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Medications'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top">21.<?php echo $AppUI->_('ARVs');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($arvTreatmentTypes, "medical_arv_status", 'onclick=toggleButtons()', $obj->medical_arv_status ? $obj->medical_arv_status : -1, $identifiers ); ?></td>     
	</tr>

 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('ARV regimes');?></td>
	 </tr>
		<tr>
		<td align="left">
		
		22a...<?php echo $AppUI->_('1st line');?>
		</td>
		<td align="left">			
		<input type="text" class="text" name="medical_arv1" id="medical_arv1" value="<?php echo $obj->medical_arv1;?>" maxlength="150" size="20"/>
		</td>
	</tr>
    <tr>  	
		<td align="left">
		22b...<?php echo $AppUI->_('Started');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_arv1_startdate",$medical_arv1_startdate ? $medical_arv1_startdate->format( $df ) : "",false, 'id="medical_arv1_startdate"');
			//<input type="text" class="text" name="medical_arv1_startdate" id="medical_arv1_startdate" value="<?php echo $medical_arv1_startdate ? $medical_arv1_startdate->format( $df ) : "" ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
		?>		
		</td>
	</tr>
    <tr>
		<td align="left">
		22c...<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_arv1_enddate",$medical_arv1_enddate ? $medical_arv1_enddate->format( $df ) : "",false, 'id="medical_arv1_enddate"');
		?>		
	   </td>
	 </tr>		
	 <tr>
		<td align="left">
		
		22d...<?php echo $AppUI->_('2nd line');?>
		</td>
		<td align="left">
		<input type="text" class="text" name="medical_arv2" id="medical_arv2" value="<?php echo $obj->medical_arv2;?>" maxlength="150" size="20"/>
		</td>
	</tr>
    <tr>
		<td align="left">
		22e...<?php echo $AppUI->_('Started');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_arv2_startdate",$medical_arv2_startdate ? $medical_arv2_startdate->format( $df ) : "",false, 'id="medical_arv2_startdate"');
		?>		
		</td>
	</tr>
    <tr>		
		<td align="left">
		22f...<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_arv2_enddate",$medical_arv2_enddate ? $medical_arv2_enddate->format( $df ) : "",false, 'id="medical_arv2_enddate"');
		?>		
	   </td>
	</tr> 
	<tr>
		<td align="left">22g.		
		<?php echo $AppUI->_('Salvage');?>
		</td>
		<td align="left">
		<input type="text" class="text" name="medical_salvage" id="medical_salvage" value="<?php echo $obj->medical_salvage;?>" maxlength="150" size="20"/>
		</td>
	</tr>
    <tr>
		<td align="left">22h.
		<?php echo $AppUI->_('Started');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_salvage_startdate",$medical_salvage_startdate ? $medical_salvage_startdate->format( $df ) : "",false, 'id="medical_salvage_startdate"');
		?>		
		</td>
	</tr>
    <tr>		
		<td align="left">22i.
		<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td align="left">
		<?php 
			echo drawDateCalendar("medical_salvage_enddate",$medical_salvage_enddate ? $medical_salvage_enddate->format( $df ) : "",false, 'id="medical_salvage_enddate"');
		?>		
	   </td>
	</tr>
	 <tr>
		<td align="left">22j.<?php echo $AppUI->_('Side effects');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_arv_side_effects" id="medical_arv_side_effects" value="<?php echo $obj->medical_arv_side_effects;?>" maxlength="150" size="20"/></td>
     </tr>
	 <tr>
		<td align="left">22k.<?php echo $AppUI->_('Adherence');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_arv_adherence" id="medical_arv_adherence" value="<?php echo $obj->medical_arv_adherence;?>" maxlength="150" size="20"/></td>
     </tr>
	 <tr>
		<td>&nbsp;</td>
		 <td align="left">
		 <table>
		   <tr> 
		    <td>
				 <table id="drugs">
				 	<thead>
				 	<tr>
					 <th><?php echo $AppUI->_('a.ART Drug');?></th>
					 <th><?php echo $AppUI->_('b.Dose');?></th>
					 <th><?php echo $AppUI->_('c.Frequency');?></th>
					 <th>&nbsp;</th>
					 </tr>
					 </thead>
					 <tbody>
				 <?php 
					 $rowcount = 1;
					 if (count($medications) > 0 )
					 {
						foreach ($medications as $medication)
						{
							
					 ?>										 
					 <tr>
						 <td align="left">
						 <input type="hidden" name="medications_history_id_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_id"]?>" />
						 <input type="text" class="text" id="drug_<?php echo $rowcount; ?>" name="drug_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_drug"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="dose_<?php echo $rowcount; ?>" name="dose_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_dose"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="frequency_<?php echo $rowcount; ?>" name="frequency_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_frequency"];?>" maxlength="150" size="20" /></td>
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
						 <input type="hidden" name="medications_history_id_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_id"]?>" />						 
						 <input type="text" class="text" id="drug_<?php echo $rowcount; ?>" name="drug_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_drug"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="dose_<?php echo $rowcount; ?>" name="dose_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_dose"];?>" maxlength="150" size="20" /></td>
						 <td align="left"><input type="text" class="text" id="frequency_<?php echo $rowcount; ?>" name="frequency_<?php echo $rowcount; ?>" value="<?php echo @$medication["medications_history_frequency"];?>" maxlength="150" size="20" /></td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					<?php
					  }//end if
					 ?>			
					</tbody>			 
				</table>
			  </td>
            </tr>			  
		 <tr>
			<td>
				<input class="button" type="button" name="append" value="new row" onclick="AppendRow('drugs','medications_history_id'); return false;"/>
			</td>
		</tr>
		 </table>		 
		 <?php
		 /*
            if ($AppUI->isActiveModule('contacts') && $perms->checkModule('contacts', 'view')) 
		{
			echo "<input type='button' class='button' value='".$AppUI->_("enter medical history...")."' onclick='javascript:popFWContacts(selected_fw_contacts_id);' />";
		}*/?>
		 </td>
	  </tr>	 
	 
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Development History and Diet'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">29.<?php echo $AppUI->_('Attend School Regularly');?>:</td>
		<td align="left" valign="top">
		<?php echo arraySelectRadio($boolTypesND, "medical_school_attendance", 'onclick=toggleButtons()', $obj->medical_school_attendance ? $obj->medical_school_attendance : -1, $identifiers ); ?>
		</td>     
	</tr>
	<tr>
	   <td align="left">
			29b...<?php echo $AppUI->_('If Yes, class');?>
		</td>
       <td>		
		<input type="text" class="text" name="medical_school_class" id="medical_school_class" value="<?php echo $obj->medical_school_class;?>" maxlength="150" size="20"/> 
       </td>
	</tr>
	 <tr>
		<td align="left" valign="top">29c...<?php echo $AppUI->_('Progress');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($educProgressType, "medical_educ_progress", 'onclick=toggleButtons()', $obj->medical_educ_progress ? $obj->medical_educ_progress : -1, $identifiers ); ?></td>     
	</tr>
	<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Sensory impairment');?>:</td>
	</tr>
		 <tr>
		  <td>
		30a...<?php echo $AppUI->_('Hearing');?>
		</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "medical_sensory_hearing", 'onclick=toggleButtons()', $obj->medical_sensory_hearing ? $obj->medical_sensory_hearing : -1, $identifiers ); ?>
	    </td>
        </tr>
        <tr>		
		  <td>
		30b...<?php echo $AppUI->_('vision');?>:
		</td>
		<td>

		<?php echo arraySelectRadio($boolTypes, "medical_sensory_vision", 'onclick=toggleButtons()', $obj->medical_sensory_vision ? $obj->medical_sensory_vision : -1, $identifiers ); ?>
		</td>
        </tr>
        <tr>
			<td valign="top">
			30c...<?php echo $AppUI->_('motor ability');?>:
		</td>
		<td>
		<?php echo arraySelectCheckbox($motorAbilityType, "medical_sensory_motor_ability[]", NULL, $motor_ability); ?>
        </td>
	    </tr>
	    <tr>
		<td>	
		30d...<?php echo $AppUI->_('speech and language');?>
		</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "medical_sensory_speech_language", 'onclick=toggleButtons()', $obj->medical_sensory_speech_language ? $obj->medical_sensory_speech_language : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td>

		30e...<?php echo $AppUI->_('social skills');?>:
		</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "medical_sensory_social_skills", 'onclick=toggleButtons()', $obj->medical_sensory_social_skills ? $obj->medical_sensory_social_skills : -1, $identifiers ); ?>
		</td>
	    </tr>
	    <tr>
		<td align="left">31a.<?php echo $AppUI->_('Number of meals per day');?>:</td>
		<td align="left"><input type="text" class="text" name="medical_meals_per_day" id="medical_meals_per_day" value="<?php echo $obj->medical_meals_per_day;?>" maxlength="150" size="20"/></td>
        </tr>
	 <tr>
		<td align="left">31b.<?php echo $AppUI->_('Types of food (list)');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="medical_food_types"><?php echo @$obj->medical_food_types;?></textarea>
		</td>		
     </tr>
	 	 <tr>
		<td align="left" valign="top">32.<?php echo $AppUI->_('Current complaints?');?></td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="medical_current_complaints"><?php echo @$obj->medical_current_complaints;?></textarea>
		</td>
     </tr>	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Examination'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
        <td align="left">33a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left">
            <input type="text" class="text" name="medical_weight" value="<?php echo dPformSafe(@$obj->medical_weight);?>" maxlength="30" size="20" />
        </td>
      </tr>
      <tr>
			<td align="left">33b.<?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_height" id="medical_height" value="<?php echo $obj->medical_height;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left">33c.<?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_zscore" id="medical_zscore" value="<?php echo $obj->medical_zscore;?>" maxlength="30" size="20"/></td>
      </tr>      <tr>
			<td align="left">33d.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_muac" id="medical_muac" value="<?php echo $obj->medical_muac;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left">33e.<?php echo $AppUI->_('Head Circum (cm)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_hc" id="medical_hc" value="<?php echo $obj->medical_hc;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left">34a.<?php echo $AppUI->_('Is the child unwell');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($boolRev, "medical_condition", 'onclick=toggleButtons()', $obj->medical_condition ? $obj->medical_condition : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left">34b.<?php echo $AppUI->_('Temperature (Celcius)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_temp" id="medical_temp" value="<?php echo $obj->medical_temp;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left">34c.<?php echo $AppUI->_('Respiratory rate');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_resp_rate" id="medical_resp_rate" value="<?php echo $obj->medical_resp_rate;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left">34d.<?php echo $AppUI->_('Heart rate');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_heart_rate" id="medical_heart_rate" value="<?php echo $obj->medical_heart_rate;?>" maxlength="30" size="20"/></td>
      </tr>      
      <tr>
			<td align="left" valign="top">35.<?php echo $AppUI->_('Identify');?>:</td>
			<td align="left" >
			<?php 
			echo arraySelectCheckbox($examinationType, "medical_conditions[]", NULL, $medical_conditions); 
			?>
			</td>     
      </tr>
      <tr>
			<td align="left">36a.<?php echo $AppUI->_('Dehydration');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($dehydrationType, "medical_dehydration", 'onclick=toggleButtons()', $obj->medical_dehydration ? $obj->medical_dehydration : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left">36b.<?php echo $AppUI->_('Parotids');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($enlargementType, "medical_parotids", 'onclick=toggleButtons()', $obj->medical_parotids ? $obj->medical_parotids : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left" valign="top">37a.<?php echo $AppUI->_('Enlarged Lymph nodes');?>:</td>
			<td align="left">
			<?php 
			echo arraySelectCheckbox($lymphType, "medical_lymph[]", NULL, $medical_lymph); 
			?>
			</td>     
      </tr>
      <tr>
			<td align="left">37a.<?php echo $AppUI->_('Eyes');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($eyeType, "medical_eyes", 'onclick=toggleButtons()', $obj->medical_eyes ? $obj->medical_eyes : -1, $identifiers ); ?></td>     
      </tr>      
	  <tr>
			<td align="left">...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical_eyes_notes" id="medical_eyes_notes" value="<?php echo $obj->medical_eyes_notes;?>" maxlength="30" size="20"/></td>     
      </tr>
      <tr>
			<td align="left">38a.<?php echo $AppUI->_('Ear discharge');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($earType, "medical_ear_discharge", 'onclick=toggleButtons()', $obj->medical_ear_discharge ? $obj->medical_ear_discharge : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left">38b.<?php echo $AppUI->_('Throat');?>:</td>
			<td align="left" valign="top"><?php echo arraySelectRadio($throatType, "medical_throat", 'onclick=toggleButtons()', $obj->medical_throat ? $obj->medical_throat : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Mouth');?>:</td>
	  </tr>
			 <tr>
			  <td>
				39a...<?php echo $AppUI->_('thrush');?>:
			  </td>
			  <td>	
				<?php echo arraySelectRadio($boolTypes, "medical_mouth_thrush", 'onclick=toggleButtons()', $obj->medical_mouth_thrush ? $obj->medical_mouth_thrush : -1, $identifiers ); ?>
			</td>
				</tr>
				<tr>
				<td>		
			39b...<?php echo $AppUI->_('ulcers');?>:
			</td>
			  <td>	
			<?php echo arraySelectRadio($boolTypes, "medical_mouth_ulcers", 'onclick=toggleButtons()', $obj->medical_mouth_ulcers ? $obj->medical_mouth_ulcers : -1, $identifiers ); ?>
			</td>
				</tr>
				<tr>
				<td>
			39c...<?php echo $AppUI->_('teeth');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($teethType, "medical_mouth_teeth", 'onclick=toggleButtons()', $obj->medical_mouth_teeth ? $obj->medical_mouth_teeth : -1, $identifiers ); ?>
			</td>     
			 </tr>
	  <tr>
			<td align="left">40a<?php echo $AppUI->_('Skin');?>:</td>
			<td align="left">
				<?php 
					echo arraySelectRadio(dPgetSysVal('ClearTypes'),'medical_skin_type',$obj->medical_skin_type ? $obj->medical_skin_type : '',$identifiers);
				?>
			</td>
	  </tr>
	  <tr>			
		<td align="left">	
			40b...<?php echo $AppUI->_('specify');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical_skin_note" id="medical_skin_note" value="<?php echo $obj->medical_skin_note;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	  
	  
      <!--  <tr> 	  
			<td align="left">
			...<?php echo $AppUI->_('Old lesions');?>:
			</td>
			<td align="left">
			<input type="text" class="text" name="medical_oldlesions" id="medical_oldlesions" value="<?php echo $obj->medical_oldlesions;?>" maxlength="30" size="20"/>
			</td>
		</tr>
		<tr>  	
			<td align="left">
			...<?php echo $AppUI->_('Current lesions');?>:
			</td>

			<td align="left">
			<input type="text" class="text" name="medical_currentlesions" id="medical_currentlesions" value="<?php echo $obj->medical_currentlesions;?>" maxlength="30" size="20"/>
			</td>     
      </tr> -->
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Respiratory and Cardiovascular'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
			  <td align="left">41a.
			<?php echo $AppUI->_('Respiratory rate per min');?>:
			  </td>
			   <td align="left">
			    <input type="text" class="text" name="medical_heartrate" id="medical_heartrate" value="<?php echo $obj->medical_heartrate;?>" maxlength="30" size="20"/>
			</td>
			      </tr>
	<tr>
			<td align="left">41b.
			<?php echo $AppUI->_('recession');?>:
			</td>
			<td align="left">
			<?php echo arraySelectRadio($boolTypes, "medical_recession", 'onclick=toggleButtons()', $obj->medical_recession ? $obj->medical_recession : -1, $identifiers ); ?>
			</td>
      </tr>
	<tr>		
		<td>
			41c.<?php echo $AppUI->_('percussion');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($percussionType, "medical_percussion", 'onclick=toggleButtons()', $obj->medical_percussion ? $obj->medical_percussion : -1, $identifiers ); ?>
			</td>
      </tr>
	  <tr>		
	 	<td>
			41d...<?php echo $AppUI->_('location');?>:
			</td>
			<td>
			<input type="text" class="text" name="medical_location" id="medical_location" value="<?php echo $obj->medical_location;?>" maxlength="30" size="20"/>
			</td>
	      </tr>
	     <tr>
			<td align="left">41e.
			<?php echo $AppUI->_('Shape of chest');?>:
			</td>
			<td align="left">
			<?php echo arraySelectRadio(dPgetSysVal('ChestShape'), "medical_chest_shape", 'onclick=toggleButtons()', $obj->medical_chest_shape ? $obj->medical_chest_shape : -1, $identifiers ); ?>
			</td>
      </tr>
	      	  
		  <tr>		
	 	 <td>42a.
			<?php echo $AppUI->_('breath sounds');?>:
			</td>
			<td>
			  <?php echo arraySelectRadio($breathType, "medical_breath_sounds", 'onclick=toggleButtons()', $obj->medical_breath_sounds ? $obj->medical_breath_sounds : -1, $identifiers ); ?>
			</td>
	      </tr>	  
		  <tr>		

			<td>
			42b...<?php echo $AppUI->_('location');?>:
			</td>
			<td>
			<input type="text" class="text" name="medical_breathlocation" id="medical_breathlocation" value="<?php echo $obj->medical_breathlocation;?>" maxlength="30" size="20"/>
			</td>
	      </tr>
	  
      </tr>
	  <tr>
			<td align="left">43a.
			<?php echo $AppUI->_('added sounds');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($soundsType, "medical_other_sounds", 'onclick=toggleButtons()', $obj->medical_other_sounds ? $obj->medical_other_sounds : -1, $identifiers ); ?>
			</td>
	 </tr>
      </tr>
	  <tr>
			<td>
	  43b...<?php echo $AppUI->_('location');?>:
			</td>
			<td>
	  
	  <input type="text" class="text" name="medical_soundlocation" id="medical_soundlocation" value="<?php echo $obj->medical_soundlocation;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	  <tr>
			<td align="left">44a.
			<?php echo $AppUI->_('pulse rate per min');?>:
			</td>
			<td>

			<input type="text" class="text" name="medical_pulserate" id="medical_pulserate" value="<?php echo $obj->medical_pulserate;?>" maxlength="30" size="20"/>
			</td>
      </tr>
	  <tr>
	  	<td>44b.
	  		<?php echo $AppUI->_('apex beat');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($apexType, "medical_apex_beat", 'onclick=toggleButtons()', $obj->medical_apex_beat ? $obj->medical_apex_beat : -1, $identifiers ); ?>
			</td>
      </tr>
	  <tr>
			<td>44c.
			<?php echo $AppUI->_('Precordial activity');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($precordialType, "medical_precordial", 'onclick=toggleButtons()', $obj->medical_precordial ? $obj->medical_precordial : -1, $identifiers ); ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">45a.			
				<?php echo $AppUI->_('femoral pulses');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($femoralType, "medical_femoral", 'onclick=toggleButtons()', $obj->medical_femoral ? $obj->medical_femoral : -1, $identifiers ); ?>
						</td>
				      </tr>	  
		  <tr>			
			<td>45b.
			<?php echo $AppUI->_('Heart');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($heartSoundType, "medical_heart_sound", 'onclick=toggleButtons()', $obj->medical_heart_sound ? $obj->medical_heart_sound : -1, $identifiers ); ?>
						</td>
	      </tr>	  
		  <tr>
		  	<td>45c.
			<?php echo $AppUI->_('type');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical_heart_type" id="medical_heart_type" value="<?php echo $obj->medical_heart_type;?>" maxlength="30" size="20"/>
			</td>     
      	</tr>
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Abdomen'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
			<td align="left" valign="top">46a.
			<?php echo $AppUI->_('distended');?>:
			</td>
			<td>			
			<?php echo arraySelectRadio($boolTypes, "medical_abdomen_distended", 'onclick=toggleButtons()', $obj->medical_abdomen_distended ? $obj->medical_abdomen_distended : -1, $identifiers ); ?>
				</td>
      	</tr>
		<tr>
			<td>46b.		
				<?php echo $AppUI->_('feel');?>:
			</td>
		<td>
			<?php echo arraySelectRadio($feelType, "medical_adbomen_feel", 'onclick=toggleButtons()', $obj->medical_adbomen_feel ? $obj->medical_adbomen_feel : -1, $identifiers ); ?>
						</td>
      </tr>
	<tr>		
		<td>46c.
			<?php echo $AppUI->_('tender');?>:
		</td>
		<td>		
		<?php echo arraySelectRadio($boolTypes, "medical_abdomen_tender", 'onclick=toggleButtons()', $obj->medical_abdomen_tender ? $obj->medical_abdomen_tender : -1, $identifiers ); ?>
						</td>
	      </tr>
	<tr>		
			<td>46d.
			<?php echo $AppUI->_('fluid');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical_abdomen_fluid", 'onclick=toggleButtons()', $obj->medical_abdomen_fluid ? $obj->medical_abdomen_fluid : -1, $identifiers ); ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">47a.			
				<?php echo $AppUI->_('Liver (cm below costal margin)');?>:
			</td>
			<td>
				<input type="text" class="text" name="medical_liver_costal" id="medical_liver_costal" value="<?php echo $obj->medical_liver_costal;?>" maxlength="30" size="20"/>
			</td>
      	</tr>
		<tr>
			<td>47b.
				<?php echo $AppUI->_('Spleen (cm below costal margin)');?>:
			</td>		
			<td>
				<input type="text" class="text" name="medical_spleen_costal" id="medical_spleen_costal" value="<?php echo $obj->medical_spleen_costal;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">48a.			
				<?php echo $AppUI->_('Masses (specify)');?>:
			</td>
			<td>		  
		  		<input type="text" class="text" name="medical_masses" id="medical_masses" value="<?php echo $obj->medical_masses;?>" maxlength="30" size="20"/>
			</td>
      	</tr>
		<tr>
			<td>48b.
				<?php echo $AppUI->_('Umbilical hernia');?>:
			</td>
			<td>
				<?php echo arraySelectRadio($umbilicalType, "medical_umbilical_hernia", 'onclick=toggleButtons()', $obj->medical_umbilical_hernia ? $obj->medical_umbilical_hernia : -1, $identifiers ); ?>
			</td>     
      </tr>
      <tr>
	  		<td align="left"  valign="top"><?php echo $AppUI->_('Genitalia');?>:</td>
	  </tr>
		<tr>
			  <td align="left">
				49a...<?php echo $AppUI->_('Male testes ');?>:
			</td>		
			<td align="left">
				<?php echo arraySelectRadio($palpableType, "medical_testes", 'onclick=toggleButtons()', $obj->medical_testes ? $obj->medical_testes : -1, $identifiers ); ?>
			</td>
		</tr>
			<tr>		
				<td align="left">
					49b.&nbsp;
				</td>
				<td align="left">
					<?php echo arraySelectRadio($directionType, "medical_which_testes", 'onclick=toggleButtons()', $obj->medical_which_testes ? $obj->medical_which_testes : -1, $identifiers ); ?>
				</td>
			</tr>
			<tr>
				<td align="left">
				49c...<?php echo $AppUI->_('penis');?>:
				</td>
				<td align="left">
				<?php echo arraySelectRadio($penisTypes, "medical_penis", 'onclick=toggleButtons()', $obj->medical_penis ? $obj->medical_penis : -1, $identifiers ); ?>
					
				</td>
			</tr>
			<tr>		
				<td align="left">
					49d...<?php echo $AppUI->_('OR Female');?>:
				</td>
				<td align="left">
				<?php echo arraySelectRadio($femaleConditionType, "medical_genitals_female", 'onclick=toggleButtons()', $obj->medical_genitals_female ? $obj->medical_genitals_female : -1, $identifiers ); ?>
				</td>     
			</tr>
			<tr>
			<td align="left">...<?php echo $AppUI->_('Other');?></td>
			<td>		  
		  <input type="text" class="text" name="medical_genitals_female_notes" id="medical_genitals_female_notes" value="<?php echo $obj->medical_genitals_female_notes;?>" maxlength="150" size="40"/>
			</td>			
			</tr>
      <tr>
			<td align="left">50.<?php echo $AppUI->_('Pubertal development');?>:</td>
			
			<td align="left" valign="top">
			<?php echo arraySelectRadio($developmentType, "medical_pubertal", 'onclick=toggleButtons()', $obj->medical_pubertal ? $obj->medical_pubertal : -1, $identifiers ); ?></td>     
      </tr>
	  	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Central Nervous System and Musculoskeletal'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
			<td align="left">51a.<?php echo $AppUI->_('Central Nervous System');?>:</td>			
			<td align="left" valign="top">
				<?php echo arraySelectRadio($cnsType, "medical_cns", 'onclick=toggleButtons()', $obj->medical_cns ? $obj->medical_cns : -1, $identifiers ); ?>
			</td>     
      </tr>
     <tr>
		<td align="left">...<?php echo $AppUI->_('Specify');?></td>
		<td>		  
			<input type="text" class="text" name="medical_cns_note" id="medical_cns_note" value="<?php echo $obj->medical_cns_note;?>" maxlength="150" size="40"/>
		</td>			
	 </tr>
	 <tr>
			<td align="left">51b.<?php echo $AppUI->_('Musculoskeletal');?>:</td>			
			<td align="left" valign="top">
				<?php echo arraySelectRadio($cnsType, "medical_muscle", 'onclick=toggleButtons()', $obj->medical_muscle ? $obj->medical_muscle : -1, $identifiers ); ?>
			</td>     
      </tr>
     <tr>
		<td align="left">...<?php echo $AppUI->_('Specify');?></td>
		<td>		  
			<input type="text" class="text" name="medical_muscle_note" id="medical_muscle_note" value="<?php echo $obj->medical_muscle_note;?>" maxlength="150" size="40"/>
		</td>			
	 </tr>
	 <tr>
			<td align="left" valign="top">52a.<?php echo $AppUI->_('Gait');?>:</td>
			<td>
				<?php 
					echo arraySelectRadio(dPgetSysVal('BodySkeleton'),'medical_gait_opt','onclick=toggleButtons()',$obj->medical_gait_opt ? $obj->medical_gait_opt : -1,$identifiers);
				?>				
			</td>
      </tr>
	  <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('...Specify');?>:</td>
			<td>
				<input type="text" class="text" name="medical_gait" id="medical_gait" value="<?php echo $obj->medical_gait;?>" maxlength="30" size="20"/>
			</td>
      </tr>
      <tr>
			<td align="left" valign="top">52b.<?php echo $AppUI->_('Hand use');?>:</td>
			<td>
				<?php 
					echo arraySelectRadio(dPgetSysVal('BodySkeleton'),'medical_handuse_opt','onclick=toggleButtons()',$obj->medical_handuse_opt ? $obj->medical_handuse_opt : -1,$identifiers);
				?>				
			</td>
      </tr>
	<tr>		
		<td><?php echo $AppUI->_('...Specify');?>:</td>		
		<td>
			<input type="text" class="text" name="medical_handuse" id="medical_handuse" value="<?php echo $obj->medical_handuse;?>" maxlength="30" size="20"/>
		</td>     
    </tr>	
	<tr>
			<td align="left" valign="top">53a.			
				<?php echo $AppUI->_('Weakness');?>:
			</td>
			<td>
				<input type="text" class="text" name="medical_weakness" id="medical_weakness" value="<?php echo $obj->medical_weakness;?>" maxlength="30" size="20"/>
			</td>
      </tr>
	<tr>
			<td>53b.
				<?php echo $AppUI->_('Tone');?>:
			</td>
			<td>	
			<?php echo arraySelectRadio($toneType, "medical_tone", 'onclick=toggleButtons()', $obj->medical_tone ? $obj->medical_tone : -1, $identifiers ); ?>
			</td>     
      </tr>	  
      <tr>
	  		<td align="left" valign="top"><?php echo $AppUI->_('Tendon reflexes');?>:</td>
					
		</tr>
			<tr>
			<td align="left">
			54...<?php echo $AppUI->_('legs');?>:
			</td>
  
			<td align="left">
				<?php echo arraySelectRadio($tendonLegsType, "medical_tendon_legs", 'onclick=toggleButtons()', $obj->medical_tendon_legs ? $obj->medical_tendon_legs : -1, $identifiers ); ?>
			</td>
			</tr>
			<tr>
			<td align="left">
			55...<?php echo $AppUI->_('arms');?>:
						</td>
			<td align="left">
			<?php echo arraySelectRadio($tendonArmsType, "medical_tendon_arms", 'onclick=toggleButtons()', $obj->medical_tendon_arms ? $obj->medical_tendon_arms : -1, $identifiers ); ?>
			</td>
			</tr>
     
	  <tr>
			<td align="left" valign="top">56.
				<?php echo $AppUI->_('Abnormal movements');?>:
			</td>
			<td align="left">
				<input type="text" class="text" name="medical_abnormal_movts" id="medical_abnormal_movts" value="<?php echo $obj->medical_abnormal_movts;?>" maxlength="30" size="20"/>
			</td>
	  </tr>	   
	  <tr>
			<td align="left" valign="top">57a.
				<?php echo $AppUI->_('Joints range of movement impaired');?>:
			</td>
			<td align="left">
				<?php echo arraySelectRadio($boolTypes, "medical_movts_impaired", 'onclick=toggleButtons()', $obj->medical_movts_impaired ? $obj->medical_movts_impaired : -1, $identifiers ); ?>
			</td>
    </tr>
	<tr>			
		<td align="left">	
			57b...<?php echo $AppUI->_('specify');?>:
		</td>
		<td>
			<input type="text" class="text" name="medical_movts_impaired_desc" id="medical_movts_impaired_desc" value="<?php echo $obj->medical_movts_impaired_desc;?>" maxlength="30" size="20"/>
		</td>     
    </tr>	  
	  <tr>
			<td align="left" valign="top">58a.
			<?php echo $AppUI->_('Joints swelling');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical_joints_swelling", 'onclick=toggleButtons()', $obj->medical_joints_swelling ? $obj->medical_joints_swelling : -1, $identifiers ); ?>
			</td>
	  </tr>
	  <tr>			
			<td>
				58b...<?php echo $AppUI->_('specify');?>:
			</td>
			<td>
				<input type="text" class="text" name="medical_joints_swelling_desc" id="medical_joints_swelling_desc" value="<?php echo $obj->medical_joints_swelling_desc;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	  
	  <tr>
			<td align="left">59.<?php echo $AppUI->_('Motor');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio($motorType, "medical_motor", 'onclick=toggleButtons()', $obj->medical_motor ? $obj->medical_motor : -1, $identifiers ); ?>
			</td>     
      </tr>
	 <tr>
	 
	 	<td align="left" valign="top">60.<?php echo $AppUI->_('Summary');?>:</td>
		<td valign="top">
		<textarea cols="70" rows="2" class="textarea" name="medical_musc_notes"><?php echo @$obj->medical_musc_notes;?></textarea>
		</td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Management Plan'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 
	<tr>
			<td align="left" valign="top">61a.
				<?php echo $AppUI->_('HIV status');?>:
			</td>
			<td>
				<?php echo arraySelectRadio($managementhivStatus, "medical_hiv_status", 'onclick=toggleButtons()', $obj->medical_hiv_status ? $obj->medical_hiv_status : -1, $identifiers ); ?>
			</td>
    </tr>
	<tr>		
			<td>61b.
				<?php echo $AppUI->_('CD4');?>:
			</td>
			<td>
				<input type="text" class="text" name="medical_cd4" id="medical_cd4" value="<?php echo $obj->medical_cd4;?>" maxlength="30" size="20"/>
			</td>
	    </tr>
	<tr>				
			<td>61c.
				<?php echo $AppUI->_('CD4%');?>:
			</td>
			<td>
				<input type="text" class="text" name="medical_cd4_percentage" id="medical_cd4_percentage" value="<?php echo $obj->medical_cd4_percentage;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	 
	  <tr>
			<td align="left" valign="top">62a.<?php echo $AppUI->_('Clinical stage (WHO)');?>:</td>
			<td>
				<?php
					echo arraySelectRadio($whostages,"medical_who_clinical_stage",'onclick=toggleButtons()',$obj->medical_who_clinical_stage ? $obj->medical_who_clinical_stage : -1,$identifiers);				
					//<input type="text" class="text" name="medical_who_clinical_stage" id="medical_who_clinical_stage" value="<?php echo $obj->medical_who_clinical_stage;" maxlength="30" size="20"/>
				?>				
			</td>
    </tr>
	<tr>				
		<td>61b.<?php echo $AppUI->_('Immunological stage');?>:</td>
			<td>
				<?php
					echo arraySelectRadio($immunostage,"medical_immuno_stage",'onclick=toggleButtons()',$obj->medical_immuno_stage ? $obj->medical_immuno_stage : -1,$identifiers);
					//<input type="text" class="text" name="medical_immuno_stage" id="medical_immuno_stage" value="<?php echo $obj->medical_immuno_stage;" maxlength="30" size="20"/>
				?>
			</td>     
      </tr>	 
      <tr>
			<td align="left" valign="top">63.<?php echo $AppUI->_('Request investigations');?>:</td>
			<td>
				<?php echo arraySelectRadio($boolTypes, "medical_request", 'onclick=toggleButtons()', $obj->medical_request ? $obj->medical_request : -1, $identifiers );
					echo '<br>';
					echo arraySelectCheckbox($investigations, "medical_request_opts[]", 'id="medical_request_opts"', $obj->medical_request_opts ? $obj->medical_request_opts : -1, $identifiers ); 
				?>
			</td>
	  </tr>
	  <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Other');?>:</td>
			<td>
				<input type="text" class="text" name="medical_request_note" id="medical_request_note" value="<?php echo dPformSafe($obj->medical_request_note);?>"  size="20"/>
			</td>
	 </tr>
	 <tr>
	 
	 	<td align="left" valign="top">64.<?php echo $AppUI->_('Treatment');?>:</td>
		<td valign="top">
		<textarea cols="70" rows="2" class="textarea" name="medical_notes"><?php echo @$obj->medical_notes;?></textarea>
		</td>
     </tr>
				
		
	<!-- <tr>
		<td align="left" valign="top">
		    <?php echo $AppUI->_('Tests');?>:
		</td>
		<td>
		    <input type="text" class="text" name="medical_tests" id="medical_tests" value="<?php echo $obj->medical_tests;?>" maxlength="30" size="20"/>
		</td>
	</tr> -->
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Next appointment');?>:</td>
		<td align="left" valign="top">
			<?php echo drawDateCalendar('medical_next_visit',$next_date ? $next_date->format($df) : '',false,'id="next_visit"');?>
		</td>
	</tr>
	<tr>		
		<td>65.			
			<?php echo $AppUI->_('Referral to');?>:
		</td>
		<td align="left">
			<?php echo arraySelect( $refers, 'medical_referral', 'id="botm_refs" size="1" class="text"', @$obj->medical_referral ? $obj->medical_referral:-1); ?>        
		</td>		 
      </tr>
	</table>
</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->medical_id, "edit" );
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
