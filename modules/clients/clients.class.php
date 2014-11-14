<?php
require_once ($AppUI->getSystemClass ( 'dp' ));
require_once ($AppUI->getSystemClass ( 'date' ));
require_once ($AppUI->getModuleClass ( 'contacts' ));
require_once ($AppUI->getModuleClass ( 'counsellinginfo' ));
require_once ($AppUI->getModuleClass ( 'social' ));

class CClient extends CDpObject {
	var $client_id = NULL;
	var $client_adm_no = NULL;
	var $client_first_name = NULL;
	var $client_other_name = NULL;
	var $client_last_name = NULL;

	var $client_status = NULL;
	var $client_notes = NULL;
	var $client_entry_date = NULL;
	var $client_dob = NULL;
	var $client_doa = NULL;
	var $client_gender = NULL;
	var $client_center = NULL;
	var $client_nhif = NULL;
	// var $client_nhif_y = NULL;
	var $client_nhif_n = NULL;
	var $client_immun = NULL;
	// var $client_immun_y = NULL;
	var $client_immun_n = NULL;
	var $client_lvd = null;
	var $client_lvd_form = null;
	var $client_obsolete = null;

	function CClient() {
		$this->CDpObject ( 'clients', 'client_id' );
	}
	function IsDeceased() {
		$clientStatus = dPgetSysVal ( "ClientStatus" );
		/*
		$q = new DBQuery ( );
		$q->addTable ( "mortality_info" );
		$q->addQuery ( "count(*)" );
		$q->addWhere ( "mortality_client_id = " . $this->client_id );
		$count = $q->loadResult ();*/

		return ((strtolower($clientStatus [$this->client_status]) === 'deceased') /*|| ($count > 0)*/);
	}

	function IsDischarged(){
		$found=false;
		$q =new DBQuery();
		$q->addTable('discharge_info');
		$q->addWhere('dis_client_id="'.$this->client_id.'"');
		$q->setLimit(1);
		$q->addQuery('1');
		$res = $q->loadResult();
		if($res == 1){
			$found=true;
		}
		return $found;
	}

	function getParts ($var){
		$res=array('bool'=>$this->{'client_'.$var},'y'=>$this->{'client_'.$var.'_y'},'n'=>$this->{'client_'.$var.'_n'});
		return $res;
	}

	function reset() {

		$this->client_id = NULL;
		$this->client_adm_no = NULL;
		$this->client_first_name = NULL;
		$this->client_other_name = NULL;
		$this->client_last_name = NULL;
		$this->client_status = NULL;
		$this->client_phone1 = NULL;
		$this->client_phone2 = NULL;
		$this->client_mobile = NULL;
		$this->client_clinic = NULL;
		$this->client_fax = NULL;
		$this->client_address1 = NULL;
		$this->client_address2 = NULL;
		$this->client_email = NULL;
		$this->client_city = NULL;
		$this->client_notes = NULL;
		$this->client_entry_date = NULL;
		$this->client_doa = NULL;
		$this->client_dob = NULL;
		$this->client_gender = null;
		$this->client_lvd = null;
		$this->client_lvd_form = null;
	}
	function check() {
		//if ($this->company_id == NULL)
		//{
		//return 'company id is null';
		//}


		//$this->company_id = intval($this->company_id);


		return NULL;
	}
	function getClinicName() {

		return $this->clinicName();
		/*$clinic = NULL;
		if (isset ( $this->client_clinic )) {
			$q = new DBQuery ( );
			$q->addTable ( 'clinics' );
			$q->addQuery ( 'clinic_name' );
			$q->addWhere ( 'clinic_id = ' . $this->client_clinic );
			$q->setLimit(1);
			$clinic = $q->loadResult ();
		}
		return $clinic;*/
	}

