<?php

require_once($AppUI->getModuleClass("followup"));

$cbcs = dPgetSysVal('CBCHomeCare');
$genderTypes = dPgetSysVal('GenderType');
$boolTypes = dPgetSysVal('YesNo');
$timeTypes = dPgetSysVal('OldNew');

//load clinics
$q  = new DBQuery;
$q->addTable('clinics','c');
$q->addQuery('clinic_id');
$q->addQuery('clinic_name');
$q->addOrder('clinic_name');
//$clinics = $q->loadHashList();
$clinics = arrayMerge(array(-1=> '-Select Clinic -'),$q->loadHashList());
$today = date('d/m/Y');

$clinrefs=dPgetSysVal('ClinicalReference');
$clister = new lister('care','cbc',$cbcs,'care',true);
$rlister = new lister('refs','cbc',$clinrefs,'clref');

$moduleScripts[]='/modules/public/form_edit.js';
$obj = null;
if(isset($_GET['initem']) && intval($_GET['initem']) > 0){
	$old_id = intval($_GET['initem']);
	$obj = new CCBCCheck();
	$obj->load($old_id);
	if($obj->cbc_entry_date != ''){
		$savedDate = new CDate($obj->cbc_entry_date);
		$savedDateWeb = $savedDate->format($AppUI->getPref('SHDATEFORMAT'));
	}
	$answer = makeListPerson ( $obj->cbc_adm_no, true );
}
?>

<form method="post" action="?m=cbccheck" id="filid">
<input type="hidden" name="dosql" value="do_cbc_aed" />
<input type="hidden" name="jfk" id="jfk">
<table id="qtab" class="csclear">
<tr>
	<td><?php echo $AppUI->_("Name of CBC")?></td>
	<td>
		<?php /*<input type="text" class="text mandat" name="cbc_name" value="<?php echo (!is_null($obj) ? $obj->cbc_name : '');?>">*/
			echo arraySelect(getCHWList(),'cbc_name','class="text mandat"',$obj->cbc_name ? $obj->cbc_name : 0);
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	</td>
	<td>	
		<?php echo$AppUI->_("VILLAGE / ESTATE")?>
	</td>
	<td>
		<input type="text" class="text" name="cbc_village" value="<?php echo (!is_null($obj) ? $obj->cbc_village : '');?>">
	</td>
</tr>
<tr>
	<td><?php echo $AppUI->_("Center")?></td>
	<td><?php echo arraySelect($clinics,'cbc_clinic_id','id="clinic_id" class="text" onchange="loadLocation(this)"',
		(!is_null($obj) ? $obj->cbc_center_id : ''));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	<td><?php echo $AppUI->_("Location")?></td>
	<td>
	<div id="loader"></div><select id="locsel" class="text" name="cbc_location" style="display:none;">
		<?php
		if(!is_null($obj) && $obj->cbc_center_id > 0 ){
			$q=new DBQuery();                                                                                                                                                                                                                    
			$q->addTable('clinic_location');
			$q->addWhere('clinic_location_clinic_id="'.$obj->cbc_center_id.'"');
			$q->addQuery('clinic_location_id as id, clinic_location as name');
			$locs=$q->loadHashListMine();
			foreach ($locs as $kid => $kval) {
				echo '<option value="'.$kid.'" '.($kid == $obj->cbc_location ? 'selected="selected"' : '').'>'.$kval['name']."</option>\n";
			}
		}
		?>
		</select>
	</td>
