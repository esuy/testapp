<?php

class Wizard {

	private $mandatoryCode = '<div class="fcontrol"></div>';

	private $rowDataPrefix = '<li>';
	private $rowDataAppix = '</li>';
	private $rowDataComma = '';

	private $mode;
	public $formName;
	public $fields;
	private $digest;
	public $form_prefix = "wform_";
	public $formSubs;
	private $tableName;
	private $tableID;

	private $rowRef = array();
	private $icount = 0;
	private $jsActions = array();
	private $client_id;
	private $entryId;
	private $radioID = 0;
	private $textRowId = 0;
	private $valid;
	private $vrow = 0;

	private $rels = array();
	public $registry;

	private $clientFieldUse = false;

	private $adminLevels = array(
		'Region'       => 1,
		'Municipality' => 2,
		'Village'      => 3
	);

	private $texts = array('numeric' => array(), 'positive' => array(), 'email' => array(), 'range' => array());

	/**
	 * Class construction for view/edit constructed form
	 * @param string $mode
	 * @param int $client_id
	 * @param int $rid
	 */
	function __construct($mode = 'edit', $client_id = 0, $rid = 0) {
		$this->mode = $mode;
		$this->client_id = $client_id;
		$this->entryId = (int)$rid;
		if($mode === 'edit' || $mode === 'add'){
			global $moduleScripts;
			$moduleScripts [] = '/modules/wizard/form_usagew.js';
		}
	}

	function loadFormInfo($id) {
		global $baseDir;
		$sql = 'select title,fields,digest,valid,subs,registry from form_master where id="' . $id . '" limit 1';
		$res = mysql_query($sql);
		if ($res) {
			$fdata = mysql_fetch_object($res);
			$this->formName = $fdata->title;
			$this->digest = explode(',', $fdata->digest);
			$this->fields = unserialize(stripslashes(gzuncompress($fdata->fields)));
			$this->valid = $fdata->valid;
			$this->formSubs = $fdata->subs;
			$this->registry = (int)$fdata->registry;
		}
		$this->tableName = $this->form_prefix . $id;
		$this->tableID = $id;

		$titles = array();
		/*obtain info on fields*/
		if (file_exists($baseDir . '/modules/outputs/titles/' . $this->tableName . '.title.php')) {
			require_once($baseDir . '/modules/outputs/titles/' . $this->tableName . '.title.php');
		}

		$this->clientFieldUse = $titles[$this->tableName]['client'];

	}

	function getDefaultFields($client_id = 0, $dvals = array()) {
		if ($this->mode !== 'view') {
			/*$code = $this->rowDataPrefix.' Visit Date '.$this->rowDataComma
			//.drawDateCalendar('entry_date',printDate($dvals['entry_date']),false,'class="mandat"',false,10,false,'$j(this).trigger("focusout");')
			.'<input type="hidden" name="client_id" value="'.$this->client_id.'">'
			.'<input type="hidden" name="id" value="'.$this->entryId.'">'
			.$this->mandatoryCode
			.$this->rowDataAppix;
}else*/
			$code = '';
		}
		return $code;
	}
	

	function tableWrap() {
		$this->rowDataPrefix = '<tr><td align="left">';
		$this->rowDataComma = '</td><td ' . ($this->mode === 'view' ? " class='hilite' " : '') . ' align="left">';
		$this->rowDataAppix = '</td></tr>';
	}

