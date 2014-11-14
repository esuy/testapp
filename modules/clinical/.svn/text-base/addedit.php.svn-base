<?php
$clinical_id = intval( dPgetParam( $_GET, "clinical_id", 0 ) );
$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('social'));
require_once ($AppUI->getModuleClass('counsellinginfo'));

// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($clinical_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $clinical_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );

// load the record data
$q  = new DBQuery;
$q->addTable('clinical_visits');
$q->addQuery('clinical_visits.*');
$q->addWhere('clinical_visits.clinical_id = '.$clinical_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CClinicalVisit();
if (!db_loadObject( $sql, $obj ) && $clinical_id > 0)
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
//$clinics = $q->loadHashList();
$clinics = arrayMerge(array(-1=> '-Select Clinic -'),$q->loadHashList());
//var_dump($clinics);

$boolTypes = dPgetSysVal('YesNo');
$diarrhoeaTypes = dPgetSysVal('DiarrhoeaType');
$dehydrationTypes = dPgetSysVal('DehydrationType');
$treatmentTypes = dPgetSysVal('TreatmentType');
$tbTypes = dPgetSysVal('TBPulmonaryType');
$growthTypes = dPgetSysVal('GrowthType');
$malnutrionTypes = dPgetSysVal('MalnutritionType');
$earTypes = dPgetSysVal('EarType');
$clearTypes = dPgetSysVal('ClearTypes');
$pneumoniaTypes = dPgetSysVal('PneumoniaTypes');
$crepTypes = dPgetSysVal('CrepitationTypes');
$skinTypes = dPgetSysVal('SkinOptions');
$therapyTypes = dPgetSysVal('TherapyStage');

$arvTypes = dPgetSysVal('ARVType');
$vitaminTypes = dPgetSysVal('VitaminTypes');
$tbDrugsTypes = dPgetSysVal('TBDrugsType');
$nutritionTypes = dPgetSysVal('NutritionType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

$vitamins = explode(",", $obj->clinical_vitamins);
$arv_types = explode(",", $obj->clinical_arv_drugs);
$clinical_tb_drugs = explode("," ,$obj->clinical_tb_drugs);
// setup the title block

//load client

if ((!empty($client_id)) || (!empty($obj->clinical_client_id)))
{
   $clientObj = new CClient();

   if ((!$clientObj->load($client_id)) && (!$clientObj->load($obj->clinical_client_id)))
   {
		$AppUI->setMsg('Client ID');
		$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
		$AppUI->redirect("?m=clients");
   }
   $client_name =  $clientObj->getFullName();
}

$client_id = $client_id ? $client_id : $obj->counselling_client_id;

$clientObj = new CClient();
if ($clientObj->load($client_id)){
	$ttl = $clinical_id > 0 ? "Edit Clinical Visit : " . $clientObj->getFullName() : "New Clinical Visit: " . $clientObj->getFullName();
}else{
   $ttl = $clinical_id > 0 ? "Edit Clinical Visit " : "New Clinical Visit ";
}

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

$age_years = 0;
$age_months = 0;
$age_years = $obj->clinical_age_yrs;
$age_months = $obj->clinical_age_months;

if ($clinical_id==0)
{
  if (isset($clientObj))
  {
	$clientObj->getAge($age_years,$age_months);
  }
}

$ages = calcIt($clientObj->getDOB());
preg_match_all("/\s?(\d*)\s/",$ages['v'],$formAges);

$date_reg = date("Y-m-d");
$entry_date = intval( $obj->clinical_entry_date ) ? new CDate( $obj->clinical_entry_date ) : new CDate( $date_reg );
$nutritional_support = explode(",", $obj->clinical_nutritional_support);
$tb_treatment_date = intval( $obj->clinical_tb_treatment_date ) ? new CDate( $obj->clinical_tb_treatment_date ) : NULL;
$blood_test_date = intval( $obj->clinical_bloodtest_date ) ? new CDate( $obj->clinical_bloodtest_date ) : NULL;
$next_appointment_date = intval( $obj->clinical_next_date ) ? new CDate( $obj->clinical_next_date ) : NULL;

$whostages = array(1=>' 1st',2=>'2nd',3=>'3rd',4=>'4th');
$clinstages = $whostages;
array_pop($clinstages);

//$refers = arrayMerge(array(-1=>'- Select Position -'), dPgetSysVal('ClinicalReference'));//PositionOptions
$refers = arrayMerge(array(0=>'--Select Position--'),  dPgetSysVal('PositionOptions'));

$df = $AppUI->getPref('SHDATEFORMAT');

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "clearSelection(document.forms['changeClinical'])", "Clear All Selections" );
if ($clientObj->client_id > 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$clientObj->client_id", $clientObj->getFullName() );

/*if ($clinical_id != 0)
  $titleBlock->addCrumb( "?m=clinical&a=view&clinical_id=$clinical_id", "View" );
  */
$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeClinical ;
	if(!manField("staff_id")){
		alert("Please select Staff!");
		return false;
	}
	if(!manField("clinic_id")){
		alert("Please select Center!");
		return false;
	}	
	if (form.clinical_entry_date && form.clinical_entry_date.value.length > 0)
	{
		errormsg = checkValidDate(form.clinical_entry_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.clinical_entry_date.focus();
			exit;
		}
	}
	if (form.clinical_bloodtest_date && form.clinical_bloodtest_date.value.length > 0)
	{
		errormsg = checkValidDate(form.clinical_bloodtest_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid blood test date" );
			form.clinical_bloodtest_date.focus();
			exit;
		}
	}
	if (form.clinical_bloodtest_cd4 && form.clinical_bloodtest_cd4.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_bloodtest_cd4.value,10)) )
		{
			alert(" Invalid CD4 Value");
			form.clinical_bloodtest_cd4.focus();
			exit;

		}
	}
	if (form.clinical_bloodtest_cd4_percentage && form.clinical_bloodtest_cd4_percentage.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_bloodtest_cd4_percentage.value,10)) )
		{
			alert(" Invalid CD4 (%) Value");
			form.clinical_bloodtest_cd4_percentage.focus();
			exit;

		}
	}


	/*if (form.clinical_age_yrs && form.clinical_age_yrs.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_age_yrs.value,10)) )
		{
			alert(" Invalid Age (years)");
			form.clinical_age_yrs.focus();
			exit;

		}
	}*/


	if (form.clinical_weight && form.clinical_weight.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_weight.value,10)) )
		{
			alert(" Invalid Weight");
			form.clinical_weight.focus();
			exit;

		}
	}
	if (form.clinical_height && form.clinical_height.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_height.value,10)) )
		{
			alert(" Invalid Height");
			form.clinical_height.focus();
			exit;

		}
	}
	/*if (form.clinical_zscore && form.clinical_zscore.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_zscore.value,10)) )
		{
			alert(" Invalid z Score");
			form.clinical_zscore.focus();
			exit;

		}
	}*/
	if (form.clinical_muac && form.clinical_muac.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_muac.value,10)) )
		{
			alert(" Invalid MUAC");
			form.clinical_muac.focus();
			exit;

		}
	}
	if (form.clinical_hc && form.clinical_hc.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_hc.value,10)) )
		{
			alert(" Invalid Head Circumference");
			form.clinical_hc.focus();
			exit;

		}
	}

	if (form.clinical_temp && form.clinical_temp.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_temp.value,10)) )
		{
			alert(" Invalid Temperature");
			form.clinical_temp.focus();
			exit;

		}
	}
	if (form.clinical_resp_rate && form.clinical_resp_rate.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_resp_rate.value,10)) )
		{
			alert(" Invalid Resp. Rate");
			form.clinical_resp_rate.focus();
			exit;

		}
	}
	if (form.clinical_heart_rate && form.clinical_heart_rate.value.length > 0)
	{
		if (isNaN(parseInt(form.clinical_heart_rate.value,10)) )
		{
			alert(" Invalid Heart Rate");
			form.clinical_heart_rate.focus();
			exit;

		}
	}
	if (form.clinical_tb_treatment_date && form.clinical_tb_treatment_date.value.length > 0)
	{
		errormsg = checkValidDate(form.clinical_tb_treatment_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid TB treatment date" );
			form.clinical_tb_treatment_date.focus();
			exit;
		}
	}
	if (form.clinical_next_date && form.clinical_next_date.value.length > 0)
	{
		errormsg = checkValidDate(form.clinical_next_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid Next Visit date" );
			form.clinical_next_date.focus();
			exit;
		}
	}


	form.submit();
}