</tr>
</table>
<br>
<br>
<table id="opf" class="tbl atbl" width="100%" border=0 cellspacing="1" cellpadding="2">
<thead>
<tr>
	<th>Date</th>
	<th>Adm. #</th>
	<th style="white-space: nowrap;">Patient or Client Name</th>
	<!-- <th>Old / New</th> -->
	<th>Sex</th>
	<th>Age</th>
	<th>HOME BASED CARE PERFORMED</th>
	<th>Adherence Support</th>
	<th>Remarks</th>
	<th>Reffered</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<tr data-row="0">
	
	<td>
	<input type="hidden" name="fentry[0][cbc_id]" class="form_row_id" value="<?php 
	if(!is_null($obj)){
		echo $old_id;
	}
	?>">
		<?php 
			echo drawDateCalendar('fentry[0][entry_date]',(!is_null($obj) ? $savedDateWeb : $today),false,'id="date_0"',false,9);
		?>
	</td>
	<td style="white-space: nowrap;">
		<div class="jbox">
			<input type="text" name="fentry[0][cbc_adm_no]" class="adm_field text" size="8" value="<?php 
				if(!is_null($obj)){
					echo $obj->cbc_adm_no;
				}
			?>">
			<div class="bsubmit" style="float:left;" onclick="editor.postName(this,1);" title="Retrieve name"></div>
		</div>
		<input type="hidden" name="fentry[0][cbc_client_id]" class="clid" value="<?php
			if(!is_null($obj)){
				echo $answer['client_id'];
			}
		?>">
	</td>
	<td class="client_name"><?php
		if(!is_null($obj)){
			echo $answer['child'][0];
		}else{
			echo '&nbsp;';
		}
	?></td>
	<!-- <td style="white-space: nowrap;">
		<?php echo arraySelectRadio($timeTypes,'fentry[0][cbc_old]','','',$identifiers);?>		
	</td> -->
	<td style="white-space: nowrap;" class="genderp">
		<?php echo arraySelectRadio($genderTypes,'fentry[0][cbc_sex]','',(!is_null($obj) ? $answer['child'][2] : ''),$identifiers);?>		
	</td>
	<td>
		<input type="text" name="fentry[0][cbc_age]" class="text" size="8" value="<?php
		if(!is_null($obj)){
				echo $answer['child'][1];
		}
		?>">	
	</td>
	<td class="care_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>	
	<td style="white-space: nowrap;">
		<?php echo arraySelectRadio($boolTypes,'fentry[0][cbc_adh_support]','',(!is_null($obj) ? $obj->cbc_adh_support : ''))?>		
	</td>
	<td>
		<textarea cols="20" rows="1" style="height:20px;" name="fentry[0][cbc_remarks]"><?php
			if(!is_null($obj)){
				echo $obj->cbc_remarks;
			}
		?></textarea>		
	</td>
	<td class="refs_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>
	<td >
		<div>
			<div class="add_row" title="Add entry" onclick="editor.counter(this);"></div>
			<div class="del_row" title="Delete entry" onclick="editor.counter(this);"></div>
		</div>		
	</td>
</tr>
</tbody>
</table>
<input type="button" value="Submit" onclick="editor.simpleSend();">
</form>
<div id="ilist" style="display:none;"></div>
<div id="iinfo" style="display:none;"></div>
<script>
var vlist={},intlist={},dsplit = false;
intlist['care'] = <?php echo json_encode($cbcs);?>;
intlist['refs'] = <?php echo json_encode($clinrefs);?>;
vlist['care'] = '<?php echo $clister->build(true);?>';
vlist['refs'] = '<?php echo $rlister->build(true);?>';
<?php
	if(count($locs) > 0){
		echo 'document.getElementById("locsel").style.display="";';
	}
	if(!is_null($obj)){
		if($obj->cbc_hbcare != '' && !is_null($obj->cbc_hbcare)){
			echo 'var inj_care=['.$obj->cbc_hbcare.'];';
		}
		if($obj->cbc_refers != '' && !is_null($obj->cbc_refers)){
			echo 'var inj_refs=['.$obj->cbc_refers.'];';
		}
		//if($obj->cbc_refers_note != '' ){
			echo 'var inj_refer_note="'.$obj->cbc_refers_note.'";';
		//}
		echo 'window.onload = up;';
	}
?>
function up (){
	editor.collectJets()
}
</script>