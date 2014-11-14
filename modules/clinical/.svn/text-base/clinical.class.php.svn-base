<?php /* CLINICAL INFO $Id: companies.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Clinical Visit Class
 *
 */
class CClinicalVisit extends CDpObject {

var $clinical_id  = NULL;
var $clinical_client_id  = NULL;
var $clinical_staff_id  = NULL;
var $clinical_entry_date  = NULL;
var $clinical_clinic_id  = NULL;
var $clinical_age_yrs  = NULL;
var $clinical_age_months  = NULL;
var $clinical_child_attending  = NULL;
var $clinical_caregiver_attending  = NULL;
var $clinical_caregiver = NULL;
var $clinical_illness  = NULL;
var $clinical_illness_notes  = NULL;
var $clinical_diarrhoea  = NULL;
var $clinical_vomiting  = NULL;
var $clinical_current_complaints = NULL;
var $clinical_bloodtest_date = NULL;
var $clinical_bloodtest_cd4  = NULL;
var $clinical_bloodtest_cd4_percentage = NULL;
var $clinical_bloodtest_viral  = NULL;
var $clinical_bloodtest_hb  = NULL;
var $clinical_xray_results  = NULL;
var $clinical_other_results  = NULL;
var $clinical_weight  = NULL;
var $clinical_height  = NULL;
var $clinical_zscore  = NULL;
var $clinical_muac  = NULL;
var $clinical_hc  = NULL;
var $clinical_child_unwell  = NULL;
var $clinical_temp  = NULL;
var $clinical_resp_rate  = NULL;
var $clinical_heart_rate  = NULL;
var $clinical_general  = NULL;
var $clinical_pallor = NULL;
var $clinical_jaundice = NULL;
var $clinical_oedema = NULL;
var $clinical_clubbing = NULL;
var $clinical_examination_dehydration = NULL;
var $clinical_examination_lymph = NULL;
var $clinical_mouth  = NULL;
var $clinical_teeth  = NULL;
var $clinical_ears  = NULL;
var $clinical_chest  = NULL;
var $clinical_chest_clear  = NULL;
var $clinical_chest_creps  = NULL;
var $clinical_cardiovascular  = NULL;
var $clinical_skin  = NULL;
var $clinical_skin_clear  = NULL;
var $clinical_skin_opts  = NULL;
var $clinical_abdomen  = NULL;
var $clinical_musculoskeletal = NULL;
var $clinical_neurodevt  = NULL;
var $clinical_adherence = NULL;
var $clinical_adherence_notes = NULL;
var $clinical_chronic_lung = NULL;
var $clinical_diarrhoea_type = NULL;
var $clinical_dehydration  = NULL;
var $clinical_pneumonia = NULL;
var $clinical_lung_disease = NULL;
var $clinical_tb  = NULL;
var $clinical_tb_treatment_date  = NULL;
var $clinical_pulmonary  = NULL;
var $clinical_discharging_ears  = NULL;
var $clinical_other_diagnoses = NULL;
var $clinical_malnutrition  = NULL;
var $clinical_growth  = NULL;
var $clinical_assessment_notes  = NULL;
var $clinical_investigations  = NULL;

var $clinical_investigations_notes  = NULL;
var $clinical_arv_notes  = NULL;
var $clinical_who_stage  = NULL;
var $clinical_who_current  = NULL;
var $clinical_who_reason  = NULL;
var $clinical_tb_drugs  = NULL;
var $clinical_other_drugs  = NULL;
var $clinical_new_drugs  = NULL;
var $clinical_on_arvs  = NULL;
var $clinical_arv_drugs = NULL;
var $clinical_arv_drugs_other = NULL;
var $clinical_vitamins  = NULL;
var $clinical_treatment_status  = NULL;
var $clinical_arv_reason  = NULL;
//var $clinical_new_drugs  = NULL;
var $clinical_nutritional_support  = NULL;
var $clinical_nutritional_notes  = NULL;
var $clinical_referral  = NULL;
var $clinical_referral_other  = NULL;
var $clinical_next_date  = NULL;
var $clinical_notes  = NULL;
var $clinical_custom  = NULL;
var $clinical_complaints= NULL;
var $clinical_ctscan = NULL;
var $clinical_astal = NULL;
var $clinical_ears_opt = NULL;
var $clinical_throat  = null;
var $clinical_mouth_thrush = NULL;
var $clinical_mouth_ulcer = NULL;
var $clinical_teeth_opt = NULL;
var $clinical_cns = null;
var $clinical_muscle = null;
var $clinical_eyes = NULL;
var $clinical_eyes_opt = NULL;
var $clinical_arv_on = NULL;
var $clinical_arv_on_adh = NULL;
var $clinical_arv_recomends = NULL;
var $clinical_stage = NULL;
var $clinical_dss = null;
var $clinical_request = null;
var $clinical_request_list = null;
var $clinical_tb_treat = null;
var $clinical_tb_status = null;
var $clinical_tb_status_notes = null;
var $clinical_other = null;
var $clinical_therapy_stage = null;

	function CClinicalVisit() {
		$this->CDpObject( 'clinical_visits', 'clinical_id' );
	}

// overload check
	function check()
	{
		/*if ($this->counselling_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->counselling_id = intval( $this->counselling_id );*/


		if (empty($this->clinical_next_date))
		{
			$this->clinical_next_date = NULL;
		}
		if (empty($this->clinical_bloodtest_date ))
		{
			$this->clinical_bloodtest_date = NULL ;
		}
		if (empty($this->clinical_tb_treatment_date))
		{
			$this->clinical_tb_treatment_date = NULL;
		}
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

		if( ($this->clinical_id) && ($this->clinical_id > 0))
		{

			addHistory('clinicalvisit', $this->clinical_id, 'update', $this->clinical_id);
			$this->_action = 'updated';
			$ret = db_updateObject( 'clinical_visits', $this, 'clinical_id', true );


		}
		else
		{

			$this->_action = 'added';
			$ret = db_insertObject( 'clinical_visits', $this, 'clinical_id' );
			addHistory('clinicalvisit', $this->clinical_id, 'add', $this->clinical_id);

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
