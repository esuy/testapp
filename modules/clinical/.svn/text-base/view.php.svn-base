<?php /* CLINICAL VISIT  */
$clinical_id = intval( dPgetParam( $_GET, "clinical_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $clinical_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $clinical_id );


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

$boolTypes = dPgetSysVal('YesNo');
$diarrhoeaTypes = dPgetSysVal('DiarrhoeaType');
$clearTypes = dPgetSysVal('ClearTypes');
$dehydrationTypes = dPgetSysVal('DehydrationType');
$tbTypes = dPgetSysVal('TBType');
$malnutrionTypes = dPgetSysVal('MalnutritionType');
$earTypes = dPgetSysVal('EarType');
$arvTypes = dPgetSysVal('ARVType');
$vitaminTypes = dPgetSysVal('VitaminTypes');
$tbDrugsTypes = dPgetSysVal('TBDrugsType');
$pneumoniaTypes = dPgetSysVal('PneumoniaTypes');
$treatmentTypes = dPgetSysVal('TreatmentType');
$growthTypes = dPgetSysVal('GrowthType');

$nutritionTypes = dPgetSysVal('NutritionType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();


// load the record data
$q  = new DBQuery;
$q->addTable('clinical_visits');
$q->addQuery('clinical_visits.*');
$q->addWhere('clinical_visits.clinical_id = '.$clinical_id);
$sql = $q->prepare();
$q->clear();

//$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Clinical Visit' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

$boolTypes = dPgetSysVal('YesNo');
$df = $AppUI->getPref('SHDATEFORMAT');
// setup the title block

//load client
$clientObj = new CClient();
if ($clientObj->load($obj->clinical_client_id))
{
	$ttl = "View Clinical Visit Info : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Clinical Visit Info ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->clinical_entry_date) ? new CDate($obj->clinical_entry_date ) :  null;
$blood_test_date = intval($obj->clinical_bloodtest_date) ? new CDate($obj->clinical_bloodtest_date ) :  null;
$clinical_next_date = intval($obj->clinical_next_date) ? new CDate($obj->clinical_next_date ) :  null;
$clinical_tb_treatment_date = intval($obj->clinical_tb_treatment_date) ? new CDate($obj->clinical_tb_treatment_date ) :  null;
$arv_types = explode (",",$obj->clinical_arv_drugs);
$vitamins = explode(",",$obj->clinical_vitamins);

$client_id = $client_id ? $client_id : $obj->clinical_client_id;		
		
		

$titleBlock->addCrumb( "?m=clients", "Clients" );

if ($client_id > 0 )
{
	$titleBlock->addCrumb( "?m=clients&a=view&&client_id=$client_id", $clientObj->getFullName() );
}

if ($canEdit) {
	$titleBlock->addCrumb( "?m=clinical&a=addedit&clinical_id=$clinical_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete clinical visit record', $canDelete, $msg );
	}
}
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new clinical visit record').'" />', '',
		'<form action="?m=clinical&a=addedit" method="post">', '</form>'
	);

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
         <td align="left"><?php echo $AppUI->_('Clinician');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$owners[$obj->clinical_staff_id]);?>
         </td>
       </tr>    
	   <tr>
         <td align="left"><?php echo $AppUI->_('Center');?>:</td>
         <td align="left" class="hilite">
          <?php echo $clinics[$obj->clinical_clinic_id]; ?>
         </td>
		 </tr>
		 <tr>
		 <td align="left"><?php echo $AppUI->_('Date');?>: </td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
       </tr>
      <tr>	 
       <tr>
         <td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left" class="hilite">
         <?php echo dPformSafe(@$clientObj->client_adm_no);?>
         </td>
       </tr>

	 <tr>
         <td align="left"><?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$clientObj->getFullName());?>
         </td>
       </tr>

      <tr>
         <td align="left"><?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->clinical_age_yrs);?>
		 </td>
      </tr>
      <tr>	  
	  	 <td><?php echo $AppUI->_('Age (months)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->clinical_age_months);?>
		 </td>
	 </tr>
	  <tr>
		<td align="left"><?php echo $AppUI->_('Child attending?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_child_attending]; ?></td>
     </tr>

	 <tr>
		<td align="left"><?php echo $AppUI->_('Caregiver attending?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_caregiver_attending]; ?></td>
     </tr>

       <tr>
         <td align="left"><?php echo $AppUI->_('Who');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->clinical_caregiver);?>
         </td>
       </tr>
	   	 <tr>
		<td align="left"><?php echo $AppUI->_('Admission/illness since last visit?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_illness]; ?></td>
     </tr>

       <tr>
         <td align="left">...<?php echo $AppUI->_('If yes, specify');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->clinical_illness_notes);?>
         </td>
       </tr>
	  <tr>
		<td align="left"><?php echo $AppUI->_('Any Diarrhoea');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_diarrhoea]; ?></td>
     </tr>
	  <tr>
		<td align="left"><?php echo $AppUI->_('Any Vomiting');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_vomiting]; ?></td>
     </tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Current complaints');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$obj->clinical_current_complaints);?>
         </td>
       </tr>
	 
		 
		 
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Last Blood Test'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

      <tr>
         <td align="left"><?php echo $AppUI->_('Date');?>:</td>
		 <td align="left" class="hilite">
				<?php echo $blood_test_date ? $blood_test_date->format( $df ) : "" ;?>
		</td>
	  </tr>
      <tr>
         <td align="left"><?php echo $AppUI->_('CD4');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4);?>
		 </td>
		 </tr>
         <tr>
           <td align="left">		 
			<?php echo $AppUI->_('CD4%');?>:
		   </td>
           <td align="left" class="hilite">		   
			<?php echo dPformSafe(@$obj->clinical_bloodtest_cd4_percentage);?>
		 </td>
	  </tr> 
	  <tr>
	  <td align="left"><?php echo $AppUI->_('Viral load');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_viral);?>
		 </td>

	  </tr>
	  <tr>
	  <td align="left"><?php echo $AppUI->_('Hb');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_bloodtest_hb);?>
		 </td>

	  </tr>
