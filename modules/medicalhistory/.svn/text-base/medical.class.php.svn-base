<?php /* MEDICAL ASSESSMENT $Id: medical.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	MedicalAssessment Class
 *	
 */
class CMedicalHistory extends CDpObject {

var $medical_history_id  = NULL;
var $medical_history_medical_id  = NULL;
var $medical_history_hospital  = NULL;
var $medical_history_date  = NULL;
var $medical_history_diagnosis   = NULL;
var $medical_history_notes   = NULL;
var $medical_history_custom   = NULL;
	



	function CMedicalHistory() {
		$this->CDpObject( 'medical_history', 'medical_history_id' );
	}

// overload check
	function check() 
	{
	
	
		return NULL; // object is ok
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
		"view" => "./index.php?m=counsellinginfo&a=view&company_id=$this->company_id",
		"add" => "./index.php?m=counsellinginfo&a=addedit&company_type=$companyType",
		"edit"=> "./index.php?m=counsellinginfo&a=addedit&company_id=$this->company_id"
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
		
		if( ($this->medical_id) && ($this->medical_history_id > 0)) 
		{
			
			addHistory('medical_history', $this->medical_history_id, 'update', $this->medical_history_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'medical_history', $this, 'medical_history_id', true);


		} 
		else 
		{
		    
			$this->_action = 'added';
			$ret = db_insertObject( 'medical_history', $this, 'medical_history_id' );
			addHistory('medical_history', $this->medical_history_id, 'add', $this->medical_history_id);

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
?>
