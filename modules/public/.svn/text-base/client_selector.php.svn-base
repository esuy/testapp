<?php


	$show_all             = dPgetParam($_REQUEST, 'show_all', 0);
	$activity_id           = dPgetParam($_REQUEST, 'activity_id', 0);
	$client_id           = dPgetParam($_POST, 'client_id', 0);
	$call_back            = dPgetParam($_GET, 'call_back', null);
	$clients_submited    = dPgetParam($_POST, 'clients_submited', 0);
	$selected_clients_id = dPgetParam($_GET, 'selected_clients_id', '');
	if (dPgetParam($_POST, 'selected_clients_id'))	{
		$selected_clients_id = dPgetParam($_POST, 'selected_clients_id');
	}
	
	if($_GET['fpart'] != ""){
		$part=$_GET['fpart'];
		$partStr='&fpart='.$part;
		$partFunc='"'.$part.'",';
	}else{
		$part='';
		$partStr='';
		$partFunc='';
	}
?>
<script language="javascript">
function setClientIDs (method,querystring)
{
	var URL = 'index.php?m=public&a=client_selector';
    
	var field = document.getElementsByName('client_id[]');
	var selected_clients_id = document.frmClientSelect.selected_clients_id;
	var tmp = new Array();
	
	if (method == 'GET' && querystring){
		URL += '&' + querystring;
	}
	
	var count = 0;
	for (i = 0; i < field.length; i++) {
		if (field[i].checked) {
			tmp[count++] = field[i].value;
		}
	}
	selected_clients_id.value = tmp.join(',');
    
	if (method == 'GET') {
		URL +=  '&selected_clients_id=' + selected_clients_id.value;
		return URL;
	} else {
		return selected_clients_id;
	}

}
</script>
<?php

function remove_invalid($arr) {
	$result = array();
	foreach ($arr as $val) {
		if (! empty($val) && trim($val) !== '' && is_numeric($val)) {
			$result[] = $val;
		}
	}	
	return $result;
}
//var_dump($selected_clients_id);
//var_dump($clients_submited);
//var_dump($call_back);
if($clients_submited == 1){	
	$call_back_string = !is_null($call_back) ? "window.opener.$call_back(".$partFunc."'$selected_clients_id');" : '';
?>
<script language="javascript">
	<?php echo $call_back_string ?>
	self.close();
</script>
<?php
return ;
}

// Remove any empty elements
$clients_id = remove_invalid(explode(',', $selected_clients_id));
$selected_clients_id = implode(',', $clients_id);

require_once( $AppUI->getModuleClass( 'clients' ) );
$oClnt = new CClient ();
$aClnts = $oClnt->getAllowedRecords ($AppUI->user_id, 'client_id', 'client_id');
$aClnts_esc = array();
foreach ($aClnts as $key => $client) {
	$aClnts_esc[$key] = db_escape($client);
}

$q = new DBQuery;

if (strlen($selected_clients_id) > 0 && ! $show_all && ! $client_id){
	$q->addTable('clients');
	$q->addQuery('client_id');
	$q->addWhere('client_id IN (' . $selected_clients_id . ')');
	$where = implode(',', $q->loadColumn());
	$q->clear();
	if (substr($where, 0, 1) == ',' && $where != ',') { 
		$where = '0'.$where; 
	} else if ($where == ',') {
		$where = '0';
	}
	$where = (($where)?('client_id IN('.$where.')'):'');
} 
/*else if ( ! $company_id ) {
	//  Contacts from all allowed companies
	$where = ("contact_company = ''"
			  ." OR (contact_company IN ('".implode('\',\'' , array_values($aCpies_esc)) ."'))"
			  ." OR ( contact_company IN ('".implode('\',\'', array_keys($aCpies_esc)) ."'))") ;
	$company_name = $AppUI->_('Allowed Companies');
} */
/*else 
{
	// Contacts for this company only
	$q->addTable('companies', 'c');
	$q->addQuery('c.company_name');
	$q->addWhere('company_id = '.$company_id);
	$company_name = $q->loadResult();
	$q->clear();
	/*
		$sql = "select c.company_name from companies as c where company_id = $company_id";
		$company_name = db_loadResult($sql);
	*/
