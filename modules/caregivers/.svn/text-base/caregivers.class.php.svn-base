<?php /* CAREGIVER $Id: caregivers.class.php,v 1.11.4.1 2005/09/14 18:03:24 pedroix Exp $ */

/**
* Caregiver Class
*/
class CCaregiver extends CDpObject {


	var $caregiver_id = NULL;
	var $caregiver_client_id = NULL;
	var $caregiver_entry_date = NULL;
	var $caregiver_client_caregiver_type = NULL;
	var $caregiver_fname = NULL;
	var $caregiver_lname = NULL;
	var $caregiver_age = NULL;
	var $caregiver_status = NULL;
	var $caregiver_health_status = NULL;
	var $caregiver_raising_child = NULL;
	var $caregiver_marital_status = NULL;
	var $caregiver_educ_level = NULL;
	var $caregiver_employment = NULL;
	var $caregiver_income = NULL;
	var $caregiver_idno = NULL;
	var $caregiver_mobile = NULL;
	var $caregiver_notes = NULL;
	var $caregiver_custom = NULL;
	var $caregiver_role = null;
	var $caregiver_active = null;
	var $caregiver_off = null; 

	function CCaregiver() 
	{
		$this->CDpObject( 'caregiver_client', 'caregiver_id' );
	}

	function check() {
		/*if ($this->caregiver_id === NULL) {
			return 'caregiver id is NULL';
		}*/
		// TODO MORE
		return NULL; // object is ok
	}

	function store() 
	{
		$msg = $this->check();
		if( $msg ) 
		{
			return get_class( $this )."::store-check failed";
		}
		$q  = new DBQuery;
		if( $this->caregiver_id ) 
		{
		
			$ret = db_updateObject( 'caregiver_client', $this, 'caregiver_id', false );
		} 
		else 
		{
			$ret = db_insertObject( 'caregiver_client', $this, 'caregiver_id' );
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

	function delete() {

		
		return $result;
 	}
	
	function getRoles($client_id)
	{
			$q = new DBQuery();
			$q->addTable('caregiver_client');
			$q->addQuery('caregiver_client_client_id');
			$q->addQuery('caregiver_client_caregiver_type');
			$q->addWhere('caregiver_client_client_id = ' . $client_id);
			return $q->loadHashList();
		
	}
	
	function getDependents()
	{
			$q = new DBQuery();
			$q->addTable('caregiver_client');
			$q->addQuery('caregiver_client_client_id');
			$q->addQuery('caregiver_client_caregiver_type');
			return $q->loadHashList();
	}
	
	function getDependentCount()
	{
	   return count($this->getDependents());
	}
}

?>