<?php
$nutrition_id = intval( dPgetParam( $_GET, "nutrition_id", 0 ) );
$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('counsellinginfo'));
require_once ($AppUI->getModuleClass('social'));
require_once ($AppUI->getModuleClass('admission'));

// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($nutrition_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $nutrition_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types

$boolTypes = dPgetSysVal( 'YesNo' );
$scoreTypes = dPgetSysVal( 'YesNo' );
$boolRev = array_reverse(dPgetSysVal('NoYes'),true);
$ageTypes = dPgetSysVal('AgeType');
$insecurityScores = dPgetSysVal('InsecurityScore');
$dietHistoryOptions = dPgetSysVal('DietHistoryOptions');
$caregiverType = dPgetSysVal('CaregiverRelation');
$foodEnrichmentType = dPgetSysVal('FoodEnrichmentOptions');
$waterSourceTypes = dPgetSysVal('WaterSourceOptions');
$waterPurificationTypes = dPgetSysVal('WaterPurificationOptions');
$genderTypes = dPgetSysVal('GenderType');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),dPgetSysVal('RiskLevel'));
$renders = dPgetSysVal('NutritionRendered');
//$refers = dPgetSysVal('NutritionReferer');
$refers = arrayMerge(array(0=>'--Select Position--'),  dPgetSysVal('PositionOptions'));
// load the record data

$past_progs = arrayMerge(array('-1'=>'-- Select --'),dPgetSysVal('NutritionProgram'));

$q  = new DBQuery;
$q->addTable('nutrition_visit');
$q->addQuery('nutrition_visit.*');
$q->addWhere('nutrition_visit.nutrition_id = '.$nutrition_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CNutritionVisit();

if (!db_loadObject( $sql, $obj ) && $nutrition_id > 0)
{
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}


//load nutrition service rendered entries for this nutriotion visit
if ($nutrition_id > 0){
	$q = new DBQuery();
	$q->addTable("nutrition_service");
	$q->addQuery("nutrition_service.*");
	$q->addWhere("nutrition_service_visit_id = " . $obj->nutrition_id);
	$nutras = $q->loadList();
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

//load centers
$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinicArray = arrayMerge(array(0=> '-Select Center-'),$q->loadHashList());


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

$client_id = $client_id ? $client_id : $obj->nutrition_client_id;

$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $nutrition_id > 0 ? "Edit Nutrition Visit : " . $clientObj->getFullName() : "New Nutrition Visit: " . $clientObj->getFullName();

}
else
{
   $ttl = $nutrition_id > 0 ? "Edit Nutrition Visit " : "New Nutrition Visit ";

}

/*$q= new DBQuery();
$q->addTable('admission_info');
$q->addWhere('admission_client_id = "'.(int)$client_id.'"');
$q->addQuery('admission_clinic_id');
$thisClientClinic=$q->loadResult();
*/


$date_reg = date("Y-m-d");
$counselling_dob = $clientObj->getDOB();
$entry_date = intval( $obj->nutrition_entry_date) ? new CDate( $obj->nutrition_entry_date ) : new CDate( $date_reg );
$next_date = intval( $obj->nutrition_next_visit) ? new CDate( $obj->nutrition_next_visit ) : "";
$dob = intval( $counselling_dob) ? new CDate( $counselling_dob ) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

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


$age_years = 0;
$age_months = 0;
$age_years = $obj->nutrition_age_yrs;
$age_months = $obj->nutrition_age_months;

if ($nutrition_id == 0)
{
  if (isset($clientObj))
  {
	$clientObj->getAge($age_years,$age_months);
  }
}

$formAge = $clientObj->age();

$black_tea = explode(",", $obj->nutrition_blacktea);
$white_tea = explode(",", $obj->nutrition_whitetea);
$bread = explode(",", $obj->nutrition_bread);
$porridge = explode(",", $obj->nutrition_porridge);
$milk = explode(",", $obj->nutrition_milk);
$water = explode(',',$obj->nutrition_water);
$ugali = explode(',',$obj->nutrition_ugali);
$rice = explode(',',$obj->nutrition_rice);
$banans = explode(',',$obj->nutrition_banan);
$tubers = explode(',',$obj->nutrition_tubers);
$wheat = explode(',',$obj->nutrition_wheat);
$carb_others = explode(',',$obj->nutrition_carbos_notes);
$bev_others = explode(',',$obj->nutrition_beverages_notes);
$breastfeeding = explode(",", $obj->nutrition_breastfeeding);
$formula_milk = explode(",", $obj->nutrition_formula_milk);
$eggs = explode(",", $obj->nutrition_eggs);
$meat = explode(",", $obj->nutrition_meat);
$carbohydrates = explode(",", $obj->nutrition_carbohydrates);
$protein_others = explode(",", $obj->nutrition_protein_notes);
$legumes = explode(",", $obj->nutrition_legumes);
$pancake = explode(",", $obj->nutrition_pancake);
$vegetables = explode(",", $obj->nutrition_vegetables);
$fruit = explode(",", $obj->nutrition_fruit);
$fat = explode(",", $obj->nutrition_fat);
$others = explode(",", $obj->nutrition_diet_history_others);
$water_purification = explode(",", $obj->nutrition_water_purification);
$water_access = explode(",", $obj->nutrition_water_access);
$food_enrichment = explode(",", $obj->nutrition_food_enrichment);

$progs = explode(",", $obj->nutrition_program);

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeNutrition'])", "Clear All Selections" );
if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName());
/*
if ($nutrition_id != 0)
  $titleBlock->addCrumb( "?m=nutrition&a=view&nutrition_id=$nutrition_id", "View" );*/
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeNutrition ;
	form.nutrition_service_rows.value=$j("#nutras").find("tr").length;
	if (form.nutrition_entry_date && form.nutrition_entry_date.value.length > 0)
	{
		errormsg = checkValidDate(form.nutrition_entry_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.nutrition_entry_date.focus();
			exit;
		}
	}

	if(!manField("staff_id")){
		alert("Please select Staff!");
		return false;
	}
	if(!manField("clinic_id")){
		alert("Please select Center!");
		return false;
	}	
	
	if (form.nutrition_weight && form.nutrition_weight.value.length > 0)
	{
		if (isNaN(parseInt(form.nutrition_weight.value,10)) )
		{
			alert(" Invalid Weight");
			form.nutrition_weight.focus();
			exit;

		}
	}
	if (form.nutrition_height && form.nutrition_height.value.length > 0)
	{
		if (isNaN(parseInt(form.nutrition_height.value,10)) )
		{
			alert(" Invalid Height");
			form.nutrition_height.focus();
			exit;

		}
	}

	if (!advNumeric(form.nutrition_muac)){
		alert(" Invalid MUAC");
		return false;
	}

	form.submit();
}

