<?php
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );
$client_id = intval (dPgetParam($_REQUEST, 'client_id', 0));
require_once ($AppUI->getModuleClass('clients'));

// check permissions for this company
$perms =& $AppUI->acl();
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($counselling_id)
  $canEdit = $perms->checkModuleItem($m, "edit", $counselling_id);
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// load the company types
$types = dPgetSysVal( 'CompanyType' );

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_visit');
$q->addQuery('counselling_visit.*');
$q->addWhere('counselling_visit.counselling_id = '.$counselling_id);
$sql = $q->prepare();
//var_dump($sql);
$q->clear();

$obj = new CCounsellingVisit();
if (!db_loadObject( $sql, $obj ) && $counselling_id > 0)
{
	// $AppUI->setMsg( '	$qid =& $q->exec(); Company' ); // What is this for?
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}


// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();

//load clinics
$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
//$clinics = $q->loadHashList();
$clinics = arrayMerge(array(0=> '-Select Center -'),$q->loadHashList());

$boolTypes = dPgetSysVal('YesNo');
$visitTypes = dPgetSysVal('VisitType');
$maritalStatus = dPgetSysVal('MaritalStatus');
$educationLevels = dPgetSysVal('EducationLevel');
$employmentTypes = dPgetSysVal('EmploymentType');
$incomeLevels = dPgetSysVal('IncomeLevels');


$healthIssues = tailFirst(dPgetSysVal('ChildHealthIssues'));
$caregiverIssues = tailFirst(dPgetSysVal('CaregiverHealthIssues'));


$disclosureStatus = dPgetSysVal('DisclosureStatus');
$disclosureResponse = dPgetSysVal('DisclosureResponse');
$disclosureProcess = dPgetSysVal('DisclosureProcessStatus');
$hivTreatmentStatus = dPgetSysVal('HIVTreatmentOptions');
$serviceOptions = dPgetSysVal('ServiceOptions');
$stigmatizationConcern = dPgetSysVal('StigmatizationOptions');
$hivAdultChildOptions = dPgetSysVal('HivAdultChildOptions');
$hivCaregiverChildOptions = dPgetSysVal('HivCaregiverChildOptions');
$hivCaregiverOptions = dPgetSysVal('HIVCaregiverOptions');
$hivCaregiverOptions2 = arrayMerge(array(0=>'Select Status'), $hivCaregiverOptions);
$hivPrimaryCaregiverOptions = dPgetSysVal('HIVPrimaryCaregiverOptions');
$hivPrimaryCaregiverOptions = arrayMerge(array(0=>'Select Status'), $hivPrimaryCaregiverOptions);

$hivmodStatus = arrayMerge(array(0=>'Select Status'), dPgetSysVal('HIVStatusTypes'));
$secondIdent = dPgetSysVal('SecondIdentified');

$child_issues = explode(",", $obj->counselling_child_issues);
$caregiver_issues = explode(",", $obj->counselling_caregiver_issues);
$caregiver_issues2 = explode(",", $obj->counselling_caregiver_issues2);
$counselling_services = explode( ",", $obj->counselling_counselling_services);

$childHiv = dPgetSysVal('ChildHivAware');
//$referer = dPgetSysVal('CounsellingReferer');
$referer = arrayMerge(array(0=>'--Select Position--'),  dPgetSysVal('PositionOptions'));

// setup the title block
$date_reg = date("Y-m-d");
$entry_date = intval( $obj->counselling_entry_date) ? new CDate( $obj->counselling_entry_date ) : new CDate($date_reg);
$next_date = intval( $obj->counselling_next_visit) ? new CDate( $obj->counselling_next_visit ) : '';
$df = $AppUI->getPref('SHDATEFORMAT');
//load client

if (!empty($client_id))
{
   $clientObj = new CClient();

   if (!$clientObj->load($client_id))
   {
		$AppUI->setMsg('Client ID');
		$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
		$AppUI->redirect("?m=clients");
   }
   $client_name =  $clientObj->getFullName();
}

$client_id = $client_id ? $client_id : $obj->counselling_client_id;

$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = $counselling_id > 0 ? "Edit Counselling Visit : " . $clientObj->getFullName() : "New Counselling Visit: " . $clientObj->getFullName();

}
else
{
   $ttl = $counselling_id > 0 ? "Edit Counselling Visit " : "New Counselling Visit ";

}

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
$titleBlock->addCrumbRight2( "javascript:clearSelection(document.forms['changeCounselling'])", "Clear All Selections" );
if ($client_id > 0)
{
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", $clientObj->getFullName() );
}
/*
if ($counselling_id != 0)
  $titleBlock->addCrumb( "?m=counselling&a=view&counselling_id=$counselling_id", "View" );
 */

$titleBlock->show();
?>