	function outputField($fld_id, $fld, $dvalue, $otm = false, $tabout = false, $prevCols = 1) {

		$blist = '';
		if (isset($fld['otm']) && count($fld['subs']) > 0) {
			$blist = str_replace('<td ', '<td colspan="2"', $this->rowDataPrefix) .
				'<strong>' . $fld['name'] . '</strong><br>' .
				'<hr width="500" size="1" align="left">' .
				$this->rowDataAppix;
			return $blist;
		} elseif ($otm === false) {
			//$blist=$this->rowDataPrefix.++$this->icount.'.'.$fld['name'].$this->rowDataComma;
			$blist = $this->rowDataPrefix . $fld['vname'] . $fld['name'] . ($tabout === false ? $this->rowDataComma : '</td>');
		} else {
			$blist = '<td>';
		}
		$this->rowRef[$fld['vid']] = array($fld_id, $dvalue, $fld['type'], $this->vrow);

		if ($fld['type'] === 'note') {
			$blist = str_replace('<td ', '<td colspan="2"', $this->rowDataPrefix) .
				'<p>' . $fld['name'] . '</p><br>' .
				$this->rowDataAppix;
			return $blist;
		}
		$fldClass = 'fcl_' . $this->vrow . ' ' . strtolower($fld['sysv']);
		$alist = $this->getValues($fld['type'], $fld['sysv'], false, false, $fld['other']);
		unset($alist['rels']);
		$code = '';
		$ftype = $fld['type'];
		if ($this->mode === 'edit' || $this->mode === 'add') {
			if (preg_match('/^select/', $ftype)) {
				$code = $this->buildSelectList($alist, $fld, $fld_id, $dvalue, $fldClass);
			} else {
				if ($ftype === 'time' || $ftype === 'datetime') {
					$pftype = 'times';
				} else {
					$pftype = $ftype;
				}
				$obligate = $fld['mand'] === true ? 'mandat' : '';
				switch ($pftype) {
					case 'date':
						//$name,$value,$hidden=false,$tags='',$yearCase = false,$length=20,$hvalue=false,$extraEvent=''
						$code = drawDateCalendar('fld_' . $fld_id, printDate($dvalue), false, 'class=" ' . $fldClass . ' ' . $obligate . '"', false, 10, false,
							($obligate != '' ? '$j(this).trigger("focusout");' : ''), $fld['range']['start'], $fld['range']['end']);
						break;
					case 'entry_date':
						//$name,$value,$hidden=false,$tags='',$yearCase = false,$length=20,$hvalue=false,$extraEvent=''
						$code = drawDateCalendar('entry_date', printDate($dvalue), false, 'class=" ' . $fldClass . ' ' . $obligate . '"', false, 10, false,
							($obligate != '' ? '$j(this).trigger("focusout");' : ''), $fld['range']['start'], $fld['range']['end'])
							. '<input type="hidden" name="client_id" value="' . $this->client_id . '">'
							. '<input type="hidden" name="id" value="' . $this->entryId . '">';
						break;
					case 'times':
						$code = drawTimePicker('fld_' . $fld_id, $dvalue, ($ftype === 'datetime' ? true : false), $obligate);
						break;
					case 'radio':
						if ($tabout === false) {
							$code = arraySelectRadio($alist, 'fld_' . $fld_id, 'class="' . $fldClass . ' ' . $obligate . '" ', $dvalue);
						} else {
							$code = arraySelectRadioMultiCol($alist, 'fld_' . $fld_id, 'class="' . $fldClass . ' ' . $obligate . '" ', $dvalue, false, 1, false);
						}
						if ($obligate !== '')
							$blist = str_replace('</td><td', '</td><td class="radioMandat" ', $blist);
						++$this->radioID;
						break;
					case 'checkbox':
						if ($tabout === false) {
							$code = arraySelectCheckbox($alist, 'fld_' . $fld_id . '[]', 'class="' . $fldClass . ' ' . $obligate . '" ', $dvalue);
						} else {
							$code = arraySelectCheckboxMultiCol($alist, 'fld_' . $fld_id . '[]', 'class="' . $fldClass . ' ' . $obligate . '" ', $dvalue, false, 1, false);
						}
						break;
					case 'bigText':
						$code = '<textarea name="fld_' . $fld_id . '" cols=40 rows=5 class="text ' . $fldClass . ' ' . $obligate . '">' . nl2br(stripslashes($dvalue)) . '</textarea>';
						break;
					default:
						if ($pftype != 'plain') {
							if (is_array($fld['range'])) {
								$obligate .= "numeric inrange\" data-rng='" . ($fld['range']['start'] . '|' . $fld['range']['end']) . "' ";
							} else {
								$obligate .= ' strictz numeric';
							}
						}
						$code = '<input class="text ' . $obligate . ' ' . $fldClass . ' " name="fld_' . $fld_id . '" id="trow_' . $this->textRowId++ . '" size="30" value="' . $dvalue . '" >';
						break;
				}
			}
		} elseif ($this->mode === 'view') {
			$code = $this->printFieldValue($fld, $dvalue, $tabout);
		} elseif ($this->mode === 'print') {
			$ft = $fld['type'];
			$code = '<tr><td>' . $fld['vname'] . '&nbsp;</td><td>' . $fld['name'] . '&nbsp;&nbsp;';
			if (in_array($ft, array('SysCenters', 'SysStaff', 'SysClients', 'plain', 'numeric'))) {
				$code .= '...............................................';
			} else {
				if ($ft === 'date') {
					$code .= '. . . /. . . /. . . . . .';
				} elseif ($ft === 'bigText') {
					$code .= '<br><br><br>';
				} else {
					if ($tabout === false) {
						$arr = $this->getValues($ft, $fld['sysv'], false, true);
						$code .= join(" - ", $arr);
					} else {
						$pres = $this->getValues($ft, $fld['sysv'], false, true);
						$code = '<tr><td>' . $fld['vname'] . $fld['name'] . '</td><td>' . showValuesMultiCol(array(), count($pres) - 1, array_keys($pres));
						$code = preg_replace("/<td>$/", "", $code);
					}
				}
			}
			return $code . '</td></tr>';
		}

		if ($this->mode !== 'view') {
			$code .= ($fld['mand'] === true ? $this->mandatoryCode : '');

			if (is_array($fld['child']) && count($fld['child']) > 0) {
				$tableParentRow = $this->rowRef[$fld['child']['parent']];
				if ($this->mode === 'add' || ($this->mode === 'edit' && $tableParentRow[1] != $fld['child']['trigger'])) {
					if ($otm === false) {
						$this->jsActions[] = 'var tv = $j("#row_' . $fld_id . '").val(); if(tv === undefined ||  tv == "-1" || tv === ""){$j("#row_' . $fld_id . '").hide();}';

						$this->appendTag("#row_" . $tableParentRow[0] . ' :input', '#row_' . $fld_id, $fld['child']['trigger'], ($fld['chain'] === true ? $fld['sysv'] : false));
					} else { //if($this->registry === 1){
						$this->jsActions[] = '$j(".fcl_' . ($this->vrow) . '").attr("disabled",true);';

						$this->appendTag(".fcl_" . $tableParentRow[3], '.' . $fldClass, $fld['child']['trigger'], ($fld['chain'] === true ? $fld['sysv'] : false));
					}
				}
			}
		}
		++$this->vrow;
		if ($otm === false) {
			$blist .= $code . $this->rowDataAppix;
			$blist = str_replace("<tr>", "<tr id='row_" . $fld_id . "'>", $blist);
		} else {
			$blist .= $code . '</td>';
		}
		return $blist;
	}

