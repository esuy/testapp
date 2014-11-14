<?php /* MORTALITY INFO $Id: companies.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *       Mortality Class
 *	@todo Move the 'address' fields to a generic table
 */
class CMortality extends CDpObject {

var $mortality_id = NULL;
var $mortality_client_id = NULL;
var $mortality_entry_date = NULL;
var $mortality_clinic_id = NULL;
var $mortality_age_yrs = NULL;
var $mortality_age_months = NULL;
var $mortality_age_status = NULL;
var $mortality_social_worker = NULL;
var $mortality_date = NULL;
var $mortality_death_type = NULL;
var $mortality_death_type_notes = NULL;
var $mortality_informant = NULL;
var $mortality_hospital = NULL;
var $mortality_hospital_adm_date = NULL;
var $mortality_relative_report_date = NULL;
var $mortality_symptoms = NULL;
var $mortality_time_course = NULL;
var $mortality_treatment = NULL;
var $mortality_referral = NULL;
var $mortality_hospital_referral = NULL;
var $mortality_hospital_adm_notes  = NULL;
var $mortality_cause_given = NULL;
var $mortality_cause_desc = NULL;
var $mortality_clinical_officer = NULL;
var $mortality_clinical_officer_old = NULL;
var $mortality_clinical_officer_date = NULL;
var $mortality_postmortem = NULL;
var $mortality_cause_pm = NULL;
var $mortality_likely_cause = NULL;
var $mortality_notes = NULL;
var $mortality_custom = NULL;
var $mortality_clinical_course = NULL;
var $mortality_postmortem_where = null;
var $mortality_recents_a = null;
var $mortality_recents_b = null;
var $mortality_malnutrition = null;
var $mortality_malnutrition_notes = null;
var $mortality_weight = null;
var $mortality_height = null;
var $mortality_nutrition_date = null;
var $mortality_cd4 = null;
var $mortality_cd4_percentage = null;
var $mortality_viral_load = null;
var $mortality_hb = null;
var $mortality_clinical_date = null;
var $mortality_arv = null;
var $mortality_arv_dateon = null;
var $mortality_arv_period = null;
var $mortality_tb = null;
var $mortality_tb_start = null;
var $mortality_enroll_date = null;
var $mortality_enrolled_time = null; 




	function CMortality() {
		$this->CDpObject( 'mortality_info', 'mortality_id' );
	}

// overload check
	function check() 
	{
		if (empty($this->mortality_date)) 
		{
			$this->mortality_date = NULL;
		}		
		if (empty($this->mortality_hospital_adm_date ))
		{
			$this->mortality_hospital_adm_date = NULL ;
		}		
		//if (empty($this->mortality_illness_date))
		//{
			//$this->mortality_illness_date = NULL;
		//}		
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
		
		if( ($this->mortality_id) && ($this->mortality_id > 0)) 
		{
			
			addHistory('mortalityinfo', $this->mortality_id, 'update', $this->mortality_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'mortality_info', $this, 'mortality_id', true );


		} 
		else 
		{
		    
			$this->_action = 'added';
			$ret = db_insertObject( 'mortality_info', $this, 'mortality_id' );
			addHistory('mortalityinfo', $this->mortality_id, 'add', $this->mortality_id);

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