function advNumeric(vald){
	res=false;
	if($j(vald) ){
		var val=$j(vald).val();
		if(!val || val.length === 0){
			res=true;
		}
		else if(val && val.length > 0){
			val=val.replace("<","").replace(">","");
			val=Math.abs(trim(val));		
			if(val >= 0){
				res=true;
			}
		}
	}
	if(res === false){
		vald.focus();
	}	
	return res;
}

// Reads the selections in the form and computes the
// totals, filling these in.
function UpdateTotals(table_id)
{
  var numrows = document.getElementById(table_id).rows.length - 1;  // don't count the header row!
  var i, totalcost = 0.00;
  for (i = 1; i <= numrows; i++) {

      // Compute total for each row

    var q = parseInt(document.getElementById('quant_' + i).value);
    var price = parseFloat(document.getElementById('price_' + i).value);
    var cost;
    if (!q || !price)
      cost = 0.00;
    else
      cost = q * price;
    var total = document.getElementById('total_' + i);
    total.value = '$' + cost;
    totalcost = totalcost + cost;  // Keep running grand total
  }
  var total = document.getElementById('total');
  total.value = '$' + totalcost;
}

//Given a tr node and row number (newid), this iterates over the row in the
//DOM tree, changing the id attribute to refer to the new row number.
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

//Appends a row to the given table, at the bottom of the table.
var topCount=0;
function AppendRow(table_id,hId){

	var $tbody = $j("#"+table_id).find("tbody"),
	curamnt=$j("tr",$tbody).length;
	if(topCount === 0 ){
		topCount=curamnt + 1;
	}else{
		++topCount;
	}
	
	var row = $j("tr:eq(0)",$tbody),  // 1st row
		newid = topCount,  // Since this includes the header row, we don't need to add one
		newrow = $j(row).clone(true);

	rowrenumber(newrow, newid,hId);

	$j(newrow)
		.find("input").val("").end()
		.find("#delete_"+newid+" #delete_1").html();
	row.parentNode.appendChild(newrow);      // Attach to table
// Clear out data from new row.
}

