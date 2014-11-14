<?php 

global $search_string;
global $owner_filter_id;
global $currentTabId;
global $currentTabName;
global $tabbed;
global $page;
global $type_filter;
global $orderby;
global $orderdir;
global $limit;

// load the company types

$types = dPgetSysVal('ClinicType');
$search = false;

$obj = new CClinic();
$allowedClinics = $obj->getAllowedRecords($AppUI->user_id, 'clinic_id, clinic_name');

$clinic_type_filter = $currentTabId;
//pager settings
$count = $obj->getCount($clinic_type_filter);

$where = $AppUI->getState( 'ClinicIdxWhere' ) ? $AppUI->getState( 'ClinicIdxWhere' ) : '%';

if ($where != '%') $search=true;


$clinicsType = true;

if (strncmp($currentTabName,"All Centers", strlen("All Centers")) == 0)
	$clinicsType = false;

if ($currentTabName == "Not Applicable")
	$clinic_type_filter = 0;

// retrieve list of records
$q  = new DBQuery;
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name, c.clinic_type, c.clinic_description');
if (count($allowedClinics) > 0) { $q->addWhere('c.clinic_id IN (' . implode(',', array_keys($allowedClinics)) . ')'); }
if ($clinicsType) { $q->addWhere('c.clinic_type = '.$clinic_type_filter); }
if ($search_string != "") { $q->addWhere("c.clinic_name LIKE '%$search_string%'"); }
//if ($owner_filter_id > 0) { $q->addWhere("c.clinic_owner = $owner_filter_id "); }
$q->addWhere("c.clinic_name LIKE '$where%'");
$q->addGroup('c.clinic_id');
//$q->addOrder($orderby.' '.$orderdir);

$rows = $q->loadList();


require_once($AppUI->getSystemClass("genericTable"));

$gt = new genericTable(true);

$headers = array("Center Name" => 'string');

$decs = array(0=>'<a href="./index.php?m=clinics&a=view&clinic_id=##1##" title="##2##">##0##</a>');

$gt->makeHeader($headers);
$s = '';
$CR = "\n"; // Why is this needed as a variable?

$none = true;

$row_data = array();
foreach ($rows as $rid => $row) {
	$none = false;

	$row_data[$rid] = array($row['clinic_name'],$row['clinic_id'],$row['clinic_description']);

	$gt->fillBody($row_data[$rid]);
}

if ($none) {
	$gt->addTableHtmlRow($CR . '<tr><td colspan="5">' . $AppUI->_( 'No clinics available' ) . '</td></tr>');
}

$gt->compile();