<?php /* FILES $Id: do_file_co.php,v 1.4 2005/03/29 00:01:18 ajdonnison Exp $ */
require_once( "$baseDir/lib/Excel/Reader.php" ) ;

//addfile sql
$file_id = intval( dPgetParam( $_POST, 'file_id', 0 ) );

$obj = new CFile();
if ($file_id) { 
	$obj->_message = 'updated';
	$oldObj = new CFile();
	$oldObj->load( $file_id );

} 
else 
{
	$obj->_message = 'added';
}
//open spreadsheet reader
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('CP1251');

if ($file_id)
   $data->read("$baseDir/files/$oldObj->file_real_filename");

if (!$obj->bind( $_POST )) 
{
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

// prepare (and translate) the module name ready for the suffix
//$AppUI->setMsg( 'File' );

set_time_limit( 600 );
ignore_user_abort( 1 );

//loop through each worksheet and move to staging table
$tableArray = array(0=>'clinics_staging',1=>'clients_staging',2=>'counselling_staging',3=>'clinical_visits_staging',
4=>'counselling_visit_staging',5=>'social_staging',6=>'social_services_staging',7=>'nutrition_staging',8=>'medical_staging',9=>'admission_staging',
10=>'household_staging',11=>'medical_history_staging',12=>'medications_history_staging',13=>'mortality_staging',14=>'contacts_staging',15=>'clinic_location_staging',
16=>'activities_staging',17=>'training_staging',18=>'activity_facilitator_staging',19=>'activity_caregivers_staging',20=>'activity_clients_staging',21=>'activity_staff_staging');

//clear the tables
foreach ($tableArray as $table)
{
    $sql = "DELETE FROM "  . $table;
	db_exec($sql);
}
//get headers for this worksheet

$sheetcount = 0;
for ($sheetcount=0; $sheetcount < count($data->sheets);$sheetcount++)
{
		$headers = NULL;
		for ($h = 1; $h <= $data->sheets[$sheetcount]['numCols']; $h++) 
		{
			$headers[] = $data->sheets[$sheetcount]['cells'][1][$h];
			//var_dump($headers);
		}
		//insert into staging tables
		for ($i = 2; $i <= $data->sheets[$sheetcount]['numRows']; $i++) 
		{
		
			$w = 'INSERT INTO '. $tableArray[$sheetcount]. ' (' . implode($headers,",") . ') VALUES (';
			for ($j = 1; $j <= $data->sheets[$sheetcount]['numCols']; $j++) 
			{
				if (!empty($data->sheets[$sheetcount]['cells'][$i][$j]))
					$w.= "'".addslashes($data->sheets[$sheetcount]['cells'][$i][$j])."',";
				else
				     $w.= 'NULL,';
			}
			
			//check if we have a comma at the end
			if ( $w[strlen($w) - 1]  == ",")
				$w = substr($w, 0, -1);
			$w .= ");";
			//echo $w;	
			db_exec($w);
			$count += db_exec('SELECT ROW_COUNT()');
			
		}
		
}
$AppUI->setMsg("$count Records moved to the various staging tables");
//move from staging to live tables -- starting with clinics then clients
$q = new DBQuery();
$q->addTable("clinics_staging");
$q->addQuery("clinic_name");
$q->addWhere("clinic_name NOT IN (SELECT concat_ws(',',clinic_name) FROM clinics)");

$new_clinics  = $q->loadColumn();
//var_dump($new_clinics);
//$new_clinics  = array_values($new_clinics);

//var_dump($new_clinics);
//insert new clinics
foreach ($new_clinics as $new_clinic)
{
	$q = new DBQuery();
	$q->addTable("clinics");
	$q->addInsert("clinic_name", $new_clinic);
	//$sql = $q->prepare();
	//print $sql;
	$q->exec();
}

//update staging tables

//get new list of clinics
$q = new DBQuery();
$q->addTable("clinics");
$q->addQuery("clinic_name,clinic_id ");
$new_clinics  = $q->loadHashList();

//update staging tables
$stagingtableArray = array('counselling_clinic'=>'counselling_staging','clinical_clinic_id'=>'clinical_visits_staging','counselling_center_id'=>'counselling_visit_staging','social_clinic_id'=>'social_staging','nutrition_center'=>'nutrition_staging','medical_clinic_id'=>'medical_staging','admission_clinic_id'=>'admission_staging','mortality_clinic_id'=>'mortality_staging','clinic_id'=>'clinics_staging','clinic_location_clinic_id'=>'clinic_location_staging');
foreach ($new_clinics as $clinic_name => $clinic_id)
{
	foreach($stagingtableArray as $field => $table)
	{
		$w = "UPDATE $table  SET  $field  = $clinic_id WHERE clinic_name = '$clinic_name'" ;
		//print "updating clinics";
		//print $w;
		db_exec($w);
	}
}
//exit;

//insert all staff
$q = new DBQuery();
$q->addTable("contacts_staging");
$q->addQuery("contact_first_name, contact_other_name, contact_last_name, contact_title, contact_job, contact_type, contact_email, contact_email2,  contact_phone, contact_phone2, contact_fax, contact_mobile, contact_address1, contact_address2, contact_city, contact_state, contact_zip, contact_country, contact_notes,  contact_icon");
$q->addWhere("concat(contact_first_name, contact_last_name) NOT IN (SELECT concat_ws(',',concat(contact_first_name, contact_last_name)) FROM contacts)");
$sql = $q->prepare();
//var_dump($sql);
$new_contacts  = $q->loadList();
//var_dump($new_contacts);

$fields = array("contact_first_name", "contact_other_name", "contact_last_name", "contact_title",  "contact_job", "contact_type", "contact_email", "contact_email2",  "contact_phone", "contact_phone2", "contact_fax", "contact_mobile", "contact_address1", "contact_address2", "contact_city", "contact_state", "contact_zip", "contact_country", "contact_notes", "contact_icon");
//insert new staff
foreach ($new_contacts as $new_contact)
{


	$q = new DBQuery();
	$q->addTable("contacts");
	for ($count=0; $count<count($new_contact);$count++)
	{
		//echo $count;
		$q->addInsert($fields[$count], $new_contact[$fields[$count]]);
	}
	$sql = $q->prepare();
	//print "adding new staff";
	//print($sql);

	$q->exec();
	
}

//get new list of staff except for admin
$q = new DBQuery();
$q->addTable("contacts");
$q->addQuery("concat_ws(' ', contact_first_name, contact_other_name, contact_last_name),contact_id ");
$q->addWhere("contact_id <> 1 AND contact_last_name IS NOT NULL");
$new_staff  = $q->loadHashList();

//update staging tables
$staffstagingtableArray = array('counselling_staging'=>array('counselling_staff_id'),'clinical_visits_staging'=>array('clinical_staff_id','clinical_referral'),'counselling_visit_staging'=>array('counselling_staff_id'),'social_staging'=>array('social_staff_id'),'nutrition_staging'=>array('nutrition_staff_id'),'medical_staging'=>array('medical_staff_id','medical_referral'),'admission_staging'=>array('admission_staff_id'));

$staffNameArray = array('counselling_staging'=>array('counselling_staff_name'),'clinical_visits_staging'=>array('clinical_staff_name', 'clinical_referral_name'),'counselling_visit_staging'=>array('counselling_visit_staff_name'),'social_staging'=>array('social_staff_name'),'nutrition_staging'=>array('nutrition_staff_name'),'medical_staging'=>array('medical_staff_name','medical_referral_name'),'admission_staging'=>array('admission_staff_name'));

$staff_keys = array_values($staffstagingtableArray);
$staff_names = array_values($staffNameArray);

$tableNames = array_keys($staffNameArray);

foreach ($new_staff as $staff_name => $staff_id)
{
	for($count=0;$count<count($staff_keys);$count++)
	{
		for ($fieldcount =0;$fieldcount<count($staff_keys[$count]);$fieldcount++)
		{
		
			$sql = "UPDATE $tableNames[$count]  SET  " . $staff_keys[$count][$fieldcount] ." = '" . $staff_id . "' WHERE " . addslashes($staff_names[$count][$fieldcount])." = '" . addslashes($staff_name) ."'" ;
			db_exec($sql);
		}
	}
}
//insert all missing clients
$q = new DBQuery();
$q->addTable("clients_staging");
$q->addQuery("client_adm_no, client_first_name, client_last_name, client_entry_date,client_status");
//$q->addWhere("client_adm_no NOT IN (SELECT concat_ws(',',client_adm_no) FROM clients)");
$q->addWhere("concat(trim(client_first_name), trim(client_last_name)) NOT IN (SELECT concat_ws(',',concat(trim(client_first_name), trim(client_last_name))) FROM clients)");
$sql = $q->prepare();
//print "fetching new clients not added";
//print $sql;
//exit;
$new_clients  = $q->loadArrayList();


$fields = array("client_adm_no", "client_first_name", "client_last_name", "client_entry_date", "client_status");
//insert new clients
foreach ($new_clients as $new_client)
{
	$q = new DBQuery();
	$q->addTable("clients");
	for ($count=0; $count<count($new_client);$count++)
	{
		//echo $count;
		if (!empty($new_client[$count]))
		{
			$q->addInsert($fields[$count], $new_client[$count]);
		}
	}
	$sql = $q->prepare();
	
	//echo $sql . '<br/>';
	$q->exec();
}


//exit;
//update staging tables


//get all new clients
$q = new DBQuery();
$q->addTable("clients_staging");
$q->addQuery("client_adm_no");
$sql = $q->prepare();
//print "fetching new clients not added";
//print $sql;
$new_clients  = $q->loadArrayList();

//get new list of clients
$q = new DBQuery();
$q->addTable("clients");
$q->addQuery("client_adm_no,client_id ");
$all_clients  = $q->loadHashList();
//var_dump($new_clients);
//update staging tables
//add records in db
$importArray = array ('counselling_info'=>'counselling_client_id,counselling_entry_date,counselling_clinic,counselling_staff_id,counselling_referral_source,counselling_total_orphan,counselling_dob,counselling_age_yrs,counselling_age_months,counselling_age_status,counselling_place_of_birth,counselling_birth_area,counselling_mode_birth,counselling_gestation_period,counselling_birth_weight,counselling_mothers_status_known,counselling_mother_antenatal,counselling_mother_pmtct,counselling_mother_illness_pregnancy,counselling_mother_illness_pregnancy_notes,counselling_breastfeeding,counselling_breastfeeding_duration,counselling_other_breastfeeding_duration,counselling_child_prenatal,counselling_child_single_nvp,counselling_child_nvp_date,counselling_child_nvp_notes,counselling_child_azt,counselling_child_azt_date,counselling_no_doses,counselling_mother_treatment,counselling_mother_art_pregnancy,counselling_mother_date_art,counselling_mother_cd4,counselling_mother_date_cd4,counselling_determine_date,counselling_determine,counselling_bioline_date,counselling_bioline,counselling_unigold_date,counselling_unigold,counselling_elisa_date,counselling_elisa,counselling_pcr1_date,counselling_pcr1,counselling_pcr2_date,counselling_pcr2,counselling_rapid12_date,counselling_rapid12,counselling_rapid18_date,counselling_rapid18,counselling_other_date,counselling_other,counselling_notes',
'clinical_visits'=>'clinical_client_id,clinical_entry_date,clinical_clinic_id,clinical_staff_id,clinical_age_yrs,clinical_age_months,clinical_child_attending,clinical_caregiver_attending,clinical_caregiver,clinical_illness,clinical_illness_notes,clinical_diarrhoea,clinical_vomiting,clinical_current_complaints,clinical_bloodtest_date,clinical_bloodtest_cd4,clinical_bloodtest_cd4_percentage,clinical_bloodtest_viral,clinical_bloodtest_hb,clinical_xray_results,clinical_other_results,clinical_weight,clinical_height,clinical_zscore,clinical_muac,clinical_hc,clinical_child_unwell,clinical_temp,clinical_resp_rate,clinical_heart_rate,clinical_general,clinical_pallor,clinical_jaundice,clinical_examination_dehydration,clinical_examination_lymph,clinical_mouth,clinical_teeth,clinical_ears,clinical_chest,clinical_chest_clear,clinical_skin_clear,clinical_cardiovascular,clinical_skin,clinical_clubbing,clinical_abdomen,clinical_neurodevt,clinical_musculoskeletal,clinical_oedema,clinical_adherence,clinical_adherence_notes,clinical_child_condition,clinical_diarrhoea_type,clinical_dehydration,clinical_pneumonia,clinical_chronic_lung,clinical_lung_disease,clinical_tb,clinical_tb_treatment_date,clinical_pulmonary,clinical_discharging_ears,clinical_other_diagnoses,clinical_malnutrition,clinical_growth,clinical_assessment_notes,clinical_investigations,clinical_investigations_blood,clinical_investigations_xray,clinical_investigations_notes,clinical_other_drugs,clinical_new_drugs,clinical_on_arvs,clinical_arv_drugs,clinical_tb_treatment,clinical_arv_notes,clinical_who_stage,clinical_who_current,clinical_who_reason,clinical_tb_drugs,clinical_tb_drugs_notes,clinical_septrin,clinical_vitamins,clinical_treatment_status,clinical_arv_reason,clinical_nutritional_support,clinical_nutritional_notes,clinical_referral,clinical_next_date,clinical_notes',
'counselling_visit'=>'counselling_client_id,counselling_staff_id,counselling_center_id,counselling_entry_date,counselling_visit_type,counselling_caregiver_fname,counselling_caregiver_lname,counselling_caregiver_age,counselling_caregiver_relationship,counselling_caregiver_marital_status,counselling_caregiver_educ_level,counselling_caregiver_employment,counselling_caregiver_income_level,counselling_caregiver_idno,counselling_caregiver_mobile,counselling_caregiver_residence,counselling_child_issues,counselling_other_issues,counselling_caregiver_issues,counselling_caregiver_other_issues,counselling_caregiver_issues2,counselling_caregiver_other_issues2,counselling_child_knows_status,counselling_otheradult_knows_status,counselling_disclosure_response,counselling_disclosure_state,counselling_secondary_caregiver_knows,counselling_primary_caregiver_tested,counselling_father_status,counselling_mother_status,counselling_caregiver_status,counselling_father_treatment,counselling_mother_treatment,counselling_caregiver_treatment,counselling_stigmatization_concern,counselling_counselling_services,counselling_other_services,counselling_notes', 
'social_visit'=>'social_client_id,social_staff_id,social_clinic_id,social_entry_date,social_client_status,social_visit_type,social_death,social_death_notes,social_death_date,social_caregiver_change,social_caregiver_change_notes,social_caregiver_fname,social_caregiver_lname,social_caregiver_age,social_caregiver_status,social_caregiver_relationship,social_caregiver_education,social_caregiver_employment,social_caregiver_income,social_caregiver_idno,social_caregiver_mobile,social_caregiver_health,social_caregiver_health_child_impact,social_residence_mobile,social_residence,social_caregiver_employment_change,social_caregiver_new_employment,social_caregiver_new_employment_desc,social_caregiver_new_income,social_school_attendance,social_school,social_reason_not_attending,social_relocation,social_iga,social_placement,social_succession_planning,social_legal,social_nursing,social_transport,social_education,social_food,social_rent,social_solidarity,social_direct_support,social_medical_support,social_medical_support_desc,social_other_support,social_othersupport_value,social_permanency_value,social_succession_value,social_legal_value,social_nursing_value,social_transport_value,social_education_value,social_food_value,social_rent_value,social_solidarity_value,social_directsupport_value,social_medicalsupport_value,social_risk_level,social_notes', 
'nutrition_visit'=>'nutrition_client_id,nutrition_staff_id,nutrition_entry_date,nutrition_center,nutrition_gender,nutrition_age_yrs,nutrition_age_months,nutrition_age_status,nutrition_caregiver_type,nutrition_caregiver_type_notes,nutrition_weight,nutrition_height,nutrition_zscore,nutrition_muac,nutrition_wfh,nutrition_wfa,nutrition_bmi,nutrition_blacktea,nutrition_whitetea,nutrition_bread,nutrition_porridge,nutrition_breastfeeding,nutrition_formula_milk,nutrition_carbohydrates,nutrition_meat,nutrition_pancake,nutrition_eggs,nutrition_legumes,nutrition_milk,nutrition_vegetables,nutrition_fruit,nutrition_diet_history_notes,nutrition_diet_history_others,nutrition_food_enrichment,nutrition_water_access,nutrition_water_purification,nutrition_water_purification_notes,nutrition_food_enrichment_notes,nutrition_quantity,nutrition_quality,nutrition_poor_preparation,nutrition_mixed_feeding,nutrition_unclean_drinking_water,nutrition_education,nutrition_counselling,nutrition_demonstration,nutrition_dietary_supplement,nutrition_nan,nutrition_unimix,nutrition_harvest_pro,nutrition_wfp,nutrition_insta,nutrition_rutf,nutrition_other,nutrition_other_service,nutrition_notes',
'medical_assessment'=>'medical_client_id,medical_staff_id,medical_clinic_id,medical_gender,medical_age_yrs,medical_age_months,medical_entry_date,medical_birth_location,medical_delivery,medical_birth_problems,medical_transferred,medical_other_programme,medical_birth_weight,medical_pmtct,medical_mother_arv_given,medical_child_arv_given,medical_immunization_status,medical_card_seen,medical_breastfeeding,medical_exclusive_breastfeeding,medical_bf_duration,medical_father_hiv_status,medical_father_arv,medical_mother_hiv_status,medical_mother_arv,medical_no_siblings_alive,medical_no_siblings_deceased,medical_tb_contact,medical_tb_contact_person,medical_tb_date_diagnosed,medical_tb_pulmonary,medical_tb_type,medical_tb_type_desc,medical_tb_bodysite,medical_tb_date1,medical_tb_date2,medical_tb_date3,medical_history_pneumonia,medical_history_diarrhoea,medical_history_skin_rash,medical_history_ear_discharge,medical_history_fever,medical_history_oral_rush,medical_history_mouth_ulcers,medical_history_malnutrition,medical_history_prev_nutrition,medical_history_notes,medical_arv_status,medical_arv1,medical_arv1_startdate,medical_arv1_enddate,medical_arv2,medical_arv2_startdate,medical_arv2_enddate,medical_arv_side_effects,medical_arv_adherence,medical_school_attendance,medical_school_class,medical_educ_progress,medical_sensory_hearing,medical_sensory_vision,medical_sensory_motor_ability,medical_sensory_speech_language,medical_sensory_social_skills,medical_meals_per_day,medical_food_types,medical_current_complaints,medical_weight,medical_height,medical_zscore,medical_muac,medical_hc,medical_condition,medical_temp,medical_conditions,medical_dehydration,medical_parotids,medical_lymph,medical_eyes,medical_eyes_notes,medical_ear_discharge,medical_ear_discharge_location,medical_throat,medical_mouth_thrush,medical_mouth_ulcers,medical_mouth_teeth,medical_oldlesions,medical_currentlesions,medical_heartrate,medical_recession,medical_percussion,medical_location,medical_breath_sounds,medical_breathlocation,medical_other_sounds,medical_soundlocation,medical_pulserate,medical_apex_beat,medical_precordial,medical_femoral,medical_heart_sound,medical_heart_type,medical_abdomen_distended,medical_adbomen_feel,medical_abdomen_tender,medical_abdomen_fluid,medical_liver_costal,medical_spleen_costal,medical_masses,medical_umbilical_hernia,medical_testes,medical_which_testes,medical_genitals_female_notes,medical_genitals_feel,medical_penis,medical_genitals_female,medical_pubertal,medical_gait,medical_handuse,medical_weakness,medical_tone,medical_tendon_legs,medical_tendon_arms,medical_abnormal_movts,medical_movts_impaired,medical_movts_impaired_desc,medical_joints_swelling,medical_joints_swelling_desc,medical_motor,medical_musc_notes,medical_hiv_status,medical_cd4,medical_cd4_percentage,medical_who_clinical_stage,medical_immuno_stage,medical_tests,medical_referral,medical_notes',
'admission_info'=>'admission_client_id,admission_staff_id,admission_clinic_id,admission_dob,admission_age_yrs,admission_age_months,admission_age_status,admission_gender,admission_residence,admission_location,admission_entry_date,admission_school_level,admission_reason_not_attending,admission_reason_not_attending_notes,admission_total_orphan,admission_province,admission_district,admission_village,admission_father_fname,admission_father_lname,admission_father_age,admission_father_status,admission_father_health_status,admission_father_raising_child,admission_father_marital_status,admission_father_educ_level,admission_father_employment,admission_father_income,admission_father_idno,admission_father_mobile,admission_mother_fname,admission_mother_lname,admission_mother_age,admission_mother_status,admission_mother_health_status,admission_mother_raising_child,admission_mother_marital_status,admission_mother_educ_level,admission_mother_employment,admission_mother_income,admission_mother_idno,admission_mother_mobile,admission_caregiver_fname,admission_caregiver_lname,admission_caregiver_age,admission_caregiver_status,admission_caregiver_health_status,admission_caregiver_relationship,admission_caregiver_marital_status,admission_caregiver_educ_level,admission_caregiver_employment,admission_caregiver_income,admission_caregiver_idno,admission_caregiver_mobile,admission_family_income,admission_risk_level,admission_risk_level_description,admission_notes',
'household_info'=>
'household_client_id,household_admission_id,household_social_id,household_name,household_yob,household_relationship,household_gender,household_notes',
'medical_history'=>'medical_history_medical_id,medical_history_client_id,medical_history_hospital,medical_history_date,medical_history_diagnosis,medical_history_notes', 
'medications_history'=>'medications_history_medical_id,medications_history_client_id,medications_history_drug,medications_history_dose,medications_history_frequency,medications_history_notes', 
'mortality_info'=>'mortality_client_id,mortality_entry_date,mortality_clinic_id,mortality_age_yrs,mortality_age_months,mortality_age_status,mortality_date,mortality_death_type,mortality_death_type_notes,mortality_informant,mortality_hospital,mortality_hospital_adm_date,mortality_relative_report_date,mortality_symptoms,mortality_time_course,mortality_treatment,mortality_referral,mortality_hospital_referral,mortality_hospital_adm_notes,mortality_cause_given,mortality_cause_desc,mortality_clinical_officer,mortality_clinical_officer_date,mortality_postmortem,mortality_cause_pm,mortality_likely_cause,mortality_notes');

$clientStagingArray = array('counselling_staging'=>'counselling_client_id','clinical_visits_staging'=>'clinical_client_id','counselling_visit_staging'=>'counselling_client_id','social_staging'=>'social_client_id','nutrition_staging'=>'nutrition_client_id','medical_staging'=>'medical_client_id','admission_staging'=>'admission_client_id','household_staging'=>'household_client_id','medical_history_staging'=>'medical_history_client_id','medications_history_staging'=>'medications_history_client_id','mortality_staging'=>'mortality_client_id');

$clientsDestArray = array('counselling_info'=>'counselling_client_id','clinical_visits'=>'clinical_client_id','counselling_visit'=>'counselling_client_id','social_visit'=>'social_client_id','nutrition_visit'=>'nutrition_client_id','medical_assessment'=>'medical_client_id','admission_info'=>'admission_client_id','household_info'=>'household_client_id','medical_history'=>'medical_history_client_id','medications_history'=>'medications_history_client_id','mortality_info'=>'mortality_client_id');

$clientFields = array_values($clientsDestArray);
//$destTables = array_values($clientsDestArray);
$destFields = array_values($importArray);
$destTables = array_keys($importArray);
$stagingTables = array_keys($clientStagingArray);
//echo implode(",",array_keys($new_clients));
$new_client_ids = implode(",", array_keys($new_clients));

foreach ($all_clients as $client_adm_no => $client_id)
{
	
	foreach ($clientStagingArray as $table => $field)
	{
		$w = " UPDATE $table SET $field  = $client_id WHERE client_adm_no LIKE '$client_adm_no'";
		//print $w . '<br/>';
		db_exec($w);
	}
	
	if (!empty($new_client_ids))
	{
		foreach ($clientsDestArray as $table => $field)
		{
			$w = " DELETE FROM $table WHERE $field  IN ($new_client_ids) ";
			//print $w . '<br/>';
			db_exec($w);
		}
	}
	for ($count=0;$count<count($destTables);$count++)
	{
			$w = " INSERT INTO $destTables[$count] ($destFields[$count]) SELECT  $destFields[$count] FROM $stagingTables[$count] WHERE $clientFields[$count]  = $client_id ";
			//if ($stagingTables[$count]  == 'medical_staging')
			//{
				//print $w . '<br/>';
			//}
			db_exec($w);


	}
}

	//postprocessing for household info,medical info and medication info
	//update staging tables with new medical ids
	$q = new DBQuery;
	$q->addTable('medical_assessment');
	$q->addQuery('medical_id,medical_client_id, medical_entry_date');
	$medical_records = $q->loadList();
	//var_dump($medical_records);
	//exit;
	//update medical ids in staging and history tables
	foreach ($medical_records as $medical_record)
	{
		//var_dump($medical_record);
		$sql = "UPDATE medical_staging SET new_medical_id = " . $medical_record["medical_id"] .  " WHERE medical_client_id = " . $medical_record["medical_client_id"] . " AND medical_entry_date  = '" . $medical_record["medical_entry_date"] . "'";
		//print $sql;
		db_exec($sql);
	}
	$q = new DBQuery;
	$q->addTable('medical_staging');
	$q->addQuery('medical_id, new_medical_id');
	$q->addWhere('new_medical_id IS NOT NULL');
	$new_medical_records = $q->loadList();
	
	foreach ($new_medical_records as $medical_record)
	{
		
			$sql = "UPDATE medical_history SET medical_history_medical_id = " . $medical_record["new_medical_id"] .  " WHERE medical_history_medical_id = " . $medical_record["medical_id"];
			//print $sql . '<br/>';
			db_exec($sql);
			//print $sql;	
			$sql = "UPDATE medications_history SET medications_history_medical_id = " . $medical_record["new_medical_id"] . " WHERE medications_history_medical_id = " . $medical_record["medical_id"];
			//print $sql . '<br/>';
			db_exec($sql);
			//print $sql;
		
	}
	
	//update staging tables with new admission and social ids
	$q = new DBQuery;
	$q->addTable('admission_info');
	$q->addQuery('admission_id,admission_client_id, admission_entry_date');
	
	$admission_records = $q->loadList();
	//update admission ids in staging  table
	foreach ($admission_records as $admission_record)
	{
	
		$sql = "UPDATE admission_staging SET new_admission_id = " . $admission_record["admission_id"] .  " WHERE admission_client_id = " . addslashes($admission_record["admission_client_id"]) . " AND admission_client_id  = '" . $admission_record["admission_client_id"] . "'";
		db_exec($sql);
	}
	
	//update social staging table
	$q = new DBQuery;
	$q->addTable('social_visit');
	$q->addQuery('social_id,social_client_id, social_entry_date');
	$social_records = $q->loadList();
	//update admission ids in staging  table
	foreach ($social_records as $social_record)
	{
		$sql = "UPDATE social_staging SET new_social_id = " . $social_record["social_id"] .  " WHERE social_client_id = " . $social_record["social_client_id"] . " AND social_client_id  = '" . $social_record["social_client_id"] . "'";
		db_exec($sql);
	}
	
	$q = new DBQuery;
	$q->addTable('admission_staging');
	$q->addQuery('admission_id, new_admission_id');
	$q->addWhere('new_admission_id IS NOT NULL');
	$new_admission_records = $q->loadList();
	//var_dump($new_medical_records);
	foreach ($new_admission_records as $admission_record)
	{
	
	
		$sql = "UPDATE household_info SET household_admission_id = " . $admission_record["new_admission_id"] .  " WHERE household_admission_id = " . $admission_record["admission_id"];
		//print $sql . '<br/>';
		db_exec($sql);
	}
	
	$q = new DBQuery;
	$q->addTable('social_staging');
	$q->addQuery('social_id, new_social_id');
	$q->addWhere('new_social_id IS NOT NULL');
	$new_social_records = $q->loadList();
	//var_dump($new_medical_records);
	foreach ($new_social_records as $social_record)
	{
		$sql = "UPDATE household_info SET household_social_id = " . $social_record["new_social_id"] .  " WHERE household_social_id = " . $social_record["social_id"];
		//print $sql . '<br/>';
		db_exec($sql);
	}	
//construct message showing records uploaded into staging tables
$q  = new DBQuery;
$q->addTable('files');
$q->addUpdate('file_upload', "{$AppUI->user_id}");
$q->addWhere("file_id = $file_id");
$q->exec();
$q->clear();
$AppUI->setMsg("Please review the records imported into the database");
$AppUI->redirect("m=clients");
?>
