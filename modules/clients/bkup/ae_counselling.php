<?php
global $AppUI,$dPconfig,$loadFromTab, $tab;
global $obj, $client_id, $url,$can_edit_contact_information;
global $convert;

//require_once ($AppUI->getModuleClass('counselling'));


$perms = & $AppUI->acl();

$canEdit = true;
$msg = '';
//$row = new CClientCounsellingInfo();

$boolTypes = dPgetSysVal('YesNo');
$birthPlaces = dPgetSysVal('BirthPlaceType');
$birthTypes = dPgetSysVal('BirthType');
$ageTypes = dPgetSysVal('AgeType');
$awareStages = dPgetSysVal('StatusAwareType');

/*
if (isset($client_id) && $client_id > 0)
{
	$sql = 'SELECT * FROM client_counselling_info WHERE counselling_client_id = ' . $client_id;

	//$counselling_info_id  = db_exec($sql);
	//$row = new CClientCounsellingInfo();

	if (!db_loadObject( $sql, $row) && !isset($convert))
	{
		$AppUI->setMsg('Counselling Info');
		$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
		$AppUI->redirect();
	}
}
//load building solution name
//var_dump($row->counselling_building_solution_id);
//var_dump($building_solution_id);

if (($building_solution_id > 0 ))
{
	$q = new DBQuery;
	$q->addTable ('building_solution');
	$q->addQuery('building_solution_location');
	$q->addWhere("building_solution_id = $building_solution_id");
	$bs_name = $q->loadResult();
}

//load types of networks 
$q  = new DBQuery;
$q->addTable('customer_ntwk_types');
$q->addQuery('typ_id');
$q->addQuery('typ_desc');
$q->addOrder('typ_desc');
$networkTypes = arrayMerge(array(0=>'Select network type'),$q->loadHashList());

//load vpn types

$vpnTypes = dPgetSysVal('VPNTypes');

//load network services
$q->clear();
$q->addTable('ntwk_service_types');
$q->addQuery('ntwk_service_type_id');
$q->addQuery('ntwk_service_type_desc');
$serviceTypes = $q->loadHashList();
$identifiers = array(0=>'', 1=>'on_bs', 2=>'not_on_bs');*/

$date_reg = date("Y-m-d");
$entry_date = intval( $date_reg) ? new CDate( dPgetParam($_REQUEST, "counselling_entry_date", date("Y-m-d") ) ) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

?>
<script language="javascript">
var selected_fw_contacts_id = "<?php echo $row->counselling_firewall_contact; ?>";
var selected_vpn_contacts_id = "<?php echo $row->counselling_vpn_contact; ?>";
var client_id = "<?php echo $row->counselling_client_id;?>";

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
	
	counselling_firewall_contact = document.getElementById('counselling_firewall_contact');
	counselling_firewall_contact.value = contact_id_string;
	
	selected_fw_contacts_id = contact_id_string;
}

function setVPNContacts(contact_id_string)
{

	if(!contact_id_string)
	{
		contact_id_string = "";
	}
	
	counselling_vpn_contact = document.getElementById('counselling_vpn_contact');
	counselling_vpn_contact.value = contact_id_string;
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
  <input type="hidden" name="counselling_unique_update" value="<?php echo uniqid("");?>" />
  <input type="hidden" name="counselling[counselling_info_id]" value="<?php echo $row->counsel_info_id;?>" />
  <input type="hidden" name="counselling[counselling_client_id]" value="<?php echo $client_id;?>" />
  <input type="hidden" name="counselling[building_solution_id]" id="building_solution_id" value="<?php echo @$building_solution_id;?>" />

<tr>
    <td width="75%" valign="top">
      <table border="0" cellpadding = "1" cellspacing="1">
	   <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Registration Date');?>: </td>
			<td align="left">
				<input type="hidden" name="log_entry_date" value="<?php echo $entry_date ? $entry_date->format( FMT_TIMESTAMP_DATE ) : "" ;?>" />
				<input type="text" name="counselling_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text" readonly disabled="disabled" />
			</td>
		   </tr>
     <tr>
		<td align="left"><?php echo $AppUI->_('Client ID');?>:</td>
		<td align="left">
		<input type="text" class="text" name="client_code" id="client_code" value="<?php echo $client_code;?>" maxlength="150" size="20" disabled="disabled" />
	    </td>
	
      </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Referral Source');?>:</td>
         <td align="left">
          <input type="text" class="text" name="counselling[counselling_referral_source]" value="<?php echo @$row->counselling_referral_source;?>" maxlength="150" size="20" />
         </td>
       </tr>
	   <tr>
		<td align="left"><?php echo $AppUI->_('Total Orphan?');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_total_orphan]", 'onclick=toggleButtons()', $row->counselling_total_orphan ? $row->counselling_total_orphan : -1, $identifiers ); ?></td>
     </tr>	  

     <tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_dob]" id="counselling_dob" value="<?php echo $obj->counselling_dob;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
			
	  </tr> 
