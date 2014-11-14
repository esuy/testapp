<?php 
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );


class CClinicLocation extends CDpObject {
/** @var int Primary Key */
	var $clinic_location_id = NULL;
	var $clinic_location_clinic_id = NULL;
/** @var string */
	var $clinic_location = NULL;
	var $clinic_location_notes = null;
	//var $clinic_date_entered = NULL;
	var $clinic_location_custom = null;

	function CClinicLocation() {
		$this->CDpObject( 'clinic_location', 'clinic_location_id' );
	}

// overload check
	function check() {
		if ($this->clinic_location_id === NULL) {
			return 'clinic_location_id is NULL';
		}
		$this->clinic_location_id = intval( $this->clinic_location_id );

		return NULL; // object is ok
	}
	
	function canDelete(&$msg, $oid=NULL)
	{
			return true;
	}
}
?>
