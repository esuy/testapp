<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('counselling');
require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');
require_once $AppUI->getModuleClass('followup');

function sds($a, $b){
	 if ($a['sort_date'] === $b['sort_date']) {
        return 0;
    }
    return ($a['sort_date'] < $b['sort_date']) ? -1 : 1;
}

$title = 'new counselling visit...';
$visitTypes = dPgetSysVal('VisitType');
$q = new DBQuery;
$q->addTable('counselling_visit');
$q->addQuery ('counselling_id as row_id,counselling_entry_date as row_date,counselling_staff_id as row_officer,"visit" as category,counselling_visit_type as visit_type');
$q->addWhere('counselling_visit.counselling_client_id = '.$client_id);
$q->addOrder('row_date desc');
$q->addQuery('unix_timestamp(counselling_entry_date) as sort_date');
$w ='';
$df = $AppUI->getPref('SHDATEFORMAT');
$sql= $q->prepare();
//print_r($sql);


//find adm_no
$q1 = new DBQuery();
$q1->addTable('clients');
$q1->addQuery('client_adm_no');
$q1->addWhere('client_id='.$client_id);
$q1->setLimit(1);
$cadm=$q1->loadResult();

$q1= new DBQuery();
$q1->addTable('followup_info');
$q1->addQuery('followup_id as row_id,followup_date as row_date, unix_timestamp(followup_date) as sort_date,followup_officer_id as row_officer,followup_visit_type as visit_type,"follow up" as category ');
$q1->addWhere('followup_adm_no="'.$cadm.'"');
$q1->addOrder('followup_date asc');
$follws= $q1->loadList();

$visitTypes = dPgetSysVal('FollowVisitType');
$servTypes = dPgetSysVal('FollowServices');

// collect all the users for the staff list
$q1  = new DBQuery;
$q1->addTable('contacts','con');
$q1->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q1->addQuery('contact_id');
$q1->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q1->addOrder('contact_last_name');
$owners = $q1->loadHashList();

$vModes= dPgetSysVal('VisitMode');

if(count($follws) > 0) 
	prepareIssue(true);
