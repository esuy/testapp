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
class CMedicalAssessment extends CDpObject {

var $medical_id  = NULL;
var $medical_client_id  = NULL;
var $medical_staff_id  = NULL;
var $medical_clinic_id  = NULL;
var $medical_entry_date   = NULL;
var $medical_gender   = NULL;
var $medical_age_yrs   = NULL;
var $medical_age_months   = NULL;
var $medical_birth_location   = NULL;
var $medical_delivery   = NULL;
var $medical_birth_problems   = NULL;
var $medical_transferred  = NULL;
var $medical_other_programme  = NULL;
var $medical_birth_weight  = NULL;
var $medical_pmtct  = NULL;
var $medical_mother_arv_given  = NULL;
var $medical_child_arv_given  = NULL;
var $medical_immunization_status  = NULL;
var $medical_card_seen  = NULL;
var $medical_breastfeeding  = NULL;
var $medical_exclusive_breastfeeding  = NULL;
var $medical_bf_duration  = NULL;
var $medical_father_hiv_status  = NULL;
var $medical_father_arv  = NULL;
var $medical_mother_hiv_status  = NULL;
var $medical_mother_arv  = NULL;
var $medical_no_siblings_alive  = NULL;
var $medical_no_siblings_deceased  = NULL;
var $medical_tb_contact  = NULL;
var $medical_tb_contact_person  = NULL;
var $medical_tb_date_diagnosed  = NULL;
var $medical_tb_pulmonary  = NULL;
var $medical_tb_type  = NULL;
var $medical_tb_type_desc  = NULL;
var $medical_tb_bodysite  = NULL;
var $medical_tb_date1  = NULL;
var $medical_tb_date2  = NULL;
var $medical_tb_date3  = NULL;
var $medical_history_pneumonia  = NULL;
var $medical_history_diarrhoea  = NULL;
var $medical_history_skin_rash  = NULL;
var $medical_history_ear_discharge  = NULL;
var $medical_history_fever  = NULL;
var $medical_history_oral_rush  = NULL;
var $medical_history_mouth_ulcers  = NULL;
var $medical_history_malnutrition  = NULL;
var $medical_history_prev_nutrition  = NULL;
var $medical_history_notes  = NULL;
var $medical_arv_status  = NULL;
var $medical_arv1  = NULL;
var $medical_arv1_startdate  = NULL;
var $medical_arv1_enddate  = NULL;
var $medical_arv2  = NULL;
var $medical_arv2_startdate  = NULL;
var $medical_arv2_enddate  = NULL;
var $medical_salvage = null;
var $medical_salvage_startdate  = NULL;
var $medical_salvage_enddate  = NULL;
var $medical_arv_side_effects  = NULL;
var $medical_arv_adherence   = NULL;
var $medical_school_attendance  = NULL;
var $medical_school_class  = NULL;
var $medical_educ_progress  = NULL;
var $medical_sensory_hearing  = NULL;
var $medical_sensory_vision  = NULL;
var $medical_sensory_motor_ability  = NULL;
var $medical_sensory_speech_language  = NULL;
var $medical_sensory_social_skills  = NULL;
var $medical_meals_per_day  = NULL;
var $medical_food_types  = NULL;
var $medical_current_complaints  = NULL;
var $medical_weight  = NULL;
var $medical_height  = NULL;
var $medical_zscore  = NULL;
var $medical_muac  = NULL;
var $medical_hc  = NULL;
var $medical_condition  = NULL;
var $medical_temp  = NULL;
var $medical_conditions  = NULL;
var $medical_dehydration  = NULL;
var $medical_parotids  = NULL;
var $medical_lymph  = NULL;
var $medical_eyes  = NULL;
var $medical_eyes_notes  = NULL;
var $medical_ear_discharge  = NULL;
var $medical_throat  = NULL;
var $medical_mouth_thrush  = NULL;
var $medical_mouth_ulcers  = NULL;
var $medical_mouth_teeth  = NULL;
var $medical_oldlesions  = NULL;
var $medical_currentlesions  = NULL;
var $medical_heartrate  = NULL;
var $medical_recession  = NULL;
var $medical_percussion  = NULL;
var $medical_location  = NULL;
var $medical_breath_sounds  = NULL;
var $medical_breathlocation  = NULL;
var $medical_other_sounds  = NULL;
var $medical_soundlocation  = NULL;
var $medical_pulserate  = NULL;
var $medical_apex_beat  = NULL;
var $medical_precordial  = NULL;
var $medical_femoral  = NULL;
var $medical_heart_sound      = NULL;
var $medical_heart_type  = NULL;
var $medical_abdomen_distended  = NULL;
var $medical_adbomen_feel  = NULL;
var $medical_abdomen_tender  = NULL;
var $medical_abdomen_fluid  = NULL;
var $medical_liver_costal  = NULL;
var $medical_spleen_costal  = NULL;
var $medical_masses  = NULL;
var $medical_umbilical_hernia  = NULL;
var $medical_testes  = NULL;
var $medical_which_testes  = NULL;
var $medical_genitals_feel  = NULL;
var $medical_penis  = NULL;
var $medical_genitals_female  = NULL;
var $medical_genitals_female_notes  = NULL;
var $medical_pubertal  = NULL;
var $medical_gait  = NULL;
var $medical_handuse  = NULL;
var $medical_weakness  = NULL;
var $medical_tone  = NULL;
var $medical_tendon_legs  = NULL;
var $medical_tendon_arms  = NULL;
var $medical_abnormal_movts  = NULL;
var $medical_movts_impaired  = NULL;
var $medical_movts_impaired_desc  = NULL;
var $medical_joints_swelling   = NULL;
var $medical_joints_swelling_desc  = NULL;
var $medical_motor  = NULL;
var $medical_musc_notes  = NULL;
var $medical_hiv_status  = NULL;
var $medical_cd4  = NULL;
var $medical_cd4_percentage  = NULL;
var $medical_who_clinical_stage  = NULL;
var $medical_immuno_stage  = NULL;
var $medical_tests  = NULL;
var $medical_referral  = NULL;
var $medical_notes  = NULL;
var $medical_custom  = NULL;
var $medical_heart_rate = NULL;
var $medical_resp_rate = NULL;
var $medical_skin_type = NULL;
var $medical_skin_note = NULL;
var $medical_chest_shape = NULL;
var $medical_cns = NULL;
var $medical_cns_note = NULL;
var $medical_muscle = NULL;
var $medical_muscle_note = NULL;
var $medical_gait_opt = NULL;
var $medical_handuse_opt = NULL;
var $medical_request = NULL;
var $medical_request_opts = NULL;
var $medical_request_note = NULL;
var $medical_next_visit = NULL;



