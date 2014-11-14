<?php

global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('discharge');

$sexTypes = dPgetSysVal('GenderType');
$ageExact = dPgetSysVal('AgeType');
$dstatus = dPgetSysVal("ClientStatus");

//load clinics
$q  = new DBQuery;
$q->addTable('clinics','c');
$q->addQuery('clinic_id');
$q->addQuery('clinic_name');
$q->addOrder('clinic_name');
//$clinics = $q->loadHashList();
$clinics = arrayMerge(array(-1=> '-Select Clinic -'),$q->loadHashList());
$today = date('d/m/Y');

// collect all the users for the staff list
$q  = new DBQuery;
$q->addTable('contacts','con');
$q->leftJoin('users','u', 'u.user_contact = con.contact_id');
$q->addQuery('contact_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere('contact_active="1"');
$owners = $q->loadHashList();

$ftypes = array('ltp' => "Transfer", 'dis' => 'Discharge');

$q = new DBQuery;
$q->addTable('discharge_info');
$q->addWhere('dis_client_id = '.$client_id);
$q->addQuery('dis_id,dis_client_status,dis_entry_date,dis_form_type,dis_center');
$q->addOrder('dis_entry_date desc');
//$q->setLimit(1); //now possible to exist multiple entries of discharge form
$s='';
$sql= $q->prepare();
//print_r($sql);
if (!($rows=$q->loadHashListMine())){
	echo /*$AppUI->_("No data available") .*/  $AppUI->getMsg().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?m=discharge&a=add">Add discharge record</a>';
}
else{
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="1"
	class="tbl">
	<tr>
		<th><?php echo $AppUI->_( 'Date' );?></td>
		<th><?php echo $AppUI->_( 'Center' );?></td>
		<th><?php echo $AppUI->_( 'Status' );?></td>
		<th><?php echo $AppUI->_( 'Type' );?></td>
	</tr>
	<?php
	$firstID = false;
	foreach ($rows as $row) {
		$url = "./?m=discharge&a=add&disid=".$row["dis_id"];
		if($firstID === false){
			$firstID = $row['dis_id'];
		}
		$entry_date = printDate($row['dis_entry_date']);
		$tcenter = $clinics[$row['dis_center']];
		$view_url= "./index.php?m=clients&a=view&disid=".$row['dis_id']."&client_id=$client_id";

		$w .= '<tr>';
		$w .= '<td><a href="'.$view_url.'">'. $entry_date.'</a></td>';
		$w .= '<td><a href="'.$view_url.'">'. $tcenter.'</a></td>';
		$w .= '<td><a href="'.$view_url.'">'. $dstatus[$row['dis_client_status']].'</a></td>';		
		$w .= '<td><a href="'.$view_url.'">'. $ftypes[$row['dis_form_type']].'</a></td>';		
		$w .= '</tr>';
	}	
	echo $w.'</table>';
	
	$title="edit discharge record...";
	//load social and counselling info
	if(isset($_GET['disid']) && (int)$_GET['disid'] > 0){
		$useID = (int)$_GET['disid'];
	}else{
		$useID = $firstID;	
	}
	if($useID > 0){
		$obj = new CDischarge();
		$obj->load($useID);

		$q= new DBQuery();
		$q->addTable('admission_caregivers');
		$q->addWhere('id="'.$obj->dis_caregiver.'"');
		$q->addQuery('concat_ws(" ",fname,lname)');
		$q->setLimit(1);
		$carename = $q->loadResult();
	}
	if (!empty($client_id))	{
		?>
		<p>
		<a href="/?m=discharge&a=add&disid=<?php echo $useID?>">Edit discharge record</a>
		</p>
		<table cellpadding="4" cellspacing="0" width="100%" class="std">
		<tr>
		<td valign="top" width="100%">
		<table border="0" cellpadding="4" cellspacing="1">
			<tr>
				<td colspan="2" align="left"><strong><?php echo $AppUI->_('Client Information'); ?><br />
				</strong>
				<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
				<td align="left" class="hilite">
				<?php echo $clinics[$obj->dis_center]; ?>       
			</td>
			</tr>
			<tr>
				<td align="left" nowrap>1b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_entry_date);?>
				</td>
			</tr>			
			<tr>
				<td align="left" nowrap>5b.<?php echo $AppUI->_('Time in programme');?> (mon): </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_time_in;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>6.<?php echo $AppUI->_('Status on discharge');?> : </td>
				<td align="left" class="hilite">
					<?php echo $dstatus[$obj->dis_client_status];?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>7a.<?php echo $AppUI->_('Date Status Changed');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_status_delta_date);?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>7b.<?php echo $AppUI->_('Date MDT recommend discharge');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_status_mdt_date);?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>7c.<?php echo $AppUI->_('Date of next appointment (T.C.A.)');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_status_next_date);?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left">
					<strong><?php echo $AppUI->_('Current Residence'); ?><br /></strong>
					<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>8a.<?php echo $AppUI->_('Physical address');?>: </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_phys_address;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>8b.<?php echo $AppUI->_('Landmarks');?>: </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_landmarks;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>9.<?php echo $AppUI->_('Contact');?>: </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_contact;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>9.<?php echo $AppUI->_('Contact');?>: </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_contact;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>10a.<?php echo $AppUI->_('Caregiver');?>: </td>
				<td align="left" class="hilite">
					<?php echo $carename;?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>10b.<?php echo $AppUI->_('Relationship');?>: </td>
				<td align="left" class="hilite">
					<?php echo $obj->dis_caregiver_relship;?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong>
					<?php echo $AppUI->_('Clinical Summary'); ?><br /></strong>
					<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>11.<?php echo $AppUI->_('Client\'s Health Status');?>: </td>
				<td align="left" class="hilite">
					<?php echo nl2br($obj->dis_client_health);?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>12a.<?php echo $AppUI->_('Name/Signature');?>: </td>
				<td align="left" class="hilite">
					<?php echo $owners[$obj->dis_client_health_staff];?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>12b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_client_health_date);?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong>
					<?php echo $AppUI->_('Counsellor Summary'); ?><br /></strong>
					<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>13.<?php echo $AppUI->_('Client\'s Psychological Status');?>: </td>
				<td align="left" class="hilite">
					<?php echo nl2br($obj->dis_client_psy);?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>14a.<?php echo $AppUI->_('Name/Signature');?>: </td>
				<td align="left" class="hilite">
					<?php echo $owners[$obj->dis_client_psy_staff];?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>14b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_client_psy_date);?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><strong>
					<?php echo $AppUI->_('Social Summary'); ?><br /></strong>
					<hr width="500" align="left" size=1 />
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>15.<?php echo $AppUI->_('Client\'s Social Status');?>: </td>
				<td align="left" class="hilite">
					<?php echo nl2br($obj->dis_client_social);?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>16a.<?php echo $AppUI->_('Name/Signature');?>: </td>
				<td align="left" class="hilite">
					<?php echo $owners[$obj->dis_client_social_staff];?>
				</td>
			</tr>
			<tr>
				<td align="left" nowrap>16b.<?php echo $AppUI->_('Date');?>: </td>
				<td align="left" class="hilite">
					<?php echo printDate($obj->dis_client_social_date);?>
				</td>
			</tr>
		</table>
	<?php
	}
}

?>
