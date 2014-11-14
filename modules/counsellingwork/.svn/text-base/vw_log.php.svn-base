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

$types = dPgetSysVal('CounsellingLogStatus');
$search = false;

$clientObj = new CClient();
$obj = new CCounsellingwork();

$allowedRecords = $obj->getAllowedRecords($AppUI->user_id, 'counselling_id' );

$record_type_filter = $currentTabId;
//pager settings
$count = $obj->getCount($record_type_filter);

$num_pages = ceil ($count / $limit);
$offset = ($page - 1) * $limit;
//var_dump($num_pages);
if ($offset < 0)
{
	$limit = intval($count);
	$offset = 0;
}

$where = $AppUI->getState( 'CounsellingIdxWhere' ) ? $AppUI->getState( 'CounsellingIdxWhere' ) : '%';

if ($where != '%') $search=true;


$clientType = true;

if (strncmp($currentTabName,"All Records", strlen("All Records")) == 0)
	$recordType = false;
	
//if ($currentTabName == "Not Applicable")
//	$company_type_filter = 0;

	
    
	
	//var_dump($limit);
	//var_dump($offset);
	$q = new DBQuery;
	
	$q->setLimit($limit, $offset  );
	$q->addTable('counselling_work', 'b');
	$q->leftJoin('clients', 'c', 'b.counselling_client_id = c.client_id');
	$q->addQuery('b.*, c.client_id, c.client_first_name, c.client_status, c.client_notes, c.client_phone1');
	$q->addWhere("c.client_first_name LIKE '$where%'");

if (count($allowedRecords) > 0) { $q->addWhere('b.counsel_id IN (' . implode(',', array_keys($allowedRecords)) . ')'); }

if (($clientType) && ($client_type_filter > 0)) 
{ 
		$q->addWhere('c.client_status = '.$client_type_filter); 
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
 <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
        <form name="filter" action="index.php" method="get">
        <input type="hidden" name="cat" value="filter">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <tr>
          <td nowrap>
		  <strong class="default">Quick Filter Form:</strong><br />
          </td>
		</tr>
		<tr>
          <td valign="top">
            <span class="default">Clients In:</span>&nbsp;
			<?php echo arraySelect($citiesArray, "city_filter", 'class="text"', $city_filter );?>
           
          </td>
		 </tr>
		 <tr>
          <td>
            <input class="button" type="submit" value="Filter">
            <input class="button" type="button" value="Clear Filters" onClick="javascript:clearFilters(document.filter);">
          </td>
		 </tr>
        </form>
      </table>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr >
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=counselling_date" class="hdr"><?php echo $AppUI->_('Visit Date');?></a>
	</th>	
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=counselling_provider" class="hdr"><?php echo $AppUI->_('Couns. Name');?></a>
	</th>		
=	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=counselling_provider" class="hdr"><?php echo $AppUI->_('Provider');?></a>
	</th>	
	<th nowrap="nowrap" width="55%">
		<a href="?m=counsellingwork&orderby=client_name" class="hdr"><?php echo $AppUI->_('Name/Caregiver Name');?></a>
	</th>

	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_code" class="hdr"><?php echo $AppUI->_('Adm. No');?></a>
	</th>	
	<th nowrap="nowrap" valign="top">
		<a href="?m=counsellingwork&orderby=client_phone1" class="hdr"><?php echo $AppUI->_('Support Couns.');?></a>
	</th>
	<?php //if (!isset($client_type_filter) || $client_type_filter == 0) {?>
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_type" class="hdr"><?php echo $AppUI->_('Child Couns');?></a>
	</th>
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_status" class="hdr"><?php echo $AppUI->_('Ind. Prev. Educ.');?></a>
	</th>
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Lifeskiss training');?></a>
	</th>
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Rec. Therapy');?></a>
	</th>	
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Hospital Visit');?></a>
	</th>	
	<th nowrap="nowrap">
		<a href="?m=counsellingwork&orderby=client_priority" class="hdr"><?php echo $AppUI->_('Home Visit');?></a>
	</th>
    <?php //} ?>
</tr>
<?php
$s = '';
$CR = "\n"; // Why is this needed as a variable?
$none = true;
$df = $AppUI->getPref('SHDATEFORMAT');

foreach ($rows as $row)
{

    //$obj->reset();	
	$obj = new CCounsellingWork();
	$obj->load($row["counselling_id"]);

	$clientObj = new CClient();
	$clientObj->load($row["counselling_client_id"]);
	var_dump($clientObj->getFullName());
	//$url
		//format date
	$entry_date = new CDate($obj->counselling_date);
	
	$none = false;
	
	$s .= $CR . '<tr>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . $entry_date->format($df) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . $clientObj->getFullName() .'</a></td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . $clientObj->getFullName() .'</a></td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . $clientObj->getFullName() .'</a></td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . $AppUI->_($clientObj->client_adm_no) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_support_counselling) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_child_counselling) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_ind_prev_educ) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_adherence_counselling) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_ind_disc_counselling) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_lifeskiss_training) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_rec_therapy) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_hospital_visit) . '</td>';
	$s .= $CR . '<td><a href="index.php?m=counsellingwork&a=view&counselling_id=' . $obj->counselling_id . '&client_id=' . $clientObj->client_id . '>' . getBoolDesc($obj->counselling_home_visit) . '</td>';


	$s .= $CR . '</tr>';
}

echo "$s\n";
if ($none)
{
	echo $CR . '<tr><td colspan="13">' . $AppUI->_( 'No clients available' ) . '</td></tr>';
}
?>
</table>
<?php
   echo printPageNavigation( '?m=counsellingwork', $page, $num_pages, $offset, $limit, $count, 'records');
?>