	function appendTag($tRow, $selector, $tval, $dig) {
		$found = false;
		if (!isset($this->rels[$tRow])) {
			$this->rels[$tRow] = array();
		}
		foreach ($this->rels[$tRow] as &$tpart) {
			if ($tpart[1] == $tval) {
				$tpart[0] .= ', ' . $selector;
				$found = true;
			}
		}
		if ($found === false) {
			$this->rels[$tRow][] = array($selector, $tval, $dig);
		}
	}

	function preIndex() {
		return $this->vrow;
	}

	function postIndex($i) {
		$this->vrow = $i;
	}

	function formJSsupport() {
		if ($this->mode !== 'view') {
			if ($this->radioID > 0) {
				$this->jsActions[] = '$j(".radioMandat").find("input").click(function(e){$j(this).parent().find(".fcontrol").addClass("rowDone");})';
			}
			foreach ($this->texts as $type => $fields) {
				//if(count($fields) > 0){
				//$pre='$j("'.join(',',$fields).'").';
				$pre = '$j(".' . $type . '").live("mouseover",function(){$j(this).';
				switch ($type) {
					case 'numeric':
						$this->jsActions[] = $pre . 'liveStrict("format({autofix:true})");});';
						break;
					/*case 'range':
					$this->jsActions[]=$pre.'liveStrict("numeric()");});';
					break;
					case 'email':
					$this->jsActions[]=$pre.'liveStrict("format({type:\"email\"},function(){alert(\"Wrong Email format!\")})")});';
					break;
					case 'positive':
					$this->jsActions[]=$pre.'liveStrict("format({precision: 0,allow_negative:false,autofix:true})");});';
					break;*/
				}

				//}
			}
			$this->jsActions[] = 'frm.brels(' . json_encode($this->rels) . ');';
		}
		return join("\n", $this->jsActions);
	}

