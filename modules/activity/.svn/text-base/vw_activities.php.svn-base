<?php
require_once ($AppUI->getModuleClass('clients'));

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


$types = dPgetSysVal('CurriculumTypes');
$search = false;

$obj = new CActivity();


$allowedActivities = $obj->getAllowedRecords($AppUI->user_id, 'activity_id, activity_description' );
if ($AppUI->user_type <> 1) //not admin user type
{
	//get allowed clinics if user is not an admin
	$q = new DBQuery();
	$q->addTable("users");
	$q->addQuery("users.user_clinics");
	$q->addWhere("users.user_id = " . $AppUI->user_id);
	$allowedClinics= $q->loadHashList();

}
//load clinics
$q = new DBQuery();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');
$clinics = $q->loadHashList();

$activity_type_filter = $currentTabId;
//pager settings
if (strncmp($currentTabName, "Not Applicable", strlen("Not Applicable")) == 0)
	$activity_type_filter = NULL;
	
$count = $obj->getCount($activity_type_filter);

$num_pages = ceil ($count / $limit);
$offset = ($page - 1) * $limit;
//var_dump($num_pages);
if ($offset < 0)
{
	$limit = intval($count);
	$offset = 0;
}

$where = $AppUI->getState( 'ActivityIdxWhere' ) ? $AppUI->getState( 'ActivityIdxWhere' ) : '%';

if ($where != '%') $search=true;


$activityType = true;

if (strncmp($currentTabName,"All Activities", strlen("All Activities")) == 0)
	$activityType = false;
if (strncmp($currentTabName,"Not Applicable", strlen("Not Applicable")) == 0)
	$activity_type_filter = NULL;	

	
    
	
	//var_dump($limit);
	//var_dump($offset);
	$q = new DBQuery;
	
	//$q->setLimit($limit, $offset  );
	$q->addTable('activity', 't');
	//$q->innerJoin('activity_clients', 'ac', 'ac.activity_clients_activity_id = a.activity_id');
	$q->addQuery('t.activity_id, t.activity_description, t.activity_entry_date, t.activity_date, t.activity_clinic, t.activity_male_count + t.activity_female_count as activity_count');
	//$q->addWhere("a.activity_name LIKE '$where%'");

if (count($allowedActivities) > 0) { $q->addWhere('t.activity_id IN (' . implode(',', array_keys($allowedActivities)) . ')'); }
if ((count($allowedClinics) > 0) && ($allowedClinics[0] <> NULL)) { $q->addWhere('t.activity_clinic IN (' . implode(',', array_keys($allowedClinics)) . ')'); }

if ($activityType) 
{
  if ($activity_type_filter > 0)	
  { 
		//$q->addWhere('ci.counselling_clinic = '.$activity_type_filter ); 
		$q->addWhere('t.activity_curriculum = '.$activity_type_filter); 
  }
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
//var_dump($rows);

//echo printPageNavigation( '?m=companies', $page, $num_pages, $offset, $limit, $count);
echo printPageNavigation( '?m=activity', $page, $num_pages, $offset, $limit, $count, 'activities');

?>


<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th nowrap="nowrap">
		<a href="?m=activity&orderby=activity_description" class="hdr"><?php echo $AppUI->_('Description');?></a>
	</th>	
	<th nowrap="nowrap">
		<a href="?m=activity&orderby=activity_date" class="hdr"><?php echo $AppUI->_('Activity Date');?></a>
	</th>
	<th nowrap="nowrap">
		<a href="?m=activity&orderby=activity_clinic" class="hdr"><?php echo $AppUI->_('Center');?></a>
	</th>
	<th nowrap="nowrap">
		<a href="?m=activity&orderby=activity_count" class="hdr"><?php echo $AppUI->_('Total Attended');?></a>
	</th>	
</tr>
<?php
$s = '';
$CR = "\n"; // Why is this needed as a variable?
$none = true;
$df = $AppUI->getPref('SHDATEFORMAT');
	$q  = new DBQuery;
	$q->addTable('clinic_location');
	$q->addQuery('clinic_location.clinic_location_id, clinic_location.clinic_location');
	$locations = $q->loadHashList();
	
foreach ($rows as $row)
{

    //$obj->reset;	
	$obj = new CActivity();
	$obj->load($row["activity_id"]);
	
	$activity_date = intval($obj->activity_date) ? new CDate($obj->activity_date ) :  null;
	$act_date = NULL;
	if (isset($activity_date))
	{
			$act_date = $activity_date->format($df);
	}
	
	
	$none = false;
	$s .= $CR . '<tr>';
	$s .= $CR . '<td nowrap="nowrap"><a href="'. $obj->getUrl('view')   .'&activity_id=' . $obj->activity_id . '" title="'.$obj->activity_notes.'">' . $obj->activity_description .'</a></td>';
	$s .= $CR . '<td align="center" nowrap="nowrap"><a href="'. $obj->getUrl('view')   .'&activity_id=' . $obj->activity_id . '" title="'.$obj->activity_notes.'">' . $act_date . '</a></td>';
	$s .= $CR . '<td align="center" nowrap="nowrap"><a href="'. $obj->getUrl('view')   .'&activity_id=' . $obj->activity_id . '" title="'.$obj->activity_notes.'">' . $clinics[$obj->activity_clinic] . '</a></td>';
	$s .= $CR . '<td align="center" nowrap="nowrap"><a href="'. $obj->getUrl('view')   .'&activity_id=' . $obj->activity_id . '" title="'.$obj->activity_notes.'">' . $row['activity_count'] .  '</a></td>';
	$s .= $CR . '</tr>';
}
//var_dump($obj);
echo "$s\n";
if ($none)
{
	echo $CR . '<tr><td colspan="8">' . $AppUI->_( 'No activities available' ) . '</td></tr>';
}
?>
</table>
<?php
   echo printPageNavigation( '?m=activity', $page, $num_pages, $offset, $limit, $count, 'activities');
?>
