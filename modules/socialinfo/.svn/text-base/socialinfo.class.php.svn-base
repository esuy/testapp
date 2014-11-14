<?php 


require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Social Info Class
 *	@todo Move the 'address' fields to a generic table
 */
class CSocialInfo extends CDpObject {
/** @var int Primary Key */
	var $social_id = NULL;
/** @var string */
	var $social_client_id = NULL;

// these next fields should be ported to a generic address book
	var $social_chw_contact = NULL;
	var $social_shw_contact = NULL;
	var $social_entry_date = NULL;
	var $social_total_orphan = NULL;
	var $social_risk_level = NULL;
	var $social_notes = NULL;
	var $social_custom = NULL;
	



	function CSocialInfo() {
		$this->CDpObject( 'social_info', 'social_id' );
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
