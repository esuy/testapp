<?php 
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );


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
$boolTypes = dPgetSysVal('YesNoND');
$birthPlaces = dPgetSysVal('BirthPlaceType');
$birthTypes = dPgetSysVal('BirthType');
$ageTypes = dPgetSysVal('AgeType');
$awareStages = dPgetSysVal('StatusAwareType');
$rapidResultsType = dPgetSysVal('RapidResultsType');
$elisaResultsType = dPgetSysVal('ElisaResultsType');
$pcrResultsType = dPgetSysVal('PCRResultsType');

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();

$owners = arrayMerge(array (0=>'-Select Officer-'), $owners);

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_info');
$q->addQuery('counselling_info.*');
$q->addWhere('counselling_info.counselling_id = '.$counselling_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');

$clinics = arrayMerge(array(0=> '-Select Center -'),$q->loadHashList());

$obj = new CCounsellingInfo();
if (!db_loadObject( $sql, $obj ) && $counselling_id > 0) {
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}





$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

// setup the title block
$client_id = $client_id ? $client_id : $obj->counselling_client_id;
//load client
$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $counselling_id > 0 ? "Edit Intake & PCR : " . $clientObj->getFullName() : "New Intake & PCR  : " . $clientObj->getFullName();

}
else
{
   $ttl = $counselling_id > 0 ? "Edit Intake & PCR " : "New Intake & PCR ";

}
$age_years = 0;
$age_months = 0;
$age_years = $obj->counselling_age_yrs;
$age_months = $obj->counselling_age_months;

if ($counselling_id == 0)
{
  if (isset($clientObj))	
  {
	$clientObj->getAge($age_years,$age_months);
  }
}

$entry_date = intval( $obj->counselling_entry_date) ? new CDate( $obj->counselling_entry_date ) : null;
$dob = intval( $obj->counselling_dob) ? new CDate( $obj->counselling_dob ) : null;
$child_nvp_date = intval( $obj->counselling_child_nvp_date) ? new CDate( $obj->counselling_child_nvp_date ) : null;
$child_azt_date = intval( $obj->counselling_child_azt_date) ? new CDate( $obj->counselling_child_azt_date ) : null;
$mother_date_art = intval( $obj->counselling_mother_date_art) ? new CDate( $obj->counselling_mother_date_art ) : null;
$mother_date_cd4 = intval( $obj->counselling_mother_date_cd4) ? new CDate( $obj->counselling_mother_date_cd4 ) : null;
$rapid18_date = intval( $obj->counselling_rapid18_date) ? new CDate( $obj->counselling_rapid18_date ) : null;
$determine_date = intval( $obj->counselling_determine_date) ? new CDate( $obj->counselling_determine_date ) : null;
$bioline_date = intval( $obj->counselling_bioline_date) ? new CDate( $obj->counselling_bioline_date ) : null;
$unigold_date = intval( $obj->counselling_unigold_date) ? new CDate( $obj->counselling_unigold_date ) : null;
$elisa_date = intval( $obj->counselling_elisa_date) ? new CDate( $obj->counselling_elisa_date ) : null;
$pcr1_date = intval( $obj->counselling_pcr1_date) ? new CDate( $obj->counselling_pcr1_date ) : null;
$pcr2_date = intval( $obj->counselling_pcr2_date) ? new CDate( $obj->counselling_pcr2_date ) : null;
$rapid12_date = intval( $obj->counselling_rapid12_date) ? new CDate( $obj->counselling_rapid12_date ) : null;
$other_date = intval( $obj->counselling_other_date) ? new CDate( $obj->counselling_other_date ) : null;
$refsrc = arrayMerge(array('-1'=>'- Select -'),dPgetSysVal('IntakeReferralSource'));

$df = $AppUI->getPref('SHDATEFORMAT');

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeCounselling'])", "Clear All Selections" );
if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName()  );
/*	
if ($counselling_id != 0)
  $titleBlock->addCrumb( "?m=counsellinginfo&a=view&counselling_id=$counselling_id", "View" );*/
