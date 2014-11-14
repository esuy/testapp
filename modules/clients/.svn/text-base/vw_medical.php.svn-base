<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('medical');
require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');
require_once $AppUI->getModuleClass('admission');

$df = $AppUI->getPref('SHDATEFORMAT');


$boolTypes = dPgetSysVal('YesNo');
$boolTypeND = dPgetSysVal('YesNoND');
$boolRev = dPgetSysVal('NoYes');
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
$tbTypes = dPgetSysVal('TBType');
$tbPulmonaryTypes = dPgetSysVal('TBPulmonaryType');
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
$skinType = dPgetSysVal('ClearTypes');
$chestShape = dPgetSysVal('ChestShape');
$cnsType= dPgetSysVal('CNSType');
$bodyskel = dPgetSysVal('BodySkeleton');
$investigations = dPgetSysVal('RequestInvestigations');
$positions = dPgetSysVal('PositionOptions');

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


$q = new DBQuery;
$q->addTable('medical_assessment');
$q->addQuery ('medical_assessment.*');
$q->addWhere('medical_assessment.medical_client_id = '.$client_id);
$s='';
$sql= $q->prepare();


//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add medical assessment...";
	$url = "./index.php?m=medical&a=addedit&client_id=$client_id";
	$s .= '<tr><td colspan="6" align="left" valign="top">';
	$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$s .= '</td></tr>';
	echo $s;
}
else
{
	$title="Edit medical assessment...";
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
	
?>
<table cellspacing="1" cellpadding="2" width="100%" class="std">


<?php

	foreach ($rows as $row)
    {
		$url = "./index.php?m=medical&a=addedit&client_id=$client_id&medical_id=".$row["medical_id"];
		$obj = new CMedicalAssessment();
		$obj->load($row["medical_id"]);

		$medical_tb_date1 = intval( $obj->medical_tb_date1 ) ? new CDate( $obj->medical_tb_date1 ) : NULL;
		$entry_date = intval( $obj->medical_entry_date ) ? new CDate( $obj->medical_entry_date ) : NULL;
		$next_date = intval( $obj->medical_next_visit ) ? new CDate( $obj->medical_next_visit ) : NULL;
		$medical_tb_date2 = intval( $obj->medical_tb_date2 ) ? new CDate( $obj->medical_tb_date2 ) : NULL;
		$medical_tb_date3 = intval( $obj->medical_tb_date3 ) ? new CDate( $obj->medical_tb_date3 ) : NULL;
		$medical_tb_date_diagnosed = intval( $obj->medical_tb_date_diagnosed ) ? new CDate( $obj->medical_tb_date_diagnosed ) : NULL;
		$medical_conditions = explode(",", $obj->medical_conditions);
		$medical_lymph_nodes = explode(",", $obj->medical_lymph);
		
		$motor_ability = explode(",", $obj->medical_sensory_motor_ability);
		$medical_arv2_startdate = intval( $obj->medical_arv2_startdate ) ? new CDate( $obj->medical_arv2_startdate ) : NULL;
		$medical_arv2_enddate = intval( $obj->medical_arv2_enddate ) ? new CDate( $obj->medical_arv2_enddate ) : NULL;
		$medical_arv1_startdate = intval( $obj->medical_arv1_startdate ) ? new CDate( $obj->medical_arv1_startdate ) : NULL;
		$medical_arv1_enddate = intval( $obj->medical_arv1_enddate ) ? new CDate( $obj->medical_arv1_enddate ) : NULL;
		$medical_sal_startdate = intval( $obj->medical_salvage_startdate ) ? new CDate( $obj->medical_salvage_startdate ) : NULL;
		$medical_sal_enddate = intval( $obj->medical_salvage_enddate ) ? new CDate( $obj->medical_salvage_enddate ) : NULL;
		//load medical history
		$q = new DBQuery();
		$q->addTable("medical_history");
		$q->addQuery("medical_history.*");
		$q->addWhere("medical_history.medical_history_medical_id = " . $row["medical_id"]);
		$medicalrecords = $q->loadList();	
		
		//load medications
		$q = new DBQuery();
		$q->addTable("medications_history");
		$q->addQuery("medications_history.*");
		$q->addWhere("medications_history.medications_history_medical_id = " . $row["medical_id"]);
		$medications = $q->loadList();	

	$s .= '<tr><td colspan="6" align="left" valign="top">';
	//$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$s .= '<a href="'.$url . '">'. $AppUI->_( $title ).'</a>';
	$s .= '</td></tr>';
	echo $s;
//load clinics
if (! is_null ( $obj->medical_clinic_id )) {
	$q = new DBQuery ( );
	$q->addTable ( 'clinics', 'c' );	
	$q->addQuery ( 'clinic_name' );
	$q->addWhere ( 'clinic_id=' . $obj->medical_clinic_id );
	$q->setLimit ( 1 );
	$clinicName = $q->loadResult ();
}
	
?>

<tr>
		<td valign="top" width="100%">
		<table cellspacing="1" cellpadding="2">
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Details'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite"> <?php echo $clinicName; ?> </td>
			</tr>
			<tr>
				<td align="left" nowrap>1b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
				
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
			</tr>
			<tr>
				<td align="left">1c.<?php echo $AppUI->_('Clinician');?>:</td>
				<td align="left" class="hilite">
		 <?php echo $owners[$obj->medical_staff_id]; ?>
		 </td>
			</tr>
			
			<tr>
				<td align="left">3c.<?php echo $AppUI->_('Age (years)');?>:</td>
				<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->medical_age_yrs);?>
		 </td>
			</tr>
			<tr>
				<td>3d.<?php echo $AppUI->_('Age (months)');?>:</td>
				<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->medical_age_months);?>
		 </td>

			</tr>
			<tr>
				<td align="left">4a.<?php echo $AppUI->_('Transferred from another programme?');?></td>
				<td align="left" class="hilite">
		 			<?php echo $boolTypes[$obj->medical_transferred]; ?>
		 		</td>
			</tr>
			<tr>
				<td align="left">4b...<?php echo $AppUI->_('If Y, which?');?></td>
				<td class="hilite"><?php echo @$obj->medical_other_programme;?>
         </td>
			</tr>
			<tr>
				<td align="left">5a.<?php echo $AppUI->_('Birth Weight');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_birth_weight;?></td>
			</tr>

			<tr>
				<td align="left">5b.<?php echo $AppUI->_('PMTCT');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_pmtct];?></td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('ARVs given?');?></td>
			</tr>
			<tr>
				<td>5c...<?php echo $AppUI->_('Mother');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_mother_arv_given]; ?></td>
			</tr>
			<tr>
				<td align="left">5d...<?php echo $AppUI->_('Baby');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_child_arv_given]; ?></td>
			</tr>
			<tr>
				<td align="left">6a.<?php echo $AppUI->_('Born');?>:</td>
				<td align="left" class="hilite"><?php echo $bornTypes[$obj->medical_birth_location]; ?></td>
			</tr>
			<tr>
				<td align="left">6b.<?php echo $AppUI->_('Delivery');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_delivery;?></td>
			</tr>
			<tr>
				<td align="left">6c.<?php echo $AppUI->_('Problems at or after birth');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_birth_problems;?></td>
			</tr>
			<tr>
				<td align="left">7a.<?php echo $AppUI->_('Immunization status');?>:</td>
				<td align="left" class="hilite"><?php echo $immunizationStatus[$obj->medical_immunization_status]; ?>
		
			
			</tr>
			<tr>
				<td align="left">7b...<?php echo $AppUI->_('Card seen?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_card_seen]; ?></td>
			</tr>
			<tr>
				<td align="left">8a.<?php echo $AppUI->_('Breastfeeding?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_breastfeeding]; ?></td>
			</tr>
			<tr>
				<td align="left">8b...<?php echo $AppUI->_('Exclusive BF?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_exclusive_breastfeeding]; ?></td>
			</tr>
			<tr>
				<td align="left">8c...<?php echo $AppUI->_('Duration of BF');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_bf_duration;?></td>
			</tr>
			<tr>
				<td align="left">9a<?php echo $AppUI->_('Father HIV Status');?>:</td>
				<td align="left" class="hilite"><?php echo $hivStatus[$obj->medical_father_hiv_status]; ?></td>
			</tr>
			<tr>
				<td align="left">9b...<?php echo $AppUI->_('On ARVs');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_father_arv]; ?></td>
			</tr>
			<tr>
				<td align="left">10a.<?php echo $AppUI->_('Mother HIV Status');?>:</td>
				<td align="left" class="hilite"><?php echo $hivStatus[$obj->medical_mother_hiv_status]; ?></td>
			</tr>
			<tr>
				<td align="left">10b...<?php echo $AppUI->_('On ARVs');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_mother_arv]; ?></td>
			</tr>
			<tr>
				<td align="left">11a.<?php echo $AppUI->_('Number of siblings alive');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_no_siblings_alive;?></td>
			</tr>
			<tr>

				<td align="left">11b.<?php echo $AppUI->_('Number of siblings deceased');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_no_siblings_deceased;?></td>
			</tr>
			<tr>
				<td align="left">12a.<?php echo $AppUI->_('TB: Any Household contact');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_tb_contact];?></td>
			</tr>
			<tr>

				<td align="left">12b...<?php echo $AppUI->_('Who');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_tb_contact_person;?></td>
			</tr>
			<tr>

				<td align="left">12c...<?php echo $AppUI->_('When diagnosed?');?>:</td>
				<td class="hilite">
					<?php echo $medical_tb_date_diagnosed ? $medical_tb_date_diagnosed->format( $df ) : "" ;?>
			 	</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Medical History'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">&nbsp;</td>
				<td align="left">
				<table class="tbl">
					<tr>
						<th><?php echo $AppUI->_('Hospital');?></th>
						<th><?php echo $AppUI->_('Date');?></th>
						<th><?php echo $AppUI->_('Reason/diagnosis');?></th>
					</tr>
		 <?php foreach ($medicalrecords as $medicalrecord)
		 {
			$hospital_date = intval( $medicalrecord["medical_history_date"] ) ? new CDate( $medicalrecord["medical_history_date"] ) : NULL;
		 ?>
		 <tr>
						<td><?php echo $medicalrecord["medical_history_hospital"];?></td>
						<td><?php echo $hospital_date ? $hospital_date->format($df) : "";?></td>
						<td><?php echo $medicalrecord["medical_history_diagnosis"];?></td>
					</tr>
		 <?php } ?>
		 </table>
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
				<td align="left">16a.<?php echo $AppUI->_('TB: ');?>:</td>
				<td align="left" class="hilite"><?php echo $tbPulmonaryTypes[$obj->medical_tb_pulmonary]; ?>
			</td>
			</tr>
			<tr>
				<td align="left">16b.<?php echo $AppUI->_('Type');?>:</td>
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
					18a...<?php echo $AppUI->_('1st');?>:
			  	</td>
				<td class="hilite">
					<?php echo $medical_tb_date1 ? $medical_tb_date1->format( $df ) : "" ;?>&nbsp;
			  </td>
			</tr>
			<tr>
				<td>
			  		18b...<?php echo $AppUI->_('2nd');?>:
			  </td>
				<td class="hilite">
			  <?php echo $medical_tb_date2 ? $medical_tb_date2->format( $df ) : "" ;?>&nbsp;<br />
				</td>
			</tr>
			<tr>
				<td>
			  		18c...<?php echo $AppUI->_('3rd');?>:
			  	</td>
				<td class="hilite">
					<?php echo $medical_tb_date3 ? $medical_tb_date3->format( $df ) : "" ;?>&nbsp;<br />
				</td>		
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Have there been a recurring history of any of the following?');?></td>
			</tr>
			<tr>
				<td>18a...<?php echo $AppUI->_('Pneumonia');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_pneumonia]; ?></td>
			</tr>
			<tr>
				<td>
			18b...<?php echo $AppUI->_('Diarrhoeal episodes');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_diarrhoea]; ?></td>
			</tr>
			<tr>
				<td>
			18c...<?php echo $AppUI->_('Skin rashes');?>:
			</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_skin_rash]; ?></td>
			</tr>
			<tr>
				<td>
			18d...<?php echo $AppUI->_('Ear discharge');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_ear_discharge]; ?></td>
			</tr>
			<tr>
				<td>18e...<?php echo $AppUI->_('Fever ');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_fever]; ?></td>
			</tr>
			<tr>
				<td>18f...<?php echo $AppUI->_('Persistent oral thrush');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_oral_rush]; ?></td>
			</tr>
			<tr>
				<td>
					18g...<?php echo $AppUI->_('Mouth ulcers');?>:</td>
				<td class="hilite"><?php echo $boolTypes[$obj->medical_history_mouth_ulcers]; ?></td>
			</tr>
			</tr>
			<tr>
				<td align="left" valign="top">19a<?php echo $AppUI->_('Malnutrition');?>:</td>
				<td align="left" class="hilite"><?php echo $malnutritionType[$obj->medical_history_malnutrition]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">19b.<?php echo $AppUI->_('Previous nutritional rehabilitation?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->medical_history_prev_nutrition]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">20.<?php echo $AppUI->_('Current nutritional rehabilitation?');?></td>
				<td align="left" class="hilite">
					<?php echo wordwrap(str_replace( chr(10), "<br />", @$obj->medical_history_notes), 75,"<br />", true);?>&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Medications'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">21.<?php echo $AppUI->_('ARVs');?>:</td>
				<td align="left" class="hilite"><?php echo $arvTreatmentTypes[$obj->medical_arv_status]; ?></td>
			</tr>

			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('ARV regimes');?></td>
			</tr>
			<tr>
				<td>
		
		22a....<?php echo $AppUI->_('1st line');?>
		</td>
				<td class="hilite">
		<?php echo $obj->medical_arv1;?>
		</td>
			</tr>
			<tr>
				<td>
		22b....<?php echo $AppUI->_('Started');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_arv1_startdate ? $medical_arv1_startdate->format( $df ) : "" ;?>&nbsp;
		</td>
			</tr>
			<tr>
				<td>
		22c....<?php echo $AppUI->_('Stopped');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_arv1_enddate ? $medical_arv1_enddate->format( $df ) : "" ;?>&nbsp;
	   </td>
			</tr>
			<tr>
				<td>
		
		22d...<?php echo $AppUI->_('2nd line');?>
		</td>
				<td class="hilite">
		<?php echo $obj->medical_arv2;?>
		</td>
			</tr>
			<tr>
				<td>
		22e...<?php echo $AppUI->_('Started');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_arv2_startdate ? $medical_arv2_startdate->format( $df ) : "" ;?>&nbsp;
		</td>
			</tr>
			<tr>
				<td>
		22f...<?php echo $AppUI->_('Stopped');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_arv2_enddate ? $medical_arv2_enddate->format( $df ) : "" ;?>&nbsp;
	   </td>
		</tr>
		<tr>
		<td>22g.
		<?php echo $AppUI->_('Salvage');?>
		</td>
				<td class="hilite">
		<?php echo $obj->medical_salvage;?>
		</td>
			</tr>
			<tr>
				<td>
		22h...<?php echo $AppUI->_('Started');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_sal_startdate ? $medical_sal_startdate->format( $df ) : "" ;?>&nbsp;
		</td>
			</tr>
			<tr>
				<td>
		22i...<?php echo $AppUI->_('Stopped');?>:
		</td>
				<td class="hilite">
		<?php echo $medical_sal_enddate ? $medical_sal_enddate->format( $df ) : "" ;?>&nbsp;
	   </td>
			</tr>
			<tr>
				<td align="left">22j.<?php echo $AppUI->_('Side effects');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_arv_side_effects;?></td>
			</tr>
			<tr>
				<td align="left">22k.<?php echo $AppUI->_('Adherence');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_arv_adherence;?></td>
			</tr>
			<tr>
				<td align="left" nowrap valign="top">&nbsp;</td>
				<td align="left">
				<table class="tbl">
					<tr>
						<th><?php echo $AppUI->_('Drug');?></th>
						<th><?php echo $AppUI->_('Dose');?></th>
						<th><?php echo $AppUI->_('Frequency');?></th>
					</tr>
		 <?php foreach ($medications as $medication)
		 {
		 ?>
		 <tr>
						<td><?php echo $medication["medications_history_drug"];?></td>
						<td><?php echo $medication["medications_history_dose"];?></td>
						<td><?php echo $medication["medications_history_frequency"];?></td>
					</tr>
		 <?php } ?>
		 </table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Development History and Diet'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">29a.<?php echo $AppUI->_('Attend School Regularly');?>:</td>
				<td align="left" class="hilite">
					<?php echo $boolTypeND[$obj->medical_school_attendance]; ?>
				</td>
			</tr>
			<tr>
				<td align="left">29b...<?php echo $AppUI->_('If Yes, class');?>
		</td>
				<td class="hilite">		
		<?php echo $obj->medical_school_class;?>
       </td>
			</tr>
			<tr>
				<td align="left" valign="top">29c...<?php echo $AppUI->_('Progress');?>:</td>
				<td align="left" class="hilite"><?php echo $educProgressType[$obj->medical_educ_progress]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Sensory impairment');?>:</td>
			</tr>
			<tr>
				<td>
		30a...<?php echo $AppUI->_('Hearing');?>
		</td>
				<td class="hilite">
		<?php echo $boolTypes[$obj->medical_sensory_hearing]; ?>
	    </td>
			</tr>
			<tr>
				<td>

		30b...<?php echo $AppUI->_('vision');?>:
		</td>
				<td class="hilite">

		<?php echo $boolTypes[$obj->medical_sensory_vision]; ?>
		</td>
			</tr>
			<tr>

				<td>

		30c...<?php echo $AppUI->_('motor ability');?>:
		</td>
				<td class="hilite">
		<?php 
		foreach ($motor_ability as $motor_ability_option)
		{
			echo $motorAbilityType[$motor_ability_option] . "<br/>";
		}
		?>		
        </td>
			</tr>
			<tr>
				<td>	
		30d...<?php echo $AppUI->_('speech and language');?>
		</td>
				<td class="hilite">

		<?php echo $boolTypes[$obj->medical_sensory_speech_language]; ?>
		</td>
			</tr>
			<tr>
				<td>

		30e...<?php echo $AppUI->_('social skills');?>:
		</td>
				<td class="hilite">
		<?php echo $boolTypes[$obj->medical_sensory_social_skills]; ?>
		</td>
			</tr>
			<tr>
				<td align="left">31a.<?php echo $AppUI->_('Number of meals per day');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_meals_per_day;?></td>
			</tr>
			<tr>
				<td align="left">31b.<?php echo $AppUI->_('Types of food (list)');?>:</td>
				<td align="left" class="hilite">
		<?php echo wordwrap(str_replace( chr(10), "<br />",@$obj->medical_food_types), 75,"<br />", true);?>&nbsp;
		</td>
			</tr>
			<tr>
				<td align="left" valign="top">32.<?php echo $AppUI->_('Current complaints?');?></td>
				<td align="left" class="hilite">
		<?php echo wordwrap(str_replace( chr(10), "<br />", @$obj->medical_current_complaints), 75,"<br />", true);?>&nbsp;
		</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Examination'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">33a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
				<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->medical_weight);?>
        </td>
			</tr>
			<tr>
				<td align="left">33b.<?php echo $AppUI->_('Height (cm)');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_height;?></td>
			</tr>
			<tr>
				<td align="left">33c.<?php echo $AppUI->_('z score');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_zscore;?></td>
			</tr>
			<tr>
				<td align="left">33d.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_muac;?></td>
			</tr>
			<tr>
				<td align="left">33e.<?php echo $AppUI->_('Head Circum (cm)');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_hc;?></td>
			</tr>
			<tr>
				<td align="left">34a.<?php echo $AppUI->_('Is the child unwell');?>:</td>
				<td align="left" class="hilite"><?php echo $boolRev[$obj->medical_condition]; ?></td>
			</tr>
			<tr>
				<td align="left">34b.<?php echo $AppUI->_('Temperature (Celcius)');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_temp;?></td>
			</tr>
			<tr>
				<td align="left">34c.<?php echo $AppUI->_('Respiratory rate');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_resp_rate;?></td>
			</tr>
			<tr>
				<td align="left">34d.<?php echo $AppUI->_('Heart rate');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_heart_rate;?></td>
			</tr>			
			<tr>
				<td align="left" valign="top">35.<?php echo $AppUI->_('Identify');?>:</td>
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
				<td align="left">36a.<?php echo $AppUI->_('Dehydration');?>:</td>
				<td align="left" class="hilite"><?php echo $dehydrationType[$obj->medical_dehydration]; ?></td>
			</tr>
			<tr>
				<td align="left">36b.<?php echo $AppUI->_('Parotids');?>:</td>
				<td align="left" class="hilite"><?php echo $enlargementType[$obj->medical_parotids]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">37a.<?php echo $AppUI->_('Enlarged Lymph nodes');?>:</td>
				<td align="left" class="hilite">
			<?php 
			foreach ($medical_lymph_nodes as $medical_lymph_node)
			{
			     echo $lymphType[$medical_lymph_node] . "<br/>";
			}
			?>
			</td>
			</tr>
			<tr>
				<td align="left">37b.<?php echo $AppUI->_('Eyes');?>:</td>
				<td align="left" class="hilite"><?php echo $eyeType[$obj->medical_eyes]; ?></td>
			</tr>
			<tr>
				<td align="left">...<?php echo $AppUI->_('Specify');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_eyes_notes; ?></td>
			</tr>
			<tr>
				<td align="left">38a.<?php echo $AppUI->_('Ear discharge');?>:</td>
				<td align="left" class="hilite"><?php echo $earType[$obj->medical_ear_discharge]; ?></td>
			</tr>
			<tr>
				<td align="left">38b.<?php echo $AppUI->_('Throat');?>:</td>
				<td align="left" class="hilite"><?php echo $throatType[$obj->medical_throat]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Mouth');?>:</td>
			</tr>
			<tr>
				<td>
				39a...<?php echo $AppUI->_('thrush');?>:
			  </td>
				<td class="hilite">	
				<?php echo $boolTypes[$obj->medical_mouth_thrush]; ?>
			</td>
			</tr>
			<tr>
				<td>		
				39b...<?php echo $AppUI->_('ulcers');?>:
			</td>
				<td class="hilite">	
			<?php echo $boolTypes[$obj->medical_mouth_ulcers]; ?>
			</td>
			</tr>
			<tr>
				<td>
				39c...<?php echo $AppUI->_('teeth');?>:
			</td>
				<td class="hilite">
			<?php echo $teethType[$obj->medical_mouth_teeth]; ?>
			</td>
			</tr>
			</tr>
			<tr>
				<td align="left">40a.<?php echo $AppUI->_('Skin');?>:</td>
				<td align="left" class="hilite"><?php echo $skinType[$obj->medical_skin_type];?>
			</td>
			</tr>
			<tr>
				<td align="left">40b...<?php echo $AppUI->_('Specify');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->medical_skin_note;?>
			</td>
			</tr>

			<!--  <tr>	   
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
      </tr> -->
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Respiratory and Cardiovascular'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>


			<tr>
				<td>41a.
			<?php echo $AppUI->_('Respiratory rate');?>:
			  </td>
				<td class="hilite">
			    <?php echo $obj->medical_heartrate;?>
			</td>
			</tr>
			<tr>
				<td>41b.
			<?php echo $AppUI->_('recession');?>:
			</td>
				<td class="hilite">
			<?php echo $boolTypes[$obj->medical_recession]; ?>
			</td>
			</tr>
			<tr>
				<td>41c.
			<?php echo $AppUI->_('percussion');?>:
			</td>
				<td class="hilite">
			<?php echo $percussionType[$obj->medical_percussion]; ?>
			</td>
			</tr>
			<tr>
				<td>41d.
			<?php echo $AppUI->_('location');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_location;?>
			</td>
			</tr>
			<tr>
				<tr>
					<td>41e.
			<?php echo $AppUI->_('Shape of chest');?>:
			</td>
					<td class="hilite">
			<?php echo $chestShape[$obj->medical_chest_shape]; ?>
			</td>
				</tr>

				<td>42a.
			<?php echo $AppUI->_('breath sounds');?>:
			</td>
				<td class="hilite">
			  <?php echo $breathType[$obj->medical_breath_sounds]; ?>
			</td>
			</tr>
			<tr>

				<td>42b.
			<?php echo $AppUI->_('location');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_breathlocation;?>
			</td>
			</tr>

			</tr>
			<tr>
				<td align="left">43a.
					<?php echo $AppUI->_('added sounds');?>:
				</td>
				<td class="hilite">
					<?php echo $soundsType[$obj->medical_other_sounds]; ?>
				</td>
			</tr>
			</tr>
			<tr>
				<td>43b.<?php echo $AppUI->_('location');?>:
			</td>
				<td class="hilite">
	  
	  <?php echo $obj->medical_soundlocation;?>
			</td>
			</tr>
			<tr>
				<td align="left">44a.
			<?php echo $AppUI->_('pulse rate');?>:
			</td>
				<td class="hilite">

			<?php echo $obj->medical_pulserate;?>
			</td>
			</tr>
			<tr>
				<td>44b.
					<?php echo $AppUI->_('apex beat');?>:
				</td>
				<td class="hilite">
					<?php echo $apexType[$obj->medical_apex_beat]; ?>
				</td>
			</tr>
			<tr>

				<td>44c.
					<?php echo $AppUI->_('Precordial activity');?>:
				</td>
				<td class="hilite">

			<?php echo $precordialType[$obj->medical_precordial]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">45a.
					<?php echo $AppUI->_('femoral pulses');?>:
				</td>
				<td class="hilite">
					<?php echo $femoralType[$obj->medical_femoral]; ?>
				</td>
			</tr>
			<tr>

				<td>45b.
			<?php echo $AppUI->_('heart');?>:
						</td>
				<td class="hilite">
			<?php echo $heartSoundType[$obj->medical_heart_sound]; ?>
						</td>
			</tr>
			<tr>

				<td>45c.
			<?php echo $AppUI->_('type');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_heart_type;?>
			</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Abdomen'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>

			<tr>
				<td align="left" valign="top">46a.
			<?php echo $AppUI->_('distended');?>:
			</td>
				<td class="hilite">			
			<?php echo $boolTypes[$obj->medical_abdomen_distended]; ?>
				</td>
			</tr>
			<tr>
				<td>46b.
			<?php echo $AppUI->_('feel');?>:
						</td>
				<td class="hilite">
			<?php echo $feelType[$obj->medical_adbomen_feel]; ?>
						</td>
			</tr>
			<tr>
				<td>46c.
			<?php echo $AppUI->_('tender');?>:
		</td>
				<td class="hilite">		
		<?php echo $boolTypes[$obj->medical_abdomen_tender]; ?>
						</td>
			</tr>
			<tr>
				<td>46d.
			<?php echo $AppUI->_('fluid');?>:
						</td>
				<td class="hilite">
			<?php echo $boolTypes[$obj->medical_abdomen_fluid]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">47a.
					<?php echo $AppUI->_('Liver (cm below costal margin)');?>:
				</td>
				<td class="hilite">
					<?php echo $obj->medical_liver_costal;?>
				</td>
			</tr>
			<tr>
				<td>47b.
			<?php echo $AppUI->_('Spleen (cm below costal margin)');?>:
						</td>

				<td class="hilite">
			<?php echo $obj->medical_spleen_costal;?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">48a.
					<?php echo $AppUI->_('Masses (specify)');?>:
				</td>
				<td class="hilite">		  
		  			<?php echo $obj->medical_masses;?>
				</td>
			</tr>
			<tr>
				<td>48b.
					<?php echo $AppUI->_('Umbilical hernia');?>:
				</td>
				<td class="hilite">
			<?php echo $umbilicalType[$obj->medical_umbilical_hernia]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Genitalia');?>:</td>
			</tr>
			<tr>
				<td>
				49a...<?php echo $AppUI->_('Male testes ');?>:
			</td>
				<td class="hilite">
					<?php echo $palpableType[$obj->medical_testes]; ?>
				</td>
			</tr>
			<tr>
				<td align="left">49b.&nbsp;</td>
				<td align="left" class="hilite">
					<?php echo $directionType[$obj->medical_which_testes]; ?>
				</td>
			</tr>
			<tr>
				<td>
				49c...<?php echo $AppUI->_('penis');?>:
				</td>
				<td class="hilite">
					<?php echo $penisTypes[$obj->medical_penis];?>
				</td>
			</tr>
			<tr>
				<td>
					49d...<?php echo $AppUI->_('OR Female');?>:
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
				<td align="left">50.<?php echo $AppUI->_('Pubertal development');?>:</td>

				<td align="left" class="hilite">
			<?php echo $developmentType[$obj->medical_pubertal]; ?></td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Central Nervous System and Musculoskeletal'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>

			<tr>
				<td align="left" valign="top">51a.			
			<?php echo $AppUI->_('Central Nervous System');?>:
						</td>
				<td class="hilite">
			<?php echo $cnsType[$obj->medical_cns];?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">...			
			<?php echo $AppUI->_('Specify');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_cns_note;?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">51b.			
			<?php echo $AppUI->_('Musculoskeletal');?>:
						</td>
				<td class="hilite">
			<?php echo $cnsType[$obj->medical_muscle];?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">...			
			<?php echo $AppUI->_('Specify');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_muscle_note;?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">52a.			
			<?php echo $AppUI->_('Gait');?>:
						</td>
				<td class="hilite">
			<?php echo $bodyskel[$obj->medical_gait_opt];?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">...			
			<?php echo $AppUI->_('Specify');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_gait;?>
			</td>
			</tr>
			<tr>
				<td>52b.
			<?php echo $AppUI->_('Hand use');?>:
		</td>

				<td class="hilite">
			<?php echo $bodyskel[$obj->medical_handuse_opt];?>
		</td>
			</tr>
			<tr>
				<td>...
			<?php echo $AppUI->_('Specify');?>:
		</td>
				<td class="hilite">
			<?php echo $obj->medical_handuse;?>
		</td>
			</tr>
			<tr>
				<td align="left" valign="top">53a.
			
			<?php echo $AppUI->_('Weakness');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_weakness;?>
			</td>
			</tr>
			<tr>
				<td>53b.
			<?php echo $AppUI->_('Tone');?>:
			</td>
				<td class="hilite">	
			<?php echo $toneType[$obj->medical_tone]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">
					<?php echo $AppUI->_('Tendon reflexes');?>:
				</td>
			</tr>
			<tr>
				<td>
			54...<?php echo $AppUI->_('legs');?>:
			</td>

				<td class="hilite">
				<?php echo $tendonLegsType[$obj->medical_tendon_legs]; ?>
			</td>
			</tr>
			<tr>
				<td>
			55...<?php echo $AppUI->_('arms');?>:
						</td>
				<td class="hilite">
			<?php echo $tendonArmsType[$obj->medical_tendon_arms]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">56.
			
			<?php echo $AppUI->_('Abnormal movements');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_abnormal_movts;?>
						</td>

			</tr>

			<tr>
				<td align="left" valign="top">57a.
			<?php echo $AppUI->_('Joints range of movement impaired');?>:
			</td>
				<td class="hilite">
			<?php echo $boolTypes[$obj->medical_movts_impaired]; ?>
					</td>
			</tr>
			<tr>
				<td>	
			57b...<?php echo $AppUI->_('specify');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_movts_impaired_desc;?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">58a.
			<?php echo $AppUI->_('Joints swelling');?>:
						</td>
				<td class="hilite">
			<?php echo $boolTypes[$obj->medical_joints_swelling]; ?>
						</td>
			</tr>
			<tr>
				<td>58b...
			<?php echo $AppUI->_('specify');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_joints_swelling_desc;?>
			</td>
			</tr>

			<tr>
				<td align="left">59.<?php echo $AppUI->_('Motor');?>:</td>
				<td align="left" class="hilite">
			<?php echo $motorType[$obj->medical_motor]; ?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">60.
					<?php echo $AppUI->_('Summary');?>:
				</td>
				<td class="hilite">
		<?php echo wordwrap(str_replace( chr(10), "<br />", $obj->medical_musc_notes), 75,"<br />", true);?>&nbsp;
		</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Management Plan'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>

			<tr>
				<td align="left" valign="top">61a.
			<?php echo $AppUI->_('HIV status');?>:
						</td>
				<td class="hilite">

			<?php echo $managementhivStatus[$obj->medical_hiv_status]; ?>
						</td>
			</tr>
			<tr>
				<td>61b.
			<?php echo $AppUI->_('CD4');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_cd4;?>
						</td>
			</tr>
			<tr>
				<td>61c.
			<?php echo $AppUI->_('CD4%');?>:
						</td>
				<td class="hilite">
			<?php echo $obj->medical_cd4_percentage;?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top">62a.
			<?php echo $AppUI->_('Clinical stage (WHO)');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_who_clinical_stage;?>
			</td>
			</tr>
			<tr>
				<td>62b.
			<?php echo $AppUI->_('Immunological stage');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_immuno_stage;?>
			</td>
			</tr>
			<tr>
				<td>63
			<?php echo $AppUI->_('Request Investigations');?>:
			</td>
				<td class="hilite">
				<?php 
					echo $boolTypes[$obj->medical_request];
					echo '<br>';
					if(strlen($obj->medical_request_opts) > 0){
						$tar = explode(',',$obj->medical_request_opts);
						foreach ($tar as $tv){
							$str.=$investigations[$tv].' ';
						}
						echo $str;
					}
				?>
			</td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Treatment');?>:</td>
				<td valign="top" class="hilite">
					<?php echo wordwrap(str_replace( chr(10), "<br />", $obj->medical_notes), 75,"<br />", true);?>&nbsp;
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">...
			<?php echo $AppUI->_('Other');?>:
			</td>
				<td class="hilite">
			<?php echo $obj->medical_request_note;?>
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
				<td>65.
			<?php echo $AppUI->_('Referral to');?>:
			</td>
				<td class="hilite">
			<?php echo $positions[$obj->medical_referral];?>
			</td>
			</tr>
			<tr>
				<td>
			<?php echo $AppUI->_('Next Appointment');?>:
			</td>
				<td class="hilite">
			<?php echo $next_date ? $next_date->format($df): '';?>
			</td>
			</tr>
			
	 <?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->medical_id, "view" );
			$custom_fields->printHTML();
		?>
	</td>
			</tr>
		</table>
	
	</tr>
<?php
	}
}
?>

</table>