function NewHistoryRow(table_id,hId){
	
	var $tbody = $j("#"+table_id).find("tbody"),
	curamnt=$j("tr",$tbody).length;
	if(topCount === 0 ){
		topCount=curamnt + 1;
	}else{
		++topCount;
	}
	var row = $j("tr:eq(0)",$tbody),  // 1st row
    newid = topCount,  // Since this includes the header row, we don't need to add one
    newrow = $j(row).clone(true);

rowrenumber(newrow, newid,hId);
$j(newrow)
//	.find(".nmb").text(20 + curamnt).end()
	//.find("img").remove().end()
	
	.find("input").val("").end()
	.find("select > option:eq(0)").attr("selected",true).end()
	//.find("#date_"+newid).attr("class",'text').end()
	.find("#delete_"+newid+" #delete_1").html();
$tbody.append(newrow);

//attachPicker($j("#date_"+newid),'');
//Clear out data from new row.

}

//Give a node within a row of the table (one level down from the td node),
//this deletes that row, renumbers the other rows accordingly, updates
//the Grand Total, and hides the delete button if there is only one row
//left.
function DeleteRow(el){
var row = el.parentNode.parentNode,   // tr node
rownum = row.rowIndex,            // row to delete
tbody = row.parentNode,           // tbody node
ctable = tbody.parentNode,
numrows = tbody.rows.length - 1;  
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
/*if($j(ctable).attr("id") == 'nutras'){
	var $tbody=$j(tbody);
	$j("tr",$tbody).each(function(i){
		$j(this).find("span.nmb").text(20 + i);
	});
}*/

}
 function whoFldToggle(opt){
 	if($j(".caresel:checked").val() == "1"){
 		$j(".whfblock").show();
 	}else{
 		$j(".whfblock").find(":input").val("").end().hide();
 	}
 }
</script>

<form name="changeNutrition" action="?m=nutrition" method="post">
	<input type="hidden" name="dosql" value="do_nutrition_aed" />
	<input type="hidden" name="nutrition_id" value="<?php echo $nutrition_id;?>" />
	<input type="hidden" name="nutrition_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" name="nutrition_age_yrs" value="<?php echo  intval(@$formAge[0]);?>">
	<input type="hidden" name="nutrition_age_months" value="<?php echo  intval(@$formAge[1]);?>">
	<input type="hidden" name="nutrition_service_rows" value="0" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">
