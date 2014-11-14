<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');

$title = 'new social visit...';
$riskLevels = dPgetSysVal('RiskLevel');
$visitTypes = dPgetSysVal('SocialVisitTypes');

$df = $AppUI->getPref('SHDATEFORMAT');
$q = new DBQuery;
$q->addTable('social_visit');
$q->addQuery ('social_visit.*');
$q->addWhere('social_visit.social_client_id = '.$client_id);
$q->addOrder('social_visit.social_entry_date desc');
$w ='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add social visit...";
	$url = "./index.php?m=social&a=addedit&client_id=$client_id";

}
else
{
// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();

?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Visit Date' );?></td>
	<th><?php echo $AppUI->_( 'Social Worker' );?></td>
	<th><?php echo $AppUI->_( 'Type of Visit' );?></td>
	<th><?php echo $AppUI->_( 'Risk Level' );?></td>

</tr>
<?php

    foreach ($rows as $row)
    {
		$url = "./index.php?m=social&a=addedit&client_id=$client_id&social_id=".$row["social_id"];
		$socialObj = new CSocialVisit();
		$socialObj->load($row["social_id"]);
		$entry_date = intval( $socialObj->social_entry_date ) ? new CDate( $socialObj->social_entry_date ) : NULL;
		$visit_date = ($entry_date != NULL) ? $entry_date->format($df) : "";


		$w .= '<tr>';
		$w .= '<td><a href="./index.php?m=clients&a=view&social_id='.$socialObj->social_id.'&client_id='.$client_id. '">'. $visit_date.'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&social_id='.$socialObj->social_id.'&client_id='.$client_id. '">'. $owners[$socialObj->social_staff_id].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&social_id='.$socialObj->social_id.'&client_id='.$client_id. '">'. $visitTypes[$socialObj->social_visit_type].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&social_id='.$socialObj->social_id.'&client_id='.$client_id. '">'. $riskLevels[$socialObj->social_risk_level].'</a></td>';
		$w .= '</tr>';
	}
}

	$w .= '<tr><td colspan="4" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new social visit' ).'" onClick="javascript:window.location=\'./index.php?m=social&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '</td></tr>';
	echo $w;

?>

