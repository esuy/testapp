<?php 


require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Social Work Class
 *	@todo Move the 'address' fields to a generic table
 */
class CCounsellingWork extends CDpObject {
/** @var int Primary Key */
	var $counselling_id = NULL;
/** @var string */
	var $counselling_client_id = NULL;

// these next fields should be ported to a generic address book
	var $counselling_date = NULL;
	var $counselling_counsellor_id = NULL;
	var $counselling_provider_type = NULL;
	var $counselling_support_counselling = NULL;
	var $counselling_child_counselling = NULL;
	var $counselling_ind_prev_educ = NULL;
	var $counselling_adherence_counselling = NULL;
	var $counselling_ind_disc_counselling = NULL;
	var $counselling_lifeskiss_training = NULL;
	var $counselling_rec_therapy = NULL;
	var $counselling_hospital_visit = NULL;
	var $counselling_home_visit = NULL;
	var $counselling_notes = NULL;
	var $counselling_date_entered = NULL;
	var $counselling_custom = NULL;
	



	function CCounsellingWork() {
		$this->CDpObject( 'counselling_work', 'counselling_id' );
	}

// overload check
	function check() 
	{
		/*if ($this->social_id === NULL) 
		{
			return 'social id is NULL';
		}
		$this->social_id = intval( $this->social_id );*/		
		if ($this->counselling_counsellor_id === NULL) 
		{
			return 'counsellor id is NULL';
		}
		$this->counselling_counsellor_id = intval( $this->counselling_counsellor_id );

		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null ) {
		/*$tables[] = array( 'label' => 'Projects', 'name' => 'projects', 'idfield' => 'project_id', 'joinfield' => 'project_company' );
		$tables[] = array( 'label' => 'Departments', 'name' => 'departments', 'idfield' => 'dept_id', 'joinfield' => 'dept_company' );
		$tables[] = array( 'label' => 'Users', 'name' => 'users', 'idfield' => 'user_id', 'joinfield' => 'user_company' );
	// call the parent class method to assign the oid
		return CDpObject::canDelete( $msg, $oid, $tables );*/
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
}
?>