	function CMedicalAssessment() {
		$this->CDpObject( 'medical_assessment', 'medical_id' );
	}

// overload check
	function check() 
	{
		/*if ($this->medical_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->medical_id = intval( $this->medical_id );*/
				
		
		/*if (empty($this->medical_tb_date_diagnosed)) 
		{
			$this->medical_tb_date_diagnosed = NULL;
		}*/		
		if (empty($this->medical_tb_date1)) 
		{
			$this->medical_tb_date1 = NULL;
		}	
		if (empty($this->medical_tb_date2)) 
		{
			$this->medical_tb_date2 = NULL;
		}			
		if (empty($this->medical_tb_date3)) 
		{
			$this->medical_tb_date3 = NULL;
		}		
		if (empty($this->medical_arv1_startdate)) 
		{
			$this->medical_arv1_startdate = NULL;
		}		
		if (empty($this->medical_arv1_enddate)) 
		{
			$this->medical_arv1_enddate = NULL;
		}		
		if (empty($this->medical_arv2_startdate)) 
		{
			$this->medical_arv2_startdate = NULL;
		}		
		if (empty($this->medical_arv2_enddate)) 
		{
			$this->medical_arv2_enddate = NULL;
		}		
	
		if (empty($this->medical_staff_id))
		{
			$this->medical_staff_id = NULL;
		}		
		if (empty($this->medical_current_complaints))
		{
			$this->medical_current_complaints = NULL;
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
		
		if( ($this->medical_id) && ($this->medical_id > 0)) 
		{
			
			addHistory('medicalassessment', $this->medical_id, 'update', $this->medical_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'medical_assessment', $this, 'medical_id', true );


		} 
		else 
		{
		    
			$this->_action = 'added';
			$ret = db_insertObject( 'medical_assessment', $this, 'medical_id' );
			addHistory('medical_assessment', $this->medical_id, 'add', $this->medical_id);

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