	function buildSelectList($arr, $fld, $fld_id, $value, $xtraClass = '') {
		if ($fld['ftype'] === 'select-multi') {
			$vals = explode(',', $value);
			$addName = array('[]', 'multiple="multiple" size="3"');
		} else {
			$vals = array($value);
			$addName = array('', '');
		}
		if ($fld['mand'] === true) {
			$addClass = ' mandat ';
		} else {
			$addClass = '';
		}

		$psel = '<select name="fld_' . $fld_id . $addName[0] . '" class="text ' . $addClass . ' ' . $xtraClass . '" ' . ($addName[1]) . '>';
		foreach ($arr as $id => $ci) {
			$psel .= "<option value='" . $id . "' " . (in_array($id, $vals) ? 'selected="selected" ' : '') . ">" . $ci . "</option>\n";
		}
		$psel .= '</select>';
		return $psel;
	}

	public function getValues($type, $psv = false, $pvalue = false, $nosubz = false, $withOther = false, $parentValue = false) {
		$result = false;
		if (in_array($psv, array_keys($this->adminLevels))) {
			$preResult = loadAdminRegions($this->adminLevels[$psv], $parentValue);
			$result = arrayMerge(array(-1 => ' - Select ' . $psv . ' -'), $preResult);
		} else {
			switch ($psv) {
				case 'SysClients':
					$q = new DBQuery();
					$q->addTable('clients');
					$q->addQuery('client_id as id, concat(client_first_name," ",client_last_name) as client_name');
					$result = arrayMerge(array(-1 => '- Select Client -'), $q->loadHashList());
					break;

				case 'SysCenters':
					$q = new DBQuery();
					$q->addTable('clinics', 'c');
					$q->addQuery('c.clinic_id as id, c.clinic_name as name');
					$q->addOrder('c.clinic_name');
					$result = arrayMerge(array(-1 => '- Select Center -'), $q->loadHashList());
					break;

				case 'SysStaff':
					$q = new DBQuery;
					$q->addTable('contacts', 'con');
					$q->leftJoin('users', 'u', 'u.user_contact = con.contact_id');
					$q->addQuery('con.contact_id as id');
					$q->addQuery('CONCAT_WS(" ",contact_first_name,contact_last_name) as name');
					$q->addOrder('contact_last_name');
					$q->addWhere('contact_active="1"');
					if ($parentValue !== false && (int)$parentValue > 0) {
						$q->addTable('staff_position', 'sf');
						$q->addWhere('position_id="' . $parentValue . '"');
						$q->addWhere("sf.contact_id = con.contact_id");
					}
					$result = arrayMerge(array(-1 => '- Select Person -'), $q->loadHashList());

					break;

				case 'SysLocations':
					$q = new DBQuery();
					$q->addTable("clinic_location");
					if ($parentValue !== false && (int)$parentValue > 0) {
						$q->addWhere('clinic_location_clinic_id = "' . (int)$parentValue . '"');
					}
					$q->addQuery("clinic_location_id, clinic_location");
					$result = arrayMerge(array(-1 => '- Select Location -'), $q->loadHashList());

					break;

				case 'SysPositions':
					$q = new DBQuery();
					$q->addTable('positions', 'c');
					$q->addQuery('id, title');
					$q->addOrder('title');
					$result = arrayMerge(array(-1 => '- Select Position -'), $q->loadHashList());
					break;

				default:
					if (in_array($type, array('select', 'radio', 'checkbox')) && $psv != '') {
						$result = dPgetSysValSet($psv);
						if ($type === 'select') {
							$result = arrayMerge(array(-1 => '-- Select --'), $result);
						}

					}
					break;
			}
		}
		if ($pvalue !== false) {
			$str = array();
			if (is_array($pvalue) && count($pvalue) > 0) {
				foreach ($pvalue as $pv) {
					$str[] = $result[$pv];
				}
			} else {
				$str[] = $result[$pvalue];
			}
			$result = join(",", $str);
		}
		if ($nosubz === true) {
			unset($result[-1]);
		}
		if ($withOther === true) {
			$result['other'] = "Other";
		}
		return $result;
	}

	function inFieldValueParse($key, $tf, $value) {
		if ($key === 'entry_date' || $tf['type'] === 'date') {
			$puretime = (int)preg_replace("/\D/", "", $value);
			if ($puretime > 0) {
				$valArr = explode("/", $value);
				$value = join("-", array_reverse($valArr));
			} else {
				$value = '0000-00-00';
			}
			//$value = storeDate($value);
		} elseif ($tf['type'] === 'select-multi' || $tf['type'] === 'checkbox') {
			if (is_array($value)) {
				$value = join(',', $value);
			}
		}
		return mysql_real_escape_string($value);
	}

