var $tabs;
function xstart(){	
	if(tabLaunch ){
		eval(tabLaunch);
	}
	$tabs = $j("#tabs");	
	$tabs.tabs()
		.find(".topnav  a").click(function(e){
			if ($j(this).data("pass") === true) {
				$j(this).data("pass", false);
			}
			else {
				var id = $j(this).attr("href").match(/\d/);
				manager.getTab(id[0]);
			}
		})
	.end().show();
	if(openTab > 1){
		manager.tabToView(openTab);
		$tabs.mtoTab((openTab-1));
	}
}

$j.fn.mtoTab = function (tid){
	//manager.getTab(tid);
	$j("ul.topnav > li:eq("+tid+")",this).find("a").data("pass",true).trigger("click");
	return this;
}

var manager = (function(my){
	var showTabs = ["1"], tabLinks = {
		2: 'parse_tin&mode=getlist',
		3: 'transfer-out',
		4: 'importer',
		5: 'exported'
	},linkPrefix='/?m=manager&suppressHeaders=1&a=',
	tabLoadingIcon= new Image(),currentId=false,
	extraActions={
					2:'$j(".undobutt",$j("table.tin")[0]).live("click",function(e){e.stopPropagation();manager.undo(this);});$j("table.tin").tablesorter();',
					3:'$j(".undobutt",$j("table.tout")[0]).live("click",function(e){e.stopPropagation();manager.undo(this);});$j("table.tout").tablesorter({headers: { 0: {sorter: "idlink"}, 1: {sorter:"itlink"}, 2: { sorter:"idate" } } });', //
					5:'$j(".exterm").live("click", function(e){manager.dropExported(this);});'
					
	},afterLoaded='';
	tabLoadingIcon.src='/modules/outputs/images/ajax-loader.gif';
	
	function refreshTab(id){
		var xpos=$j.inArray((id+''),showTabs);
		delete showTabs[xpos];
		loadTab(id);
	}
	
	function loadTab(id){		
		if($j.inArray(id,showTabs) >= 0){
			return true;
		}else{
			$tabs.mtoTab(id-1);
			var $ctab=$j("div#tabs-"+id,$tabs),$mdt=$j(".mandat",$ctab).detach();
			$ctab.empty().append(tabLoadingIcon);
			$j.get(linkPrefix + tabLinks[id],function(msg){
				$ctab.empty().append($mdt).append(msg);
				showTabs.push(id+'');
				if(extraActions.hasOwnProperty(id))	{
					eval(extraActions[id]);
					//delete(extraActions[id]);
				}
			});
		}
	}
	
	function deleteFile(obrow){
		var eid=$j(obrow).attr("data-fid");
		if(confirm("Delete this exported file from system?")){
    	    	    $j.get("/?m=manager&suppressHeaders=1&mode=dropex",{dfid:eid},function(msg){
			if(msg && msg === 'ok'){
				$j(obrow).closest("tr").slideUp("slow",function(){
					$j(this).remove();
				})
			}
		    });
		}
	}
	
	function waitServerMsg(tabID){		
		setTimeout(function(){
			$j.ajax({
				url: linkPrefix+"monex",
				type: 'get',
				async: true,
				data: "ekey=" + currentId,
				success: function(msg){
					if (msg && msg === 'ok') {
						currentId=false;
						$j("#start_ex").attr("disabled",false);
						refreshTab(tabID);		
						eval(afterLoaded);
						afterLoaded='';				
					}
				}
			});
		},1000);
	}
	
	function transferBack(rcell){
		var $row=$j(rcell).closest("tr"),
			adm_no=$row.find("td:eq(0)").text(),
			clinic_id=$row.attr("data-clinic"),
			client_id=$row.attr("data-clid");
			$row.fadeTo(500,0.3);
		$j.get("/?m=manager&suppressHeaders=1&mode=transfer_back",{"client_id":client_id,'clinic': clinic_id},function(msg){
			if(msg && msg === 'ok'){
				var centers=$j("#sample_select").html().replace("##CLNT##",client_id);
				$row
					.toggleClass("past future")
					.find("td:lt(2)").find("a").toggleClass("exported fresh_users").end().end()
					.find("td:eq(2)").find("input:eq(1)").val(0).end()
					.next().html(centers)
					.next().html("");
				$row.fadeTo(500,1);
				$j("#tabfinish").attr("disabled",false);
			}
		});		
		return false;			
	}
	
	function departure(obj){
		var $row=$j(obj).closest("tr"),
		trin_id=$row.attr("data-tid");
		$row.fadeTo(500,0.3);
		$j.get(linkPrefix+"parse_tin&mode=undo",{'row_id':trin_id},function(msg){
			if(msg && msg === 'ok'){
				$row
				.find("td:eq(1)").toggleClass("past future").end()
				.find("td:eq(4)").html("Not Done");				
			}			
			$row.fadeTo(500,1);
		});
		return false;		
	}
	
	function postClinClients (){
		var vf=form2object("xlist");
		$j.ajax({
			url	: '?m=manager&mode=makecenters&suppressHeaders=1',
			type: 'post',
			data: 'cparts='+JSON.stringify(vf)+"&ekey=" + currentId,			
			success: function(msg){
				if(msg && msg === 'ok'){												
						var idata=$j.parseJSON(msg);
						showButs(idata);
					}else{
						alert("Parsing center assignment failed");
					}
				
			}
		});
	}
	
	function itererD (){
		var $cli_outs=$j("#clitab"),rows = $j("tbody",$cli_outs).find("tr.future"),res=true;
		for(var i=0,l = rows.length; i < l; i++){
			if($j("td:eq(3) > select.d2chk",$j(rows[i])).val() == "0"){
				$j(rows[i]).find("td:eq(3)").addClass("bcell");
				res=false;								
			}else{
				$j("td:eq(3)",$j(rows[i])).removeClass("bcell");
			}			
		}
		if(res === false){
			alert("Please define valid center!");
			$j(".bcell > select",$cli_outs).change(function(e){
				if($j(this).val() > 0){
					$j(this).parent().removeClass("bcell");
				}
			});
		}else{
			$j(".bcell > select",$cli_outs).unbind("change");
		}
		return res;		
	}
	
	function doImportClients(){
		return false;
	}
	
	
	return {
		getTab: function(tabId){
			loadTab(tabId);
		},
		tabToView: function(id){
			showTabs.push(id+'');
		},
		importDone: function(msg){
			if(msg && msg === 'ok'){				
				$j("#importbox").slideUp('fast');				
				$j("div#tabs-4",$tabs).find(".mandat").find("#msg_place")
					.addClass("msg_ok").html("<br>File successfully uploaded. Select file to import<br>").show().delay(3000).fadeOut(2000,function(){
						$j(this).removeClass("msg_ok").hide();
					});
				refreshTab(4);

					
			}
		},
		exportDone: function(){
			return true;			
		},
		initExport: function(){
			$j("#start_ex").attr("disabled",true);
			currentId=randomString();
			document.doEx.skey.value=currentId;
			waitServerMsg(5);			
		},
		dropExported: function(obj){			
			deleteFile(obj);
		},
		undo: function(obj){
			var $tabcase=$j(obj).closest("table");
			if ($tabcase.hasClass('tout')) {
				if (confirm("Undo Transfer-Out? Client status will be set back to previous state")) {
					transferBack(obj);
				}
			}else if($tabcase.hasClass('tin')){
				if (confirm("Remove this client from center?")) {
					departure(obj);
				}
			}
		},
		checkDrops: function(){
			var act=itererD();
			if(act === true){
				currentId=randomString();				
				$j("#tabfinish").attr("disabled",true);
				document.xlist.ekey.value=currentId;
				afterLoaded='manager.fixTab();';
				waitServerMsg(3);
				$j("#xlist").submit();							
			}
		},
		forzip: function(){					
			return true;
		},
		fixTab: function(){
			$j("#tabs").find("ul.topnav").find("li:eq(2)").find("b").remove();
		},
		reloadTab: function(id){
			refreshTab(id);
		},
		tinDone: function(msg){
			if(msg){
				if(msg === 'wrong_center'){
					alert("You are trying to import file for another center!");
					return false;
				}else if (msg.match(/\d+/) && parseInt(msg) > 0){
					$j("#tinbox").slideUp('fast');
					refreshTab(2);
				}
			}
		},
		startTIN: function(but){			
			$j(but).attr("disabled",true).after("<span>Importing...</span>");
			$j.get(linkPrefix+"parse_tin&mode=proceed",function(msg){
				if (msg && msg === 'ok') {
					refreshTab(2);
					res = true;
				}else{
					$j(but).attr("disabled", false).next().remove();
				}				
			});			
		}
	};
	
	
}(manager));

function randomSelect(zclass){
	$j("select."+zclass).each(function(){
		var opts = $j(this).find("option").length, pick = (function(opts){
			var res=0;
			while (res === 0) {
				res=Math.floor(Math.random() * opts);
			}
			return res;
		})(opts);
		$j(this).find("option:eq("+pick+")").attr("selected",true);		
	});
}