<tr>
<td valign="top" width="100%">
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
				<?php echo arraySelect( $clinicArray, 'nutrition_center', 'size="1" class="text" id="clinic_id"', @$obj->nutrition_center ? $obj->nutrition_center:0,0);
				?>
			</td>
		 </tr>
		 <tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left">
			<?php
				echo drawDateCalendar('nutrition_entry_date',$entry_date ? $entry_date->format( $df ) : "");
				//<input type="text" name="nutrition_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;" class="text"  />
			?>
			&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
			</td>
       </tr>
       <tr>
         <td align="left">1c.<?php echo $AppUI->_('Nutritionist');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'nutrition_staff_id', 'size="1" id="staff_id" class="text"', @$obj->nutrition_staff_id ? $obj->nutrition_staff_id:'' ,false,0); ?>
			</td>
       </tr>
	   
      <tr>
	<tr>
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="nutrition_client_code" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
	 <tr>
         <td align="left">2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="nutrition_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Gender');?>:</td>

		<td>
		<?php echo /*arraySelectRadio($genderTypes, "nutrition_gender", 'onclick=toggleButtons() readonly="readonly" disabled', $admissionObj->admission_gender ?$admissionObj->admission_gender : -1, $identifiers );*/
			$genderTypes[$clientObj->client_gender];
		 ?>
		</td>
       
      <tr>
         <td align="left"><?php echo $AppUI->_('Date of Birth');?>:</td>
			<td align="left">
				<input type="text" name="nutrition_dob" value="<?php echo $dob ? $dob->format( $df ) : "" ;?>" class="text" readonly disabled="disabled" />
			</td>
	 </tr>
	 <tr>
         <td align="left">3a.<?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left">
	    <input type="text" class="text" disabled="disabled" name="nutrition_age_yrs" value="<?php echo dPformSafe(@$formAge[0]);?>" maxlength="30" size="20" readonly />
		</td>
		</tr>
		<tr>
		<td><?php echo $AppUI->_('Age (months)');?>:</td>
		<td>
	    <input type="text" class="text" disabled="disabled" name="nutrition_age_months" value="<?php echo dPformSafe(@$formAge[1]);?>" maxlength="30" size="20" readonly />
		 </td>
	    </tr>
	    <tr>
         <td align="left">3b.<?php echo $AppUI->_('Child attending');?>:</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "nutrition_child_attend", '', $obj->nutrition_child_attend ? $obj->nutrition_child_attend : -1, $identifiers ); ?>
		</td>
		</tr>
       <tr>
         <td align="left">3c.<?php echo $AppUI->_('Caregiver attending');?>:</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "nutrition_care_attend", 'class="caresel"', $obj->nutrition_care_attend ? $obj->nutrition_care_attend : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr class="whfblock" >
         <td align="left">3d.<?php echo $AppUI->_('Who');?>:</td>
		<td>
		<input type="text" class="text" name="nutrition_care_who" value="<?php echo dPformSafe(@$obj->nutrition_care_who);?>" maxlength="150" size="40" />
		</td>
		</tr>
	<!-- <tr>
         <td align="left"><?php echo $AppUI->_('Caregiver');?>:</td>
		<td>
		<?php/* echo arraySelectRadio($caregiverType, "nutrition_caregiver_type", 'onclick=toggleButtons()', $obj->nutrition_caregiver_type ? $obj->nutrition_caregiver_type : -1, $identifiers ); */?>
		</td>
       </tr> 
	   <tr>
         <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		<td>
		<input type="text" class="text" name="nutrition_caregiver_type_notes" value="<?php echo dPformSafe(@$obj->nutrition_caregiver_type_notes);?>" maxlength="150" size="40" />
		</td>
       </tr> -->
	<tr>
			<td colspan="2" align="left">
				<strong>B.<?php echo $AppUI->_('Anthropometry'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
        <td align="left">4a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left">
            <input type="text" class="text" name="nutrition_weight" value="<?php echo dPformSafe(@$obj->nutrition_weight);?>" maxlength="30" size="20" />
        </td>
      </tr>
      <tr>
			<td align="left">4b.<?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="nutrition_height" id="nutrition_height" value="<?php echo $obj->nutrition_height;?>" maxlength="30" size="20"/></td>
      </tr>
      <!--  <tr>
			<td align="left"><?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top"><input type="text" disabled="disabled" class="text" name="nutrition_zscore" id="nutrition_zscore" value="<?php echo $obj->nutrition_zscore;?>" maxlength="30" size="20"/></td>
      </tr> -->
	  <tr>
			<td align="left">4c.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="nutrition_muac" id="nutrition_muac" value="<?php echo $obj->nutrition_muac;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
        <td align="left">4d.<?php echo $AppUI->_('Oedema');?>:</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "nutrition_oedema", 'onclick=toggleButtons()', $obj->nutrition_oedema ? $obj->nutrition_oedema : '', $identifiers ); ?>
		</td>

       </tr>
	  <tr>
			<td align="left">5a.<?php echo $AppUI->_('WFH');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="nutrition_wfh" id="nutrition_wfh" value="<?php echo $obj->nutrition_wfh;?>" maxlength="30" size="20"/></td>
      </tr>
	  <tr>
			<td align="left">5b.<?php echo $AppUI->_('WFA');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="nutrition_wfa" id="nutrition_wfa" value="<?php echo $obj->nutrition_wfa;?>" maxlength="30" size="20"/></td>
      </tr>
	  <tr>
			<td align="left">5c,d<?php echo $AppUI->_('BMI');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="nutrition_bmi" id="nutrition_bmi" value="<?php echo $obj->nutrition_bmi;?>" maxlength="30" size="20"/>
			</td>
      </tr>
	<tr>
			<td colspan="2" align="left">
				<strong>C.<?php echo $AppUI->_('Diet History: Usual Food Intake'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
	 <td colspan="2">
	 <table>
	 	<thead>
	 	<tr>
	 		<th colspan="2">Food Item</th>
	 		<th >Breakfast</th>
	 		<th >Mid-morning</th>
	 		<th >Lunch</th>
	 		<th >Mid-afternoon</th>
	 		<th >Supper</th>
	 	</tr>
	 	</thead>
	 	<tbody>
	 	<tr>
	 		<td rowspan="5">6.Beverages</td>
         	<td align="left" valign="top"><?php echo $AppUI->_('Black tea');?>:</td>
			<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_blacktea[]", '', $black_tea, false,1 ,false); ?>
		</tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('White tea');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_whitetea[]", '', $white_tea, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Porridge');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_porridge[]", '', $porridge, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Water');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_water[]", '', $water, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:
         	<input type="text" class="text" name="nutrition_beverages_title" value="<?php echo $obj->nutrition_beverages_title;?>" size="10">
         </td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_beverages_notes[]", '', $bev_others, false,1 ,false); ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="6">7.Carbohydrates</td>
         	<td align="left" valign="top"><?php echo $AppUI->_(' Ugali / Maize');?>:</td>
			<?php
				echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_ugali[]", '', $ugali, false,1 ,false);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Rice');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_rice[]", '', $rice, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Bananas');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_banan[]", '', $banans, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Tubers');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_tubers[]", '', $tubers, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Wheat products');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_wheat[]", '', $wheat, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:
         	<input type="text" class="text" name="nutrition_carbos_title" value="<?php echo $obj->nutrition_carbos_title;?>" size="10">
         </td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_carbos_notes[]", '', $carb_others, false,1 ,false); ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="5">8.Protein</td>
         	<td align="left" valign="top"><?php echo $AppUI->_(' Legumes / Pulses / Nuts');?>:</td>
			<?php
				echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_legumes[]", '', $legumes , false,1 ,false);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Milk / Milk products');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_milk[]", '', $milk, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Meat / Meat products');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_meat[]", '', $meat, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Eggs');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_eggs[]", '', $eggs, false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:
         	<input type="text" class="text" name="nutrition_protein_title" value="<?php echo $obj->nutrition_protein_title;?>" size="10">
         </td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_protein_notes[]", '', $protein_others, false,1 ,false); ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="5">9.Others</td>
         	<td align="left" valign="top"><?php echo $AppUI->_('Vegetables');?>:</td>
			<?php
				echo arraySelectCheckboxMultiCol($dietHistoryOptions,"nutrition_vegetables[]", '', $vegetables  , false,1 ,false);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Fruits / Juices');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_fruit[]", '', $fruit,false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Fats and Oils');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_fat[]", '', $fat,false,1,false ); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Breast milk');?>:</td>
		 <?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_breastfeeding[]", '', $breastfeeding ,false,1,false); ?>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Formula milk');?>:</td>
		<?php echo arraySelectCheckboxMultiCol($dietHistoryOptions, "nutrition_formula_milk[]", '', $formula_milk ,false,1,false); ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       </tbody>
	 </table>
	 </td>
	 </tr>

		<tr>
         <td align="left" valign="top">10.<?php echo $AppUI->_('How are the foods enriched?');?></td>
		 <td>
		<?php echo arraySelectCheckbox($foodEnrichmentType, "nutrition_food_enrichment[]", '', $food_enrichment ); ?>
		 </td>
       </tr>
	   <tr>
	   <td align="left" valign="top">10f...<?php echo $AppUI->_('Other');?>:</td>
	   <td align="left">
	   <input type="text" class="text" name="nutrition_food_enrichment_notes" id="nutrition_food_enrichment_notes" value="<?php echo $obj->nutrition_food_enrichment_notes;?>" maxlength="40" size="40"/>
	   </td>
	   </tr>
		<tr>
         <td align="left" valign="top">11.
		 <?php echo $AppUI->_('Where does the household ');?><br/>
		 <?php echo $AppUI->_('access water for daily use?');?>
		 </td>
		 <td>
		<?php echo arraySelectCheckbox($waterSourceTypes, "nutrition_water_access[]", '', $water_access ); ?>
		 </td>
       </tr>
		<tr>
         <td align="left" valign="top">12.<?php echo $AppUI->_('How do you purify drinking water?');?></td>
		 <td>
		<?php echo arraySelectCheckbox($waterPurificationTypes, "nutrition_water_purification[]", '', $water_purification ); ?>
		 </td>
       </tr>
	   <tr>
	   <td align="left" valign="top">12d...<?php echo $AppUI->_('Other');?>:</td>
	   <td align="left">
	   <input type="text" class="text" name="nutrition_water_purification_notes" id="nutrition_water_purification_notes" value="<?php echo $obj->nutrition_water_purification_notes;?>" maxlength="40" size="40"/>
	   </td>
	   </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs Assessment and Services Rendered'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	<tr>

	</tr>
	<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Identify any potential problems or concerns');?>:</td>
		</tr>
		 <tr>
        <td align="left" >13.a<?php echo $AppUI->_('Adequate quantity (meal frequency)');?>:</td>
		<td>
		<?php echo arraySelectRadio($boolRev, "nutrition_quantity", 'onclick=toggleButtons()', $obj->nutrition_quantity ? $obj->nutrition_quantity : -1, $identifiers ); ?>
		</td>

       </tr>
	   <tr>
        <td align="left" >13b.<?php echo $AppUI->_('Adequate quality (dietary diversity)');?>:</td>
		<td>
		<?php echo arraySelectRadio($boolRev, "nutrition_quality", 'onclick=toggleButtons()', $obj->nutrition_quality ? $obj->nutrition_quality : -1, $identifiers ); ?>
		</td>

		</td>
       </tr>
	   <tr>
        <td align="left">13c.<?php echo $AppUI->_('Poor practices or preparation');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_poor_preparation", 'onclick=toggleButtons()', $obj->nutrition_poor_preparation ? $obj->nutrition_poor_preparation : -1, $identifiers ); ?>
		</td>

       </tr>
	   <tr>
        <td align="left">13d.<?php echo $AppUI->_('Mixed feeding');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_mixed_feeding", 'onclick=toggleButtons()', $obj->nutrition_mixed_feeding ? $obj->nutrition_mixed_feeding : -1, $identifiers ); ?>
		</td>

       </tr>
	   <tr>
        <td align="left">13e.<?php echo $AppUI->_('Unclean drinking water');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_unclean_drinking_water", 'onclick=toggleButtons()', $obj->nutrition_unclean_drinking_water ? $obj->nutrition_unclean_drinking_water : -1, $identifiers ); ?>
		</td>
       </tr>
       <tr>
        <td align="left">13f...<?php echo $AppUI->_('Other');?>:</td>
		<td>
		<input type="text" class="text" name="nutrition_issue_notes" id="nutrition_issue_notes" value="<?php echo $obj->nutrition_issue_notes;?>" maxlength="40" size="40"/>
		</td>
       </tr>
    <tr>
         <td align="left" valign="top">14.<?php echo $AppUI->_('Recommended Food Program');?>:</td>
         <td>
         <?php echo arraySelectCheckbox(dPgetSysVal('NutritionProgram'),'nutrition_program[]','',$obj->nutrition_program ? $obj->nutrition_program  : -1,$identifiers); ?>
         </td>
	</tr>
	 <tr>
        <td align="left">...<?php echo $AppUI->_('Other ');?>:</td>
        <td>
        	<input type="text" class="text" name="nutrition_program_other" value="<?php echo dPformSafe(@$obj->nutrition_program_other);?>" maxlength="40" size="40" />
		</td>
     </tr>
	<tr>
        <td align="left" valign="top">15.<?php echo $AppUI->_('Services Rendered');?>:</td>
        <td>
		<?php echo arraySelectCheckboxFlat($renders, "nutrition_rendered[]", '', $obj->nutrition_rendered ? $obj->nutrition_rendered : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
        <td align="left">15...<?php echo $AppUI->_('Other ');?>:
        </td>
        <td>
        	<input type="text" class="text" name="nutrition_service_other" value="<?php echo dPformSafe(@$obj->nutrition_service_other);?>" maxlength="40" size="40" />
		</td>

     </tr>


	<!--    <tr>
        <td align="left"><?php echo $AppUI->_('b.	Nutrition Counselling');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_counselling", 'onclick=toggleButtons()', $obj->nutrition_counselling ? $obj->nutrition_counselling : -1, $identifiers ); ?>
		</td>

       </tr>
	   <tr>
        <td align="left"><?php echo $AppUI->_('c.	Demonstration');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_demonstration", 'onclick=toggleButtons()', $obj->nutrition_demonstration ? $obj->nutrition_demonstration : -1, $identifiers ); ?>
		</td>

       </tr>
	   <tr>
        <td align="left"><?php echo $AppUI->_('d.	Dietary Supplementation ');?>:</td>
		<td>
		<?php echo arraySelectRadio($scoreTypes, "nutrition_dietary_supplement", 'onclick=toggleButtons()', $obj->nutrition_dietary_supplement ? $obj->nutrition_dietary_supplement : -1, $identifiers ); ?>
		</td>
		</tr> -->
      	 <tr>
		<td>&nbsp;</td>
		 <td align="left">
		 <table>
		   <tr>
		    <td>
				 <table id="nutras">
				 	<thead>
				 	<tr>
					 <th><?php echo $AppUI->_('Received Food Program');?></th>
					 <th><?php echo $AppUI->_('Item');?></th>
					 <th><?php echo $AppUI->_('Qty');?></th>
					 <th>&nbsp;</th>
					 </tr>
					</thead>
					<tbody>
					 <?php
					 $rowcount = 1;
					 if (count($nutras) == 0 ) {
					 	$nutras = array(0=>array());
					 }
						foreach ($nutras as $row) {
					 ?>
					 <tr>
						 <td align="left">
						 	<input type="hidden" name="nutrition_service_id_<?php echo $rowcount; ?>" value="<?php echo @$row["nutrition_service_id"]?>" />
						 	<?php
						 		echo arraySelect($past_progs,'program_'.$rowcount,'',$row['nutrition_service_program'] > 0 ? $row['nutrition_service_program'] : -1);
						 	?>
						 </td>
						 <td align="left">
						 	<input type="text" class="text" id="item_<?php echo $rowcount; ?>" name="item_<?php echo $rowcount; ?>" value="<?php echo $row["nutrition_service_item"];?>" maxlength="150" size="20" />
						 </td>
						 <td align="left">
						 	<input type="text" class="text" id="qty_<?php echo $rowcount; ?>" name="qty_<?php echo $rowcount; ?>" value="<?php echo $row["nutrition_service_qty"];?>" maxlength="150" size="20" />
						 </td>
						 <td align="left">
				              <span id="delete_<?php echo $rowcount; ?>" style="color:red; cursor: pointer;" onclick="DeleteRow(this);">X</span>
				         </td>
					 </tr>
					 <?php
							$rowcount++;
						} //end for
					  ?>
				</tbody>
				</table>
			  </td>
            </tr>
		 <tr>
			<td>
				<input class="button" type="button" name="append" value="new entry" onclick="NewHistoryRow('nutras','nutrition_service_id'); return false;"/>
			</td>
		</tr>
		 </table>
		 </td>
	  </tr>
	<tr>
        <td align="left">20.<?php echo $AppUI->_('Refer To');?>:</td>
		<td>
		<?php echo arraySelect($refers, "nutrition_refer", 'class="text"', $obj->nutrition_refer ? $obj->nutrition_refer : -1, $identifiers ); ?>
		</td>
	</tr>
	<tr>
    	<td align="left">20b...<?php echo $AppUI->_('Other ');?>:</td>
    	<td align="left"><input type="text" class="text" name="nutrition_refer_other" value="<?php echo dPformSafe(@$obj->nutrition_refer_other);?>" maxlength="40" size="40" />
		</td>
    </tr>

	  <tr>
		<td align="left" valign="top">21.<?php echo $AppUI->_('Next appointment');?>:</td>
		<td align="left" valign="top">
			<?php echo drawDateCalendar('nutrition_next_visit',$next_date ? $next_date->format($df) : '',false,'id="next_visit"');?>
		</td>
     </tr>

	 <tr>
		<td align="left" valign="top">22.<?php echo $AppUI->_('Comments (on reverse)');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="nutrition_notes"><?php echo @$obj->nutrition_notes;?></textarea>
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


</td>

	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->nutrition_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
</tr>



</table>
</form>
