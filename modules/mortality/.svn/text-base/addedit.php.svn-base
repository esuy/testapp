<?php 
$mortality_id = intval( dPgetParam( $_GET, "mortality_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('counsellinginfo'));
require_once ($AppUI->getModuleClass('social'));
require_once ($AppUI->getModuleClass('admission'));
//require_once ($AppUI->getModuleClass('clinical'));
//require_once ($AppUI->getModuleClass('nutrition'));

// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($mortality_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $mortality_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );
$ageTypes = dPgetSysVal('AgeType');
$genderTypes = dPgetSysVal('GenderType');
$deathPlaces = dPgetSysVal('DeathPlaceTypes');
// load the record data
$q  = new DBQuery;
$q->addTable('mortality_info');
$q->addQuery('mortality_info.*');
$q->addWhere('mortality_info.mortality_id = '.$mortality_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CMortality();
if (!db_loadObject( $sql, $obj ) && $mortality_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}
//load counselling object
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

	$q  = new DBQuery;
	$q->addTable('admission_info');
	$q->addQuery('admission_info.*');
	$q->addWhere('admission_info.admission_client_id = '.$client_id);
	$sql = $q->prepare();
	//var_dump($sql);
	$q->clear();
	$admissionObj = new CAdmissionRecord();	
	db_loadObject( $sql, $admissionObj );

	/*$q  = new DBQuery;
	$q->addTable('clinical_visits');
	$q->addQuery('clinical_bloodtest_cd4 as cd4, clinical_bloodtest_cd4_percentage as cd4_percent,clinical_bloodtest_viral as viral ,clinical_bloodtest_hb as hb ,clinical_entry_date as date,
					clinical_tb_treat, clinical_tb_treatment_date as tb_date,clinical_on_arvs');
	$q->addWhere('clinical_client_id = '.$client_id);
	$q->setLimit(1);
	$q->addOrder('clinical_entry_date desc');
	$cdata = $q->loadList();
	$clin_data=$cdata[0];
	
	$q = new DBQuery();
	$q->addTable('nutrition_visit');
	$q->addQuery('nutrition_weight as weight,nutrition_height as height,nutrition_entry_date as date');
	$q->addWhere('nutrition_client_id = '.$client_id);
	$q->setLimit(1);
	$q->addOrder('nutrition_entry_date desc');
	$ndata = $q->loadList();
	$nutr_data=$ndata[0];*/
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



$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

//load centers
$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');

$clinics = arrayMerge(array(0=> '-Select Center -'),$q->loadHashList());
// setup the title block
$client_id = $client_id ? $client_id : $obj->mortality_client_id;
//load client
$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $mortality_id > 0 ? "Edit Mortality Record : " . $clientObj->getFullName() : "New Mortality Record: " . $clientObj->getFullName();

}
else
{
   $ttl = $mortality_id > 0 ? "Edit Mortality Record " : "New Mortality Record ";

}

$age_years = 0;
$age_months = 0;
$age_years = $obj->mortality_age_yrs;
$age_months = $obj->mortality_age_months;


$client_age= $clientObj->age();

if ($mortality_id == 0)
{
  if (isset($clientObj))	
  {
	$clientObj->getAge($age_years,$age_months);
  }
}
$ddate = new CDate(date("Y-m-d") );
$df = $AppUI->getPref('SHDATEFORMAT');
$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeMortality'])", "Clear All Selections" );
if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName());
  
$date_reg = date("Y-m-d");
$entry_date = intval( $obj->mortality_entry_date) ? new CDate( $obj->mortality_entry_date ) : new CDate( $date_reg );
$dob = intval( $clientObj->getDOB()) ? new CDate( $clientObj->getDOB() ) : null;
$mortality_date = intval($obj->mortality_date) ? new CDate($obj->mortality_date ) :  NULL;
$enroll_date = intval($admissionObj->admission_entry_date) ? new CDate($admissionObj->admission_entry_date ) :  NULL;

$timein=calcIt($admissionObj->admission_entry_date);
$timeinTotal = $timein['v'];

//$arvLength = $arvdate[v];
$arv_date = intval($obj->mortality_arv_dateon) ? new CDate($obj->mortality_arv_dateon) : null;
$nutr_date = intval($obj->mortality_nutrition_date) ? new CDate($obj->mortality_nutrition_date) : null;
$tb_date = intval($obj->mortality_tb_start) ? new CDate($obj->mortality_tb_start) : null;
$clin_date = intval($obj->mortality_clinical_date) ? new CDate($obj->mortality_clinical_date) : null;
$mortality_report_date = intval($obj->mortality_relative_report_date) ? new CDate($obj->mortality_relative_report_date ) :  $ddate;
$mortality_admission_date = intval($obj->mortality_hospital_adm_date) ? new CDate($obj->mortality_hospital_adm_date ) : $ddate ;
//$mortality_admission_date = intval($counsellingObj->counselling_entry_date) ? new CDate($counsellingObj->counselling_entry_date ) :  null;
$mortality_clinical_report_date = intval($obj->mortality_clinical_officer_date) ? new CDate($obj->mortality_clinical_officer_date ) :  $ddate;

$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeMortality ;

	if(!manField("staff_id")){
		alert("Please select Officer!");
		return false;
	}
	if(!manField("clinic_id")){
		alert("Please select Center!");
		return false;
	}
	if (form.mortality_date && form.mortality_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.mortality_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of death " + errormsg);
			form.mortality_date.focus();
			exit;

		}
    }
	if (form.mortality_age_yrs && form.mortality_age_yrs.value.length > 0) 
	{
		if (isNaN(parseInt(form.mortality_age_yrs.value,10)) )
		{
			alert(" Invalid Age (years)");
			form.mortality_age_yrs.focus();
			exit;
			
		}
	}
	 if (form.mortality_age_months && form.mortality_age_months.value.length > 0) 
	{
		if (isNaN(parseInt(form.mortality_age_months.value,10)) )
		{
			alert(" Invalid Age (months)");
			form.mortality_age_months.focus();
			exit;

		}
	}
	if (form.mortality_hospital_adm_date && form.mortality_hospital_adm_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.mortality_hospital_adm_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of admission " + errormsg);
			form.mortality_hospital_adm_date.focus();
			exit;

		}
    }	
	if (form.mortality_relative_report_date && form.mortality_relative_report_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.mortality_relative_report_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of report " + errormsg);
			form.mortality_relative_report_date.focus();
			exit;

		}
    }	
	if (form.mortality_clinical_officer_date && form.mortality_clinical_officer_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.mortality_clinical_officer_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of clinical report " + errormsg);
			form.mortality_clinical_officer_date.focus();
			exit;

		}
    }	
	form.submit();
}


