<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('mortality');
require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');

$df = $AppUI->getPref('SHDATEFORMAT');
$ageTypes = dPgetSysVal('AgeType');
$genderTypes = dPgetSysVal('GenderType');
$deathPlaces = dPgetSysVal('DeathPlaceTypes');
$boolTypes = dPgetSysVal('YesNo');
$boolTypesND = dPgetSysVal('YesNoND');
$q = new DBQuery;
$q->addTable('mortality_info');
$q->addQuery ('mortality_info.*');
$q->addWhere('mortality_info.mortality_client_id = '.$client_id);
$s='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo /*$AppUI->_("No data available") .*/  $AppUI->getMsg().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?m=mortality&a=addedit&client_id='.$client_id.'">Add mortality record</a>';
	
}
else
{
	$title="edit mortality record...";
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
}

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


$riskLevels = dPgetSysVal('RiskLevel');

//load centers
$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();
	
//load centers
/*$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');	
$clinics = $q->loadHashList();*/




?>
<table cellpadding="4" cellspacing="0" width="100%" class="std">


<?php



foreach ( $rows as $row ) {
		$url = "./index.php?m=mortality&a=addedit&client_id=$client_id&mortality_id=" . $row ["mortality_id"];
		$obj = new CMortality ( );
		$obj->load ( $row ["mortality_id"] );
		$clientObj = new CClient ( );
		$clientObj->load ( $obj->mortality_client_id );
		
		// collect all the users for the staff list
		if (! is_null ( $obj->mortality_clinical_officer ) && (int)$obj->mortality_clinical_officer  > 0) {
			$q = new DBQuery ( );
			$q->addTable ( 'contacts', 'con' );
			$q->leftJoin ( 'users', 'u', 'u.user_contact = con.contact_id' );
			//$q->addQuery('contact_id');
			$q->addQuery ( 'CONCAT_WS(", ",contact_last_name,contact_first_name)' );
			$q->addWhere ( 'contact_id=' . $obj->mortality_clinical_officer );
			//$q->addOrder('contact_last_name');
			$officerName = $q->loadResult (); //$q->loadHashList();
		}
		
		$client_dob = $clientObj->getDOB ();
		$titleBlock = new CTitleBlock ( $ttl, '', $m, "$m.$a" );
		$entry_date = intval ( $obj->mortality_entry_date ) ? new CDate ( $obj->mortality_entry_date ) : null;
		$dob = intval ( $client_dob ) ? new CDate ( $client_dob ) : null;
		$mortality_date = intval ( $obj->mortality_date ) ? new CDate ( $obj->mortality_date ) : null;		
		$mortality_report_date = intval ( $obj->mortality_relative_report_date ) ? new CDate ( $obj->mortality_relative_report_date ) : null;
		$mortality_admission_date = intval ( $obj->mortality_hospital_adm_date ) ? new CDate ( $obj->mortality_hospital_adm_date ) : null;
		$mortality_clinical_report_date = intval ( $obj->mortality_clinical_officer_date ) ? new CDate ( $obj->mortality_clinical_officer_date ) : null;
		$arv_date = intval($obj->mortality_arv_dateon) ? new CDate($obj->mortality_arv_dateon) : null;
		$nutr_date = intval($obj->mortality_nutrition_date) ? new CDate($obj->mortality_nutrition_date) : null;
		$tb_date = intval($obj->mortality_tb_start) ? new CDate($obj->mortality_tb_start) : null;
		$clin_date = intval($obj->mortality_clinical_date) ? new CDate($obj->mortality_clinical_date) : null;
		$enroll_date = intval($obj->mortality_enroll_date) ? new CDate($obj->mortality_enroll_date) : null;
	$s .= '<tr><td colspan="6" align="left" valign="top">';
	//$s .= '<input type="button" class=button value="'.$AppUI->_( $title ).'" onClick="javascript:window.location=\''.$url.'\'">';
	$s .= '<a href="'.$url . '">'. $AppUI->_( $title ).'</a>';
	$s .= '</td></tr>';		echo $s;
?>
	<tr>
		<td valign="top" width="100%">
		<table border="0" cellpadding="4" cellspacing="1">
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Details'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite">
				<?php echo $clinics[$obj->mortality_clinic_id]; ?>       
			</td>
			</tr>

			<tr>
				<td align="left" nowrap>1b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
				
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
			</tr>			
			<tr>
				<td align="left" nowrap>1b.<?php echo $AppUI->_('Social Worker');?>: </td>
				<td align="left" class="hilite">				
					<?php echo $obj->mortality_social_worker ;?>
				</td>
			</tr>
			<tr>
				<td align="left">3b.<?php echo $AppUI->_('Total Orphan?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[@$admissionObj->admission_total_orphan]; ?></td>
			</tr>
			<tr>
				<td align="left">5a.<?php echo $AppUI->_('Date of admission');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $enroll_date ?  $enroll_date->format($df) : "-";?>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">5b.<?php echo $AppUI->_('Time on programme');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->mortality_enrolled_time;?>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">6a.<?php echo $AppUI->_('Date of death');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $mortality_date ?  $mortality_date->format($df) : "-";?>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">6b<?php echo $AppUI->_('Place of death');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $deathPlaces[$obj->mortality_death_type];?></td>
			</tr>
			<tr>
				<td align="left">6c...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->mortality_death_type_notes);?>
         </td>
			</tr>

			<tr>
				<td align="left">7.<?php echo $AppUI->_('Informant');?>:</td>
				<td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->mortality_informant);?>
         </td>
			</tr>
			<tr>
				<td align="left">8a.<?php echo $AppUI->_('Hospital Name');?>:</td>
				<td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->mortality_hospital);?>
         </td>
			</tr>
			<tr>
				<td align="left">8b.<?php echo $AppUI->_('Date of admission');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $mortality_admission_date ?  $mortality_admission_date->format($df) : "-"  ;?>&nbsp;</td>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Report from relative'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">9.<?php echo $AppUI->_('Date of report');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $mortality_report_date ?  $mortality_report_date->format($df) : "-"  ;?>&nbsp;</td>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top"><?php echo $AppUI->_('Last illness');?>:</td>
			</tr>
			<tr>
				<td align="left" valign="top">10a....<?php echo $AppUI->_('Symptoms');?>:</td>
				<td align="left" class="hilite"><?php echo dPformSafe(@$obj->mortality_symptoms);?></td>
			</tr>
			<tr>
				<td align="left" valign="top">10b...<?php echo $AppUI->_('Time course');?>:</td>
				<td align="left" class="hilite"><?php echo dPformSafe(@$obj->mortality_time_course);?></td>
			</tr>
			<tr>
				<td align="left" valign="top">10c...<?php echo $AppUI->_('Treatment');?>:</td>
				<td align="left" class="hilite"><?php echo dPformSafe(@$obj->mortality_treatment);?></td>
			</tr>
			<tr>

				<td align="left">11a.<?php echo $AppUI->_('Was the child refered to hospital by LT clinic?');?></td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->mortality_hospital_referral]; ?></td>

			</tr>
			<tr>
				<td align="left" valign="top">11b...<?php echo $AppUI->_('If Yes, why?');?></td>
				<td align="left" class="hilite" valign="top">
		<?php echo dPformSafe(@$obj->mortality_referral);?>
		</td>
			</tr>

			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Report from the hospital'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">12.<?php echo $AppUI->_('Reason for admission');?>:</td>
				<td align="left" valign="top" class="hilite">
		<?php echo dPformSafe(@$obj->mortality_hospital_adm_notes);?>
		</td>

			</tr>
			<tr>
         <td align="left">13.<?php echo $AppUI->_('Clinical Course');?>:</td>
         <td align="left" class="hilite">
          	<?php echo $obj->mortality_clinical_course; ?>
         </td>
       </tr>
       <tr>
			<tr>
				<td align="left" valign="top">14a.<?php echo $AppUI->_('Cause(s0 of death given');?>:</td>
				<td align="left" class="hilite"><?php echo $boolTypes[$obj->mortality_cause_given]; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">14b...<?php echo $AppUI->_('If Yes, what?');?></td>
				<td align="left" valign="top" class="hilite">
		<?php echo dPformSafe(@$obj->mortality_cause_desc);?>
		</td>

			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Clinical Officer'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>

			<tr>
				<td align="left">15a.<?php echo $AppUI->_('Clinical Officer Name');?>:</td>
				<td align="left" class="hilite">
          <?php echo $officerName;/*dPformSafe(@$obj->mortality_clinical_officer);*/?>
          &nbsp;&nbsp;&nbsp;<b>Obsolete:&nbsp;</b><?php echo dPformSafe(@$obj->mortality_clinical_officer_old);?>
         </td>
			</tr>
			<tr>
				<td align="left">15b.<?php echo $AppUI->_('Date of report');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $mortality_clinical_report_date ?  $mortality_clinical_report_date->format($df) : "-"   ;?>&nbsp;</td>
				</td>
			</tr>
			 <tr>
    	<td><b>Immune status</b></td>
    </tr>
    <tr>
		<td align="left">16a.<?php echo $AppUI->_('CD4');?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->mortality_cd4;?>	
		</td>
    </tr>   
    <tr>
		<td align="left">16b.<?php echo $AppUI->_('CD4 %');?>:</td>		
		<td align="left" valign="top" class="hilite">
		<?php echo $obj->mortality_cd4_percentage;?>
		</td>
    </tr>
    <tr>
		<td align="left">16c.<?php echo $AppUI->_('Viral load');?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->mortality_viral_load;?>
		</td>
    </tr>
    <tr>
		<td align="left">16d.<?php echo $AppUI->_('Hb');?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->mortality_hb;?>
		</td>
    </tr>
    <tr>
		<td align="left">16e.<?php echo $AppUI->_('Date');?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php
				echo $clin_date ? $clin_date->format($df) : '';
			?>				
		</td>
    </tr>    
    <tr>
        <td align="left">17a.<?php echo $AppUI->_("ARV's");?>:</td>
        <td align="left" valign="top" class="hilite">
			<?php echo @$boolTypes[$obj->mortality_arv] ?> 
		</td>
	</tr>	
    <tr>
		<td align="left">17b.<?php echo $AppUI->_('Date started');?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php 
				echo $arv_date ? $arv_date->format($df) : "";
			?>		
		</td>
    </tr>
    <tr>
		<td align="left">17c.<?php echo $AppUI->_("Time on ARV's");?>:</td>		
		<td align="left" valign="top" class="hilite">
			 <?php echo $obj->mortality_arv_period;?>
		</td>
    </tr>   
    <tr>
		<td align="left">18a.<?php echo $AppUI->_("TB treatment");?>:</td>		
		<td align="left" valign="top" class="hilite">
			<?php echo @$boolTypes[$obj->mortality_tb]; ?>
		</td>		
    </tr>
    <tr>
		<td align="left">18b...<?php echo $AppUI->_("Date started");?>:</td>	
		<td align="left" valign="top" class="hilite">
		<?php 
			echo $tb_date ? $tb_date->format($df) : "";
		?>				
		</td>
    </tr>
    <tr><td><b>Nutrition</b></td></tr>
    <tr>
		<td align="left">19a...<?php echo $AppUI->_("Last Weight");?>:</td>	
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->mortality_weight;?>
		</td>
    </tr>
    <tr>
		<td align="left">19b...<?php echo $AppUI->_("Last Height");?>:</td>	
		<td align="left" valign="top" class="hilite">
			<?php echo $obj->mortality_height;?>
		</td>
    </tr>
    <tr>
		<td align="left">19c...<?php echo $AppUI->_("Date");?>:</td>	
		<td align="left" valign="top" class="hilite">
		<?php 
			 echo $nutr_date ?  $nutr_date->format($df) : "" ;
		?>						
		</td>
    </tr>
    <tr>
        <td align="left">20a.<?php echo $AppUI->_("Malnutrition");?>:</td>
		<td align="left" valign="top" class="hilite">
			<?php echo @$boolTypes[$obj->mortality_malnutrition]; ?>&nbsp;&nbsp;&nbsp;
			20b.&nbsp;<?php $tar=dPgetSysVal('Grades'); echo @$tar[$obj->mortality_malnutrition_notes]; ?>
		</td>
	</tr>
	<tr>
		<td align="left">21a.<?php echo $AppUI->_("Other Recent Problems: A");?>:</td>	
		<td align="left" valign="top" class="hilite">
			<?php echo dPformSafe($obj->mortality_recents_a);?>
		</td>
    </tr>
    <tr>
		<td align="left">21b.<?php echo $AppUI->_("Other Recent Problems: B");?>:</td>	
		<td align="left" valign="top" class="hilite">
			<?php echo dPformSafe($obj->mortality_recents_b);?>
		</td>
    </tr>
	<tr>
		<td align="left">22a.<?php echo $AppUI->_("Is the postmortem arranged");?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->mortality_postmortem]; ?></td>
	</tr>
		<tr>
		<td align="left">22b.<?php echo $AppUI->_("Where ?");?>:</td>	
		<td align="left" valign="top" class="hilite">
				<?php echo dPformSafe($obj->mortality_postmortem_where);?>
		</td>
    </tr>
			</td>
			</tr>
		

			<tr>
				<td align="left" valign="top">22c...<?php echo $AppUI->_('If Yes, cause of death from PM?');?></td>
				<td align="left" valign="top" class="hilite">
		<?php echo dPformSafe(@$obj->mortality_cause_pm);?>
		</td>

			</tr>
			<tr>
				<td align="left">23...<?php echo $AppUI->_('If No PM, likely causes of death? ');?>:</td>
				<td align="left" valign="top" class="hilite">
				<?php echo dPformSafe(@$obj->mortality_likely_cause);?>
			</td>
			</tr>

			<tr>
				<td align="left">24...<?php echo $AppUI->_('Other factors');?>:</td>


				<td align="left" valign="top" class="hilite">
				<?php echo dPformSafe(@$obj->mortality_notes);?>
			</td>
			</tr>
		</table>
		</td>
	<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->mortality_id, "view" );
			$custom_fields->printHTML();
	?>
		
 </tr>
<?php
	}
}
	
?>

</table>
