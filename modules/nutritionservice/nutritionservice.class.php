<?php /* Nutrition Service Rendered $Id: medical.class.php,v 1.9 2010/05/04 06:30:43 istesin Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	MedicalAssessment Class
 *	
 */
class CNutritionService extends CDpObject {

var $nutrition_service_id  = NULL;
var $nutrition_service_visit_id  = NULL;
var $nutrition_service_program  = NULL;
var $nutrition_service_item  = NULL;
var $nutrition_service_qty   = NULL;
var $nutrition_service_client_id   = NULL;



	function CNutritionService() {
		$this->CDpObject( 'nutrition_service', 'nutrition_service_id' );
	}

// overload check
	function check() 
	{
	
	
		return NULL; // object is ok
	}


  
  function store()
  {
		global $AppUI;
		
		//$importing_tasks = false;
		$msg = $this->check();
		if( $msg ) 
		{
			$return_msg = array(get_class($this) . '::store-check',  'failed',  '-');
			if (is_array($msg))
				return array_merge($return_msg, $msg);
			else 
			{
				array_push($return_msg, $msg);
				return $return_msg;
			}
		}
		
		if( ($this->nutrition_service_id) && ($this->nutrition_service_visit_id > 0)) 
		{
			
			addHistory('nutrition_service', $this->nutrition_service_id, 'update', $this->nutrition_service_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'nutrition_service', $this, 'nutrition_service_id', true);


		} 
		else 
		{
		    
			$this->_action = 'added';
			$ret = db_insertObject( 'nutrition_service', $this, 'nutrition_service_id' );
			addHistory('nutrition_service', $this->nutrition_service_id, 'add', $this->nutrition_service_id);

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
}
?>
