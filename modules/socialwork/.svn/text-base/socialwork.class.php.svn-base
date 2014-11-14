<?php 


require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Social Work Class
 *	@todo Move the 'address' fields to a generic table
 */
class CSocialWork extends CDpObject {
/** @var int Primary Key */
	var $social_id = NULL;
/** @var string */
	var $social_client_id = NULL;

// these next fields should be ported to a generic address book
	var $social_date = NULL;
	var $social_counsellor_id = NULL;

	var $social_needs_assessment = NULL;
	var $social_supported_needs = NULL;
	var $social_food_support = NULL;
	var $social_permanency_plan = NULL;
	var $social_nurse_care = NULL;
	var $social_hospital_visit = NULL;
	var $social_home_visit = NULL;
	var $social_microfin = NULL;
	var $social_medical_support = NULL;
	var $social_transport_support = NULL;
	var $social_education_support = NULL;
	var $social_clothing = NULL;
	var $social_solidarity_support = NULL;
	var $social_rent_support = NULL;
	var $social_other_support = NULL;
	var $social_no_support = NULL;
	var $social_gender = NULL;
	var $social_notes = NULL;
	var $social_entry_date = NULL;
	var $social_custom = NULL;
	



	function CSocialWork() {
		$this->CDpObject( 'social_work', 'social_id' );
	}

// overload check
	function check() 
	{
		/*if ($this->social_id === NULL) 
		{
			return 'social id is NULL';
		}
		$this->social_id = intval( $this->social_id );*/

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