<script language="javascript">
function submitIt() {
	var form = document.changeCounselling ;
	if(!manField("staff_id")){                                                                                                                           
		alert("Please select Staff!");                                                                                                                   
		return false;                                                                                                                                    
	}                                                                                                                                                    
	if(!manField("clinic_id")){                                                                                                                          
	    alert("Please select Center!");                                                                                                                  
	    return false;                                                                                                                                    
	}  
	if (form.counselling_entry_date && form.counselling_entry_date.value.length > 0)
	{
		errormsg = checkValidDate(form.counselling_entry_date.value);

		if (errormsg.length > 1)
		{
			alert("Invalid entry date" );
			form.counselling_entry_date.focus();
			exit;
		}
	}

	if (form.counselling_caregiver_age && form.counselling_caregiver_age.value.length > 0)
	{
		if (isNaN(parseInt(form.counselling_caregiver_age.value,10)) )
		{
			alert(" Invalid Caregiver Age");
			form.counselling_caregiver_age.focus();
			exit;

		}
	}

	form.submit();
}
function noneActive(){
	var cevnt="change",ztype='input[type!="text"]';
	if($j.browser.msie){
		cevnt="click";
	}
	$j('.multinone')
	.find("input:first").each(function(){
			var name=$j(this).attr("name"),
				val=$j(this).val(),
				state = ($j(this).is(":checked") === true ? "checked" : '');			
			
			$j(this).replaceWith("<input type='radio' name='"+ name +"' value='"+val+"' " + state + ">")
		}).end()	
	.delegate(ztype,cevnt,function(){
		var $part=$j(this).parent().find(ztype), state = $j(this).is(":checked");
		if($part.index(this) == 0){
			if(state === true){
				$part.nextAll().attr("checked",false);
				$j(this).parent().parent().next().find("input[type='text']").val('');
			}
		}else{
			$part.eq(0).attr("checked",false);
		}
	});

}

</script>

