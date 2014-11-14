<?php
require_once ($AppUI->getModuleClass ( 'clients' ));
require_once ($AppUI->getModuleClass ( 'caregivers' ));
require_once ($AppUI->getModuleClass ( 'admin' ));

global $search_string;
global $activity_id;
global $owner_filter_id;
global $currentTabId;
global $currentTabName;
global $tabbed;
global $page;
global $type_filter;
global $city_filter;
global $caregiverorderby;
global $orderdir;
global $limit;
global $options;
global $perms;
//global $AppUI;



if(!isset($activity_id) && (int)$_GET['act_id'] > 0){
	$activity_id=(int)$_GET['act_id'];
}

if (empty ( $caregiverorderby )) {
	$caregiverorderby = 'fname';
}

if (empty ( $orderdir )) {
	$orderdir = 'asc';
}

//pager settings
$page = dPgetParam ( $_GET, 'page', 1 );
$limit = intval ( $dPconfig ['max_limit'] );

$types = dPgetSysVal ( 'ClientStatus' );
$search = false;

$obj = new CCaregiver ();

$allowedContacts = $obj->getAllowedRecords ( $AppUI->user_id, 'caregiver_id, fname' );
if ($AppUI->user_type != 1) //not admin user type
{
	//get allowed clinics if user is not an admin
	$q = new DBQuery ();
	$q->addTable ( "users" );
	$q->addQuery ( "users.user_clinics" );
	$q->addWhere ( "users.user_id = " . $AppUI->user_id );
	$allowedClinics = $q->loadHashList ();

}
$caregiver_type_filter = $currentTabId;
//pager settings
if (strncmp ( $currentTabName, "Not Applicable", strlen ( "Not Applicable" ) ) == 0)
	$caregiver_type_filter = NULL;
if (strncmp ( $currentTabName, "Not Active", strlen ( "Not Active" ) ) == 0)
	$caregiver_type_filter = 99;

//$count = $obj->getCount($AppUI->user_type, $AppUI->user_id,$client_type_filter);


$where = $AppUI->getState ( 'ContactIdxWhere' ) ? $AppUI->getState ( 'ContactIdxWhere' ) : '%';

if ($where != '%')
	$search = true;

$clientType = true;

if (strncmp ( $currentTabName, "All Caregivers", strlen ( "All Caregivers" ) ) == 0)
	$staffType = false;
if (strncmp ( $currentTabName, "Not Applicable", strlen ( "Not Applicable" ) ) == 0)
	$caregiver_type_filter = NULL;
if (strncmp ( $currentTabName, "Not Active", strlen ( "Not Active" ) ) == 0)
	$caregiver_type_filter = 99;
else{
	$caregiver_type_filter = 0;
}
	//if ($currentTabName == "Not Applicable")
//	$company_type_filter = 0;


if (isset ( $activity_id ) && $activity_id > 0) {
	$q = new DBQuery ();

	//$q->setLimit($limit, $offset  );
	$q->addTable ( 'admission_caregivers', 'cc' );
	$q->innerJoin ( 'activity_caregivers', 'ac', 'ac.activity_caregivers_caregiver_id = cc.id' );
	$q->leftJoin ( 'clients', 'c', 'c.client_id = cc.client_id' );
	$q->addQuery ( 'cc.id, cc.fname, cc.lname,c.client_adm_no, c.client_id' );
	if($where != '' && $where != '%'){
		$q->addWhere ( "cc.fname LIKE '$where%'" );
	}

	if (count ( $allowedCaregivers ) > 0) {
		$q->addWhere ( 'cc.caregiver_id IN (' . implode ( ',', array_keys ( $allowedCaregivers ) ) . ')' );
	}

	$q->addWhere ( 'ac.activity_caregivers_activity_id = ' . $activity_id );
	$q->addOrder ( $caregiverorderby . ' ' . $orderdir );
	$num_pages = ceil ( $count / $limit );
	$offset = ($page - 1) * $limit;
	//var_dump($num_pages);
	if ($offset < 0) {
		$limit = intval ( $count );
		$offset = 0;
	}
	//var_dump($count);
	$q->setLimit ( $limit, $offset );
	$sql = $q->prepare ();

	//var_dump($sql);
	$qid = db_exec ( $sql );

	//$count = db_num_rows ( $qid );
	$rows = $q->loadList ();
}

$sql='select activity_caregivers_other as oname from activity_caregivers where activity_caregivers_activity_id="'.$activity_id.'"';
$res=mysql_query($sql);
if($res){
	while($row=my_fetch_assoc($res)){
		$lrow=array();
		$rn=explode('#@#',$row['oname']);
		if($rn[0] != ''){
			$sql='select client_id from clients where client_adm_no="'.$rn[0].'"';
			$r1=mysql_query($sql);
			if($r1){
				$xres=my_fetch_assoc($r1);
				$lrow['client_id']=$xres['client_id'];
				$lrow['client_adm_no']=$rn[0];
				$lrow['fname']=$rn[1];
				$rows[]=$lrow;
			}
		}
	}
}

$count=count($rows);

//echo printPageNavigation( '?m=companies', $page, $num_pages, $offset, $limit, $count);
if ($count > 0) {
	echo printPageNavigation ( '?m=activity', $page, $num_pages, $offset, $limit, $count, 'caregivers' );
}
if ($AppUI->isActiveModule ( 'caregivers' ) && $perms->checkModule ( 'caregivers', 'view' )) {
	echo "<input type='button' class='button' value='" . $AppUI->_ ( "Select caregivers..." ) . "' onclick='javascript:popSelects(\"caregivers\");' />";
}
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1"
	class="tbl">
	<tr>
		<th nowrap="nowrap">
		<?php
		echo $AppUI->_ ( 'Client Adm/No' );
		?>
	</th>
		<th nowrap="nowrap">
		<?php
		echo $AppUI->_ ( 'Caregiver Name' );
		?>
	</th>
	</tr>
<?php
$s = '';
$CR = "\n"; // Why is this needed as a variable?
$none = true;
$df = $AppUI->getPref ( 'SHDATEFORMAT' );


if (! empty ( $rows )) {
	foreach ( $rows as $row ) {

		//$obj = & new CCaregiver ();
		//$obj->load ( $row ["caregiver_id"] );

		//$url
		//$obj->getUrl('view')


		$none = false;
		$s .= $CR . '<tr>';
		$s .= $CR . '<td nowrap="nowrap"><a href="index.php?m=clients&a=view&client_id=' . $row ["client_id"] . '">' . $row ["client_adm_no"] . '</a></td>';
		//$s .= $CR . '<td nowrap="nowrap">' . $obj->fname . ' ' . $obj->lname . '</td>';
		$s .= $CR . '<td nowrap="nowrap">' . $row['fname'] . ' ' . $row['lname'] . '</td>';
	}
}
//$s .= '<input type="button" class=button value="'.$AppUI->_( 'add new participant' ).'" onClick="javascript:window.location=\'./index.php?m=clients&a=pick&activity_id='.$activity_id.'\'">';
//var_dump($obj);
echo "$s\n<br>\n";
if ($none) {
	echo $CR . '<tr><td colspan="8">' . $AppUI->_ ( 'No caregivers selected' ) . '</td></tr>';
}
?>
</table>
<?php
if ($count > 0) {
	echo printPageNavigation ( '?m=activity', $page, $num_pages, $offset, $limit, $count, 'caregivers' );
}
?>
