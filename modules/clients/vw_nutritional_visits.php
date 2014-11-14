<?php
global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('nutrition');
require_once $AppUI->getModuleClass('admission');
require_once $AppUI->getModuleClass('social');
require_once $AppUI->getModuleClass('counsellinginfo');

$title = 'new nutritional visit...';
$df = $AppUI->getPref('SHDATEFORMAT');
$q = new DBQuery;
$q->addTable('nutrition_visit');
$q->addQuery ('nutrition_visit.*');
$q->addWhere('nutrition_visit.nutrition_client_id = '.$client_id);
$q->addOrder('nutrition_visit.nutrition_entry_date desc');
$w ='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadList())){
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	$title="add nutritional visit...";
	$url = "./index.php?m=nutrition&a=addedit&client_id=$client_id";

}else{

	// collect all the users for the staff list
	$q  = new DBQuery;
	$q->addTable('contacts','con');
	$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
	$q->addQuery('contact_id');
	$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
	$q->addOrder('contact_last_name');
	$owners = $q->loadHashList();


	//load social and counselling info

	if (!empty($client_id))	{
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

	if (!empty($client_id))	{
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

?>
<table width="100%" border="0" cellpadding="2" cellspacing="1"
	class="tbl">
	<tr>
		<th><?php echo $AppUI->_( 'Visit Date' );?></td>
		<th><?php echo $AppUI->_( 'Nutritionist' );?></td>
		<th><?php echo $AppUI->_( 'Z Score' );?></td>
		<th><?php echo $AppUI->_( 'MUAC' );?></td>

	</tr>
<?php

	foreach ($rows as $row){
		$url = "./index.php?m=nutritional&a=addedit&client_id=$client_id&nutrition_id=".$row["nutrition_id"];
		$view_url= "./index.php?m=clients&a=view&nutrition_id=".$row["nutrition_id"]."&client_id=".$client_id;
		$nutritionalObj = new CNutritionVisit();
		$nutritionalObj->load($row["nutrition_id"]);
		$entry_date = intval( $nutritionalObj->nutrition_entry_date ) ? new CDate( $nutritionalObj->nutrition_entry_date ) : NULL;
		$visit_date = ($entry_date != NULL) ? $entry_date->format($df) : "";

		$w .= '<tr>';
		$w .= '<td><a href="'.$view_url.'">'. $visit_date.'</a></td>';
		$w .= '<td><a href="'.$view_url.'">'. $owners[$nutritionalObj->nutrition_staff_id].'</a></td>';
		$w .= '<td><a href="'.$view_url.'">'. $nutritionalObj->nutrition_zscore.'</a></td>';
		$w .= '<td><a href="'.$view_url. '">'. $nutritionalObj->nutrition_muac.'</a></td>';
		$w .= '</tr>';
	}
}

$w .= '<tr><td colspan="4" align="right" valign="top" style="background-color:#ffffff">';
$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new nutritional visit' ).'" onClick="javascript:window.location=\'./index.php?m=nutrition&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
$w .= '</td></tr>';
echo $w;

?>

</table>
<?php /* NUTRITION VISIT $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$nutrition_id = intval( dPgetParam( $_GET, "nutrition_id", $rows[0]["nutrition_id"] ) );
$client_id = intval( dPgetParam( $_GET, "client_id", $client_id ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( 'nutrition', 'view', $nutrition_id );
$canEdit = $perms->checkModuleItem( 'nutrition', 'edit', $nutrition_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}


$dietHistoryOptions = dPgetSysVal('DietHistoryOptions');
// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CNutritionVisit();
$canDelete = $obj->canDelete( $msg, $nutrition_id );

// load the record data

if ($nutrition_id > 0)
{
	$q  = new DBQuery;
	$q->addTable('nutrition_visit');
	$q->addQuery('nutrition_visit.*');
	$q->addWhere('nutrition_visit.nutrition_id = '.$nutrition_id);
	$sql = $q->prepare();
	$q->clear();


	if (!db_loadObject( $sql, $obj )) {
		$AppUI->setMsg( 'Nutrition Visit' );
		$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
		$AppUI->redirect();
	} else {
		$AppUI->savePlace();
	}

	/*
	// collect all the users for the staff list
	$q  = new DBQuery;
	$q->addTable('contacts','con');
	$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
	$q->addQuery('user_id');
	$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
	$q->addOrder('contact_last_name');
	$owners = $q->loadHashList();
	*/

	// collect all the users for the staff list
	$q  = new DBQuery;
	$q->addTable('contacts','con');
	$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
	//$q->addQuery('contact_id');
	$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
	$q->addWhere('contact_id='.$obj->nutrition_staff_id);
	//$q->addOrder('contact_last_name');
	$ownerName = $q->loadResult();//$q->loadHashList();

	$renders = dPgetSysVal('NutritionRendered');


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
	$entry_date = intval($obj->nutrition_entry_date) ? new CDate($obj->nutrition_entry_date ) :  null;
	$next_date= intval($obj->nutrition_next_visit) ? new CDate($obj->nutrition_next_visit ) :  null;


	$refers = dPgetSysVal('PositionOptions'); // ('NutritionReferer');
	//load client
	$clientObj = new CClient();
	$client_id = $client_id ? $client_id : $obj->nutrition_client_id;
	// setup the title block
	$date_string = $entry_date ? $entry_date->format($df) : "";
	if ($date_string)	{
		$ttl = "Details of Nutrition Visit : " . $date_string;
	}
	else{
		$ttl = "Details of Nutrition Visit ";
	}
	$q  = new DBQuery;
	$q->addTable('clinics', 'c');
	$q->addQuery('c.clinic_name');
	$q->addWhere('clinic_id='.$obj->nutrition_center);
	$clinicName=$q->loadResult();
	if (!empty($client_id))	{
		$q  = new DBQuery;
		$q->addTable('admission_info');
		$q->addQuery('admission_info.*');
		$q->addWhere('admission_info.admission_client_id = '.$client_id);
		$sql = $q->prepare();
		//var_dump($sql);
		$q->clear();
		$admissionObj = new CAdmissionRecord();
		db_loadObject( $sql, $admissionObj );
	}
	$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );

	$black_tea = explode(",", $obj->nutrition_blacktea);
	$white_tea = explode(",", $obj->nutrition_whitetea);
	$bread = explode(",", $obj->nutrition_bread);
	$porridge = explode(",", $obj->nutrition_porridge);
	$milk = explode(",", $obj->nutrition_milk);
	$water = explode(',',$obj->nutrition_water);
	$ugali = explode(',',$obj->nutrition_ugali);
	$rice = explode(',',$obj->nutrition_rice);
	$banans = explode(',',$obj->nutrition_banan);
	$tubers = explode(',',$obj->nutrition_tubers);
	$wheat = explode(',',$obj->nutrition_wheat);
	$carb_others = explode(',',$obj->nutrition_carbos_notes);
	$bev_others = explode(',',$obj->nutrition_beverages_notes);
	$breastfeeding = explode(",", $obj->nutrition_breastfeeding);
	$formula_milk = explode(",", $obj->nutrition_formula_milk);
	$eggs = explode(",", $obj->nutrition_eggs);
	$meat = explode(",", $obj->nutrition_meat);
	$carbohydrates = explode(",", $obj->nutrition_carbohydrates);
	$protein_others = explode(",", $obj->nutrition_protein_notes);
	$legumes = explode(",", $obj->nutrition_legumes);
	$pancake = explode(",", $obj->nutrition_pancake);
	$vegetables = explode(",", $obj->nutrition_vegetables);
	$fruit = explode(",", $obj->nutrition_fruit);
	$fat = explode(",", $obj->nutrition_fat);
	$others = explode(",", $obj->nutrition_diet_history_others);
	$water_purification = explode(",", $obj->nutrition_water_purification);
	$water_access = explode(",", $obj->nutrition_water_access);
	$food_enrichment = explode(",", $obj->nutrition_food_enrichment);

	$diet_history_other = explode(",", $obj->nutrition_diet_history_others);
	$renders = dPgetSysVal("NutritionRendered");
	$foodEnrichmentType = dPgetSysVal('FoodEnrichmentOptions');
	$waterSourceTypes = dPgetSysVal('WaterSourceOptions');
	$waterPurificationTypes = dPgetSysVal('WaterPurificationOptions');

	$dietLength = count($dietHistoryOptions);

	$boolRev = array_reverse(dPgetSysVal("NoYes"),true);

	$programs = dPgetSysVal('NutritionProgram');

	$next_date = intval( $obj->nutrition_next_visit) ? new CDate( $obj->nutrition_next_visit ) : "";

	$q = new DBQuery();
	$q->addWhere('nutrition_service_visit_id='.$obj->nutrition_id);
	$q->addTable('nutrition_service');
	$q->addWhere('nutrition_service_client_id='.$client_id);
	$progz= $q->loadList();


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

<table border="0" cellpadding="4" cellspacing="0" width="100%"
	class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=nutrition" method="post"><input
		type="hidden" name="dosql" value="do_nutrition_aed" /> <input
		type="hidden" name="del" value="1" /> <input type="hidden"
		name="nutrition_id" value="<?php echo $nutrition_id;?>" /></form>
<?php } ?>
<tr>
		<td valign="top" width="100%">
		<table>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Details'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>			
			<tr>
				<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite">
				<?php echo $clinicName; ?>
			</td>
			</tr>
			<tr>
				<td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
			</tr>
			<tr>
				<td align="left">1c.<?php echo $AppUI->_('Nutritionist');?>:</td>
				<td align="left" class="hilite">
				<?php echo  $ownerName/*$owners[$obj->nutrition_staff_id];*/ ?>
			</td>
			</tr>
			<tr>
				<td align="left">3a.<?php echo $AppUI->_('Age (years)');?>:</td>
				<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->nutrition_age_yrs);?>
		 </td>
			</tr>
			<tr>
				<td><?php echo $AppUI->_('Age (months)');?>:</td>
				<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->nutrition_age_months);?>
		 </td>
		</tr>
		<tr>
			<td align="left">3b.<?php echo $AppUI->_('Child attending');?>:</td>
			<td align="left" class="hilite">
		<?php echo $boolTypes[$obj->nutrition_child_attend]; ?>
		</td>
		</tr>
		<tr>
			<td align="left">3c.<?php echo $AppUI->_('Caregiver attending');?>:</td>
			<td align="left" class="hilite">
		<?php echo $boolTypes[$obj->nutrition_care_attend]; ?>
		</td>
		</tr>
		<tr>
			<td align="left">3d.<?php echo $AppUI->_('Caregiver - Who');?>:</td>
			<td align="left" class="hilite">
		<?php echo $obj->nutrition_care_who; ?>
		</td>
		</tr>		
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Anthropometry'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">4a.<?php echo $AppUI->_("Weight (kg)");?>:</td>
				<td align="left" class="hilite">
            <?php echo dPformSafe(@$obj->nutrition_weight);?>
        </td>
			</tr>
			<tr>
				<td align="left">4b.<?php echo $AppUI->_('Height (cm)');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_height;?></td>
			</tr>
			<!-- <tr>
				<td align="left"><?php echo $AppUI->_('z score');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_zscore;?></td>
			</tr> -->
			<tr>
				<td align="left">4c.<?php echo $AppUI->_('MUAC (mm) ');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_muac;?></td>
			</tr>
			<tr>
				<td align="left">4d.<?php echo $AppUI->_('Oedema');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->nutrition_oedema];?></td>
			</tr>
			<tr>
				<td align="left">5a.<?php echo $AppUI->_('WFH');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_wfh;?></td>
			</tr>
			<tr>
				<td align="left">5b.<?php echo $AppUI->_('WFA');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_wfa;?></td>
			</tr>
			<tr>
				<td align="left">5c,d.<?php echo $AppUI->_('BMI');?>:</td>
				<td align="left" valign="top" class="hilite"><?php echo $obj->nutrition_bmi;?>
			</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Diet History: Usual Food Intake'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>

	<tr>
	 <td colspan="2">
	 <table>
	 	<thead>
	 	<tr>
	 		<th colspan="2">Food Item</th>
	 		<th >Breakfast</th>
	 		<th >Mid-morning</th>
	 		<th >Lunch</th>
	 		<th >Mid-afternoon</th>
	 		<th >Supper</th>
	 	</tr>
	 	</thead>
	 	<tbody>
	 	<tr>
	 		<td rowspan="5">6.Beverages</td>
         	<td align="left" valign="top"><?php echo $AppUI->_('Black tea');?>:</td>
			<?php echo showValuesMultiCol( $black_tea, $dietLength); ?>
		</tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('White tea');?>:</td>
		<?php echo showValuesMultiCol( $white_tea, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Porridge');?>:</td>
		<?php echo showValuesMultiCol( $porridge, $dietLength);   ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Water');?>:</td>
		<?php echo showValuesMultiCol( $water, $dietLength);?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:&nbsp;&nbsp;
		<?php echo $obj->nutrition_beverages_title;?>
         </td>
		<?php echo showValuesMultiCol( $bev_others, $dietLength);  ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="6">7.Carbohydrates</td>
         	<td align="left" valign="top"><?php echo $AppUI->_(' Ugali / Maize');?>:</td>
			<?php
			echo showValuesMultiCol( $ugali, $dietLength);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Rice');?>:</td>
		<?php echo showValuesMultiCol( $rice, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Bananas');?>:</td>
		<?php echo showValuesMultiCol( $banans, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Tubers');?>:</td>
		<?php echo showValuesMultiCol( $tubers, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Wheat products');?>:</td>
		<?php echo showValuesMultiCol( $wheat, $dietLength);  ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:&nbsp;&nbsp;
         	<?php echo $obj->nutrition_carbos_title;?>
         </td>
		<?php echo showValuesMultiCol( $carb_others, $dietLength); ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="5">8.Protein</td>
         	<td align="left" valign="top"><?php echo $AppUI->_(' Legumes / Pulses / Nuts');?>:</td>
			<?php
			echo showValuesMultiCol( $legumes, $dietLength);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Milk / Milk products');?>:</td>
		<?php echo showValuesMultiCol( $milk, $dietLength);  ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Meat / Meat products');?>:</td>
		<?php echo showValuesMultiCol( $meat, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Eggs');?>:</td>
		<?php echo showValuesMultiCol( $eggs, $dietLength); ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Others');?>:
         	<?php echo $obj->nutrition_protein_title;?>
         </td>
		<?php echo showValuesMultiCol( $protein_others, $dietLength);  ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       <tr>
	 		<td rowspan="5">Others</td>
         	<td align="left" valign="top"><?php echo $AppUI->_('Vegetables');?>:</td>
			<?php
			echo showValuesMultiCol( $vegetables, $dietLength);
			// echo arraySelectCheckbox($dietHistoryOptions, "nutrition_carbohydrates[]", '', $carbohydrates );
			?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Fruits / Juices');?>:</td>
		<?php echo showValuesMultiCol( $fruit, $dietLength);  ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Fats and Oils');?>:</td>
		<?php echo showValuesMultiCol( $fat, $dietLength);  ?>
       </tr>
       <tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Breast Milk');?>:</td>
		 <?php echo showValuesMultiCol( $breastfeeding, $dietLength); ?>
       </tr>
		<tr>
         <td align="left" valign="top"><?php echo $AppUI->_('Formula milk');?>:</td>
		<?php echo showValuesMultiCol( $formula_milk, $dietLength);  ?>
       </tr>
       <tr><td colspan="7" style="border-bottom: 2px solid #000;"></td> </tr>
       </tbody>
	 </table>
	 </td>
	 </tr>


			<tr>
				<td align="left" valign="top">10.<?php echo $AppUI->_('How are the foods enriched?');?></td>
				<td align="left" class="hilite">
		<?php
		foreach ($food_enrichment as $food_enrichment_option){
			echo $foodEnrichmentType[$food_enrichment_option] . "<br/>";
		}
		?>
		</td>
			</tr>
			<tr>
				<td align="left" valign="top">10f...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
	   <?php echo $obj->nutrition_food_enrichment_notes;?>
	   </td>
			</tr>
			<tr>
				<td align="left" valign="top">11.
		 <?php echo $AppUI->_('Where does the household ');?><br />
		 <?php echo $AppUI->_('access water for daily use?');?>
		 </td>
				<td align="left" class="hilite">
		<?php
		foreach ($water_access as $water_access_option)	{
			echo $waterSourceTypes[$water_access_option] . "<br/>";
		}
		?>
		</td>
			</tr>
			<tr>
				<td align="left" valign="top">12.<?php echo $AppUI->_('How do you purify drinking water?');?></td>
				<td align="left" class="hilite">
		<?php
		foreach ($water_purification as $water_purification_option)	{
			echo $waterPurificationTypes[$water_purification_option] . "<br/>";
		}
		?>
		</td>
			</tr>
			<tr>
				<td align="left" valign="top">12d...<?php echo $AppUI->_('Other');?>:</td>
				<td align="left" class="hilite">
	   <?php echo $obj->nutrition_water_purification_notes;?>
	   </td>
			</tr>

		</table>
		<table>
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Needs Assessment and Services Rendered'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>

			</tr>
			<tr>
				<td align="left" valign="top">13.<?php echo $AppUI->_('Identify any potential problems or concerns');?>:</td>
				<td>
				<table>
					<tr>
						<td align="left"><?php echo $AppUI->_('a.	Adequeate quantity (meal frequency)');?>:</td>
						<td align="left" class="hilite">
		<?php echo $boolRev[$obj->nutrition_quantity]; ?>
		</td>
					</tr>
					<tr>
						<td align="left"><?php echo $AppUI->_('b.	Adequeate quality (dietary diversity)');?>:</td>
						<td align="left" class="hilite">
		<?php echo $boolRev[$obj->nutrition_quality]; ?>
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
				<td align="left">13f...<?php echo $AppUI->_('Other ');?>:</td>
				<td align="left" class="hilite">
					<?php echo $obj->nutrition_issue_notes; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">14.<?php echo $AppUI->_('Recommended Food Program');?>:</td>
				<td class="hilite" align="left">
					<?php
					echo buildStringVals(dPgetSysVal("NutritionProgram"),$obj->nutrition_program);
					?>
				</td>
			</tr>
			<tr>
				<td align="left">...<?php echo $AppUI->_('Other ');?>:&nbsp;</td>
				<td align="left" class="hilite">
					<?php echo $obj->nutrition_program_other; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">15.<?php echo $AppUI->_('Services Rendered');?>:</td>
				<td align="left" class="hilite">
					<?php
					echo buildStringVals($renders,$obj->nutrition_rendered);
					?>
				</td>
			</tr>
			<tr>
				<td align="left">...<?php echo $AppUI->_('Other ');?>:</td>
				<td align="left" class="hilite">
					<?php echo $obj->nutrition_service_other; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="tbl">
						<thead>
							<tr>
								<th>Received Food Program</th>
								<th>Item</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($progz as $pitem) {
								echo '<tr class="hilite"><td>'.$programs[$pitem['nutrition_service_program']].'</td>
										<td>'.$pitem['nutrition_service_item'].'</td>
										<td>'.$pitem['nutrition_service_qty'].'</td>
									</tr>';
							}
							?>
						</tbody>
					</table>
				</td>
			</tr>	
			<tr>
				<td align="left" valign="top">20.<?php echo $AppUI->_('Refer To');?>:</td>
				<td align="left" class="hilite">
					<?php
					echo $refers[$obj->nutrition_refer];
					?>
				</td>
			</tr>
			<tr>

				<td align="left">20b...<?php echo $AppUI->_('Other ');?>:</td>
				<td align="left" class="hilite">
					<?php echo $obj->nutrition_refer_other; ?>
				</td>
			</tr>
			<tr>
				<td align="left">21.<?php echo $AppUI->_('Next appointment');?>: </td>
				<td align="left" class="hilite">
				<?php echo $next_date ? $next_date->format( $df ) : "" ;?>
			</td>
			</tr>
			<tr>
				<td align="left">22.<?php echo $AppUI->_('Comments');?>:</td>
				<td align="left" class="hilite">
		 <?php echo wordwrap( str_replace( chr(10), "<br />", $obj->nutrition_notes), 75,"<br />", true);?>
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

<?php } ?>
