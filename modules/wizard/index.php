<?php

$jmsg='';

if($_GET['todo'] === 'empty'){
	$fuid=(int)$_GET['fid'];
	if($fuid > 0){
		$sql='select count(*) as trows from wform_'.$fuid;
		$res=mysql_query($sql);
		if($res){
			$crows = mysql_fetch_object($res);

			$sql = 'truncate wform_'.$fuid;
			$dres = mysql_query($sql);
			if($dres){
				echo "ok";
				return ;
			}
		}
	}
	echo "fail";
	return ;
}
elseif($_GET['todo'] === 'del'){
	$fuid=(int)$_GET['fid'];
	if($fuid > 0){
		$sql='select count(*) from wform_'.$fuid;
		$res=mysql_query($sql);
		if($res){
			$nowrows=mysql_fetch_row($res);
			if((int)$nowrows[0] === 0){
				$sql='select title,subs from form_master where id="'.$fuid.'" limit 1';
				$res=mysql_query($sql);
				if($res){
					$fname=mysql_fetch_object($res);
				}
				if(!is_null($fname->subs)){
					$subs=explode(',',$fname->subs);
					foreach($subs as $st){
						$sql='drop table '.$st.' IF EXISTS';
						$dres=mysql_query($sql);
					}
				}
				$sql='delete from form_master where id="'.$fuid.'" limit 1';
				$res1=mysql_query($sql);
				$sql='DROP TABLE wform_'.$fuid;
				$res2=mysql_query($sql);
				if(file_exists($baseDir.'/modules/outputs/data/wform_'.$fuid.'.fields.php')){
					unlink($baseDir.'/modules/outputs/data/wform_'.$fuid.'.fields.php');
				}
				if(file_exists($baseDir.'/modules/outputs/titles/wform_'.$fuid.'.title.php')){
					unlink($baseDir.'/modules/outputs/titles/wform_'.$fuid.'.title.php');
				}
				if($res1 && $res2){
					//$jmsg="Form ".$fname->title.' deleted';
					echo 'ok';
				}
			}
		}else{
			echo 'fail';
		}
	}
	return false;
}elseif($_GET['mode'] == 'editf' && (int)$_GET['fid'] > 0){
	$sql='select title,fields,registry,touch from form_master WHERE id="'.(int)$_GET['fid'].'" limit 1';
	$res=mysql_query($sql);
	if($res && mysql_num_rows($res)  == 1){
		$trow=my_fetch_assoc($res);
		echo json_encode(array('rows'=>unserialize(gzuncompress($trow['fields'])),'title'=>$trow['title'],'registry'=>$trow['registry'],'touch'=>$trow['touch']));
	}else{
		echo 'fail';
	}
	return false;
}elseif($_GET['mode'] == 'printForm' && (int)$_GET['fid'] > 0){
	$fuid=(int)$_GET['fid'];
	$wz = new Wizard('print');
	$wz->loadFormInfo($fuid);
	$wz->tableWrap();

	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=form_".str_replace(' ','_',$wz->formName).".doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
	<style type='text/css'>
	table{
		border-collapse:collapse;
	}
	table,td,th{
		font-family: arial, sans-serif;
		font-size: 10pt;
		border:1px solid #000;
	}
	</style>
	";
	echo "<body>
			<table cellpadding='2' cellspacing='1' width='99%' border='0'><tbody>
			<tr><td>&nbsp;</td><td>Visit Date&nbsp;. . . /. . ./ . . . . </td></tr>";
	foreach($wz->fields as $fld_id => $fld){
		if(isset($fld['otm']) && count($fld['subs']) > 0){
			if($fld['otm'] === false && $fld['tout'] === false){
				echo "<tr><td colspan='2'><b>".$fld['name'].'</b><br></tr>';
			}else{
				echo "<tr><td>&nbsp;</td><td><b>".$fld['name'].'</b><br>';
				echo "<table cellpadding='1' cellspacing='0' width='90%' border='1'>
				<thead>
				<tr>";
			}
			if($fld['tout'] === true){
				$firsttab=$fld['subs'][0];
				$blist='<th>&nbsp;</th>';
				if($firsttab['type'] === 'checkbox' || $firsttab['type'] === 'radio'){
					$columns = $wz->getValues($firsttab['type'],$firsttab['sysv'],false,true,$firsttab['other']);
					foreach ($columns as $vid => $vcol){
						$blist.='<th>'.$vcol.'</th>';
					}
					$blist.='</tr>
							</thead>
							<tbody>';
					foreach ($fld['subs'] as $sy => &$fsub){
						$blist.=$wz->outputField(str_replace('fld_','',$fsub['dbfld']),$fsub,$dvals[$fsub['dbfld']],false,true);
					}
					$blist.='</tbody>
							</table></td></tr>';
					echo $blist;
				}
			}else{
				foreach ($fld['subs'] as $sid => &$fsub) {
					if($fld['otm'] === false){
						echo $wz->outputField(str_replace('fld_','',$fsub['dbfld']),$fsub,false,$fld['tout']);
					}elseif($fld['tout'] === false){
						echo "<th>".$fsub['name']."</th>";
					}
				}
				if($fld['otm'] === true){
					echo "</tr></thead><tbody>";
					for($y =0 ; $y < 3; $y++){
						echo "<tr>";
						for($x=0; $x < count($fld['subs']); $x++){
							echo "<td>&nbsp;</td>";
						}
						echo "</tr>";
					}
					echo "</tbody></table></td></tr>";
				}
			}
		}else{
			echo $wz->outputField(str_replace('fld_','',$fld['dbfld']),$fld,false);
		}
	}
	echo "</tbody></body></html>";
	return ;
}elseif ($_GET['todo']==='onoff' && (int)$_GET['fid'] > 0){
	$fid=(int)$_GET['fid'];
	$sql='update form_master set valid=(select if(valid > 0,0,1) ),valid_change=now() where id="'.$fid.'"';
	$res=mysql_query($sql);
	if($res){
		echo 'ok';
	}else {
		echo 'fail';
	}
	return ;
}elseif($_GET['todo'] === 'exportf' && (int)$_GET['fid'] > 0 ){
	$fid=(int)$_GET['fid'];
	$sql='select * from form_master where id = "'.$fid.'" limit 1';
	$res=mysql_query($sql);
	if($res && mysql_num_rows($res) === 1){
		$fd=mysql_fetch_assoc($res);
		$fd['digest']=null;
		$fd['fields']=unserialize(stripslashes(gzuncompress($fd['fields'])));
		$fields = $fd['fields'];
		/*search for questions using sysvals and add those sysvals to package with form itself*/
		$togrift=array();
		foreach ($fields as &$fld) {
			if(!isset($fld['subs'])){
				if( in_array($fld['type'],array('select','radio','checkbox','select_multi')) &&
					is_numeric($fld['sysv']) ){
						$togrift[]=$fld['sysv'];
				}
			}else{
				foreach ($fld['subs'] as &$sfld) {
					if( in_array($sfld['type'],array('select','radio','checkbox','select_multi')) &&
						is_numeric($sfld['sysv']) ){
							$togrift[]=$sfld['sysv'];
					}
				}
			}
		}
		$topack=array();
		$togrift = array_unique($togrift);
		if(count($togrift) > 0){
			$sql = 'select * from svsets where id IN ('.join(",",$togrift).')';
			$res = mysql_query($sql);
			if($res){
				while($srow = my_fetch_assoc($res)){
					$topack[$srow['id']]=$srow;
				}
				mysql_free_result($res);
			}
		}

		$sql = 'select * from wform_'.$fid;
		$rdata = mysql_query($sql);
		$datas = array();
		if((int)$_GET['wdata'] === 1){
			if($rdata && mysql_num_rows($rdata) > 0){
				while ($drow = my_fetch_assoc($rdata) ){
					$datas[]=$drow;
				}
			}
			mysql_free_result($rdata);
			if($fd['subs']!= ''){
				$sparts = explode(",",$fd['subs']);
				$datas['subs']=array();
				foreach ($sparts as $sutab) {
					//wf_20_sub_3
					$sutab1 = preg_replace("/^wf_\d+_/","",$sutab);
					$sql = 'select * from '.$sutab;
					$rsdata = mysql_query($sql);
					if(!is_array($datas['subs'][$sutab1])){
						$datas['subs'][$sutab1] = array();
					}
					if($rsdata && mysql_num_rows($rsdata) > 0){
						while ($dsrow = my_fetch_assoc($rsdata) ){
							$datas['subs'][$sutab1][]=$dsrow;
						}
					}
				}
			}
		}
		unset($fd['digest'],$fd['subs']);
		$fd['rowData'] = $datas;
		$bfd = array("form"=>$fd,"sets"=>$topack);
		$str=base64_encode(addslashes(gzcompress(serialize($bfd),9)));
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment;Filename=form_".str_replace(' ','_',$fd['title']).".fbn");
		echo $str;
		return;

	}
}elseif ($_GET['todo'] === 'importf'){
	$res='fail';
	if(count($_FILES) === 1  && $_FILES['frfile']['size'] > 0 && $_FILES['frfile']['error'] == 0){
		$res = importForm();
	}
	echo $res;
	return ;
}


