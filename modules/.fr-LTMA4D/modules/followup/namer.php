<?php

require_once $AppUI->getModuleClass ( 'followup' );

global $answer;

$answer = array ();
$ualone = 0;
if (isset ( $_GET ['nadm'] ) && trim ( $_GET ['nadm'] ) != '') {
	$admno = trim ( $_GET ['nadm'] );
	if (isset ( $_GET ['alone'] ) && ( int ) $_GET ['alone'] == 1) {
		$ualone = 1;
	}
	$answer = makeListPerson ( $admno, $ualone );
	
	echo json_encode ( $answer );
} else {
	echo 'fail';
}

return;
?>