<tr>
         <td valign="top"><?php echo $AppUI->_('Age');?>:</td>
		 <td>
		 <table>
		  <tr>
		   <td>
	       <input type="text" class="text" name="counselling[counselling_age_yrs]" value="<?php echo dPformSafe(@$obj->counselling_age_yrs);?>" maxlength="30" size="20" />
		    <?php echo $AppUI->_('years');?>:
		    </td>
          </tr>
		  <tr>
		   <td>
	         <input type="text" class="text" name="counselling[counselling_age_months]" value="<?php echo dPformSafe(@$obj->counselling_age_months);?>" maxlength="30" size="20" />
		      <?php echo $AppUI->_('months');?>:
		   </td>
		   </tr>
		<tr>
		<td><?php echo arraySelectRadio($ageTypes, "counselling[counselling_age_status]", 'onclick=toggleButtons()', $row->counselling_age_status ? $row->counselling_age_status : -1, $identifiers ); ?></td>		
		</tr>
		</table>
	 </tr>
    	
	<tr>
         <td align="left"><?php echo $AppUI->_('Place of Birth');?>:</td>
		 <td align="left">
		 <?php echo arraySelectRadio($birthPlaces, "counselling[counselling_place_of_birth]", 'onclick=toggleButtons()', $row->counselling_place_of_birth ? $row->counselling_place_of_birth : -1, $identifiers ); ?></td>

		 </td>
      </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Area');?>:</td>
		 <td>
	    <input type="text" class="text" name="counselling[counselling_birth_area]" value="<?php echo @$row->counselling_birth_area;?>" maxlength="150" size="20" />
		 </td>
		 </tr>  
	 <tr>
		<td align="left"><?php echo $AppUI->_('Mode of birth');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($birthTypes, "counselling[counselling_mode_birth]", 'onclick=toggleButtons()', $row->counselling_mode_birth ? $row->counselling_mode_birth : -1, $identifiers ); ?></td>		
		
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Gestation period (months)');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_gestation_period]" id="counselling_gestation_period" value="<?php echo $obj->counselling_gestation_period;?>" maxlength="150" size="20"/></td>
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_birth_weight]" id="counselling_birth_weight" value="<?php echo $obj->counselling_birth_weight;?>" maxlength="150" size="20"/></td>
     </tr>
	  
	  


      <tr>
			<td align="left"><?php echo $AppUI->_('Mother aware of status');?>:</td>
			<td align="left" nowrap>&nbsp;&nbsp;<?php echo arraySelectRadio($awareStages, "counselling[counselling_mothers_status_known]", 'onclick=toggleButtons()', $row->counselling_mothers_status_known ? $row->counselling_mothers_status_known : -1, $identifiers ); ?>
			</td>	
      </tr>
	  <tr>
		<td align="left" nowrap><?php echo $AppUI->_('Did mother receive any antenatal care?');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_antenatal]", 'onclick=toggleButtons()', $row->counselling_mother_antenatal ? $row->counselling_mother_antenatal : -1, $identifiers ); ?></td>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother enrolled in a PMTCT program?');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_pmtct]", 'onclick=toggleButtons()', $row->counselling_mother_pmtct ? $row->counselling_mother_pmtct : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Illness/STI at pregnancy?');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_illness_pregnancy]", 'onclick=toggleButtons()', $row->counselling_mother_illness_pregnancy ? $row->counselling_mother_illness_pregnancy : -1, $identifiers ); ?></td>
	 	
     </tr>
	 <tr>
	 <td align="left" valign="top"><?php echo $AppUI->_('If Y please describe');?>:</td>
		<td align="right" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="counselling[counselling_mother_illness_pregnancy_notes]"><?php echo @$obj->counselling_mother_illness_pregnancy_notes;?></textarea>
		</td>
	 </tr> 
 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Exclusive breastfeeding? ');?></td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "counselling[counselling_breastfeeding]", 'onclick=toggleButtons()', $row->counselling_breastfeeding ? $row->counselling_breastfeeding : -1, $identifiers ); ?></td>
	</tr>
    <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('If Y, duration (months) ');?></td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_breastfeeding_duration]" id="counselling_breastfeeding_duration" value="<?php echo $obj->counselling_breastfeeding_duration;?>" maxlength="150" size="20"/></td>
	</tr>
    <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('Duration other breastfeeding (months) ');?></td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_other_breastfeeding_duration]" id="counselling_other_breastfeeding_duration" value="<?php echo $obj->counselling_other_breastfeeding_duration;?>" maxlength="150" size="20"/>
		<br/><?php echo $AppUI->_('(Note: consider carefully advice on feeding as replacement feeding is often not safe)'); ?>
		</td>
	  </tr>
	  
	</table>
    </td>
 <td width="50%" cellpadding="5">
   <table border="0" cellpadding="1" cellspacing="1" class="details" width="100%">
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Child perinatal ARV exposure?');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_perinatal]", 'onclick=toggleButtons()', $row->counselling_child_perinatal ? $row->counselling_child_perinatal : -1 ); ?>
        </td>
	</tr>
	<tr>
	
		<td align="left" valign="top"><?php echo $AppUI->_('If Y single dose NVP?') ?> </td>
		<td valign="top">
		<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_single_nvp]", 'onclick=toggleButtons()', $row->counselling_child_single_nvp? $row->counselling_child_single_nvp : -1 ); ?>
		</td>		
     </tr>	
	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Date given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_child_nvp_date]" id="counselling_child_nvp_date" value="<?php echo $obj->counselling_child_nvp_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>

		
     </tr>
	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Was AZT given (normally twice daily for one week after birth)?');?>:</td>
		
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_azt]", 'onclick=toggleButtons()', $row->counselling_child_azt? $row->counselling_child_azt : -1); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Date AZT given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_child_azt_date]" id="counselling_child_azt_date" value="<?php echo $obj->counselling_child_azt_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
	</tr>
    <tr>	
		<td nowrap><?php echo $AppUI->_('Number of doses') ?> </td>
		<td align="left"><input type="text" class="text" name="counselling[counselling_no_doses]" id="counselling_no_doses" value="<?php echo $obj->counselling_no_doses;?>" maxlength="150" size="20"/></td>		
     </tr>
 
	 
	 <tr>
		<td align="left" ><?php echo $AppUI->_('Mother in treatment program?');?>:</td>
		<td align="left" >&nbsp;&nbsp;
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_treatment]", 'onclick=toggleButtons()', $row->counselling_mother_treatment ? $row->counselling_mother_treatment : -1 ); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother on ART in pregnancy');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_art_pregnancy]", 'onclick=toggleButtons()', $row->counselling_mother_art_pregnancy ? $row->counselling_mother_art_pregnancy : -1 ); ?>
        </td>
     </tr>	 
	 <tr>
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Date began ART');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_date_art]" id="counselling_mother_date_art" value="<?php echo $obj->counselling_mother_date_art;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
	 </tr>
     <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Most recent maternal CD4 count');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_cd4]" id="counselling_mother_cd4" value="<?php echo $obj->counselling_mother_cd4;?>" maxlength="150" size="20"/></td>

	 </tr>	
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Date of CD4 test');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_date_cd4]" id="counselling_mother_date_cd4" value="<?php echo $obj->counselling_mother_date_cd4;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
	 </tr>
	<tr>
			<td colspan="2" align="left" nowrap>
				<?php echo $AppUI->_('PCR Tests'); ?><br />
				<hr width="500" align="left" size=1 />
			</td>
	 </tr> 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Test');?>:</td>
		<td align="left"><?php echo $AppUI->_('Date');?>:</td>
		<td align="left"><?php echo $AppUI->_('Result');?>:</td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Determine');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_determine_date]" id="counselling_determine_date" value="<?php echo $obj->counselling_determine_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_determine]" id="counselling_determine" value="<?php echo $obj->counselling_determine;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Bio-line');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_bioline_date]" id="counselling_bioline_date" value="<?php echo $obj->counselling_bioline_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_bioline]" id="counselling_bioline" value="<?php echo $obj->counselling_bioline;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Uni-gold');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_unigold_date]" id="counselling_unigold_date" value="<?php echo $obj->counselling_unigold_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_unigold]" id="counselling_unigold" value="<?php echo $obj->counselling_unigold;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('ELISA');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_elisa_date]" id="counselling_elisa_date" value="<?php echo $obj->counselling_elisa_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_elisa]" id="counselling_elisa" value="<?php echo $obj->counselling_elisa;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('PCR1');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_pcr1_date]" id="counselling_pcr1_date" value="<?php echo $obj->counselling_pcr1_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_pcr1]" id="counselling_pcr1" value="<?php echo $obj->counselling_pcr1;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('PCR2');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_pcr2_date]" id="counselling_pcr2_date" value="<?php echo $obj->counselling_pcr2_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_pcr2]" id="counselling_pcr2" value="<?php echo $obj->counselling_pcr2;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Rapid @ 12 months');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_rapid12_date]" id="counselling_rapid12_date" value="<?php echo $obj->counselling_rapid12_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_rapid12]" id="counselling_rapid12" value="<?php echo $obj->counselling_rapid12;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Rapid @ 18 months');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_rapid18_date]" id="counselling_rapid18_date" value="<?php echo $obj->counselling_rapid18_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_rapid18]" id="counselling_rapid18" value="<?php echo $obj->counselling_rapid18;?>" maxlength="150" size="20"/></td>
	 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Other');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_other_date]" id="counselling_other_date" value="<?php echo $obj->counselling_other_date;?>" maxlength="150" size="20"/>&nbsp;yyyy-mm-dd</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_other]" id="counselling_other" value="<?php echo $obj->counselling_other;?>" maxlength="150" size="20"/></td>
	 </tr>
<tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('History');?>:</td>
		<td valign="top">
		<textarea cols="30" rows="5" class="textarea" name="counselling[counselling_history]"><?php echo @$obj->counselling_history;?></textarea>
		</td>
     </tr>	
	</table>
 </td>
</tr>
</form>
</table>
<script language="javascript">
 subForm.push(new FormDefinition(<?php echo $tab;?>, document.counsellingInfoFrm, checkDetail, saveCounsellingInfo));
</script>