?>

<link rel="stylesheet" type="text/css" href="/modules/wizard/jquery-ui-1.8.12.custom.css" />
<?php  
$moduleScripts[]="/modules/wizard/jquery-ui-1.8.12.custom.min.js";

$q = new DBQuery();
$q->addTable('sysvals');
$q->addQuery('sysval_title,sysval_title as c');
$q->addOrder('sysval_title');
$q->addWhere('sysval_tport = "0"');
$q->addWhere("sysval_key_id='1'");
$svals = $q->loadHashList();

//$svals = arrayMerge(array('-1'=>'Select Answer set','SysCenters'=>'List of Centers','SysClients'=>'List of Clients','SysStaff'=>'List of Staff'),$svals);

$q = new DBQuery();
$q->addTable('svsets');
$q->addQuery('id,title,vtype,level,touch');
$q->addOrder('vtype,title');
$q->addWhere('status="1"');
$sets = $q->loadHashListMine();

$setlist = $touchlog = array();

foreach ($sets as $sd) {
	$setlist[$sd['id']]=$sd['title'];
	$touchlog[$sd['id']]=$sd['touch'];
}


$singles = array('-1'=>'Select Answer set',
                            'SysCenters'=>'List of Centers',
                            'SysClients'=>'List of Clients',
                            'SysStaff'=>'List of Staff',
                            'SysLocations'=>'List of Locations',
	                        'SysPositions' => 'List of Positions'
                         );
