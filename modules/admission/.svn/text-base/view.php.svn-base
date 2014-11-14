<?php /* MEDICAL ASSESSMENT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$admission_id = intval( dPgetParam( $_GET, "admission_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));


$boolTypes = dPgetSysVal('YesNo');
$maritalStatus = dPgetSysVal('MaritalStatus');
$educationLevels = dPgetSysVal('EducationLevel');
$caregiverStatus = dPgetSysVal('CaregiverStatus');
$childEducationLevels = dPgetSysVal('ChildEducationLevel');
$reasonsNoSchool = dPgetSysVal('AdmissionReasonsNotAttendingSchool');
$incomeLevels = dPgetSysVal('IncomeLevels');
$employmentType = dPgetSysVal('EmploymentType');
$dehydrationTypes = dPgetSysVal('DehydrationType');
$tbTypes = dPgetSysVal('TBType');
$malnutrionTypes = dPgetSysVal('MalnutritionType');
$earTypes = dPgetSysVal('EarType');
$arvTypes = dPgetSysVal('ARVType');
$tbDrugsTypes = dPgetSysVal('TBDrugsType');
$nutritionTypes = dPgetSysVal('NutritionType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );
$enclosures = dPgetSysVal('Enclosures');
$caregiverHealthStatus = dPgetSysVal('CaregiverHealthStatus');

/*
$boolTypes = dPgetSysVal('YesNoND');
$genderTypes = dPgetSysVal('GenderType');
$immunizationStatus = dPgetSysVal('ImmunizationType');
$hivStatus = dPgetSysVal('HIVStatus');
$schoolLevels = dPgetSysVal('EducationLevel');
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
$reasonsNoSchool = dPgetSysVal('ReasonsNotAttendingSchool');
*/
// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $admission_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $admission_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CAdmissionRecord();
$canDelete = $obj->canDelete( $msg, $admission_id );

// load the record data
$q  = new DBQuery;
$q->addTable('admission_info');
$q->addQuery('admission_info.*');
$q->addWhere('admission_info.admission_id = '.$admission_id);
$sql = $q->prepare();
$q->clear();

if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Admission Record' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}


$df = $AppUI->getPref('SHDATEFORMAT');
// setup the title block
$client_id = $client_id ? $client_id : $obj->admission_client_id;
//load client
$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = "View Admission Record : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Admission Record";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->admission_entry_date) ? new CDate($obj->admission_entry_date ) :  null;
//$mother_status_date = intval($obj->counselling_date_mothers_status_known) ? new CDate($obj->counselling_date_mothers_status_known ) :  null;


//load family members
$q = new DBQuery();
$q->addTable("household_info");
$q->addQuery("household_info.*");
$q->addWhere("household_info.household_admission_id = " . $obj->admission_id);
$housemembers = $q->loadList();


$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id != 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " .$clientObj->getFullName()  );

if ($canEdit) {
	$titleBlock->addCrumb( "?m=admission&a=addedit&admission_id=$admission_id&client_id=$client_id", "Edit" );

	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete admission record', $canDelete, $msg );
	}
}
$titleBlock->show();
$caregivers=null;
$tablePre=null;
$careofs=null;