else $follws=array();
	/*?>
<b>Follow-ups</b>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Date' );?></th>
	<th><?php echo $AppUI->_( 'Counsellor' );?></th>	
	<th><?php echo $AppUI->_( 'Category' );?></th>
	<th><?php echo $AppUI->_( 'Visit Type' );?></th>	
	
</tr>
<?php 
$str_link='<td><a href="?m=followup&a=monoedit&fid=';
//<td>'.issueView($far['followup_issues']).$far['followup_issues_notes'].'</td>
//<td>'.buildStringVals($servTypes,$far['followup_service']).'</td>

	foreach ($follws as $fid => $far){
		$link=$far['followup_id'].'">'.$far['followup_date'].'</a></td>';
		$str.='<tr>
				'.$str_link.$link.'
				<td>'.$owners[$far['followup_officer_id']].'</td>				
				<td>'.$vModes[$far['followup_visit_mode']].'</td>				
				<td>'.$visitTypes[$far['followup_visit_type']].'</td>				
			</tr>';
	}
	echo $str;
	echo '</table><br><br>';		
}
*/
if (!($rows=$q->loadList()) && !$follws){
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add counselling visit...";
	$url = "./index.php?m=counselling&a=addedit&client_id=$client_id";

}
else
{
$rows=array_merge($rows,$follws);
usort($rows,'sds');
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1"
	class="tbl">
	<tr>
		<th><?php echo $AppUI->_( 'Date' );?></th>
		<th><?php echo $AppUI->_( 'Counsellor' );?></th>
		<th><?php echo $AppUI->_( 'Category' );?></th>
		<th><?php echo $AppUI->_( 'Type of visit' );?></th>
	</tr>
<?php

    foreach ($rows as $row) {		
		/*$counsellingObj = new CCounsellingVisit();
		$counsellingObj->load($row["counselling_id"]);*/
		$url = "./index.php?m=counselling&a=addedit&client_id=$client_id&counselling_id=".$row["row_id"];
		$entry_date = intval( /*$counsellingObj->counselling_entry_date*/$row['row_date'] ) ? new CDate( /*$counsellingObj->counselling_entry_date*/$row['row_date'] ) : NULL;
		$visit_date = ($entry_date != NULL) ? $entry_date->format($df) : "";
		/*if($row['category'] === 'visit'){
			$link='?m=clients&a=view&counselling_id='.$row['counselling_id'].'&client_id='.$client_id;
		}else{
			$link="?m=followup&a=monoedit&fid=".$row['row_id'];
		}*/
		if($row['category'] == 'visit'){
			$uid=$row['row_id'];
			$ucat=$row['category'];
		}else{
			$uid=$row['row_id'];
			$ucat=str_replace(' ','_',$row['category']);
		}
		
		
		$link='?m=clients&a=view&counselling_id='.$uid.'&client_id='.$client_id.'&vpmode='.$ucat;
		
		//var_dump($entry_date->format($df));	
		$w .= '<tr>';
		$w .= '<td><a href="'.$link.'">'. $visit_date .'</a></td>';
		$w .= '<td><a href="'.$link.'">'. $owners[$row['row_officer']/*$counsellingObj->counselling_staff_id*/].'</a></td>';
		$w .= '<td><a href="'.$link.'">'. $row['category'] .'</a></td>';
		$w .= '<td><a href="'.$link.'">'. $visitTypes[/*$counsellingObj->counselling_visit_type*/$row['visit_type']].'</a></td>';
		$w .= '</tr>';
	}
}

	$w .= '<tr><td colspan="4" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new counselling visit' ).'" onClick="javascript:window.location=\'./index.php?m=counselling&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '</td></tr>';
	echo $w;
		
?>

</table>

<?php 

