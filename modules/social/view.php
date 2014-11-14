<?php /* SOCIAL WORK VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$social_id = intval( dPgetParam( $_GET, "social_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $social_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $social_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CSocialVisit();
$canDelete = $obj->canDelete( $msg, $social_id );

// load the record data
$q  = new DBQuery;
$q->addTable('social_visit');
$q->addQuery('social_visit.*');
$q->addWhere('social_visit.social_id = '.$social_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Social Visit' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$boolTypesND = dPgetSysVal('YesNoND');
$boolTypes = dPgetSysVal('YesNo');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );
$visitTypes = dPgetSysVal('SocialVisitTypes');
$deathTypes = dPgetSysVal('DeathTypes');
$caregiverChangeTypes = dPgetSysVal('CaregiverChangeTypes');
$caregiverHealthStatus = dPgetSysVal('CaregiverHealthStatus');
$caregiverHealthChanges =  dPgetSysVal('CaregiverHealthChanges');
$educationLevels =  dPgetSysVal('EducationLevel');
$employmentTypes =  dPgetSysVal('EmploymentType');
$socialstatusTypes = arrayMerge(array(0=>'-Select Client Status-'),dPgetSysVal('SocialClientStatus'));

$incomeLevels =  dPgetSysVal('IncomeLevels');
$relocationTypes = dPgetSysVal('RelocationType');
$reasonsNotAttendingSchool = dPgetSysVal('ReasonsNotAttendingSchool');
$igaTypes = dPgetSysVal('IGAOptions');
$placementTypes = dPgetSysVal('PlacementType');
$successionPlanningTypes = dPgetSysVal('SuccessionPlanningTypes');
$legalIssues = dPgetSysVal('LegalIssues');
$nursingCareTypes = dPgetSysVal('NursingCareTypes');
$transportNeeds = dPgetSysVal('TransportNeeds');
$educationNeeds = dPgetSysVal('EducationNeeds');
$foodNeeds = dPgetSysVal('FoodNeeds');
$rentNeeds = dPgetSysVal('RentNeeds');
$solidarityNeeds = dPgetSysVal('SolidarityNeeds');
$directSupportNeeds = dPgetSysVal('DirectSupportNeeds');
$medicalSupportNeeds = dPgetSysVal('MedicalSupportNeeds');
$childSchoolLevels = dPgetSysVal('ChildSchoolLevels');
$childSchoolStatus = dPgetSysVal('ChildSchoolStatus');
$trainingSupport = dPgetSysVal('TrainingSupport');
$positionOptions = dPgetSysVal("PositionOptions");

//load clinics
$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('user_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


$df = $AppUI->getPref('SHDATEFORMAT');
// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($obj->social_client_id))
{
	$ttl = "View Social Visit : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Social Visit ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->social_entry_date) ? new CDate($obj->social_entry_date ) :  null;
$death_date = intval($obj->social_death_date) ? new CDate($obj->social_death_date ) :  null;




if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new social visit record').'" />', '',
		'<form action="?m=social&a=addedit" method="post">', '</form>'
	);

}

$client_id = $client_id ? $client_id : $obj->social_client_id;
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id > 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName() );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=social&a=addedit&social_id=$social_id&client_id=$client_id", "Edit" );

	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete social visit record', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Social Visit Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="75%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=social" method="post">
	<input type="hidden" name="dosql" value="do_social_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="social_id" value="<?php echo $social_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="100%">
	<table cellspacing="1" cellpadding="2">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>

	<tr>
         <td align="left"><?php echo $AppUI->_('Officer');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->social_staff_id]);?>
         </td>
       </tr>
<tr>
         <td align="left"><?php echo $AppUI->_('Center');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clinics[$obj->social_clinic_id]);?>
         </td>
		 </tr>
		 <tr>
		 <td align="left"><?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "-" ;?>
			</td>
       </tr>
      <tr>

	 <tr>
         <td align="left"><?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$clientObj->getFullName());?>
         </td>
       </tr>
         <td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clientObj->client_adm_no);?>
         </td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Type of Visit');?>:</td>

		<td align="left" class="hilite">
		<?php echo $visitTypes[$obj->social_visit_type]; ?>
		</td>
       </tr>
<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Life Events'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Client Status');?>:</td>
		 <td align="left" class="hilite">
				<?php echo $socialstatusTypes[$obj->social_client_status]; ?>
			</td>
       </tr>

	 <tr>
         <td align="left"><?php echo $AppUI->_('Death');?>:</td>
		 <td align="left" class="hilite"><?php echo $deathTypes[$obj->social_death]; ?>
	 </tr>
      <td align="left">...<?php echo $AppUI->_('Other');?></td>
	  <td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_death_notes);?></td>
	 </tr>

	 <tr>
         <td align="left">...<?php echo $AppUI->_('Date');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $death_date ? $death_date->format( $df ) : "-" ;?>
		 </td>
       <tr>
	  <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in primary caregiver");?>:</td>
	   </tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Reason");?>:
		</td>
		<td align="left" class="hilite">
		<?php echo $caregiverChangeTypes[$obj->social_caregiver_change]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Other");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_change_notes);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("First Name");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_fname);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Last Name");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_lname);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Age");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_age);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Status");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $caregiverHealthStatus[$obj->social_caregiver_status]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Relationship to Child");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_relationship);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Education level");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $educationLevels[$obj->social_caregiver_education]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Employment");?>:
		</td>
		<td align="left" class="hilite">
		 <?php echo $employmentTypes[$obj->social_caregiver_employment]; ?>

		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Income level");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $incomeLevels[$obj->social_caregiver_income]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("ID #");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_idno);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Mobile #");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_caregiver_mobile);?>
		</td>
		</tr>

	  <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in health of caregiver");?>:</td>
        </tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Health");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $caregiverHealthChanges[$obj->social_caregiver_health]; ?></td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Condition is hindrance on care for the child");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $boolTypesND[$obj->social_caregiver_health_child_impact]; ?>
			</td>
		</tr>
	   <tr>
        <td align="left" valign="top" ><?php echo $AppUI->_("Change of Residence");?>:</td>
        </tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Mobile #");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->social_caregiver_mobile);?>
			</td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Current residence (physical address and landmarks)");?>:
			</td>
			<td align="left" class="hilite">
						<?php echo dPformSafe(@$obj->social_residence);?>
			</td>
		</tr>
	   <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in household income level");?>:</td>
        </tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Change due to employment type of primary caregiver");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $boolTypes[$obj->social_caregiver_employment_change]; ?>
			</td>

		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("If yes, new employment");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $employmentTypes[$obj->social_caregiver_new_employment]; ?>
			</td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Other");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $obj->social_caregiver_new_employment_desc; ?>
			</td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("New income range");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $incomeLevels[$obj->social_caregiver_income]; ?>
			</td>
		</tr>
	   <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in schooling");?>:</td>
        </tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("Attendance");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $childSchoolStatus[$obj->social_school_attendance]; ?>

			</td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("New school level");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $childSchoolLevels[$obj->social_school]; ?></td>
		</tr>
		<tr>
			<td align="left">
			...<?php echo $AppUI->_("If not attending, why");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $reasonsNotAttendingSchool[$obj->social_reason_not_attending]; ?></td>
		</tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs supported'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Permanency Planning');?>:</td>
			</tr>
			<tr>
			<td align="left">
			...<?php echo $AppUI->_("Relocation");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $relocationTypes[$obj->social_relocation]; ?></td>
			</tr>
			<tr>
			<td align="left">
			...<?php echo $AppUI->_("IGA");?>:
			</td>
			<td align="left" class="hilite">
	        	<?php echo $igaTypes[$obj->social_iga]; ?>

			</td>

			</tr>
			<tr>
			<td align="left">
			...<?php echo $AppUI->_("Placement");?>:
			</td>
			<td align="left" class="hilite">
	            			<?php echo $placementTypes[$obj->social_placement]; ?>

			</td>

			</tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Succession Planning');?>:</td>
			<td align="left" class="hilite" valign="top"><?php echo $successionPlanningTypes[$obj->social_succession_planning]; ?>
			</td>
      </tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Legal');?>:</td>
			<td align="left" class="hilite" valign="top"><?php echo $legalIssues[$obj->social_legal]; ?>
			</td>
      </tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Nursing/Palliative Care');?>:</td>
			<td align="left" class="hilite" valign="top"><?php echo $nursingCareTypes[$obj->social_nursing]; ?>
			</td>
      </tr>
	  <tr>
			<td align="left"><?php echo $AppUI->_('Transport');?>:</td>
			<td align="left" class="hilite" valign="top"><?php echo $transportNeeds[$obj->social_transport]; ?>
			</td>
      </tr>

		<tr>
         <td align="left"><?php echo $AppUI->_('Education');?>:</td>
			<td align="left" class="hilite">
		<?php echo $educationNeeds[$obj->social_education]; ?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Food');?>:</td>
		<td align="left" class="hilite">
		<?php echo $foodNeeds[$obj->social_food]; ?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Rent');?>:</td>
		<td align="left" class="hilite">
		<?php echo $rentNeeds[$obj->social_rent]; ?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Solidarity');?>:</td>
		<td align="left" class="hilite">
		<?php echo $solidarityNeeds[$obj->social_solidarity]; ?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Direct Support');?>:</td>
		<td align="left" class="hilite">
		<?php echo $directSupportNeeds[$obj->social_direct_support]; ?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Medical Support');?>:</td>
		<td align="left" class="hilite">
		<?php echo $medicalSupportNeeds[$obj->social_medical_support]; ?>
		</td>
		</tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Training Support');?>:</td>
		<td align="left" class="hilite">
		<?php echo $trainingSupport[$obj->social_training]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_('Other');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_medical_support_desc);?>
		</td>
       </tr>
		<tr>
		<td align="left">
			<?php echo $AppUI->_('Other Direct Support');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_other_support);?>
		</td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('New Risk Level');?>:</td>
		<td align="left" class="hilite">
		<?php echo $riskLevels[$obj->social_risk_level]; ?>
		</td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Next Appointment Date');?>:</td>
		<td align="left" class="hilite">
		<?php echo $obj->social_next_visit; ?>
		</td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Referral To');?>:</td>
		<td align="left" class="hilite">
		<?php echo @$positionOptions[$obj->social_referral]; ?>
		</td>
       </tr>
	   <tr>
	   <td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->social_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
       </tr>
		<tr>
		 <td align="left" valign="top"><?php echo $AppUI->_('Comments');?>:</td>
		<td valign="top" class="hilite">
		<?php echo nl2br(@$obj->social_notes);?>
		</td>
		</tr>
     </table>
	</td>
</tr>
</table>


