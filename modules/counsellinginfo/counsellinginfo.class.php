<?php /* COUNSELLING INFO $Id: companies.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
 */

require_once ($AppUI->getSystemClass ( 'dp' ));

/**
 *	CounsellingInfo Class
 *	@todo Move the 'address' fields to a generic table
 */
class CCounsellingInfo extends CDpObject {
	
	var $counselling_id = NULL;
	var $counselling_client_id = NULL;
	var $counselling_clinic = NULL;
	var $counselling_staff_id = NULL;
	var $counselling_referral_source = NULL;
	//var $counselling_total_orphan = NULL;
	var $counselling_dob = NULL;
	var $counselling_entry_date = NULL;
	var $counselling_age_yrs = NULL;
	var $counselling_age_months = NULL;
	var $counselling_age_status = NULL;
	var $counselling_place_of_birth = NULL;
	var $counselling_birth_area = NULL;
	var $counselling_mode_birth = NULL;
	var $counselling_gestation_period = NULL;
	var $counselling_birth_weight = NULL;
	var $counselling_mothers_status_known = NULL;
	var $counselling_mother_antenatal = NULL;
	var $counselling_mother_antenatal_where = NULL;
	var $counselling_mother_pmtct = NULL;
	var $counselling_mother_pmtct_where = NULL;
	var $counselling_mother_illness_pregnancy = NULL;
	var $counselling_mother_illness_pregnancy_notes = NULL;
	var $counselling_breastfeeding = NULL;
	var $counselling_breastfeeding_duration = NULL;
	var $counselling_other_breastfeeding_duration = NULL;
	var $counselling_child_prenatal = NULL;
	var $counselling_child_single_nvp = NULL;
	var $counselling_child_nvp_date = NULL;
	var $counselling_child_nvp_notes = NULL;
	var $counselling_child_azt = NULL;
	var $counselling_child_azt_date = NULL;
	var $counselling_no_doses = NULL;
	var $counselling_mother_treatment = NULL;
	var $counselling_mother_treatment_where = NULL;
	var $counselling_mother_art_pregnancy = NULL;
	var $counselling_mother_date_art = NULL;
	var $counselling_mother_cd4 = NULL;
	var $counselling_mother_date_cd4 = NULL;
	var $counselling_determine_date = NULL;
	var $counselling_determine = NULL;
	var $counselling_bioline_date = NULL;
	var $counselling_bioline = NULL;
	var $counselling_unigold_date = NULL;
	var $counselling_unigold = NULL;
	var $counselling_elisa_date = NULL;
	var $counselling_elisa = NULL;
	var $counselling_pcr1_date = NULL;
	var $counselling_pcr1 = NULL;
	var $counselling_pcr2_date = NULL;
	var $counselling_pcr2 = NULL;
	var $counselling_rapid12_date = NULL;
	var $counselling_rapid12 = NULL;
	var $counselling_rapid18_date = NULL;
	var $counselling_rapid18 = NULL;
	var $counselling_other_date = NULL;
	var $counselling_other = NULL;
	var $counselling_other_notes = NULL;
	var $counselling_notes = NULL;
	var $counselling_custom = NULL;
	var $counselling_vct_camp = NULL;
	var $counselling_vct_camp_site = NULL;
	var $counselling_client_code = NULL;
	var $counselling_partner_code = NULL;
	var $counselling_return = NULL;
	var $counselling_area = NULL;
	var $counselling_gender = NULL;
	var $counselling_marital = NULL;
	var $counselling_client_seen = NULL;
	var $counselling_final = NULL;
	var $counselling_dis_couple = NULL;
	var $counselling_positive_ref = NULL;
	var $counselling_positive_ref_notes = NULL;
	var $counselling_mother_cd4_note = null;
	var $counselling_admission_date = NULL;
	var $counselling_referral_source_notes = NULL;
	
	function CCounsellingInfo() {
		$this->CDpObject ( 'counselling_info', 'counselling_id' );
	}
	 