/* COUNSELLING VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", $rows[0]["counselling_id"] ) );
$client_id = intval( dPgetParam( $_GET, "client_id", $client_id ) );


require_once ($AppUI->getModuleClass('counselling'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( 'counselling', 'view', $counselling_id );
$canEdit = $perms->checkModuleItem( 'counselling', 'edit', $counselling_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

if($_GET['vpmode'] == 'visit'){

// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCounsellingVisit();
$canDelete = $obj->canDelete( $msg, $counselling_id );
// load the record data
if ($counselling_id > 0)
{
	$q  = new DBQuery;
	$q->addTable('counselling_visit');
	$q->addQuery('counselling_visit.*');
	$q->addWhere('counselling_visit.counselling_id = '.$counselling_id);
	$sql = $q->prepare();
	$q->clear();

	if (!db_loadObject( $sql, $obj )) {
		$AppUI->setMsg( 'Counselling Visit' );
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
$hivPrimaryCaregiverOptions = dPgetSysVal('HIVPrimaryCaregiverOptions');

$childHiv = dPgetSysVal('ChildHivAware');
$secondIdent = dPgetSysVal('SecondIdentified');
$df = $AppUI->getPref('SHDATEFORMAT');

$careHivTypes = dPgetSysVal('HIVStatusTypes');

$referer = dPgetSysVal('PositionOptions');

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
$entry_date = intval($obj->counselling_entry_date) ? new CDate($obj->counselling_entry_date ) :  null;
//load client
$clientObj = new CClient();
$date_string = $entry_date ? $entry_date->format($df) : "";
if ($clientObj->load($obj->counselling_client_id)){
	$ttl = "Details on Counselling Visit : " . $date_string;	
}
else{
   $ttl = "Details on Counselling Visit ";
}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );

$mother_status_date = intval($obj->counselling_date_mothers_status_known) ? new CDate($obj->counselling_date_mothers_status_known ) :  null;

$child_issues = explode(",", $obj->counselling_child_issues);
$caregiver_issues = explode(",", $obj->counselling_caregiver_issues);
$caregiver_issues2 = explode(",", $obj->counselling_caregiver_issues2);
$counselling_services = explode( ",", $obj->counselling_counselling_services);

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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Counselling Visit').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="75%"
	class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=counselling" method="post">
	<input type="hidden" name="dosql" value="do_counselling_aed" /> <input
		type="hidden" name="del" value="1" /> <input type="hidden"
		name="counselling_id" value="<?php echo $counselling_id;?>" /></form>
<?php } ?>

<tr>
		<td valign="top" width="100%">
		<table cellspacing="1" cellpadding="2">
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Details'); ?><br />
				</strong>
				<hr width="500" align="left" size="1" />
				</td>
			</tr>
			<tr>
				<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite">
          <?php echo dPformSafe(@$clinics[$obj->counselling_center_id]);?>
         </td>
			</tr>
			<tr>
				<td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
			</tr>
			<tr>
				<td align="left">1c.<?php echo $AppUI->_('Counsellor');?>:</td>
				<td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->counselling_staff_id]);?>
         </td>
			</tr>
			
			<tr>
				<td align="left">3.<?php echo $AppUI->_('Type of visit');?>:</td>
				<td align="left" class="hilite">&nbsp;&nbsp;<?php echo $visitTypes[$obj->counselling_visit_type]?></td>
			</tr>
			
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Mental and Health Issues'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	</tr> 
			<tr>
				<td align="left" valign="top">4.<?php echo $AppUI->_('Issues facing child includes');?>:</td>
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
				<td align="left">4j...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_other_issues);?>
		  </td>
			</tr>
			<tr>
				<td align="left" valign="top">5.
		<?php echo $AppUI->_("Mother or Father's");?><br />
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
				<td align="left">5f...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_other_issues);?>
		  </td>
			</tr>
			<tr>
				<td align="left" valign="top">6.
		<?php echo $AppUI->_("Other primary caregiver's");?><br />
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
				<td align="left">6f...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
		    <?php echo dPformSafe(@$obj->counselling_caregiver_other_issues2);?>
		  </td>
			</tr>
			<tr>
				<td colspan="2" align="left">
					<strong><?php echo $AppUI->_('Disclosure Status'); ?></strong><br/>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap="nowrap">7.<?php echo $AppUI->_('Does child know his / her HIV status');?>:</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureStatus[$obj->counselling_child_knows_status]?></td>
			</tr>
			<tr>
				<td align="left">8.
		<?php echo $AppUI->_("Apart from primary caregiver, do any other");?><br />
		<?php echo $AppUI->_("close adult know child's HIV status");?>:
		
		</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $hivAdultChildOptions[$obj->counselling_otheradult_knows_status]?></td>
			</tr>
			<tr>
				<td align="left">9.
		<?php echo $AppUI->_("If new disclosure has occurred since");?><br />
		<?php echo $AppUI->_("last counselling, describe response");?>:</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureResponse[$obj->counselling_disclosure_response]?></td>
			</tr>
			<tr>
				<td align="left">10.
		<?php echo $AppUI->_("If no other adults know child's status,");?><br />
		<?php echo $AppUI->_("describe state of disclosure process");?>:</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $disclosureProcess[$obj->counselling_disclosure_state]?></td>
			</tr>
			<tr>
				<td align="left">11.
		<?php echo $AppUI->_("Does child's secondary caregiver");?><br />
		<?php echo $AppUI->_("know child's HIV status?");?>:
		</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $hivCaregiverChildOptions[$obj->counselling_secondary_caregiver_knows];?></td>
			</tr>
			<tr>
				<td align="left" nowrap="nowrap">12.<?php echo $AppUI->_("Has child's primary caregiver been tested for HIV?");?>:</td>
				<td class="hilite">&nbsp;&nbsp;<?php echo $hivCaregiverOptions[$obj->counselling_primary_caregiver_tested]?></td>
			</tr>
			<tr>
				<td align="left" valign="top" nowrap="nowrap"><?php echo $AppUI->_('If yes what is their HIV status');?>:</td>
				<td align="left">&nbsp;</td>
			</tr>
			<tr>
				<td align="left">13a...<?php echo $AppUI->_('Father');?>:</td>
				<td class="hilite">
		    		<?php echo /*$hivPrimaryCaregiverOptions*/ $careHivTypes[$obj->counselling_father_status];?>
		  		</td>
			</tr>
			<tr>
				<td align="left">13b...<?php echo $AppUI->_('Mother');?>:</td>
				<td class="hilite">
		    		<?php echo /*$hivPrimaryCaregiverOptions*/ $careHivTypes[$obj->counselling_mother_status];?>
		  		</td>
			</tr>
			<tr>
				<td align="left">13c...<?php echo $AppUI->_('Caregiver');?>:</td>
				<td class="hilite">
		    		<?php echo /*$hivPrimaryCaregiverOptions*/ $careHivTypes[$obj->counselling_caregiver_status];?>
		  		</td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('If positive, is s/he receiving medical treatment?  ');?>:</td>
				<td align="left">&nbsp;</td>
			</tr>
			<tr>
				<td align="left">14a...<?php echo $AppUI->_('Father');?>:</td>
				<td align="left" class="hilite">
					<?php echo $hivTreatmentStatus[$obj->counselling_father_treatment]?>
				</td>
			</tr>
			<tr>
				<td align="left">14b...<?php echo $AppUI->_('Mother');?>:</td>
				<td align="left" class="hilite">
					<?php echo $hivTreatmentStatus[$obj->counselling_mother_treatment]?>
				</td>
			</tr>
			<tr>
				<td align="left">14c...<?php echo $AppUI->_('Caregiver');?>:</td>
				<td align="left" class="hilite">
					<?php echo $hivTreatmentStatus[$obj->counselling_caregiver_treatment]?>
				</td>
			</tr>
			<tr>
				<td align="left">15.
					<?php echo $AppUI->_("To what degree is HIV related stigmatization");?><br />
					<?php echo $AppUI->_("discrimination a concern for this family?");?>:</td>
				<td align="left" class="hilite">
					<?php echo $stigmatizationConcern[$obj->counselling_stigmatization_concern]?>
				</td>
			</tr>
			<tr>
				<td align="left">16.
					<?php echo $AppUI->_("Has Secondary Caregiver been identified");?>:
				</td>
				<td align="left" class="hilite">
					<?php echo $secondIdent[$obj->counselling_second_ident]?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">17.<?php echo $AppUI->_("Services Offered");?>:</td>
				<td align="left" class="hilite">
					<?php 
						foreach ($counselling_services as $counselling_service){
			     			echo $serviceOptions[$counselling_service] . "<br/>";
						}
		 			?>
		 		</td>
			</tr>
			<tr>
				<td align="left">17f...<?php echo $AppUI->_('Other');?>:</td>
				<td class="hilite">
				    <?php echo dPformSafe(@$obj->counselling_other_services);?>
		  		</td>
			</tr>
			<tr>
				<td align="left" valign="top">18.<?php echo $AppUI->_("Refer to");?>:</td>
				<td align="left" class="hilite">
					<?php echo $referer[$obj->counselling_referer];?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">18...<?php echo $AppUI->_("Other");?>:</td>
				<td align="left" class="hilite">
					<?php echo $obj->counselling_referer_other;?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">19.<?php echo $AppUI->_("Next appointment");?>:</td>
				<td align="left" class="hilite">
					<?php $ndate=((int) $obj->counselling_next_visit > 0 ? new CDate($obj->counselling_next_visit) : null);
					    if($ndate){
						echo $ndate->format($df);
					    }
					?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">20.<?php echo $AppUI->_("Counsellor's overall assessments");?>:</td>
				<td align="left" class="hilite">
					<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->counselling_notes), 75,"<br />", true);?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?php
}
}elseif ($_GET['vpmode'] === 'follow_up'){
	if((int)$_GET['counselling_id'] > 0){
	$ufid=(int)$_GET['counselling_id'];
	$q= new DBQuery();
	$q->addWhere('followup_id='.$ufid);
	$q->addTable('followup_info');
	$q->addQuery('followup_info.*');
	$q->setLimit(1);
	$sql=$q->prepare();
	
	$obj = new CFollowUp();
	if (!db_loadObject( $sql, $obj ) && $ufid > 0){
	    breakOut();
	}	
}else{
	breakOut();
}

global $issues;

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

$clientTypes =  dPgetSysVal('FollowClientType');
$servTypes = dPgetSysVal('FollowServices');
$visitTypes = dPgetSysVal('FollowVisitType');

$edate = new Date($obj->followup_date);
$df = $AppUI->getPref('SHDATEFORMAT');

$staff = makeListPerson($obj->followup_adm_no);


foreach ( $staff as $sname => $svar ) {
	$zstr = '';
	if (is_array ( $svar )) {
		if (count ( $svar ) > 0) {
			foreach ( $svar as $v1 ) {
					if ($v1 == $obj->followup_object) {
						$viewStaff = $v1;
					}
				}
			}
		} else {
			if ($svar == $obj->followup_object) {
				$viewStaff = $svar;
			}
		}
	}
	$ttl = "Details on Follow Up ";
	$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
	$titleBlock = new CTitleBlock ( $ttl, '', $m, "$m.$a" );
	
	$mother_status_date = intval ( $obj->counselling_date_mothers_status_known ) ? new CDate ( $obj->counselling_date_mothers_status_known ) : null;
	
	$child_issues = explode ( ",", $obj->counselling_child_issues );
	$caregiver_issues = explode ( ",", $obj->counselling_caregiver_issues);
	$caregiver_issues2 = explode(",", $obj->counselling_caregiver_issues2);
	$counselling_services = explode( ",", $obj->counselling_counselling_services);

if ($canEdit) {
	$titleBlock->addCrumb( "?m=followup&a=monoedit&fid=$counselling_id&client_id=$client_id", "Edit" );
	
if ($canDelete) {
	$titleBlock->addCrumbDelete( 'delete counselling record', $canDelete, $msg );
}
}
$titleBlock->show();

?>

<table class="std" border="0" width="100%">
	<tr>
		<td>
		<table id="ftab">
			<tr>
				<td align="left"><?php echo $AppUI->_('Counsellor');?>:</td>
				<td align="left" class="hilite">
		<?php echo $owners[$obj->followup_officer_id]; ?>
	</td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite">
		<?php echo $clinics[$obj->followup_center_id]; ?>
	</td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
		<?php echo  $edate ? $edate->format( $df ) : "";?>
	 </td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->followup_adm_no;?></td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Client Name');?>:</td>
				<td align="left" class="hilite"><?php echo $viewStaff; ?></td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Client Type');?>:</td>
				<td align="left" class="hilite"><?php echo $clientTypes[$obj->followup_client_type]; ?></td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Visit Type');?>:</td>
				<td align="left" class="hilite">
				<?php echo $visitTypes[$obj->followup_visit_type]; ?>
			</td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Issues');?>:</td>
				<td align="left" class="hilite"><?php echo buildStringVals($issues,$obj->followup_issues); ?></td>
			</tr>
			<tr>
				<td align="left">...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->followup_issues_notes;?></td>
			</tr>
			<tr>
				<td align="left"><?php echo $AppUI->_('Services');?>:</td>
				<td align="left" class="hilite">
				<?php echo buildStringVals($servTypes,$obj->followup_service); ?>
			</td>
			</tr>
			<tr>
				<td align="left">...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite"><?php echo $obj->followup_service_notes;?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?php 
}
?>