$otherlists= array("-1" => '-- Select --');

$nsets=array();
foreach($sets as $iset){
	if(!isset($nsets[$iset['vtype']])){
		$nsets[$iset['vtype']]=array();
	}
	$nsets[$iset['vtype']][$iset['id']]=$iset['title'];
}

$q=new DBQuery();
$q->addTable('form_master');
$q->addOrder('title');
$forms=$q->loadHashListMine();

$formsjs='';

echo '<div id="msgbox" style="color:#288d28;font-weight:800;"></div>';

echo '<div id="tabs" style="visibility:hidden;" class="bigtab">
<ul class="topnav">
	<li><a href="#tabs-1"><span>Forms</span></a></li>
	<li id="editTab"><a href="#tabs-2"><span>Wizard<img src="/images/tab_load.gif" style="display:none;" border="0"></a></span></li>
	<li><a href="#tabs-3" onclick="svals.init();"><span>System Values<img src="/images/tab_load.gif" style="display:none;" border="0"></span></a></li>
</ul>
<div id="tabs-1" class="mtab">
<p>
	<span onclick="$j(\'#importbox\').toggle();" class="fhref flink">Import form</span>
		<div id="importbox" class="myimporter">
			<form name="upq" action="/?m=wizard&suppressHeaders=1&todo=importf" enctype="multipart/form-data" method="POST" onsubmit="return AIM.submit(this, {onStart : startCallback, onComplete : wzrd.addIForm})">
				<input type="file" name="frfile" id="fultra">
				<input type="submit" value="Import Form" class="button" disabled="disabled" >
			</form>
		</div>';

