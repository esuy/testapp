<?php 
$AppUI->savePlace();

// retrieve any state parameters
if (isset( $_GET['orderby'] )) {
    $orderdir = $AppUI->getState( 'SocialIdxOrderDir' ) ? ($AppUI->getState( 'SocialIdxOrderDir' )== 'asc' ? 'desc' : 'asc' ) : 'desc';
	$AppUI->setState( 'SocialIdxOrderBy', $_GET['orderby'] );
    $AppUI->setState( 'SocialIdxOrderDir', $orderdir);
}
$orderby         = $AppUI->getState( 'SocialIdxOrderBy' ) ? $AppUI->getState( 'SocialIdxOrderBy' ) : 'client_first_name';
$orderdir        = $AppUI->getState( 'SocialIdxOrderDir' ) ? $AppUI->getState( 'SocialIdxOrderDir' ) : 'asc';

// load the client types
//$types = dPgetSysVal( 'ClientStatus' );

// get any records denied from viewing
$obj = new CSocialWork();
$deny = $obj->getDeniedRecords( $AppUI->user_id );

$canEdit = !getDenyEdit( $m );
// retrieve list of records

$perms =& $AppUI->acl();

if (isset( $_GET['tab'] )) 
{
	$AppUI->setState( 'SocialIdxTab', $_GET['tab'] );
}
$socialTypeTab = defVal( $AppUI->getState( 'SocialIdxTab' ),  0 );

// $tabTypes = array(getCompanyTypeID('Client'), getCompanyTypeID('Supplier'), 0);
$socialType = $socialTypeTab;


$let = ':';

$q = new DBQuery;
$q->addTable('clients', 'a');
$q->innerJoin('social_work' , 'b', 'b.social_client_id = a.client_id');
$q->addQuery("DISTINCT UPPER(SUBSTRING(a.client_first_name,1,1)) as L");

if ($clientType > 0)
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
$a2z .= '<td><a href="./index.php?m=socialwork&where=0">' . $AppUI->_('All') . '</a></td>';

for ($c=65; $c < 91; $c++) 
{
	
	$cu = chr( $c );
	$cell = strpos($let, "$cu") > 0 ?
		"<a href=\"?m=socialwork&where=$cu\">$cu</a>" :
		"<font color=\"#999999\">$cu</font>";
	
	if ($cu == $where)
		$a2z .= "\n\t<td class=\"selected\">$cell</td>";
	else
		$a2z .= "\n\t<td>$cell</td>";
}

$a2z .= "\n</tr>\n<tr><td colspan='28'>$form</td></tr></table>";

// setup the title block
$titleBlock = new CTitleBlock( 'Social Work Services Log', 'handshake.png', $m, "$m.$a" );
$titleBlock->addCell ($a2z);

if (($canEdit)  && ($clientType > 1))
{
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_("new $types[$companiesType] log entry").'">', '',
		'<form action="?m=socialwork&a=addedit" method="post">', '</form>'
	);
}

$titleBlock->addCell("<form name='searchform' action='?m=search&amp;search_string=$search_string' method='post'>
						<table>
							<tr>
                      			<td>
                                    <strong>".$AppUI->_('Search')."</strong>
                                    <input class='text' type='text' name='search_string' value='$search_string' /><br />
							</tr>
						</table>
                      </form>");

$search_string = addslashes($search_string);
$titleBlock->show();

$tabBox = new CTabBox( "?m=socialwork", dPgetConfig('root_dir')."/modules/socialwork/", $socialTypeTab );

/*if ($tabbed = $tabBox->isTabbed()) 
{
	$add_na = true;
	if (isset($types[0])) 
	{ // They have a Not Applicable entry.
		$add_na = false;
		$types[] = $types[0];
	}
	$types[0] = "All Log Entries";
	if ($add_na)
		$types[] = "Not Applicable";
}*/

$types[0] = "All Records";

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