$titleBlock->show();
?>

<script language="javascript">

function submitIt() 
{
	var form = document.changeCounselling ;
	var errormsg = "";

	if (!manField("counselling_dob")) 	{		
			alert("Invalid date of birth" );
			return false;		
	}	
	if (form.counselling_entry_date && form.counselling_entry_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_entry_date.value);
		
		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.counselling_entry_date.focus();
			exit;
		}
	}
	
	if (form.counselling_age_yrs && form.counselling_age_yrs.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_age_yrs.value,10)) )
		{
			alert(" Invalid Age (years)");
			form.counselling_age_yrs.focus();
			exit;
			
		}
	}
	 if (form.counselling_age_months && form.counselling_age_months.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_age_months.value,10)) )
		{
			alert(" Invalid Age (months)");
			form.counselling_age_months.focus();
			exit;

		}
	}	
	 if (form.counselling_gestation_period && form.counselling_gestation_period.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_gestation_period.value,10)) )
		{
			alert(" Invalid Gestation Period");
			form.counselling_gestation_period.focus();
			exit;

		}
	}	
	 if (form.counselling_birth_weight && form.counselling_birth_weight.value.length > 0) 
	{
		
		if (isNaN(parseInt(form.counselling_birth_weight.value,10)) )
		{
			alert(" Invalid Birth Weight");
			form.counselling_birth_weight.focus();
			exit;
			
		}
	}	
	 if (form.counselling_breastfeeding_duration && form.counselling_breastfeeding_duration.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_breastfeeding_duration.value,10)) )
		{
			alert(" Invalid Breastfeeding Duration");
			form.counselling_breastfeeding_duration.focus();
			exit;

		}
	}	
	 if (form.counselling_other_breastfeeding_duration && form.counselling_other_breastfeeding_duration.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_other_breastfeeding_duration.value,10)) )
		{
			alert(" Invalid Breastfeeding Duration");
			form.counselling_other_breastfeeding_duration.focus();
			exit;

		}
	}
	 if (form.counselling_child_nvp_date && form.counselling_child_nvp_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_child_nvp_date.value);
		if (errormsg.length > 1)
		{
			alert("Child NVP Date " + errormsg);
			form.counselling_child_nvp_date.focus();
			exit;

		}
    }	
	 if (form.counselling_child_azt_date && form.counselling_child_azt_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_child_azt_date.value);
		if (errormsg.length > 1)
		{
			alert("Child AZT Date " + errormsg);
			form.counselling_child_azt_date.focus();
			exit;

		}
    } 
	 if (form.counselling_no_doses && form.counselling_no_doses.value.length > 0) 
	{
		if (isNaN(parseInt(form.counselling_no_doses.value,10)) )
		{
			alert(" Invalid No of AZT Doses");
			form.counselling_no_doses.focus();
			exit;

		}
	}
	 if (form.counselling_mother_date_art && form.counselling_mother_date_art.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_mother_date_art.value);
		if (errormsg.length > 1)
		{
			alert("Date Mother began ART : " + errormsg);
			form.counselling_mother_date_art.focus();
			exit;

		}
    }	
	if (form.counselling_mother_date_cd4 && form.counselling_mother_date_cd4.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_mother_date_cd4.value);
		if (errormsg.length > 1)
		{
			alert("Date Mother had CD4 Test: " + errormsg);
			form.counselling_mother_date_cd4.focus();
			exit;

		}
    } 	
	if (form.counselling_determine_date && form.counselling_determine_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_determine_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of Determine Test date: " + errormsg);
			form.counselling_determine_date.focus();
			exit;

		}
    } 
	if (form.counselling_bioline_date && form.counselling_bioline_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_bioline_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of Bioline Test: " + errormsg);
			form.counselling_bioline_date.focus();
			exit;

		}
    } 
	if (form.counselling_unigold_date && form.counselling_unigold_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_unigold_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of Unigold Test: " + errormsg);
			form.counselling_unigold_date.focus();
			exit;

		}
    } 
	if (form.counselling_elisa_date && form.counselling_elisa_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_elisa_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of ELISA Test: " + errormsg);
			form.counselling_elisa_date.focus();
			exit;

		}
    } 
	if (form.counselling_pcr1_date && form.counselling_pcr1_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_pcr1_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of PCR1 Test: " + errormsg);
			form.counselling_pcr1_date.focus();
			exit;

		}
    } 
	if (form.counselling_pcr2_date && form.counselling_pcr2_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_pcr2_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of PCR2 Test: " + errormsg);
			form.counselling_pcr2_date.focus();
			exit;

		}
    } 
	if (form.counselling_rapid12_date && form.counselling_rapid12_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_rapid12_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of Rapid Test @ 12 months: " + errormsg);
			form.counselling_rapid12_date.focus();
			exit;

		}
    } 
	if (form.counselling_rapid18_date && form.counselling_rapid18_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_rapid18_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of Rapid Test @ 18 months: " + errormsg);
			form.counselling_rapid18_date.focus();
			exit;

		}
    } 
	if (form.counselling_other_date && form.counselling_other_date.value.length > 0) 
	{
		errormsg = checkValidDate(form.counselling_other_date.value);
		if (errormsg.length > 1)
		{
			alert("Date of other Test: " + errormsg);
			form.counselling_other.focus();
			exit;

		}
    } 

    form.submit();

	
}

