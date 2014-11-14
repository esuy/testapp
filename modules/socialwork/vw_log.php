<?php

global $search_string;
global $owner_filter_id;
global $currentTabId;
global $currentTabName;
global $tabbed;
global $page;
global $type_filter;
global $city_filter;
global $orderby;
global $orderdir;
global $limit;
global $options;

//$types = dPgetSysVal('ClientStatus');
$search = false;

$obj = new CSocialWork();

$allowedRecords = $obj->getAllowedRecords($AppUI->user_id );

$social_type_filter = $currentTabId;
//pager settings
$count = $obj->getCount($social_type_filter);

$num_pages = ceil ($count / $limit);
$offset = ($page - 1) * $limit;
//var_dump($num_pages);
if ($offset < 0)
{
	$limit = intval($count);
	$offset = 0;
}
$df = $AppUI->getPref('SHDATEFORMAT');
$where = $AppUI->getState( 'SocialIdxWhere' ) ? $AppUI->getState( 'SocialIdxWhere' ) : '%';

if ($where != '%') $search=true;


$clientType = true;

if (strncmp($currentTabName,"All Entries", strlen("All Entries")) == 0)
	$clientType = false;
	
//if ($currentTabName == "Not Applicable")
//	$company_type_filter = 0;

	
    
	
	//var_dump($limit);
	//var_dump($offset);
	$q = new DBQuery;
	
	$q->setLimit($limit, $offset  );
	$q->addTable('social_work', 'a');
	$q->addJoin('clients', 'c', 'c.client_id = a.social_client_id');
	$q->addQuery('a.*,c.client_id, c.client_first_name, c.client_status, c.client_notes, c.client_phone1');
	$q->addWhere("c.client_first_name LIKE '$where%'");

if (count($allowedRecords) > 0) { $q->addWhere('a.social_id IN (' . implode(',', array_keys($allowedRecords)) . ')'); }

if (($socialType) && ($social_type_filter > 0)) 
{ 
		$q->addWhere('c.client_status = '.$social_type_filter); 
}
if  (!empty($city_filter))
{
		$q->addWhere("c.client_city = " . $city_filter);
}

$q->addOrder($orderby.' '.$orderdir);

$sql = $q->prepare();

//var_dump($sql);
$qid = db_exec($sql);
$count = db_num_rows($qid);

//var_dump($count);
$num_pages = ceil ($count / $limit);

$offset = ($page - 1) * $limit;

if ($offset < 0)
{
	$limit = intval($count);
	$offset = 0;
}


$q->setLimit($limit, $offset  );

$rows = $q->loadList();

//load cities
$citiesArray = arrayMerge(array(0=>'-Select City-'), dPgetSysVal('ClientCities'));
//echo printPageNavigation( '?m=companies', $page, $num_pages, $offset, $limit, $count);
echo printPageNavigation( '?m=clients', $page, $num_pages, $offset, $limit, $count, 'records');

?>
<script language="javascript">
function viewGraph(x)
{
    var url = x;
	newwin = window.open( url, 'newwin', 'height=800,width=600,resizable,scrollbars=yes' );
	if (typeof newwin == 'undefined')
	{
	   alert('Problem with graph. Please check on the MRTG server');
	}
}

function clearFilters(f)
{
    f.city_filter.selectedIndex = 0;
	f.filter='';
	f.submit();
}
</script>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th>
		<a href="?m=socialwork&orderby=client_adm_no" class="hdr"><?php echo $AppUI->_('Social Worker  Name');?></a>
	</th>	
	<th>
		<a href="?m=socialwork&orderby=client_adm_no" class="hdr"><?php echo $AppUI->_('Social Worker  Code');?></a>
	</th>	
	<th>
		<a href="?m=socialwork&orderby=client_name" class="hdr"><?php echo $AppUI->_('Visit Date');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_phone1" class="hdr"><?php echo $AppUI->_('Childs Name');?></a>
	</th>
	<?php //if (!isset($client_type_filter) || $client_type_filter == 0) {?>
	<th >
		<a href="?m=socialwork&orderby=client_type" class="hdr"><?php echo $AppUI->_('Childs Adm No.');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_status" class="hdr"><?php echo $AppUI->_('Needs Assessment');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Supported Needs');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Food Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Permanency plan');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Nurse & Pal. care');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Hospital visit');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Home visit');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('I.G.A/Microfin');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Medical Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Transport Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Education Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Clothing & bedding');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Solidarity Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Rent Support');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Other Material Spt');?></a>
	</th>	
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('# Supported/Assessed');?></a>
	</th>
	<th >
		<a href="?m=socialwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Gender (M/F)');?></a>
	</th>
    <?php //} ?>
</tr>
<?php
$s = '';
$CR = "\n"; // Why is this needed as a variable?
$none = true;


foreach ($rows as $row)
{

    $obj->reset();	
	$obj->load($row["social_id"]);
	
	//$url
	//format date
	$entry_date = new CDate($obj->social_entry_date);
	
	$none = false;
	$s .= $CR . '<tr>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . $socialworkerObj->getFullName() . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . $entry_date->format($df) .'</a></td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . $clientObj->getFullName() . '</td>';

	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_needs_assessment) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_supported_needs) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_food_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_permanency_plan) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_nurse_care) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_hospital_visit) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_home_visit) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_microfin) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_medical_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_transport_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_education_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_clothing) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_solidarity_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . getBoolDesc($obj->social_rent_support) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' .$obj->social_no_support . '</td>';
	$s .= $CR . '<td><a href="index.php?m=socialwork&a=view&social_id='.$obj->social_id.'&client_id=' . $clientObj->client_id . '">' . $obj->social_gender . '</td>';
	$s .= $CR . '</tr>';
}
//var_dump($obj);
echo "$s\n";
if ($none)
{
	echo $CR . '<tr><td colspan="22">' . $AppUI->_( 'No clients available' ) . '</td></tr>';
}
?>
</table>
<?php
   echo printPageNavigation( '?m=clients', $page, $num_pages, $offset, $limit, $count, 'records');
?>