	function saveFormData($id = 0) {
		$q = new DBQuery();
		$q->addTable($this->tableName);
		$action = '';
		$pclient_id = (int)$_POST['client_id'];
		if ($id > 0) {
			$q->addWhere('id="' . (int)$id . '"');
			$action = 'Update';
		} else {
			if ($this->clientFieldUse !== false) {
				$q->addInsert('client_id', $pclient_id);
			}
			$action = 'Insert';
		}
		$afterSave = array();
		$lsubs = explode(',', $this->formSubs);
		foreach ($_POST as $key => $value) {
			if (preg_match("/_subs$/", $key) || preg_match('/^fld_$/', $key)) {
				$is_otm = 1;
			} else {
				$is_otm = 0;
			}
			if ((strstr($key, 'fld_') && $is_otm === 0) || $key === 'entry_date') {
				$tf = $this->findFieldName($key);
				if ($key === 'entry_date') {
					$value = $this->inFieldValueParse($key, $key, $value);
					$q->{"add" . $action}($tf['dbfld'], $value);
				}

				$value = $this->inFieldValueParse($key, $tf, $value);
				$q->{"add" . $action}($key, $value);

			} elseif ($is_otm === 1) {
				$onetype = array();
				$values = array();
				$tf = $this->findFieldName($key, 1);
				if ($this->registry === 0) {
					if ($id > 0) {
						$sql = 'delete from ' . $lsubs[$tf['dbsub']] . ' where wf_id="' . $id . '"';
						$rdel = mysql_query($sql);
					}

					foreach ($value as $rid => $mrow) {
						foreach ($mrow as $field => $svalue) {
							if (!array_key_exists($field, $onetype)) {
								$stf = $this->findFieldName($field, $tf);
								$onetype[$field] = $stf;
							} else {
								$stf = $onetype[$field];
							}
							$values[$rid][] = $this->inFieldValueParse($field, $stf, $svalue);
						}
						$values[$rid][] = '#@WFID@#';
						$values[$rid][] = $pclient_id;
						$values[$rid] = '("' . join('","', $values[$rid]) . '")';
					}
					$onetype['wf_id'] = '';
					$onetype['client_id'] = '';

					$sql = 'insert into ' . $lsubs[$tf['dbsub']] . '(' . join(",", array_keys($onetype)) . ') VALUES ' .
						join(',', $values);
					unset($values);
					$afterSave[] = $sql;
				} elseif ($this->registry === 1) {
					foreach ($value as $rid => $mrow) {
						foreach ($mrow as $field => $svalue) {
							if (!array_key_exists($field, $onetype)) {
								$stf = $this->findFieldName($field, $tf);
								$onetype[$field] = $stf;
							} else {
								$stf = $onetype[$field];
							}
							if (!is_array($values[$rid])) {
								$value[$rid] = array();
							}
							$values[$rid][$field] = $this->inFieldValueParse($field, $stf, $svalue);
						}
					}
				}
			}
		}
		if (is_array($values) && count($values) > 0) {
			foreach ($values as $kv) {
				$q2 = clone $q;
				foreach ($kv as $key => $cval) {
					$q2->addInsert($key, $cval);
				}
				$sql = $q2->prepare();
				$res = mysql_query($sql);
			}
		} else {
			$sql = $q->prepare();
			$res = mysql_query($sql);
		}
		if (count($afterSave) > 0) {
			if ($id === 0) {
				$id = mysql_insert_id();
			}
			foreach ($afterSave as &$sob) {
				$sql = str_replace('#@WFID@#', $id, $sob);
				$ires = mysql_query($sql);
			}
		}
		return $res;
	}

	private function findFieldName($dfld, $forceSub = false) {
		if ($forceSub !== false && $forceSub !== 1) {
			$useit =& $forceSub['subs'];
		} else {
			$useit = $this->fields;
		}

		$searchSubject = true;

		if ($dfld === 'entry_date') {
			$searchSubject = false;
		}
		foreach ($useit as &$sfl) {
			if (!isset($sfl['otm']) || $forceSub === 1) {
				if ($sfl['dbfld'] === $dfld) {
					return $sfl;
				}
			} elseif (isset($sfl['subs'])) {
				if (isset($sfl['otm']) && preg_match("/_subs$/", $dfld)) {
					if ($sfl['dbfld'] === $sfl['dbfld']) {
						return $sfl;
					}
				}
				foreach ($sfl['subs'] as &$subfl) {
					if ($searchSubject === false) {
						if ($subfl['type'] === 'entry_date') {
							return $subfl;
						}
					} else {
						if ($subfl['dbfld'] === $dfld) {
							return $subfl;
						}
					}

				}
			}
		}
		return false;
	}


