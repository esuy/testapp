<?php

$cset = array('Dandora','Kangemi','Kariobangi','Kawangware','Kibera','Mukuru');
$q0 = new DBQuery();
$q0->addTable('clinics');
$q0->setLimit(1);
$q0->addQuery('clinic_id');
foreach ($cset as $val) {
	$q = clone $q0;
	$q->addWhere('lower(clinic_name) = "'.strtolower($val).'"');
	$cid=$q->loadResult();
	if(!is_numeric($cid)){
		$sql='insert into clinics  (clinic_name,clinic_owner) values ("'.$val.'","1")';
		$res=mysql_query($sql);
	}
	unset($q);
}
?>