function testURL( x ) {
	var test = "document.changeCounselling.company_primary_url.value";
	test = eval(test);
	if (test.length > 6) {
		newwin = window.open( "http://" + test, 'newwin', '' );
	}
}
</script>

<form name="changeCounselling" action="?m=counsellinginfo" method="post">
	<input type="hidden" name="dosql" value="do_counsellinginfo_aed" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
	<input type="hidden" name="counselling_client_id" value="<?php echo $client_id;?>" />
	
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">


<tr>
<td width="100%" valign="top">
<table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
     <tr>
		<td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
		<td align="left">
		<input type="text" class="text" name="client_code" id="client_code" value="<?php echo $clientObj->client_adm_no;?>" maxlength="150" size="20" disabled="disabled" />
	    </td>
      </tr>

		<tr>
			<td align="left" nowrap><?php echo $AppUI->_('Registration Date');?>: </td>
			<td align="left">
				<input type="text" name="counselling_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text"  />
			</td>
		   </tr>

	   <tr>
		<td align="left"><?php echo $AppUI->_('Center');?>:</td>
		<td align="left"><?php echo arraySelect($clinics, "counselling_clinic", 'size="1" class="text"', $obj->counselling_clinic ? $obj->counselling_clinic : 0 ); ?></td>
     </tr>	  
       <tr>
         <td align="left"><?php echo $AppUI->_('Officer');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'counselling_staff_id', 'size="1" class="text"', @$obj->counselling_staff_id ? $obj->counselling_staff_id : 0); ?>        
			</td>
       </tr>	 
       <tr>
         <td align="left"><?php echo $AppUI->_('Referral Source');?>:</td>
         <td align="left">
         	<?php echo arraySelect($refsrc,'counselling_referral_source','id="refsrc"',$obj->counselling_referral_source ? $obj->counselling_referral_source : '')?>
         </td>
       </tr>
       <tr>
         <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
         <td align="left">         	
          <input type="text" class="text" name="counselling_referral_source_notes" value="<?php echo @$obj->counselling_referral_source_notes;?>" maxlength="150" size="20" />
         </td>
       </tr>
	   <tr>
		<td align="left"><?php echo $AppUI->_('Total Orphan?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "counselling_total_orphan", 'onclick=toggleButtons()', $obj->counselling_total_orphan ? $obj->counselling_total_orphan : -1, $identifiers ); ?></td>
     </tr>	  

     <tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top">
			<input type="text" class="text" name="counselling_dob" id="counselling_dob" value="<?php echo $dob ? $dob->format($df) : "";?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy
			</td>
			
	  </tr> 
<tr>
         <td valign="top"><?php echo $AppUI->_('Age (years)');?>:</td>
		   <td>
	       <input type="text" class="text" name="counselling_age_yrs" value="<?php echo dPformSafe(@$age_years);?>" maxlength="30" size="20"/>
		    </td>
          </tr>
		  <tr>
         <td valign="top"><?php echo $AppUI->_('Age (months)');?>:</td>

		  <td>
	         <input type="text" class="text" name="counselling_age_months" value="<?php echo dPformSafe(@$age_months);?>" maxlength="30" size="20" />
    	   </td>
		 </tr>
		<tr>
		<td>&nbsp;</td>  
		<td><?php echo arraySelectRadio($ageTypes, "counselling_age_status", 'onclick=toggleButtons()', $obj->counselling_age_status ? $obj->counselling_age_status : -1, $identifiers ); ?></td>		
		</tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Place of Birth');?>:</td>
		 <td align="left">
		 <?php echo arraySelectRadio($birthPlaces, "counselling_place_of_birth", 'onclick=toggleButtons()', $obj->counselling_place_of_birth ? $obj->counselling_place_of_birth : -1, $identifiers ); ?></td>

		 </td>
      </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Area of Birth');?>:</td>
		 <td>
	    <input type="text" class="text" name="counselling_birth_area" value="<?php echo @$obj->counselling_birth_area;?>" maxlength="150" size="20" />
		 </td>
		 </tr>  
	 <tr>
		<td align="left"><?php echo $AppUI->_('Mode of birth');?>:</td>
		<td align="left"><?php echo arraySelectRadio($birthTypes, "counselling_mode_birth", 'onclick=toggleButtons()', $obj->counselling_mode_birth ? $obj->counselling_mode_birth : -1, $identifiers ); ?></td>		
		
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Gestation period (months)');?>:</td>
		<td align="left"><input type="text" class="text" name="counselling_gestation_period" id="counselling_gestation_period" value="<?php echo $obj->counselling_gestation_period;?>" maxlength="150" size="20"/></td>
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left"><input type="text" class="text" name="counselling_birth_weight" id="counselling_birth_weight" value="<?php echo $obj->counselling_birth_weight;?>" maxlength="150" size="20"/></td>
     </tr>
	  
	  


      <tr>
			<td align="left"><?php echo $AppUI->_('Mother aware of status');?>:</td>
			<td align="left" nowrap><?php echo arraySelectRadio($awareStages, "counselling_mothers_status_known", 'onclick=toggleButtons()', $obj->counselling_mothers_status_known ? $obj->counselling_mothers_status_known : -1, $identifiers ); ?>
			</td>	
      </tr>
	  <tr>
		<td align="left" nowrap><?php echo $AppUI->_('Did mother receive any antenatal care?');?></td>
		<td align="left"><?php echo arraySelectRadio($boolTypes, "counselling_mother_antenatal", 'onclick=toggleButtons()', $obj->counselling_mother_antenatal ? $obj->counselling_mother_antenatal : -1, $identifiers ); ?></td>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother enrolled in a PMTCT program?');?>:</td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "counselling_mother_pmtct", 'onclick=toggleButtons()', $obj->counselling_mother_pmtct ? $obj->counselling_mother_pmtct : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Illness/STI at pregnancy?');?></td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "counselling_mother_illness_pregnancy", 'onclick=toggleButtons()', $obj->counselling_mother_illness_pregnancy ? $obj->counselling_mother_illness_pregnancy : -1, $identifiers ); ?></td>
	 	
     </tr>
	 <tr>
	 <td align="left" valign="top">...<?php echo $AppUI->_('If Y please describe');?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="counselling_mother_illness_pregnancy_notes"><?php echo @$obj->counselling_mother_illness_pregnancy_notes;?></textarea>
		</td>
	 </tr> 
 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Exclusive breastfeeding? ');?></td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "counselling_breastfeeding", 'onclick=toggleButtons()', $obj->counselling_breastfeeding ? $obj->counselling_breastfeeding : -1, $identifiers ); ?></td>
	</tr>
    <tr>	
		<td align="left" valign="top">...<?php echo $AppUI->_('If Y, duration (months) ');?></td>
		<td align="left"><input type="text" class="text" name="counselling_breastfeeding_duration" id="counselling_breastfeeding_duration" value="<?php echo $obj->counselling_breastfeeding_duration;?>" maxlength="150" size="20"/></td>
	</tr>
    <tr>	
		<td align="left" valign="top">...<?php echo $AppUI->_('Duration other breastfeeding (months) ');?></td>
		<td align="left"><input type="text" class="text" name="counselling_other_breastfeeding_duration" id="counselling_other_breastfeeding_duration" value="<?php echo $obj->counselling_other_breastfeeding_duration;?>" maxlength="150" size="20"/>
		</td>
	  </tr>
