<?php
$AppUI->savePlace();

/*$titleBlock = new CTitleBlock( 'Client Transfers', $m, "$m.$a" );
$titleBlock->show();*/

$out=TRUE;
$initScr=false;
switch ($_GET['action']) {
	case 'getlist':
		echo buildList();
	break;
	
	case 'rcv':
		$res=exportCs();			
	break;
	
	case 'import':
		$res=importUsers(); 
	break; 
	
	case 'makecenters':
		echo $res=buildCFile();			
	break;
	
	case 'centre_list':
		echo arraySelect( centerList(true), 'zzzCen', 'id="selCen" onchange="exim.rollCenters(1);" size="1" class="text"', -1);
	break;
	
	case 'centre_dates':
		echo getAllExports((int)$_GET['clin_id']);
	break;
	
	case 'centre_clean':
		echo $res=cancelClinic((int)$_POST['xid'],$_POST['xdate']);
	break;
	
	case 'clin_off':
		echo $res=cancelClinic((int)$_POST['clin_off'],false);
	break;
	
	default:
		$out=false;
	break;
}
if($out === true){
	return ;
}else{
	//check whether db already have client for transfer but files were not exported
	$cenbuts=checkLTPT(); 
	$initScr=true;
}
?>
<div id="imp-box"></div>
<a href="#" onclick="exim.showIMBox(this);" class="mlink">Import LTP Data</a>
<div id="importblk" class="mpart" style="display:none;">
<form action="/?m=clientmove&action=import&suppressHeaders=1" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : exim.startCallback, 'onComplete' : exim.importDone})">
<input type="file" name="ncomes" id="filim">
<input type="button" class="button" value="Import" onclick="exim.checkImFile(this)">
</form>
</div>
<br><br>
<div ><a href="#" onclick="exim.loadList(this);" style="float:left;" class="mlink">Export LTP Data</a><div id="eximld"></div></div><br>
<div id="xprtblk" class="exps mpart" style="display:none; clear: both;">
<form id='xlist' action="/m=clientmove&action=actlist&suppressHeaders=1" method="post">
<table class="tbl" id="clitab" cellspacing="1" cellpadding="2" border="0">
<thead>
  <tr>  	
    <th>Center</th>
    <th>Client name</th>
    <th>ADM #</th>    
  </tr>
</thead>
<tbody>
</tbody>
</table><br>
<input type="button" class="button" value="Submit" id="tabfinish" onclick="exim.checkDrops();">
</form>
</div><br>
<div id="rollBox"><a href="#" onclick="exim.rollCenters(this);" style="float:left;" class="mlink">Rollback Transfer</a><br><br>
	<div id="rollblk" class="mpart"></div>
</div><br>
<div id="failblk" class="exps" style="display: none;">No clients found for transfer!</div>
<br>
<div id="cenbuts" style="display: none;">
<span>Centers available for LTP transfer</span>
<ul class="bholds"></ul>
</div>


<script id="mvTpl" type="text/x-jquery-tmpl">
<li id="clin_${cid}"><span>${title} - <b>${amount}</b></span>&nbsp; <div style="float:right;"><input type="button" class="text" href="/?m=clientmove&suppressHeaders=1&action=rcv&cid=${cid}" value="Go"> &nbsp;&nbsp;<input type="button" value="Cancel" class="text" onclick="exim.cancelExport(${cid})"></div> </li>
</script>

<script>
<?php if($initScr === true){?>
var svcens=<?php echo ($cenbuts ? $cenbuts : '');?>;
window.onload=xstart;
function xstart(){
	exim.fromDbButs(svcens);
}
<?php }?>
</script>