</table>
<?php /* SOCIAL WORK VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$social_id = intval( dPgetParam( $_GET, "social_id", $rows[0]["social_id"] ) );
$client_id = intval( dPgetParam( $_GET, "client_id", $client_id) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( 'social', 'view', $social_id );
$canEdit = $perms->checkModuleItem( 'social', 'edit', $social_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CSocialVisit();
$canDelete = $obj->canDelete( $msg, $social_id );

// load the record data

if ($social_id > 0)
{
	$q  = new DBQuery;
	$q->addTable('social_visit');
	$q->addQuery('social_visit.*');
	$q->addWhere('social_visit.social_id = '.$social_id);
	$sql = $q->prepare();
	$q->clear();


	if (!db_loadObject( $sql, $obj )) {
		$AppUI->setMsg( 'Social Visit' );
		$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
		$AppUI->redirect();
	} else {
	$AppUI->savePlace();
	}

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

$boolTypesND = dPgetSysVal('YesNoND');
$boolTypes = dPgetSysVal('YesNo');
$riskLevels = dPgetSysVal('RiskLevel');
$visitTypes = dPgetSysVal('SocialVisitTypes');
$deathTypes = dPgetSysVal('DeathTypes');
$caregiverChangeTypes = dPgetSysVal('CaregiverChangeTypes');
$caregiverHealthStatus = dPgetSysVal('CaregiverHealthStatus');
$caregiverHealthChanges =  dPgetSysVal('CaregiverHealthChanges');
$educationLevels =  dPgetSysVal('EducationLevel');
$employmentTypes =  dPgetSysVal('EmploymentType');
$socialstatusTypes = dPgetSysVal('SocialClientStatus');
$genderTypes = dPgetSysVal('GenderType');
$serviceTypes = dPgetSysVal('ServiceTypes');
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
$positionOptions = dPgetSysVal('PositionOptions');

$statusTypes = dPgetSysVal('ClientStatus');
$clientHealth = dPgetSysVal('ClientHealth');

$medical_support_options = explode(",",$obj->social_medical_support);
$direct_support_options = explode(",", $obj->social_direct_support);
$solidarity_options = explode(",", $obj->social_solidarity);
$rent_options = explode(",", $obj->social_rent);
$food_options = explode(",", $obj->social_food);
$education_options = explode(",", $obj->social_education);
$transport_options = explode(",", $obj->social_transport);
$nursing_options = explode(",", $obj->social_nursing);
$legal_options = explode(",", $obj->social_legal);
$succession_planning_options = explode(",", $obj->social_succession_planning);
$placement_options = explode(",", $obj->social_placement);
$iga_options = explode(",", $obj->social_iga);
$relocation_options = explode(",", $obj->social_relocation);
$trainings = explode(',', $obj->social_training);

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
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


$df = $AppUI->getPref('SHDATEFORMAT');
$entry_date = intval($obj->social_entry_date) ? new CDate($obj->social_entry_date ) :  null;

// setup the title block
$date_string = $entry_date ? $entry_date->format($df) : "";

$careInfo = array('primary'=>array(),'secondary'=>array());
$q = new DBQuery();
$q->addTable('admission_caregivers');
$q->addOrder('id desc');
$q->setLimit(1);
$q->addWhere('client_id='.$client_id);

$q1 = clone $q;
$q->addWhere('role="pri"');
if((int)$obj->social_caregiver_pri > 0){
	$q->addWhere('id='.$obj->social_caregiver_pri);
}else{
	$q->addWhere('datesoff is null');
}
$tar = $q->loadList();
//again add check for caregiver parent assigned during admission
if(count($tar) === 0){
	$q3 = new DBQuery();
	$q3->addTable('admission_info');	
	$q3->setLimit(1);
	$q3->addQuery('ac.*');
	$q3->addWhere('admission_client_id='.$client_id);
	$q3->addJoin('admission_caregivers','ac','admission_caregiver_pri = ac.id');
	$q3->addWhere('ac.datesoff is null');
	$tar = $q3->loadList();
}
if(count($tar) == 1){
	$careInfo['primary']=$tar[0];
	unset($tar);
}
if((int)$obj->social_caregiver_sec > 0){
	$q1->addWhere('id='.$obj->social_caregiver_sec);
}else{
	$q1->addWhere('datesoff is null');
}
$q1->addWhere('role="sec"');
$tar = $q1->loadList();
if(count($tar) === 0){
	$q3 = new DBQuery();
	$q3->addTable('admission_info');	
	$q3->setLimit(1);
	$q3->addQuery('ac.*');
	$q3->addWhere('admission_client_id='.$client_id);
	$q3->addJoin('admission_caregivers','ac','admission_caregiver_sec = ac.id');
	$q3->addWhere('ac.datesoff is null');
	$tar = $q3->loadList();
}
if(count($tar) == 1){
	$careInfo['secondary']=$tar[0];
	unset($tar);
}

if ($obj->social_id)
{
	$q = new DBQuery();
	$q->addTable("household_info");
	$q->addQuery("household_info.*");
	//$q->addWhere("household_info.household_social_id = " . $obj->social_id);
	$q->addWhere("household_info.household_client_id = \"" . $client_id.'"');
	$housemembers = $q->loadList();
}
if ($obj->social_id)
{
	$q = new DBQuery();
	$q->addTable("social_services");
	$q->addQuery("social_services.*");
	$q->addWhere("social_services.social_services_social_id = \"" . $obj->social_id.'"');
	$socialservices = $q->loadList();
}
//load client
$clientObj = new CClient();
if ($clientObj->load($obj->social_client_id))
{
	$ttl = "Details of Social Visit : " . $date_string;

}
else
{
   $ttl = "Details of Social Visit ";

}

$nhif=$clientObj->getParts('nhif');
$immun=$clientObj->getParts('immun');

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$death_date = intval($obj->social_death_date) ? new CDate($obj->social_death_date ) :  null;

$client_id = $client_id ? $client_id : $obj->social_client_id;
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
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clinics[$obj->social_clinic_id]);?>
         </td>
		</tr>
		 <tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "-" ;?>
			</td>
       </tr>
       <tr>
         <td align="left">1c.<?php echo $AppUI->_('Social Worker');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->social_staff_id]);?>
         </td>
       </tr>
		<tr>
         <td align="left">3a.<?php echo $AppUI->_('Type of Visit');?>:</td>

		<td align="left" class="hilite">
		<?php echo $visitTypes[$obj->social_visit_type]; ?>
		</td>
       </tr>
       <tr>
        <td align="left">3b.<?php echo $AppUI->_('Client Health');?>:</td>

		<td align="left" class="hilite">
		<?php echo $clientHealth[$obj->social_client_health]; ?>
		</td>
       </tr>
       <tr>
        <td align="left">4.<?php echo $AppUI->_('Client Status');?>:</td>

		<td align="left" class="hilite">
		<?php echo $statusTypes[$obj->social_client_status]; ?>
		</td>
       </tr>
       <tr>
        <td align="left">5a.<?php echo $AppUI->_('NHF #');?>:</td>
		<td align="left" class="hilite">
		<?php echo /*$boolTypes[/*$obj->social_nhf*/ $nhif['bool'] /*]*/; ?>
		</td>
       </tr>
 	<!--      <tr>
         <td align="left">...<?php echo $AppUI->_('If yes, #');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(/*@$obj->social_nhf_y*/$nhif['y']);?>
         </td>
	</tr> -->
       <tr>
         <td align="left">5b...<?php echo $AppUI->_('If no, why');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(/*@$obj->social_nhf_n*/$nhif['n']);?>
         </td>
		</tr>
		<tr>
        <td align="left">5c.<?php echo $AppUI->_('Immun Card. #');?>:</td>
		<td align="left" class="hilite">
		<?php echo /*$boolTypes[/*$obj->social_immun*/ $immun['bool'] /*]*/; ?>
		</td>
       </tr>
    <!--   <tr>
         <td align="left">...<?php echo $AppUI->_('If yes, #');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(/*@$obj->social_immun_y*/$immun['y']);?>
         </td>
    
    </tr>
 -->
       <tr>
         <td align="left">5d...<?php echo $AppUI->_('If no, why');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(/*@$obj->social_immun_n*/$immun['n']);?>
         </td>
		</tr>
       
       
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Life Events'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 		<tr>
         <td align="left">6.<?php echo $AppUI->_('Any Life Events');?>:</td>

		<td align="left" class="hilite">
		<?php echo $boolTypes[$obj->social_change]; ?>
		</td>
       </tr>
	 <tr>
         <td align="left">7a.<?php echo $AppUI->_('Death');?>:</td>
		 <td align="left" class="hilite"><?php echo $deathTypes[$obj->social_death]; ?>
	 </tr>
      <td align="left">7b...<?php echo $AppUI->_('Other');?></td>
	  <td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_death_notes);?></td>
	 </tr>

	 <tr>
         <td align="left">7c...<?php echo $AppUI->_('Date');?>:</td>
		 <td align="left" class="hilite">
		 <?php echo $death_date ? $death_date->format( $df ) : "-" ;?>
		 </td>
       <tr>
       <?php 
       	foreach ($careInfo as $cname => $cinfo) {
       		$briefName=substr($cname,0,3);
       		$opref='social_caregiver_'.$briefName.'_';
       		if(count($cinfo) === 0){
       			?>
       			<tr>
        			<td align="left" valign="top"><?php echo $AppUI->_(ucfirst($cname)." caregiver");?>:</td>
        			<td align="left" class="hilite">Absent</td>
	   			</tr>
       			<?php 
       			if(!is_null($obj->{$opref.'change'})){
       				?>
       				<tr>
						<td align="left">b...<?php echo $AppUI->_("Reason");?>:</td>
						<td align="left" class="hilite">
							<?php echo $caregiverChangeTypes[$obj->{$opref.'change'}]; ?>
						</td>
					</tr>
       				<?php
       				if($obj->{$opref.'change'} == 4){
       					?>
       					<tr>
							<td align="left">c...<?php echo $AppUI->_("Other");?>:</td>
							<td align="left" class="hilite">
            					<?php echo dPformSafe(@$obj->{$opref.'change_notes'});?>
							</td>
						</tr>       					
       					<?php 
       				}       				
       				       						
       			}
       		}else {
       ?>
	  <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in ".$cname." caregiver");?>:</td>
	   </tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Reason");?>:
		</td>
		<td align="left" class="hilite">
		<?php echo $caregiverChangeTypes[$obj->{$opref.'change'}]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Other");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->{$opref.'change_notes'});?>
		</td>
		</tr>
		<tr>
		<td align="left">
		d...<?php echo $AppUI->_("First Name");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['fname']);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		e...<?php echo $AppUI->_("Last Name");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['lname']);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		f...<?php echo $AppUI->_("Age");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['age']);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		g...<?php echo $AppUI->_("Health Status");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $caregiverHealthStatus[$cinfo['health_status']]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		h...<?php echo $AppUI->_("Relationship to Child");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['relationship']);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		i...<?php echo $AppUI->_("Education level");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $educationLevels[$cinfo['educ_level']]; ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		j...<?php echo $AppUI->_("Employment");?>:
		</td>
		<td align="left" class="hilite">
		 <?php echo $employmentTypes[$cinfo['employment']]; ?>

		</td>
		</tr>
		<!--  <tr>
		<td align="left">
		...<?php echo $AppUI->_("Income level");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo $incomeLevels[$obj->social_caregiver_income]; ?>
		</td>
		</tr>
		-->
		<tr>
		<td align="left">
		i...<?php echo $AppUI->_("ID #");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['idno']);?>
		</td>
		</tr>
		<tr>
		<td align="left">
		m...<?php echo $AppUI->_("Mobile #");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$cinfo['mobile']);?>
		</td>
		</tr>

	  <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in health of ".$cname." caregiver");?>:</td>
        </tr>
		<tr>
			<td align="left">
			9a...<?php echo $AppUI->_("Health");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $caregiverHealthChanges[$obj->{$opref.'health'}]; ?></td>
		</tr>
		<tr>
			<td align="left">
			9b...<?php echo $AppUI->_("Condition is hindrance on care for the child");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $boolTypesND[$obj->{$opref.'health_child_impact'}]; ?>
			</td>
		</tr>
		<?php 
       		}
       	}
		?>
	   <tr>
        <td align="left" valign="top" ><?php echo $AppUI->_("Change of Contacts");?>:</td>
        </tr>
		<tr>
			<td align="left">
			10a...<?php echo $AppUI->_("Mobile #");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->social_caregiver_mobile);?>
			</td>
		</tr>
		<tr>
			<td align="left" valign="top">
			10b...<?php echo $AppUI->_("physical address/landmarks");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->social_residence), 75,"<br />", true);?>
			</td>
		</tr>