	// overload check
	function check() {
		/*if ($this->counselling_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->counselling_id = intval( $this->counselling_id );*/
		
		if (empty ( $this->counselling_dob )) {
			$this->counselling_dob = NULL;
		}
		if (empty ( $this->counselling_age_yrs )) {
			$this->counselling_age_yrs = NULL;
		}
		if (empty ( $this->counselling_age_months )) {
			$this->counselling_age_months = NULL;
		}
		if (empty ( $this->counselling_gestation_period )) {
			$this->counselling_gestation_period = NULL;
		}
		if (empty ( $this->counselling_no_doses )) {
			$this->counselling_no_doses = NULL;
		}
		if (empty ( $this->counselling_child_nvp_date )) {
			$this->counselling_child_nvp_date = NULL;
		}
		if (empty ( $this->counselling_mother_date_art )) {
			$this->counselling_mother_date_art = NULL;
		}
		if (empty ( $this->counselling_child_azt_date )) {
			$this->counselling_child_azt_date = NULL;
		}
		
		if (empty ( $this->counselling_mother_date_cd4 )) {
			$this->counselling_mother_date_cd4 = NULL;
		}
		if (empty ( $this->counselling_determine_date )) {
			$this->counselling_determine_date = NULL;
		}
		if (empty ( $this->counselling_bioline_date )) {
			$this->counselling_bioline_date = NULL;
		}
		if (empty ( $this->counselling_unigold_date )) {
			$this->counselling_unigold_date = NULL;
		}
		if (empty ( $this->counselling_elisa_date )) {
			$this->counselling_elisa_date = NULL;
		}
		if (empty ( $this->counselling_pcr1_date )) {
			$this->counselling_pcr1_date = NULL;
		}
		if (empty ( $this->counselling_pcr2_date )) {
			$this->counselling_pcr2_date = NULL;
		}
		
		if (empty ( $this->counselling_rapid12_date )) {
			$this->counselling_rapid12_date = NULL;
		}
		if (empty ( $this->counselling_rapid18_date )) {
			$this->counselling_rapid18_date = NULL;
		}
		
		if (empty ( $this->counselling_other_date )) {
			$this->counselling_other_date = NULL;
		}
		
		return NULL; // object is ok
	}
	
	// overload canDelete
	function canDelete(&$msg, $oid = null) {
	
	}
	function getContacts($type = NULL) {
		$contacts = NULL;
		$q = new DBQuery ( );
		
		if (isset ( $this->company_id )) {
			$q->addTable ( 'company_contacts' );
			$q->addQuery ( 'company_contacts_contact_id' );
			$q->addWhere ( "company_contacts_company_id = $this->company_id" );
			if ($type)
				$q->addWhere ( "company_contacts_contact_type = $type" );
			
			$contacts = $q->loadColumn ();
		}
		//if (count($contacts)==1)
		//$contacts = $contacts[0];
		

		return $contacts;
	
	}
	function getUrl($urlType = 'view', $companyType = NULL) {
		if ($companyType == NULL)
			$companyType = $this->company_type;
		
		$modules = dPgetSysVal ( 'CompanyModules' );
		$unit = $modules [$companyType];
		$url_array = array ("view" => "./index.php?m=counsellinginfo&a=view&company_id=$this->company_id", "add" => "./index.php?m=counsellinginfo&a=addedit&company_type=$companyType", "edit" => "./index.php?m=counsellinginfo&a=addedit&company_id=$this->company_id" );
		return $url_array [$urlType];
	}
	function getDescription() {
		static $types;
		if (! isset ( $types )) {
			$types = dPgetSysVal ( 'CompanyType' );
		}
		$desc = $types [$this->company_type];
		return $desc;
	}
	function getCount() {
		$sql = "SELECT COUNT(*) FROM counselling_info WHERE counselling_client_id = $this->counselling_client_id";
		
		$count = db_loadResult ( $sql );
		return $count;
	}
	
	function store() {
		global $AppUI;
		//$importing_tasks = false;
		$msg = $this->check ();
		if ($msg) {
			$return_msg = array (get_class ( $this ) . '::store-check', 'failed', '-' );
			if (is_array ( $msg ))
				return array_merge ( $return_msg, $msg );
			else {
				array_push ( $return_msg, $msg );
				return $return_msg;
			}
		}
		//var_dump($this);
		if (($this->counselling_id) && ($this->counselling_id > 0)) {
			
			addHistory ( 'counsellinginfo', $this->counselling_id, 'update', $this->counselling_id );
			$this->_action = 'updated';
			
			$ret = db_updateObject ( 'counselling_info', $this, 'counselling_id', true );
		
		} else {
			
			$this->_action = 'added';
			$ret = db_insertObject ( 'counselling_info', $this, 'counselling_id' );
			addHistory ( 'counsellinginfo', $this->counselling_id, 'add', $this->counselling_id );
		
		}
		
		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}
	}
}
?>