	function getDOB() {
		$dob = NULL;
		//check for dob from counselling record
		$q = new DBQuery ( );
		$q->addTable ( "counselling_info" );
		$q->addQuery ( "counselling_dob" );
		$q->addWhere ( 'counselling_client_id = ' . $this->client_id );
		$q->setLimit(1);
		$dob = $q->loadResult ();

		return $dob;
	}
	function canDelete(&$msg, $oid = NULL) {
		//$tables[] = array( 'label' => 'Departments', 'name' => 'departments', 'idfield' => 'dept_id', 'joinfield' => 'dept_company' );
		//$tables[] = array( 'label' => 'Users', 'name' => 'users', 'idfield' => 'user_id', 'joinfield' => 'user_company' );
		//return CDpObject::canDelete( $msg, $oid, $tables );
		return true;
	}
	function store() {
		global $AppUI;

		//$importing_tasks = false;
		$msg = $this->check ();
		if ($msg) {
			$return_msg = array (get_class ( $this ) . '::store-check', 'failed', '-' );
			if (is_array ( $msg ))
				return array_merge ( $return_msg, $msg );
			else {
				array_push ( $return_msg, $msg );
				return $return_msg;
			}
		}

		if (($this->client_id) && ($this->client_id > 0)) {

			addHistory ( 'client', $this->client_id, 'update', $this->client_last_name );
			$this->_action = 'updated';

			$ret = db_updateObject ( 'clients', $this, 'client_id', true );
		} else {

			$this->_action = 'added';
			$ret = db_insertObject ( 'clients', $this, 'client_id' );
			if((int)$_POST['counselling']['counselling_clinic'] > 0 && $this->client_id > 0){
				$this->client_adm_no=(int)$_POST['counselling']['counselling_clinic'].'-'.$this->client_id;
				$ret = db_updateObject ( 'clients', $this, 'client_id', true );
			}
			addHistory ( 'clients', $this->client_id, 'add'/*, $this->company_name*/ );
		}

		if (! $ret) {
			return get_class ( $this ) . "::store failed <br />" . db_error ();
		} else {
			return NULL;
		}

	}
	function getContacts($type = NULL) {
		$contacts = NULL;
		$q = new DBQuery ( );

		if (isset ( $this->company_id )) {
			$q->addTable ( 'client_contacts' );
			$q->addQuery ( 'client_contacts_contact_id' );
			$q->addWhere ( "client_contacts_client_id = $this->client_id" );
			if ($type)
				$q->addWhere ( "company_contacts_contact_type = $type" );

			$contacts = $q->loadColumn ();
		}
		//if (count($contacts)==1)
		//$contacts = $contacts[0];


		return $contacts;

	}
	function delete() {
		// finally delete the company
		$sql = "DELETE FROM clients WHERE client_id = $this->client_id";
		if (! db_exec ( $sql )) {
			return db_error ();
		} else
			$this->_action = 'deleted';

		return NULL;
	}
	function getAge(&$years, &$months) {
		$age = 0;
		//load counselling obj
		$q = new DBQuery ( );
		$q->addTable ( 'counselling_info' );
		$q->addQuery ( 'counselling_info.*' );
		$q->addWhere ( 'counselling_info.counselling_client_id = ' . $this->client_id );

		if ($rows = $q->loadList ()) {
			foreach ( $rows as $row ) {
				$counsellingObj = new CCounsellingInfo ( );
				$counsellingObj->load ( $row ["counselling_id"] );
			}
		}
		$dob = intval ( $counsellingObj->counselling_dob ) ? new CDate ( $counsellingObj->counselling_dob ) : null;
		if (! empty ( $dob )) {
			$refdate = new CDate ( date ( 'Y-m-d' ) );
			$age = $refdate->dateDiff ( $dob );
		}
		$years = intval ( $age / 365 );
		$months = intval ( ($age / 365 - $years) * 12 );
	}

	function getUrl($urlType = 'view', $clientType = NULL) {
		if ($clientType == NULL)
			$clientType = $this->client_type;

		$modules = dPgetSysVal ( 'ClientModules' );
		$unit = $modules [$companyType];
		$url_array = array ("view" => "./index.php?m=clients&a=view&client_id=$this->client_id", "add" => "./index.php?m=clients&&a=addedit", "edit" => "./index.php?m=clients&a=addedit&client_id=$this->client_id" );
		return $url_array [$urlType];
	}

	function getConvertOptions() {
		$modules = dPgetSysVal ( 'CompanyModules' );
		$types = dPgetSysVal ( 'CompanyType' );

		unset ( $types [$this->company_type] );
		unset ( $types [0] );
		unset ( $modules [$this->company_type] );
		unset ( $modules [5] );

		//print_r($modules);
		//print_r($types);
		//print "<br/>";


		foreach ( $modules as $key => $value ) {
			$convertOptions [$value] = "Convert this client to a $types[$key] client";
		}
		return $convertOptions;
	}

	function checkClientOnBS() {

		//check if client is on bs
		$retval = false;

		$q = new DBQuery ( );
		$q->addTable ( "company_building_solution" );
		$q->addQuery ( "count(*)" );
		$q->addWhere ( "company_bs_company_id = $this->company_id" );

		$num = intval ( $q->loadResult () );
		if ($num > 0)
			$retval = true;

		$q->clear ();

		return $retval;
	}