</script>

<form name="changeClinical" action="?m=clinical" method="post">
	<input type="hidden" name="dosql" value="do_clinical_aed" />
	<input type="hidden" name="clinical_id" value="<?php echo $clinical_id;?>" />
	<input type="hidden" name="clinical_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" name="clinical_age_yrs" value="<?php echo  intval(@$formAges[0][0]);?>">
	<input type="hidden" name="clinical_age_months" value="<?php echo  intval(@$formAges[0][1]);?>">
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td valign="top" width="100%">


<table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
       
	   <tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left">
          <?php echo arraySelect($clinics, "clinical_clinic_id", 'class="text" id="clinic_id"', $obj->clinical_clinic_id ); ?>
         </td>
		 </tr>
		 <tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left">
				<?php
					echo drawDateCalendar("clinical_entry_date",$entry_date ? $entry_date->format( $df ) : "");
					/* <input type="text" name="clinical_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;" class="text" /> */
				?>
				&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>

			</td>
       </tr>
       <tr>
         <td align="left">1c.<?php echo $AppUI->_('Clinician');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'clinical_staff_id', 'id="staff_id" size="1" class="text"', @$obj->clinical_staff_id ? $obj->clinical_staff_id:-1); ?>
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
         <td align="left"2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="clinical_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>

      <tr>
         <td align="left">3a.<?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left">
	    <input type="text" class="text" readonly="readonly" disabled="disabled" name="clinical_age_yrs" value="<?php echo dPformSafe(@$formAges[0][0]);?>" maxlength="30" size="20" readonly />
		 </td>
	 </tr>
	 <tr>
	 <td><?php echo $AppUI->_('Age (months)');?>:</td>
	 <td align="left">
	    <input type="text" class="text" readonly="readonly" disabled="disabled" name="clinical_age_months" value="<?php echo dPformSafe(@$formAges[0][1]);?>" maxlength="30" size="20" readonly />
		 </td>

	 </tr>
	  <tr>
		<td align="left">3b.<?php echo $AppUI->_('Child attending?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_child_attending", 'onclick=toggleButtons()', $obj->clinical_child_attending ? $obj->clinical_child_attending : -1, $identifiers ); ?></td>
     </tr>

	 <tr>
		<td align="left">3c.<?php echo $AppUI->_('Caregiver attending?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_caregiver_attending", 'onclick=toggleButtons()', $obj->clinical_caregiver_attending ? $obj->clinical_caregiver_attending : -1, $identifiers ); ?></td>
     </tr>
       <tr>
         <td align="left">3d...<?php echo $AppUI->_('Who?');?></td>
         <td align="left">
          <input type="text" class="text" name="clinical_caregiver" value="<?php echo dPformSafe(@$obj->clinical_caregiver);?>" maxlength="150" size="20" />
         </td>
       </tr>
	   	 <tr>
		<td align="left" nowrap="nowrap">4a.<?php echo $AppUI->_('Admission/illness since last visit?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_illness", 'onclick=toggleButtons()', $obj->clinical_illness ? $obj->clinical_illness : -1, $identifiers ); ?></td>
     </tr>

       <tr>
         <td align="left">4b...<?php echo $AppUI->_('If yes, specify');?>:</td>
         <td align="left">
          <input type="text" class="text" name="clinical_illness_notes" value="<?php echo dPformSafe(@$obj->clinical_illness_notes);?>" maxlength="150" size="20" />
         </td>
       </tr>
<!--	  <tr>
		<td align="left">5a.<?php echo $AppUI->_('Any Diarrhoea?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_diarrhoea", 'onclick=toggleButtons()', $obj->clinical_diarrhoea ? $obj->clinical_diarrhoea : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left">5b.<?php echo $AppUI->_('Any Vomiting?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_vomiting", 'onclick=toggleButtons()', $obj->clinical_vomiting ? $obj->clinical_vomiting : -1, $identifiers ); ?></td>
     </tr> -->
     
     <tr>
		<td align="left">5a<?php echo $AppUI->_('Any Complaints?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_complaints", 'onclick=toggleButtons()', $obj->clinical_complaints ? $obj->clinical_complaints : -1, $identifiers ); ?></td>
     </tr>

       <tr>
         <td align="left">5b.<?php echo $AppUI->_('if Yes,current complaints');?>:</td>
         <td align="left">
		 <textarea cols="70" rows="2" class="textarea" name="clinical_current_complaints"><?php echo dPformSafe(@$obj->clinical_current_complaints);?></textarea>
         </td>
       </tr>
	<tr>
			<td align="left">
				<?php echo $AppUI->_('Last Blood Test'); ?>
			</td>
	 </tr>

      <tr>
         <td align="left">6a...<?php echo $AppUI->_('Date');?>:</td>
		 <td align="left">
		 <?php
		 	echo drawDateCalendar("clinical_bloodtest_date",$blood_test_date ? $blood_test_date->format( $df ) : "");
		 	//<input type="text" name="clinical_bloodtest_date" value="<?php echo $blood_test_date ? $blood_test_date->format( $df ) : "" ;" class="text" />&nbsp;dd/mm/yyyy
		 ?>

		</td>
	  </tr>
      <tr>
         <td align="left">6b...<?php echo $AppUI->_('CD4');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_bloodtest_cd4" value="<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4);?>" maxlength="30" size="20" />&nbsp;
		 </td>
	  </tr>
	  <tr>
	  		<td align="left">7c...<?php echo $AppUI->_('CD4%');?>:</td>
			<td align="left"><input type="text" class="text" name="clinical_bloodtest_cd4_percentage" value="<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4_percentage);?>" maxlength="30" size="20" /></td>

	  </tr>
	  <tr>
	  <td align="left">6d...<?php echo $AppUI->_('Viral load');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_bloodtest_viral" value="<?php echo dPformSafe(@$obj->clinical_bloodtest_viral);?>" maxlength="30" size="20" />
		 </td>

	  </tr>
	  <tr>
	  <td align="left">6e...<?php echo $AppUI->_('Hb');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_bloodtest_hb" value="<?php echo dPformSafe(@$obj->clinical_bloodtest_hb);?>" maxlength="30" size="20" />
		 </td>

	  </tr>
	  <tr>
	  	<td align="left"><?php echo $AppUI->_('X-ray results');?>:</td>
	  </tr>
	  <tr>
	  	<td align="left">7a.<?php echo $AppUI->_('...X-ray');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_xray_results" value="<?php echo dPformSafe(@$obj->clinical_xray_results);?>" maxlength="150" size="50" />
		 </td>
	  </tr>
	  <tr>
	  	<td align="left">7b.<?php echo $AppUI->_('...CT Scan');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_ctscan" value="<?php echo dPformSafe(@$obj->clinical_ctscan);?>" maxlength="150" size="50" />
		 </td>
	  </tr>
	  <tr>
	  	<td align="left">7c.<?php echo $AppUI->_('...AST/AL');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_astal" value="<?php echo dPformSafe(@$obj->clinical_astal);?>" maxlength="150" size="50" />
		 </td>
	  </tr>
	  <tr>
	  <td align="left">7d.<?php echo $AppUI->_('...Other results');?>:</td>
		 <td align="left">
			<input type="text" class="text" name="clinical_other_results" value="<?php echo dPformSafe(@$obj->clinical_other_results);?>" maxlength="150" size="50" />
		 </td>

	  </tr>
	 <tr>
		<td align="left" valign="top">8.<?php echo $AppUI->_('Nutritional support');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectCheckbox($nutritionTypes, "clinical_nutritional_support[]", 'class="text"', $nutritional_support ); ?>
		</td>
	  </tr>
	  <tr>
		<td align="left" valign="top">8b...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_nutritional_notes" id="clinical_nutritional_notes" value="<?php echo $obj->clinical_nutritional_notes;?>" maxlength="150" size="20"/></td>

		</tr>

	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Examination'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
        <td align="left">9a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left">
            <input type="text" class="text" name="clinical_weight" value="<?php echo dPformSafe(@$obj->clinical_weight);?>" maxlength="30" size="20" />
        </td>
      </tr>
      <tr>
		<td align="left">9b.<?php echo $AppUI->_('Height (cm)');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_height" id="clinical_height" value="<?php echo $obj->clinical_height;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
		<td align="left">9c.<?php echo $AppUI->_('z score');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_zscore" id="clinical_zscore" value="<?php echo $obj->clinical_zscore;?>" maxlength="30" size="20"/></td>
      </tr>     
      <tr>
		<td align="left">9d.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_muac" id="clinical_muac" value="<?php echo $obj->clinical_muac;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
		<td align="left">9e.<?php echo $AppUI->_('Head Circum (cm)');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_hc" id="clinical_hc" value="<?php echo $obj->clinical_hc;?>" maxlength="30" size="20"/></td>
      </tr>
	  <tr>
		<td align="left">10a...<?php echo $AppUI->_('Temp (Celcius)');?>:&nbsp;</td>
		<td align="left"><input type="text" class="text" name="clinical_temp" id="clinical_temp" value="<?php echo $obj->clinical_temp;?>" maxlength="30" size="20"/>&nbsp;</td>
      </tr>
	  <tr>
		<td align="left">10b...<?php echo $AppUI->_('Respiratory rate');?>:&nbsp;</td>
		<td align="left"><input type="text" class="text" name="clinical_resp_rate" id="clinical_resp_rate" value="<?php echo $obj->clinical_resp_rate;?>" maxlength="30" size="20"/>&nbsp;</td>
     </tr>
	 <tr>
	 	<td align="left">10c...<?php echo $AppUI->_('Heart rate');?>:&nbsp;</td>
		<td align="left"><input type="text" class="text" name="clinical_heart_rate" id="clinical_heart_rate" value="<?php echo $obj->clinical_heart_rate;?>" maxlength="30" size="20"/>&nbsp;</td>
	 </tr>

	 <tr>
		<td align="left">11a.<?php echo $AppUI->_('Pallor');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_pallor", 'onclick=toggleButtons()', $obj->clinical_pallor ? $obj->clinical_pallor : -1, $identifiers ); ?></td>
      </tr>
      <tr>
		<td align="left">11b.<?php echo $AppUI->_('Jaundice');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_jaundice", 'onclick=toggleButtons()', $obj->clinical_jaundice ? $obj->clinical_jaundice : -1, $identifiers ); ?></td>
      </tr>
      <tr>
		<td align="left">11c.<?php echo $AppUI->_('Oedema');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_oedema", 'onclick=toggleButtons()', $obj->clinical_oedema ? $obj->clinical_oedema : -1, $identifiers ); ?></td>
      </tr>
      <tr>
		  <td align="left">11d.<?php echo $AppUI->_('Clubbing ');?>:</td>
		  <td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_clubbing", 'onclick=toggleButtons()', $obj->clinical_clubbing ? $obj->clinical_clubbing : -1, $identifiers ); ?></td>
      </tr>
	  <tr>
		  <td align="left">11e.<?php echo $AppUI->_('Dehydration');?>:</td>
		  <td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_examination_dehydration", 'onclick=toggleButtons()', $obj->clinical_examination_dehydration ? $obj->clinical_examination_dehydration : -1, $identifiers ); ?></td>
      </tr>
	  <tr>
		  <td align="left">11f.<?php echo $AppUI->_('Lymph nodes');?>:</td>
		  <td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_examination_lymph", 'onclick=toggleButtons()', $obj->clinical_examination_lymph ? $obj->clinical_examination_lymph : -1, $identifiers ); ?></td>
      </tr>

      <tr>
		<td align="left">12a.<?php echo $AppUI->_('Cardiovascular');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_cardiovascular" id="clinical_cardiovascular" value="<?php echo $obj->clinical_cardiovascular;?>" maxlength="150" size="50"/></td>
      </tr>
      <tr>
		<td align="left">.<?php echo $AppUI->_('Respiratory ');?>:</td>
		<td align="left">13a.
			<?php echo arraySelectRadio($clearTypes, "clinical_chest_clear", 'onclick=toggleButtons()', $obj->clinical_chest_clear ? $obj->clinical_chest_clear : -1, $identifiers ); ?><br>				
			13b.<?php echo arraySelectRadio($crepTypes, "clinical_chest_creps", 'onclick=toggleButtons()', $obj->clinical_chest_creps ? $obj->clinical_chest_creps : -1, $identifiers ); ?>
		</td>
      </tr> 
      
	   <tr>
		<td align="left">13c...<?php echo $AppUI->_('Specify');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_chest" id="clinical_chest" value="<?php echo $obj->clinical_chest;?>" maxlength="150" size="50"/></td>
	   </tr>
      <tr>
		<td align="left"><?php echo $AppUI->_('Skin');?>:</td>
		<td align="left">14a.
				<?php echo arraySelectRadio($clearTypes, "clinical_skin_clear", 'onclick=toggleButtons()', $obj->clinical_skin_clear ? $obj->clinical_skin_clear : -1, $identifiers ); ?><br>
				14b.<?php echo arraySelectRadio($skinTypes, "clinical_skin_opts", 'onclick=toggleButtons()', $obj->clinical_skin_opts ? $obj->clinical_skin_opts : -1, $identifiers ); ?>
			</td>
      </tr>
	   <tr>
		<td align="left">14c...<?php echo $AppUI->_('Specify');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_skin" id="clinical_skin" value="<?php echo $obj->clinical_skin;?>" maxlength="150" size="50"/></td>
	   </tr>
      <tr>
		<td align="left">14d.<?php echo $AppUI->_('Ears discharge?');?>:</td>
		<td align="left" valign="top">
				<?php echo arraySelectRadio(dPgetSysVal('EarsOptions'),"clinical_ears_opt",'id="clinical_ears_opt"', $obj->clinical_ears_opt ? $obj->clinical_ears_opt : '',$identifiers);?>
			</td>
      </tr>
	  <!--  <tr>
			<td align="left"><?php echo $AppUI->_('...Ears ');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_ears" id="clinical_ears" value="<?php echo $obj->clinical_ears;?>" maxlength="150" size="50"/></td>
      </tr> -->
      <tr>
			<td align="left">15a.<?php echo $AppUI->_('Throat');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio(dPgetSysVal('ThroatOptions'),"clinical_throat",'id="clinical_throat"', $obj->clinical_ears_opt ? $obj->clinical_throat : '',$identifiers);?>
			</td>
      </tr>      
      <!--  <tr>
			<td align="left"><?php echo $AppUI->_('Mouth');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_mouth" disabled id="clinical_mouth" value="<?php echo $obj->clinical_mouth;?>" maxlength="150" size="50"/></td>
      </tr> -->
      <tr>
			<td align="left">15b.<?php echo $AppUI->_('Mouth Thrush');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio($boolTypes,"clinical_mouth_thrush",'id="clinical_mouth"', $obj->clinical_mouth ? $obj->clinical_mouth_thrush : '',$identifiers);?>
			</td>
      </tr>
      <tr>
			<td align="left">15c.<?php echo $AppUI->_('Mouth Ulcers');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio($boolTypes,"clinical_mouth_ulcer",'id="clinical_mouth_ulcer"', $obj->clinical_mouth_ulcer ? $obj->clinical_mouth_ulcer : '',$identifiers);?>
			</td>
      </tr>

	  <!-- <tr>
			<td align="left"><?php echo $AppUI->_('Teeth ');?>:</td>
			<td align="left" valign="top"><input type="text" disabled class="text" name="clinical_teeth" id="clinical_teeth" value="<?php echo $obj->clinical_teeth;?>" maxlength="150" size="50"/></td>
      </tr> -->
      <tr>
			<td align="left">15d.<?php echo $AppUI->_('Teeth ');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio(dPgetSysVal('TeethType'),"clinical_teeth_opt",'id="clinical_teeth_opt"', $obj->clinical_teeth_opt ? $obj->clinical_teeth_opt : '',$identifiers);?>
			</td>
      </tr>
      <tr>
			<td align="left">16.<?php echo $AppUI->_('Per/Abdomen');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_abdomen" id="clinical_abdomen" value="<?php echo $obj->clinical_abdomen;?>" maxlength="150" size="50"/></td>
      </tr>
      <tr>
			<td align="left">17a.<?php echo $AppUI->_('Central Nervous System');?>:</td>
			<td align="left">
				<?php echo arraySelectRadio(dPgetSysVal('CNSType'),"clinical_cns",'id="clinical_cns"', $obj->clinical_cns ? $obj->clinical_cns : '',$identifiers);?>
			</td>
	  </tr>
	  <tr>
	  		<td align="left">17b.<?php echo $AppUI->_('...Specify');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_neurodevt" id="clinical_neurodevt" value="<?php echo $obj->clinical_neurodevt;?>" maxlength="150" size="50"/></td>
      </tr>
      <tr>
			<td align="left">17c.<?php echo $AppUI->_('Musculoskeletal');?>:</td>
			<td align="left">
				<?php echo arraySelectRadio(dPgetSysVal('CNSType'),"clinical_muscle",'id="clinical_muscle"', $obj->clinical_muscle ? $obj->clinical_muscle : '',$identifiers);?>
			</td>
	  </tr>
	  <tr>
			<td align="left">17d.<?php echo $AppUI->_('...Specify');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_musculoskeletal" id="clinical_musculoskeletal" value="<?php echo $obj->clinical_musculoskeletal;?>" maxlength="150" size="50"/></td>
      </tr>
	<tr>
			<td align="left">18a.<?php echo $AppUI->_('Eyes');?>:</td>
			<td align="left">
				<?php echo arraySelectRadio(dPgetSysVal('CNSType'),"clinical_eyes",'id="clinical_eyes"', $obj->clinical_eyes ? $obj->clinical_eyes : '',$identifiers);?>
			</td>
	  </tr>
	  <tr>
			<td align="left">18b.<?php echo $AppUI->_('...Specify');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_eyes_opt" id="clinical_eyes_opt" value="<?php echo $obj->clinical_eyes_opt;?>" maxlength="150" size="50"/></td>
      </tr>
      <tr>
			<td align="left">18c...<?php echo $AppUI->_('Other');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="clinical_other" id="clinical_other" value="<?php echo $obj->clinical_other;?>" maxlength="150" size="50"/></td>
      </tr>
      <tr>
		<td align="left" valign="top"><b><?php echo $AppUI->_('ARV therapy');?>:</b></td>
	 </tr>
      <tr>
			<td align="left">19a.<?php echo $AppUI->_('Is Client on ARV Therapy');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio($boolTypes,"clinical_arv_on",'id="clinical_arv_on"', $obj->clinical_arv_on ? $obj->clinical_arv_on : '',$identifiers);?>
			</td>
      </tr>
      <tr>
		<td align="left" nowrap="nowrap">19b.
		<?php echo $AppUI->_('Are you satisfied with');?><br/>
		<?php echo $AppUI->_('knowledge and adherence?');?>
		</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "clinical_adherence", 'onclick=toggleButtons()', $obj->clinical_adherence ? $obj->clinical_adherence : -1, $identifiers ); ?></td>
     </tr>
      <tr>
			<td align="left">19c.<?php echo $AppUI->_('If No, is client on adherence counseling');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio($boolTypes,"clinical_arv_on_adh",'id="clinical_arv_on_adh"', $obj->clinical_arv_on_adh ? $obj->clinical_arv_on_adh : '',$identifiers);?>
			</td>
      </tr>
      <tr>
			<td align="left">19d.<?php echo $AppUI->_('Recomendations');?>:</td>
			<td align="left" valign="top">
				<?php echo arraySelectRadio(dPgetSysVal('ARVTreatment'),"clinical_arv_recomends",'id="clinical_arv_recomends"', $obj->clinical_arv_recomends ? $obj->clinical_arv_recomends : '',$identifiers);?>
			</td>
      </tr>


     

	 <tr>
	 	<td align="left" valign="top">...<?php echo $AppUI->_('Specify');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="clinical_arv_notes"><?php echo dPformSafe(@$obj->clinical_arv_notes);?></textarea>
		</td>

	 </tr>

	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Diagnosis'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">20a.<?php echo $AppUI->_('WHO stage');?>:</td>
		<td align="left">
			<?php
				echo arraySelectRadio($whostages,'clinical_who_stage','id="clinical_who_stage"',$obj->clinical_who_stage ? $obj->clinical_who_stage : '',$identifiers);
				//<input type="text" class="text" name="clinical_who_stage" id="clinical_who_stage" value="<?php echo $obj->clinical_who_stage;" maxlength="150" size="20"/>
			?>
		</td>
      </tr>
      <tr>
		<td align="left" valign="top">20b.<?php echo $AppUI->_('Clinical stage');?>:</td>
		<td align="left">
			<?php
				echo arraySelectRadio($clinstages,'clinical_stage','id="clinical_stage"',$obj->clinical_stage ? $obj->clinical_stage : '',$identifiers);
				//<input type="text" class="text" name="clinical_who_stage" id="clinical_who_stage" value="<?php echo $obj->clinical_who_stage;" maxlength="150" size="20"/>
			?>
		</td>
      </tr>
	 <!-- <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Well child - minimal problems');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "clinical_child_condition", 'onclick=toggleButtons()', $obj->clinical_child_condition ? $obj->clinical_child_condition : -1, $identifiers ); ?></td>
	  </tr> -->
	  <tr>
		<td align="left" valign="top">21a.<?php echo $AppUI->_('Diarrhoea');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($diarrhoeaTypes, "clinical_diarrhoea_type", 'onclick=toggleButtons()', $obj->clinical_diarrhoea_type ? $obj->clinical_diarrhoea_type : -1, $identifiers ); ?></td>
	  </tr>
	  <tr>
		<td align="left" valign="top">21b.<?php echo $AppUI->_('Dehydration');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($dehydrationTypes, "clinical_dehydration", 'onclick=toggleButtons()', $obj->clinical_dehydration ? $obj->clinical_dehydration : -1, $identifiers ); ?></td>
	  </tr>
     <tr>

		<td align="left" valign="top">22a.<?php echo $AppUI->_('Pneumonia');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($pneumoniaTypes, "clinical_pneumonia", 'onclick=toggleButtons()', $obj->clinical_pneumonia ? $obj->clinical_pneumonia : -1, $identifiers ); ?></td>
	  </tr>
	<tr>
		<td align="left" valign="top">22b.<?php echo $AppUI->_('Chronic lung disease');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "clinical_chronic_lung", 'onclick=toggleButtons()', $obj->clinical_chronic_lung ? $obj->clinical_chronic_lung : -1, $identifiers ); ?></td>
     </tr>
     <tr>

		<td align="left" valign="top">23a.<?php echo $AppUI->_('TB');?>:</td>
		<td align="left" valign="top" nowrap="nowrap"><?php echo arraySelectRadio($tbTypes, "clinical_tb", 'onclick=toggleButtons()', $obj->clinical_tb ? $obj->clinical_tb : -1, $identifiers ); ?></td>
	 </tr>
	 <tr>
		<td align="left" valign="top">23b...<?php echo $AppUI->_('On treatment since');?>:</td>
		<td align="left" valign="top">
		<?php
			echo drawDateCalendar("clinical_tb_treatment_date",$tb_treatment_date ? $tb_treatment_date->format( $df ) : "" ,false,'id="clinical_tb_treatment_date"');
			//<input type="text" class="text" name="clinical_tb_treatment_date" id="clinical_tb_treatment_date" value="<?php echo $tb_treatment_date ? $tb_treatment_date->format( $df ) : "" ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
		?>
		</td>
     </tr>
     <tr>
		<td align="left" valign="top">24a.<?php echo $AppUI->_('Other diagnoses');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectCheckbox(dPgetSysVal('OtherDiagnoses'), "clinical_dss[]", 'onclick=toggleButtons()', $obj->clinical_dss ? $obj->clinical_dss : -1, $identifiers ); ?></td>
	</tr>
	 <tr>
		<td align="left" valign="top">24b.<?php echo $AppUI->_('Others');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="clinical_other_diagnoses" id="clinical_other_diagnoses" value="<?php echo $obj->clinical_other_diagnoses;?>" maxlength="150" size="50"/></td>
     </tr>
     <tr>

		<td align="left" valign="top">25a.<?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($malnutrionTypes, "clinical_malnutrition", 'onclick=toggleButtons()', $obj->clinical_malnutrition ? $obj->clinical_malnutrition : -1, $identifiers ); ?></td>
	</tr>
   <tr>
	<td align="left" valign="top">25b.<?php echo $AppUI->_('Growth');?>:</td>
	<td align="left" valign="top"><?php echo arraySelectRadio($growthTypes, "clinical_growth", 'onclick=toggleButtons()', $obj->clinical_growth ? $obj->clinical_growth : -1, $identifiers ); ?></td>
     </tr>


	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Treatment plan'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
     <tr>
		<td align="left" valign="top">26a.<?php echo $AppUI->_('Request investigations');?>:</td>
		<td align="left">
			<?php
				echo arraySelectRadio($boolTypes,'clinical_request','id="clinical_request"',$obj->clinical_request ? $obj->clinical_request : '',$identifiers);
			?><br>26b.<br>
			<?php
			echo arraySelectCheckbox(dPgetSysVal('RequestInvestigations'),'clinical_request_list[]','',$obj->clinical_request_list ? $obj->clinical_request_list : '');
			?>
		</td>
	</tr>
    <tr>
		<td align="left" valign="top">26c...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left" valign="top">
		<input type="text" class="text" name="clinical_investigations_notes" id="clinical_investigations_notes" value="<?php echo $obj->clinical_investigations_notes;?>" maxlength="150" size="20"/>
		</td>
     </tr>

	<tr>
		<td align="left" valign="top">27a.<?php echo $AppUI->_('ART');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "clinical_on_arvs", 'onclick=toggleButtons()', $obj->clinical_on_arvs ? $obj->clinical_on_arvs : -1, $identifiers ); ?></td>
	</tr>
	 <tr>
		<td align="left" valign="top">27b.<?php echo $AppUI->_('ARV Drugs');?>:</td>
		<td align="left" valign="top">
		<?php echo arraySelectCheckbox($arvTypes, "clinical_arv_drugs[]", NULL, $arv_types ); ?>
		</td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Other ARV Drugs');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_arv_drugs_other" id="clinical_arv_drugs_other" value="<?php echo $obj->clinical_arv_drugs_other;?>" maxlength="150" size="50"/></td>
     </tr>
    <tr>
		<td align="left" valign="top">27c.<?php echo $AppUI->_('Cotrimoxazole/Multivitamins');?>:</td>
		<td align="left" valign="top">
		<?php echo arraySelectCheckbox($vitaminTypes, "clinical_vitamins[]", NULL, $vitamins ); ?>
		</td>
     </tr>
     <tr>
		<td align="left" valign="top">28.<?php echo $AppUI->_('Therapy Stage');?>:</td>
		<td align="left" valign="top">
		<?php echo arraySelectRadio($therapyTypes, "clinical_therapy_stage",'', $obj->clinical_therapy_stage ? $obj->clinical_therapy_stage : '',$identifiers ); ?>
		</td>
     </tr>
	
	  <tr>
	    <td>29a.<?php echo $AppUI->_('ART');?>:</td>
		<td align="left" nowrap="nowrap"><?php echo arraySelectRadio($treatmentTypes, "clinical_treatment_status", 'onclick=toggleButtons()', $obj->clinical_treatment_status ? $obj->clinical_treatment_status : -1, $identifiers ); ?></td>
	 </tr>
     <tr>
		<td align="left" valign="top">29b.<?php echo $AppUI->_('Reasons');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_arv_reason" id="clinical_arv_reason" value="<?php echo $obj->clinical_arv_reason;?>" maxlength="150" size="50"/></td>

		</tr>
	<tr>
		<td align="left" valign="top">30a.<?php echo $AppUI->_('TB treatment');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "clinical_tb_treat",'id="clinical_tb_treat"',  $obj->clinical_tb_treat ? $obj->clinical_tb_treat : '',$identifiers ); ?></td>
	 </tr>
	<tr>
		<td align="left" valign="top">30b.<?php echo $AppUI->_('TB drugs');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectCheckbox($tbDrugsTypes, "clinical_tb_drugs[]", NULL, $clinical_tb_drugs ); ?></td>
	</tr>
	  <tr>
	    <td>31a.<?php echo $AppUI->_('TB Status');?>:</td>
		<td align="left" nowrap="nowrap"><?php echo arraySelectRadio(dPgetSysVal('TBStatus'), "clinical_tb_status", 'onclick=toggleButtons()', $obj->clinical_tb_status ? $obj->clinical_tb_status : -1, $identifiers ); ?></td>
	 </tr>
     <tr>
		<td align="left" valign="top">31b.<?php echo $AppUI->_('Reason');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_tb_status_notes" id="clinical_tb_status_notes" value="<?php echo dPformSafe(@$obj->clinical_tb_status_notes);?>" maxlength="150" size="50"/></td>
		</tr>
	 <tr>
        <td>32a.<?php echo $AppUI->_('Other drugs continuing');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_other_drugs" id="clinical_other_drugs" value="<?php echo $obj->clinical_other_drugs;?>" maxlength="150" size="50"/></td>
		</tr>
	 <tr>
		<td align="left" valign="top">32b.<?php echo $AppUI->_('New drugs prescribed');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_new_drugs" id="clinical_new_drugs" value="<?php echo $obj->clinical_new_drugs;?>" maxlength="150" size="50"/></td>
	 </tr>
      <tr>
		<td align="left">33.<?php echo $AppUI->_('Is the child unwell?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "clinical_child_unwell", 'onclick=toggleButtons()', $obj->clinical_child_unwell ? $obj->clinical_child_unwell : -1, $identifiers ); ?></td>
	  </tr>


	 <tr>
		<td align="left">34.<?php echo $AppUI->_('Refer to');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $refers, 'clinical_referral', 'size="1" class="text"', @$obj->clinical_referral ? $obj->clinical_referral:-1); ?>
		</td>
     </tr>
     <tr>
		<td align="left" valign="top">34b...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left"><input type="text" class="text" name="clinical_referral_other" id="clinical_referral_other" value="<?php echo $obj->clinical_referral_other;?>" maxlength="150" size="50"/></td>
	 </tr>
	 <tr>
		<td align="left">35.<?php echo $AppUI->_('Next appointment');?>:</td>
	 <td align="left" valign="top">
	 	<?php
	 		echo drawDateCalendar("clinical_next_date",$next_appointment_date ? $next_appointment_date->format( $df ) : "",false,'id="clinical_next_date"');
	 		//<input type="text" class="text" name="clinical_next_date" id="clinical_next_date" value="<?php echo $next_appointment_date ? $next_appointment_date->format( $df ) : "" ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
	 	?>
	 </td>
	</tr>
	<tr>

	 	<td align="left" valign="top"><?php echo $AppUI->_('Comment');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="clinical_assessment_notes"><?php echo dPformSafe(@$obj->clinical_assessment_notes);?></textarea>
		</td>
     </tr>
<tr>
	</table>
</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->clinical_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
</tr>

	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>

</table>
</form>
