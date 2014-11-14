<?php

require_once $AppUI->getModuleClass('clients');
require_once $AppUI->getModuleClass('followup');

$obj = new CDischarge();
$kid = new CClient();
if(isset($_GET['disid']) && (int)$_GET['disid'] > 0){
	$dis_id = (int)$_GET['disid'];
	$obj->load($dis_id);	
	$kid->load($obj->dis_client_id);
	$dups= makeListPerson($kid->client_adm_no);
	$ages = calcIt($kid->getDOB());                                                                                                                                                                                                        
	preg_match_all("/\s?(\d*)\s/",$ages['v'],$formAges); 
	$kinfo = $dups['child'];
	$sopts=array('<select id="csel" name="dis_caregiver"><option disabled value="-1">Select Caregiver</option>');
	foreach ($dups['caregiver'] as $ci => $cname){
		if($dups['careids'][$ci] === $obj->dis_caregiver ){
			$usethis='selected="selected"';
			$relation=$dups['relship'][$ci];
		}else{
			$usethis='';
		}
		$sopts[]='<option value="'.$dups['careids'][$ci].'" '.($usethis) .' data-rship="'.$dups['relship'][$ci].'">'.$dups['caregiver'][$ci].'</option>';
	}
	$sopts[]='</select>';
	$sopts=implode("",$sopts);
}

$sexTypes = dPgetSysVal('GenderType');
$ageExact = dPgetSysVal('AgeType');
//$dclientStatus = array_merge(array('-1'=>'Select status'),dPgetSysVal("ClientDischargeStatus"));
$dclientStatus = dPgetSysVal("ClientStatus");

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


$moduleScripts[]='/modules/public/form_edit.js';
?>
<link rel="stylesheet" type="text/css" href="/modules/followup/followup.module.css" media="all" />


<form name="changeDischarge" action="?m=discharge" method="post">
	<input type="hidden" name="dosql" value="do_dis_aed" />
	<input type="hidden" name="dis_id" value="<?php echo $dis_id;?>" />
	<input type="hidden" name="dis_client_id" value="<?php echo $client_id;?>" />
	
