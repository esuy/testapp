<?php /* MORTALITY INFO $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$mortality_id = intval( dPgetParam( $_GET, "mortality_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );
require_once ($AppUI->getModuleClass('clients'));
require_once ($AppUI->getModuleClass('counsellinginfo'));
require_once ($AppUI->getModuleClass('medical'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $mortality_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $mortality_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CMortality();
$canDelete = $obj->canDelete( $msg, $mortality_id );

// load the record data
$q  = new DBQuery;
$q->addTable('mortality_info');
$q->addQuery('mortality_info.*');
$q->addWhere('mortality_info.mortality_id = '.$mortality_id);
$sql = $q->prepare();
$q->clear();

if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Mortality Record.' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$boolTypes = dPgetSysVal('YesNo');
$ageTypes = dPgetSysVal('AgeType');
$genderTypes = dPgetSysVal('GenderType');

$df = $AppUI->getPref('SHDATEFORMAT');
$client_id = $client_id ? $client_id : $obj->mortality_client_id;

//load clinics
$q  = new DBQuery;
$q->addTable('clinics','c');
$q->addQuery('clinic_id');
$q->addQuery('clinic_name');
$q->addOrder('clinic_name');
$clinics = $q->loadHashList();
// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = "View Mortality Record : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Mortality Record ";

}
$client_dob = $clientObj->getDOB();
$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->mortality_entry_date) ? new CDate($obj->mortality_entry_date ) :  null;
$dob = intval($client_dob) ? new CDate($client_dob) :  null;
$mortality_date = intval($obj->mortality_date) ? new CDate($obj->mortality_date ) :  null;
$mortality_report_date = intval($obj->mortality_relative_report_date) ? new CDate($obj->mortality_relative_report_date ) :  null;
$mortality_admission_date = intval($obj->mortality_hospital_adm_date) ? new CDate($obj->mortality_hospital_adm_date ) :  null;
$mortality_clinical_report_date = intval($obj->mortality_clinical_officer_date) ? new CDate($obj->mortality_clinical_officer_date ) :  null;
//load intake and pcr
$counsellingObj = new CCounsellingInfo();
$q = new DBQuery();
$q->addTable("counselling_info");
$q->addQuery("counselling_info.*");
$q->addWhere("counselling_info.counselling_client_id = " . $client_id);

$sql = $q->prepare();

db_loadObject($sql, $counsellingObj);

//load medical assessment
$medicalObj = new CMedicalAssessment();
$q = new DBQuery();
$q->addTable("medical_assessment");
$q->addQuery("medical_assessment.*");
$q->addWhere("medical_assessment.medical_client_id = " . $client_id);

$sql = $q->prepare();

db_loadObject($sql, $medicalObj);
		
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id > 0)
{
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " . $clientObj->getFullName() );

}
if ($canEdit) {
	$titleBlock->addCrumb( "?m=mortality&a=addedit&mortality_id=$mortality_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete mortality record', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Mortality Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=mortality" method="post">
	<input type="hidden" name="dosql" value="do_mortality_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="mortality_id" value="<?php echo $mortality_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="75%">
		<table cellspacing="1" cellpadding="2">
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
		</tr>		
		 <tr>
			<td align="left" nowrap><?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
		   </tr>
	   <tr>
         <td align="left"><?php echo $AppUI->_('Center');?>:</td>
		 <td align="left" class="hilite">
				<?php echo $clinics[@$obj->mortality_clinic_id]; ?>        
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
		<td align="left"><?php echo $AppUI->_('Total Orphan?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->mortality_total_orphan]; ?></td>
     </tr>	  

     <tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $dob ? $dob->format($df) : "";?>
			
	  </tr> 
<tr>
         <td align="left"><?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->mortality_age_yrs);?>
		 </td>
</tr>
<tr>
         <td align="left"><?php echo $AppUI->_('Age (months)');?>:</td>
         
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->mortality_age_yrs);?>
		 </td>
		
	 </tr>
	<tr>
	<td>&nbsp;</td>
		<td align="left" class="hilite"><?php echo $ageTypes[$obj->mortality_age_status]; ?></td>		

	</tr>
	<tr>
         <td align="left"><?php echo $AppUI->_('Gender');?>:</td>
		 <td align="left" class="hilite"><?php echo $genderTypes[$obj->mortality_gender]; ?></td>

       </tr>	
	<tr>
		<td align="left"><?php echo $AppUI->_('Date of death');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $mortality_date ?  $mortality_date->format($df) : "-";?>&nbsp;</td>
     </tr>

       <tr>
         <td align="left"><?php echo $AppUI->_('Informant');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->mortality_informant);?>
         </td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Name of hospital attended');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->mortality_hospital);?>
         </td>
       </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Date of admission');?>:</td>
         <td align="left" valign="top" class="hilite"><?php echo $mortality_admission_date ?  $mortality_admission_date->format($df) : "-"  ;?>&nbsp;</td>
         </td>
       </tr>       
	   
	 	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Report from relative'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>  
       <tr>
         <td align="left"><?php echo $AppUI->_('Date of report');?>:</td>
         <td align="left" valign="top" class="hilite"><?php echo $mortality_report_date ?  $mortality_report_date->format($df) : "-"  ;?>&nbsp;</td>
         </td>
       </tr>       
       <tr>
         <td align="left"  valign="top"><?php echo $AppUI->_('Last illness');?>:</td>
	   </tr>
		   <tr>
		   <td align="left" valign="top">...<?php echo $AppUI->_('Symptoms');?>:</td>
		   <td align="left" class="hilite"><?php echo dPformSafe(nl2br(@$obj->mortality_symptoms));?></td>
		   </tr>
		   <tr>
		   <td align="left" valign="top">...<?php echo $AppUI->_('Time course');?>:</td>
		   <td align="left" class="hilite"><?php echo dPformSafe(nl2br(@$obj->mortality_time_course));?></td>
		   </tr>
		   <tr>
		   <td align="left" valign="top">...<?php echo $AppUI->_('Treatment');?>:</td>
		   <td align="left" class="hilite"><?php echo dPformSafe(nl2br(@$obj->mortality_treatment));?></td>
		   </tr>
	   <tr>
	 
	 	<td align="left" ><?php echo $AppUI->_('Was the child refered to hospital by LT clinic?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->mortality_hospital_referral]; ?></td>

     </tr>	
	 <tr>
	 	<td align="left" valign="top">...<?php echo $AppUI->_('If so, why?');?></td>
		<td align="left" class="hilite" valign="top">
		<?php echo dPformSafe(@$obj->mortality_referral);?>
		</td>
	 </tr>
 
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Report from the hospital'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	  
	 <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('Reason for admission');?>:</td>
		<td align="left" valign="top"  class="hilite">
		<?php echo dPformSafe(@$obj->mortality_hospital_adm_notes);?>
		</td>

	  </tr>		  
	 <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('Cause of death given');?>:</td>
		<td align="left"  class="hilite"><?php echo $boolTypes[$obj->mortality_cause_given]; ?></td>
	  </tr>		  
	<tr>
	    <td align="left" valign="top">...<?php echo $AppUI->_('If Yes, what?');?></td>
		<td align="left" valign="top" class="hilite">
		<?php echo dPformSafe(@$obj->mortality_cause_desc);?>
		</td>

	</tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Clinical Officer'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	  

	<tr>
         <td align="left"><?php echo $AppUI->_('Clinical Officer Name');?>:</td>
         <td align="left"  class="hilite">
          <?php echo dPformSafe(@$obj->mortality_clinical_officer);?>
         </td>
      </tr>	
       <tr>
         <td align="left"><?php echo $AppUI->_('Date of report');?>:</td>
         <td align="left" valign="top"  class="hilite"><?php echo $mortality_clinical_report_date ?  $mortality_clinical_report_date->format($df) : "-"   ;?>&nbsp;</td>
         </td>
       </tr>
	  <tr>
        <td align="left"><?php echo $AppUI->_("Is the postmortem arranged");?>:</td>
		<td align="left" valign="top"  class="hilite"><?php echo $boolTypes[$obj->mortality_postmortem]; ?></td>
		</tr>  
        </td>
      </tr>

	<tr>
	    <td align="left" valign="top">...<?php echo $AppUI->_('If Yes, cause of death from PM?');?></td>
		<td align="left" valign="top"  class="hilite">
		<?php echo dPformSafe(@$obj->mortality_cause_pm);?>
		</td>

	</tr>
      <tr>
			<td align="left">...<?php echo $AppUI->_('If N, likely causes of death? ');?>:</td>

			<td align="left" valign="top"  class="hilite">
				<?php echo dPformSafe(@$obj->mortality_likely_cause);?>
			</td>
      </tr>
     
	  <tr>
			<td align="left">...<?php echo $AppUI->_('Other factors');?>:</td>
			<td align="left" valign="top"  class="hilite">
				<?php echo dPformSafe(@$obj->mortality_notes);?>
			</td>
      </tr>
	
    
	  
		</table>
		</td>
    </tr>	
	 
	</table>

	</td>
	  
	
</tr>
</table>


