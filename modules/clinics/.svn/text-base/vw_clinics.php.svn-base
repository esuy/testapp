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
$num_pages = ceil ($count / $limit);
$offset = ($page - 1) * $limit;
//var_dump($num_pages);
if ($offset < 0)
{
	$limit = intval($count);
	$offset = 0;
}

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
$q->addOrder($orderby.' '.$orderdir);

$rows = $q->loadList();

echo printPageNavigation( '?m=clinics', $page, $num_pages, $offset, $limit, $count, 'centers');

?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<td nowrap="nowrap" width="60" align="right">&nbsp;<?php echo $AppUI->_('sort by');?>:&nbsp;</td>
	<th nowrap="nowrap">
		<a href="?m=clinics&orderby=clinic_name" class="hdr"><?php echo $AppUI->_('Center Name');?></a>
	</th>
</tr>
<?php
$s = '';
$CR = "\n"; // Why is this needed as a variable?

$none = true;
foreach ($rows as $row) {
	$none = false;
	$s .= $CR . '<tr>';
	$s .= $CR . '<td>&nbsp;</td>';
	$s .= $CR . '<td><a href="./index.php?m=clinics&a=view&clinic_id=' . $row["clinic_id"] . '" title="'.$row['clinic_description'].'">' . $row["clinic_name"] .'</a></td>';
	$s .= $CR . '</tr>';
}
echo "$s\n";
if ($none) {
	echo $CR . '<tr><td colspan="5">' . $AppUI->_( 'No clinics available' ) . '</td></tr>';
}
?>
</table>
<?php
   echo printPageNavigation( '?m=clinics', $page, $num_pages, $offset, $limit, $count, 'clinics');
?>
