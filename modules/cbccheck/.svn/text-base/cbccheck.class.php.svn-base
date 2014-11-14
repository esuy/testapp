<?php
require_once ($AppUI->getSystemClass ( 'dp' ));

/**
 * CBC Check Class
 *
 */
class CCBCCheck extends CDpObject {
	
	var $cbc_id = NULL;
	var $cbc_name = NULL;
	var $cbc_village = NULL;
	var $cbc_center_id = NULL;
	var $cbc_location = NULL;
	var $cbc_entry_date = NULL;
	var $cbc_adm_no = NULL;	
	var $cbc_old = NULL;
	var $cbc_sex = NULL;
	var $cbc_age = NULL;
	var $cbc_hbcare = NULL;
	var $cbc_adh_support = NULL;
	var $cbc_remarks = NULL;
	var $cbc_refers = NULL;
	var $cbc_refers_note = NULL;
	var $cbc_client_id = NULL;	
	
	
	function CCBCCheck() {
		$this->CDpObject ( 'cbc_info', 'cbc_id' );
	}

	function store() {
		global $AppUI;
				
		if (($this->cbc_id) && ($this->cbc_id > 0)) {
			
			addHistory ( 'cbcinfo', $this->cbc_id, 'update', $this->cbc_id );
			$this->_action = 'updated';
			
			$ret = db_updateObject ( 'cbc_info', $this, 'cbc_id', true );
		
		} else {
			
			$this->_action = 'added';
			$ret = db_insertObject ( 'cbc_info', $this, 'cbc_id' );
			addHistory ( 'cbcinfo', $this->cbc_id, 'add', $this->cbc_id );
		
		}
		
		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}
	}	
}