</script>

<form name="changeMortality" action="?m=mortality" method="post">
	<input type="hidden" name="dosql" value="do_mortality_aed" />
	<input type="hidden" name="mortality_id" value="<?php echo $mortality_id;?>" />
	<input type="hidden" name="mortality_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" name="mortality_age_status" value="<?php echo $counsellingObj->counselling_age_status;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td>


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
		 		<?php echo arraySelect( $clinics, 'mortality_clinic_id', 'size="1" class="text" id="clinic_id"', @$obj->mortality_clinic_id ? $obj->mortality_clinic_id:0); ?>        
			</td>
		</tr>
	   <tr>
			<td align="left" nowrap>1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left">
			<?php 
				echo drawDateCalendar('mortality_entry_date',$entry_date ? $entry_date->format( $df ) : "");
				//<input type="text" name="mortality_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;" class="text"  />
			?>				
			&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
			</td>
		   </tr>

	   <tr>
			<td align="left" nowrap>1c.<?php echo $AppUI->_('Social worker');?>: </td>
			<td align="left">
			
				<input type="text" name="mortality_social_worker" value="<?php echo $obj->mortality_social_worker?>" class="text"  />
						
			</td>
		   </tr>
	   
       <tr>
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="client_code" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" readonly disabled="disabled" />
         </td>
       </tr>
	 <tr>
         <td align="left">2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" readonly disabled="disabled" />
         </td>
       </tr>


	  <tr>
         <td align="left">3a.<?php echo $AppUI->_('Gender');?>:</td>
		 <td align="left"><?php echo $genderTypes[$clientObj->client_gender]; ?></td>

       </tr>	   
 	   <tr>
		<td align="left">3b.<?php echo $AppUI->_('Total Orphan?');?></td>
		<td align="left"><?php echo @$boolTypes[$admissionObj->admission_total_orphan];?></td>
     </tr>	  

     <tr>
			<td align="left">4a.<?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top"><?php echo $dob ? $dob->format($df) : "";?>
				<!--  <input type="text" class="text" name="counselling_dob" id="counselling_dob" value="" maxlength="150" size="20" readonly disabled="disabled" />&nbsp;dd/mm/yyyy</td> -->			
	  </tr> 
