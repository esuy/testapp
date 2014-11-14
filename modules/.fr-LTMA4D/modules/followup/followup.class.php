<?php /* ADMISSION $Id: followup.class.php,v 1.9 2010/10/05 06:30:43 istesin Exp $ */
/**
 * @package dotProject
 * @subpackage modules
 * @version $Revision: 1.9 $
 */
global $issues;
/*$issues=array(  1 => array('title'=>'Child','kids'=>'FollowChildIssues'),
					2 => array('title'=>'Mother/Father','kids'=>'FollowParentIssues'),
					3 => array('title'=>'Caregiver','kids'=>'FollowParentIssues'),
					4 => 'Disclosure',
					5 => 'Other close adults learn child HIV status',
					6 => 'Response of Disclosure',
					7 => 'State of Disclosure',
					8 => 'Secondary Caregiver knowledge',
					9 => 'Primary Caregiver Tested',
					10 => 'Mother/Father Status',
					11 => 'M/F/C Treatment',
					12 => 'Stigmatization',
					13 => 'Secondary Caregiver Identification'
				);
*/
$issues = dPgetSysVal('FollowIssues');
$treatIssues = true;
require_once ($AppUI->getSystemClass ( 'dp' ));

/**
 * Admission Record Class
 *
 */
class CFollowUp extends CDpObject {

	var $followup_id = NULL;
	var $followup_adm_no = NULL;
	var $followup_client_type = NULL;
	var $followup_visit_type = NULL;
	var $followup_issues = NULL;
	var $followup_issues_notes = NULL;
	var $followup_service = NULL;
	var $followup_service_notes = NULL;
	var $followup_date = NULL;
	var $followup_center_id = NULL;
	var $followup_officer_id = NULL;
	var $followup_object = NULL;
	var $followup_client_id = NULL;

	function CFollowUp() {
		$this->CDpObject ( 'followup_info', 'followup_id' );
	}

	function store() {
		global $AppUI;

		if (($this->followup_id) && ($this->followup_id > 0)) {

			addHistory ( 'followupinfo', $this->followup_id, 'update', $this->followup_id );
			$this->_action = 'updated';

			$ret = db_updateObject ( 'followup_info', $this, 'followup_id', true );

		} else {

			$this->_action = 'added';
			$ret = db_insertObject ( 'followup_info', $this, 'followup_id' );
			addHistory ( 'followupinfo', $this->followup_id, 'add', $this->followup_id );

		}

		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}
	}
}

function issueView($str){
	global $treatIssues,$issues;
	if($treatIssues == true){
		prepareIssue(true);
		$treatIssues=false;
	}
	$code=array();
	$list = explode(',',$str);
	foreach ($list as $val) {
		if(strstr($val,'-')){
			$parts=explode('-',$val);
			$code[]=$issues[$parts[0]]['title'].'-'.$issues[$parts[0]]['kids'][$parts[1]];

		}else{
			$code[]=$issues[$val];
		}
	}
	return join(', ',$code);
}

function prepareIssue($tail = false) {
	global $issues;
	/*foreach ( $issues as $key => $val ) {
		if (is_array ( $val )) {
			if ($val ['kids'] != '') {
				$dvals = dPgetSysVal ( $val ['kids'] );
				$issues [$key] ['kids'] = $dvals;
			}
		}
	}*/
	if($tail === true){
		$issues[14]='Other ';
	}
}

class lister {
	var $pref;
	var $cvals;
	var $hname;
	var $str;
	var $mode;
	var $list = array();
	var $lval=0;

	function lister($case,$prefix,$arr,$name,$str=false){
		if($case == 'follow'){
			global $issues;
			prepareIssue(true);
			$this->cvals=$issues;
		}else{
			$this->cvals=$arr;
		}
		if($name == 'issues'){
			$this->list = explode(',',$str);
			$this->lval='14';
		}
		$this->mode=$case;
		$this->pref=$prefix;
		$this->hname = $name;
		$this->str=$str;

	}

