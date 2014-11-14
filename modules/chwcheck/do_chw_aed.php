<?php

require_once($AppUI->getModuleClass("chwcheck"));
if($_SERVER['CONTENT_LENGTH'] > 0){
	$siis = json_decode(stripslashes($_POST['jfk']));

	$chmode = 'added';

	$entries = $_POST['fentry'];
	if(count($entries) > 0){
		$dbe = new CCHWCheck();

		$dbe->chw_village = mysql_real_escape_string($_POST['chw_village']);
		$dbe->chw_name = mysql_real_escape_string($_POST['chw_name']);
		$dbe->chw_center_id = (int)$_POST['chw_center_id'];
		$dbe->chw_location = mysql_real_escape_string($_POST['chw_location']);

		foreach ($entries as $key => $evals){
			$dbe1 = clone $dbe;
			$dbe1->bind($evals);
			$entry_date = new CDate ( $evals ["entry_date"] );
			$dbe1->chw_entry_date = $entry_date->format ( FMT_DATE_MYSQL );

			$dbe1->chw_adm_no = mysql_real_escape_string($evals['chw_adm_no']);

			/*$dbe1->chw_old= $evals['chw_old'];
			$dbe1->chw_sex = $evals['chw_sex'];
			$dbe1->chw_age = $evals['chw_age'];*/

			if($evals['chw_arv'] == '2'){
				$dbe1->chw_arv_note = null;
			}
			if($evals['chw_oir'] == '2'){
				$dbe1->chw_oir_note = null;
			}
			$dbe1->chw_assess = @join(',',$siis->store->issue[$key]);
			$dbe1->chw_support = @join(',',$siis->store->service[$key]);
			if(count($evals['maly']) > 0 && $dbe1->chw_old == '2'){
				$dbe1->chw_comm_mob = makeCMs($evals);
			}else{
				$dbe1->chw_comm_mob = null;
			}
			$dbe1->chw_remarks = mysql_real_escape_string($evals['chw_remarks']);
			//$dbe1->chw_refers = mysql_real_escape_string($evals['chw_refers']);
			$dbe1->chw_refers = @implode(',',$siis->store->refs[$key]);
			$dbe1->chw_adh_support = @implode(',',$siis->store->adhs[$key]);

			if(isset($evals['chw_id']) && (int)$evals['chw_id'] > 0) {
				$dbe1->chw_id = (int)$evals['chw_id'];
				$chmode = ' changes saved';
			}
			$dbe1->store();

			unset($dbe1);
		}
		$AppUI->setMsg( 'CHW '.$chmode, UI_MSG_OK, false );
	}

	$AppUI->redirect("m=clients");
}

function makeCMs ($row){
	$ops=array('ma','my','fa','fy');
	$res=array();
	foreach ($ops as $key => $op){
		if(isset($row['maly'][$op]) ){
			$res[]=$row['maly'][$op];
		}else{
			$res[]=null;
		}
	}
	return @join(",",$res);
}
?>
