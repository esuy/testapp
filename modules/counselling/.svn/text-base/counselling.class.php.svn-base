<?php /* COUNSELLING INTAKE $Id: counselling.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Counselling Intake Class
 *
 */
class CCounsellingVisit extends CDpObject
{

var $counselling_id   = NULL;
var $counselling_client_id  = NULL;
var $counselling_staff_id  = NULL;
var $counselling_center_id  = NULL;
var $counselling_entry_date  = NULL;
var $counselling_visit_type  = NULL;
var $counselling_caregiver_fname  = NULL;
var $counselling_caregiver_lname  = NULL;
var $counselling_caregiver_age  = NULL;
var $counselling_caregiver_relationship  = NULL;
var $counselling_caregiver_marital_status  = NULL;
var $counselling_caregiver_educ_level  = NULL;
var $counselling_caregiver_employment  = NULL;
var $counselling_caregiver_income_level  = NULL;
var $counselling_caregiver_idno  = NULL;
var $counselling_caregiver_mobile  = NULL;
var $counselling_caregiver_residence  = NULL;
var $counselling_child_issues  = NULL;
var $counselling_other_issues  = NULL;
var $counselling_caregiver_issues  = NULL;
var $counselling_caregiver_other_issues  = NULL;
var $counselling_caregiver_issues2  = NULL;
var $counselling_caregiver_other_issues2  = NULL;
var $counselling_child_knows_status  = NULL;
var $counselling_otheradult_knows_status  = NULL;
var $counselling_disclosure_response  = NULL;
var $counselling_disclosure_state  = NULL;
var $counselling_secondary_caregiver_knows  = NULL;
var $counselling_primary_caregiver_tested  = NULL;
var $counselling_father_status  = NULL;
var $counselling_mother_status  = NULL;
var $counselling_caregiver_status  = NULL;
var $counselling_father_treatment  = NULL;
var $counselling_mother_treatment  = NULL;
var $counselling_caregiver_treatment  = NULL;
var $counselling_stigmatization_concern  = NULL;
var $counselling_counselling_services  = NULL;
var $counselling_other_services  = NULL;
var $counselling_notes  = NULL;
var $counselling_custom  = NULL;
var $counselling_second_ident = NULL;
var $counselling_referer = NULL;
var $counselling_referer_other = NULL;
var $counselling_next_visit = NULL;


	function CCounsellingVisit() {
		$this->CDpObject( 'counselling_visit', 'counselling_id' );
	}

// overload check
	function check()
	{
		/*if ($this->counselling_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->counselling_id = intval( $this->counselling_id );*/


		if (empty($this->counselling_caregiver_age))
		{
			$this->counselling_caregiver_age = NULL;
		}
		if (empty($this->counselling_staff_id))
		{
			$this->counselling_staff_id = NULL;
		}
		if (empty($this->counselling_father_status))
		{
			$this->counselling_father_status = NULL;
		}
		if (empty($this->counselling_mother_status))
		{
			$this->counselling_mother_status = NULL;
		}
		if (empty($this->counselling_caregiver_status))
		{
			$this->counselling_caregiver_status = NULL;
		}
		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null )
	{

	}
	function getContacts($type = NULL)
	{
		$contacts = NULL;
		$q = new DBQuery;

		if (isset($this->company_id))
		{
			$q->addTable('company_contacts');
			$q->addQuery('company_contacts_contact_id');
			$q->addWhere("company_contacts_company_id = $this->company_id");
			if ($type)
			   $q->addWhere("company_contacts_contact_type = $type");

			$contacts = $q->loadColumn();
		}
		//if (count($contacts)==1)
		   //$contacts = $contacts[0];

		return $contacts;

	}
	function getUrl($urlType='view', $companyType = NULL)
	{
		if ($companyType == NULL) $companyType = $this->company_type;


		$modules = dPgetSysVal('CompanyModules');
		$unit = $modules[$companyType];
		$url_array = array(
		"view" => "./index.php?m=counselling&a=view&company_id=$this->company_id",
		"add" => "./index.php?m=counselling&a=addedit&company_type=$companyType",
		"edit"=> "./index.php?m=counselling&a=addedit&company_id=$this->company_id"
		);
		return $url_array[$urlType];
	}
	function getDescription()
	{
		static $types;
		if (!isset($types))
		{
			$types = dPgetSysVal('CompanyType');
		}
		$desc = $types[$this->company_type];
		return $desc;
	}
	function getCount($type = NULL)
	{
				if (!empty($type))
				{
					$sql = "SELECT COUNT(*) FROM companies WHERE company_type IS NOT NULL AND company_type = $type";
				}
				else
				{
					$sql = "SELECT COUNT(*)  FROM companies WHERE company_type IS NOT NULL";
				}
		$count = db_loadResult($sql);
		return $count;
  }

  function store()
  {
		global $AppUI;

		//$importing_tasks = false;
		$msg = $this->check();
		if( $msg )
		{
			$return_msg = array(get_class($this) . '::store-check',  'failed',  '-');
			if (is_array($msg))
				return array_merge($return_msg, $msg);
			else
			{
				array_push($return_msg, $msg);
				return $return_msg;
			}
		}

		if( ($this->counselling_id) && ($this->counselling_id > 0))
		{

			addHistory('counsellingvisit', $this->counselling_id, 'update', $this->counselling_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'counselling_visit', $this, 'counselling_id', true );


		}
		else
		{

			$this->_action = 'added';
			$ret = db_insertObject( 'counselling_visit', $this, 'counselling_id' );
			addHistory('counsellingvisit', $this->counselling_id, 'add', $this->counselling_id);

		}

		if( !$ret )
		{
			return get_class( $this )."::store failed <br />" . db_error();
		}
		else
		{
			return NULL;
		}
	}
}

function tailFirst($arr){
	foreach ($arr as $key => $val) {
		$tail=array($key=>$val);
	}
	array_pop($arr);
	return arrayMerge($tail,$arr);
}
?>
