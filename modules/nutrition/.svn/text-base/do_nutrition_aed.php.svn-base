<?php /* NUTRITION $Id: do_company_aed.php,v 1.9 2005/04/26 06:55:42 ajdonnison Exp $ */

function findQty ($var){
	if(strstr($var,'qty_')){
		return true;
	}
}

$del = dPgetParam ( $_POST, 'del', 0 );
$obj = new CNutritionVisit ();
$msg = '';

$nutr_service_rows = dPgetParam ( $_POST, 'nutrition_service_rows', 0 );

if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}

require_once ($AppUI->getModuleClass ( "nutritionservice" ));

if (empty ( $obj->nutrition_weight )) {
	$obj->nutrition_weight = NULL;
}
if (empty ( $obj->nutrition_height )) {
	$obj->nutrition_height = NULL;
}
if (empty ( $obj->nutrition_age_yrs )) {
	$obj->nutrition_age_yrs = NULL;
}
if (empty ( $obj->nutrition_age_months )) {
	$obj->nutrition_age_months = NULL;
}

if (empty ( $obj->nutrition_zscore )) {
	$obj->nutrition_zscore = NULL;
}
if (empty ( $obj->nutrition_muac )) {
	$obj->nutrition_muac = NULL;
}
if (empty ( $obj->nutrition_wfh )) {
	$obj->nutrition_wfh = NULL;
}
if (empty ( $obj->nutrition_wfa )) {
	$obj->nutrition_wfa = NULL;
}
if (empty ( $obj->nutrition_bmi )) {
	$obj->nutrition_bmi = NULL;
}

