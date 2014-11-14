<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('clinical');
require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');


$title = 'new clinical visit...';

$boolTypes = dPgetSysVal('YesNo');
$diarrhoeaTypes = dPgetSysVal('DiarrhoeaType');

$clearTypes = dPgetSysVal('ClearTypes');
$dehydrationTypes = dPgetSysVal('DehydrationType');
$tbTypes = dPgetSysVal('TBPulmonaryType');
$malnutrionTypes = dPgetSysVal('MalnutritionType');
$earTypes = dPgetSysVal('EarType');
$arvTypes = dPgetSysVal('ARVType');
$vitaminTypes = dPgetSysVal('VitaminTypes');
$tbDrugsTypes = dPgetSysVal('TBDrugsType');
$pneumoniaTypes = dPgetSysVal('PneumoniaTypes');
$treatmentTypes = dPgetSysVal('TreatmentType');
$growthTypes = dPgetSysVal('GrowthType');
$tbTypes = dPgetSysVal('TBPulmonaryType');
$crepTypes = dPgetSysVal('CrepitationTypes');
$nutritionTypes = dPgetSysVal('NutritionType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );



$df = $AppUI->getPref('SHDATEFORMAT');

$q = new DBQuery;
$q->addTable('clinical_visits');
$q->addQuery ('clinical_visits.*');
$q->addWhere('clinical_visits.clinical_client_id = '.$client_id);
$q->addOrder('clinical_visits.clinical_entry_date desc');
$w ='';
$sql= $q->prepare();

if (!($rows=$q->loadList()))
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add clinical visit...";
	$url = "./index.php?m=clinical&a=addedit&client_id=$client_id";

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
	<th><?php echo $AppUI->_( 'Clinician' );?></td>
	<th><?php echo $AppUI->_( 'Diarrhoea' );?></td>
	<th><?php echo $AppUI->_( 'Dehydration' );?></td>
	<th><?php echo $AppUI->_( 'Pneumonia' );?></td>
	<th><?php echo $AppUI->_( 'TB' );?></td>
	<th><?php echo $AppUI->_( 'Referral To' );?></td>
	<th><?php echo $AppUI->_( 'Next Visit Date' );?></td>

</tr>
<?php
    foreach ($rows as $row)
    {
		$url = "./index.php?m=clinical&a=addedit&client_id=$client_id&clinical_id=".$row["clinical_id"];
		$clinicalObj = new CClinicalVisit();
		$clinicalObj->load($row["clinical_id"]);

		$entry_date = intval( $clinicalObj->clinical_entry_date ) ? new CDate( $clinicalObj->clinical_entry_date ) : NULL;
		$next_date = intval( $clinicalObj->clinical_next_date ) ? new CDate( $clinicalObj->clinical_next_date ) : NULL;
		//var_dump($next_date);
		$next_appointment = ($next_date != NULL) ? $next_date->format($df) : "";

		//$blood_test_date = intval( $clinicalObj->clinical_blood_test_date ) ? new CDate( $clinicalObj->clinical_blood_test_date ) : "";


		$w .= '<tr>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. ($entry_date ? $entry_date->format($df) : "" ).'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $owners[$clinicalObj->clinical_staff_id].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $diarrhoeaTypes[$clinicalObj->clinical_diarrhoea_type].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $dehydrationTypes[$clinicalObj->clinical_dehydration].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $pneumoniaTypes[$clinicalObj->clinical_pneumonia].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $tbTypes[$clinicalObj->clinical_tb].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $owners[$clinicalObj->clinical_referral].'</a></td>';
		$w .= '<td><a href="./index.php?m=clients&a=view&clinical_id='.$clinicalObj->clinical_id.'&client_id='.$client_id. '">'. $next_appointment .'</a></td>';
		$w .= '</tr>';
	}
}

	$w .= '<tr><td colspan="8" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new clinical visit' ).'" onClick="javascript:window.location=\'./index.php?m=clinical&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '</td></tr>';
	echo $w;

?>