<tr>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "edit" );
 			$custom_fields->printHTML();
		?>		
	</td>
</tr>
  <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Child prenatal ARV exposure?');?></td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling_child_prenatal", 'onclick=toggleButtons()', $obj->counselling_child_prenatal ? $obj->counselling_child_prenatal : -1 ); ?>
        </td>
	</tr>
	<tr>
	
		<td align="left" valign="top">...<?php echo $AppUI->_('If Y single dose NVP?') ?> </td>
		<td valign="top">
		<?php echo arraySelectRadio($boolTypes, "counselling_child_single_nvp", 'onclick=toggleButtons()', $obj->counselling_child_single_nvp? $obj->counselling_child_single_nvp : -1 ); ?>
		</td>		
     </tr>	
	 <tr>
	 
	 	<td align="left" valign="top">...<?php echo $AppUI->_('Date given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling_child_nvp_date" id="counselling_child_nvp_date" value="<?php echo $child_nvp_date ? $child_nvp_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>

		
     </tr>
	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Was AZT given?');?></td>
		
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling_child_azt", 'onclick=toggleButtons()', $obj->counselling_child_azt? $obj->counselling_child_azt : -1); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Date AZT given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling_child_azt_date" id="counselling_child_azt_date" value="<?php echo $child_azt_date ? $child_azt_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
    <tr>	
		<td nowrap>...<?php echo $AppUI->_('Number of doses') ?> </td>
		<td align="left"><input type="text" class="text" name="counselling_no_doses" id="counselling_no_doses" value="<?php echo $obj->counselling_no_doses;?>" maxlength="150" size="20"/></td>		
     </tr>
 
	 
	 <tr>
		<td align="left" ><?php echo $AppUI->_('Mother in treatment program?');?></td>
		<td align="left" >
			<?php echo arraySelectRadio($boolTypes, "counselling_mother_treatment", 'onclick=toggleButtons()', $obj->counselling_mother_treatment ? $obj->counselling_mother_treatment : -1 ); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother on ART in pregnancy');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling_mother_art_pregnancy", 'onclick=toggleButtons()', $obj->counselling_mother_art_pregnancy ? $obj->counselling_mother_art_pregnancy : -1 ); ?>
        </td>
     </tr>	 
	 <tr>
		<td align="left" valign="top" nowrap>...<?php echo $AppUI->_('Date began ART');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling_mother_date_art" id="counselling_mother_date_art" value="<?php echo $mother_date_art ? $mother_date_art->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	 </tr>
     <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Most recent maternal CD4 count');?>:</td>
		<td align="left"><input type="text" class="text" name="counselling_mother_cd4" id="counselling_mother_cd4" value="<?php echo $obj->counselling_mother_cd4;?>" maxlength="150" size="20"/></td>

	 </tr>	
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Date of CD4 test');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling_mother_date_cd4" id="counselling_mother_date_cd4" value="<?php echo $mother_date_cd4 ? $mother_date_cd4->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	 </tr>
	<tr>
			<td colspan="2" align="left" nowrap>
				<strong><?php echo $AppUI->_('PCR Tests'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr> 

	 <tr>
		<td align="left"><?php echo $AppUI->_('Determine');?>:</td>
	 </tr>
     <tr>
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_determine_date" id="counselling_determine_date" value="<?php echo $determine_date ? $determine_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left"><<?php echo arraySelectRadio($rapidResultsType, "counselling_determine", 'onclick=toggleButtons()', $obj->counselling_determine ? $obj->counselling_determine : -1 ); ?></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Bio-line');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_bioline_date" id="counselling_bioline_date" value="<?php echo $bioline_date ? $bioline_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left"><?php echo arraySelectRadio($rapidResultsType, "counselling_bioline", 'onclick=toggleButtons()', $obj->counselling_bioline ? $obj->counselling_bioline : -1 ); ?></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Uni-gold');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_unigold_date" id="counselling_unigold_date" value="<?php echo $unigold_date ? $unigold_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left"><?php echo arraySelectRadio($rapidResultsType, "counselling_unigold", 'onclick=toggleButtons()', $obj->counselling_unigold ? $obj->counselling_unigold : -1 ); ?></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('ELISA');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_elisa_date" id="counselling_elisa_date" value="<?php echo $elisa_date ? $elisa_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
		</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($elisaResultsType, "counselling_elisa", 'onclick=toggleButtons()', $obj->counselling_elisa ? $obj->counselling_elisa : -1 ); ?>
		</td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('PCR1');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_pcr1_date" id="counselling_pcr1_date" value="<?php echo $pcr1_date ? $pcr1_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($pcrResultsType, "counselling_pcr1", 'onclick=toggleButtons()', $obj->counselling_pcr1 ? $obj->counselling_pcr1 : -1 ); ?>
		
		</td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('PCR2');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_pcr2_date" id="counselling_pcr2_date" value="<?php echo $pcr2_date ? $pcr2_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($pcrResultsType, "counselling_pcr2", 'onclick=toggleButtons()', $obj->counselling_pcr2 ? $obj->counselling_pcr2 : -1 ); ?>
		</td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Rapid @ 12 months');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_rapid12_date" id="counselling_rapid12_date" value="<?php echo $rapid12_date ? $rapid12_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left">
		<?php echo arraySelectRadio($rapidResultsType, "counselling_rapid12", 'onclick=toggleButtons()', $obj->counselling_rapid12 ? $obj->counselling_rapid12 : -1 ); ?>
		</td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Rapid @ 18 months');?>:</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_rapid18_date" id="counselling_rapid18_date" value="<?php echo $rapid18_date ? $rapid18_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left"><?php echo arraySelectRadio($rapidResultsType, "counselling_rapid18", 'onclick=toggleButtons()', $obj->counselling_rapid18 ? $obj->counselling_rapid18 : -1 ); ?></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Other');?>:
		</td>
		<td>
		<input type="text" class="text" name="counselling_other_notes" id="counselling_other_notes" value="<?php echo $obj->counselling_other_notes;?>" maxlength="150" size="20"/>
		</td>
	 </tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Date');?>:</td>	
		<td align="left"><input type="text" class="text" name="counselling_other_date" id="counselling_other_date" value="<?php echo $other_date ? $other_date->format( $df ) : "" ;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
     <tr>	 
		<td align="left">...<?php echo $AppUI->_('Result');?>:</td>
		<td align="left"><input type="text" class="text" name="counselling_other" id="counselling_other" value="<?php echo $obj->counselling_other;?>" maxlength="150" size="20"/></td>
	 </tr>
<tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('History');?>:</td>
		<td valign="top">
		<textarea cols="70" rows="2" class="textarea" name="counselling_notes"><?php echo @$obj->counselling_notes;?></textarea>
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