	function printFieldValue($fld, $val, $tabout = false) {
		$res = '';
		if ($fld['type'] === 'date' || $fld['type'] === 'entry_date') {
			$res = printDate($val);
		} elseif (in_array($fld['type'], array('select', 'radio', 'checkbox', 'centers', 'clients', 'staff'))) {
			if ($fld['smult'] === true || $fld['type'] === 'checkbox') {
				$val = explode(',', $val);
			}
			if ($val >= 0) {
				if ($tabout === false) {
					$res = $this->getValues($fld['type'], $fld['sysv'], $val);
				} else {
					$pres = $this->getValues($fld['type'], $fld['sysv']);
					unset($pres['rels']);
					$res = showValuesMultiCol($val, count($pres), array_keys($pres));
				}
			} else {
				$res = '&nbsp;';
			}
		} else {
			$res = nl2br(stripslashes($val));
		}
		return $res;
	}

	function drawDigest() {
		$code = '';
		if (!$this->digest) {
			$this->digest = array();
		}
		$dpicks = join(',', array_merge(array('id', 'entry_date'), $this->digest));
		$dpicks = preg_replace("/\,$/", "", $dpicks);
		$sql = 'select ' . $dpicks . ' from ' . $this->tableName . ' where client_id="' . $this->client_id . '" order by entry_date ASC';
		$res = mysql_query($sql);
		$first = false;
		if ($res && mysql_num_rows($res) > 0) {
			$nrows = mysql_num_rows($res);
			$code = '<table width="100%" cellspacing="1" cellpadding="2" border="0" class="tbl">
				<thead>
					<tr>
						<th>Visit Date</th>';
			$localflds = array();
			foreach ($this->digest as $dfld) {
				if ($dfld != '') {
					$tname = $this->findFieldName($dfld);
					$code .= '<th>' . $tname['name'] . '</th>';
					$localflds[$dfld] = $tname;
				}
			}
			$code .= '</tr></thead><tbody>';
			while ($drow = my_fetch_assoc($res)) {
				$code .= '<tr>
					<td><a href="?m=clients&a=view&client_id=' . $this->client_id . '&tab=' . $_GET['tab'] . '&fid=' . $this->tableID . '&todo=view&itemid=' . $drow['id'] . '">' . printDate($drow['entry_date']) . '</a></td>';
				foreach ($this->digest as $dfld) {
					if ($dfld != '') {
						$code .= '<td>' . $this->printFieldValue($localflds[$dfld], $drow[$dfld]) . '</td>';
					}
				}
				$code .= '</tr>';
				if ($first === false) {
					$first = $drow['id'];
				}
			}
			$code .= '</tbody></table>';
		} else {
			$code = 'No data available';
			$nrows = 0;
		}
		if ($this->valid == 1) {
			$code .= '<tr><td colspan="4" align="right" valign="top" style="background-color:#ffffff">';
			$code .= '<input type="button" class="button" value="add new visit" onClick="window.location=\'./index.php?m=wizard&a=form_use&fid=' . $this->tableID . '&todo=addedit&client_id=' . $this->client_id . '\'">';
			$code .= '</td></tr>';
		}

		return array($code, $nrows, $first);
	}

}

