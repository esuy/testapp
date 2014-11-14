<?php 
$AppUI->savePlace();
require_once $AppUI->getModuleClass('counsellingwork');
require_once $AppUI->getModuleClass('clients');
// retrieve any state parameters
if (isset( $_GET['orderby'] )) {
    $orderdir = $AppUI->getState( 'CounsellingIdxOrderDir' ) ? ($AppUI->getState( 'CounsellingIdxOrderDir' )== 'asc' ? 'desc' : 'asc' ) : 'desc';
	$AppUI->setState( 'CounsellingIdxOrderBy', $_GET['orderby'] );
    $AppUI->setState( 'CounsellingIdxOrderDir', $orderdir);
}
$orderby         = $AppUI->getState( 'CounsellingIdxOrderBy' ) ? $AppUI->getState( 'CounsellingIdxOrderBy' ) : 'client_first_name';
$orderdir        = $AppUI->getState( 'CounsellingIdxOrderDir' ) ? $AppUI->getState( 'CounsellingIdxOrderDir' ) : 'asc';

// load the client types
//$types = dPgetSysVal( 'ClientStatus' );

// get any records denied from viewing
$obj = new CCounsellingWork();
$deny = $obj->getDeniedRecords( $AppUI->user_id );

$canEdit = !getDenyEdit( $m );
// retrieve list of records

$perms =& $AppUI->acl();

if (isset( $_GET['tab'] )) 
{
	$AppUI->setState( 'CounsellingIdxTab', $_GET['tab'] );
}
$counsellingTypeTab = defVal( $AppUI->getState( 'CounsellingIdxTab' ),  0 );

// $tabTypes = array(getCompanyTypeID('Client'), getCompanyTypeID('Supplier'), 0);
$counsellingType = $counsellingTypeTab;

$let = ':';

$q = new DBQuery;
$q->addTable('clients', 'a');
$q->leftJoin('counselling_work', 'b', 'b.counselling_client_id = a.client_id');
$q->addQuery("DISTINCT UPPER(SUBSTRING(client_first_name,1,1)) as L");

if ($counsellingType > 0)
{
	$q->addWhere("client_status = $clientType");
}

$arr = $q->loadList();

foreach ($arr as $L)
{
  $let .= $L['L'];
}

$a2z = "\n<table cellpadding=\"2\" cellspacing=\"1\" border=\"0\">";
$a2z .= "\n<tr>";
$a2z .= "<td width='100%' align='right'>" . $AppUI->_('Show'). ": </td>";
$a2z .= '<td><a href="./index.php?m=counsellingwork&where=0">' . $AppUI->_('All') . '</a></td>';

for ($c=65; $c < 91; $c++) 
{
	
	$cu = chr( $c );
	$cell = strpos($let, "$cu") > 0 ?
		"<a href=\"?m=counsellingwork&where=$cu\">$cu</a>" :
		"<font color=\"#999999\">$cu</font>";
	
	if ($cu == $where)
		$a2z .= "\n\t<td class=\"selected\">$cell</td>";
	else
		$a2z .= "\n\t<td>$cell</td>";
}

$a2z .= "\n</tr>\n<tr><td colspan='28'>$form</td></tr></table>";

// setup the title block
$titleBlock = new CTitleBlock( 'Counselling Services Log', 'handshake.png', $m, "$m.$a" );
$titleBlock->addCell ($a2z);

if (($canEdit)  && ($counsellingType > 1))
{
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_("new counselling services log entry").'">', '',
		'<form action="?m=counsellingwork&a=addedit" method="post">', '</form>'
	);
}


$search_string = addslashes($search_string);
$titleBlock->show();

$tabBox = new CTabBox( "?m=counsellingwork", dPgetConfig('root_dir')."/modules/counsellingwork/", $counsellingTypeTab );
$types[0] = "All Records";
/*if ($tabbed = $tabBox->isTabbed()) 
{
	$add_na = true;
	if (isset($types[0])) 
	{ // They have a Not Applicable entry.
		$add_na = false;
		$types[] = $types[0];
	}
	$types[0] = "All Records";
	if ($add_na)
		$types[] = "Not Applicable";
}
*/
//pager settings
$page = dPgetParam($_GET, 'page', 1);
$limit = intval($dPconfig['max_limit']);

$type_filter = array();
foreach($types as $type => $type_name){
	$type_filter[] = $type;
	$tabBox->add("vw_log", $type_name . " (" . $obj->getCount($type) . ")", false, $type );
}

$tabBox->show();
?>
