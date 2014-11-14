<?php

//1.First to find max value of activity ID and use it for storing in activity_* tables
//	Also store this ID in session to use for future saving of complete Activity info


function handleStaff($part, $list, $act_id, $mode) {

	$brief = preg_replace ( "/s$/", '', $part );
	$sql = ' DELETE FROM activity_' . $part . ' WHERE activity_' . $part . '_activity_id = "' . $act_id . '"';
	db_exec ( $sql );
	if ($mode != 'clean') {
		$fields  = array('activity_' . $part . '_activity_id','activity_' . $part . '_' . $brief . '_id');
		$vals = array('"'.$act_id.'"');

		$clientArray = array_map('mysql_real_escape_string',explode ( ",", $list ));
		if (! empty ( $list )) {
			if($part === 'caregivers'){
				$fields[]=' activity_caregivers_other';
				//$addValue=',';
			}/*else{

				$addValue='';
			}*/

			foreach ( $clientArray as $client ) {
				$tvars=$vals;
				if($part === 'caregivers' ){
					if( preg_match('/#@#/',$client) === 1){
					//$addValue=',"'.$client.'"';
						$tvars[]='0';
						$tvars[]='"'.$client.'"';
					}else{
						$tvars[]='"'.$client.'"';
						$tvars[]='null';
					}
					//$client='0';
				}else{
					//$addValue=',null';
					$tvars[]='"'.$client.'"';
				}
				$sql = ' INSERT INTO activity_' . $part . ' ('.implode(',',$fields).')
						VALUES ('.implode(',',$tvars).')';
				//var_dump($sql);
				db_exec ( $sql );
			}
		}
		return true;

	} else {
		return false;
	}


}

$opts = array ('clients', 'contacts', 'caregivers' );
$zmode = $_GET ['mode'];

if ($zmode == 'stuff') {
	if (isset ( $_POST ['act_id'] ) && intval ( $_POST ['act_id'] ) > 0) {
		$top = ( int ) $_POST ['act_id'];
	} else if (isset ( $_SESSION ['tmp_act_id'] )) {
		$top = ( int ) $_SESSION ['tmp_act_id'];
	} else {
		$sql = 'insert into activity (activity_date) values(now())';
		//$sql = 'select (max(activity_id) +1 ) as  top from activities ';
		$res = mysql_query ( $sql );
		$top = mysql_insert_id ();
		$_SESSION ['tmp_act_id'] = $top;
	}

	if ($top > 0) {
		$zcase = $_POST ['staff'];
		$zlist = $_POST ['list'];
		if (in_array ( $zcase, $opts )) {
			$res = handleStaff ( $zcase, $zlist, $top, $zmode );
			if ($res === true) {
				echo json_encode ( array ('res' => 'ok', 'id' => $top ) );
			}
		}
	} else {
		//Failed to find valid new id for activtity
		return false;
	}
} elseif ($zmode == 'clean' && ( int ) $_GET ['act_id'] > 0) {
	$top = ( int ) $_GET ['act_id'];
	foreach ( $opts as $val ) {
		handleStaff ( $val, '', $top, $zmode );
	}
	$sql = 'delete from activity where activity_id="' . $top . '" limit 1';
	mysql_query ( $sql );
	unset ( $_SESSION ['tmp_act_id'] );
}
?>