<tr>
         <td align="left">4b.<?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left">
	    	<input type="text" class="text" readonly="readonly" name="mortality_age_yrs" value="<?php echo dPformSafe(@$client_age[0]);?>" maxlength="30" size="20" readonly  />
		 </td>
</tr>
<tr>
         <td align="left"><?php echo $AppUI->_('Age (months)');?>:</td>         
		 <td align="left">
	    <input type="text" class="text" readonly="readonly" name="mortality_age_months" value="<?php echo dPformSafe(@$client_age[1]);?>" maxlength="30" size="20" readonly />
		 </td>		
	 </tr>
	<tr>
		<td>4c.&nbsp;</td>
		<td align="left"><?php echo arraySelectRadio($ageTypes, "mortality_age_ss", 'readonly disabled="disabled"', $counsellingObj->counselling_age_status ? $counsellingObj->counselling_age_status : -1, $identifiers ); ?></td>	
	</tr>
	<tr>
		<td align="left">5a.<?php echo $AppUI->_('Date of admission');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_enroll_date" id="mortality_edate" value="<?php echo $enroll_date ?  $enroll_date->format($df).'" readonly="readonly' : "";?>"  size="20"/>					
		</td>
    </tr>
    <tr>
		<td align="left">5b.<?php echo $AppUI->_('Time in programme (mon)');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_enrolled_time" id="mortality_edate" <?php echo $enroll_date ? ' readonly="readonly" ' : ''; ?> value="<?php echo $timeinTotal;?>"  size="20"/>					
		</td>
    </tr>
	
	<tr>
		<td align="left">6a.<?php echo $AppUI->_('Date of death');?>:</td>		
		<td align="left" valign="top">
		<?php
		echo drawDateCalendar('mortality_date',$mortality_date ?  $mortality_date->format($df) : "",false,'id="mortality_date"'); 
		//<input type="text" class="text" name="mortality_date" id="mortality_date" value="<?php echo $mortality_date ?  $mortality_date->format($df) : "";" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
		?>			
		</td>
     </tr>
	   <tr>
	 	<td align="left" >6b.<?php echo $AppUI->_('Place of death');?></td>
		<td align="left"><?php echo arraySelectRadio($deathPlaces, "mortality_death_type", 'onclick=toggleButtons()', $obj->mortality_death_type? $obj->mortality_death_type : -1, $identifiers ); ?></td>

     </tr>	
		<tr>
		   <td  valign="top">6c...<?php echo $AppUI->_('Other');?>:</td>
		   <td>
		   <input type="text" class="text" name="mortality_death_type_notes" id="mortality_death_type_notes" value="<?php echo $obj->mortality_death_type_notes;?>" maxlength="150" size="20"/>		   </td>
		   </tr>

       <tr>
         <td align="left">7.<?php echo $AppUI->_('Informant (relationship)');?>:</td>
         <td align="left">
          <input type="text" class="text" name="mortality_informant" value="<?php echo dPformSafe(@$obj->mortality_informant);?>" maxlength="150" size="20" />
         </td>
       </tr>
       <tr>
         <td align="left">8a.<?php echo $AppUI->_('Name of hospital attended');?>:</td>
         <td align="left">
          <input type="text" class="text" name="mortality_hospital" value="<?php echo dPformSafe(@$obj->mortality_hospital);?>" maxlength="150" size="20" />
         </td>
       </tr>
       <tr>
         <td align="left">8b.<?php echo $AppUI->_('Date of admission (to hospital)');?>:</td>         
         <td align="left" valign="top">
         	<?php 
         		echo drawDateCalendar('mortality_hospital_adm_date',$mortality_admission_date ?  $mortality_admission_date->format($df) : "",false,'id="mortality_hospital_adm_date"');
         		//<input type="text" class="text" name="mortality_hospital_adm_date" id="mortality_hospital_adm_date" value="<?php echo $mortality_admission_date ?  $mortality_admission_date->format($df) : ""  ;" maxlength="150" size="20" />&nbsp;dd/mm/yyyy
         	?>
         	
         </td>
         </td>
       </tr>       
	   
	 	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Report from relative'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>  
       <tr>
         <td align="left">9.<?php echo $AppUI->_('Date of report');?>:</td>
         <td align="left" valign="top">
         <?php 
         	echo drawDateCalendar("mortality_relative_report_date",$mortality_report_date ?  $mortality_report_date->format($df) : "" ,false, 'id="mortality_relative_report_date"');
         	//<input type="text" class="text" name="mortality_relative_report_date" id="mortality_relative_report_date" value="<?php echo $mortality_report_date ?  $mortality_report_date->format($df) : ""  ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
         ?>         
         </td>
       </tr>       
       <tr>
         <td align="left"  valign="top"><?php echo $AppUI->_('Last illness');?>:</td>
	   </tr>
		<tr>
		   <td  valign="top">10a...<?php echo $AppUI->_('Symptoms');?>:</td>
		   <td><textarea cols="70" rows="2" class="textarea" name="mortality_symptoms"><?php echo dPformSafe(@$obj->mortality_symptoms);?></textarea></td>
		   </tr>
		   <tr>
		   <td  valign="top">10b...<?php echo $AppUI->_('Time course');?>:</td>
		   <td><textarea cols="70" rows="2" class="textarea" name="mortality_time_course"><?php echo dPformSafe(@$obj->mortality_time_course);?></textarea></td>
		   </tr>
		   <tr>
		   <td  valign="top">10c...<?php echo $AppUI->_('Treatment');?>:</td>
		   <td><textarea cols="70" rows="2" class="textarea" name="mortality_treatment"><?php echo dPformSafe(@$obj->mortality_treatment);?></textarea></td>
		   </tr>
	   <tr>
	 	<td align="left" >11a.<?php echo $AppUI->_('Was the child refered to hospital by LT clinic?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "mortality_hospital_referral", 'onclick=toggleButtons()', $obj->mortality_hospital_referral? $obj->mortality_hospital_referral : -1, $identifiers ); ?></td>

     </tr>	
	 <tr>
	 	<td align="left" valign="top">11b...<?php echo $AppUI->_('If so, why?');?></td>
		<td  valign="top">
		<textarea cols="70" rows="2" class="textarea" name="mortality_referral"><?php echo dPformSafe(@$obj->mortality_referral);?></textarea>
		</td>
	 </tr>
 
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Report from the hospital'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	  
	 <tr>	
		<td align="left" valign="top">12.<?php echo $AppUI->_('Reason for admission');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="mortality_hospital_adm_notes"><?php echo dPformSafe(@$obj->mortality_hospital_adm_notes);?></textarea>
		</td>
	  </tr>	
	 <tr>
         <td align="left">13.<?php echo $AppUI->_('Clinical Course');?>:</td>
         <td align="left">
          <input type="text" class="text" name="mortality_clinical_course" value="<?php echo dPformSafe(@$obj->mortality_clinical_course);?>" maxlength="150" size="20" />
         </td>
       </tr>
       <tr>
	 <tr>	
		<td align="left" valign="top">14a.<?php echo $AppUI->_('Cause of death given');?>:</td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "mortality_cause_given", 'onclick=toggleButtons()', $obj->mortality_cause_given? $obj->mortality_cause_given : -1, $identifiers ); ?></td>
	  </tr>		  
	<tr>
	    <td align="left" valign="top">14b...<?php echo $AppUI->_('If Yes, what?');?></td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="mortality_cause_desc"><?php echo dPformSafe(@$obj->mortality_cause_desc);?></textarea>
		</td>

	</tr>
	<tr>
			<td colspan="2" align="left">
				<strong>?php echo $AppUI->_('Clinical Officer'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	  

	<tr>
         <td align="left">15a.<?php echo $AppUI->_('Clinical Officer Name');?>:</td>
         <td align="left">
         <?php 
         echo arraySelect($owners,'mortality_clinical_officer','class="text" id="staff_id"',$obj->mortality_clinical_officer ? $obj->mortality_clinical_officer : '', $indentifiers); //
         ?>          
         &nbsp;&nbsp;&nbsp;<b>Obsolete</b>
         <input type="text" readonly  class="text" name="mortality_clinical_officer_old" value="<?php echo dPformSafe(@$obj->mortality_clinical_officer_old);?>"  size="20" />
         </td>
      </tr>	
       <tr>
         <td align="left">15b.<?php echo $AppUI->_('Date of report');?>:</td>
         <td align="left" valign="top">
         <?php 
         	echo drawDateCalendar("mortality_clinical_officer_date",$mortality_clinical_report_date ?  $mortality_clinical_report_date->format($df) : "" ,false, 'id="mortality_clinical_officer_date"');
         	//<input type="text" class="text" name="mortality_clinical_officer_date" id="mortality_clinical_officer_date" value="<?php echo $mortality_clinical_report_date ?  $mortality_clinical_report_date->format($df) : ""   ;" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
         ?>         
        </td>         
      </tr>
    <tr>
    	<td><b>Immune status</b></td>
    </tr>
    <tr>
		<td align="left">16a.<?php echo $AppUI->_('CD4');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_cd4" id="mortality_cd4" value="<?php echo $obj->mortality_cd4;?>"  size="20"/>					
		</td>
    </tr>   
    <tr>
		<td align="left">16b.<?php echo $AppUI->_('CD4 %');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_cd4_percentage" id="mortality_cd4p"  value="<?php echo $obj->mortality_cd4_percentage;?>"  size="20"/>					
		</td>
    </tr>
    <tr>
		<td align="left">16c.<?php echo $AppUI->_('Viral load');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_viral_load" id="mortality_vl" value="<?php echo $obj->mortality_viral_load;?>"  size="20"/>					
		</td>
    </tr>
    <tr>
		<td align="left">16d.<?php echo $AppUI->_('Hb');?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_hb" id="mortality_hb"  value="<?php echo $obj->mortality_hb;?>"  size="20"/>					
		</td>
    </tr>
    <tr>
		<td align="left">16e.<?php echo $AppUI->_('Date');?>:</td>		
		<td align="left" valign="top">
			<?php
				echo drawDateCalendar("mortality_clinical_date",$clin_date ? $clin_date->format($df) : '',false, '');
			?>				
		</td>
    </tr>    
    <tr>
        <td align="left">17a.<?php echo $AppUI->_("ARV's");?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "mortality_arv", 'onclick=toggleButtons()', $obj->mortality_arv ? $obj->mortality_arv : -1, $identifiers ); ?></td>
	</tr>	
    <tr>
		<td align="left">17b.<?php echo $AppUI->_('Date started');?>:</td>		
		<td align="left" valign="top">
			<?php 
				echo drawDateCalendar("mortality_arv_dateon",$arv_date ?  $arv_date->format($df) : "" ,false, '');
			?>		
		</td>
    </tr>
    <tr>
		<td align="left">17c.<?php echo $AppUI->_("Time on ARV's (mon)");?>:</td>		
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_arv_period" id="mortality_apd"  value="<?php echo $obj->mortality_arv_period;?>"  size="20"/>					
		</td>
    </tr>   
    <tr>
		<td align="left">18a.<?php echo $AppUI->_("TB: On treatment");?>:</td>		
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "mortality_tb", '', $obj->mortality_tb ? $obj->mortality_tb : -1, $identifiers ); ?></td>		
    </tr>
    <tr>
		<td align="left">18b...<?php echo $AppUI->_("Date started");?>:</td>	
		<td align="left" valign="top">
		<?php 
			echo drawDateCalendar("mortality_tb_start",$tb_date ?  $tb_date->format($df) : "" ,false, '');
		?>				
		</td>
    </tr>
    <tr><td><b>Nutrition</b></td></tr>
    <tr>
		<td align="left">19a...<?php echo $AppUI->_("Last Weight");?>:</td>	
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_weight" id="mortality_w8"  value="<?php echo $obj->mortality_weight;?>"  size="20"/>
		</td>
    </tr>
    <tr>
		<td align="left">19b...<?php echo $AppUI->_("Last Height");?>:</td>	
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_height" id="mortality_h8"  value="<?php echo $obj->mortality_height;?>"  size="20"/>
		</td>
    </tr>
    <tr>
		<td align="left">19c...<?php echo $AppUI->_("Date");?>:</td>	
		<td align="left" valign="top">
		<?php 
			echo drawDateCalendar("mortality_nutrition_date",$nutr_date ?  $nutr_date->format($df) : "" ,false, '');
		?>						
		</td>
    </tr>
    <tr>
        <td align="left">20a.<?php echo $AppUI->_("Malnutrition");?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "mortality_malnutrition", '', $obj->mortality_malnutrition ? $obj->mortality_malnutrition : -1, $identifiers ); ?>&nbsp;&nbsp;&nbsp;
		20b.<?php echo arraySelectRadio(dPgetSysVal('Grades'), "mortality_malnutrition_notes", '', $obj->mortality_malnutrition_notes ? $obj->mortality_malnutrition_notes : -1, $identifiers ); ?></td>
	</tr>
	<tr>
		<td align="left">21a.<?php echo $AppUI->_("Other Recent Problems: A");?>:</td>	
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_recents_a" id="mortality_ra" value="<?php echo dPformSafe($obj->mortality_recents_a);?>"  size="20"/>
		</td>
    </tr>
    <tr>
		<td align="left">21b.<?php echo $AppUI->_("Other Recent Problems: B");?>:</td>	
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_recents_b" id="mortality_rb" value="<?php echo dPformSafe($obj->mortality_recents_b);?>"  size="20"/>
		</td>
    </tr>
	<tr>
        <td align="left">22a.<?php echo $AppUI->_("Is the postmortem arranged");?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "mortality_postmortem", 'onclick=toggleButtons()', $obj->mortality_postmortem ? $obj->mortality_postmortem : -1, $identifiers ); ?></td>
	</tr>
	<tr>
		<td align="left">22b.<?php echo $AppUI->_("Where ?");?>:</td>	
		<td align="left" valign="top">
		<input type="text" class="text" name="mortality_postmortem_where" id="mortality_pmw" value="<?php echo dPformSafe($obj->mortality_postmortem_where);?>"  size="20"/>
		</td>
    </tr>  
        </td>
      </tr>

	<tr>
	    <td align="left" valign="top">22c...<?php echo $AppUI->_('If Yes, cause of death from PM?');?></td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="mortality_cause_pm"><?php echo dPformSafe(@$obj->mortality_cause_pm);?></textarea>
		</td>

	</tr>
      <tr>
			<td align="left">23...<?php echo $AppUI->_('If No PM, likely causes of death? ');?>:</td>

			<td align="left" valign="top">
				<textarea cols="70" rows="2" class="textarea" name="mortality_likely_cause"><?php echo dPformSafe(@$obj->mortality_likely_cause);?></textarea>
			</td>
      </tr>
     
	  <tr>
			<td align="left">24...<?php echo $AppUI->_('Other factors');?>:</td>


			<td align="left" valign="top">
				<textarea cols="70" rows="2" class="textarea" name="mortality_notes"><?php echo dPformSafe(@$obj->mortality_notes);?></textarea>
			</td>
      </tr>

</table>


</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->mortality_id, "edit" );
 			$custom_fields->printHTML();
		?>		
	</td>
</tr>

<tr>
	<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	<td align="left"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
</tr>

</table>
</form>
