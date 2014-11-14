<?php
global $AppUI,$dPconfig,$loadFromTab, $tab;
global $obj, $client_id, $url,$can_edit_contact_information;
global $convert;

require_once ($AppUI->getModuleClass('medical'));


$perms = & $AppUI->acl();

$canEdit = true;
$msg = '';
$row = new CMedicalAssessment();

$boolTypes = dPgetSysVal('YesNo');
$genderTypes = dPgetSysVal('GenderType');
$immunizationStatus = dPgetSysVal('ImmunizationType');
$hivStatus = dPgetSysVal('HIVStatus');
$malnutritionType = dPgetSysVal('MalnutritionType');
$educProgressType = dPgetSysVal('EducationProgressType');
$motorType = dPgetSysVal('MotorAbilityType');
$dehydrationType = dPgetSysVal('MalnutritionType');
$lymphType = dPgetSysVal('LymphType');
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
$palpableType = dPgetSysVal('NormalIncReducedType');
$directionType = dPgetSysVal('NormalIncReducedType');
$conditionType = dPgetSysVal('ConditionType');
$examinationType = dPgetSysVal('ExaminationType');

if (isset($client_id) && $client_id > 0)
{
	$sql = 'SELECT * FROM medical_assessment WHERE medical_client_id = ' . $client_id;

	if (!db_loadObject( $sql, $row) && !isset($convert))
	{
		$AppUI->setMsg('Medical Assessment Info');
		$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
		$AppUI->redirect();
	}
}


$date_reg = date("Y-m-d");
$entry_date = intval( $date_reg) ? new CDate( dPgetParam($_REQUEST, "medical_entry_date", date("Y-m-d") ) ) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

?>
<script language="javascript">
var selected_fw_contacts_id = "<?php echo $row->medical_firewall_contact; ?>";
var selected_vpn_contacts_id = "<?php echo $row->medical_vpn_contact; ?>";
var client_id = "<?php echo $row->medical_client_id;?>";

function popFWContacts() 
{
	window.open('./index.php?m=public&a=contact_selector&dialog=1&call_back=setFWContacts&selected_contacts_id='+selected_fw_contacts_id, 'contacts','height=600,width=450,resizable,scrollbars=yes');
}

function popVPNContacts() 
{
	window.open('./index.php?m=public&a=contact_selector&dialog=1&call_back=setVPNContacts&selected_contacts_id='+selected_vpn_contacts_id, 'contacts','height=600,width=450,resizable,scrollbars=yes');
}

function setFWContacts(contact_id_string)
{

	if(!contact_id_string)
	{
		contact_id_string = "";
	}
	
	medical_firewall_contact = document.getElementById('medical_firewall_contact');
	medical_firewall_contact.value = contact_id_string;
	
	selected_fw_contacts_id = contact_id_string;
}

function setVPNContacts(contact_id_string)
{

	if(!contact_id_string)
	{
		contact_id_string = "";
	}
	
	medical_vpn_contact = document.getElementById('medical_vpn_contact');
	medical_vpn_contact.value = contact_id_string;
	selected_vpn_contacts_id = contact_id_string;
}
function toggleButtons()
{
	client_on_bs = document.getElementById('on_bs');
	client_not_on_bs = document.getElementById('not_on_bs');
	building_solution = document.getElementById('building_solution');
	
	building_solution.disabled = true;
	
	if ((!client_on_bs.checked) && (!client_not_on_bs.checked))
	{
		building_solution.disabled = true;
	}
	
	if ((client_on_bs.checked) || (selected_bs_id > 0))
	{
		building_solution.disabled = false;
	}
    	
}
function popBuildingSolutions(field)
{
	bs_name = field;
	window.open('./index.php?m=public&a=bs_selector&dialog=1&call_back=setBuildingSolution&selected_bs_id='+selected_bs_id, 'building_solutions','height=600,width=600,resizable,scrollbars=yes');
}

function setBuildingSolution(bs_id_string, bs_location)
{

	if(!bs_id_string)
	{
		bs_id_string = "";
	}
	building_solution_id = document.getElementById('building_solution_id');
	
	building_solution = document.getElementById('bs_name');
	building_solution_id.value = bs_id_string;
	
	bs_name.value = bs_location;
}

</script>
<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">
<form name="counsellingInfoFrm" action="?m=clients&a=addedit&client_id=<?php echo $client_id; ?>" method="post">
  <input type="hidden" name="dosql" value="do_newclient_aed"/>
  <input type="hidden" name="del" value="0" />
  <input type="hidden" name="medical_unique_update" value="<?php echo uniqid("");?>" />
  <input type="hidden" name="medical[medical_id]" value="<?php echo $row->medical_id;?>" />
  <input type="hidden" name="medical[medical_client_id]" value="<?php echo $client_id;?>" />
  <input type="hidden" name="medical[medical_clinician]" id="medical_clinician" value="<?php echo @$row->medical_clinician;?>" />