	function build($cut = FALSE) {
		foreach ( $this->cvals as $key => $val ) {
			if (is_array ( $val )) {
				$code .= '<b>' . $val ['title'] . '</b><br>';
				if ($val ['kids'] != '') {
					foreach ( $val ['kids'] as $dkey => $dval ) {
						$chk = '';
						if (in_array ( $key . '-' . $dkey, $this->list )) {
							$chk = 'checked="checked"';
						}
						$code .= '<label><input ' . $chk . ' type="checkbox" name="' . $this->pref . '_' . $this->hname . '[]" value="' . $key . '-' . $dkey . '">&nbsp;' . /*$key ''. '.' .*/ $dval . '</label><br>'."\n";
					}
					$code .= "<hr>\n\n";
				}
			} else {
				$chk = '';
				if (in_array ( $key, $this->list )) {
					$chk = 'checked="checked"';
				}
				$code .= '<label><input ' . $chk . ' type="checkbox" name="' . $this->pref . '_' . $this->hname . '[]" value="' . $key . '">&nbsp;' . $key . '.' . $val . '</label><br>'."\n";
			}
		}
		if ($this->mode == 'issue') {
			$chk = '';
			if (in_array ( '14', $this->list )) {
				$chk = 'checked="checked"';
			}
			$code .= '<input type="checkbox" ' . $chk . ' name="' . $this->pref . '_' . $this->hname . '[]" value="' . $this->lval . '">' . $this->lval . '. Other:&nbsp;';
		}
		if ($this->str === false) {
			$code .= '<input type="text" size="18" name="' . $this->pref . '_' . $this->hname . '_note" id="other_' . $this->hname . '">';
		}
		if($cut === true){
			$code=str_replace("\n",'',$code);
			$code=str_replace("\t",'',$code);
		}
		return $code;

	}
}

function listHTML($mode,$str = ''){
	$prefix='followup';
	if($mode == 'issue'){
		global $issues;
		prepareIssue();
		$cvals=$issues;
		$hname='issues';
		$lval='14';
		$list = explode(',',$str);
	}elseif($mode == 'service'){
		$cvals = dPgetSysVal('FollowServices');
		$hname='services';
		$lval=count($cvals);
		$list=array();
	}elseif($mode == 'care'){
		$cvals = dPgetSysVal('CBCHomeCare');
		$hname='care';
		$lval=count($cvals);
		$list=array();
		$prefix= 'cbc';
		$str = true;
	}elseif ($mode == 'needs'){
		$cvals = dPgetSysVal('ServiceTypes');
		$hname='need';
		$lval=count($cvals);
		$list=array();
		$prefix= 'chw';
		$str = true;
	}


}


function makeListPerson($admno,$alone = 0){
	$answer=array();

	$sql= 'select concat_ws(" ",client_first_name,client_last_name,client_other_name) as aname,client_id,client_gender,client_dob
			from clients
			where client_adm_no ="'.mysql_real_escape_string($admno).'" limit 1';
	$res=mysql_query($sql);
	if($res && mysql_num_rows($res) == 1){
		$kid = mysql_fetch_object($res);
		//echo $name->aname;
	}else{
		echo 'fail';
		return ;
	}
	$lages=digiAge($kid->client_dob);
	if(!$lages || !is_array($lages)){
		$lages=array();
		$lages[0]='';
	}
	$answer['child']=array($kid->aname,$lages[0],$kid->client_gender);
	$answer['client_id']=$kid->client_id;

	if ($alone === 0) {
		$sql = 'select distinct role, concat_ws(" ",fname,lname) as aname from admission_caregivers where client_id="' . $kid->client_id . '" and role IN ("father","mother") limit 2';
		$res2 = mysql_query ( $sql );
		$parentscount = mysql_num_rows ( $res2 );
		if ($res2) {
			$answer ['parent'] = array ();
			for($i = 0; $i < $parentscount; $i ++) {
				$pars = mysql_fetch_object ( $res2 );
				if (! is_null ( $pars->aname )) {
					$answer ['parent'] [] = $pars->aname;
				}
			}
		}
		unset ( $pars );
		$carez = array ('pri', 'sec' );
		$answer ['caregiver'] = array ();
		foreach ( $carez as $val ) {
			$sql = 'select concat_ws(" ",fname,lname) as aname, role from admission_caregivers where client_id="' . $kid->client_id . '" and lname <> "" and lname is not null  and role="' . $val . '" and datesoff is null limit 1';
			$res2 = mysql_query ( $sql );
			if ($res2) {
				$pars = mysql_fetch_object ( $res2 );
				if (! is_null ( $pars->aname )) {
					$answer ['caregiver'] [] = $pars->aname;
				}
			}
		}
	}
	return $answer;
}

?>