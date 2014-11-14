var frm = (function (my) {

	var $wf = $j("#wform"),
		self, registry, refrels, irun = true;

	function parseData() {
		var dirty = 0, res = false;
		$j(":input.mandat:visible", $wf).each(function () {
			if (solveField(this) === false) {
				++dirty;
			}
		});
		$j(".inrange:visible", $wf).each(function () {
			var rngs = $j(this).attr("data-rng").split("|"),
				origval = $j(this).val(),
				tv = parseFloat(origval),
				start = parseFloat(rngs[0]),
				end = parseFloat(rngs[1]),
				tpl = '',
				uvals,
				showtxt;
			if (!isNaN(start) && !isNaN(end)) {
				tpl = 'Value must be in limit between %s and %s';
				showtxt = sprintf(tpl, start, end);
			} else {
				if (isNaN(start) && !isNaN(end)) {
					tpl = "Value must be less than %s";
					uvals = end;
				} else if (!isNaN(start) && isNaN(end)) {
					tpl = "Value must be greater than %s";
					uvals = start;
				}
				showtxt = sprintf(tpl, uvals);
			}
			if (!(isset(origval) && tv > 0 && tv >= start && tv <= end)) {
				$j(this).addClass("alert").parent()
					.find(".rngmsg").remove().end()
					.append(["<span class='alert rngmsg'>", showtxt, "</span>"].join(""));
				++dirty;
			} else {
				$j(this).removeClass("alert").parent().find(".rngmsg").remove();
			}
		});
		if (dirty > 0) {
			alert("Please fill all required fields!");
		} else {
			res = true;
		}
		return res;
	}

	function solveField(obj) {
		var $o = $j(obj), ptype = $o.attr("type").toLowerCase(),
		/*
		 ptag = $o[0].nodeName.toLowerCase(),
		 pname = $o.attr("name"),
		 */
			pval = $o.val(), res = false,
			$pcontrol = $o.closest("td").find(".fcontrol");

		if (ptype === 'radio') {
			pval = $o.parent().find(":input:checked").length || null;
		}
		if (pval == -1 || !isset(pval) || pval === 'empty' || trim(pval.toString()).length === 0) {
			$pcontrol.removeClass("rowDone").addClass("hereBug");
		} else {
			$pcontrol.removeClass("hereBug").addClass("rowDone");
			res = true;
		}
		return res;
	}

	function prepareMandats() {
		$j("select.mandat", $wf).live("change", function (e) {
			solveField(this);
		});
		/*$j(".mandat:not(select)",$wf).closest("td").focusout(function(e){
		 solveField($j(this).find(":input"));
		 });*/
		$j(".mandat").live("mouseenter", function () {
			if (!$j(this).data("minit")) {
				$j(this).data("minit", true)
					.closest("td")
					.focusout(function (e) {
						solveField($j(this).find(":input"));
					})

			}
		});
		$j(".delRow", $wf).live("click", function (e) {
			frm.delSubRow(this);
		});
	}

	function buildRels(rels) {
		if (rels && amnt(rels) > 0) {
			var viewopts = [
				['table-row', false],
				['none', true]
			];
			refrels = rels;
			for (var ir in rels) {
				if (rels.hasOwnProperty(ir)) {
					var tr = rels[ir];
					$wf.delegate("" + ir, "mouseenter", {s: tr, o: ir}, function (se) {
						if (!$j(this).data("reinit")) {
							$j(this).data("reinit", true);
							if (this.type.toLowerCase() === "radio") {
								var $radpar = $j(this).parent(), pardi = se.data.o;
								$radpar.find(":input").data("reinit", true);
								$radpar.click({vd: se.data.s, o: se.data.o}, function (e) {
									var adata = e.data,
										$chkd = $j(this).find(":input:checked")[0],
										fp = findTVal($chkd.value, adata.vd, irun);
									for (var i = 0, l = fp.length; i < l; i++) {
										if (fp[i] && fp[i] != '') {
											if (adata.o.charAt(0) === '#') {
												$j("" + fp[i]).css("display", viewopts[i][0]).find(":input").attr("disabled", viewopts[i][1]);
											} else {
												$j(this).closest("tr").find("" + fp[i]).attr("disabled", viewopts[i][1]).end();
											}
										}
									}
								});
							} else {
								$j(this).change({tset: tr}, function (e) {
									var adata = e.data.tset,
										selectedVal = $j(this).val(), fp;
									if (selectedVal && selectedVal != '-1') {
										fp = findTVal(selectedVal, adata, irun);
										for (var i = 0, l = fp.length; i < l; i++) {
											if (fp[i] && fp[i] != '') {
												if (fp[i].charAt(0) === '#') {
													//if(adata[2] === false){
													$j("" + fp[i]).css("display", viewopts[i][0]).find(":input").attr("disabled", viewopts[i][1]);
													//}else if(adata[2] && adata[2].length > 0){

													//}
												} else {
													$j(this).closest("tr").find("" + fp[i]).attr("disabled", viewopts[i][1]).end();
													/*}else if(i===1){
													 $j(this).closest("tr").find(""+getRest(fp[0],adata)).attr("disabled",true);
													 }*/
												}
											}
										}
									}
								});

							}
						}
					});
					var $tel = $j("" + ir);
					if ($tel.val() && $tel.val() !== '-1') {
						$tel.trigger("mouseover").trigger("change");
					}
				}
			}
		}
		regions.init();
		irun = false;
	}

	function findTVal(val, arr, lrun) {
		var res = [], newfp = [], nay = [];
		for (var i in arr) {
			if (i && arr.hasOwnProperty(i)) {
				var aid = arr[i];
				if (val === aid[1] || (aid[1] === 'any' && val != '-1' && val.length > 0)) {
					if (aid[1] === 'any' && aid[2] && aid[2].length > 0) {
						var $tobj = $j("" + aid[0]), stock = [], inival = $tobj.find("select").val(), uval;
						$tobj.append($j("<div/>", { "class": "loading"}));
						$j.get("/?m=wizard&suppressHeaders=1&a=sysvals&stype=select&mode=getNSet&sval=" + aid[2] + "&parval=" + val, function (msg) {
							if (msg && msg !== 'fail') {
								var nlist = $j.parseJSON(msg);
								if (nlist.hasOwnProperty("data")) {
									nlist = nlist.data;
								}
								stock.push("<option value='-1' disabled>-- Select --</option>");
								if (amnt(nlist) > 0) {
									for (var ni in nlist) {
										stock.push(["<option value='", ni, "'>", nlist[ni], "</option>"].join(""));
									}
								}
							}
							if (lrun === true) {
								uval = inival;
							} else {
								uval = '-1';
							}
							$tobj.find("select").empty().html(stock.join("")).val(uval).end().find(".loading").remove();
						});
					}
					res.push(aid[0]);
				} else {
					nay.push(aid[0]);
				}
			}
		}
		return [res.join(","), nay.join(",")];
	}

	function getRest(seld, arr) {
		var res = [];
		for (var i in arr) {
			if (i && arr.hasOwnProperty(i)) {
				if ((isArray(seld) && seld !== arr[i]) || seld !== arr[i][0]) {
					res.push(arr[i][0]);
				}
			}
		}
		return res.join(", ");
	}

	function addTabRow($t) {
		var $row = $t.find("tbody > tr:first").clone(), rtop = $t.data("maxRow") || $t.find("tbody > tr").length,
			$tbody = $t.find("tbody"),
			todis = [],
			t = cloneThis(calObj);

		t.defaultDate = "";
		t.onClose = function (dates) {
			$j(this).trigger("focusout");
		};

		for (var ip in refrels) {
			if (refrels.hasOwnProperty(ip)) {
				var rpt = refrels[ip];
				for (var x = 0, l = rpt.length; x < l; x++) {
					todis.push(rpt[x][0]);
				}
			}
		}
		var todis1 = todis.join(", ");

		$t.data("maxRow", ++rtop);
		$row
			.html(function (e, code) {
				code = code.replace(/(fld_(\d+_subs|)\[)\d+\]/g, "$1" + rtop + "]");
				code = code.replace(/rowDone/, "");
				code = code.replace(/hereBug/, "");
				return code;
			})
			.find(":input[type='text']").val("").filter(".spCals").attr("id", "").removeClass("hasDatepick")
			.next("img").remove().end()/*.datepick("destroy")*/.datepick(t).datepick("enable").end().end()
			.find("" + todis1).attr("disabled", true).end()
			.appendTo($tbody);
	}

	function dropSubRow(b) {
		var $row = $j(b).closest("tr"),
			$t = $row.closest("tbody"),
			total = $t.find("tr").length;
		if (total === 1) {
			return false;
		} else {
			$row.remove();
		}
		return true;
	}

	return {
		checkForm: function () {
			if (parseData() === true) {
				$j("#wform").submit();
			}
		},
		init: function (ps, isreg) {
			prepareMandats();
			if (ps > 0) {
				parseData();
			}
			self = this;
			registry = isreg;
		},
		brels: function (rels) {
			buildRels(rels);
		},
		addSubRow: function (bloc) {
			var $table = $j(bloc).closest("td").find(".usub");
			addTabRow($table);
		},
		delSubRow: function (b) {
			dropSubRow(b);
		}
	}
}(frm));