</table>
<?php /* SHOW CLINICAL VISIT  */
$clinical_id = intval( dPgetParam( $_GET, "clinical_id", $rows[0]["clinical_id"] ) );
$client_id = intval( dPgetParam( $_GET, "client_id", $client_id ) );
// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( 'clinical', 'view', $clinical_id );
$canEdit = $perms->checkModuleItem( 'clinical', 'edit', $clinical_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CClinicalVisit();
$canDelete = $obj->canDelete( $msg, $clinical_id );

//var_dump($canDelete);

$q  = new DBQuery;
$q->addTable('clinics','c');
$q->addQuery('clinic_id');
$q->addQuery('clinic_name');
$q->addOrder('clinic_name');
$clinics = $q->loadHashList();


// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();

// load the record data
if ($clinical_id > 0)
{
	$q  = new DBQuery;
	$q->addTable('clinical_visits');
	$q->addQuery('clinical_visits.*');
	$q->addWhere('clinical_visits.clinical_id = '.$clinical_id);
	$sql = $q->prepare();
	$q->clear();

	//$obj = null;
	if (!db_loadObject( $sql, $obj ))
	{
		$AppUI->setMsg( 'Clinical Visit' );
		$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
		$AppUI->redirect();
	}
	else
	{
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
$df = $AppUI->getPref('SHDATEFORMAT');

$entry_date = intval($obj->clinical_entry_date) ? new CDate($obj->clinical_entry_date ) :  null;
$blood_test_date = intval($obj->clinical_bloodtest_date) ? new CDate($obj->clinical_bloodtest_date ) :  null;
$clinical_next_date = intval($obj->clinical_next_date) ? new CDate($obj->clinical_next_date ) :  null;
$clinical_tb_treatment_date = intval($obj->clinical_tb_treatment_date) ? new CDate($obj->clinical_tb_treatment_date ) :  null;
$arv_types = explode (",",$obj->clinical_arv_drugs);
$clinical_tb_drugs = explode (",",$obj->clinical_tb_drugs);
$vitamins = explode(",",$obj->clinical_vitamins);
$nutritional_support = explode(",",$obj->clinical_nutritional_support);
$earsOpts= dPgetSysVal('EarsOptions');
$throatOpts = dPgetSysVal('ThroatOptions');
$teethType = dPgetSysVal('TeethType');
$norms = dPgetSysVal('CNSType');
$skinTypes = dPgetSysVal('SkinOptions');
$therapyTypes = dPgetSysVal('TherapyStage');
$client_id = $client_id ? $client_id : $obj->clinical_client_id;

// setup the title block

//load client
$clientObj = new CClient();

$date_str = $entry_date ? $entry_date->format( $df ) : "";
if ($clientObj->load($obj->clinical_client_id))
{
	$ttl = "Details on Clinical Visit : " . $date_str;
}
else
{
   $ttl = "Details on Clinical Visit";

}
$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );

if ($canEdit) {
	$titleBlock->addCrumb( "?m=clinical&a=addedit&clinical_id=$clinical_id&client_id=$client_id", "Edit" );

	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete clinical visit record', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Clinical Visit Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="75%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=clinical" method="post">
	<input type="hidden" name="dosql" value="do_clinical_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="clinical_id" value="<?php echo $clinical_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="75%">
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
          <?php echo $clinics[$obj->clinical_clinic_id]; ?>
         </td>
		 </tr>
		 <tr>
		 <td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
       </tr>
       <tr>
         <td align="left">1c.<?php echo $AppUI->_('Clinician');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->clinical_staff_id]);?>
         </td>
       </tr>
	   
    <tr>
         <td align="left">3a.<?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->clinical_age_yrs);?>
		 </td>
	 </tr>
	 <tr>
	 <td>3a.<?php echo $AppUI->_('Age (months)');?>:</td>
	 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->clinical_age_months);?>
		 </td>

	 </tr>
 	  <tr>
		<td align="left">3b.<?php echo $AppUI->_('Child attending?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_child_attending]; ?></td>
     </tr>

	 <tr>
		<td align="left">3c.<?php echo $AppUI->_('Caregiver attending?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_caregiver_attending]; ?></td>
     </tr>

       <tr>
         <td align="left">3d.<?php echo $AppUI->_('Who?');?></td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->clinical_caregiver);?>
         </td>
	</tr>
	<tr>
	    <td align="left">4a.<?php echo $AppUI->_('Admission/illness since last visit?');?></td>
	    <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_illness]; ?></td>
	</tr>

       <tr>
         <td align="left">4b...<?php echo $AppUI->_('If yes, specify');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->clinical_illness_notes);?>
         </td>
       </tr>
	<!--   <tr>
		<td align="left">5a.<?php echo $AppUI->_('Any Diarrhoea');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_diarrhoea]; ?></td>
     </tr>
	   <tr>
		<td align="left">5b.<?php echo $AppUI->_('Any Vomiting');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_vomiting]; ?></td>
     </tr>
 -->
     <tr>
		<td align="left">5a.<?php echo $AppUI->_('Any Complaints');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_complaints]; ?></td>
     </tr>
       <tr>
         <td align="left">5b.<?php echo $AppUI->_('if Yes, current complaints');?>:</td>
         <td align="left" class="hilite">
		 <?php echo wordwrap( str_replace( chr(10), "<br />", $obj->clinical_current_complaints), 75,"<br />", true);?>
         </td>
       </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Last Blood Test'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

      <tr>
         <td align="left">6a.<?php echo $AppUI->_('Date');?>:</td>
		 <td align="left" class="hilite">
				<?php echo $blood_test_date ? $blood_test_date->format( $df ) : "" ;?>
		</td>
	  </tr>
      <tr>
         <td align="left">6b.<?php echo $AppUI->_('CD4');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4);?>
		 </td>
		 </tr>
         <tr>
           <td align="left">6c.
			<?php echo $AppUI->_('CD4%');?>:
		   </td>
           <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4_percentage);?>
		 </td>
	  </tr>
	  <tr>
	  <td align="left">6d.<?php echo $AppUI->_('Viral load');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_viral);?>
		 </td>

	  </tr>
	  <tr>
	  <td align="left">6e.<?php echo $AppUI->_('Hb');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_hb);?>
		 </td>

	  </tr>
	 <tr>
	 	<td align="left" colspan="2"><?php echo $AppUI->_('X-ray results');?>:</td>
	 </tr>
	  <tr>
		<td align="left">7a.<?php echo $AppUI->_('X-Ray');?>:</td>
		 <td align="left" class="hilite">

			<?php echo dPformSafe(@$obj->clinical_xray_results);?>
		 </td>
	  </tr>
	  <tr>
		<td align="left">7b.<?php echo $AppUI->_('CT Scan');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_ctscan);?>
		 </td>
	  </tr>
	  <tr>
		<td align="left">7c.<?php echo $AppUI->_('AST/AL');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_astal);?>
		 </td>
	  </tr>
	  <tr>
	  <td align="left">7d.<?php echo $AppUI->_('Other results');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_other_results);?>
		 </td>

	  </tr>
	 <tr>
		<td align="left" valign="top">8a.<?php echo $AppUI->_('Nutritional support');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php
		foreach ($nutritional_support as $support)
		{
			     echo $nutritionTypes[$support] . "<br/>";
		}
		?>
		</td>
	</tr>
	  <tr>
		<td align="left" valign="top">8b...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_nutritional_notes;?></td>

		</tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Examination'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
        <td align="left">9a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->clinical_weight);?>
        </td>
      </tr>
      <tr>
			<td align="left">9b.<?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_height;?></td>
      </tr>
      <tr>
			<td align="left">9c.<?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_zscore;?></td>
      </tr>      <tr>
			<td align="left">9d.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_muac;?></td>
      </tr>
      <tr>
			<td align="left">9e.<?php echo $AppUI->_('Head Circum (cm)');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_hc;?></td>
      </tr>

	  <tr>
		<td align="left">10a.<?php echo $AppUI->_('Temp (Celcius)');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_temp;?>&nbsp;</td>
      </tr>
	  <tr>
		<td align="left">10b.<?php echo $AppUI->_('Respiratory rate');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_resp_rate;?>&nbsp;</td>
	  </tr>
      <tr>
		<td align="left">10c.<?php echo $AppUI->_('Heart rate');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_heart_rate;?>&nbsp;</td>
     </tr>
	  <!--  <tr>
		<td align="left" valign="top"><strong><?php echo $AppUI->_('General Condition');?>:</strong></td>
	 </tr> -->
   <tr>
		<td align="left">11a.<?php echo $AppUI->_('Pallor');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_pallor]; ?></td>
      </tr>
      <tr>
		<td align="left">11b.<?php echo $AppUI->_('Jaundice');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_jaundice]; ?></td>
      </tr>
      <tr>
			<td align="left">11c.<?php echo $AppUI->_('Oedema');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_oedema]; ?></td>
      </tr>
      <tr>
		  <td align="left">11d.<?php echo $AppUI->_('Clubbing ');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_clubbing]; ?></td>
      </tr>
	  <tr>
		  <td align="left">11e.<?php echo $AppUI->_('Dehydration');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_examination_dehydration]; ?></td>
      </tr>
	  <tr>
		  <td align="left">11f.<?php echo $AppUI->_('Lymph nodes');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_examination_lymph]; ?></td>
      </tr>
      <tr>
			<td align="left">12a.<?php echo $AppUI->_('Cardiovascular');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_cardiovascular;?></td>
      </tr>
      <tr>
			<td align="left">13a.<?php echo $AppUI->_('Respiratory ');?>:</td>
			<td align="left" class="hilite"><?php echo $clearTypes[$obj->clinical_chest_clear]; ?></td>
      </tr>
      <tr>
			<td align="left">13b.<?php echo $AppUI->_('Crepitations ');?>:</td>
			<td align="left" class="hilite"><?php echo $crepTypes[$obj->clinical_chest_creps]; ?></td>
      </tr> 
      <tr>
			<td align="left">13c...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_chest;?></td>
    </tr>
      <tr>
			<td align="left">14a.<?php echo $AppUI->_('Skin');?>:</td>
			<td align="left" class="hilite"><?php echo $clearTypes[$obj->clinical_skin_clear]; ?></td>
      </tr>
      <tr>
			<td align="left">14b.<?php echo $AppUI->_('');?>:</td>
			<td align="left" class="hilite"><?php echo $skinTypes[$obj->clinical_skin_opts]; ?></td>
      </tr>
	   <tr>
			<td align="left">14c...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_skin;?></td>
	   </tr>
	    <tr>
			<td align="left">14d.<?php echo $AppUI->_('Ears discharge?');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo @$earsOpts[$obj->clinical_ears_opt];?></td>
      </tr>
      <!--<tr>
			<td align="left"><?php echo $AppUI->_('Ears ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_ears;?></td>
      </tr> -->
      <tr>
			<td align="left">15a.<?php echo $AppUI->_('Throat ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $throatOpts[$obj->clinical_throat];?></td>
      </tr>
      <tr>
			<td align="left">15b.<?php echo $AppUI->_('Mouth thrush');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_mouth_thrush];?></td>
      </tr>
      <tr>
			<td align="left">15c.<?php echo $AppUI->_('Mouth ulcers');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_mouth_ulcer];?></td>
      </tr>
      <tr>
			<td align="left">15d.<?php echo $AppUI->_('Teeth ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php $tar=dPgetSysVal('TeethType'); echo $tar[$obj->clinical_teeth_opt];unset($tar);?></td>
      </tr>
     <!--  <tr>
			<td align="left"><?php echo $AppUI->_('Mouth');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_mouth;?></td>
      </tr> -->
	  <tr>
			<td align="left">16.<?php echo $AppUI->_('Per/Abdomen');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_abdomen;?></td>
      </tr>
 	 
      <tr>
			<td align="left">17a.<?php echo $AppUI->_('Central Nervous System');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo @$norms[$obj->clinical_cns];?></td>
      </tr>
      <tr>
			<td align="left">17b.<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_neurodevt;?></td>
      </tr>
      <tr>
			<td align="left">17c.<?php echo $AppUI->_('Musculoskeletal');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo @$norms[$obj->clinical_muscle];?></td>
      </tr>
 	  <tr>
			<td align="left">17d.<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_musculoskeletal;?></td>
      </tr>
      <tr>
			<td align="left">18a.<?php echo $AppUI->_('Eyes');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $norms[$obj->clinical_eyes];?></td>
      </tr>
      <tr>
			<td align="left">18b.<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_eyes_opt;?></td>
      </tr>
      <tr>
			<td align="left">18c.<?php echo $AppUI->_('Other');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_other;?></td>
      </tr>
      <tr>
		<td align="left" valign="top"><strong><?php echo $AppUI->_('ARV therapy');?></strong></td>
	 </tr>
      <tr>
		  <td align="left">19a.<?php echo $AppUI->_('Is Client on ARV Therapy');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_arv_on]; ?></td>
      </tr>
      <tr>
			<td align="left" nowrap="nowrap">19b.
				<?php echo $AppUI->_('Are you satisfied with knowledge and adherence');?>:
			</td>
			<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_adherence]; ?></td>
	</tr>
      <tr>
		  <td align="left">19c.<?php echo $AppUI->_('If No, is client on adherence counseling');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_arv_on_adh]; ?></td>
      </tr>
      <tr>
		  <td align="left">19d.<?php echo $AppUI->_('Recomendations');?>:</td>
		  <td align="left" class="hilite"><?php $tar=dPgetSysVal('ARVTreatment'); echo $tar[$obj->clinical_arv_recomends];unset($tar); ?></td>
      </tr>
	
	<!-- <tr>
	 		<td align="left" >...<?php echo $AppUI->_('Specify');?>:</td>
		    <td align="left"class="hilite">
				<?php echo wordwrap(str_replace( chr(10), "<br />", $obj->clinical_arv_notes), 75,"<br />", true);?>&nbsp;
			</td>
	</tr> -->

	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Diagnosis'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">20a.<?php echo $AppUI->_('WHO stage');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_who_stage;?></td>
     </tr>
     <tr>
		<td align="left" valign="top">20b.<?php echo $AppUI->_('Clinical stage');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_stage;?></td>
     </tr>
	 <!--<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Well child - minimal problems');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_child_condition]; ?></td>
	  </tr> -->
     <tr>
		<td align="left" valign="top">21a.<?php echo $AppUI->_('Diarrhoea');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $diarrhoeaTypes[$obj->clinical_diarrhoea_type]; ?></td>
	  </tr>
      <tr>
		<td align="left" valign="top">21b.<?php echo $AppUI->_('Dehydration');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $dehydrationTypes[$obj->clinical_dehydration]; ?></td>
	</tr>
     <tr>

		<td align="left" valign="top">22a.<?php echo $AppUI->_('Pneumonia');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $pneumoniaTypes[$obj->clinical_pneumonia]; ?></td>
	</tr>
     <tr>
		<td align="left" valign="top">22b.<?php echo $AppUI->_('Chronic lung disease');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_chronic_lung];?></td>
     </tr>
     <tr>

		<td align="left" valign="top">23a.<?php echo $AppUI->_('TB');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $tbTypes[$obj->clinical_tb]; ?></td>
	 </tr>
	<tr>
	<td align="left" valign="top">23b...<?php echo $AppUI->_('On treatment since');?>:</td>
	<td align="left" valign="top" class="hilite"><?php  echo $clinical_tb_treatment_date ? $clinical_tb_treatment_date->format( $df ) : "" ;?></td>

     </tr>
     <tr>
		<td align="left" valign="top">24a.<?php echo $AppUI->_('Other diagnoses');?>:</td>
		<td align="left" valign="top" class="hilite"><?php
			if(strlen($obj->clinical_dss) > 0){
				$vals=dPgetSysVal('OtherDiagnoses');
				$dsa=explode(',',$obj->clinical_dss);
				foreach ($dsa as $c){
					$str.=$vals[$c].' ';
				}
			}
			echo $str;
		?>
		</td>
     </tr>
	<tr>
	<td align="left" valign="top">24b.<?php echo $AppUI->_('Others');?>:</td>
	<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_other_diagnoses;?></td>
     </tr>
     <tr>

		<td align="left" valign="top">25a.<?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $malnutrionTypes[$obj->clinical_malnutrition]; ?></td>
	 </tr>
	<tr>
	<td align="left" valign="top">25b.<?php echo $AppUI->_('Growth');?>:</td>
	<td align="left" valign="top" class="hilite"><?php echo $growthTypes[$obj->clinical_growth];?></td>
     </tr>

	 <!-- <tr>
	 	<td align="left" valign="top"><?php echo $AppUI->_('Comment');?>:</td>
		<td class="hilite">
				<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->clinical_assessment_notes), 75,"<br />", true);?>&nbsp;
			</td>
     </tr> -->
     
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Treatment plan'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	<tr>
		<td align="left" valign="top">26a.<?php echo $AppUI->_('Request Investigations');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_request]; ?> </td>
	</tr>
	<tr>
		<td align="left" valign="top">26b...<?php echo $AppUI->_('Investigations');?>:</td>
		<td align="left" valign="top" class="hilite"><?php
		if(strlen($obj->clinical_request_list) > 0){
			$str='';
			$vals=dPgetSysVal('RequestInvestigations');
			$dsa=explode(',',$obj->clinical_request_list);
			foreach ($dsa as $c){
				$str.=$vals[$c].' ';
			}
		}
		echo $str;
		?></td>
	</tr>
	    <tr>
		<td align="left" valign="top">26c...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $obj->clinical_investigations_notes;?>
		</td>
     </tr>

	 <tr>
		<td align="left" valign="top">27a...<?php echo $AppUI->_('ART');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_on_arvs]; ?></td>

     </tr>
	 <tr>
		<td align="left" valign="top">27b.&nbsp;</td>
		<td align="left" valign="top" class="hilite">
		<?php
		foreach ($arv_types as $arv_type)
		{
			     echo $arvTypes[$arv_type] . "<br/>";
		}
		 ?>
		</td>
     </tr>
	<tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Other ARV drugs');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_arv_drugs_other; ?></td>

     </tr>
    <tr>
		<td align="left" valign="top">27c...<?php echo $AppUI->_('Cotrimoxazole/Multivitamins');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php
		foreach ($vitamins as $vitamin){
			     echo $vitaminTypes[$vitamin] . "<br/>";
		}
		 ?>
		</td>
     </tr>
     <tr>
		<td align="left" valign="top">28.<?php echo $AppUI->_('Therapy stage');?>:</td>
		<td align="left" class="hilite"><?php echo $therapyTypes[$obj->clinical_therapy_stage];?></td>
	</tr>
    <tr>
		<td>29a.<?php echo $AppUI->_('ART');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $treatmentTypes[$obj->clinical_treatment_status]; ?></td>
	 </tr>
     <tr>
		<td align="left" valign="top">29b...<?php echo $AppUI->_('Reasons');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_arv_reason;?></td>
	</tr>
	<tr>
		<td align="left" valign="top">30a.<?php echo $AppUI->_('Tb treatment');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_tb_treat];?></td>
	</tr>
	 <tr>
		<td align="left" valign="top">30b.<?php echo $AppUI->_('TB drugs');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php
		foreach ($clinical_tb_drugs as $clinical_tb_drug)
		{
			     echo $tbDrugsTypes[$clinical_tb_drug] . "<br/>";
		}
		?>
		</td>
	 </tr>
	 <tr>
		<td align="left" valign="top">31a.<?php echo $AppUI->_('Tb status');?>:</td>
		<td align="left" class="hilite"><?php
			$tar = dPgetSysVal('TBStatus');
			echo $tar[$obj->clinical_tb_status];
			unset($tar);
		?></td>
	</tr>
	 <tr>
		<td align="left" valign="top">31b.<?php echo $AppUI->_('Reason');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_tb_status_notes;?></td>
		</tr>
	 <tr>
		<td>32a.<?php echo $AppUI->_('Other drugs continuing');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_other_drugs;?></td>

		</tr>
	 <tr>
		<td align="left" valign="top">32b.<?php echo $AppUI->_('New drugs prescribed');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_new_drugs;?></td>
	</tr>
	  <tr>
		<td align="left">33.<?php echo $AppUI->_('Is the child unwell?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_child_unwell]; ?></td>
	  </tr>
	 <tr>
		<td align="left">34.<?php echo $AppUI->_('Refer to');?>:</td>
		<td align="left" class="hilite"><?php
		$tar=dPgetSysVal("PositionOptions"); //('ClinicalReference');
		 echo $tar[$obj->clinical_referral];
		 unset($tar);
		 ?></td>

     </tr>
	 <tr>
		<td align="left">35.<?php echo $AppUI->_('Next appointment');?>:</td>
	 <td align="left" valign="top" class="hilite"><?php echo $clinical_next_date ? $clinical_next_date->format($df) : "";?>&nbsp;</td>
	</tr>

</table>
</td>
	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->clinical_id, "edit" );
 			$custom_fields->printHTML();
		?>
	</td>
</tr>

	</table>

	</td>

</tr>
</table>

<?php } ?>