<table cellspacing="1" cellpadding="1" border="0" width='100%' class="std">
<tr>
<td valign="top" width="100%">
<table id="ftab">
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Client Information'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
         <td align="left">1a.<?php echo $AppUI->_('Center');?>:</td>
         <td align="left">
		 	<?php echo arraySelect($clinics, "dis_center", 'class="text mandat-field" id="clinic_id"', $obj->dis_center ? $obj->dis_center : -1 ); ?>
         </td>
	</tr>
	<tr>
		<td align="left">1b.<?php echo $AppUI->_('Date');?>: </td>
		<td align="left">
			<?php echo  drawDateCalendar('dis_entry_date',printDate($obj->dis_entry_date),false,'class="mandat-field"',false,10);?>
		</td>
	</tr>
	<tr>
		<td align="left">2a.<?php echo $AppUI->_('Adm #');?>: </td>
		<td align="left">
			<div class="jbox">
				<input type="text" name="dis_client_adm_no" class="adm_field text" size="8" value="<?php 
				if(!is_null($obj)){
					echo $obj->dis_client_adm_no;
				}
			?>">
				<div class="bsubmit" style="float:left;" onclick="editor.postName(this,2);" title="Retrieve name"></div>
			</div>
		<input type="hidden" name="dis_client_id" id="clid" value="<?php	echo $obj->dis_client_id;?>">
		</td>
	</tr>
	<tr>
			<td align="left">
				2b.<?php echo $AppUI->_('First Name'); ?>
			</td>
			<td align="left">
				<input type="text" class="text" id="fname" value="<?php echo $kid->client_first_name;?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				2c.<?php echo $AppUI->_('Last Name'); ?>
			</td>
			<td align="left">
				<input type="text" class="text" id="lname" value="<?php echo $kid->client_last_name;?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				2d.<?php echo $AppUI->_('Other Name'); ?>
			</td>
			<td align="left">
				<input type="text" class="text" id="oname" value="<?php echo $kid->client_other_name;?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				3.<?php echo $AppUI->_('Sex'); ?>
			</td>
			<td align="left" id="sex_cell">
				<?php echo  ($kid->client_gender ? $sexTypes[$kid->client_gender] : '&nbsp;');?>
			</td>
	</tr>
	<tr>
			<td align="left">
				4a.<?php echo $AppUI->_('Date of Birth'); ?>
			</td>
			<td align="left" id="dob_cell">
				<?php echo  ($kid->client_dob ? printDate($kid->client_dob) : '&nbsp;');?>
			</td>
	</tr>
	<tr>
			<td align="left">
				4b.<?php echo $AppUI->_('Age - Years'); ?>
			</td>
			<td align="left" id="yrz_cell">
				<input type="text" class="text" name="dis_age_years" value="<?php echo  ($obj->dis_age_years ? $obj->dis_age_years : '&nbsp;');?>" readonly="readonly">
			</td>
	</tr>
	<tr>
			<td align="left">
				4c.<?php echo $AppUI->_('Age - Months'); ?>
			</td>
			<td align="left" id="mnth_cell">
				<input type="text" class="text" name="dis_age_months" readonly="readonly" value="<?php echo  ($obj->dis_age_months ? $obj->dis_age_months : '&nbsp;');?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				4c.<?php echo $AppUI->_('Age exactness'); ?>
			</td>
			<td align="left" id="exact_cell">
				<?php echo arraySelectRadio($ageExact,'dis_age_exact','',($obj->dis_age_exact ? $obj->dis_age_exact : -1),$identifiers); ?>			
			</td>
	</tr>
	<tr>
			<td align="left">
				5a.<?php echo $AppUI->_('Date of admission'); ?>
			</td>
			<td align="left" id="doa_cell">
				<?php echo  printDate($kid->client_doa);?>&nbsp;
			</td>
	</tr>
	<tr>
			<td align="left">
				5b.<?php echo $AppUI->_('Time in programme '); ?>(mon)
			</td>
			<td align="left" >
				<input type="text" id="timein" class="text" name="dis_time_in" value="<?php echo $obj->dis_time_in;?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				6.<?php echo $AppUI->_('Status'); ?>
			</td>
			<td align="left" >
				<input type="hidden" name="dis_client_status" id="dt_client_status" value="<?php echo $obj->dis_client_status?>">
				<input type="text" class="text" readonly="readonly" id="dt_client_status_vis" value="<?php echo $dclientStatus[$obj->dis_client_status]?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				7a.<?php echo $AppUI->_('Date Status Changed'); ?>
			</td>
			<td align="left" >
				<input type="text" readonly="readonly" value="<?=printDate($obj->dis_status_delta_date)?>" name="dis_status_delta_date" size="10" class="text" id="dsc_date"> 
				<?php	//drawDateCalendar('dis_status_delta_date',printDate($obj->dis_status_delta_date),false,'',false,10); ?>				
			</td>
	</tr>
	<tr>
			<td align="left">
				7b.<?php echo $AppUI->_('Date MDT recommend discharge'); ?>
			</td>			
			<td align="left" >				
				<?php echo  drawDateCalendar('dis_status_mdt_date',printDate($obj->dis_status_mdt_date),false,'',false,10);?>				
			</td>
	</tr>
	<tr>
			<td align="left">
				7c.<?php echo $AppUI->_('Date of next appointment (T.C.A.)'); ?>
			</td>
			<td align="left" >
				<?php echo  drawDateCalendar('dis_status_next_date',printDate($obj->dis_status_next_date),false,' id="tca_fld" ',false,10);?>				
			</td>
	</tr>	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Current Residence'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
			<td align="left">
				8a.<?php echo $AppUI->_('Physical Address'); ?><br /></strong>				
			</td>
			<td align="left">
				<input type="text" class="text" name="dis_phys_address" value="<?php echo $obj->dis_phys_address?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				8b.<?php echo $AppUI->_('Landmarks'); ?><br /></strong>				
			</td>
			<td align="left">
				<input type="text" class="text" name="dis_landmarks" value="<?php echo $obj->dis_landmarks?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				9.<?php echo $AppUI->_('Contact'); ?><br /></strong>				
			</td>
			<td align="left">
				<input type="text" class="text" name="dis_contact" value="<?php echo $obj->dis_contact?>">
			</td>
	</tr>
	<tr>
			<td align="left">
				10a.<?php echo $AppUI->_('Caregiver'); ?><br /></strong>				
			</td>
			<td align="left" id="care_names">
				<?php echo $sopts,'&nbsp;';?>
			</td>
	</tr>
	<tr>
			<td align="left">
				10a.<?php echo $AppUI->_('Relationship'); ?><br /></strong>				
			</td>
			<td align="left" >
				<input type="text" id="care_rels" class="text" readonly="readonly" value="<?php echo $obj->dis_caregiver_relship;?>" name="dis_caregiver_relship">
 			</td>
	</tr>
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Clinical Summary'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
			<td align="left">
				11.<?php echo $AppUI->_('Client\'s Health Summary'); ?>				
			</td>
			<td>
				<textarea cols="50" rows="4" name="dis_client_health" class="text"><?php echo (!is_null($obj->dis_client_health) ? dPformSafe($obj->dis_client_health ) : '')?></textarea>
			</td>
	</tr>
	<tr>
			<td align="left">
				12a.<?php echo $AppUI->_('Name/Signature'); ?>				
			</td>
			<td>
				<?php echo arraySelect( $owners, 'dis_client_health_staff', 'id="health_staff_id" size="1" class="text"', @$obj->dis_client_health_staff ? $obj->dis_client_health_staff :-1); ?>
			</td>
	</tr>
	<tr>
		<td align="left">12b.<?php echo $AppUI->_('Date');?>: </td>
		<td align="left">
			<?php echo  drawDateCalendar('dis_client_health_date',printDate($obj->dis_client_health_date),false,'',false,10);?>
		</td>
	</tr>
	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Counselor Summary'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
			<td align="left">
				13.<?php echo $AppUI->_('Client\'s Psychological Status'); ?>				
			</td>
			<td>
				<textarea cols="50" rows="4" name="dis_client_psy" class="text"><?php echo (!is_null($obj->dis_client_psy) ? dPformSafe($obj->dis_client_psy ) : '')?></textarea>
			</td>
	</tr>
	<tr>
			<td align="left">
				14a.<?php echo $AppUI->_('Name/Signature'); ?>				
			</td>
			<td>
				<?php echo arraySelect( $owners, 'dis_client_psy_staff', 'id="psy_staff_id" size="1" class="text"', @$obj->dis_client_psy_staff ? $obj->dis_client_psy_staff :-1); ?>
			</td>
	</tr>
	<tr>
		<td align="left">14b.<?php echo $AppUI->_('Date');?>: </td>
		<td align="left">
			<?php echo  drawDateCalendar('dis_client_psy_date',printDate($obj->dis_client_psy_date),false,'',false,10);?>
		</td>
	</tr>
	
	<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Social Summary'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
	</tr>
	<tr>
			<td align="left">
				15.<?php echo $AppUI->_('Client\'s Social Status'); ?>				
			</td>
			<td>
				<textarea cols="50" rows="4" name="dis_client_social" class="text"><?php echo (!is_null($obj->dis_client_social) ? dPformSafe($obj->dis_client_social ) : '')?></textarea>
			</td>
	</tr>
	<tr>
			<td align="left">
				16a.<?php echo $AppUI->_('Name/Signature'); ?>				
			</td>
			<td>
				<?php echo arraySelect( $owners, 'dis_client_social_staff', 'id="social_staff_id" size="1" class="text"', @$obj->dis_client_social_staff ? $obj->dis_client_social_staff :-1); ?>
			</td>
	</tr>
	<tr>
		<td align="left">16b.<?php echo $AppUI->_('Date');?>: </td>
		<td align="left">
			<?php echo  drawDateCalendar('dis_client_social_date',printDate($obj->dis_client_social_date),false,'',false,10);?>
		</td>
	</tr>	
	<tr>   		
   		<td align="left">
   			<input type="button" class="text" value="Cancel" onclick="cancel();">
   		</td>
   		<td align="right">
   			<input type="button" class="text" value="Submit" onclick="lauch();">
   		</td>
   </tr>
    
    </table>
   </td>
   </tr>   
</table>
</form>
<script>
var iNJs=true,sas;
var client_id=<?php echo ($kid->client_id > 0 ? $kid->client_id : 0)?>;
var statuses = <?php echo json_encode($dclientStatus)?>;
window.onload = up;
function up(){
	ses=$j.parseJSON(statuses);
	if(client_id == 0){
		$j("#ftab").find(":input:not(.adm_field)").attr("disabled",true);		
	}
	$j("#care_names").delegate("select","change",function(e){
		var rds = $j(this).find("option[value='"+$j(this).val()+"']").attr("data-rship");
		$j("#care_rels").val(rds);
	});
	if($j("#dt_client_status").val() != '7'){
		$j("#tca_fld").attr("disabled",true).datepick('disable');
	}
}

function lauch(){
	
	if($j("#clid").val() > 0){
		if(checkMandatFields() === true){
			document.changeDischarge.submit();
		}
	}else{
		alert("Please enter valid client Adm #");
		return false;
	}
}

function cancel(){
	if(client_id > 0){
		document.location.href="?m=clients&a=view&client_id="+client_id;
	}else{
		document.location.href="?m=clients";
	}
}
</script>
