<?php

class CDischarge extends CDpObject {

	var $dis_id = NULL;
	var $dis_client_id = NULL;
	var $dis_client_adm_no = NULL;
	var $dis_center = NULL;
	var $dis_entry_date = NULL;
	var $dis_time_in = NULL;
	var $dis_age_years = NULL;
	var $dis_age_months = NULL;
	var $dis_age_exact = NULL;
	var $dis_client_status = NULL;
	var $dis_status_delta_date = NULL;
	var $dis_status_mdt_date = NULL;
	var $dis_status_next_date = NULL;
	var $dis_phys_address = NULL;
	var $dis_landmarks = NULL;
	var $dis_contact = NULL;
	var $dis_caregiver = NULL;
	var $dis_caregiver_relship = NULL;
	var $dis_client_health = NULL;
	var $dis_client_health_staff = NULL;
	var $dis_client_health_date = NULL;
	var $dis_client_psy = NULL;
	var $dis_client_psy_staff = NULL;
	var $dis_client_psy_date = NULL;
	var $dis_client_social = NULL;
	var $dis_client_social_staff = NULL;
	var $dis_client_social_date = NULL;
	var $dis_form_type = NULL;

	function CDischarge() {
		$this->CDpObject ( 'discharge_info', 'dis_id' );
	}

	function store() {
		global $AppUI;

		if (($this->dis_id) && ($this->dis_id > 0)) {

			addHistory ( 'dischargeinfo', $this->dis_id, 'update', $this->dis_id );
			$this->_action = 'updated';

			$ret = db_updateObject ( 'discharge_info', $this, 'dis_id', true );

		} else {

			$this->_action = 'added';
			$ret = db_insertObject ( 'discharge_info', $this, 'dis_id' );
			addHistory ( 'dischargeinfo', $this->dis_id, 'add', $this->dis_id );

		}

		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}
	}
}
?>