var regions;
regions = (function (my) {

	var alevels = {
		1: 'region',
		2: 'municipality',
		3: 'village'
	};

	var initVals = {};

	function collectLevels() {
		for (var l in alevels) {
			if (alevels.hasOwnProperty(l)) {
				if ($j("." + alevels[l]).length > 0) {
					//we have found one of levels
					//1.check if it has already value not equal to -1
					var $seler = $j("." + alevels[l]);
					var cval = $seler.val();
					initVals[l] = cval;
					var future = parseInt(l) + 1;
					//2.Attach event on change of value
					//Atach only to selectors who have children, others will be without this event
					if (alevels.hasOwnProperty(future.toString()) !== false ) {
						$seler.change({yourLevel: parseInt(l)}, function (e) {
							var mylev = e.data.yourLevel;
							var newVal = $j(this).val();
							if (newVal != initVals[mylev]) {
								resetChild((mylev + 1), newVal);
							}
						});
					}
				}
			}
		}
	}

	function buildSelectionList(obj, dset) {
		if (dset && dset != 'fail' && dset.length > 0) {
			var $ptv = obj.parent();
			var $fly = obj.detach();
			var dvs = $j.parseJSON(dset);
			for (var i in dvs) {
				if (dvs.hasOwnProperty(i)) {
					$j("<option value="+(i)+">"+(dvs[i])+"</option>").appendTo($fly);
				}
			}
			$fly.appendTo($ptv);
		}
	}

	function cleanChild(start){
		var miss = false, first = false, tgt;
		while(miss === false){
			if(alevels.hasOwnProperty(start)){
				tgt = $j("." + alevels[start]);
				tgt.find("option:gt(0)")
					.attr("disabled", true)
					.remove().end();
				++start;
				if(first === false){
					first = tgt;
				}
			}else{
				miss = true;
			}
		}
		return first;
	}

	function resetChild(lev, nval) {
		//0.clean made choices for all underlying items
		var $tgt = cleanChild(lev);
		$tgt.closest("td").append("<img src='/images/zload.gif'>");

		$j.when($j.get("/?m=wizard&a=form_use", {suppressHeaders: 1, vcmode: "getlevel", vclevel: lev, vcval: nval}))
			.done(function (msg) {
				buildSelectionList($tgt, msg);
				$tgt.val("-1").attr("disabled", false).closest("td").find("img").remove();
			});
	}

	function getUpperLevel(clevel) {
		var result = false;
		if (clevel > 1) {
			var perTag = alevels[(clevel - 1)];
			result = $j("." + perTag);
		}
		return result;
	}

	return {
		init: function () {
			collectLevels();
		}
	};
})(regions);