function importForm() {
	global $wres, $newd;
	$fpath = $_FILES['frfile']['tmp_name'];
	$res = 'fail';
	if (is_uploaded_file($fpath)) {
		$newQuery = file_get_contents($fpath);
		if (strlen($newQuery) > 0) {
			$newin = @unserialize(@gzuncompress(@stripslashes(@base64_decode($newQuery))));
			$newsets = $newin['sets'];
			$newForm = $newin['form'];
		}
	}
	/*elseif(isset($_SESSION['form_delay_store']) && $_SESSION['form_delay_store'] != ''){
		$tform = $_SESSION['form_delay_store'];
		$newForm = unserialize(tmpFileRead($tform),true);
		unset($_SESSION['form_delay_store']);
		}*/
	if (isset($newsets) && count($newsets) > 0) {
		$sres = importSets($newsets);
		if ($sres['result'] === 'partial') {
			if (count($sres['multi']) > 0) {
				$_SESSION['form_delay_store'] = tmpFileStore(serialize($newForm));
				//$_SESSION['sets_details'] = serialize($sres);
				$sres['form_case'] = true;
				$res = json_encode(array("withsets" => true, "sinfo" => $sres));
			}
		} elseif ($sres['result'] === true) {
			// Update sysvals' ID so they have to be valid in new place
			$fds = & $newForm['fileds'];
			if (count($fds) > 0 && count($sres['multi']) > 0) {
				foreach ($fds as &$fi) {
					if (is_numeric($fi['sysv']) && array_key_exists($fi['sysv'], $sres['multi'])) {
						$fi['sysv'] = $sres['multi'][$fi['sysv']];
					}
				}
			}
			$res = formInject($newForm);
		}
		if ($sres['result'] == 'ok' || !isset($sres)) {
			$res = formInject($newForm);
		}
	}
	return $res;
}

function wrapT($a) {
	return '"' . $a . '"';
}

function formInject($newForm) {
	global $wres, $newd;
	if (is_array($newForm) && count($newForm) > 2) {
		$_POST['formName'] = $newForm['title'];
		$_POST['formsum'] = json_encode(unserialize(gzuncompress($newForm['fields'])));
		$_POST['regForm'] = $newForm['registry'];
		$_POST['fakereturn'] = true;
		require_once('saveform.php');
		if ($wres) {
			$pdata = $newForm['rowData'];
			$subPrefix = 'wf_' . $newd;
			$plain = 0;
			$sqlInsert = 'insert into wform_' . $newd;
			$once = false;
			if (count($pdata) > 0) {
				$tvals = array();
				foreach ($pdata as $pid => &$prow) {
					if (is_numeric($pid)) {
						if ($once === false) {
							$akeys = array_keys($prow);
							$once = true;
							$tinsert = $sqlInsert . ' (' . join(",", $akeys) . ') VALUES ';
						}
						$tvals[] = '(' . join(",", array_map("wrapT", array_values($prow))) . ')';

						++$plain;
					} else {
						$subinsert = 'insert into wf_' . $newd . '_' . $pid . ' ';
						$sonce = false;
						$subvals = array();
						foreach ($prow as $sid => &$sprow) {
							if ($sonce === false) {
								$subkeys = array_keys($sprow);
								$subinsert .= '(' . join(",", $subkeys) . ') VALUES ';
							}
							$subvals[] = '(' . join(",", array_map("wrapT", array_values($sprow))) . ')';
						}
						if (count($subvals) > 0) {
							$subinsert .= join(",", $subvals);
							$sires = mysql_query($subinsert);
						}
					}
				}
				if (count($tvals) > 0) {
					$sql = $tinsert . join(",", $tvals);
					$din = mysql_query($sql);
				}
			}
			$res = json_encode(
				array(
					0 => array(
						'title'        => $newForm['title'],
						'registry'     => $newForm['registry'],
						'valid'        => $newForm['valid'],
						'valid_change' => '&nbsp;',
						'id'           => $newd,
						'rows'         => $plain
					)
				)
			);
		}
		return $res;
	}
}

