<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('counsellinginfo');
require_once $AppUI->getModuleClass('social');

$df = $AppUI->getPref('SHDATEFORMAT');
$q = new DBQuery;
$q->addTable('counselling_info');
$q->addQuery ('counselling_info.*');
$q->addWhere('counselling_info.counselling_client_id = '.$client_id);
$s='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="Add intake & pcr record...";
	$url = "./index.php?m=clients&a=add&client_id=$client_id";
	$s = '<tr><td colspan="8" align="left" valign="top">';
	$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$s .= '</td></tr>';
	echo $s;
}
else
{
	$title="Edit intake & pcr record...";

?>
<table width="100%">


<?php

$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$birthPlaces = dPgetSysVal('BirthPlaceType');
$birthTypes = dPgetSysVal('BirthType');
$ageTypes = dPgetSysVal('AgeType');
$awareStages = dPgetSysVal('StatusAwareType');
$rapidResultsType = dPgetSysVal('RapidResultsType');
$elisaResultsType = dPgetSysVal('ElisaResultsType');
$pcrResultsType = dPgetSysVal('PCRResultsType');
$genderTypes = dPgetSysVal('GenderType');
$maritalTypes = dPgetSysVal('MaritalStatusIntake');
$seenTypes = dPgetSysVal('ClientSeen');
$cd4count= dPgetSysVal('CD4Count');
$posRef = dPgetSysVal('PositiveReferral');
$refsrc = dPgetSysVal('IntakeReferralSource');

$ageTypes = dPgetSysVal('AgeType');

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


$q = new DBQuery();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();

foreach ($rows as $row)
{
		$url = "./index.php?m=clients&a=add&client_id=$client_id&counselling_id=".$row["counselling_id"];
		$obj = new CCounsellingInfo();
		$obj->load($row["counselling_id"]);
		$entry_date = intval( $obj->counselling_admission_date ) ? new CDate( $obj->counselling_admission_date ) : NULL;
		$visit_date = ($entry_date != NULL) ? $entry_date->format($df) : "";

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

		$s = '<tr><td colspan="8" align="left" valign="top">';
		//$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
		$s .= '<a href="'.$url . '">'.$AppUI->_( $title ).'</a>';
		$s .= '</td></tr>';
		echo $s;
?>

<tr>
	<td valign="top" width="50%">
		<table cellspacing="1" cellpadding="4" width="95%" class="std">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	<tr>
		<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
		<td align="left" class="hilite"><?php echo $clinics[$obj->counselling_clinic]; ?></td>
     </tr>
	   <tr>
			<td nowrap="nowrap">1b.<?php echo $AppUI->_('Date of Admission');?>: </td>
			<td class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
		   </tr>
	   
	 <tr>
		<td align="left">1c.<?php echo $AppUI->_('Counsellor');?>:</td>
		<td align="left" class="hilite"><?php echo $owners[$obj->counselling_staff_id]; ?></td>
     </tr>
     <tr>
		<td align="left">2a.<?php echo $AppUI->_('VCT Camp');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->counselling_vct_camp]; ?></td>
     </tr>
     <tr>
		<td align="left">2b.<?php echo $AppUI->_('VCT Camp site');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_vct_camp_site; ?></td>
     </tr>
     <tr>
		<td align="left">3a.<?php echo $AppUI->_('Return visit');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->counselling_return]; ?></td>
     </tr>
     <tr>
         <td nowrap="nowrap">3b.<?php echo $AppUI->_('Client Code');?>:</td>
         <td class="hilite">
          <?php echo @$obj->counselling_client_code;?>
         </td>
       </tr>
       <tr>
         <td nowrap="nowrap">3c.<?php echo $AppUI->_('Partner Code');?>:</td>
         <td class="hilite">
          <?php echo @$obj->counselling_partner_code;?>
         </td>
       </tr>
       <tr>
         <td nowrap="nowrap">5a.<?php echo $AppUI->_('Referral Source');?>:</td>
         <td class="hilite">
          <?php echo @$refsrc[$obj->counselling_referral_source];?>
         </td>
       </tr>
       <tr>
         <td nowrap="nowrap">5a...<?php echo $AppUI->_('Other');?>:</td>
         <td class="hilite">
          <?php echo @$obj->counselling_referral_source_notes;?>
         </td>
       </tr>
       <tr>
         <td nowrap="nowrap">5b.<?php echo $AppUI->_('Area of residence');?>:</td>
         <td class="hilite">
          <?php echo @$obj->counselling_area;?>
         </td>
       </tr>
	 <tr>
		<td align="left">6a.<?php echo $AppUI->_('Date of birth');?>:</td>
	    <td class="hilite">
	         <?php echo ($dob != NULL) ? $dob->format($df) : "";?></td>
	</tr>
	        
	 <tr>
		<td align="left">6b.<?php echo $AppUI->_('Exactness');?>:</td>
	    <td class="hilite">
	         <?php echo $ageTypes[$obj->counselling_age_status];?>
		</td>
	 </tr>
	 
	 <tr>
		<td align="left">6c.<?php echo $AppUI->_('Gender');?>:</td>
		<td align="left" class="hilite"><?php echo $genderTypes[$obj->counselling_gender]; ?></td>
     </tr>
     <tr>
		<td align="left">7.<?php echo $AppUI->_('Marital Status');?>:</td>
		<td align="left" class="hilite"><?php echo $maritalTypes[$obj->counselling_marital]; ?></td>
     </tr>
     <tr>
		<td align="left">8.<?php echo $AppUI->_('Client Seen');?>:</td>
		<td align="left" class="hilite"><?php echo $seenTypes[$obj->counselling_client_seen]; ?></td>
     </tr>

	<!-- <tr>
         <td align="left"><?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->counselling_age_yrs);?>&nbsp;

		 </td>
    </tr>
    <tr>
		<td><?php echo $AppUI->_('Age (months)');?>:</td>
		<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->counselling_age_months);?>&nbsp;
    	 </td>
	</tr> -->
    <tr>
         <td align="left">20b.<?php echo $AppUI->_('Place of Birth');?>:</td>
		 <td align="left" class="hilite"><?php echo $birthPlaces[$obj->counselling_place_of_birth]; ?></td>
	</tr>
    <tr>
		<td align="left">20c.<?php echo $AppUI->_('Area of birth');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->counselling_birth_area;?>
		 </td>
      </tr>
	 <tr>
		<td align="left">21a.<?php echo $AppUI->_('Mode of birth');?>:</td>
		<td align="left" class="hilite"><?php echo $birthTypes[$obj->counselling_mode_birth]; ?></td>

     </tr>
	 <tr>
		<td align="left">21b.<?php echo $AppUI->_('Gestation period (months)');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_gestation_period;?></td>
     </tr>
	 <tr>
		<td align="left">21c.<?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_birth_weight;?>&nbsp;kg</td>
     </tr>




      <tr>
			<td align="left">22.<?php echo $AppUI->_('Mother aware of status');?>:</td>
			<td align="left" class="hilite"><?php echo $awareStages[$obj->counselling_mothers_status_known]; ?>
			</td>
      </tr>
      
	 <tr>
		<td align="left">23a.<?php echo $AppUI->_('Mother any antenatal care?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypesND[$obj->counselling_mother_antenatal]; ?> </td>
	</tr>
	<tr>
		<td align="left">23b.<?php echo $AppUI->_('If Yes, Where?');?></td>
		<td align="left" class="hilite">
			<?php  echo $obj->counselling_mother_antenatal_where;?>		
		</td>
     </tr>
     
	 <tr>
		<td align="left" valign="top">24a.<?php echo $AppUI->_('Mother enrolled in a PMTCT program?');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypesND[$obj->counselling_mother_pmtct];?> </td>
	</td>
	<tr>
		<td align="left">24b.<?php echo $AppUI->_('If Yes, Where?');?></td>
		<td align="left" class="hilite">
			<?php echo $obj->counselling_mother_pmtct_where; ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top">25a.<?php echo $AppUI->_('Illness/STI at pregnancy?');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypesND[$obj->counselling_mother_illness_pregnancy]; ?></td>
	 </tr>
     <tr>
		<td align="left" valign="top">25b...<?php echo $AppUI->_('If Y please describe');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->counselling_mother_illness_pregnancy_notes), 75,"<br />", true);?>
		</td>
     </tr>

 	 <tr>
		<td align="left" valign="top">26a.<?php echo $AppUI->_('Exclusive breastfeeding? ');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypesND[$obj->counselling_breastfeeding]; ?></td>
	 </tr>
     <tr>
		<td align="left" valign="top">26b...<?php echo $AppUI->_('If Y, duration (months) ');?></td>
		<td align="left" class="hilite"><?php echo $obj->counselling_breastfeeding_duration;?>
		</td>
	 </tr>
     <tr>
		<td align="left" valign="top">26c.<?php echo $AppUI->_('Duration other breastfeeding method (mon) ');?></td>
		<td align="left" class="hilite"><?php echo $obj->counselling_other_breastfeeding_duration;?>
		</td>
	  </tr>
 <tr>
		<td align="left" valign="top">27a.<?php echo $AppUI->_('Child prenatal ARV exposure?');?></td>
		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypesND[$obj->counselling_child_prenatal]; ?>
        </td>
	</tr>
	<tr>

		<td align="left" valign="top">27b...<?php echo $AppUI->_('If Y single dose NVP?') ?> </td>
		<td valign="top" class="hilite">
		<?php echo $boolTypesND[$obj->counselling_child_single_nvp]; ?>
		</td>
     </tr>
	 <tr>

	 	<td align="left" valign="top">27c...<?php echo $AppUI->_('When given');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $child_nvp_date ? $child_nvp_date->format( $df ) : "" ;?>
		</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">27d.<?php echo $AppUI->_('Was AZT given?');?></td>

		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypesND[$obj->counselling_child_azt]; ?>
        </td>
	 </tr>
	 <tr>
		<td align="left" valign="top">27e...<?php echo $AppUI->_('Date AZT given');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $child_azt_date ? $child_azt_date->format( $df ) : "" ;?>
		</td>
	 </tr>
     <tr>
		<td nowrap="nowrap">27f...<?php echo $AppUI->_('number of doses') ?> </td>
		<td align="left" class="hilite"><?php echo $obj->counselling_no_doses;?></td>
     </tr>


	 <tr>
		<td align="left" >28a.<?php echo $AppUI->_('Mother in medical care program?');?></td>
		<td align="left"class="hilite" >
			<?php echo $boolTypesND[$obj->counselling_mother_treatment]; ?>
        </td>
	 </tr>
	 <tr>
		<td align="left" valign="top" nowrap>28b...<?php echo $AppUI->_('If yes, where');?>:</td>
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->counselling_mother_treatment_where;?>
		</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">29a.<?php echo $AppUI->_('Mother on ART in pregnancy');?>:</td>
		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypesND[$obj->counselling_mother_art_pregnancy]; ?>
        </td>
     </tr>
	 <tr>
		<td align="left" valign="top" nowrap>29b...<?php echo $AppUI->_('Date began ART');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $mother_date_art ? $mother_date_art->format( $df ) : "" ;?>

		</td>
	 </tr>
     <tr>
		<td align="left" valign="top">30a.<?php echo $AppUI->_('Most recent maternal CD4 count');?>:</td>
		<td align="left" class="hilite"><?php echo $cd4count[$obj->counselling_mother_cd4_note].'&nbsp;'. $obj->counselling_mother_cd4;?></td>

	 </tr>
	 <tr>
		<td align="left" valign="top">30b...<?php echo $AppUI->_('Date of CD4 test');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $mother_date_cd4 ? $mother_date_cd4->format( $df ) : "" ;?>
		</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">31...<?php echo $AppUI->_('Remarks');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->counselling_notes), 65,"<br />", true);?>
		</td>
	 </tr>


	</table>

    </td>


	<td width="50%" valign="top">
	<!--  <a href="?m=counsellinginfo&u=pcr&a=addedit&counselling_id=<?php echo $obj->counselling_id;?>"><?php echo $AppUI->_("Edit pcr record...");?></a> -->
		<table cellspacing="1" cellpadding="2"  class="std" width="100%">
		 <tr>
		    <th><?php echo $AppUI->_('Test');?></th>
		    <th><?php echo $AppUI->_('Date PCR');?></th>
		    <th><?php echo $AppUI->_('Result');?></th>
		 </tr>
		 <tr>
		    <td class="hilite">9a.&nbsp; <?php echo $AppUI->_('Determine');?> </td>
		    <td class="hilite">9b. <?php echo $determine_date ? $determine_date->format( $df ) : "" ;?></td>
		    <td class="hilite">9c. <?php echo $rapidResultsType[$obj->counselling_determine];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">10a. <?php echo $AppUI->_('Bio-line');?> </td>
		    <td class="hilite">10b. <?php echo $bioline_date ? $bioline_date->format( $df ) : "" ;?></td>
		    <td class="hilite">10c. <?php echo $rapidResultsType[$obj->counselling_bioline];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">11a. <?php echo $AppUI->_('Uni-gold');?> </td>
		    <td class="hilite">11b. <?php echo $unigold_date ? $unigold_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">11c. <?php echo $rapidResultsType[$obj->counselling_unigold];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">12a. <?php echo $AppUI->_('Elisa');?> </td>
		    <td class="hilite">12b. <?php echo $elisa_date ? $elisa_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">12c. <?php echo $elisaResultsType[$obj->counselling_elisa];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">13a. <?php echo $AppUI->_('PCR 1');?> </td>
		    <td class="hilite">13b.<?php echo  $pcr1_date ? $pcr1_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">13c.<?php echo $pcrResultsType[$obj->counselling_pcr1];?></td>
		 </tr>
		 <tr>
		    <td class="hilite">14a. <?php echo $AppUI->_('PCR 2');?> </td>
		    <td class="hilite">14b. <?php echo $pcr2_date ? $pcr2_date->format( $df ) : "" ;?>  </td>
		    <td class="hilite">14c. <?php echo $pcrResultsType[$obj->counselling_pcr2];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">15a. <?php echo $AppUI->_('Rapid @ 12 months');?> </td>
		    <td class="hilite">15b. <?php echo $rapid12_date ? $rapid12_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">15c.<?php echo $rapidResultsType[$obj->counselling_rapid12];?></td>
		 </tr>
		 <tr>
		    <td class="hilite">16a. <?php echo $AppUI->_('Rapid @ 18 months');?> </td>
		    <td class="hilite">16b. <?php echo $rapid18_date ? $rapid18_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">16c. <?php echo $rapidResultsType[$obj->counselling_rapid18];?> </td>
		 </tr>
		 <tr>
		    <td class="hilite">17a. <?php echo $AppUI->_('Other');?> : &nbsp;<?php echo $obj->counselling_other_notes;?></td>
		    <td class="hilite">17b. <?php echo $other_date ? $other_date->format( $df ) : "" ;?> </td>
		    <td class="hilite">17c. <?php echo $obj->counselling_other;?> </td>
		 </tr>
		 <tr>			
			<td class="hilite">18a. <?php echo $AppUI->_('Final Results');?> : </td>			
			<td class="hilite" colspan="2"> <?php echo $pcrResultsType[$obj->counselling_final];?> </td>
	 	</tr>
	 	<tr>
	 		<td class="hilite">18b. <?php echo $AppUI->_('Discordant Couple');?> : </td>
			<td class="hilite" colspan="2"> <?php echo $boolTypes[$obj->counselling_dis_couple];?> </td>			
	 	</tr>
	 	<tr>
	 		<td class="hilite">19a. <?php echo $AppUI->_('If positive, referred to');?> : </td>
			<td class="hilite" colspan="2"> <?php echo $posRef[$obj->counselling_positive_ref];?> </td>		
	 	</tr>
	 	<tr>
			<td class="hilite">19b...<?php echo $AppUI->_('Other (specify)');?>:</td>
			<td class="hilite" colspan="2">
				<?php echo $obj->counselling_positive_ref_notes; ?>			
			</td>
	 	</tr>

		 </table>
	</td>
	<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "view" );
			$custom_fields->printHTML();
	?>

 </tr>
<?php
	}
}

?>

</table>
