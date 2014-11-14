<?php /* NUTRITION VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$nutrition_id = intval( dPgetParam( $_GET, "nutrition_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $nutrition_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $nutrition_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


$dietHistoryOptions = dPgetSysVal('DietHistoryOptions');
// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CNutritionVisit();
$canDelete = $obj->canDelete( $msg, $nutrition_id );

// load the record data
$q  = new DBQuery;
$q->addTable('nutrition_visit');
$q->addQuery('nutrition_visit.*');
$q->addWhere('nutrition_visit.nutrition_id = '.$nutrition_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Nutrition Visit' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$owners = $q->loadHashList();





//load centers
$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();

$types = dPgetSysVal( 'CompanyType' );
$boolTypes = dPgetSysVal( 'YesNo' );
$dietHistoryOptions = dPgetSysVal('DietHistoryOptions');
$scoreTypes = dPgetSysVal( 'YesNo' );
$ageTypes = dPgetSysVal('AgeType');
$insecurityScores = dPgetSysVal('InsecurityScore');
$caregiverType = dPgetSysVal('CaregiverRelation');
$genderTypes = dPgetSysVal('GenderType');
$riskLevels = dPgetSysVal('RiskLevel');
$riskLevels = arrayMerge(array(-1=>'-Select Risk Level-'),$riskLevels );
$df = $AppUI->getPref('SHDATEFORMAT');
// setup the title block

//load client
$clientObj = new CClient();
$client_id = $client_id ? $client_id : $obj->nutrition_client_id;
	
if ($clientObj->load($client_id))
{
	$ttl = "View Nutrition Visit : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Nutrition Visit ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->nutrition_entry_date) ? new CDate($obj->nutrition_entry_date ) :  null;
		
$black_tea = explode(",", $obj->nutrition_blacktea);
$white_tea = explode(",", $obj->nutrition_whitetea);
$bread = explode(",", $obj->nutrition_bread);
$porridge = explode(",", $obj->nutrition_porridge);
$milk = explode(",", $obj->nutrition_milk);
$breastfeeding = explode(",", $obj->nutrition_breastfeeding);
$formula_milk = explode(",", $obj->nutrition_formula_milk);
$eggs = explode(",", $obj->nutrition_eggs);
$meat = explode(",", $obj->nutrition_meat);
$carbohydrates = explode(",", $obj->nutrition_carbohydrates);
$legumes = explode(",", $obj->nutrition_legumes);
$pancakes = explode(",", $obj->nutrition_pancake);
$vegetables = explode(",", $obj->nutrition_vegetables);
$fruits = explode(",", $obj->nutrition_fruit);
$diet_history_other = explode(",", $obj->nutrition_diet_history_others);		
$foodEnrichmentType = dPgetSysVal('FoodEnrichmentOptions');
$waterSourceTypes = dPgetSysVal('WaterSourceOptions');
$waterPurificationTypes = dPgetSysVal('WaterPurificationOptions');



if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new nutrition visit record').'" />', '',
		'<form action="?m=nutrition&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id > 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " . $clientObj->getFullName() );
	
if ($canEdit) {
	$titleBlock->addCrumb( "?m=nutrition&a=addedit&nutrition_id=$nutrition_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete nutrition visit record', $canDelete, $msg );
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
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Nutrition Visit').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=nutrition" method="post">
	<input type="hidden" name="dosql" value="do_nutrition_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="nutrition_id" value="<?php echo $nutrition_id;?>" />
</form>
<?php } ?>
<tr>
<td valign="top" width="100%">
	<table>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
       <tr>
         <td align="left"><?php echo $AppUI->_('Nutritionist');?>:</td>
		 <td align="left" class="hilite">
				<?php echo  $owners[$obj->nutrition_staff_id]; ?>        
			</td>
       </tr>    
	   <tr>
         <td align="left"><?php echo $AppUI->_('Center');?>:</td>
		 <td align="left" class="hilite">
				<?php echo $clinics[$obj->nutrition_center]; ?>        
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
         <td align="left"><?php echo $AppUI->_('Client Name');?>:</td>
         <td align="left" class="hilite">
		    <?php echo dPformSafe(@$clientObj->getFullName());?>
         </td>
       </tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Gender');?>:</td>

		<td align="left" class="hilite">
		<?php echo $genderTypes[$obj->nutrition_gender]; ?>
		</td>
       <tr>
         <td align="left"><?php echo $AppUI->_('Adm No');?>:</td>
         <td align="left" class="hilite">
          <?php echo dPformSafe(@$clientObj->client_adm_no);?>
         </td>
       </tr>
      <tr>
         <td align="left"><?php echo $AppUI->_('Date of Birth');?>:</td>
			<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
	 </tr>
	 <tr>
         <td align="left"><?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->nutrition_age_yrs);?>
		</td>
		</tr>
		<tr>
		<td align="left"><?php echo $AppUI->_('Age (months)');?></td>
		<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->nutrition_age_months);?>
		 </td>
	    </tr>
		<tr>
			<td align="left">&nbsp;</td>
			<td align="left" class="hilite">&nbsp;&nbsp;<?php echo $ageTypes[$obj->nutrition_age_status]; ?></td>
		</tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Caregiver');?>:</td>
		<td align="left" class="hilite">
		<?php echo $caregiverType[$obj->nutrition_caregiver_type]; ?>
		</td>
       </tr>
	   <tr>
         <td align="left"><?php echo $AppUI->_('Other');?>:</td>
		<td align="left" class="hilite">
		<?php echo dPformSafe(@$obj->nutrition_caregiver_type_notes);?>
		</td>
       </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Anthropometry'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	 
	  <tr>
        <td align="left"><?php echo $AppUI->_("Weight (kg)");?>:</td>
        <td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->nutrition_weight);?>
        </td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('Height (cm)');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_height;?></td>
      </tr>
      <tr>
			<td align="left"><?php echo $AppUI->_('z score');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_zscore;?></td>
      </tr>      
	  <tr>
			<td align="left"><?php echo $AppUI->_('MUAC (mm) ');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_muac;?></td>
      </tr> 
	  <tr>
			<td align="left"><?php echo $AppUI->_('WFH');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_wfh;?></td>
      </tr> 
	  <tr>
			<td align="left"><?php echo $AppUI->_('WFA');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_wfa;?></td>
      </tr> 
	  <tr>
			<td align="left"><?php echo $AppUI->_('BMI');?>:</td>
			<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_bmi;?>
			</td>
      </tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Diet History: Usual Food Intake'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>	  
	 <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Black tea');?>:</td>
		<td align="left" class="hilite">
			<?php
			foreach ($black_tea as $black_tea_time)
			{
			     echo $dietHistoryOptions[$black_tea_time] . "<br/>";
			}
			?>
		
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('White tea');?>:</td>
		<td align="left" class="hilite">
			<?php
			foreach ($white_tea as $white_tea_time)
			{
			     echo $dietHistoryOptions[$white_tea_time] . "<br/>";
			}
			?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Bread/ cake');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($bread as $bread_time)
			{
			     echo $dietHistoryOptions[$bread_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Porridge');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($porridge as $porridge_time)
			{
			     echo $dietHistoryOptions[$porridge_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Milk');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($milk as $milk_time)
			{
			     echo $dietHistoryOptions[$milk_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Breast feeding');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($breastfeeding as $breastfeeding_time)
			{
			     echo $dietHistoryOptions[$breastfeeding_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Formula milk');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($formula_milk as $formula_milk_time)
			{
			     echo $dietHistoryOptions[$formula_milk_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Eggs');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($eggs as $egg_time)
			{
			     echo $dietHistoryOptions[$egg_time] . "<br/>";
			}
		?>

		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Beef / Chicken /Fish');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($meat as $meat_time)
			{
			     echo $dietHistoryOptions[$meat_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Rice / Ugali / Tubers / Banana');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($carbohydrates as $carbohydrate_time)
			{
			     echo $dietHistoryOptions[$carbohydrate_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Legumes');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($legumes as $legume_time)
			{
			     echo $dietHistoryOptions[$legume_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Pancake/Chapatti');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($pancakes as $pancake_time)
			{
			     echo $dietHistoryOptions[$pancake_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Vegetables');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($vegetables as $vegetable_time)
			{
			     echo $dietHistoryOptions[$vegetable_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Fruit');?>:</td>
		<td align="left" class="hilite">
		<?php
			foreach ($fruits as $fruits_time)
			{
			     echo $dietHistoryOptions[$fruits_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Other (specify)');?>:
		 </td>
		  <td align="left" class="hilite"><?php echo $obj->nutrition_diet_history_notes;?>		
		 </td>
		 </tr>
		<tr>
		<td>&nbsp;</td>
		<td align="left" class="hilite">
		<?php
			foreach ($diet_history_other as $diet_history_other_time)
			{
			     echo $dietHistoryOptions[$diet_history_other_time] . "<br/>";
			}
		?>
		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('How are the foods enriched?');?></td>
		<td align="left" class="hilite">
		<?php echo $foodEnrichmentType[$obj->nutrition_food_enrichment]; ?>		</td>
       </tr>
	   <tr>
	   <td align="left" valign="top">...<?php echo $AppUI->_('Other');?>:</td>
	   <td align="left" class="hilite">
	   <?php echo $obj->nutrition_food_enrichment_notes;?>
	   </td>
	   </tr>
		<tr>
         <td align="left" valign="top">
		 <?php echo $AppUI->_('Where does the household ');?><br/>
		 <?php echo $AppUI->_('access water for daily use?');?>
		 </td>
		<td align="left" class="hilite">
		<?php echo $waterSourceTypes[$obj->nutrition_water_access]; ?>		</td>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('How do you purify drinking water?');?></td>
		<td align="left" class="hilite">
		<?php echo $waterPurificationTypes[$obj->nutrition_water_purification]; ?>		</td>
       </tr>	 
	   <tr>
	   <td align="left" valign="top">...<?php echo $AppUI->_('Other');?>:</td>
	   <td align="left" class="hilite">
	   <?php echo $obj->nutrition_water_purification_notes;?>
	   </td>
	   </tr>	   
	   
	   </table>
   <table>	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Needs Assessment and Services Rendered'); ?><br /></strong>
				<hr width="500" align="left" size=1 />
			</td>
	 </tr>
	<tr>
		  
	</tr>
	<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Identify any potential problems or concerns');?>:</td>
		<td>
		<table>
		 <tr>
        <td align="left" ><?php echo $AppUI->_('a.	Insufficient quantity (meal frequency)');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_quantity]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left" ><?php echo $AppUI->_('b.	Insufficient quality (dietary diversity)');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_quality]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left"><?php echo $AppUI->_('c.	Poor practices or preparation');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_poor_preparation]; ?>
		</td>		
       </tr>	   
	   <tr>
        <td align="left"><?php echo $AppUI->_('d.	Mixed feeding');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_mixed_feeding]; ?>
		</td>		
       </tr>	   
	   <tr>
        <td align="left"><?php echo $AppUI->_('e.	Unclean drinking water');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_unclean_drinking_water]; ?>
		</td>		
       </tr>
		
		</table>
		</td>
     </tr>
	<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Services Rendered');?>:</td>
		<td align="left">
		<table>
		 <tr>
        <td align="left"><?php echo $AppUI->_('a. Nutrition Education');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_education]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left"><?php echo $AppUI->_('b.	Nutrition Counselling');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_counselling]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left"><?php echo $AppUI->_('c.	Demonstration');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_demonstration]; ?>
		</td>		
       </tr>	 
	   <tr>
        <td align="left"><?php echo $AppUI->_('d.	Dietary Supplementation ');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_dietary_supplement]; ?>
		</td>		
       </tr>
	  </table>
		</td>
     </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Recommended Food Program');?>:</td>
		<td>
		<table>
		 <tr>
        <td align="left"><?php echo $AppUI->_('Nan');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_nan]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left"><?php echo $AppUI->_('Unimix');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_unimix]; ?>
		</td>		
       </tr> 
	   <tr>
        <td align="left"><?php echo $AppUI->_('Harvest Pro');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_harvest_pro]; ?>
		</td>		
	   </tr>
       <tr>	   
         <td align="left"><?php echo $AppUI->_('WFP');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_wfp]; ?>
		</td>		
       </tr>
	 <tr>
        <td align="left"><?php echo $AppUI->_('Insta');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_insta]; ?>
		</td>		
       </tr>
	 <tr>
        <td align="left"><?php echo $AppUI->_('RUTF');?>:</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_rutf]; ?>
		</td>		
       </tr>
	 <tr>
        <td align="left"><?php echo $AppUI->_('Other ');?>:&nbsp;<?php echo dPformSafe(@$obj->nutrition_other);?>
		</td>
		<td align="left" class="hilite">
		<?php echo $scoreTypes[$obj->nutrition_other_service]; ?>
		</td>		
		</table>
		</td>
     </tr>	   
	</table>
  </td>
  </tr>
</table>


</td>

	<td align='left'>
		<?php
 			require_once("./classes/CustomFields.class.php");
 			$custom_fields = New CustomFields( $m, $a, $obj->nutrition_id, "view" );
 			$custom_fields->printHTML();
		?>		
	</td>
</tr>



</table>