<?php /* COUNSELLING VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $counselling_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $counselling_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCounsellingVisit();
$canDelete = $obj->canDelete( $msg, $counselling_id );

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_visit');
$q->addQuery('counselling_visit.*');
$q->addWhere('counselling_visit.counselling_id = '.$counselling_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Counselling Visit' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$boolTypes = dPgetSysVal('YesNo');
$visitTypes = dPgetSysVal('VisitType');
$maritalStatus = dPgetSysVal('MaritalStatus');
$educationLevels = dPgetSysVal('EducationLevel');
$employmentTypes = dPgetSysVal('EmploymentType');
$incomeLevels = dPgetSysVal('IncomeLevels');
$healthIssues = dPgetSysVal('ChildHealthIssues');
$caregiverIssues = dPgetSysVal('CaregiverHealthIssues');
$disclosureStatus = dPgetSysVal('DisclosureStatus');
$disclosureResponse = dPgetSysVal('DisclosureResponse');
$disclosureProcess = dPgetSysVal('DisclosureProcessStatus');
$hivTreatmentStatus = dPgetSysVal('HIVTreatmentOptions');
$serviceOptions = dPgetSysVal('ServiceOptions');
$stigmatizationConcern = dPgetSysVal('StigmatizationOptions');
$hivAdultChildOptions = dPgetSysVal('HivAdultChildOptions');
$hivCaregiverChildOptions = dPgetSysVal('HivCaregiverChildOptions');
$hivCaregiverOptions = dPgetSysVal('HivCaregiverOptions');

$df = $AppUI->getPref('SHDATEFORMAT');

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


//load clinics
$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();
// setup the title block

//load client
$clientObj = new CClient();

if ($clientObj->load($obj->counselling_client_id))
{
	$ttl = "View Counselling Visit : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Counselling Visit ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->counselling_entry_date) ? new CDate($obj->counselling_entry_date ) :  null;
$mother_status_date = intval($obj->counselling_date_mothers_status_known) ? new CDate($obj->counselling_date_mothers_status_known ) :  null;

$child_issues = explode(",", $obj->counselling_child_issues);
$caregiver_issues = explode(",", $obj->counselling_caregiver_issues);
$caregiver_issues2 = explode(",", $obj->counselling_caregiver_issues2);
$counselling_services = explode( ",", $obj->counselling_counselling_services);


		
		
		
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new counselling visit record').'" />', '',
		'<form action="?m=counselling&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=clients", "Clients" );


$titleBlock->addCrumb( "?m=clients&a=view&client_id=$clientObj->client_id", "view " . $clientObj->getFullName() );
if ($canEdit) {
	$titleBlock->addCrumb( "?m=counselling&a=addedit&counselling_id=$counselling_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete counselling record', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Counselling Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="75%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=counselling" method="post">
	<input type="hidden" name="dosql" value="do_counselling_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="100%" >
	<table cellspacing="1" cellpadding="2">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
    <tr>
         <td align="left"><?php echo $AppUI->_('Counsellor');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->counselling_staff_id]);?>
         </td>
     </tr>    
	 <tr>
         <td align="left"><?php echo $AppUI->_('Center');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clinics[$obj->counselling_center_id]);?>
         </td>
	</tr>
	<tr>
		 <td align="left"><?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
     </tr>
	 <tr>
         <td align="left"><?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$clientObj->getFullName());?>
         </td>
     </tr>
   <tr>
         <td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clientObj->client_adm_no);?>
         </td>
     </tr>
     <tr>
         <td align="left"><?php echo $AppUI->_('Type of visit');?>:</td>
		 <td align="left" class="hilite">&nbsp;&nbsp;<?php echo $visitTypes[$obj->counselling_visit_type]?></td>
	 </tr>
	 <tr>
		<td align="left">
		<?php echo $AppUI->_('Secondary Caregiver');?><br/>
		<?php echo $AppUI->_('Information');?>:
		</td>
	  </tr>	 
	 <tr>
         <td align="left">...<?php echo $AppUI->_('First Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_fname);?>
         </td>
       </tr>
		<tr>
         <td align="left">...<?php echo $AppUI->_('Last Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_lname);?>
         </td>
       </tr>
	 <tr>
         <td align="left">...<?php echo $AppUI->_('Age');?>:</td>
         <td  align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_age);?>
         </td>
       </tr>	   
		<tr>
         <td align="left">...<?php echo $AppUI->_('Relationship to child');?>:</td>
		<td align="left" class="hilite">
		<?php echo $obj->counselling_caregiver_relationship;?>
		</td>
     </tr>
		<tr>
         <td align="left">...<?php echo $AppUI->_('Marital status');?>:</td>

		<td class="hilite">
		<?php echo $maritalTypes[$obj->counselling_caregiver_marital_status]?>
		</td>
     </tr>
		<tr>
         <td align="left">...<?php echo $AppUI->_('Education level');?>:</td>

		<td class="hilite">
		<?php echo $educationLevels[$obj->counselling_caregiver_educ_level];?>
		</td>
     </tr>
		<tr>
         <td align="left">...<?php echo $AppUI->_('Employment');?>:</td>

		<td class="hilite">
		<?php echo $employmentTypes[$obj->counselling_caregiver_employment];?>
		</td>
     </tr>
		<tr>
         <td align="left">...<?php echo $AppUI->_('Income Level');?>:</td>

		<td class="hilite">
		<?php echo $incomeLevels[$obj->counselling_caregiver_income_level]?>
		</td>
     </tr>
	 <tr>
         <td align="left">...<?php echo $AppUI->_('ID #');?>:</td>
         <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_idno);?>
         </td>
       </tr>
	 <tr>
         <td align="left">...<?php echo $AppUI->_('Mobile #');?>:</td>
         <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_mobile);?>
         </td>
       </tr>
	 <tr>
         <td align="left">...<?php echo $AppUI->_('Residence');?>:</td>
         <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_residence);?>
         </td>
       </tr>
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Mental and Health Issues'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	</tr>  
	<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Issues facing child includes');?>:</td>
		  <td align="left" class="hilite">
			<?php 
			foreach ($child_issues as $child_issue)
			{
			     echo $healthIssues[$child_issue] . "<br/>";
			}
			?>	
		  </td>
		 </tr>
<tr>
		 <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		 <td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_other_issues);?>
		  </td>
		 </tr>	
		<tr>
		<td align="left" valign="top">
		<?php echo $AppUI->_("Mother or Father's");?><br/>
		<?php echo $AppUI->_("personal health history includes");?>:
		</td>
		 <td align="left" class="hilite">
			<?php 
			foreach ($caregiver_issues as $caregiver_issue)
			{
			     echo $caregiverIssues[$caregiver_issue] . "<br/>";
			}
			?>	
		  </td>
		 </tr>		
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		  <td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_other_issues);?>
		  </td>
		 </tr>
		<tr>
		<td align="left" valign="top">
		<?php echo $AppUI->_("Other primary caregiver's");?><br/>
		<?php echo $AppUI->_("history includes");?>:
		</td>
		  <td class="hilite">
			<?php 
			foreach ($caregiver_issues2 as $caregiver_issue2)
			{
			     echo $caregiverIssues[$caregiver_issue2] . "<br/>";
			}
			?>	
		  </td>
		 </tr>
 <tr>
		 <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		  <td align = "left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_other_issues2);?>
		  </td>
		 </tr>
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Disclosure Status'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
 <tr>
		<td align="left" nowrap="nowrap"><?php echo $AppUI->_('Does child know his / her HIV status');?>:</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureStatus[$obj->counselling_child_knows_status]?></td>
     </tr>
	 <tr>
		<td align="left">
		<?php echo $AppUI->_("Apart from primary caregiver, do any other");?><br/>
		<?php echo $AppUI->_("close adult know child's HIV status");?>:
		
		</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $hivAdultChildOptions[$obj->counselling_otheradult_knows_status]?></td>
     </tr>	 
	 <tr>
		<td align="left">
		<?php echo $AppUI->_("If new disclosure has occurred since");?><br/>
		<?php echo $AppUI->_("last counselling, describe response");?>:</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureResponse[$obj->counselling_disclosure_response]?></td>
     </tr>	 
	 <tr>
		<td align="left">
		<?php echo $AppUI->_("If no other adults know child's status,");?><br/>
		<?php echo $AppUI->_("describe state of disclosure process");?>:</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureProcess[$obj->counselling_disclosure_state]?></td>
     </tr>	 
	 <tr>
		<td align="left">
		<?php echo $AppUI->_("Does child's secondary caregiver");?><br/>
		<?php echo $AppUI->_("know child's HIV status?");?>:
		</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $hivCaregiverChildOptions[$obj->counselling_secondary_caregiver_knows];?></td>
     </tr>	 
	 <tr>
		<td align="left" nowrap="nowrap"><?php echo $AppUI->_("Has child's primary caregiver been tested for HIV?");?>:</td>
		<td class="hilite">&nbsp;&nbsp;<?php echo $hivCaregiverOptions[$obj->counselling_primary_caregiver_tested]?></td>
     </tr>	
<tr>
		<td align="left" valign="top" nowrap="nowrap"><?php echo $AppUI->_('If yes what is their HIV status');?>:</td>
		<td align="left">&nbsp;</td>
	</tr>	
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Father');?>:</td>
		  <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_father_status);?>
		  </td>
		 </tr>
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Mother');?>:</td>
		  <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_mother_status);?>
		  </td>
		 </tr>
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Caregiver');?>:</td>
		  <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_status);?>
		  </td>
		 </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('If positive, is s/he receiving medical treatment?  ');?>:</td>
		<td align="left">&nbsp;</td>
	 </tr>	
	 <tr>
		 <td align="left">...<?php echo $AppUI->_('Father');?>:</td>
		<td align="left" class="hilite"><?php echo $hivTreatmentStatus[$obj->counselling_father_treatment]?></td>
	  </tr>
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Mother');?>:</td>

		<td align="left" class="hilite"><?php echo $hivTreatmentStatus[$obj->counselling_mother_treatment]?></td>
		 </tr>
		 <tr>
		 <td align="left">...<?php echo $AppUI->_('Caregiver');?>:</td>

		<td align="left" class="hilite"><?php echo $hivTreatmentStatus[$obj->counselling_caregiver_treatment]?></td>

		 </tr>

	 <tr>
		<td align="left">
		<?php echo $AppUI->_("To what degree is HIV related stigmatization");?><br/>
		<?php echo $AppUI->_("discrimination a concern for this family?");?>:</td>
		<td align="left" class="hilite"><?php echo $stigmatizationConcern[$obj->counselling_stigmatization_concern]?></td>
     </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_("Services Offered");?>:</td>
		<td align="left" class="hilite">
		<?php 
		foreach ($counselling_services as $counselling_service)
			{
			     echo $serviceOptions[$counselling_service] . "<br/>";
			}
		 ?>
		 </td>
     </tr>
	 <tr>	
		 <td align="left">...<?php echo $AppUI->_('Other');?>:</td>
		  <td class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_other_services);?>
		  </td>
		 </tr>
	 <tr>
	 	<td align="left" valign="top"><?php echo $AppUI->_("Counsellor's overall assessments");?>:</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->counselling_notes);?>
		</td>
	 </tr>

	 
	 </table>
	
	
	</td>
	  

</tr>
</table>