<form name="changeCounselling" action="?m=counselling" method="post">
	<input type="hidden" name="dosql" value="do_counselling_aed" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
	<input type="hidden" name="counselling_client_id" value="<?php echo $client_id;?>" />
	<input type="hidden" name="counselling_staff_id" value="<?php echo $counsellor_id;?>" />
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">
<tr>
<td valign="top" width="75%">
   <table border="0" cellpadding = "1" cellspacing="1">
    <tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left">
		 <?php echo arraySelect($clinics, "counselling_center_id", 'class="text" id="clinic_id"', $obj->counselling_center_id ? $obj->counselling_center_id : -1); ?>
         </td>
		 </tr>
		 <tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left">
			<?php
				echo drawDateCalendar("counselling_entry_date",$entry_date ? $entry_date->format( $df ) : "",false);
				//<input type="text" name="counselling_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;" class="text"  />
			?>
							&nbsp;<label>Force LVD update&nbsp;<input type="checkbox" name="force_lvd_update"></label>
			</td>
       </tr>
       <tr>
         <td align="left">1c.<?php echo $AppUI->_('Counsellor');?>:</td>
		 <td align="left">
				<?php echo arraySelect( $owners, 'counselling_staff_id', 'size="1" class="text" id="staff_id"', @$obj->counselling_staff_id ? $obj->counselling_staff_id:0); ?>
		</td>
        </tr>
	   
      <tr>
	<tr>
         <td align="left">2a.<?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left">
          <input type="text" class="text" name="counselling_client_code" value="<?php echo dPformSafe(@$clientObj->client_adm_no);?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>
	 <tr>
         <td align="left">2b,2c.<?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left">
		    <input type="text" class="text" name="counselling_client_name" value="<?php echo dPformSafe(@$clientObj->getFullName());?>" maxlength="150" size="20" disabled  readonly="readonly" />
         </td>
       </tr>      
      <tr>
         <td align="left">3.<?php echo $AppUI->_('Type of visit');?>:</td>
		<td align="left"><?php echo arraySelectRadio($visitTypes, "counselling_visit_type", 'onclick=toggleButtons()', $obj->counselling_visit_type ? $obj->counselling_visit_type : -1, $identifiers ); ?></td>
		 </td>
	 </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Mental and Health Issues'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	</tr>
	<tr>
		<td align="left" valign = "top">4.<?php echo $AppUI->_('Issues facing child includes');?>:</td>
		 <td class="multinone">
			<?php echo arraySelectCheckbox($healthIssues, "counselling_child_issues[]", '', $child_issues,$identifiers ); ?>
		  </td>
	</tr>
	<tr>
		 <td align="left">4j...<?php echo $AppUI->_('Other:specify');?>:</td>
		  <td align="left">
		    <input type="text" class="text" name="counselling_other_issues" value="<?php echo dPformSafe(@$obj->counselling_other_issues);?>" maxlength="150" size="40"  />
		  </td>
	</tr>
	 <tr>
		<td align="left" valign = "top">5.<?php echo $AppUI->_("Mother or Father's personal health history includes");?>:</td>
		  <td class="multinone">
			<?php echo arraySelectCheckbox($caregiverIssues, "counselling_caregiver_issues[]", '', $caregiver_issues , $identifiers ); ?>
		  </td>
	</tr>
	<tr>
		 <td align="left">5f...<?php echo $AppUI->_('Other:specify');?>:</td>
		  <td align="left">
		    <input type="text" class="text" name="counselling_caregiver_other_issues" value="<?php echo dPformSafe(@$obj->counselling_caregiver_other_issues);?>" maxlength="150" size="40" />
		  </td>
	</tr>

	<tr>
		<td align="left" valign = "top">6.<?php echo $AppUI->_("Other primary caregiver's history includes");?>:</td>

		  <td class="multinone">
			<?php echo arraySelectCheckbox($caregiverIssues, "counselling_caregiver_issues2[]", '', $caregiver_issues2  ); ?>
		  </td>
	</tr>
	<tr>
		 <td align="left">6f...<?php echo $AppUI->_('Other:specify');?>:</td>
		  <td>
		    <input type="text" class="text" name="counselling_caregiver_other_issues2" value="<?php echo dPformSafe(@$obj->counselling_caregiver_other_issues2);?>" maxlength="150" size="40" />
		  </td>
	</tr>
	</table>
	<table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Disclosure Status'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left">7.<?php echo $AppUI->_('Does child know his / her HIV status');?>:</td>
		<td align="left"><?php echo arraySelectRadio(/*$disclosureStatus*/ $childHiv, "counselling_child_knows_status", 'onclick=toggleButtons()', $obj->counselling_child_knows_status ? $obj->counselling_child_knows_status : -1, $identifiers ); ?>
		&nbsp;&nbsp;<b>Old value</b><?php echo @$disclosureStatus[$obj->counselling_child_knows_status]; ?>
		</td>
     </tr>
	 <tr>
		<td align="left">8.<?php echo $AppUI->_("Apart from primary caregiver, do any other close adult know child's HIV status");?>:</td>
		<td align="left"><?php echo arraySelectRadio($hivAdultChildOptions, "counselling_otheradult_knows_status", 'onclick=toggleButtons()', $obj->counselling_otheradult_knows_status ? $obj->counselling_otheradult_knows_status : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left">9.<?php echo $AppUI->_("If new disclosure has occurred since last counselling, describe response");?>:</td>
		<td align="left"><?php echo arraySelectRadio($disclosureResponse, "counselling_disclosure_response", 'onclick=toggleButtons()', $obj->counselling_disclosure_response ? $obj->counselling_disclosure_response : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top">10.<?php echo $AppUI->_("If no other adults know child's status, describe state of disclosure process");?>:</td>
		<td align="left"><?php echo arraySelectRadio($disclosureProcess, "counselling_disclosure_state", 'onclick=toggleButtons()', $obj->counselling_disclosure_state ? $obj->counselling_disclosure_state : -1, $identifiers, false, true ); ?></td>
     </tr>
	 <tr>
		<td align="left">11.<?php echo $AppUI->_("Does child's secondary caregiver know child's HIV status?");?>:</td>
		<td align="left"><?php echo arraySelectRadio($hivCaregiverChildOptions, "counselling_secondary_caregiver_knows", 'onclick=toggleButtons()', $obj->counselling_secondary_caregiver_knows ? $obj->counselling_secondary_caregiver_knows : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left">12.<?php echo $AppUI->_("Has child's primary caregiver been tested for HIV?");?>:</td>
		<td align="left"><?php echo arraySelectRadio($hivCaregiverOptions, "counselling_primary_caregiver_tested", 'onclick=toggleButtons()', $obj->counselling_primary_caregiver_tested ? $obj->counselling_primary_caregiver_tested : -1, $identifiers ); ?></td>
     </tr>
 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('If yes what is their HIV status');?>:</td>
		<td align="left">
         <table>
		 <tr>
		 <td align="left">13a.<?php echo $AppUI->_('Father');?>:</td>
		  <td>
		  <?php echo arraySelect( /*$hivPrimaryCaregiverOptions*/ $hivmodStatus, 'counselling_father_status', 'size="1" class="text"', @$obj->counselling_father_status ? $obj->counselling_father_status:0); ?>
		  </td>
		 <tr>
		 <tr>
		 <td align="left">13b.<?php echo $AppUI->_('Mother');?>:</td>
		  <td>
		  <?php echo arraySelect( /*$hivPrimaryCaregiverOptions*/ $hivmodStatus, 'counselling_mother_status', 'size="1" class="text"', @$obj->counselling_mother_status ? $obj->counselling_mother_status:0); ?>
		  </td>
		 <tr>
		 <tr>
		 <td align="left">13c.<?php echo $AppUI->_('Caregiver');?>:</td>
		  <td>
		  	<?php echo arraySelect( /*$hivPrimaryCaregiverOptions*/ $hivmodStatus, 'counselling_caregiver_status', 'size="1" class="text"', @$obj->counselling_caregiver_status ? $obj->counselling_caregiver_status:0); ?>
		  </td>
		 <tr>
		</table>
		</td>
     </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('If positive, is s/he receiving medical treatment?  ');?>:</td>
		<td align="left">

		 <tr>
		 <td align="left">14a.<?php echo $AppUI->_('Father');?>:</td>

		<td align="left"><?php echo arraySelectRadio($hivTreatmentStatus, "counselling_father_treatment", 'onclick=toggleButtons()', $obj->counselling_father_treatment ? $obj->counselling_father_treatment : -1, $identifiers ); ?></td>
 		  </td>
		 </tr>
		 <tr>
		 <td align="left">14b.<?php echo $AppUI->_('Mother');?>:</td>

		<td align="left"><?php echo arraySelectRadio($hivTreatmentStatus, "counselling_mother_treatment", 'onclick=toggleButtons()', $obj->counselling_mother_treatment ? $obj->counselling_mother_treatment : -1, $identifiers ); ?></td>
 		  </td>
		 </tr>
		 <tr>
		 <td align="left">14c.<?php echo $AppUI->_('Caregiver');?>:</td>

		<td align="left"><?php echo arraySelectRadio($hivTreatmentStatus, "counselling_caregiver_treatment", 'onclick=toggleButtons()', $obj->counselling_caregiver_treatment ? $obj->counselling_caregiver_treatment : -1, $identifiers ); ?></td>
 		  </td>
		 </tr>

	 <tr>
		<td align="left">15.<?php echo $AppUI->_("To what degree is HIV related stigmatization or discrimination a concern for this family?");?>:</td>
		<td align="left"><?php echo arraySelectRadio($stigmatizationConcern, "counselling_stigmatization_concern", 'onclick=toggleButtons()', $obj->counselling_stigmatization_concern ? $obj->counselling_stigmatization_concern : -1, $identifiers ); ?></td>
     </tr>
      <tr>
		<td align="left">16.<?php echo $AppUI->_("Has Secondary Caregiver been identified");?>:</td>
		<td align="left"><?php echo arraySelectRadio($secondIdent, "counselling_second_ident", 'onclick=toggleButtons()', $obj->counselling_second_ident ? $obj->counselling_second_ident : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top">17.<?php echo $AppUI->_("Services Offered");?>:</td>
		<td align="left"><?php echo arraySelectCheckbox($serviceOptions, "counselling_counselling_services[]", NULL, $counselling_services ); ?>        </td>
     </tr>

	<tr>
		 <td align="left">17f...<?php echo $AppUI->_('Other:specify');?>:</td>
		  <td>
		    <input type="text" class="text" name="counselling_other_services" value="<?php echo dPformSafe(@$obj->counselling_other_services);?>" maxlength="150" size="40" />
		  </td>
		 <tr>
	<tr>
		<td align="left" valign="top">18.<?php echo $AppUI->_("Refer to");?>:</td>
		<td align="left"><?php echo arraySelect($referer, "counselling_referer",'class="text"',$obj->counselling_referer ? $obj->counselling_referer : -1,$identifiers ); ?>        </td>
     </tr>
     <tr>
		<td align="left" valign="top">18...<?php echo $AppUI->_("Other");?>:</td>
		<td align="left"><input type="text" class="text" value="<?php echo $obj->counselling_referer_other;?>" name="counselling_referer_other" maxlength="150" size="20"></td>
     </tr>
     <tr>
		<td align="left" valign="top">19.<?php echo $AppUI->_("Next Appointment Date");?>:</td>
		<td align="left"><?php echo drawDateCalendar('counselling_next_visit',$next_date ? $next_date->format( $df ) : ""); ?>        </td>
     </tr>
	<tr>
	 <tr>
	 	<td align="left" valign="top">20.<?php echo $AppUI->_("Counsellor's overall assessments");?>:</td>
		<td align="left" valign="top">
		<textarea cols="70" rows="2" class="textarea" name="counselling_notes"><?php echo dPformSafe(@$obj->counselling_notes);?></textarea>
		</td>
	 </tr>

	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
    </tr>
	<tr>
	    <td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
	    <td align="right"><input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="submitIt()" /></td>
    </tr>
   </table>

</td>
</tr>
</table>
</form>
<script>
window.onload = noneActive;
</script>