function importSets($upset) {
	global $dpConfig, $baseDir;
	$resume = array("result"   => false, "multi" => array(), 'passed' => 0, 'form_case' => false, 'done' => array(),
	                'children' => array()
	);
	$delay = array();
	$happen = 0;
	$children = array();
	$stats = array();
	if (count($upset) > 0) {
		foreach ($upset as $upid => $ndset) {
			$sql = 'select title,touch,id from svsets where title="' . $ndset['title'] . '" limit 1';
			$res = mysql_query($sql);
			if ($ndset['id'] != $ndset['parent']) {
				$resume['children'][$ndset['id']] = $ndset['parent'];
			}
			if (!$res || mysql_num_rows($res) == 0) {
				$sql = 'insert into svsets (title,touch,vtype,level,status,options)
					values("' . $ndset['title'] . '",
					"' . $ndset['touch'] . '",
					"' . $ndset['vtype'] . '",
					"' . $ndset['level'] . '",
					"' . $ndset['status'] . '",
					"' . $ndset['options'] . '"
					)';
				$ires = mysql_query($sql);
				if ($ires) {
					$parid = mysql_insert_id();
					if ($ndset['id'] == $ndset['parent']) {
						$sql = 'update svsets set parent="' . $parid . '" where id="' . $parid . '" limit 1';
						$nres = mysql_query($sql);
					}
					/*else{
										// newID => oldParentID
										$children[$parid] = $ndset['parent'];
										}*/
					$resume['done'][$ndset[id]] = $parid;
					++$happen;
				}
			} else {
				//set with such name is found, now will have to compare
				$prev = my_fetch_assoc($res);
				$resume['multi'][] = array(
					"title"     => $ndset['title'],
					'in_touch'  => $ndset['touch'],
					'now_touch' => $prev['touch'],
					'in_id'     => $ndset['id'],
					'now_id'    => $prev['id']
				);
				$delay[$ndset['id']] = $ndset;
			}
		}
	}
	$resume['happen'] = $happen;
	if (count($delay) > 0) {
		$fpath = tmpFileStore(serialize($delay));
		$_SESSION['set_delay_store'] = $fpath;
		$resume['result'] = 'partial';
	}
	if ($happen > 0 && count($delay) === 0) {
		$resume['result'] = true;
		fixParent($resume);
	}
	return $resume;
}

function fixParent($clist) {
	foreach ($clist['children'] as $old_id => $oldParentId) {
		if (array_key_exists($oldParentId, $clist['done'])) {
			$sql = 'update svsets set parent="' . $clist['done'][$oldParentId] . '" where id="' . $clist['done'][$old_id] . '"';
			$ires = mysql_query($sql);
		}
	}
}

function swapKV($a) {
	$b = array();
	if (count($a) > 0) {
		foreach ($a as $k => $v) {
			$b[$v] = $k;
		}
	}
	return $b;
}

function importDelayed($solution, $conserv) {
	if (!is_array($solution)) {
		$solution = array();
	}

	if (!is_array($conserv)) {
		$conserv = array();
	}
	$children = array();
	$complete = array();
	if ($_POST['relvs'] != '') {
		$children = json_decode(stripslashes($_POST['relvs']), true);
		if (!is_array($children)) {
			$children = array();
		}
	}
	$res = 'ok';
	$ncom = array();
	if ($_SESSION['set_delay_store'] != '' && file_exists($_SESSION['set_delay_store']) && (count($solution) > 0 || count($conserv) > 0)) {
		$desd = unserialize(tmpFileRead($_SESSION['set_delay_store'], true));
		$cnt = 0;
		$setsDone = json_decode(stripslashes($_POST['wdone']), true);
		// current -> incoming IDs
		foreach ($solution as $spart => $svalue) {
			$set = $desd[$svalue];

			$sql = 'update svsets set vtype="' . $set['vtype'] . '",
						level="' . $set['level'] . '",
						options="' . $set['options'] . '",
						touch="' . $set['touch'] . '",
						status="' . $set['status'] . '"
					where id="' . (int)$spart . '"';
			$res = mysql_query($sql);
			if ($res)
				++$cnt;
			if ($set['id'] != $set['parent']) {
				$children[$svalue] = $spart;
			}
			$setsDone[$svalue] = $spart;
		}
		$complete = swapKV($conserv);
		unset($_SESSION['set_delay_store']);
		if (count($setsDone) > 0) {
			$complete = $complete + $setsDone;
		}
		fixParent(array("done" => $complete, "children" => $children));

		if ($cnt === count($solution)) {
			if ((int)$_GET['isform'] === 1) {
				if (isset($_SESSION['form_delay_store']) && $_SESSION['form_delay_store'] != '') {
					$tform = $_SESSION['form_delay_store'];
					$newForm = unserialize(tmpFileRead($tform, true));
					unset($_SESSION['form_delay_store']);
					//Update fields in form with new Ids
					$fds = unserialize(gzuncompress($newForm['fields']));
					$ncom = swapKV($complete);
					if (count($fds) > 0 && count($complete) > 0) {
						foreach ($fds as &$fi) {
							if (is_numeric($fi['sysv']) && array_key_exists($fi['sysv'], $complete)) {
								$fi['sysv'] = $complete[$fi['sysv']];
							}
						}
						$newForm['fields'] = gzcompress(serialize($fds));
					}
					$fres = formInject($newForm);
					if ($res) {
						$res .= '#@#' . $fres;
					}
				}
			}

		} else {
			$res = "fail";
		}
	}
	return $res;
}