$obj->getCare();
?>
<script language="javascript">
<?php
// security improvement:
// some javascript functions may not appear on client side in case of user not having write permissions
// else users would be able to arbitrarily run 'bad' functions
if ($canDelete) {
?>
function delIt() {
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Admission Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=admission" method="post">
	<input type="hidden" name="dosql" value="do_admission_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="admission_id" value="<?php echo $admission_id;?>" />
</form>
<?php } ?>

<tr>
    <td colspan="2" valign="top">
     <table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
		<td align="left" nowrap><?php echo $AppUI->_('Entry Date');?>: </td>
		<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
		</td>
    </tr>

      <tr>
         <td align="left" nowrap><?php echo $AppUI->_('School Level');?>:</td>
		 <td align="left" class="hilite">
	   <?php echo $childEducationLevels[$obj->admission_school_level]; ?>
		 </td>
      </tr>
	  <tr>
         <td align="left" nowrap>...<?php echo $AppUI->_('If not attending,why');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo $reasonsNoSchool[@$obj->admission_reason_not_attending];?>
		 </td>
	 </tr>
	 <tr>
         <td align="left" nowrap>...<?php echo $AppUI->_('Other reason');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->admission_reason_not_attending_notes;?>
		 </td>
	 </tr>
	  <tr>
	  <td align="left" nowrap><?php echo $AppUI->_('Current Residence');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->admission_residence;?>
		 </td>

      </tr>
		  <tr>
			<td align="left"><?php echo $AppUI->_('Location');?>:</td>
			<td nowrap="nowrap" align="left" class="hilite">
				<?php echo $obj->admission_location;?>
			</td>
		</tr>
        <tr>
			<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Rural Home');?>:</td>
		</tr>
		<tr>
			  <td align="left" nowrap="nowrap">...<?php echo $AppUI->_('Province');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->admission_province;?>
		 </td>
		   </tr>
			<tr>
			  <td align="left" nowrap="nowrap">...<?php echo $AppUI->_('District');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->admission_district;?>
		 </td>
		   </tr>
			<tr>
			  <td align="left" nowrap="nowrap">...<?php echo $AppUI->_('Village');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->admission_village;?>
		 </td>
		   </tr>
	      <tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Caregiver Information'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	 <?php
		if($careofs > 0){
			foreach ($caregivers as $carename => $carearr) {
				if(count($carearr) == 2){
					$sdata=$carearr['data'];
					$gname=$tablePre.'_'.$carename;
					?>
						   <tr>
         <td align="left" nowrap = "nowrap"><?php echo $AppUI->_($carearr['title']);?>:</td>
		</tr>
		 <tr>
		   <td align="left" nowrap="nowrap">...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$sdata['fname'];?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$sdata['lname'];?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left" class="hilite"><?php echo @$sdata['age'];?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Health Status');?>:</td>
		   <td align="left" class="hilite"><?php echo $caregiverHealthStatus[@$sdata['health_status']];?></td>
		 </tr>
		  <?php if(!strstr($carename,'caregive')){ ?>
		  <tr>
			<td align="left">...<?php echo $AppUI->_('Raising Child');?>:</td>
		   <td align="left" class="hilite"><?php echo $boolTypes[$obj->{$gname.'_raising_child'}]; ?>
		 </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left" class="hilite"><?php echo $caregiverStatus[$obj->{$gname.'_status'}]; ?>
		 </td>
		   </tr>
		 <?php
		  }else{?>
		  <tr>
			<td align="left">...<?php echo $AppUI->_('Relationship to child');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->{$gname.'_relationship'};?>
		 </td>
		   </tr>
		  <?php
		  }
		 ?>

		   <tr>
			<td align="left">...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left" class="hilite"><?php echo $maritalStatus[$sdata['marital_status']]; ?>
		 </td>
		   </tr>
		 </tr>
		 <tr>
		   <td align="left" >...<?php echo $AppUI->_('Education Level');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $educationLevels[$sdata['educ_level']]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $employmentType[$sdata['employment']]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left"><?php echo $AppUI->_('Other Details');?>:</td>
		  </tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$sdata['idno'];?></td>
			</tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$sdata['mobile'];?></td>
			</tr>
			<?php
				}
			}
		}
/*
	 ?>
	   <tr>
         <td align="left" nowrap = "nowrap"><?php echo $AppUI->_('Father');?>:</td>
		</tr>
		 <tr>
		   <td align="left" nowrap="nowrap">...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_father_fname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_father_lname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_father_age;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left" class="hilite"><?php echo $caregiverStatus[$obj->admission_father_status]; ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Raising Child');?>:</td>
		   <td align="left" class="hilite"><?php echo $boolTypes[$obj->admission_father_raising_child]; ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left" class="hilite"><?php echo $maritalStatus[$obj->admission_father_marital_status]; ?>
		 </td>
		   </tr>
		 </tr>
		 <tr>
		   <td align="left" >...<?php echo $AppUI->_('Education Level');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $educationLevels[$obj->admission_father_educ_level]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $employmentType[$obj->admission_father_employment]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left"><?php echo $AppUI->_('Other Details');?>:</td>
		  </tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_father_idno;?></td>
			</tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_father_mobile;?></td>
			</tr>
	  <tr>
         <td align="left" nowrap valign="top"><?php echo $AppUI->_('Mother');?>:</td>
	   </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_mother_fname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_mother_lname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_mother_age;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left" class="hilite">
	   <?php echo $caregiverStatus[$obj->admission_mother_status]; ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Raising Child');?>:</td>
		   <td align="left" class="hilite">
	   <?php echo $boolTypes[$obj->admission_mother_raising_child]; ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left" class="hilite"><?php echo $maritalStatus[$obj->admission_mother_marital_status]; ?>
		 </td>
		   </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Education Level');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $educationLevels[$obj->admission_mother_educ_level]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $employmentType[$obj->admission_mother_employment]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left"><?php echo $AppUI->_('Other Details');?>:</td>
		 </tr>
			<tr>
			  <td align="left">...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_mother_idno;?></td>
			</tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_mother_mobile;?></td>
			</tr>
	  <tr>
         <td align="left" nowrap valign="top"><?php echo $AppUI->_('Primary Caregiver');?>:</td>
	  </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('First Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_fname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Last Name');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_lname;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Age');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_age;?></td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Status');?>:</td>
		   <td align="left" class="hilite"><?php echo $caregiverStatus[$obj->admission_caregiver_status]; ?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Relationship to child');?>:</td>
		   <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_relationship;?>
		 </td>
		   </tr>
		   <tr>
			<td align="left">...<?php echo $AppUI->_('Marital status');?>:</td>
		   <td align="left" class="hilite"><?php echo $maritalStatus[$obj->admission_caregiver_marital_status]; ?>
		 </td>
		   </tr>
		 <tr>
		   <td align="left" >...<?php echo $AppUI->_('Education Level');?>:</td>

		   <td align="left" class="hilite">
		   <?php echo $educationLevels[$obj->admission_caregiver_educ_level]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Employment');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $employmentType[$obj->admission_caregiver_employment]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left">...<?php echo $AppUI->_('Monthly Income');?>:</td>
		   <td align="left" class="hilite">
		   <?php echo $incomeLevels[$obj->admission_caregiver_income]; ?>
		   </td>
		 </tr>
		 <tr>
		   <td align="left"><?php echo $AppUI->_('Other Details');?>:</td>
		   </tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('ID #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_idno;?></td>
			</tr>
		    <tr>
			  <td align="left">...<?php echo $AppUI->_('Mobile #');?>:</td>
			  <td align="left" class="hilite"><?php echo @$obj->admission_caregiver_mobile;?></td>
			</tr> */?>
	 <tr>
			  <td align="left">...<?php echo $AppUI->_('Total Family Income');?>:</td>
			  <td class="hilite">
			  <?php echo $incomeLevels[$obj->admission_family_income]; ?>
			  </td>
	</tr>
      <tr>
         <td align="left" nowrap valign="top"><?php echo $AppUI->_('Other Household Members');?>:</td>
		 <td align="left">
		 <table class="tbl">
		 <tr>
		 	<th><?php echo $AppUI->_('Name');?></th>
			<th><?php echo $AppUI->_('Year of Birth');?></th>
			<th><?php echo $AppUI->_('Gender');?></th>
			<th><?php echo $AppUI->_('Relationship to child');?></th>
			<th><?php echo $AppUI->_('Comments');?></th>
		 </tr>
		 <?php foreach ($housemembers as $housemember)
		 {
		 ?>
		 <tr>
			<td><?php echo $housemember["household_name"];?></td>
			<td><?php echo $housemember["household_yob"];?></td>
			<td><?php echo $housemember["household_gender"];?></td>
			<td><?php echo $housemember["household_relationship"];?></td>
			<td><?php echo $housemember["household_notes"];?></td>
		 </tr>
		 <?php } ?>
		 </table>
		 </td>
	  </tr>
       <tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Social Worker Assessment'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	  <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Risk Level');?>:</td>
			<td align="left" valign="top" class="hilite">
			<?php echo $riskLevels[$obj->admission_risk_level];?>
 			</td>
	  </tr>
      <tr>
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Social Worker Assessment');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo @$obj->admission_risk_level_description;?>
		</td>

      </tr>
	<tr>
	  <td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->admission_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
</tr>

</td>
</tr>
</table>