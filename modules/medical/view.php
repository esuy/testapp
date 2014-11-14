<?php /* MEDICAL ASSESSMENT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$medical_id = intval( dPgetParam( $_GET, "medical_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

$boolTypes = dPgetSysVal('YesNo');
$bornTypes = dPgetSysVal('BirthTypes');
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


// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $medical_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $medical_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();




// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CMedicalAssessment();
$canDelete = $obj->canDelete( $msg, $medical_id );

// load the record data
$q  = new DBQuery;
$q->addTable('medical_assessment');
$q->addQuery('medical_assessment.*');
$q->addWhere('medical_assessment.medical_id = '.$medical_id);
$sql = $q->prepare();
$q->clear();

if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Medical Asessment Record' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$boolTypes = dPgetSysVal('YesNo');
$medical_conditions = explode(",", $obj->medical_conditions);
$df = $AppUI->getPref('SHDATEFORMAT');
// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($obj->medical_client_id))
{
	$ttl = "View Medical Asessment : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Medical Asessment";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->medical_entry_date) ? new CDate($obj->medical_entry_date ) :  null;
//$mother_status_date = intval($obj->counselling_date_mothers_status_known) ? new CDate($obj->counselling_date_mothers_status_known ) :  null;
$medical_tb_date_diagnosed = intval( $obj->medical_tb_date_diagnosed ) ? new CDate( $obj->medical_tb_date_diagnosed ) : NULL;
$medical_tb_date1 = intval( $obj->medical_tb_date1 ) ? new CDate( $obj->medical_tb_date1 ) : NULL;
$medical_tb_date2 = intval( $obj->medical_tb_date2 ) ? new CDate( $obj->medical_tb_date2 ) : NULL;
$medical_tb_date3 = intval( $obj->medical_tb_date3 ) ? new CDate( $obj->medical_tb_date3 ) : NULL;
$medical_arv2_startdate = intval( $obj->medical_arv2_startdate ) ? new CDate( $obj->medical_arv2_startdate ) : NULL;
$medical_arv2_enddate = intval( $obj->medical_arv2_enddate ) ? new CDate( $obj->medical_arv2_enddate ) : NULL;
$medical_arv1_startdate = intval( $obj->medical_arv1_startdate ) ? new CDate( $obj->medical_arv1_startdate ) : NULL;
$medical_arv1_enddate = intval( $obj->medical_arv1_enddate ) ? new CDate( $obj->medical_arv1_enddate ) : NULL;



		
		
		
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new medical assessment record').'" />', '',
		'<form action="?m=medical&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=medical&a=addedit&medical_id=$medical_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete medical assessment record', $canDelete, $msg );
	}
}
$titleBlock->show();
?>
<script language="javascript">
<?php
// security improvement:
// some javascript functions may not appear on client side in case of user not having write permissions
// else users would be able to arbitrarily run 'bad' functions
if ($canDelete) {
?>
function delIt() {
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Medical Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=medical" method="post">
	<input type="hidden" name="dosql" value="do_medical_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="medical_id" value="<?php echo $medical_id;?>" />
</form>
<?php } ?>

<tr>
    <td colspan="2" valign="top">
      <table border="0" cellpadding = "1" cellspacing="1">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
  <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Registration Date');?>: </td>
			<td align="left" class="hilite">
				
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
		   </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Gender');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $genderTypes[$obj->medical_gender]; ?>
		 </td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Transferred from another programme?');?></td>
		 <td align="left" class="hilite">
		 <?php echo $boolTypes[$obj->medical_transferred]; ?>
		 </td>

       </tr>
	  <tr>	   
	      <td align="left">...<?php echo $AppUI->_('If Y, which?');?></td>
          <td class="hilite"><?php echo @$obj->medical_other_programme;?>
         </td>
	   </tr>	 
	   <tr>
		<td align="left" ><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_birth_weight;?></td>
      </tr>
	 	  
	   <tr>
		<td align="left"><?php echo $AppUI->_('PMTCT');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_pmtct;?></td>
      </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('ARVs given');?>:</td>
         </tr>
		 <tr>
		  <td>...<?php echo $AppUI->_('Mother');?>:</td>
		   <td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_mother_arv_given]; ?></td>
		 </tr>
		 <tr>
         <td align="left">...<?php echo $AppUI->_('Baby');?>:</td>
		 <td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_child_arv_given]; ?></td>
		</tr>
	       <tr>
         <td align="left"><?php echo $AppUI->_('Born');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $birthTypes[$obj->medical_birth_location]; ?>
		 </td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Delivery');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $obj->medical_delivery; ?>
		 </td>
       </tr>       
	   <tr>
         <td align="left"><?php echo $AppUI->_('Problems at or after birth');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $obj->medical_birth_problems; ?>
		 </td>
       </tr>	
		
	   <tr>
		<td align="left"><?php echo $AppUI->_('Immunization status');?>:</td>
		<td align="left" class="hilite"><?php echo $immunizationStatus[$obj->medical_immunization_status]; ?>
		</tr>
		<tr>
		<td align="left">...<?php echo $AppUI->_('Card seen?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_card_seen]; ?></td>
		</tr>
	   <tr>
		<td align="left"><?php echo $AppUI->_('Breastfeeding?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_breastfeeding]; ?></td>
	   </tr>
       <tr>	   
		<td align="left">...<?php echo $AppUI->_('Exclusive BF?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_exclusive_breastfeeding]; ?></td>
	   </tr>
       <tr>	   
		<td align="left">...<?php echo $AppUI->_('Duration of BF');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_bf_duration;?></td>
	   </tr>
     <tr>
			<td align="left"><?php echo $AppUI->_('Father HIV Status');?>:</td>
			<td align="left" class="hilite"><?php echo $hivStatus[$obj->medical_father_hiv_status]; ?></td>
	 </tr>
	 <tr>
			<td align="left">...<?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_father_arv]; ?></td>
	 </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('Mother HIV Status');?>:</td>
			<td align="left" class="hilite"><?php echo $hivStatus[$obj->medical_mother_hiv_status]; ?></td>
	 </tr>
	 <tr>
			<td align="left">...<?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_mother_arv]; ?></td>
	  </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('Number of siblings alive');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_no_siblings_alive;?></td>
		 </tr>
	 <tr>

			<td align="left"><?php echo $AppUI->_('Number of siblings deceased');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_no_siblings_deceased;?></td>
	  </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('TB: Any Household contact');?>:</td>
			<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_tb_contact];?></td>
	 </tr>
	 <tr>

			<td align="left">...<?php echo $AppUI->_('Who');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_tb_contact_person;?></td>
	 </tr>
	 <tr>

			<td align="left">...<?php echo $AppUI->_('When diagnosed');?>:</td>
			<td class="hilite">
			<?php echo $medical_tb_date_diagnosed ? $medical_tb_date_diagnosed->format( $df ) : "" ;?>
			 </td>
	  </tr> 
	  
	  
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Medical History'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		 <td align="left">
		 <?php
            /*if ($AppUI->isActiveModule('contacts') && $perms->checkModule('contacts', 'view')) 
		{
			echo "<input type='button' class='button' value='".$AppUI->_("enter medical history...")."' onclick='javascript:popFWContacts(selected_fw_contacts_id);' />";
		}
		*/
		?>
		 </td>
	  </tr>

	 <tr>
			<td align="left"><?php echo $AppUI->_('TB: ');?>:</td>
			<td align="left" class="hilite"><?php echo $tbPulmonaryTypes[$obj->medical_tb_pulmonary]; ?>
			</td>	
	 </tr>
	 <tr>
	   <td align="left"><?php echo $AppUI->_('Type');?>:</td>
	  <td align="left" class="hilite"><?php echo $tbTypes[$obj->medical_tb_type];?></td>
	
      </tr>	 
	  <tr>
	   <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
	  <td align="left" class="hilite"><?php echo $obj->medical_tb_type_desc;?></td>
	
      </tr>

      <tr>
			<td align="left"><?php echo $AppUI->_('Courses of treatment(dates)');?>:</td>
		
	  </tr>
	  <tr>
			  <td>
				...<?php echo $AppUI->_('1st');?>:
			  </td>
			  <td class="hilite">
				<?php echo $medical_tb_date1 ? $medical_tb_date1->format( $df ) : "" ;?>&nbsp;
			  </td>
	          </tr>
			  <tr>
			  <td>

			  ...<?php echo $AppUI->_('2nd');?>:
			  </td>
			  <td class="hilite">
			  <?php echo $medical_tb_date2 ? $medical_tb_date2->format( $df ) : "" ;?>&nbsp;<br/>
			  </td>
	          </tr>
			 <tr>
			  <td>

			  ...<?php echo $AppUI->_('3rd');?>:
			  </td>
			  <td class="hilite">

			  <?php echo $medical_tb_date3 ? $medical_tb_date3->format( $df ) : "" ;?>&nbsp;<br/>
	          </tr>
	   <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Have there been a recurring history of any of the following?');?></td>
		</tr>
		  <tr>
		  <td>...<?php echo $AppUI->_('Pneumonia');?>:</td>
		  <td class="hilite"><?php echo $boolTypes[$obj->medical_history_pneumonia]; ?></td>
		  </tr>
		  <tr>
			<td>
			...<?php echo $AppUI->_('Diarrhoeal episodes');?>:</td>
			<td class="hilite"><?php echo $boolTypes[$obj->medical_history_diarrhoea]; ?></td>
		 </tr>
		<tr>
			<td>
			...<?php echo $AppUI->_('Skin rashes');?>:
			</td>
			<td class="hilite"><?php echo $boolTypes[$obj->medical_history_skin_rash]; ?></td>
		</tr>
		<tr>
			<td>
			...<?php echo $AppUI->_('Ear discharge');?>:</td>
			<td class="hilite"><?php echo $boolTypes[$obj->medical_history_ear_discharge]; ?></td>
		</tr>
		<tr>
			<td>...<?php echo $AppUI->_('Fever ');?>:</td>
			<td class="hilite"><?php echo $boolTypes[$obj->medical_history_fever]; ?></td>
		</tr>
		<tr>
		<td>...<?php echo $AppUI->_('Persistent oral thrush');?>:</td>
		<td class="hilite"><?php echo $boolTypes[$obj->medical_history_oral_rush]; ?></td>
		</tr>
		<tr>
			<td>
			...<?php echo $AppUI->_('Mouth ulcers');?>:</td>
			<td class="hilite"><?php echo $boolTypes[$obj->medical_history_mouth_ulcers]; ?></td>
		</tr>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" class="hilite"><?php echo $malnutritionType[$obj->medical_history_malnutrition]; ?></td>
     </tr>	  
	 <tr>	
	<td align="left" valign="top"><?php echo $AppUI->_('Previous nutritional rehabilitation?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_history_prev_nutrition]; ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Other (specify)?');?></td>
		<td align="left" class="hilite">
		<?php echo str_replace( chr(10), "<br />", @$obj->medical_history_notes);?>&nbsp;
		</td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Medications'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('ARVs');?>:</td>
		<td align="left"  class="hilite"><?php echo $arvTreatmentTypes[$obj->medical_arv_status]; ?></td>     
	</tr>

 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('ARV regimes');?></td>
	  </tr>
     <tr>	  
		<td>
		
		...<?php echo $AppUI->_('1st line');?>
		</td>
		<td class="hilite">
		<?php echo $obj->medical_arv1;?>
		</td>
		</tr>
		<tr>
		<td>
		...<?php echo $AppUI->_('Started');?>:
		</td>
		<td class="hilite">
		<?php echo $medical_arv1_startdate ? $medical_arv1_startdate->format( $df ) : "" ;?>&nbsp;
		</td>
		</tr>
		<tr>
		<td>
		...<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td class="hilite">
		<?php echo $medical_arv1_enddate ? $medical_arv1_enddate->format( $df ) : "" ;?>&nbsp;
	   </td>
	 </tr>		
	 <tr>
		<td>
		
		...<?php echo $AppUI->_('2nd line');?>
		</td>
		<td class="hilite">
		<?php echo $obj->medical_arv2;?>
		</td>
		</tr>
		<tr>
		<td>
		...<?php echo $AppUI->_('Started');?>:
		</td>
		<td class="hilite">
		<?php echo $medical_arv2_startdate ? $medical_arv2_startdate->format( $df ) : "" ;?>&nbsp;
		</td>
		</tr>
		<tr>
		<td>
		...<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td class="hilite">
		<?php echo $medical_arv2_enddate ? $medical_arv2_enddate->format( $df ) : "" ;?>&nbsp;
	   </td>
	 </tr>	 
	 <tr>
		<td align="left">...<?php echo $AppUI->_('Side effects');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_arv_side_effects;?></td>
     </tr>
	 <tr>
		<td align="left">...<?php echo $AppUI->_('Adherence');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_arv_adherence;?></td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Development History and Diet'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Attend School Regularly');?>:</td>
		<td align="left" class="hilite">
		<?php echo $boolTypes[$obj->medical_school_attendance]; ?>
		</td>     
	</tr>
	<tr>
	   <td align="left">
			...<?php echo $AppUI->_('If Yes, class');?>
		</td>
       <td class="hilite">		
		<?php echo $obj->medical_school_class;?>
       </td>
	</tr>
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Progress');?>:</td>
		<td align="left" class="hilite"><?php echo $educProgressType[$obj->medical_educ_progress]; ?></td>     
	</tr>
	<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Sensory impairment');?>:</td>
	</tr>
		<tr>
		  <td>
		...<?php echo $AppUI->_('Hearing');?>
		</td>
		<td class="hilite">
		<?php echo $boolTypes[$obj->medical_sensory_hearing]; ?>
	    </td>
        </tr>
        <tr>		
		  <td>

		...<?php echo $AppUI->_('vision');?>:
		</td>
		<td class="hilite">

		<?php echo $boolTypes[$obj->medical_sensory_vision]; ?>
		</td>
        </tr>
        <tr>		

		<td>

		...<?php echo $AppUI->_('motor ability');?>:
		</td>
		<td class="hilite">
		<?php echo $motorAbilityType[$obj->medical_sensory_motor_ability]; ?>
        </td>
	    </tr>
	    <tr>
		<td>	
		...<?php echo $AppUI->_('speech and language');?>
		</td>
		<td class="hilite">

		<?php echo $boolTypes[$obj->medical_sensory_speech_language]; ?>
		</td>
		</tr>
		<tr>
		<td>

		...<?php echo $AppUI->_('social skills');?>:
		</td>
		<td class="hilite">
		<?php echo $boolTypes[$obj->medical_sensory_social_skills]; ?>
		</td>
	    </tr>
	    <tr>
		<td align="left"><?php echo $AppUI->_('Number of meals per day');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->medical_meals_per_day;?></td>
        </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Types of food (list)');?>:</td>
		<td align="left" class="hilite">
		<?php echo str_replace( chr(10), "<br />",@$obj->medical_food_types);?>&nbsp;
		</td>		
     </tr>
	 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Current complaints?');?></td>
		<td align="left" class="hilite">
		<?php echo str_replace( chr(10), "<br />", @$obj->medical_current_complaints);?>&nbsp;
		</td>
     </tr>	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Examination'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
        <td align="left"><?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->medical_weight);?>
        </td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_height;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('z score');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_zscore;?></td>
      </tr>      <tr>
			<td align="left"><?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_muac;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Head Circum (cm)');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_hc;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Looks');?>:</td>
			<td align="left" class="hilite"><?php echo $conditionType[$obj->medical_condition]; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Temperature (Celcius)');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_temp;?></td>
      </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Identify');?>:</td>
			<td align="left" class="hilite">
			<?php 
			foreach ($medical_conditions as $medical_condition)
			{
			     echo $examinationType[$medical_condition] . "<br/>";
			}
			?>
			</td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Dehydration');?>:</td>
			<td align="left" class="hilite"><?php echo $dehydrationType[$obj->medical_dehydration]; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Parotids');?>:</td>
			<td align="left" class="hilite"><?php echo $enlargementType[$obj->medical_parotids]; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Lymph nodes');?>:</td>
			<td align="left" class="hilite"><?php echo $lymphType[$obj->medical_lymph]; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Eyes');?>:</td>
			<td align="left" class="hilite"><?php echo $eyeType[$obj->medical_eyes]; ?></td>     
      </tr>      
	  <tr>
			<td align="left">...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" class="hilite"><?php echo $obj->medical_eyes_notes; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Ear discharge');?>:</td>
			<td align="left" class="hilite"><?php echo $earType[$obj->medical_ear_discharge]; ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Throat');?>:</td>
			<td align="left" class="hilite"><?php echo $throatType[$obj->medical_throat]; ?></td>     
      </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Mouth');?>:</td>
	   </tr>	
		<tr>
			  <td>
				...<?php echo $AppUI->_('thrush');?>:
			  </td>
			  <td class="hilite">	
				<?php echo $boolTypes[$obj->medical_mouth_thrush]; ?>
			</td>
				</tr>
				<tr>
				<td >		
			...<?php echo $AppUI->_('ulcers');?>:
			</td>
			  <td class="hilite">	
			<?php echo $boolTypes[$obj->medical_mouth_ulcers]; ?>
			</td>
				</tr>
				<tr>
				<td>
			...<?php echo $AppUI->_('teeth');?>:
			</td>
			<td class="hilite">
			<?php echo $teethType[$obj->medical_mouth_teeth]; ?>
			</td>     
			 </tr>
      </tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Skin');?>:</td>
	   </tr>
       <tr>	   
			<td align="left" valign="top">
			...<?php echo $AppUI->_('Old lesions');?>:
			</td>
			<td align="left" class="hilite"><?php echo $obj->medical_oldlesions;?></td>
		</tr>
		<tr>	
			<td align="left" valign="top">
			...<?php echo $AppUI->_('Current lesions');?>:
			</td>
			<td align="left" class="hilite"><?php echo $obj->medical_currentlesions;?>
			</td>     
      </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Respiratory and Cardiovascular'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>


			 <tr>
			  <td>
			<?php echo $AppUI->_('heart rate');?>:
			  </td>
			   <td class="hilite">
			    <?php echo $obj->medical_heartrate;?>
			</td>
			      </tr>
	<tr>
			<td>
			<?php echo $AppUI->_('recession');?>:
			</td>
			<td class="hilite">
			<?php echo $boolTypes[$obj->medical_recession]; ?>
			</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('percussion');?>:
			</td>
			<td class="hilite">
			<?php echo $percussionType[$obj->medical_percussion]; ?>
			</td>
      </tr>
	  <tr>		
	 	<td>
			<?php echo $AppUI->_('location');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_location;?>
			</td>
	      </tr>	  
		  <tr>		
	 	 <td>
			<?php echo $AppUI->_('breath sounds');?>:
			</td>
			<td class="hilite">
			  <?php echo $breathType[$obj->medical_breath_sounds]; ?>
			</td>
	      </tr>	  
		  <tr>		

			<td>
			<?php echo $AppUI->_('location');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_breathlocation;?>
			</td>
	      </tr>
	  
      </tr>
	  <tr>
			<td align="left">
			<?php echo $AppUI->_('added sounds');?>:
			</td>
			<td class="hilite">
			<?php echo $soundsType[$obj->medical_other_sounds]; ?>
			</td>
	 </tr>
      </tr>
	  <tr>
			<td>

	  <?php echo $AppUI->_('location');?>:
			</td>
			<td class="hilite">
	  
	  <?php echo $obj->medical_soundlocation;?>
			</td>     
      </tr>
	  <tr>
			<td align="left">
			<?php echo $AppUI->_('pulse rate');?>:
			</td>
			<td class="hilite">

			<?php echo $obj->medical_pulserate;?>
			</td>
      </tr>
	  <tr>

			<td>

			<?php echo $AppUI->_('apex beat');?>:
			</td>
			<td class="hilite">

			<?php echo $apexType[$obj->medical_apex_beat]; ?>
			</td>
      </tr>
	  <tr>

			<td>

			<?php echo $AppUI->_('Precordial activity');?>:
			</td>
			<td class="hilite">

			<?php echo $precordialType[$obj->medical_precordial]; ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('femoral pulses');?>:
						</td>
			<td class="hilite">
			<?php echo $femoralType[$obj->medical_femoral]; ?>
						</td>
				      </tr>	  
		  <tr>		
			
			<td>
			<?php echo $AppUI->_('heart');?>:
						</td>
			<td class="hilite">
			<?php echo $heartSoundType[$obj->medical_heart_sound]; ?>
						</td>
	      </tr>	  
		  <tr>		

						<td>
			<?php echo $AppUI->_('type');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_heart_type;?>
			</td>     
      </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Abdomen'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('distended');?>:
			</td>
			<td class="hilite">			
			<?php echo $boolTypes[$obj->medical_abdomen_distended]; ?>
				</td>
      </tr>
	<tr>
				<td>		
			<?php echo $AppUI->_('feel');?>:
						</td>
			<td class="hilite">
			<?php echo $feelType[$obj->medical_adbomen_feel]; ?>
						</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('tender');?>:
		</td>
		<td class="hilite">		
		<?php echo $boolTypes[$obj->medical_abdomen_tender]; ?>
						</td>
	      </tr>
	<tr>		
			<td>
			<?php echo $AppUI->_('fluid');?>:
						</td>
			<td class="hilite">
			<?php echo $boolTypes[$obj->medical_abdomen_fluid]; ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Liver (cm below costal margin)');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_liver_costal;?>
						</td>
      </tr>
	<tr>
						<td>
			<?php echo $AppUI->_('Spleen (cm below costal margin)');?>:
						</td>
		
		<td class="hilite">
			<?php echo $obj->medical_spleen_costal;?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Masses (specify)');?>:
			</td>
			<td class="hilite">		  
		  <?php echo $obj->medical_masses;?>
						</td>
      </tr>
	<tr>
						<td>
		
		<?php echo $AppUI->_('Umbilical hernia');?>:
						</td>
			<td class="hilite">
			<?php echo $umbilicalType[$obj->medical_umbilical_hernia]; ?>
			</td>     
      </tr>


	  <tr>
	  		<td align="left"  valign="top"><?php echo $AppUI->_('Genitalia');?>:</td>
	  </tr>
			<tr>
			  <td>
				...<?php echo $AppUI->_('Male testes ');?>:
			</td>
		
				<td class="hilite">
					<?php echo $palpableType[$obj->medical_testes]; ?>
				</td>
			</tr>
			<tr>		
				<td align="left">
					&nbsp;
				</td>
				<td align="left" class="hilite">
					<?php echo $directionType[$obj->medical_which_testes]; ?>
				</td>
			</tr>		

			<tr>
				<td>
				...<?php echo $AppUI->_('penis');?>:
				</td>
				<td class="hilite">
					<?php echo $penisTypes[$obj->medical_penis];?>
				</td>
			</tr>
			<tr>		
				<td>
					...<?php echo $AppUI->_('OR Female');?>:
				</td>
				<td class="hilite">
				<?php echo $femaleConditionType[$obj->medical_genitals_female]; ?>
				</td>     
			</tr>
			<tr>		
				<td align="left">
					...<?php echo $AppUI->_('Other');?>:
				</td>
				<td align="left" class="hilite">
					<?php echo $obj->medical_genitals_female_notes;?>
				</td>
			</tr>		

			
      <tr>
			<td align="left"><?php echo $AppUI->_('Pubertal development');?>:</td>
			
			<td align="left" class="hilite">
			<?php echo $boolTypes[$obj->medical_pubertal]; ?></td>     
      </tr>
	  	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Central Nervous System and Musculoskeletal'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Gait');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_gait;?>
			</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('Hand use');?>:
		</td>
		
		<td class="hilite">
			<?php echo $obj->medical_handuse;?>
		</td>     
      </tr>	
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Weakness');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_weakness;?>
			</td>
      </tr>
	<tr>
			<td>
			<?php echo $AppUI->_('Tone');?>:
			</td>
			<td class="hilite">	
			<?php echo $toneType[$obj->medical_tone]; ?>
			</td>     
      </tr>	  
      <tr>
	  		<td align="left" valign="top"><?php echo $AppUI->_('Tendon reflexes');?>:</td>
	   </tr>					
			<tr>
			<td>
			...<?php echo $AppUI->_('legs');?>:
			</td>
  
			<td class="hilite">
				<?php echo $tendonLegsType[$obj->medical_tendon_legs]; ?>
			</td>
			</tr>
			<tr>
			<td>
			...<?php echo $AppUI->_('arms');?>:
						</td>
			<td class="hilite">
			<?php echo $tendonArmsType[$obj->medical_tendon_arms]; ?>
			</td>
			</tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Abnormal movements');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_abnormal_movts;?>
						</td>

	   </tr>
	   
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Joints range of movement impaired');?>:
			</td>
			<td class="hilite">
			<?php echo $boolTypes[$obj->medical_movts_impaired]; ?>
					</td>
    </tr>
	<tr>			
	<td>	
			...<?php echo $AppUI->_('specify');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_movts_impaired_desc;?>
			</td>     
      </tr>	  
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Joints swelling');?>:
						</td>
			<td class="hilite">
			<?php echo $boolTypes[$obj->medical_joints_swelling]; ?>
						</td>
		    </tr>
	<tr>			
			<td>
			...<?php echo $AppUI->_('specify');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_joints_swelling_desc;?>
			</td>     
      </tr>	  
	  
	  <tr>
			<td align="left"><?php echo $AppUI->_('Motor');?>:</td>
			<td align="left" class="hilite">
			<?php echo $motorType[$obj->medical_motor]; ?>
			</td>     
      </tr>

	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Summary');?>:</td>
		<td class="hilite">
		<?php echo str_replace( chr(10), "<br />", $obj->medical_musc_notes);?>&nbsp;
		</td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Management Plan'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 
	<tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('HIV status');?>:
						</td>
			<td class="hilite">

			<?php echo $managementhivStatus[$obj->medical_hiv_status]; ?>
						</td>
    </tr>
	<tr>		
						<td>
			<?php echo $AppUI->_('CD4');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_cd4;?>
						</td>
	    </tr>
	<tr>				
			<td>
			<?php echo $AppUI->_('CD4%');?>:
						</td>
			<td class="hilite">
			<?php echo $obj->medical_cd4_percentage;?>
			</td>     
      </tr>	 
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Clinical stage (WHO)');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_who_clinical_stage;?>
			</td>
    </tr>
	<tr>				
			<td>
			<?php echo $AppUI->_('Immunological stage');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_immuno_stage;?>
			</td>     
      </tr>	 
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Tests');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_tests;?>
			</td>
	</tr>
	<tr>		
			<td>
			<?php echo $AppUI->_('Referral to');?>:
			</td>
			<td class="hilite">
			<?php echo $obj->medical_referral;?>
			</td>     
      </tr>	 
	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Treatment');?>:</td>
		<td valign="top" class="hilite">
		<?php echo str_replace( chr(10), "<br />", $obj->medical_notes);?>&nbsp;
		</td>
     </tr>
	<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
</tr>
</table>