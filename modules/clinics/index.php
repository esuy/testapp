<?php 
$AppUI->savePlace();

// retrieve any state parameters
if (isset( $_GET['orderby'] )) {
    $orderdir = $AppUI->getState( 'ClinicIdxOrderDir' ) ? ($AppUI->getState( 'CompIdxOrderDir' )== 'asc' ? 'desc' : 'asc' ) : 'desc';
	$AppUI->setState( 'ClinicIdxOrderBy', $_GET['orderby'] );
    $AppUI->setState( 'ClinicIdxOrderDir', $orderdir);
}
$orderby         = $AppUI->getState( 'ClinicIdxOrderBy' ) ? $AppUI->getState( 'ClinicIdxOrderBy' ) : 'clinic_name';
$orderdir        = $AppUI->getState( 'ClinicIdxOrderDir' ) ? $AppUI->getState( 'ClinicIdxOrderDir' ) : 'asc';

if (isset($_GET['where']))
{
  $AppUI->setState('ClinicIdxWhere', $_GET['where']);
}
if(isset($_REQUEST["owner_filter_id"])){
	$AppUI->setState("owner_filter_id", $_REQUEST["owner_filter_id"]);
	$owner_filter_id = $_REQUEST["owner_filter_id"];
} else {
	$owner_filter_id = $AppUI->getState( 'owner_filter_id');
	if (! isset($owner_filter_id)) {
		$owner_filter_id = $AppUI->user_id;
		$AppUI->setState('owner_filter_id', $owner_filter_id);
	}
}
// load the company types
$types = dPgetSysVal( 'ClinicType' );

// get any records denied from viewing
$obj = new CClinic();
$deny = $obj->getDeniedRecords( $AppUI->user_id );

// Company search by Kist
$search_string = dPgetParam( $_REQUEST, 'search_string', "" );
if($search_string != ""){
	$search_string = $search_string == "-1" ? "" : $search_string;
	$AppUI->setState("search_string", $search_string);
} else {
	$search_string = $AppUI->getState("search_string");
}

// $canEdit = !getDenyEdit( $m );
// retrieve list of records
$search_string = dPformSafe($search_string, true);

$perms =& $AppUI->acl();
$owner_list = array( 0 => $AppUI->_("All", UI_OUTPUT_RAW)) + $perms->getPermittedUsers("clinics"); // db_loadHashList($sql);
$owner_combo = arraySelect($owner_list, "owner_filter_id", "class='text' onchange='javascript:document.searchform.submit()'", $owner_filter_id, false);

if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'ClinicsIdxTab', $_GET['tab'] );
}
$clinicsTypeTab = defVal( $AppUI->getState( 'ClinicsIdxTab' ),  0 );

// $tabTypes = array(getCompanyTypeID('Client'), getCompanyTypeID('Supplier'), 0);
$clinicsType = $clinicsTypeTab;

$let = ':';

$q = new DBQuery;
$q->addTable('clinics');
$q->addQuery("DISTINCT UPPER(SUBSTRING(clinic_name,1,1)) as L");

if ($companiesType > 0)
{
	$q->addWhere("clinic_type = $clinicsType");
}

$arr = $q->loadList();
/*
foreach ($arr as $L)
{
  $let .= $L['L'];
}

$a2z = "\n<table cellpadding=\"2\" cellspacing=\"1\" border=\"0\">";
$a2z .= "\n<tr>";
$a2z .= "<td width='100%' align='right'>" . $AppUI->_('Show'). ": </td>";
$a2z .= '<td><a href="./index.php?m=clinics&where=0">' . $AppUI->_('All') . '</a></td>';

for ($c=65; $c < 91; $c++) 
{
	
	$cu = chr( $c );
	$cell = strpos($let, "$cu") > 0 ?
		"<a href=\"?m=clinics&where=$cu\">$cu</a>" :
		"<font color=\"#999999\">$cu</font>";
	
	if ($cu == $where)
		$a2z .= "\n\t<td class=\"selected\">$cell</td>";
	else
		$a2z .= "\n\t<td>$cell</td>";
}

$a2z .= "\n</tr>\n<tr><td colspan='28'>$form</td></tr></table>";

// setup the title block
$titleBlock = new CTitleBlock( 'Centers', '', $m, "$m.$a" );
$titleBlock->addCell ($a2z);


$titleBlock->addCell ("<form name='searchform' action='?m=search' method='post'>
                        <table>
                         <tr>
                           <td>						   
                              <input class = 'text' type='text' name ='search_string' value='$search_string' />
						   </td>
						   <td>
							  <input type='submit' value='" .$AppUI->_( 'search' )."' class='button' />
						   </td>
						  </tr>
                         </table>
                        </form>"
);

if ($canEdit )
{
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_("new center").'">', '',
		'<form action="?m=clinics&a=add" method="post">', '</form>'
	);
}



$titleBlock->show();
$search_string = addslashes($search_string);

*/
$tabBox = new CTabBox( "?m=clinics", dPgetConfig('root_dir')."/modules/clinics/", $clinicsTypeTab );

if ($tabbed = $tabBox->isTabbed()) {
	$add_na = true;
	if (isset($types[0])) { // They have a Not Applicable entry.
		$add_na = false;
		$types[] = $types[0];
	}
	$types[0] = "All Centers";
	if ($add_na)
		$types[] = "Not Applicable";
}

//pager settings
$page = dPgetParam($_GET, 'page', 1);
$limit = intval($dPconfig['max_limit']);

$type_filter = array();
foreach($types as $type => $type_name){
	$type_filter[] = $type;
	$tabBox->add("vw_clinics_new", $type_name . " (" . $obj->getCount($type) . ")", false, $type );
}

$tabBox->show();