<tr>
         <td align="left" nowrap valign="top">11.<?php echo $AppUI->_('Change in household composition');?>:</td>
		 <td align="left">
		 <table class="tbl">
		 <tr>
		 	<th><?php echo $AppUI->_('Name');?></th>
			<th><?php echo $AppUI->_('Year of Birth');?></th>
			<th><?php echo $AppUI->_('Gender');?></th>
			<th><?php echo $AppUI->_('Relationship to child');?></th>
			<!-- <th><?php echo $AppUI->_('If registered, Adm #');?></th>
 -->
			<th><?php echo $AppUI->_('Comments');?></th>
		 </tr>
		 <?php foreach ($housemembers as $housemember)
		 {
		 ?>
		 <tr>
			<td><?php echo $housemember["household_name"];?></td>
			<td><?php echo $housemember["household_yob"];?></td>
			<td><?php echo $genderTypes[$housemember["household_gender"]];?></td>
			<td><?php echo $housemember["household_relationship"];?></td>
			<!-- <td><?php echo $housemember["household_notes"];?></td>
 -->
			<td><?php echo $housemember["household_custom"];?></td>
		 </tr>
		 <?php } ?>
		 </table>
		 </td>
	  </tr>
	   <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Change in household income level");?>:</td>
        </tr>
		<tr>
			<td align="left">
			12a...<?php echo $AppUI->_("Change due to employment type of primary caregiver");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $boolTypes[$obj->social_caregiver_employment_change]; ?>
			</td>

		</tr>
		<tr>
			<td align="left">
			12b...<?php echo $AppUI->_("If yes, new employment");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $employmentTypes[$obj->social_caregiver_new_employment]; ?>
			</td>
		</tr>
		<tr>
			<td align="left">
			12b...<?php echo $AppUI->_("Other");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $obj->social_caregiver_new_employment_desc; ?>
			</td>
		</tr>
		<tr>
			<td align="left">
			12c...<?php echo $AppUI->_("New income range");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $incomeLevels[$obj->social_caregiver_income]; ?>
			</td>
		</tr>
	   <tr>
        <td align="left" valign="top"><?php echo $AppUI->_("Schooling");?>:</td>
        </tr>
		<tr>
			<td align="left">
			13a...<?php echo $AppUI->_("Attending school regularly");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $childSchoolStatus[$obj->social_school_attendance]; ?>

			</td>
		</tr>
		<tr>
			<td align="left">
			13b...<?php echo $AppUI->_("New school level");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $childSchoolLevels[$obj->social_school]; ?></td>
		</tr>
		<tr>
			<td align="left">
			13c...<?php echo $AppUI->_("Current class / form");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $obj->social_class_form; ?></td>
		</tr>
		<tr>
			<td align="left">
			13d...<?php echo $AppUI->_("If not attending, why");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $reasonsNotAttendingSchool[$obj->social_reason_not_attending]; ?></td>
		</tr>
				<tr>
			<td align="left">
			13e...<?php echo $AppUI->_("Not attending - Other");?>:
			</td>
			<td align="left" class="hilite">
			<?php echo $reasonsNotAttendingSchool[$obj->social_reason_not_attending_notes]; ?></td>
		</tr>

