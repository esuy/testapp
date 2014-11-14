<?php /* NUTRITION VISIT $Id: counselling.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Nutrition Visit Class
 *
 */
class CNutritionVisit extends CDpObject
{

var $nutrition_id  = NULL;
var $nutrition_client_id  = NULL;
var $nutrition_staff_id  = NULL;
var $nutrition_entry_date  = NULL;
var $nutrition_center  = NULL;
var $nutrition_gender  = NULL;
var $nutrition_age_yrs  = NULL;
var $nutrition_age_months  = NULL;
var $nutrition_age_status  = NULL;
var $nutrition_caregiver_type  = NULL;
var $nutrition_caregiver_type_notes  = NULL;
var $nutrition_weight  = NULL;
var $nutrition_height  = NULL;
var $nutrition_zscore  = NULL;
var $nutrition_muac  = NULL;
var $nutrition_wfh  = NULL;
var $nutrition_wfa  = NULL;
var $nutrition_bmi  = NULL;
var $nutrition_child_attend = NULL;
var $nutrition_care_attend = NULL;
var $nutrition_care_who = NULL;

var $nutrition_blacktea = NULL;
var $nutrition_whitetea = NULL;
var $nutrition_bread = NULL;
var $nutrition_porridge = NULL;
var $nutrition_milk = NULL;
var $nutrition_breastfeeding = NULL;
var $nutrition_formula_milk = NULL;
var $nutrition_eggs = NULL;
var $nutrition_meat = NULL;
var $nutrition_carbohydrates = NULL;
var $nutrition_legumes = NULL;
var $nutrition_pancake = NULL;
var $nutrition_vegetables = NULL;
var $nutrition_fruit = NULL;
var $nutrition_diet_history_notes = NULL;
var $nutrition_diet_history_others = NULL;
var $nutrition_food_enrichment = NULL;
var $nutrition_water_access = NULL;
var $nutrition_water_purification = NULL;
var $nutrition_water_purification_notes = NULL;
var $nutrition_food_enrichment_notes = NULL;
var $nutrition_quantity  = NULL;
var $nutrition_quality  = NULL;
var $nutrition_poor_preparation  = NULL;
var $nutrition_mixed_feeding = NULL;
var $nutrition_unclean_drinking_water  = NULL;

var $nutrition_education  = NULL;
var $nutrition_counselling  = NULL;
var $nutrition_demonstration  = NULL;
var $nutrition_dietary_supplement  = NULL;
var $nutrition_nan  = NULL;
var $nutrition_unimix  = NULL;
var $nutrition_harvest_pro  = NULL;
var $nutrition_wfp  = NULL;
var $nutrition_insta  = NULL;
var $nutrition_rutf  = NULL;
var $nutrition_other  = NULL;
var $nutrition_other_service  = NULL;
var $nutrition_service_other  = NULL;
var $nutrition_notes  = NULL;
var $nutrition_custom  = NULL;

var $nutrition_oedema = NULL;
var $nutrition_water = NULL;
var $nutrition_beverages_title = NULL;
var $nutrition_beverages_notes = NULL;
var $nutrition_ugali = NULL;
var $nutrition_rice = NULL;
var $nutrition_banan = NULL;
var $nutrition_tubers = NULL;
var $nutrition_wheat = NULL;
var $nutrition_carbos_title = NULL;
var $nutrition_carbos_notes = NULL;
var $nutrition_protein_title = NULL;
var $nutrition_protein_notes = NULL;
var $nutrition_fat = NULL;
var $nutrition_issue_notes = NULL;

/*var $nutrition_flours = null;
var $butrition_sfp = NULL;
var $butrition_fbp = NULL;*/
var $nutrition_program = NULL;
var $nutrition_program_other = NULL;
var $nutrition_rendered = NULL;
var $nutrition_next_visit = NULL;
var $nutrition_refer = NULL;
var $nutrition_refer_other = NULL;

	function CNutritionVisit() {
		$this->CDpObject( 'nutrition_visit', 'nutrition_id' );
	}

// overload check
	function check()
	{
		/*if ($this->counselling_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->counselling_id = intval( $this->counselling_id );*/



		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null )
	{

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
	function getUrl($urlType='view', $companyType = NULL)
	{
		if ($companyType == NULL) $companyType = $this->company_type;


		$modules = dPgetSysVal('CompanyModules');
		$unit = $modules[$companyType];
		$url_array = array(
		"view" => "./index.php?m=counselling&a=view&company_id=$this->company_id",
		"add" => "./index.php?m=counselling&a=addedit&company_type=$companyType",
		"edit"=> "./index.php?m=counselling&a=addedit&company_id=$this->company_id"
		);
		return $url_array[$urlType];
	}
	function getDescription()
	{
		static $types;
		if (!isset($types))
		{
			$types = dPgetSysVal('CompanyType');
		}
		$desc = $types[$this->company_type];
		return $desc;
	}
	function getCount($type = NULL)
	{
				if (!empty($type))
				{
					$sql = "SELECT COUNT(*) FROM companies WHERE company_type IS NOT NULL AND company_type = $type";
				}
				else
				{
					$sql = "SELECT COUNT(*)  FROM companies WHERE company_type IS NOT NULL";
				}
		$count = db_loadResult($sql);
		return $count;
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

		if( ($this->nutrition_id) && ($this->nutrition_id > 0))
		{

			addHistory('nutritionvisit', $this->nutrition_id, 'update', $this->nutrition_id);
			$this->_action = 'updated';

			$ret = db_updateObject( 'nutrition_visit', $this, 'nutrition_id', true );


		}
		else
		{

			$this->_action = 'added';
			$ret = db_insertObject( 'nutrition_visit', $this, 'nutrition_id' );
			addHistory('nutritionvisit', $this->nutrition_id, 'add', $this->nutrition_id);

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