/*	
	$company_name_sql = db_escape($company_name);
	$where = " ( contact_company = '$company_name_sql' or contact_company = '$company_id' )";
}*/

// This should now work on company ID, but we need to be able to handle both
$q->addTable('clients', 'a');
//$q->leftJoin('companies', 'b', 'company_id = contact_company');
//$q->leftJoin('departments', 'c', 'dept_id = contact_department');
$q->addQuery('client_id, client_first_name, client_other_name, client_last_name,client_adm_no');
//$q->addQuery('company_name');
//$q->addQuery('dept_name');
if ($where) { // Don't assume where is set. Change needed to fix Mantis Bug 0002056
	$q->addWhere($where);
}
//$q->addWhere("(contact_owner = '".$AppUI->user_id."' OR contact_private = '0')");
$q->addOrder('client_first_name'); // May need to review this.

$clients = $q->loadHashList('client_id');


$moduleScripts[]='./modules/public/tsjq.js';
?>

<form action="index.php?m=public&a=client_selector&dialog=1&<?php if(!is_null($call_back)) echo 'call_back='.$call_back.'&'; ?>activity_id=<?php echo $activity_id.$partStr ?>" method='post' name='frmClientSelect'>
<input type="submit" value="<?php echo $AppUI->_('Continue'); ?>" onClick="setClientIDs()" class="button" />
<?php
$actual_department = '';
$actual_company    = '';
//$companies_names = array(0 => $AppUI->_('Select a company')) + $aCpies;
/*echo arraySelect($companies_names, 'company_id', 
				 'onchange="document.frmContactSelect.contacts_submited.value=0; '
				 .'setContactIDs(); document.frmContactSelect.submit();"', 
				 0);*/
?>

<br>
 <h4><a href="#" onClick="window.location.href=setClientIDs('GET','dialog=1&<?php if(!is_null($call_back)) echo 'call_back='.$call_back.'&'; ?>show_all=1<?php echo  $partStr;?>');"><?php echo $AppUI->_('Click to view all clients'); ?></a></h4>
<hr />
<h2><?php echo $AppUI->_('Clients for'); ?> <?php echo $activity_name ?></h2>
<table id="qtable" class="tablesorter tbl" cellpadding=2 cellspacing=1 border=0>
<thead>
	<tr>
		<th>&nbsp;</th><th>Adm No</th><th>Name</th>
	</tr>
</thead>
<tbody>
<?php	
	foreach($clients as $client_id => $client_data)
	{
		$checked = in_array($client_id, $clients_id) ? 'checked="checked"' : '';
		echo "<tr>\n\t".'<td><input type="checkbox" name="client_id[]" id="client_'.$client_id.'" value="'.$client_id.'" '.$checked.' /></td>
			<td>'.$client_data['client_adm_no'].'</td>';
		echo '<td >
				<label for="client_'.$client_id.'" data-skort="'.$client_data['client_last_name'].'">'.$client_data['client_first_name'].' '.$client_data['client_last_name'].'</label>
			</td>';
		//var_dump($caregivers[$client_id]);
		echo "\n</tr>\n";
	}
?>
</tbody>
</table>
<hr />
<input name="clients_submited" type="hidden" value="1" />
<input name="selected_clients_id" type="hidden" value="<?php echo $selected_clients_id; ?>">
<input type="submit" value="<?php echo $AppUI->_('Continue'); ?>" onClick="setClientIDs()" class="button" />
</form>
<script type="text/javascript">
window.onload=boost;function boost(){$j("#qtable").tablesorter({headers:{0:{sorter:false},2:{sorter: "soname"}},widgets:['fixHead']});}
</script>