<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs supported'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
<tr>
         <td align="left" nowrap valign="top">14.<?php echo $AppUI->_('Needs Supported');?>:</td>
		 <td align="left">
		 <table class="tbl">
		 <tr>
		 	<th><?php echo $AppUI->_('Service');?></th>
			<th><?php echo $AppUI->_('Date');?></th>
			<th><?php echo $AppUI->_('Comments');?></th>
			<th><?php echo $AppUI->_('Value');?></th>
		 </tr>
		 <?php foreach ($socialservices as $socialservice)
		 {
			$service_date = new CDate( @$socialservice["social_services_date"] );
		 ?>
		 <tr>
			<td><?php echo $serviceTypes[$socialservice["social_services_service_id"]];?></td>
			<td><?php echo $service_date->format( $df );?></td>
			<td><?php echo $socialservice["social_services_notes"];?></td>
			<td><?php echo $socialservice["social_services_value"];?></td>
		 </tr>
		 <?php } ?>
		 </table>
		 </td>
	  </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs assessment'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	<tr>
			<td align="left" valign="top">15.<?php echo $AppUI->_('Any needs');?>:</td>
			<td align="left" class="hilite">
				<?php echo $boolTypes[$obj->social_any_needs]; ?>
			</td>
	</tr>
	 
      <tr>
			<td align="left" valign="top"><?php echo $AppUI->_('Permanency Planning');?>:</td>
			</tr>
			<tr>
			<td align="left">
			16a...<?php echo $AppUI->_("Relocation");?>:
			</td>
			<td align="left" class="hilite">
		<?php
		foreach ($relocation_options as $relocation_option)
		{
			     echo $relocationTypes[$relocation_option] . "<br/>";
		}
		 ?>
			</td>
			</tr>
			<tr>
			<td align="left">
			16b...<?php echo $AppUI->_("IGA");?>:
			</td>
			<td align="left" class="hilite">
					<?php
		foreach ($iga_options as $iga_option)
		{
			     echo $igaTypes[$iga_option] . "<br/>";
		}
		 ?>
			</td>

			</tr>
			<tr>
			<td align="left">
			16c...<?php echo $AppUI->_("Placement");?>:
			</td>
			<td align="left" class="hilite">
					<?php
		foreach ($placement_options as $placement_option)
		{
			     echo $placementTypes[$placement_option] . "<br/>";
		}
		 ?>
	           </td>
			</tr>
		  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>

			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_permanency_value);?></td>
	  </tr>
	  <tr>
			<td align="left">17.<?php echo $AppUI->_('Succession Planning');?>:</td>

			<td align="left" class="hilite" valign="top">
					<?php
		foreach ($succession_planning_options as $succession_planning_option)
		{
			     echo $successionPlanningTypes[$succession_planning_option] . "<br/>";
		}
		 ?>
		</td>
      </tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Value");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_succession_value);?>
		</td>
		</tr>
	  <tr>
			<td align="left">18.<?php echo $AppUI->_('Legal');?>:</td>
			<td align="left" class="hilite" valign="top">
					<?php
		foreach ($legal_options as $legal_option)
		{
			     echo $legalIssues[$legal_option] . "<br/>";
		}
		 ?>
		</td>
      </tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_("Value");?>:
		</td>
		<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->social_legal_value);?>
		</td>
		</tr>

	  <tr>
			<td align="left">19.<?php echo $AppUI->_('Nursing/Palliative Care');?>:</td>
			<td align="left" class="hilite" valign="top">
					<?php
		foreach ($nursing_options as $nursing_option)
		{
			     echo $nursingCareTypes[$nursing_option] . "<br/>";
		}
		 ?>
			</td>
      </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_nursing_value);?></td>
	  </tr>

	  <tr>
			<td align="left">20.<?php echo $AppUI->_('Transport');?>:</td>
			<td align="left" class="hilite" valign="top">
		<?php
		foreach ($transport_options as $transport_option)
		{
			     echo $transportNeeds[$transport_option] . "<br/>";
		}
		 ?>
		</td>
      </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_transport_value);?></td>
	  </tr>

		<tr>
         <td align="left">21.<?php echo $AppUI->_('Education');?>:</td>
			<td align="left" class="hilite">
		<?php
		foreach ($education_options as $education_option)
		{
			     echo $educationNeeds[$education_option] . "<br/>";
		}
		 ?>

		</td>
		</tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_education_value);?></td>
	  </tr>

		<tr>
         <td align="left">22.<?php echo $AppUI->_('Food');?>:</td>
		<td align="left" class="hilite">
		<?php
		foreach ($food_options as $food_option)
		{
			     echo $foodNeeds[$food_option] . "<br/>";
		}
		 ?>
		</td>
       </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_food_value);?></td>
	  </tr>

	   <tr>
         <td align="left">23.<?php echo $AppUI->_('Rent');?>:</td>
		<td align="left" class="hilite">
		<?php
		foreach ($rent_options as $rent_option)
		{
			     echo $rentNeeds[$rent_option] . "<br/>";
		}
		 ?>
		</td>
       </tr>
	  <tr>
		<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
		<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_rent_value);?></td>
	  </tr>

	   <tr>
         <td align="left">24.<?php echo $AppUI->_('Solidarity');?>:</td>
		<td align="left" class="hilite">
		<?php
		foreach ($solidarity_options as $solidarity_option)
		{
			     echo $solidarityNeeds[$solidarity_option] . "<br/>";
		}
		 ?>
		</td>
       </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_solidarity_value);?></td>
	  </tr>

	   <tr>
         <td align="left">25.<?php echo $AppUI->_('Direct Support');?>:</td>
		<td align="left" class="hilite">
		<?php
		foreach ($direct_support_options as $direct_support_option)
		{
			     echo $directSupportNeeds[$direct_support_option] . "<br/>";
		}
		 ?>
		</td>
       </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_directsupport_value);?></td>
	  </tr>
	  <tr>
			<td align="left">25b...<?php echo $AppUI->_("Other");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_direct_support_desc);?></td>
	  </tr>

	   <tr>
         <td align="left">26.<?php echo $AppUI->_('Medical Support');?>:</td>
		<td align="left" class="hilite">
		<?php
		foreach ($medical_support_options as $medical_support_option)
		{
			     echo $medicalSupportNeeds[$medical_support_option] . "<br/>";
		}
		 ?>
		</td>
		</tr>

		<tr>
		<td align="left">
		26b...<?php echo $AppUI->_('Other');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_medical_support_desc);?>
		</td>
       </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_medicalsupport_value);?></td>
	  </tr>
	   <tr>
         <td align="left">27.<?php echo $AppUI->_('Training Support');?>:</td>
		<td align="left" class="hilite">
		<?php
		$trvals = dPgetSysVal(TrainingSupport);
		foreach ($trainings as $trs){
			     echo $trvals[$trs] . "<br/>";
		}
		 ?>
		</td>
		</tr>
		<tr>
		<td align="left">
		...<?php echo $AppUI->_('Value');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_training_value);?>
		</td>
       </tr>
		<tr>
		<td align="left">
		27b...<?php echo $AppUI->_('Other');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_training_desc);?>
		</td>
       </tr>
       
	   <tr>
		<td align="left">28.
			<?php echo $AppUI->_('Other Needs Assessed');?>
		</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->social_other_support);?>
		</td>
       </tr>
	  <tr>
			<td align="left">...<?php echo $AppUI->_("Value");?>:</td>
			<td align="left" class="hilite"><?php echo dPformSafe(@$obj->social_othersupport_value);?></td>
	  </tr>

	   <tr>
         <td align="left">29a.<?php echo $AppUI->_('New Risk Level');?>:</td>
		<td align="left" class="hilite">
		<?php echo $riskLevels[$obj->social_risk_level]; ?>
		</td>
       </tr>
		<tr>
         <td align="left">29b.<?php echo $AppUI->_('Next Appointment Date');?>:</td>
		<td align="left" class="hilite">
		<?php $ndate=((int) $obj->social_next_visit > 0 ? new CDate($obj->social_next_visit) : null);
		    if($ndate){
			echo $ndate->format($df);
		    } ?>
		</td>
       </tr>
       <tr>
         <td align="left">30.<?php echo $AppUI->_('Referral To');?>:</td>
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
		 <td align="left" valign="top">31.<?php echo $AppUI->_('Comments');?>:</td>
		<td valign="top" class="hilite">
		<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->social_notes), 75,"<br />", true);?>
		</td>
		</tr>
     </table>
	</td>
</tr>
</table>
<?php } ?>