<tr>
    <td colspan="2" valign="top">
      <table border="0" cellpadding = "1" cellspacing="1">
	   <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Registration Date');?>: </td>
			<td align="left">
				<input type="hidden" name="log_entry_date" value="<?php echo $entry_date ? $entry_date->format( FMT_TIMESTAMP_DATE ) : "" ;?>" />
				<input type="text" name="medical_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text" readonly disabled="disabled" />
			</td>
		   </tr>

       <tr>
         <td align="left"><?php echo $AppUI->_('Transferred from another programme?');?>:</td>
		 <td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_transferred]", 'onclick=toggleButtons()', $row->medical_transferred? $row->medical_transferred : -1, $identifiers ); ?></td>

       </tr>
	  <tr>	   
	      <td align="left"><?php echo $AppUI->_('If Y, which?');?>:</td>
          <td><input type="text" class="text" name="medical[medical_other_programme]" value="<?php echo @$row->medical_other_programme;?>" maxlength="150" size="20" />
         </td>
	   </tr>	 
	   <tr>
		<td align="left"><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_birth_weight]" id="medical_birth_weight" value="<?php echo $obj->medical_birth_weight;?>" maxlength="150" size="20"/></td>
      </tr>
	 	  
	   <tr>
		<td align="left"><?php echo $AppUI->_('PMTCT');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_pmtct]" id="medical_pmtct" value="<?php echo $obj->medical_pmtct;?>" maxlength="150" size="20"/></td>
      </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('ARVs given');?>:</td>
         <td align="left">
		 <table>
		 <tr>
		  <td><?php echo $AppUI->_('Mother');?>:</td>
		   <td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_mother_arv_given]", 'onclick=toggleButtons()', $row->medical_mother_arv_given? $row->medical_mother_arv_given : -1, $identifiers ); ?></td>
		 </tr>
		 <tr>
         <td align="left"><?php echo $AppUI->_('Baby');?>:</td>
		 <td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_child_arv_given]", 'onclick=toggleButtons()', $row->medical_child_arv_given? $row->medical_child_arv_given : -1, $identifiers ); ?></td>
		</tr>
 	    </table>
       </td> 		
	   <tr>
		<td align="left"><?php echo $AppUI->_('Immunization status');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($immunizationStatus, "medical[medical_immunization_status]", 'onclick=toggleButtons()', $row->medical_immunization_status ? $row->medical_immunization_status : -1, $identifiers ); ?>
		</tr>
		<tr>
		<td align="left"><?php echo $AppUI->_('Card seen?');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_card_seen]", 'onclick=toggleButtons()', $row->medical_card_seen ? $row->medical_card_seen : -1, $identifiers ); ?></td>
		</tr>
	   <tr>
		<td align="left"><?php echo $AppUI->_('Breastfeeding?');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_breastfeeding]", 'onclick=toggleButtons()', $row->medical_breastfeeding ? $row->medical_breastfeeding : -1, $identifiers ); ?></td>
	   </tr>
       <tr>	   
		<td align="left"><?php echo $AppUI->_('Exclusive BF?');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_exclusive_breastfeeding]", 'onclick=toggleButtons()', $row->medical_exclusive_breastfeeding ? $row->medical_exclusive_breastfeeding : -1, $identifiers ); ?></td>
	   </tr>
       <tr>	   
		<td align="left"><?php echo $AppUI->_('Duration of BF');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_bf_duration]" id="medical_bf_duration" value="<?php echo $obj->medical_bf_duration;?>" maxlength="150" size="20"/></td>
	   </tr>
     <tr>
			<td align="left"><?php echo $AppUI->_('Father HIV Status');?>:</td>
			<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($hivStatus, "medical[medical_father_hiv_status]", 'onclick=toggleButtons()', $row->medical_father_hiv_status ? $row->medical_father_hiv_status : -1, $identifiers ); ?></td>
	 </tr>
	 <tr>
			<td align="left"><?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_father_arv]", 'onclick=toggleButtons()', $row->medical_father_arv ? $row->medical_father_arv : -1, $identifiers ); ?></td>
	 </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('Mother HIV Status');?>:</td>
			<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($hivStatus, "medical[medical_mother_hiv_status]", 'onclick=toggleButtons()', $row->medical_mother_hiv_status ? $row->medical_mother_hiv_status : -1, $identifiers ); ?></td>
	 </tr>
	 <tr>
			<td align="left"><?php echo $AppUI->_('On ARVs');?>:</td>
			<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_mother_arv]", 'onclick=toggleButtons()', $row->medical_mother_arv ? $row->medical_mother_arv : -1, $identifiers ); ?></td>
	  </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('Number of siblings alive');?>:</td>
			<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_no_siblings_alive]" id="medical_no_siblings_alive" value="<?php echo $obj->medical_no_siblings_alive;?>" maxlength="150" size="20"/></td>
		 </tr>
	 <tr>

			<td align="left"><?php echo $AppUI->_('Number of siblings deceased');?>:</td>
			<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_no_siblings_deceased]" id="medical_no_siblings_deceased" value="<?php echo $obj->medical_no_siblings_deceased;?>" maxlength="150" size="20"/></td>
	  </tr> 
     <tr>
			<td align="left"><?php echo $AppUI->_('TB: Any Household contact');?>:</td>
			<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_tb_contact]" id="medical_tb_contact" value="<?php echo $obj->medical_tb_contact;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>

			<td align="left"><?php echo $AppUI->_('Who');?>:</td>
			<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_tb_contact_person]" id="medical_tb_contact_person" value="<?php echo $obj->medical_tb_contact_person;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>

			<td align="left"><?php echo $AppUI->_('When diagnosed');?>:</td>
			<td>
				<input type="text" class="text" name="medical[medical_tb_date_diagnosed]" id="medical_tb_date_diagnosed" value="<?php echo $obj->medical_tb_date_diagnosed;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
			 </td>
	  </tr> 
	  
	  
	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Medical History'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		 <td align="left">
		 <?php
            if ($AppUI->isActiveModule('contacts') && $perms->checkModule('contacts', 'view')) 
		{
			echo "<input type='button' class='button' value='".$AppUI->_("enter medical history...")."' onclick='javascript:popFWContacts(selected_fw_contacts_id);' />";
		}?>
		 </td>
	  </tr>

	 <tr>
			<td align="left"><?php echo $AppUI->_('TB: ');?>:</td>
			<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_tb_pulmonary]", 'onclick=toggleButtons()', $row->medical_tb_pulmonary ? $row->medical_tb_pulmonary : -1, $identifiers ); ?>
			</td>	
	 </tr>
	 <tr>
	 <td align="left"><?php echo $AppUI->_('Body site');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_tb_bodysite]" id="medical_tb_bodysite" value="<?php echo $obj->medical_tb_bodysite;?>" maxlength="150" size="20"/></td>
	
      </tr>

      <tr>
			<td align="left"><?php echo $AppUI->_('Courses of treatment(dates)');?>:</td>
			<td align="left" valign="top">
			<table>
			 <tr>
			  <td>
				<?php echo $AppUI->_('1st');?>:
			  </td>
			  <td>
				<input type="text" class="text" name="medical[medical_tb_date1]" id="medical_tb_date1" value="<?php echo $obj->medical_tb_date1;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
			  </td>
	          </tr>
			  <tr>
			  <td>

			  <?php echo $AppUI->_('2nd');?>:
			  </td>
			  <td>
			  <input type="text" class="text" name="medical[medical_tb_date2]" id="medical_tb_date2" value="<?php echo $obj->medical_tb_date2;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd<br/>
			  </td>
	          </tr>
			 <tr>
			  <td>

			  <?php echo $AppUI->_('3rd');?>:
			  </td>
			  <td>

			  <input type="text" class="text" name="medical[medical_tb_date3]" id="medical_tb_date3" value="<?php echo $obj->medical_tb_date3;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd<br/>
	          </tr>
			 <tr>
			</table>

			  </td>

      </tr>
	  <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Have there been a recurring history of any of the following?');?>:</td>
		<td align="left">
		<table>
		  <tr>
		  <td><?php echo $AppUI->_('Pneumonia');?>:</td>
		  <td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_pneumonia]", 'onclick=toggleButtons()', $row->medical_history_pneumonia ? $row->medical_history_pneumonia : -1, $identifiers ); ?></td>
		  </tr>
		<tr><td><?php echo $AppUI->_('Diarrhoeal episodes');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_diarrhoea]", 'onclick=toggleButtons()', $row->medical_history_diarrhoea ? $row->medical_history_diarrhoea : -1, $identifiers ); ?></td></tr>
		<tr><td><?php echo $AppUI->_('Skin rashes');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_skin_rash]", 'onclick=toggleButtons()', $row->medical_history_skin_rash ? $row->medical_history_skin_rash : -1, $identifiers ); ?></td></tr>
		<tr><td><?php echo $AppUI->_('Ear discharge');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_ear_discharge]", 'onclick=toggleButtons()', $row->medical_history_ear_discharge ? $row->medical_history_ear_discharge : -1, $identifiers ); ?></td></tr>
		<tr><td><?php echo $AppUI->_('Fever ');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_fever]", 'onclick=toggleButtons()', $row->medical_history_fever ? $row->medical_history_fever : -1, $identifiers ); ?></td></tr>
		<tr><td><?php echo $AppUI->_('Persistent oral thrush');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_oral_rush]", 'onclick=toggleButtons()', $row->medical_history_oral_rush ? $row->medical_history_oral_rush : -1, $identifiers ); ?></td></tr>
		<tr><td><?php echo $AppUI->_('Mouth ulcers');?>:</td><td>&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_mouth_ulcers]", 'onclick=toggleButtons()', $row->medical_history_mouth_ulcers ? $row->medical_history_mouth_ulcers : -1, $identifiers ); ?></td></tr>
		</table>
		</td>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($malnutritionType, "medical[medical_history_malnutrition]", 'onclick=toggleButtons()', $row->medical_history_malnutrition ? $row->medical_history_malnutrition : -1, $identifiers ); ?></td>
     </tr>	  
	 <tr>	
	<td align="left" valign="top"><?php echo $AppUI->_('Previous nutritional rehabilitation?');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_history_prev_nutrition]", 'onclick=toggleButtons()', $row->medical_history_prev_nutrition ? $row->medical_history_prev_nutrition : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Other (specify)?');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="medical[medical_history_notes]"><?php echo @$obj->medical_history_notes;?></textarea>
		</td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('ARVs');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_arv_status]", 'onclick=toggleButtons()', $row->medical_arv_status ? $row->medical_arv_status : -1, $identifiers ); ?></td>     
	</tr>

 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('ARV regimes');?></td>
	  </tr>
     <tr>	  
		<td align="left" >
		<table>
		<tr>
		<td>
		
		<?php echo $AppUI->_('1st line');?>
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv1]" id="medical_arv1" value="<?php echo $obj->medical_arv1;?>" maxlength="150" size="20"/>
		</td>
		</tr>
		<tr>
		<td>
		<?php echo $AppUI->_('Started');?>:
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv1_startdate]" id="medical_arv1_startdate" value="<?php echo $obj->medical_arv1_startdate;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
		</td>
		</tr>
		<tr>
		<td>
		<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv1_enddate]" id="medical_arv1_enddate" value="<?php echo $obj->medical_arv1_enddate;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
	   </td>
	 </tr>		
	 <tr>
		<td>
		
		<?php echo $AppUI->_('2nd line');?>
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv2]" id="medical_arv2" value="<?php echo $obj->medical_arv2;?>" maxlength="150" size="20"/>
		</td>
		</tr>
		<tr>
		<td>
		<?php echo $AppUI->_('Started');?>:
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv2_startdate]" id="medical_arv2_startdate" value="<?php echo $obj->medical_arv2_startdate;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
		</td>
		</tr>
		<tr>
		<td>
		<?php echo $AppUI->_('Stopped');?>:
		</td>
		<td>
		<input type="text" class="text" name="medical[medical_arv2_enddate]" id="medical_arv2_enddate" value="<?php echo $obj->medical_arv2_enddate;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd
	   </td>
	 </tr>	 
	</table> 	
	</td>
	</tr> 

	 <tr>
		<td align="left"><?php echo $AppUI->_('Side effects');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_arv_side_effects]" id="medical_arv_side_effects" value="<?php echo $obj->medical_arv_side_effects;?>" maxlength="150" size="20"/></td>
     </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Adherence');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_arv_adherence]" id="medical_arv_adherence" value="<?php echo $obj->medical_arv_adherence;?>" maxlength="150" size="20"/></td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Development History and Diet'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Attend School Regularly');?>:</td>
		<td align="left" valign="top">
		&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_school_attendance]", 'onclick=toggleButtons()', $row->medical_school_attendance ? $row->medical_school_attendance : -1, $identifiers ); ?>
		</td>     
	</tr>
	<tr>
	   <td align="left">
			<?php echo $AppUI->_('If Yes, class');?>
		</td>
       <td>		
		<input type="text" class="text" name="medical[medical_school_class]" id="medical_school_class" value="<?php echo $obj->medical_school_class;?>" maxlength="150" size="20"/> 
       </td>
	</tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Progress');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($educProgressType, "medical[medical_educ_progress]", 'onclick=toggleButtons()', $row->medical_educ_progress ? $row->medical_educ_progress : -1, $identifiers ); ?></td>     
	</tr>
	<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Sensory impairment');?>:</td>
		<td align="left">
		<table>
		 <tr>
		  <td>
		<?php echo $AppUI->_('Hearing');?>
		</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "medical[medical_sensory_hearing]", 'onclick=toggleButtons()', $row->medical_sensory_hearing ? $row->medical_sensory_hearing : -1, $identifiers ); ?>
	    </td>
        </tr>
        <tr>		
		  <td>

		<?php echo $AppUI->_('vision');?>:
		</td>
		<td>

		<?php echo arraySelectRadio($boolTypes, "medical[medical_sensory_vision]", 'onclick=toggleButtons()', $row->medical_sensory_vision ? $row->medical_sensory_vision : -1, $identifiers ); ?>
		</td>
        </tr>
        <tr>		

		<td>

		<?php echo $AppUI->_('motor ability');?>:
		</td>
		<td>
		<?php echo arraySelectRadio($motorType, "medical[medical_sensory_motor_ability]", 'onclick=toggleButtons()', $row->medical_sensory_motor_ability ? $row->medical_sensory_motor_ability : -1, $identifiers ); ?>
        </td>
	    </tr>
	    <tr>
		<td>	
		<?php echo $AppUI->_('speech and language');?>
		</td>
		<td>

		<?php echo arraySelectRadio($boolTypes, "medical[medical_sensory_speech_language]", 'onclick=toggleButtons()', $row->medical_sensory_speech_language ? $row->medical_sensory_speech_language : -1, $identifiers ); ?>
		</td>
		</tr>
		<tr>
		<td>

		<?php echo $AppUI->_('social skills');?>:
		</td>
		<td>
		<?php echo arraySelectRadio($boolTypes, "medical[medical_sensory_social_skills]", 'onclick=toggleButtons()', $row->medical_sensory_social_skills ? $row->medical_sensory_social_skills : -1, $identifiers ); ?>
		</td>
	    </tr>
		</table>
		</td>
		</tr>
	    <tr>
		<td align="left"><?php echo $AppUI->_('Number of meals per day');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_meals_per_day]" id="medical_meals_per_day" value="<?php echo $obj->medical_meals_per_day;?>" maxlength="150" size="20"/></td>
        </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Types of food (list)');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="medical[medical_food_types]"><?php echo @$obj->medical_food_types;?></textarea>
		</td>		
     </tr>
	 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Current complaints?');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="medical[medical_current_complaints]"><?php echo @$obj->medical_current_complaints;?></textarea>
		</td>
     </tr>	
	</table>
    </td>
 <td width="50%" cellpadding="5">
   <table border="0" cellpadding="1" cellspacing="1" class="details" width="100%">
	 
	 

	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Examination'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
        <td align="left"><?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left">
            <input type="text" class="text" name="medical[medical_weight]" value="<?php echo dPformSafe(@$obj->medical_weight);?>" maxlength="30" size="20" />
        </td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical[medical_height]" id="medical_height" value="<?php echo $obj->medical_height;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical[medical_zscore]" id="medical_zscore" value="<?php echo $obj->medical_zscore;?>" maxlength="30" size="20"/></td>
      </tr>      <tr>
			<td align="left"><?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical[medical_muac]" id="medical_muac" value="<?php echo $obj->medical_muac;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Head Circum (cm)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical[medical_hc]" id="medical_hc" value="<?php echo $obj->medical_hc;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Looks');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($conditionType, "medical[medical_condition]", 'onclick=toggleButtons()', $row->medical_condition ? $row->medical_condition : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Temperature (Celcius)');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="medical[medical_temp]" id="medical_temp" value="<?php echo $obj->medical_temp;?>" maxlength="30" size="20"/></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Identify');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectCheckbox($examinationType, "medical[medical_conditions]", 'onclick=toggleButtons()', $row->medical_conditions ? $row->medical_conditions : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Dehydration');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($dehydrationType, "medical[medical_dehydration]", 'onclick=toggleButtons()', $row->medical_dehydration ? $row->medical_dehydration : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Parotids');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_parotids]", 'onclick=toggleButtons()', $row->medical_mother_pmtct ? $row->medical_parotids : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Lymph nodes');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($lymphType, "medical[medical_lymph]", 'onclick=toggleButtons()', $row->medical_lymph ? $row->medical_lymph : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Eyes');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "medical[medical_eyes]", 'onclick=toggleButtons()', $row->medical_eyes ? $row->medical_eyes : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Ear discharge');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($earType, "medical[medical_ear_discharge]", 'onclick=toggleButtons()', $row->medical_ear_discharge ? $row->medical_ear_discharge : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Throat');?>:</td>
			<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($throatType, "medical[medical_throat]", 'onclick=toggleButtons()', $row->medical_throat ? $row->medical_throat : -1, $identifiers ); ?></td>     
      </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Mouth');?>:</td>
			<td align="left">
			<table>
			 <tr>
			  <td>
				<?php echo $AppUI->_('thrush');?>:
			  </td>
			  <td>	
				<?php echo arraySelectRadio($boolTypes, "medical[medical_mouth_thrush]", 'onclick=toggleButtons()', $row->medical_mouth_thrush ? $row->medical_mouth_thrush : -1, $identifiers ); ?>
			</td>
				</tr>
				<tr>
				<td>		
			<?php echo $AppUI->_('ulcers');?>:
			</td>
			  <td>	
			<?php echo arraySelectRadio($boolTypes, "medical[medical_mouth_ulcers]", 'onclick=toggleButtons()', $row->medical_mouth_ulcers ? $row->medical_mouth_ulcers : -1, $identifiers ); ?>
			</td>
				</tr>
				<tr>
				<td>
			<?php echo $AppUI->_('teeth');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($teethType, "medical[medical_mouth_teeth]", 'onclick=toggleButtons()', $row->medical_mouth_teeth ? $row->medical_mouth_teeth : -1, $identifiers ); ?>
			</td>     
			 </tr>
		</table>
		</td>
      </tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Skin');?>:</td>
			<td align="left" valign="top">

			<?php echo $AppUI->_('Old lesions');?>:&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_oldlesions]" id="medical_oldlesions" value="<?php echo $obj->medical_oldlesions;?>" maxlength="30" size="20"/>
			<?php echo $AppUI->_('Current lesions');?>:&nbsp;&nbsp;<input type="text" class="text" name="medical[medical_currentlesions]" id="medical_currentlesions" value="<?php echo $obj->medical_currentlesions;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Respiratory and Cardiovascular'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>


			 <tr>
			  <td>
			<?php echo $AppUI->_('heart rate');?>:
			  </td>
			   <td>
			    <input type="text" class="text" name="medical[medical_heartrate]" id="medical_heartrate" value="<?php echo $obj->medical_heartrate;?>" maxlength="30" size="20"/>
			</td>
			      </tr>
	<tr>
			<td>
			<?php echo $AppUI->_('recession');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_recession]", 'onclick=toggleButtons()', $row->medical_recession ? $row->medical_recession : -1, $identifiers ); ?>
			</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('percussion');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($percussionType, "medical[medical_percussion]", 'onclick=toggleButtons()', $row->medical_percussion ? $row->medical_percussion : -1, $identifiers ); ?>
			</td>
      </tr>
	  <tr>		
	 	<td>
			<?php echo $AppUI->_('location');?>:
			</td>
			<td>
			<input type="text" class="text" name="medical[medical_location]" id="medical_location" value="<?php echo $obj->medical_location;?>" maxlength="30" size="20"/>
			</td>
	      </tr>	  
		  <tr>		
	 	 <td>
			<?php echo $AppUI->_('breath sounds');?>:
			</td>
			<td>
			  <?php echo arraySelectRadio($breathType, "medical[medical_breath_sounds]", 'onclick=toggleButtons()', $row->medical_breath_sounds ? $row->medical_breath_sounds : -1, $identifiers ); ?>
			</td>
	      </tr>	  
		  <tr>		

			<td>
			<?php echo $AppUI->_('location');?>:
			</td>
			<td>
			<input type="text" class="text" name="medical[medical_breathlocation]" id="medical_breathlocation" value="<?php echo $obj->medical_breathlocation;?>" maxlength="30" size="20"/>
			</td>
	      </tr>
	  
      </tr>
	  <tr>
			<td align="left">
			<?php echo $AppUI->_('added sounds');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($soundsType, "medical[medical_other_sounds]", 'onclick=toggleButtons()', $row->medical_other_sounds ? $row->medical_other_sounds : -1, $identifiers ); ?>
			</td>
	 </tr>
      </tr>
	  <tr>
			<td>

	  <?php echo $AppUI->_('location');?>:
			</td>
			<td>
	  
	  <input type="text" class="text" name="medical[medical_soundlocation]" id="medical_soundlocation" value="<?php echo $obj->medical_soundlocation;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	  <tr>
			<td align="left">
			<?php echo $AppUI->_('pulse rate');?>:
			</td>
			<td>

			<input type="text" class="text" name="medical[medical_pulserate]" id="medical_pulserate" value="<?php echo $obj->medical_pulserate;?>" maxlength="30" size="20"/>
			</td>
      </tr>
	  <tr>

			<td>

			<?php echo $AppUI->_('apex beat');?>:
			</td>
			<td>

			<?php echo arraySelectRadio($apexType, "medical[medical_apex_beat]", 'onclick=toggleButtons()', $row->medical_apex_beat ? $row->medical_apex_beat : -1, $identifiers ); ?>
			</td>
      </tr>
	  <tr>

			<td>

			<?php echo $AppUI->_('Precordial activity');?>:
			</td>
			<td>

			<?php echo arraySelectRadio($precordialType, "medical[medical_precordial]", 'onclick=toggleButtons()', $row->medical_precordial ? $row->medical_precordial : -1, $identifiers ); ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('femoral pulses');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($femoralType, "medical[medical_femoral]", 'onclick=toggleButtons()', $row->medical_femoral ? $row->medical_femoral : -1, $identifiers ); ?>
						</td>
				      </tr>	  
		  <tr>		
			
			<td>
			<?php echo $AppUI->_('heart');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($heartSoundType, "medical[medical_heart_sound]", 'onclick=toggleButtons()', $row->medical_heart_sound ? $row->medical_heart_sound : -1, $identifiers ); ?>
						</td>
	      </tr>	  
		  <tr>		

						<td>
			<?php echo $AppUI->_('type');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_heart_type]" id="medical_heart_type" value="<?php echo $obj->medical_heart_type;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Abdomen'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('distended');?>:
			</td>
			<td>			
			<?php echo arraySelectRadio($boolTypes, "medical[medical_abdomen_distended]", 'onclick=toggleButtons()', $row->medical_abdomen_distended ? $row->medical_abdomen_distended : -1, $identifiers ); ?>
				</td>
      </tr>
	<tr>
				<td>		
			<?php echo $AppUI->_('feel');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_adbomen_feel]", 'onclick=toggleButtons()', $row->medical_adbomen_feel ? $row->medical_adbomen_feel : -1, $identifiers ); ?>
						</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('tender');?>:
		</td>
		<td>		
		<?php echo arraySelectRadio($boolTypes, "medical[medical_abdomen_tender]", 'onclick=toggleButtons()', $row->medical_abdomen_tender ? $row->medical_abdomen_tender : -1, $identifiers ); ?>
						</td>
	      </tr>
	<tr>		
			<td>
			<?php echo $AppUI->_('fluid');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_abdomen_fluid]", 'onclick=toggleButtons()', $row->medical_abdomen_fluid ? $row->medical_abdomen_fluid : -1, $identifiers ); ?>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Liver (cm below costal margin)');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_liver_costal]" id="medical_liver_costal" value="<?php echo $obj->medical_liver_costal;?>" maxlength="30" size="20"/>
						</td>
      </tr>
	<tr>
						<td>
			<?php echo $AppUI->_('Spleen (cm below costal margin)');?>:
						</td>
		
		<td>
			<input type="text" class="text" name="medical[medical_spleen_costal]" id="medical_spleen_costal" value="<?php echo $obj->medical_spleen_costal;?>" maxlength="30" size="20"/>
			</td>     
      </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Masses (specify)');?>:
			</td>
			<td>		  
		  <input type="text" class="text" name="medical[medical_masses]" id="medical_masses" value="<?php echo $obj->medical_masses;?>" maxlength="30" size="20"/>
						</td>
      </tr>
	<tr>
						<td>
		
		<?php echo $AppUI->_('Umbilical hernia');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_umbilical_hernia]", 'onclick=toggleButtons()', $row->medical_umbilical_hernia ? $row->medical_umbilical_hernia : -1, $identifiers ); ?>
			</td>     
      </tr>


	  <tr>
	  		<td align="left"  valign="top"><?php echo $AppUI->_('Genitalia');?>:</td>
			<td align="left">
			<table>
			 <tr>
			  <td>
				<?php echo $AppUI->_('Male testes ');?>:
			</td>
		
				<td>
					<?php echo arraySelectRadio($palpableType, "medical[medical_testes]", 'onclick=toggleButtons()', $row->medical_testes ? $row->medical_testes : -1, $identifiers ); ?>
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo $AppUI->_('feel');?>:
				</td>
	
				<td>
					<?php echo arraySelectRadio($boolTypes, "medical[medical_genitals_feel]", 'onclick=toggleButtons()', $row->medical_genitals_feel ? $row->medical_genitals_feel : -1, $identifiers ); ?>
				</td>
			</tr>
			<tr>
				<td>
				<?php echo $AppUI->_('penis');?>:
				</td>
				<td>
					<input type="text" class="text" name="medical[medical_penis]" id="medical_penis" value="<?php echo $obj->medical_penis;?>" maxlength="30" size="20"/>
						</td>
				</tr>
			<tr>		
				<td>
					<?php echo $AppUI->_('OR Female');?>:
				</td>
				<td>
				<?php echo arraySelectRadio($boolTypes, "medical[medical_genitals_female]", 'onclick=toggleButtons()', $row->medical_genitals_female ? $row->medical_genitals_female : -1, $identifiers ); ?>
				</td>     
			</tr>
			</table>
		  </td>
        </tr>		  
      <tr>
			<td align="left"><?php echo $AppUI->_('Pubertal development');?>:</td>
			
			<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "medical[medical_pubertal]", 'onclick=toggleButtons()', $row->medical_pubertal ? $row->medical_pubertal : -1, $identifiers ); ?></td>     
      </tr>
	  	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Central Nervous System and Musculoskeletal'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Gait');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_gait]" id="medical_gait" value="<?php echo $obj->medical_gait;?>" maxlength="30" size="20"/>
						</td>
      </tr>
	<tr>		
		<td>
			<?php echo $AppUI->_('Hand use');?>:
						</td>
		
		<td>
			<input type="text" class="text" name="medical[medical_handuse]" id="medical_handuse" value="<?php echo $obj->medical_handuse;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Weakness');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_weakness]" id="medical_weakness" value="<?php echo $obj->medical_weakness;?>" maxlength="30" size="20"/>
						</td>
      </tr>
	<tr>
						<td>
			<?php echo $AppUI->_('Tone');?>:
					</td>
			<td>	
			<?php echo arraySelectRadio($toneType, "medical[medical_tone]", 'onclick=toggleButtons()', $row->medical_tone ? $row->medical_tone : -1, $identifiers ); ?>
			</td>     
      </tr>	  
      <tr>
	  		<td align="left" valign="top"><?php echo $AppUI->_('Tendon reflexes');?>:</td>
					
			<td align="left" >
			<table>
			<tr>
			<td>
			<?php echo $AppUI->_('legs');?>:
			</td>
  
			<td>
				<?php echo arraySelectRadio($tendonLegsType, "medical[medical_tendon_legs]", 'onclick=toggleButtons()', $row->medical_tendon_legs ? $row->medical_tendon_legs : -1, $identifiers ); ?>
			</td>
			</tr>
			<tr>
			<td>
			<?php echo $AppUI->_('arms');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($tendonArmsType, "medical[medical_tendon_arms]", 'onclick=toggleButtons()', $row->medical_tendon_arms ? $row->medical_tendon_arms : -1, $identifiers ); ?>
			</td>
			</tr>
			</table> 
		 </td>				
      </tr>      
	  <tr>
			<td align="left" valign="top">
			
			<?php echo $AppUI->_('Abnormal movements');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_abnormal_movts]" id="medical_abnormal_movts" value="<?php echo $obj->medical_abnormal_movts;?>" maxlength="30" size="20"/>
						</td>

	   </tr>
	   
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Joints range of movement impaired');?>:
			</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_movts_impaired]", 'onclick=toggleButtons()', $row->medical_movts_impaired ? $row->medical_movts_impaired : -1, $identifiers ); ?>
					</td>
    </tr>
	<tr>			
	<td>	
			<?php echo $AppUI->_('specify');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_movts_impaired_desc]" id="medical_movts_impaired_desc" value="<?php echo $obj->medical_movts_impaired_desc;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	  
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Joints swelling');?>:
						</td>
			<td>
			<?php echo arraySelectRadio($boolTypes, "medical[medical_joints_swelling]", 'onclick=toggleButtons()', $row->medical_joints_swelling ? $row->medical_joints_swelling : -1, $identifiers ); ?>
						</td>
		    </tr>
	<tr>			
			<td>
			<?php echo $AppUI->_('specify');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_joints_swelling_desc]" id="medical_joints_swelling_desc" value="<?php echo $obj->medical_joints_swelling_desc;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	  
	  
	  <tr>
			<td align="left"><?php echo $AppUI->_('Motor');?>:</td>
			<td align="left" valign="top">
			<?php echo arraySelectRadio($motorType, "medical[medical_motor]", 'onclick=toggleButtons()', $row->medical_motor ? $row->medical_motor : -1, $identifiers ); ?>
			</td>     
      </tr>

	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Summary');?>:</td>
		<td valign="top">
		<textarea cols="30" rows="5" class="textarea" name="medical[medical_musc_notes]"><?php echo @$obj->medical_musc_notes;?></textarea>
		</td>
     </tr>
	<tr>
			<td colspan="2" align="left">
				<?php echo $AppUI->_('Management Plan'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 
	<tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('HIV status');?>:
						</td>
			<td>

			<?php echo arraySelectRadio($hivStatus, "medical[medical_hiv_status]", 'onclick=toggleButtons()', $row->medical_hiv_status ? $row->medical_hiv_status : -1, $identifiers ); ?>
						</td>
    </tr>
	<tr>		
						<td>
			<?php echo $AppUI->_('CD4');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_cd4]" id="medical_cd4" value="<?php echo $obj->medical_cd4;?>" maxlength="30" size="20"/>
						</td>
	    </tr>
	<tr>				
			<td>
			<?php echo $AppUI->_('CD4%');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_cd4_percentage]" id="medical_cd4_percentage" value="<?php echo $obj->medical_cd4_percentage;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	 
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Clinical stage (WHO)');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_who_clinical_stage]" id="medical_who_clinical_stage" value="<?php echo $obj->medical_who_clinical_stage;?>" maxlength="30" size="20"/>
						</td>
    </tr>
	<tr>				
		<td>
			
			<?php echo $AppUI->_('Immunological stage');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_immuno_stage]" id="medical_immuno_stage" value="<?php echo $obj->medical_immuno_stage;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	 
	  <tr>
			<td align="left" valign="top">
			<?php echo $AppUI->_('Tests');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_tests]" id="medical_tests" value="<?php echo $obj->medical_tests;?>" maxlength="30" size="20"/>
						</td>
			    </tr>
	       <tr>		
			<td>
			
			<?php echo $AppUI->_('Referral to');?>:
						</td>
			<td>
			<input type="text" class="text" name="medical[medical_referral]" id="medical_referral" value="<?php echo $obj->medical_referral;?>" maxlength="30" size="20"/>
			</td>     
      </tr>	 
	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Treatment');?>:</td>
		<td valign="top">
		<textarea cols="30" rows="5" class="textarea" name="medical[medical_treat_notes]"><?php echo @$obj->medical_treat_notes;?></textarea>
		</td>
     </tr>
	</table>
 </td>
</tr>
	 <tr>
		<td align="left" colspan="2"><?php echo $AppUI->_('Prepared by');?>:</td>	 
	    <td align="left">
          <input type="text" class="text" name="medical_clinician" value="<?php echo dPformSafe(@$clientObj->client_code);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>

	</tr> 

</form>
</table>
<script language="javascript">
 subForm.push(new FormDefinition(<?php echo $tab;?>, document.medicalFrm, checkDetail, saveMedicalInfo));
</script>