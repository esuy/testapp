<?php

require_once($AppUI->getModuleClass("followup"));

$needz = dPgetSysVal('ServiceTypes');
$genderTypes = capt8(dPgetSysVal('GenderType'));
$boolTypes = capt8(dPgetSysVal('YesNo'));

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

$adhss = dPgetSysVal('AdherenceSupport');
$clinrefs = dPgetSysVal('ClinicalReference');


$nlister = new lister('needs','chw',$needz,'need',true);
$alister = new lister('adhs','chw',$adhss,'adhs',true);
$rlister = new lister('refs','chw',$clinrefs,'clref',true);

$moduleScripts[]='/modules/public/form_edit.js';
$moduleScripts[]='/modules/chwcheck/edit.js';

if(isset($_GET['initem']) && intval($_GET['initem']) > 0){
	$old_id = intval($_GET['initem']);
	$obj = new CCHWCHECK();
	$obj->load($old_id);
	if($obj->chw_entry_date != ''){
		$savedDate = new CDate($obj->chw_entry_date);
		$savedDateWeb = $savedDate->format($AppUI->getPref('SHDATEFORMAT'));
	}
	$answer = makeListPerson ( $obj->chw_adm_no, true );
}
?>

<form method="post" action="?m=chwcheck" id="filid">
<input type="hidden" name="dosql" value="do_chw_aed" />
<input type="hidden" name="jfk" id="jfk">
<table id="qtab" class="csclear">
<tr>
	<td><?php echo $AppUI->_("Name of CHW")?></td>
	<td>
		<?php /*<input type="text" class="text mandat" name="chw_name" value="<?php echo (!is_null($obj) ? $obj->chw_name : '');?>">*/
			echo arraySelect(getCHWList(),'chw_name','class="text mandat"',$obj->chw_name ? $obj->chw_name : 0);
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	</td>
	<td><?php echo$AppUI->_("VILLAGE / ESTATE")?></td>
	<td><input type="text" class="text" name="chw_village" value="<?php echo (!is_null($obj) ? $obj->chw_village : '');?>">
	</td>