	function getDescription() {

		static $types;
		if (! isset ( $types )) {
			$types = dPgetSysVal ( 'ClientStatus' );
		}
		if($this->client_status != 9 && $this->client_status > 0){
			$desc = $types [1];
			$q = new DBQuery ( );
			$q->addTable ( "social_visit" );
			//$q->addQuery ( "*" );
			$q->addQuery('social_client_status');
			$q->addOrder ( "social_entry_date DESC" );
			$q->setLimit ( 1 );
			$q->addWhere ( "social_client_id = " . $this->client_id );

			if ($rows = $q->loadList ()) {

				/*foreach ( $rows as $row ) {
				$q= new DBQuery();
				$q->addTable('social_visit');
				$q->setLimit(1);
				$q->addWhere('social_client_id="'.$row['social_id'].'"');
				$q->addQuery('social_client_status');
				$socialObj = new CSocialVisit ( );
				$socialObj->load ( $row ["social_id"] );
				}*/
				$row=$rows[0];
				$desc = $types [$row['social_client_status']];
			}
		}else{
			$desc = $types[$this->client_status];
		}
		return $desc;
	}

	function getBuildingSolutionClients() {
		$q = new DBQuery ( );
		$q->addTable ( "company_building_solution" );
		$q->addQuery ( "DISTINCT company_bs_company_id" );
		$retval = $q->loadColumn ();

		$q->clear ();
		return $retval;

	}
	function getNoNetscreenClients() {
		$q = new DBQuery ( );
		$q->addTable ( "blue_term" );
		$q->addQuery ( "DISTINCT term_equip_company_id" );
		$q->addWhere ( "term_equip_netscreen_leased = 0" );
		$retval = $q->loadColumn ();

		$q->clear ();
		return $retval;

	}
	function getStatus() {
		/*$q = new DBQuery;
	   $q->addTable ("company_status");
	   $q->addQuery("company_status_desc");
	   $q->addWhere("company_status_id = " . $this->company_status);
	   $retval = $q->loadResult();*/
		return $retval;
	}
	function getPriority() {
		$retval = 'Not defined';
		if (isset ( $this->company_priority )) {
			$q = new DBQuery ( );
			$q->addTable ( "company_priority" );
			$q->addQuery ( "company_priority_desc" );
			$q->addWhere ( "company_priority_id = " . $this->company_priority );
			$retval = $q->loadResult ();
		}
		return $retval;

	}
	function getCount($user_type = NULL, $user_id = NULL, $type = NULL, $options = NULL) {
		global $AppUI,$_SESSION,$dPconfig;
		static $types;
		if (! isset ( $types )) {
			$types = dPgetSysVal ( 'ClientStatus' );
		}


		if(!isset($_SESSION['aclinics'])){
			$sql='SELECT clinic_id FROM clinics';
			$res=mysql_query($sql);
			if($res){
				$als=array();
				while ($row=mysql_fetch_row($res)) {
					$als[]=$row[0];
				}
				$_SESSION['aclinics']=implode(',',$als);
				mysql_free_result($res);
			}
		}
		if ($user_type == NULL)
			$user_type = $AppUI->user_type;
		if ($user_id == NULL)
			$user_id = $AppUI->user_id;

		$left='';

		$sql = "SELECT COUNT(distinct clients.client_id) FROM clients  ";//INNER JOIN counselling_info ci on ci.counselling_client_id = c.client_id

		/*$sql .= " LEFT JOIN  social_visit sv ON sv.social_client_id = c.client_id " .
		" WHERE (sv.social_id IS NULL OR sv.social_id IN ( SELECT svi.social_id " .
		" FROM social_visit svi INNER JOIN " .
		"(SELECT social_client_id, MAX( social_entry_date ) AS social_max_date " .
		" FROM social_visit GROUP BY social_client_id  )" .
		" AS s2 ON svi.social_client_id = s2.social_client_id and svi.social_entry_date = s2.social_max_date)) ";
		*/
		//$where = "WHERE (sv.social_client_status = 1 OR sv.social_client_status IS NULL)";
		$whera=array();
		if (($type > 0) && ($type < 98)) {
			//~~~~~~$where .= " AND ci.counselling_clinic = \"".$type."\" AND (sv.social_client_status = 1 OR sv.social_client_status IS NULL)";
			//$where .= " WHERE ci.counselling_clinic = $type ";
			//$where .= " AND ci.counselling_clinic = \"".$type."\" AND (c.client_status IS NULL OR c.client_status <> '9')";
			//$whera[] = " c.client_center = \"".$type."\" AND (c.client_status IS NULL OR c.client_status <> '9')";
			//$whera[] = " c.client_center = \"".$type."\" AND (c.client_status IN (1,11) )";//IS NULL OR c.client_status <> '9'

			$whera[] = " client_center = \"".$type."\"".
					($dPconfig['regular_definition'] != '' ?
					"AND ".$dPconfig['regular_definition'] : '');//IS NULL OR c.client_status <> '9'
		}
		if ($type === NULL) {
			//$where .= " AND  (ci.counselling_clinic = 0 OR ci.counselling_clinic IS NULL ) ";
			//$where .= " AND  (ci.counselling_clinic NOT IN (".$_SESSION['aclinics'].") OR ci.counselling_clinic IS NULL )";
			//$whera[]= "   (c.client_center NOT IN (".$_SESSION['aclinics'].") OR c.client_center IS NULL )";

			$whera[]= "   (client_center NOT IN (SELECT CONCAT_WS(',', clinic_id) FROM clinics) OR client_center IS NULL )";
			$whera[]= " client_status <> '9'";

			//$where .= " WHERE  (ci.counselling_clinic NOT IN (SELECT CONCAT_WS(',', clinic_id) FROM clinics)) OR ci.counselling_clinic IS NULL ";
			//$q->addWhere ( '(c.client_center NOT IN ( SELECT CONCAT_WS(",", clinic_id) FROM clinics)) OR c.client_center IS NULL ' );
			//$q->addWhere('c.client_status <> "9"');
		}

		if ($type == 98) {
			//$where .= " AND  (ci.counselling_clinic = 0 OR ci.counselling_clinic IS NULL ) ";
			//~~~~$where .= " AND  (sv.social_client_status <> 1 AND sv.social_client_status IS NOT NULL ) ";
			//$where .= " WHERE  (sv.social_client_status <> 1 AND sv.social_client_status IS NOT NULL ) ";
			$whera[]=' (client_status = "7" AND client_status IS NOT NULL)';
		}
		if ($type == 99) {

			//$where .= " AND  (ci.counselling_clinic = 0 OR ci.counselling_clinic IS NULL ) ";
			//~~~~$where .= " AND  (sv.social_client_status <> 1 AND sv.social_client_status IS NOT NULL ) ";
			//$where .= " WHERE  (sv.social_client_status <> 1 AND sv.social_client_status IS NOT NULL ) ";

			//$whera[]=' ( client_status IS NOT NULL  AND client_status  NOT IN (1,7,9,11))';
			if($dPconfig['regular_definition']!= ''){

				$q = new DBQuery();
				$q->addTable('clients');
				$q->addQuery('client_id');
				$q->addWhere($dPconfig['regular_definition']);
				$actives = $q->loadColumn();

				if(count($actives) > 0){
					$whera[] = ' client_id not in ('.join(',',$actives).')';
				}

			}

		}
		if($type == 100){
			//case for vct only tab
			$whera[]='  client_status="9"';
		}

		if ($user_type != 1) //not admin user type
{
			//get allowed clinics if user is not an admin
			$q = new DBQuery ( );
			$q->addTable ( "users" );
			$q->addQuery ( "users.user_clinics" );
			$q->addWhere ( "users.user_id = " . $user_id );
			$allowedClinics = $q->loadHashList ();

		}

		if ((count ( $allowedClinics ) > 0) && ($allowedClinics [0] != NULL)) {
			$whera[] = ' client_clinic IN (' . implode ( ',', array_keys ( $allowedClinics ) ) . ')';
		}
		$sql .= (count($whera) > 0  ? 'WHERE '.join(' AND ',$whera) : '');
		//print($sql);
		$count = db_loadResult ( $sql );
		return $count;
	}
	function getFullname() {
		$curr_client_name = $this->client_first_name . " " . $this->client_other_name . " " . $this->client_last_name;
		return $curr_client_name;
	}

	function clinicName() {
		$q = new DBQuery ( );
		$q->addTable ( 'admission_info' );
		$q->addWhere ( 'admission_client_id = "' . ( int ) $this->client_id . '"' );
		$q->addQuery ( 'admission_clinic_id' );
		$q->setLimit(1);
		$thisClientClinic = $q->loadResult ();

		//load centers
		if (( int ) $thisClientClinic > 0) {
			$q = new DBQuery ( );
			$q->addTable ( 'clinics', 'c' );
			$q->addQuery ( 'c.clinic_name' );
			$q->addWhere ( 'clinic_id=' . $thisClientClinic );
			$q->setLimit(1);
			$clinicName = $q->loadResult (); //$q->loadHashList ();
		}

		return $clinicName;
	}

	function age(){
		return digiAge($this->getDOB());
	}

}

?>