require_once ("./classes/CustomFields.class.php");

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg ( 'Nutrition Visit' );
if ($del) {
	if (! $obj->canDelete ( $msg )) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	}
	if (($msg = $obj->delete ())) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
		$AppUI->redirect ();
	} else {
		$AppUI->setMsg ( 'deleted', UI_MSG_ALERT, true );
		$AppUI->redirect ( 'm=clients' );
	}
} else {
	if (! empty ( $_POST ["nutrition_entry_date"] )) {
		$entry_date = new CDate ( $_POST ["nutrition_entry_date"] );
		$obj->nutrition_entry_date = $entry_date->format ( FMT_DATETIME_MYSQL );
	}
	if (! empty ( $_POST ["nutrition_next_visit"] )) {
		$next_date = new CDate ( $_POST ["nutrition_next_visit"] );
		$obj->nutrition_next_visit = $next_date->format ( FMT_DATETIME_MYSQL );
	}
	if ((count ( $_POST ['nutrition_blacktea'] )) > 0) {
		$obj->nutrition_blacktea = implode ( ",", $_POST ['nutrition_blacktea'] );
	}
	if ((count ( $_POST ['nutrition_whitetea'] )) > 0) {
		$obj->nutrition_whitetea = implode ( ",", $_POST ['nutrition_whitetea'] );
	}
	if ((count ( $_POST ['nutrition_bread'] )) > 0) {
		$obj->nutrition_bread = implode ( ",", $_POST ['nutrition_bread'] );
	}
	if ((count ( $_POST ['nutrition_porridge'] )) > 0) {
		$obj->nutrition_porridge = implode ( ",", $_POST ['nutrition_porridge'] );
	}
	if ((count ( $_POST ['nutrition_milk'] )) > 0) {
		$obj->nutrition_milk = implode ( ",", $_POST ['nutrition_milk'] );
	}
	if ((count ( $_POST ['nutrition_banan'] )) > 0) {
		$obj->nutrition_banan = implode ( ",", $_POST ['nutrition_banan'] );
	}
	if ((count ( $_POST ['nutrition_rice'] )) > 0) {
		$obj->nutrition_rice = implode ( ",", $_POST ['nutrition_rice'] );
	}
	if ((count ( $_POST ['nutrition_tubers'] )) > 0) {
		$obj->nutrition_tubers = implode ( ",", $_POST ['nutrition_tubers'] );
	}
	if ((count ( $_POST ['nutrition_ugali'] )) > 0) {
		$obj->nutrition_ugali = implode ( ",", $_POST ['nutrition_ugali'] );
	}
	if ((count ( $_POST ['nutrition_wheat'] )) > 0) {
		$obj->nutrition_wheat = implode ( ",", $_POST ['nutrition_wheat'] );
	}
	if ((count ( $_POST ['nutrition_carbos_notes'] )) > 0) {
		$obj->nutrition_carbos_notes = implode ( ",", $_POST ['nutrition_carbos_notes'] );
	}
	if ((count ( $_POST ['nutrition_beverages_notes'] )) > 0) {
		$obj->nutrition_beverages_notes = implode ( ",", $_POST ['nutrition_beverages_notes'] );
	}
	if ((count ( $_POST ['nutrition_protein_notes'] )) > 0) {
		$obj->nutrition_protein_notes = implode ( ",", $_POST ['nutrition_protein_notes'] );
	}
	if ((count ( $_POST ['nutrition_breastfeeding'] )) > 0) {
		$obj->nutrition_breastfeeding = implode ( ",", $_POST ['nutrition_breastfeeding'] );
	}
	if ((count ( $_POST ['nutrition_formula_milk'] )) > 0) {
		$obj->nutrition_formula_milk = implode ( ",", $_POST ['nutrition_formula_milk'] );
	}
	if ((count ( $_POST ['nutrition_eggs'] )) > 0) {
		$obj->nutrition_eggs = implode ( ",", $_POST ['nutrition_eggs'] );
	}
	if ((count ( $_POST ['nutrition_meat'] )) > 0) {
		$obj->nutrition_meat = implode ( ",", $_POST ['nutrition_meat'] );
	}
	if ((count ( $_POST ['nutrition_carbohydrates'] )) > 0) {
		$obj->nutrition_carbohydrates = implode ( ",", $_POST ['nutrition_carbohydrates'] );
	}
	if ((count ( $_POST ['nutrition_legumes'] )) > 0) {
		$obj->nutrition_legumes = implode ( ",", $_POST ['nutrition_legumes'] );
	}
	if ((count ( $_POST ['nutrition_pancake'] )) > 0) {
		$obj->nutrition_pancake = implode ( ",", $_POST ['nutrition_pancake'] );
	}
	if ((count ( $_POST ['nutrition_vegetables'] )) > 0) {
		$obj->nutrition_vegetables = implode ( ",", $_POST ['nutrition_vegetables'] );
	}
	if ((count ( $_POST ['nutrition_fruit'] )) > 0) {
		$obj->nutrition_fruit = implode ( ",", $_POST ['nutrition_fruit'] );
	}
	if ((count ( $_POST ['nutrition_fat'] )) > 0) {
		$obj->nutrition_fat = implode ( ",", $_POST ['nutrition_fat'] );
	}
	if ((count ( $_POST ['nutrition_water'] )) > 0) {
		$obj->nutrition_water = implode ( ",", $_POST ['nutrition_water'] );
	}
	if ((count ( $_POST ['nutrition_water_purification'] )) > 0) {
		$obj->nutrition_water_purification = implode ( ",", $_POST ['nutrition_water_purification'] );
	}
	if ((count ( $_POST ['nutrition_water_access'] )) > 0) {
		$obj->nutrition_water_access = implode ( ",", $_POST ['nutrition_water_access'] );
	}
	if ((count ( $_POST ['nutrition_food_enrichment'] )) > 0) {
		$obj->nutrition_food_enrichment = implode ( ",", $_POST ['nutrition_food_enrichment'] );
	}

	if ((count ( $_POST ['nutrition_diet_history_others'] )) > 0) {
		$obj->nutrition_diet_history_others = implode ( ",", $_POST ['nutrition_diet_history_others'] );
	}
	if ((count ( $_POST ['nutrition_program'] )) > 0) {
		$obj->nutrition_program = implode ( ",", $_POST ['nutrition_program'] );
	}
	if ((count ( $_POST ['nutrition_rendered'] )) > 0) {
		$obj->nutrition_rendered = implode ( ",", $_POST ['nutrition_rendered'] );
	}
	if ($msg = $obj->store ()) {
		$AppUI->setMsg ( $msg, UI_MSG_ERROR );
	} else {

		//db_exec("update clients set client_lvd = '".$obj->nutrition_entry_date."',client_lvd_form='nutrition_visit' where 
		//client_id = '".$obj->nutrition_client_id."' and client_lvd < '".$obj->nutrition_entry_date."'");
		updateLVD('nutrition_visit',$obj->nutrition_client_id,$obj->nutrition_entry_date,isset($_POST['force_lvd_update']));
		
		if (($nutr_service_rows > 0) /*&& (! empty ( $_POST ["program_1"] ))*/) {

			$pkeys = array_keys($_POST);
			$needKeys = array_filter($pkeys,'findQty');

			$sql = 'DELETE FROM nutrition_service WHERE nutrition_service_visit_id = ' . $obj->nutrition_id;
			db_exec ( $sql );

			//for($count = 1; $count < $nutr_service_rows; $count ++) {
			if(count($needKeys) > 0){
				foreach ($needKeys as $nkey) {
					$xcnt= preg_match('/_(\d+)$/',$nkey,$countz);
					$count=$countz[1];
					if(is_numeric($count) && intval($count) > 0){
						$nsObj = new CNutritionService ();
						$nsObj->nutrition_service_client_id = $obj->nutrition_client_id;
						$nsObj->nutrition_service_visit_id = $obj->nutrition_id;
						$nsObj->nutrition_service_program = $_POST ['program_'.$count];
						$nsObj->nutrition_service_item = $_POST ['item_'.$count];
						$nsObj->nutrition_service_qty = $_POST ['qty_'.$count];
						$nsObj->store ();
					}
				}
			}
		}
		$custom_fields = New CustomFields ( $m, 'addedit', $obj->nutrition_id, "edit" );
		$custom_fields->bind ( $_POST );
		$sql = $custom_fields->store ( $obj->nutrition_id ); // Store Custom Fields
		$AppUI->setMsg ( @$_POST ['nutrition_id'] ? 'updated' : 'added', UI_MSG_OK, true );
	}
	$AppUI->redirect ( 'm=clients&a=view&client_id=' . $obj->nutrition_client_id );
}
?>