<tr>
	  <td align="left"><?php echo $AppUI->_('X-ray results');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_xray_results);?>
		 </td>

	  </tr>
	  <tr>
	  <td align="left"><?php echo $AppUI->_('Other results');?>:</td>
		 <td align="left" class="hilite">
			<?php echo dPformSafe(@$obj->clinical_other_results);?>
		 </td>

	  </tr>  
	 <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('Nutritional support');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $nutritionTypes[$obj->clinical_nutritional_support]; ?></td>	  </tr>	
	  <tr>	
		<td align="left" valign="top">...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_nutritional_notes;?></td>

		</tr>	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Examination'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>

	  <tr>
        <td align="left"><?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->clinical_weight);?>
        </td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_height;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_zscore;?></td>
      </tr>      <tr>
			<td align="left"><?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_muac;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Head Circum (cm)');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_hc;?></td>
      </tr>
	  <tr>
		<td align="left"><?php echo $AppUI->_('Is the child unwell?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_child_unwell]; ?></td>
	  </tr>	
	  <tr>	
		<td align="left">...<?php echo $AppUI->_('If unwell record: Temp (Celcius)');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_temp;?>&nbsp;</td>
      </tr>  		
	  <tr>
		<td align="left">...<?php echo $AppUI->_('Respiratory rate');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_resp_rate;?>&nbsp;</td>		
	  </tr>
      <tr>	  
		<td align="left">...<?php echo $AppUI->_('Heart rate');?>:&nbsp;</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_heart_rate;?>&nbsp;</td>
     </tr>	 
	 <tr>
		<td align="left" valign="top"><strong><?php echo $AppUI->_('General Condition');?>:</strong></td>
	 </tr>	 
   <tr>
		<td align="left"><?php echo $AppUI->_('Pallor');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_pallor]; ?></td>
      </tr>	
      <tr>
		<td align="left"><?php echo $AppUI->_('Jaundice');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_jaundice]; ?></td>
      </tr>		  
      <tr>
			<td align="left"><?php echo $AppUI->_('Oedema');?>:</td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_oedema]; ?></td>
      </tr>	  
      <tr>
		  <td align="left"><?php echo $AppUI->_('Clubbing ');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_clubbing]; ?></td>
      </tr>	      
	  <tr>
		  <td align="left"><?php echo $AppUI->_('Dehydration');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_examination_dehydration]; ?></td>
      </tr>
	  <tr>
		  <td align="left"><?php echo $AppUI->_('Lymph nodes');?>:</td>
		  <td align="left" class="hilite"><?php echo $boolTypes[$obj->clinical_examination_lymph]; ?></td>
      </tr>	
      <tr>
			<td align="left"><?php echo $AppUI->_('Cardiovascular');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_cardiovascular;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Chest ');?>:</td>
			<td align="left" class="hilite"><?php echo $clearTypes[$obj->clinical_chest_clear]; ?></td>
      </tr>
	   <tr>
			<td align="left">...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_chest;?></td>
	   </tr>		
      <tr>
			<td align="left"><?php echo $AppUI->_('Skin');?>:</td>
			<td align="left" class="hilite"><?php echo $clearTypes[$obj->clinical_skin_clear]; ?></td>
      </tr>
	   <tr>
			<td align="left">...<?php echo $AppUI->_('Specify');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_skin;?></td>
	   </tr>		  
      <tr>
			<td align="left"><?php echo $AppUI->_('Ears ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_ears;?></td>
      </tr>	  
      <tr>
			<td align="left"><?php echo $AppUI->_('Abdomen');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_abdomen;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Mouth');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_mouth;?></td>
      </tr>	       
	  <tr>
			<td align="left"><?php echo $AppUI->_('Teeth ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_teeth;?></td>
      </tr>		 
      
      <tr>
			<td align="left"><?php echo $AppUI->_('Neuro/development');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_neurodevt;?></td>
      </tr>
 <tr>
			<td align="left"><?php echo $AppUI->_('Musculoskeletal');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_musculoskeletal;?></td>
      </tr>	  
	<tr>
		<td align="left" valign="top"><strong><?php echo $AppUI->_('ARV therapy');?></strong></td>
	 </tr>
	<tr> 
			<td align="left" nowrap="nowrap">
			<?php echo $AppUI->_('Satisfaction with knowledge and adherence');?>:
			
			</td>
			<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_adherence]; ?></td>
		</tr>
		<tr>
	 		<td align="left" >...<?php echo $AppUI->_('Specify');?>:</td>
		    <td align="left"class="hilite">
				<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->clinical_arv_notes), 75,"<br />", true);?>&nbsp;
			</td>
		</tr>	  
	  <tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Diagnosis'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	 <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('WHO stage');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_who_stage;?></td>
     </tr> 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Well child - minimal problems');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_child_condition]; ?></td>
	  </tr>    	 
     <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('Diarrhoea');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $diarrhoeaTypes[$obj->clinical_diarrhoea_type]; ?></td>
	  </tr>
      <tr>	  
		<td align="left" valign="top"><?php echo $AppUI->_('Dehydration');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $dehydrationTypes[$obj->clinical_dehydration]; ?></td>  
	</tr>
     <tr>	 

		<td align="left" valign="top"><?php echo $AppUI->_('Pneumonia');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $pneumoniaTypes[$obj->clinical_pneumonia]; ?></td>			
	</tr>
     <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('Chronic lung disease');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_chronic_lung];?></td>
     </tr>
     <tr>	 

		<td align="left" valign="top"><?php echo $AppUI->_('TB');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $tbTypes[$obj->clinical_tb]; ?></td>		
	 </tr>	
	<tr>
	<td align="left" valign="top">...<?php echo $AppUI->_('On treatment since');?>:</td>
	<td align="left" valign="top" class="hilite"><?php  echo $clinical_tb_treatment_date ? $clinical_tb_treatment_date->format( $df ) : "" ;?></td>
	
     </tr>
	<tr>
	<td align="left" valign="top"><?php echo $AppUI->_('Other diagnoses');?>:</td>
	<td align="left" valign="top" class="hilite"><?php echo $obj->clinical_other_diagnoses;?></td>
     </tr>     
     <tr>	 

		<td align="left" valign="top"><?php echo $AppUI->_('Malnutrition');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $malnutrionTypes[$obj->clinical_malnutrition]; ?></td>			
	 </tr>	
	<tr>	
	<td align="left" valign="top"><?php echo $AppUI->_('Growth');?>:</td>
	<td align="left" valign="top" class="hilite"><?php echo $growthTypes[$obj->clinical_growth];?></td>
     </tr>     
	 
	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Comment');?>:</td>
		<td class="hilite">
				<?php echo wordwrap( str_replace( chr(10), "<br />", $obj->clinical_assessment_notes), 75,"<br />", true);?>&nbsp;
			</td>
		
     </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Treatment plan'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	
	<tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('Investigations requested');?>:</td>
	</tr>     
	<tr>	 

		<td align="left" valign="top">...<?php echo $AppUI->_('Blood');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_investigations_blood]; ?></td>			
	</tr>     
	<tr>	 
		<td align="left" valign="top">...<?php echo $AppUI->_('X-Ray');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_investigations_xray]; ?></td>			
	</tr>
    <tr>	
		<td align="left" valign="top">...<?php echo $AppUI->_('Other');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $obj->clinical_investigations_notes;?>
		</td>			
     </tr>  	 
	 <tr>	 
		<td align="left" valign="top">...<?php echo $AppUI->_('ART');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->clinical_on_arvs]; ?></td>			

     </tr>     
	 <tr>	 
		<td align="left" valign="top">&nbsp;</td>
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
		<td align="left" valign="top">...<?php echo $AppUI->_('Cotrimoxazole/Multivitamins');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php 
		foreach ($vitamins as $vitamin)
		{
			     echo $vitaminTypes[$vitamin] . "<br/>";
		}
		 ?>
		</td>			
     </tr>
    <tr>	 
		<td><?php echo $AppUI->_('Treatment');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $treatmentTypes[$obj->clinical_treatment_status]; ?></td>			
	 </tr>
     <tr>	 
		<td align="left" valign="top">...<?php echo $AppUI->_('Reasons');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_arv_reason;?></td>

		</tr>  	 
	 <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('TB drugs');?>:</td>
		<td align="left" valign="top" class="hilite"><?php echo $tbDrugsTypes[$obj->clinical_tb_drugs]; ?></td>			
	 </tr>      
	 <tr>	 
		<td><?php echo $AppUI->_('Other drugs continuing');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_other_drugs;?></td>

		</tr>  	 
	 <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('New drugs prescribed');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->clinical_new_drugs;?></td>
		</tr>  	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Referral to');?>:</td>
		<td align="left" class="hilite"><?php echo $owners[$obj->clinical_referral];?></td>
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Next appointment');?>:</td>	 
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


