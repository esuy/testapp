<?php

function buildList(){
	$q = new DBQuery();
	$q->addTable('clients','c');
	$q->addQuery('distinct c.client_id, client_adm_no, concat(client_first_name," ", client_last_name) as name');
	$q->addWhere('client_status="7"'); // exclude transfered to ltp and deceased
	$q->addOrder('name ASC');
	$q->leftJoin('ltp_transfers', 'lf', 'c.client_id=lf.client_id');
	$q->addWhere('lf.clinic_id is null');
	$list=$q->loadList();

	$clinicArray=centerList(TRUE);

	$ddown='<select name="cli_##CLNT##" class="d2chk">';
	foreach ($clinicArray as $key => $val) {
		if($key == 0){
			$dop=' selected="selected"';
		}else{
			$dop='';
		}
		$ddown.='<option value="'.$key.'" '.$dop.'>'.$val.'</option>';
	}
	$ddown.='</select>';


	$code='';
	if(count($list) > 0){
		foreach ($list as $row) {
			$code.='<tr><td>'.str_replace('##CLNT##', $row['client_id'],$ddown).'<td>'.$row['name'].'</td><td>'.$row['client_adm_no'].'</td></tr>'."\n";
		}
	}else{
		$code = 'fail';
	}
	return $code;
}

function checkLTPT(){
	$res='';
	$q = new DBQuery();
	$q->addQuery('lt.clinic_id,count(lt.id) as amount');
	$q->addTable('ltp_transfers','lt');
	$q->addWhere('lt.ondate is null');
	$q->addGroup('lt.clinic_id');
	$toexports=$q->loadList();
	$cents=array();
	if (count ( $toexports ) > 0) {
		$outh = array ();
		$clinarr = centerList ();
		foreach ( $toexports as $row ) {
			//$cents [$row ['clinic_id']] ++;

			$outh [] = array ('title' => $clinarr [$row['clinic_id']], 'cid' => $row['clinic_id'], 'amount' => $row['amount'] );

		}
		$res = json_encode ( $outh );
	}
	return $res;
}

function cancelClinic($clin_id,$olds=false){
	$sql='delete from ltp_transfers where clinic_id="'.mysql_real_escape_string($clin_id).'" and ondate '.($olds !== false ? '="'.mysql_real_escape_string($olds).'"' : ' is null');
	$res=mysql_query($sql);
	if($res){
		return 'ok';
	}
}

function getAllExports($clin_id){
	$sql='select count(distinct lt1.id) as amount ,
				lt2.ondate as edate,
				lt2.clinic_id as clinic
			from ltp_transfers lt1
				left join ltp_transfers lt2 on lt1.clinic_id = lt2.clinic_id and lt2.ondate=lt1.ondate
			where lt1.ondate is not null and lt2.clinic_id="'.$clin_id.'" and
				 lt1.clinic_id=lt2.clinic_id group by lt2.ondate';
	$res=mysql_query($sql);
	$html='';
	$zcnt=0;
	if($res){
		$zcnt=mysql_num_rows($res);
		$html='<table>';

		while ($dset = my_fetch_assoc($res)) {
			$html.='<tr><td>'.$dset['edate'].'</td><td><b>'.$dset['amount'].'</b>'.($dset['amount'] > 1 ? ' clients' : 'client').'</td><td><input type="button" class="text" onclick="exim.dateClean(this);" value="Cancel"></td></tr>';
		}
		$html.='</table>';
	}
	if($zcnt === 0){
		$html='<span>No clients transferred to the specified clinic</span>';
	}
	return $html;
}



function buildCFile(){
	$resh='fail';
	if(strlen($_POST['cparts'] ) > 0){
		$pcents=json_decode ( str_replace ( '\\"', '"', $_POST['cparts'] ) ,true);
		$centers=array();
		if(count($pcents) > 0){
			foreach ($pcents as $uname => $uval) {
				$clid=str_replace('cli_', '', $uname);
				if(!array_key_exists($uval, $centers)){
					$centers[$uval]=array();
				}
				$centers[$uval][]=$clid;
			}
			if(count($centers) > 0){
				$outh=array();
				$clinarr= centerList();
				foreach ($centers as $cid => $cusers) {
					$outh[]=array('title'=>$clinarr[$cid],'cid'=>$cid,'amount'=>count($cusers));
					$sql='insert into ltp_transfers (client_id, clinic_id) VALUES ';
					$sqlvs=array();
					foreach ($cusers as $cuser) {
						$sqlvs[]='("'.$cuser.'","'.$cid.'")';
					}
					$sql.=join(',', $sqlvs);
					$res=mysql_query($sql);
				}
				if(count($outh) > 0){
					$resh=json_encode($outh);
				}
			}
		}
	}
	return $resh;
}

function exportCs(){
	global $AppUI,$m;
	require_once $AppUI->getFileInModule($m, 'doexfile');
	exportProceed();
}

function importUsers(){
	if(count($_FILES) == 1){
		global $AppUI,$m;
		require_once $AppUI->getFileInModule($m, 'doimfile');
		importProceed();
	}
}
?>