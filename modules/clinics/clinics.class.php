<?php 
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );


class CClinic extends CDpObject {
/** @var int Primary Key */
	var $clinic_id = NULL;
/** @var string */
	var $clinic_name = NULL;

// these next fields should be ported to a generic address book
	var $clinic_phone1 = NULL;
	var $clinic_phone2 = NULL;
	var $clinic_fax = NULL;
	var $clinic_address1 = NULL;
	var $clinic_address2 = NULL;
	var $clinic_city = NULL;
	var $clinic_state = NULL;
	var $clinic_zip = NULL;
	var $clinic_email = NULL;
	

/** @var string */
	var $clinic_primary_url = NULL;
/** @var int */
	var $clinic_owner = NULL;
/** @var string */
	var $clinic_description = NULL;
/** @var int */
	var $clinic_type = null;
	//var $clinic_date_entered = NULL;
	var $clinic_custom = null;

	function CClinic() {
		$this->CDpObject( 'clinics', 'clinic_id' );
	}

// overload check
	function check() {
		if ($this->clinic_id === NULL) {
			return 'clinic id is NULL';
		}
		$this->clinic_id = intval( $this->clinic_id );

		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null ) {
		$tables[] = array( 'label' => 'Projects', 'name' => 'projects', 'idfield' => 'project_id', 'joinfield' => 'project_company' );
		$tables[] = array( 'label' => 'Departments', 'name' => 'departments', 'idfield' => 'dept_id', 'joinfield' => 'dept_company' );
		$tables[] = array( 'label' => 'Users', 'name' => 'users', 'idfield' => 'user_id', 'joinfield' => 'user_company' );
	// call the parent class method to assign the oid
		return CDpObject::canDelete( $msg, $oid, $tables );
	}
	function getContacts($type = NULL)
	{
		$contacts = NULL;
		$q = new DBQuery;
		
		if (isset($this->clinic_id))
		{
			$q->addTable('clinic_contacts');
			$q->addQuery('clinic_contacts_contact_id');
			$q->addWhere("clinic_contacts_clinic_id = $this->clinic_id");
			if ($type)
			   $q->addWhere("clinic_contacts_contact_type = $type");
			   
			$contacts = $q->loadColumn();
		}
		//if (count($contacts)==1)
		   //$contacts = $contacts[0];
		   
		return $contacts;

	}
	function getUrl($urlType='view', $companyType = NULL)
	{
		if ($clinicType == NULL) $clinicType = $this->clinic_type;
		
		
		$modules = dPgetSysVal('ClinicModules');
		$unit = $modules[$clinicType];
		$url_array = array(
		"view" => "./index.php?m=clinics&a=view&clinic_id=$this->clinic_id",
		"add" => "./index.php?m=clinics&a=addedit&clinic_type=$companyType",
		"edit"=> "./index.php?m=clinics&a=addedit&clinic_id=$this->clinic_id"
		);
		return $url_array[$urlType];
	}
	function getDescription()
	{
		static $types; 
		if (!isset($types)) 
		{
			$types = dPgetSysVal('ClinicType');
		}
		$desc = $types[$this->clinic_type];
		return $desc;
	}
	function getCount($type = NULL)
	{
				if (!empty($type))
				{
					$sql = "SELECT COUNT(*) FROM clinics WHERE clinic_type IS NOT NULL AND clinic_type = $type";
				}
				else
				{
					$sql = "SELECT COUNT(*)  FROM clinics WHERE clinic_type IS NOT NULL";
				}
		$count = db_loadResult($sql);
		return $count;
  }
}
?>