</tr>
<tr>
	<td><?php echo $AppUI->_("Center")?></td>
	<td><?php echo arraySelect($clinics,'chw_center_id','id="clinic_id" onchange="loadLocation(this)" class="text"',
		(!is_null($obj) ? $obj->chw_center_id : ''));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	<td><?php echo $AppUI->_("Location")?></td>
	<td><div id="loader"></div><select id="locsel" name="chw_location" style="display:none;" class="text">
			<?php
		if(!is_null($obj) && $obj->chw_center_id > 0 ){
			$q=new DBQuery();                                                                                                                                                                                                                    
			$q->addTable('clinic_location');
			$q->addWhere('clinic_location_clinic_id="'.$obj->chw_center_id.'"');
			$q->addQuery('clinic_location_id as id, clinic_location as name');
			$locs=$q->loadHashListMine();
			foreach ($locs as $kid => $kval) {
				echo '<option value="'.$kid.'" '.($kid == $obj->chw_location ? 'selected="selected"' : '').'>'.$kval['name']."</option>\n";
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
	<th>Old/New</th>
	<th>Date</th>
	<th>Adm. #</th>
	<th style="white-space: nowrap;">Name</th>	
	<th>Sex&nbsp;&nbsp;&nbsp;</th>
	<th>Age</th>
	<th>Has careplan</th>
	<th>ARVs&nbsp;</th>
	<th>OIRx&nbsp;</th>
	<th style="white-space: nowrap;">TB Rx&nbsp;</th>
	<th>Nutrition</th>
	<th>Adherence support</th>
	<th>Needs assessed</th>
	<th>Needs supported</th>
	<th>Community mobilisation</th>
	<th>Refferal To</th>	
	<th>Remarks</th>	
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<tr data-row="0">
	<td class="definer">
	<input type="hidden" name="fentry[0][chw_id]" class="form_row_id" value="<?php 
	if(!is_null($obj)){
		echo $old_id;
	}
	?>">
		<?php echo arraySelectRadio($timeTypes,'fentry[0][chw_old]','onchange="rowMode(this)"','',$identifiers);?>		
	</td>
	<td class="calendar">
		<?php 
			echo drawDateCalendar('fentry[0][entry_date]',(!is_null($obj) ? $savedDateWeb : $today),false,'id="date_0"',false,9);
		?>
	</td>
	<td style="white-space: nowrap;">
		<div class="jbox">
			<input type="text" name="fentry[0][chw_adm_no]" class="adm_field text" size="8" value="<?php 
				if(!is_null($obj)){
					echo $obj->chw_adm_no;
				}
			?>">
			<div class="bsubmit" style="float:left;" onclick="editor.postName(this,1);" title="Retrieve name"></div>
		</div>
		<input type="hidden" name="fentry[0][chw_client_id]" class="clid" value="<?php
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
	
	<td class="genderp">
		<?php echo arraySelectRadio($genderTypes,'fentry[0][chw_sex]','',(!is_null($obj) ? $answer['child'][2] : ''),$identifiers);?>		
	</td>
	<td>
		<input type="text" name="fentry[0][chw_age]" class="text" size="4" value="<?php
		if(!is_null($obj)){
				echo $answer['child'][1];
		}
		?>"> 	
	</td>
	<td >
		<?php echo arraySelectRadio($boolTypes,'fentry[0][chw_hasplan]','',(!is_null($obj) ? $obj->chw_hasplan : ''))?>		
	</td>
	<td class="arv_choice">
		<?php echo arraySelectRadio($boolTypes,'fentry[0][chw_arv]','onchange="editor.xtras(this);"','')?>		
		<select style="display: none;" name="fentry[0][chw_arv_note]" class="text xtras">
			<option disabled="disabled" selected="selected">-- Select --</option> 
			<option value="1">1st line</option>
			<option value="2">2nd line</option>
		</select>
	</td>
	<td class="oir_choice">
		<?php echo arraySelectRadio($boolTypes,'fentry[0][chw_oir]','onchange="editor.xtras(this);"',(!is_null($obj) ? $obj->chw_oir : ''))?>
		<input type="text" class="text xtras" style="display:none;float:left;" name="fentry[0][chw_oir_note]" size="10">		
	</td>
	<td >
		<?php echo arraySelectRadio($boolTypes,'fentry[0][chw_tb]','',(!is_null($obj) ? $obj->chw_tb : ''))?>		
	</td>
	<td>
		<input type="text" name="fentry[0][chw_nutrition]" class="text" size="5" value="<?php echo (!is_null($obj) ? $obj->chw_nutrition : '');?>">
	</td>
	<td class="adhs_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>
	<td class="issue_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>	
	<td class="service_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>	
	<td style="white-space: nowrap;" class="comobi">		
		<?php
			if(!is_null($obj) && !is_null($obj->chw_comm_mob )){
				$cm=explode(",",$obj->chw_comm_mob);
			}else{
				$cm = false;
			}
		?>
		Youth M:<input type="text" class="text" size="2" name="fentry[0][maly][my]" value="<?php echo ($cm !== false ? $cm[1] : '');?>">
		&nbsp;F:<input type="text" class="text" size="2" name="fentry[0][maly][fy]" value="<?php echo ($cm !== false ? $cm[3] : '');?>"><br>
		Adult&nbsp;&nbsp;M:<input type="text" class="text" size="2" name="fentry[0][maly][fa]" value="<?php echo ($cm !== false ? $cm[2] : '');?>">&nbsp;
		F:<input type="text" class="text" size="2" name="fentry[0][maly][ma]" value="<?php echo ($cm !== false ? $cm[0] : '');?>">
		
	</td>
	<td class="refs_block ltrigger">&nbsp;
		<input type="button" class="text" onclick="editor.show(this);" value="Edit">		
	</td>	
		<!-- <input type="text" class="text" name="fentry[0][chw_refers]" size="20"> -->	
	<td>
		<textarea cols="20" rows="1" style="height:20px;" name="fentry[0][chw_remarks]"><?php echo (!is_null($obj) ? $obj->chw_remarks : ''); ?></textarea>		
	</td>
		
	<td class="rowplim">
		<div>
			<div class="add_row" title="Add entry" onclick="editor.counter(this);"></div>
			<div class="del_row" title="Delete entry" onclick="editor.counter(this);"></div>
		</div>		
	</td>
</tr>
</tbody>
</table>
<input type="button" value="Submit" onclick="editor.forSend();" class="text">
</form>
<div id="ilist" style="display:none;"></div>
<div id="iinfo" style="display:none;"></div>
<script>
var vlist = {},intlist={},dsplit = true;
intlist['care'] = <?php echo json_encode($needz);?>;
intlist['issue'] = intlist['care'];
intlist['service'] = intlist['care'];
intlist['adhs'] = <?php echo json_encode($adhss);?>;
intlist['refs'] = <?php echo json_encode($clinrefs);?>;
vlist['issue'] = '<?php echo $nlister->build(true) ;?>';	
vlist['service'] = vlist['issue'];
vlist['adhs'] = '<?php echo $alister->build(true);?>';
vlist['refs'] = '<?php echo $rlister->build(true);?>';
	
<?php
if(!is_null($obj)){	

			if($obj->chw_adh_support != '' && !is_null($obj->chw_adh_support)){
				$tval='['.$obj->chw_adh_support.']';
			}else{
				$tval = 'false';
			}
			echo 'var inj_adhs=',$tval,';';
			
			if($obj->chw_refers != '' && !is_null($obj->chw_refers)){
				$tval = '['.$obj->chw_refers.']';
			}else{
				$tval = 'false';
			}
			echo 'var inj_refs=',$tval,';'; 
			
			if($obj->chw_support != '' && !is_null($obj->chw_support)){
				$tval = '['.$obj->chw_support.']';
			}else{
				$tval = 'false';
			}
			echo 'var inj_service=',$tval,';';
			
			if($obj->chw_assess != '' && !is_null($obj->chw_assess)){
				$tval='['.$obj->chw_assess.']';
			}else{
				$tval = 'false';
			}
			echo 'var inj_issue=',$tval,';';
			
			echo 'var inj_refer_note="'.$obj->cbc_refers_note.'";';
}
?>
var inj_care=false;
function ddd1(){
	initEdits();
	<?php 
		if(!is_null($obj)){
			echo '
			$j(".definer").find("input[value=\''.$obj->chw_old.'\']").rowCHW();
			$j(".arv_choice").find("input[value=\''.$obj->chw_arv.'\']").getExtras().end()
				.find("select").val("'.$obj->chw_arv_note.'");
			$j(".oir_choice").find("input[value=\''.$obj->chw_oir.'\']").getExtras().end()
				.find("input[type=\'text\']").val("'.$obj->chw_oir_note.'");
			';

			if(count($locs) > 0){
				echo 'document.getElementById("locsel").style.display="";';
			}			
			
			echo 'editor.collectJets();';
	}	
	?>
}
window.onload=ddd1;
</script>