if(count($forms) > 0){
	echo '<table cellspacing="1" cellpadding="2" border="0" id="qtable" class="tbl tablesorter moretable">
	<thead>
		<tr>
			<th>Form name</th>
			<th>Type</th>
			<th>Status</th>
			<th>Date of status change</th>
			<th>Entries</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>';
	foreach($forms as &$fvals){
		$q = new DBQuery();
		$q->addTable('wform_'.$fvals['id']);
		$q->addQuery("count(*)");
		$fentries=(int) $q->loadResult();
		$fvals['rows']=$fentries;
		unset($fvals['fields']);
		$fvals['valid_change']=printDate($fvals['valid_change']);
	}
	$formjs=json_encode($forms);
	echo '</tbody></table>';
}
echo '</p></div>
	<div id="tabs-2" class="mtab">
		<p>';
?>
			<button type="button" class="text" onclick="wzrd.sectionWork('add');">Add new Section</button>&nbsp;&nbsp;
			<button type="button" class="text" onclick="wzrd.rowWork('add');">Add new Question</button>

			<form action="?m=wizard&a=saveform" method="post" name="formform" onsubmit="return false;">
				Form name&nbsp;<input type="text" class="text" size="30" name="formName" id="fname">
				<input type="checkbox" value="1" id="regForm" name="regForm"><label id="regFormCap" for="regForm">Registry</label>
				<br>
				<div id="hugeStore">
					<ul id="mainList" class="initlist">
					</ul>
				</div>

				<input type="button" value="Clear" class="text" onclick="wzrd.clean();">&nbsp;&nbsp;&nbsp;
				<input type="button" value="Save" class="text" onclick="wzrd.collect();">&nbsp;&nbsp;&nbsp;
				<input type="button" value="View" class="text" onclick="wzrd.preView();" style="display:none;" id="viewbut">

				<input type="hidden" id="forSend" name="formsum" value="">
				<input type="hidden" name="form_id" value="" id="fid">
			</form>
		</p>
	</div>
	<div id="tabs-3">
		<p>
			<table cellspacing="1" cellpadding="2" border="0" id="stable" class="tbl tablesorter moretable" style="display:none;clear:both;">
				<thead>
					<tr>
						<th class="header">Name</th><th class="header">Type</th><th class="header">Level</th><th class="header">Status</th><th class="header">Created/Changed</th><th>Options</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</p>
	</div>
</div>
<div id="msg_note_box"><div class="note_msg ci_sprite"></div><span></span></div>
<div id="stock" style="display:none;">
	<?php echo arraySelect($svals,'sysval_use-old','class="sysval_use-old text"',-1);
	/* <div class="fbutton newsval qticon"></div> */
	foreach ($nsets as $nkey => $net){
		$net=arrayMerge( ($nkey === 'select' ? $singles : $otherlists) ,$net);
		echo arraySelect($net,'sysval_use','class="sysval_use text '.$nkey.'"',-1);
	}
	?>
</div>
<div id="stip" style="display: none;"></div>
<div class="mctrl" style="display:none;">
	<div class="fbutton inc">+</div>
	<div class="fbutton del">-</div>
</div>
<script type="text/javascript">
	window.onload=up;
	var tmsg="<?php echo $jmsg;?>",
		pforms=<?php echo ($formjs != '' ? $formjs : 'false')?>;

	function up(){
		if(tmsg != ''){
			info(tmsg,"#msgbox");
		}
		wzrd.init();
	}
	var valtail = <?php	echo (count($touchlog) > 0 ? json_encode($touchlog) : "{}"); ?>;
</script>