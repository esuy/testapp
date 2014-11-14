<?php
require_once ($AppUI->getSystemClass ( 'dp' ));

/**
 * CHW Check Class
 *
 */
class CCHWCheck extends CDpObject {
	
	var $chw_id = NULL;
	var $chw_name = NULL;
	var $chw_village = NULL;
	var $chw_center_id = NULL;
	var $chw_location = NULL;
	var $chw_entry_date = NULL;
	var $chw_adm_no = NULL;	
	var $chw_old = NULL;
	var $chw_sex = NULL;
	var $chw_age = NULL;
	var $chw_hasplan = NULL;
	var $chw_arv = NULL;
	var $chw_arv_note = NULL;
	var $chw_oir = NULL;
	var $chw_oir_note = NULL;
	var $chw_tb = NULL;
	var $chw_nutrition = NULL;	
	var $chw_adh_support = NULL;
	var $chw_assess = NULL;
	var $chw_support = NULL;
	var $chw_comm_mob = NULL;
	var $chw_remarks = NULL;
	var $chw_refers = NULL;	
	var $chw_client_id = NULL;
	
	function CCHWCHECK() {
		$this->CDpObject ( 'chw_info', 'chw_id' );
	}

	function store() {
		global $AppUI;
				
		if (($this->chw_id) && ($this->chw_id > 0)) {
			
			addHistory ( 'chwinfo', $this->chw_id, 'update', $this->chw_id );
			$this->_action = 'updated';
			
			$ret = db_updateObject ( 'chw_info', $this, 'chw_id', true );
		
		} else {
			
			$this->_action = 'added';
			$ret = db_insertObject ( 'chw_info', $this, 'chw_id' );
			addHistory ( 'chwinfo', $this->chw_id, 'add', $this->chw_id );
		
		}
		
		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}
	}	
}
