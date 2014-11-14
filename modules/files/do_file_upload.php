<?php /* FILES $Id: do_file_co.php,v 1.4 2005/03/29 00:01:18 ajdonnison Exp $ */
//require_once ("$baseDir/lib/Excel/Reader.php");

require_once $AppUI->getSystemClass('systemImport');

//define staging tables and target tables
$stagingTablesFileMapping = array (
	"clinics" => "clinics_staging",
	"clients" => "clients_staging",
	"intake_pcr" => "counselling_staging",
	"clinical_visits" => "clinical_visits_staging",
	"counselling_visits" => "counselling_visit_staging",
	"social_visits" => "social_staging",
	"social_services_details" => "social_services_staging",
	"nutrional_visits" => "nutrition_staging",
	"medical_assessment" => "medical_staging",
	"admission_details" => "admission_staging",
	"household_details" => "household_staging",
	"medical_history" => "medical_history_staging",
	"medication_history" => "medications_history_staging",
	"mortality" => "mortality_staging",
	"staff" => "contacts_staging",
	"clinic_location" => "clinic_location_staging",
	"group_activities" => "activities_staging",
	"trainings" => "training_staging",
	"activity_facilitator" => "activity_facilitators_staging",
	"activity_caregivers" => "activity_caregivers_staging",
	"activity_clients" => "activity_clients_staging",
	"activity_staff" => "activity_staff_staging"
);

$stagingTablesTargetTablesMapping = array (
	'clinics_staging' => "clinics",
	'clients_staging' => "clients",
	'admission_caregivers_staging' => 'admission_caregivers',
	'counselling_staging' => "counselling_info",
	'clinical_visits_staging' => "clinical_visits",
	'counselling_visit_staging' => "counselling_visit",
	'social_staging' => "social_visit",
	'social_services_staging' => "social_services",
	'nutrition_staging' => "nutrition_visit",
	'medical_staging' => "medical_assessment",
	'admission_staging' => "admission_info",
	'household_staging' => "household_info",
	'medical_history_staging' => "medical_history",
	'medications_history_staging' => "medications_history",
	'mortality_staging' => "mortality_info",
	'contacts_staging' => "contacts",
	'clinic_location_staging' => "clinic_location",
	'activities_staging' => "activity",
	'training_staging' => "trainings",
	'activity_facilitator_staging' => "activity_facilitator",
	'activity_caregivers_staging' => "activity_caregivers",
	'activity_clients_staging' => "activity_clients",
	'activity_staff_staging' => "activity_contacts",
	'activity_facilitator_staging' => "activity_facilitator" ,
	'followup_info_staging'	=>	'followup_info',
	'chw_info_staging'		=>	'chw_info',
	'cbc_info_staging'		=>	'cbc_info'

);

$targetTablesStagingTablesMapping = array (
	"clinics" => 'clinics_staging',
	"clients" => 'clients_staging',
	'admission_caregivers' => 'admission_caregivers_staging',
	"counselling_info" => 'counselling_staging',
	"clinical_visits" => 'clinical_visits_staging',
	"counselling_visit" => 'counselling_visit_staging',
	"social_visit" => 'social_staging',
	"social_services" => 'social_services_staging',
	"nutrition_visit" => 'nutrition_staging',
	"medical_assessment" => 'medical_staging',
	"admission_info" => 'admission_staging',
	"household_info" => 'household_staging',
	"medical_history" => 'medical_history_staging',
	"medications_history" => 'medications_history_staging',
	"mortality_info" => 'mortality_staging',
	"contacts" => 'contacts_staging',
	"clinic_location" => 'clinic_location_staging',
	"activity" => 'activities_staging',
	"trainings" => 'training_staging',
	"activity_facilitator" => 'activity_facilitator_staging',
	"activity_caregivers" => 'activity_caregivers_staging',
	"activity_clients" => 'activity_clients_staging',
	"activity_contacts" => 'activity_staff_staging',
	"activity_facilitator" => 'activity_facilitator_staging',
	'followup_info'	=>	'followup_info_staging',
	'chw_info'		=>	'chw_info_staging',
	'cbc_info'		=>	'cbc_info_staging'
);

$stagingTables = array (
	'clinics_staging',
	'clients_staging',
	'admission_caregivers_staging',
	'counselling_staging',
	'clinical_visits_staging',
	'counselling_visit_staging',
	'social_staging',
	'social_services_staging',
	'nutrition_staging',
	'medical_staging',
	'admission_staging',
	'household_staging',
	'medical_history_staging',
	'medications_history_staging',
	'mortality_staging',
	'contacts_staging',
	'clinic_location_staging',
	'activities_staging',
	'training_staging',
	'activity_facilitator_staging',
	'activity_caregivers_staging',
	'activity_clients_staging',
	'activity_staff_staging',
	'followup_info_staging',
	'chw_info_staging',
	'cbc_info_staging'

);


$targetTables = array (
	'counselling_info',
	'clinical_visits',
	'admission_caregivers',
	'counselling_visit',
	'social_visit',
	'social_services',
	'nutrition_visit',
	'medical_assessment',
	'admission_info',
	'household_info',
	'medical_history',
	'medications_history',
	'mortality_info',
	'activity_clients'
);

$targetTables = array_values ( $stagingTablesTargetTablesMapping );
$stagingTables = array_keys ( $stagingTablesTargetTablesMapping );

//domain specific mappings
$clinicIdFieldsStagingArray = array (
	'counselling_clinic' => 'counselling_staging',
	'clinical_clinic_id' => 'clinical_visits_staging',
	'counselling_center_id' => 'counselling_visit_staging',
	'social_clinic_id' => 'social_staging',
	'nutrition_center' => 'nutrition_staging',
	'medical_clinic_id' => 'medical_staging',
	'admission_clinic_id' => 'admission_staging',
	'mortality_clinic_id' => 'mortality_staging',
	'clinic_id' => 'clinics_staging',
	'clinic_location_clinic_id' => 'clinic_location_staging',
	'activity_clinic' => 'activities_staging',
	'followup_center_id' => 'followup_info_staging',
	'chw_center_id'		=> 'chw_info_staging',
	'cbc_center_id'		=> 'cbc_info_staging'/*,
	'client_center'		=> 'client_staging' */
);

$staffStagingTableArray = array (
	'counselling_staging' => array ('counselling_staff_id' ),
	'clinical_visits_staging' => array ('clinical_staff_id', 'clinical_referral' ),
	'counselling_visit_staging' => array ('counselling_staff_id' ),
	'social_staging' => array ('social_staff_id' ),
	'nutrition_staging' => array ('nutrition_staff_id' ),
	'mortality_staging' => array('mortality_clinical_officer'),
	'medical_staging' => array ('medical_staff_id', 'medical_referral' ),
	'admission_staging' => array ('admission_staff_id' ),
	'activity_staff_staging' => array ('activity_contacts_contact_id' ),
	'followup_info_staging' => array('followup_officer_id')
);

$staffNameArray = array (
	'counselling_staging' => array ('counselling_staff_name' ),
	'clinical_visits_staging' => array ('clinical_staff_name', 'clinical_referral_name' ),
	'counselling_visit_staging' => array ('counselling_visit_staff_name' ),
	'social_staging' => array ('social_staff_name' ),
	'nutrition_staging' => array ('nutrition_staff_name' ),
	'mortality_staging' => array('mortality_clinical_officer_name'),
	'medical_staging' => array ('medical_staff_name', 'medical_referral_name' ),
	'admission_staging' => array ('admission_staff_name' ),
	'activity_staff_staging' => array ('activity_staff_name' ) ,
	'followup_info_staging'	=> array('followup_staff_name')
);

$clientIdDestArray = array (
	'counselling_info' => 'counselling_client_id',
	'clinical_visits' => 'clinical_client_id',
	'counselling_visit' => 'counselling_client_id',
	'social_visit' => 'social_client_id',
	'social_services' => 'social_services_client_id',
	'nutrition_visit' => 'nutrition_client_id',
	'medical_assessment' => 'medical_client_id',
	'admission_info' => 'admission_client_id',
	'household_info' => 'household_client_id',
	'medical_history' => 'medical_history_client_id',
	'medications_history' => 'medications_history_client_id',
	'mortality_info' => 'mortality_client_id',
	'activity_clients' => 'activity_clients_client_id',
	'admission_caregivers' => 'client_id',
	'followup_info'		=> 'followup_client_id',
	'cbc_info'		=> 'cbc_client_id',
	'chw_info'		=> 'chw_client_id'
);

$dateFieldsArray = array (
	'counselling_info' => 'counselling_entry_date',
	'clinical_visits' => 'clinical_entry_date',
	'counselling_visit' => 'counselling_entry_date',
	'social_visit' => 'social_entry_date',
	'nutrition_visit' => 'nutrition_entry_date',
	'medical_assessment' => 'medical_entry_date',
	'admission_info' => 'admission_entry_date',
	'household_info' => '',
	'medical_history' => 'medical_history_date',
	'medications_history' => '',
	'mortality_info' => 'mortality_entry_date',
	'activity_clients' => '',
	'admission_caregivers' => ''
);

$metaDataArray = array (
	'counselling_info' => 'counselling_entry_date',
	'clinical_visits' => NULL,
	'counselling_visit' => NULL,
	'social_visit' => NULL,
	'nutrition_visit' => NULL,
	'medical_assessment' => 'medical_entry_date',
	'admission_info' => 'admission_entry_date',
	'household_info' => NULL,
	'medical_history' => NULL,
	'medications_history' => NULL,
	'mortality_info' => NULL,
	'activity_clients' => NULL
);

$tableArray = array (
	'counselling_info' =>'
		counselling_client_id,
		counselling_entry_date,counselling_clinic,counselling_staff_id,counselling_referral_source,counselling_total_orphan,counselling_dob,counselling_age_yrs,counselling_age_months,counselling_age_status,
		counselling_place_of_birth,counselling_birth_area,counselling_mode_birth,counselling_gestation_period,counselling_birth_weight,counselling_mothers_status_known,counselling_mother_antenatal,counselling_mother_pmtct,
		counselling_mother_illness_pregnancy,counselling_mother_illness_pregnancy_notes,counselling_breastfeeding,counselling_breastfeeding_duration,counselling_other_breastfeeding_duration,counselling_child_prenatal,
		counselling_child_single_nvp,counselling_child_nvp_date,counselling_child_nvp_notes,counselling_child_azt,
  		counselling_child_azt_date,
  		counselling_no_doses,
  		counselling_mother_treatment,
  		counselling_mother_art_pregnancy,
  		counselling_mother_date_art,
  		counselling_mother_cd4,
  counselling_mother_date_cd4,
  counselling_determine_date,
  counselling_determine,
  counselling_bioline_date,
  counselling_bioline,
  counselling_unigold_date,
  counselling_unigold,
  counselling_elisa_date,
  counselling_elisa,
  counselling_pcr1_date,
  counselling_pcr1,
  counselling_pcr2_date,
  counselling_pcr2,
  counselling_rapid12_date,
  counselling_rapid12,
  counselling_rapid18_date,
  counselling_rapid18,
  counselling_other_date,
  counselling_other,
  counselling_notes,
  counselling_custom,
  counselling_other_notes,
  counselling_vct_camp,
  counselling_vct_camp_site,
  counselling_return,
  counselling_client_code,
  counselling_partner_code,
  counselling_area,
  counselling_gender,
  counselling_marital,
  counselling_client_seen,
  counselling_final,
  counselling_dis_couple,
  counselling_mother_treatment_where,
  counselling_mother_pmtct_where,
  counselling_mother_antenatal_where,
  counselling_mother_cd4_note,
  counselling_positive_ref,
  counselling_positive_ref_notes,
  counselling_admission_date,
  counselling_referral_source_old,
  counselling_referral_source_notes',


	'clinical_visits' =>
							'clinical_client_id,
  clinical_entry_date,
  clinical_clinic_id,
  clinical_staff_id,
  clinical_age_yrs,
  clinical_age_months,
  clinical_child_attending,
  clinical_caregiver_attending,
  clinical_caregiver,
  clinical_illness,
  clinical_illness_notes,
  clinical_diarrhoea,
  clinical_vomiting,
  clinical_current_complaints,
  clinical_bloodtest_date,
  clinical_bloodtest_cd4,
  clinical_bloodtest_cd4_percentage,
  clinical_bloodtest_viral,
  clinical_bloodtest_hb,
  clinical_xray_results,
  clinical_other_results,
  clinical_weight,
  clinical_height,
  clinical_zscore,
  clinical_muac,
  clinical_hc,
  clinical_child_unwell,
  clinical_temp,
  clinical_resp_rate,
  clinical_heart_rate,
  clinical_general,
  clinical_pallor,
  clinical_jaundice,
  clinical_examination_dehydration,
  clinical_examination_lymph,
  clinical_mouth,
  clinical_teeth,
  clinical_ears,
  clinical_chest,
  clinical_chest_clear,
  clinical_skin_clear,
  clinical_cardiovascular,
  clinical_skin,
  clinical_clubbing,
  clinical_abdomen,
  clinical_neurodevt,
  clinical_musculoskeletal,
  clinical_oedema,
  clinical_adherence,
  clinical_adherence_notes,
  clinical_diarrhoea_type,
  clinical_dehydration,
  clinical_pneumonia,
  clinical_chronic_lung,
  clinical_lung_disease,
  clinical_tb,
  clinical_tb_treatment_date,
  clinical_pulmonary,
  clinical_discharging_ears,
  clinical_other_diagnoses,
  clinical_malnutrition,
  clinical_growth,
  clinical_assessment_notes,
  clinical_investigations,
  clinical_investigations_notes,
  clinical_other_drugs,
  clinical_new_drugs,
  clinical_on_arvs,
  clinical_arv_drugs,
  clinical_tb_treatment,
  clinical_arv_notes,
  clinical_who_stage,
  clinical_who_current,
  clinical_who_reason,
  clinical_tb_drugs,
  clinical_tb_drugs_notes,
  clinical_septrin,
  clinical_vitamins,
  clinical_treatment_status,
  clinical_arv_reason,
  clinical_nutritional_support,
  clinical_nutritional_notes,
  clinical_referral_old,
  clinical_next_date,
  clinical_notes,
  clinical_custom,
  clinical_arv_drugs_other,
  clinical_complaints,
  clinical_ctscan,
  clinical_astal,
  clinical_mouth_thrush,
  clinical_mouth_ulcer,
  clinical_teeth_opt,
  clinical_ears_opt,
  clinical_throat,
  clinical_chest_creps,
  clinical_skin_opts,
  clinical_cns,
  clinical_eyes,
  clinical_eyes_opt,
  clinical_muscle,
  clinical_tb_treat,
  clinical_dss,
  clinical_arv_on,
  clinical_arv_on_adh,
  clinical_tb_status,
  clinical_tb_status_notes,
  clinical_stage,
  clinical_referral,
  clinical_referral_other,
  clinical_request,
  clinical_request_list,
  clinical_other,
  clinical_therapy_stage',


	'counselling_visit' => ' counselling_client_id,
  counselling_staff_id,
  counselling_center_id,
  counselling_entry_date,
  counselling_visit_type,
  counselling_caregiver_fname,
  counselling_caregiver_lname,
  counselling_caregiver_age,
  counselling_caregiver_relationship,
  counselling_caregiver_marital_status,
  counselling_caregiver_educ_level,
  counselling_caregiver_employment,
  counselling_caregiver_income_level,
  counselling_caregiver_idno,
  counselling_caregiver_mobile,
  counselling_caregiver_residence,
  counselling_child_issues,
  counselling_other_issues,
  counselling_caregiver_issues,
  counselling_caregiver_other_issues,
  counselling_caregiver_issues2,
  counselling_caregiver_other_issues2,
  counselling_child_knows_status_old,
  counselling_otheradult_knows_status,
  counselling_disclosure_response,
  counselling_disclosure_state,
  counselling_secondary_caregiver_knows,
  counselling_primary_caregiver_tested,
  counselling_father_status,
  counselling_mother_status,
  counselling_caregiver_status,
  counselling_father_treatment,
  counselling_mother_treatment,
  counselling_caregiver_treatment,
  counselling_stigmatization_concern,
  counselling_counselling_services,
  counselling_other_services,
  counselling_notes,
  counselling_custom,
  counselling_child_knows_status,
  counselling_second_ident,
  counselling_referer,
  counselling_referer_other',

	'social_visit' =>'social_client_id,
  social_staff_id,
  social_clinic_id,
  social_entry_date,
  social_client_status,
  social_client_health,
  social_visit_type,
  social_death,
  social_death_notes,
  social_death_date,
  social_caregiver_pri_change,
  social_caregiver_pri_change_notes,
  social_caregiver_sec_change,
  social_caregiver_sec_change_notes,
  social_caregiver_relationship,
  social_caregiver_pri_health_child_impact,
  social_caregiver_sec_health_child_impact,
  social_caregiver_pri_health,
  social_caregiver_sec_health,
  social_residence_mobile,
  social_residence,
  social_caregiver_pri_employment_change,
  social_caregiver_pri_new_employment,
  social_caregiver_pri_new_employment_desc,
  social_caregiver_sec_employment_change,
  social_caregiver_sec_new_employment,
  social_caregiver_sec_new_employment_desc,
  social_caregiver_pri_new_income,
  social_school_attendance,
  social_school,
  social_reason_not_attending,
  social_reason_not_attending_notes,
  social_relocation,
  social_iga,
  social_placement,
  social_succession_planning,
  social_legal,
  social_nursing,
  social_transport,
  social_education,
  social_food,
  social_rent,
  social_solidarity,
  social_direct_support,
  social_medical_support,
  social_medical_support_desc,
  social_other_support,
  social_othersupport_value,
  social_permanency_value,
  social_succession_value,
  social_legal_value,
  social_nursing_value,
  social_transport_value,
  social_education_value,
  social_food_value,
  social_rent_value,
  social_solidarity_value,
  social_directsupport_value,
  social_medicalsupport_value,
  social_risk_level,
  social_notes,
  social_custom,
  social_change,
  social_training,
  social_training_desc,
  social_next_visit,
  social_referral,
  social_caregiver_pri,
  social_caregiver_sec,
  social_caregiver_pri_type,
  social_caregiver_sec_type,
  social_nhf,
  social_nhf_y,
  social_nhf_n,
  social_immun,
  social_immun_y,
  social_immun_n,
  social_caregiver_employment_change,
  social_caregiver_new_employment,
  social_caregiver_new_employment_desc,
  social_class_form,
  social_caregiver_income,
  social_any_needs',

	'social_services' => 'social_services_id,
						  social_services_client_id,
						  social_services_social_id,
						  social_services_service_id,
						  social_services_date,
						  social_services_notes',

	'nutrition_visit' =>'nutrition_client_id,
  nutrition_staff_id,
  nutrition_entry_date,
  nutrition_center,
  nutrition_gender,
  nutrition_age_yrs,
  nutrition_age_months,
  nutrition_age_status,
  nutrition_caregiver_type,
  nutrition_caregiver_type_notes,
  nutrition_weight,
  nutrition_height,
  nutrition_zscore,
  nutrition_muac,
  nutrition_wfh,
  nutrition_wfa,
  nutrition_bmi,
  nutrition_blacktea,
  nutrition_whitetea,
  nutrition_bread,
  nutrition_porridge,
  nutrition_breastfeeding,
  nutrition_formula_milk,
  nutrition_carbohydrates,
  nutrition_meat,
  nutrition_pancake,
  nutrition_eggs,
  nutrition_legumes,
  nutrition_milk,
  nutrition_vegetables,
  nutrition_fruit,
  nutrition_diet_history_notes,
  nutrition_diet_history_others,
  nutrition_food_enrichment,
  nutrition_water_access,
  nutrition_water_purification,
  nutrition_water_purification_notes,
  nutrition_food_enrichment_notes,
  nutrition_quantity,
  nutrition_quality,
  nutrition_poor_preparation,
  nutrition_mixed_feeding,
  nutrition_unclean_drinking_water,
  nutrition_education,
  nutrition_counselling,
  nutrition_demonstration,
  nutrition_dietary_supplement,
  nutrition_nan,
  nutrition_unimix,
  nutrition_harvest_pro,
  nutrition_wfp,
  nutrition_insta,
  nutrition_rutf,
  nutrition_other,
  nutrition_other_service,
  nutrition_notes,
  nutrition_custom,
  nutrition_water,
  nutrition_oedema,
  nutrition_beverages_title,
  nutrition_beverages_notes,
  nutrition_ugali,
  nutrition_rice,
  nutrition_banan,
  nutrition_tubers,
  nutrition_wheat,
  nutrition_carbos_title,
  nutrition_carbos_notes,
  nutrition_protein_title,
  nutrition_protein_notes,
  nutrition_fat,
  nutrition_issue_notes,
  nutrition_program,
  nutrition_program_other,
  nutrition_rendered,
  nutrition_next_visit,
  nutrition_refer,
  nutrition_refer_other,
  nutrition_service_other,
  nutrition_care_attend,
  nutrition_child_attend,
  nutrition_care_who',


	'medical_assessment' => 'medical_client_id,
  medical_staff_id,
  medical_clinic_id,
  medical_gender,
  medical_age_yrs,
  medical_age_months,
  medical_entry_date,
  medical_birth_location,
  medical_delivery,
  medical_birth_problems,
  medical_transferred,
  medical_other_programme,
  medical_birth_weight,
  medical_pmtct,
  medical_mother_arv_given,
  medical_child_arv_given,
  medical_immunization_status,
  medical_card_seen,
  medical_breastfeeding,
  medical_exclusive_breastfeeding,
  medical_bf_duration,
  medical_father_hiv_status,
  medical_father_arv,
  medical_mother_hiv_status,
  medical_mother_arv,
  medical_no_siblings_alive,
  medical_no_siblings_deceased,
  medical_tb_contact,
  medical_tb_contact_person,
  medical_tb_date_diagnosed,
  medical_tb_pulmonary,
  medical_tb_type,
  medical_tb_type_desc,
  medical_tb_bodysite,
  medical_tb_date1,
  medical_tb_date2,
  medical_tb_date3,
  medical_history_pneumonia,
  medical_history_diarrhoea,
  medical_history_skin_rash,
  medical_history_ear_discharge,
  medical_history_fever,
  medical_history_oral_rush,
  medical_history_mouth_ulcers,
  medical_history_malnutrition,
  medical_history_prev_nutrition,
  medical_history_notes,
  medical_arv_status,
  medical_arv1,
  medical_arv1_startdate,
  medical_arv1_enddate,
  medical_arv2,
  medical_arv2_startdate,
  medical_arv2_enddate,
  medical_arv_side_effects,
  medical_arv_adherence,
  medical_school_attendance,
  medical_school_class,
  medical_educ_progress,
  medical_sensory_hearing,
  medical_sensory_vision,
  medical_sensory_motor_ability,
  medical_sensory_speech_language,
  medical_sensory_social_skills,
  medical_meals_per_day,
  medical_food_types,
  medical_current_complaints,
  medical_weight,
  medical_height,
  medical_zscore,
  medical_muac,
  medical_hc,
  medical_condition,
  medical_temp,
  medical_conditions,
  medical_dehydration,
  medical_parotids,
  medical_lymph,
  medical_eyes,
  medical_eyes_notes,
  medical_ear_discharge,
  medical_ear_discharge_location,
  medical_throat,
  medical_mouth_thrush,
  medical_mouth_ulcers,
  medical_mouth_teeth,
  medical_oldlesions,
  medical_currentlesions,
  medical_heartrate,
  medical_recession,
  medical_percussion,
  medical_location,
  medical_breath_sounds,
  medical_breathlocation,
  medical_other_sounds,
  medical_soundlocation,
  medical_pulserate,
  medical_apex_beat,
  medical_precordial,
  medical_femoral,
  medical_heart_sound,
  medical_heart_type,
  medical_abdomen_distended,
  medical_adbomen_feel,
  medical_abdomen_tender,
  medical_abdomen_fluid,
  medical_liver_costal,
  medical_spleen_costal,
  medical_masses,
  medical_umbilical_hernia,
  medical_testes,
  medical_which_testes,
  medical_genitals_female_notes,
  medical_genitals_feel,
  medical_penis,
  medical_genitals_female,
  medical_pubertal,
  medical_gait,
  medical_handuse,
  medical_weakness,
  medical_tone,
  medical_tendon_legs,
  medical_tendon_arms,
  medical_abnormal_movts,
  medical_movts_impaired,
  medical_movts_impaired_desc,
  medical_joints_swelling,
  medical_joints_swelling_desc,
  medical_motor,
  medical_musc_notes,
  medical_hiv_status,
  medical_cd4,
  medical_cd4_percentage,
  medical_who_clinical_stage,
  medical_immuno_stage,
  medical_tests,
  medical_referral_old,
  medical_notes,
  medical_custom,
  medical_salvage,
  medical_salvage_startdate,
  medical_salvage_enddate,
  medical_gait_opt,
  medical_handuse_opt,
  medical_referral,
  medical_heart_rate,
  medical_resp_rate,
  medical_skin_type,
  medical_skin_note,
  medical_chest_shape,
  medical_cns,
  medical_cns_note,
  medical_muscle,
  medical_muscle_note,
  medical_request,
  medical_request_opts,
  medical_request_note,
  medical_next_visit',



	'admission_info' =>'admission_client_id,
  admission_staff_id,
  admission_chw,
  admission_clinic_id,
  admission_dob,
  admission_age_yrs,
  admission_age_months,
  admission_age_status,
  admission_gender,
  admission_residence,
  admission_location,
  admission_entry_date,
  admission_school_level,
  admission_reason_not_attending,
  admission_reason_not_attending_notes,
  admission_total_orphan,
  admission_province,
  admission_district,
  admission_village,
  admission_father,
  admission_father_status,
  admission_father_raising_child,
  admission_mother,
  admission_mother_status,
  admission_mother_raising_child,
  admission_caregiver_pri,
  admission_caregiver_pri_relationship,
  admission_caregiver_sec,
  admission_caregiver_sec_relationship,
  admission_caregiver_sec_residence,
  admission_family_income,
  admission_risk_level,
  admission_risk_level_description,
  admission_notes,
  admission_custom,
  admission_enclosures,
  admission_birth_cert,
  admission_id_no,
  admission_med_recs,
  admission_nhf,
  admission_immun,
  admission_death_cert,
  admission_enclosures_other',

//'admission_client_id,admission_staff_id,admission_clinic_id,admission_dob,admission_age_yrs,admission_age_months,admission_age_status,admission_gender,admission_residence,admission_location,admission_entry_date,admission_school_level,admission_reason_not_attending,admission_reason_not_attending_notes,admission_total_orphan,admission_province,admission_district,admission_village,admission_father_fname,admission_father_lname,admission_father_age,admission_father_status,admission_father_health_status,admission_father_raising_child,admission_father_marital_status,admission_father_educ_level,admission_father_employment,admission_father_income,admission_father_idno,admission_father_mobile,admission_mother_fname,admission_mother_lname,admission_mother_age,admission_mother_status,admission_mother_health_status,admission_mother_raising_child,admission_mother_marital_status,admission_mother_educ_level,admission_mother_employment,admission_mother_income,admission_mother_idno,admission_mother_mobile,admission_caregiver_fname,admission_caregiver_lname,admission_caregiver_age,admission_caregiver_status,admission_caregiver_health_status,admission_caregiver_relationship,admission_caregiver_marital_status,admission_caregiver_educ_level,admission_caregiver_employment,admission_caregiver_income,admission_caregiver_idno,admission_caregiver_mobile,admission_family_income,admission_risk_level,admission_risk_level_description,admission_notes',

	'household_info' => 'household_client_id,household_admission_id,household_social_id,household_name,household_yob,household_relationship,household_gender,household_notes,household_custom',

	'medical_history' => 'medical_history_medical_id,medical_history_client_id,medical_history_hospital,medical_history_date,medical_history_diagnosis,medical_history_notes,medical_history_custom',

	'medications_history' => 'medications_history_medical_id,medications_history_client_id,medications_history_drug,medications_history_dose,medications_history_frequency,medications_history_notes,medications_history_custom',

	'mortality_info' => 'mortality_client_id,
  mortality_entry_date,
  mortality_clinic_id,
  mortality_age_yrs,
  mortality_age_months,
  mortality_age_status,
  mortality_date,
  mortality_death_type,
  mortality_death_type_notes,
  mortality_informant,
  mortality_hospital,
  mortality_hospital_adm_date,
  mortality_relative_report_date,
  mortality_symptoms,
  mortality_time_course,
  mortality_treatment,
  mortality_referral,
  mortality_hospital_referral,
  mortality_hospital_adm_notes,
  mortality_cause_given,
  mortality_cause_desc,
  mortality_clinical_officer,
  mortality_clinical_officer_date,
  mortality_postmortem,
  mortality_cause_pm,
  mortality_likely_cause,
  mortality_notes,
  mortality_custom,
  mortality_clinical_course,
  mortality_postmortem_where,
  mortality_recents_a,
  mortality_recents_b,
  mortality_malnutrition,
  mortality_malnutrition_notes,
  mortality_cd4,
  mortality_cd4_percentage,
  mortality_viral_load,
  mortality_hb,
  mortality_clinical_date,
  mortality_arv,
  mortality_arv_dateon,
  mortality_arv_period,
  mortality_tb,
  mortality_tb_start,
  mortality_weight,
  mortality_height,
  mortality_nutrition_date,
  mortality_enroll_date,
  mortality_enrolled_time',
		//'mortality_client_id,mortality_entry_date,mortality_clinic_id,mortality_age_yrs,mortality_age_months,mortality_age_status,mortality_date,mortality_death_type,mortality_death_type_notes,mortality_informant,mortality_hospital,mortality_hospital_adm_date,mortality_relative_report_date,mortality_symptoms,mortality_time_course,mortality_treatment,mortality_referral,mortality_hospital_referral,mortality_hospital_adm_notes,mortality_cause_given,mortality_cause_desc,mortality_clinical_officer,mortality_clinical_officer_date,mortality_postmortem,mortality_cause_pm,mortality_likely_cause,mortality_notes',

	'activity_clients' => 'activity_clients_activity_id, activity_clients_client_id',

'admission_caregivers' =>

 'fname,
  lname,
  age,
  health_status,
  marital_status,
  educ_level,
  employment,
  idno,
  mobile,
  client_id,
  reason,
  datesoff,
  role,
  relationship,
  status' ,

 'followup_info' =>

 'followup_client_id,
  followup_adm_no,
  followup_client_type,
  followup_object,
  followup_visit_type,
  followup_issues,
  followup_issues_notes,
  followup_service,
  followup_service_notes,
  followup_date,
  followup_center_id,
  followup_officer_id,
  followup_visit_mode',

	'chw_info' =>

	'chw_client_id,
  chw_name,
  chw_center_id,
  chw_village,
  chw_location,
  chw_entry_date,
  chw_adm_no,
  chw_sex,
  chw_old,
  chw_age,
  chw_hasplan,
  chw_arv,
  chw_arv_note,
  chw_oir,
  chw_oir_note,
  chw_tb,
  chw_nutrition,
  chw_adh_support,
  chw_assess,
  chw_support,
  chw_comm_mob,
  chw_refers,
  chw_remarks' ,

	'cbc_info' =>
		'cbc_client_id,
  cbc_name,
  cbc_village,
  cbc_center_id,
  cbc_location,
  cbc_entry_date,
  cbc_adm_no,
  cbc_old,
  cbc_sex,
  cbc_age,
  cbc_hbcare,
  cbc_adh_support,
  cbc_remarks,
  cbc_refers,
  cbc_refers_note',

		'trainings'=>
		'training_date,
		training_entry_date,
		training_name,
		training_clinic,
		training_notes,
		training_custom,
		training_curriculum,
		training_curriculum_desc'
);

$destClientIdFields = array_values ( $clientIdDestArray );
$dateFields = array_values ( $dateFieldsArray );
$metaData = array_values ( $metaDataArray );

//addfile sql
$file_id = intval ( dPgetParam ( $_POST, 'file_id', 0 ) );

$obj = new CFile ();
if ($file_id) {
	$obj->_message = 'updated';
	$oldObj = new CFile ();
	$oldObj->load ( $file_id );

} else {
	$obj->_message = 'added';
}
//open spreadsheet reader
// ExcelFile($filename, $encoding);
/*$data = new Spreadsheet_Excel_Reader ();

// Set output Encoding.
$data->setOutputEncoding ( 'CP1251' );

if ($file_id)
	$data->read ( "$baseDir/files/$oldObj->file_real_filename" );
*/
if (! $obj->bind ( $_POST )) {
	$AppUI->setMsg ( $obj->getError (), UI_MSG_ERROR );
	$AppUI->redirect ();
}


// prepare (and translate) the module name ready for the suffix
//$AppUI->setMsg( 'File' );


set_time_limit ( 6000 );
ignore_user_abort ( 1 );

//loop through each worksheet and move to staging table


//clear the tables

$sqlarr =array('CREATE TABLE IF NOT EXISTS `admission_caregivers_staging` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `new_id` int(11) unsigned NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `health_status` tinyint(1) unsigned DEFAULT "0",
  `marital_status` tinyint(2) unsigned DEFAULT "0",
  `educ_level` tinyint(2) unsigned NOT NULL DEFAULT "0",
  `employment` tinyint(2) unsigned NOT NULL DEFAULT "0",
  `idno` varchar(100) NOT NULL DEFAULT "",
  `mobile` varchar(50) NOT NULL DEFAULT "",
  `client_adm_no` varchar(50) DEFAULT NULL,
  `client_first_name` varchar(100) DEFAULT NULL,
  `client_other_name` varchar(100) DEFAULT NULL,
  `client_last_name` varchar(100) DEFAULT NULL,
  `client_id` INT( 11 ) NULL DEFAULT NULL ,
  `reason` tinyint(2) unsigned DEFAULT NULL,
  `datesoff` date DEFAULT NULL,
  `role` enum("mother","father","pri","sec") DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ',
'ALTER TABLE `mortality_staging` ADD `mortality_clinical_officer_name` VARCHAR( 200 ) NULL DEFAULT NULL AFTER `mortality_cause_desc`',
'ALTER TABLE  `counselling_staging` ADD  `counselling_other_notes` VARCHAR( 50 ) NULL DEFAULT NULL AFTER  `counselling_custom`',
'alter table clinical_visits_staging add  `clinical_arv_drugs_other` varchar(150) DEFAULT NULL , add  `clinical_referral_old` INT( 11 ) NULL DEFAULT NULL',
'alter table training_staging add `training_custom` longtext default null',
'ALTER TABLE  `counselling_staging` ADD INDEX  `counselling_name` (  `counselling_id` ,  `counselling_staff_name` )',
'ALTER TABLE	`clinical_visits_staging` ADD INDEX  `clinical_staff_name` (  `clinical_id` ,  `clinical_staff_name` ),
	      		ADD INDEX  `clinical_referral_name` (  `clinical_id` ,  `clinical_referral_name` )',
'ALTER TABLE  `social_staging` ADD INDEX  `social_staff_name` (  `social_id` ,  `social_staff_name` )',
'ALTER TABLE  `nutrition_staging` ADD INDEX  `nutrition_staff_name` (  `nutrition_id` ,  `nutrition_staff_name` )',
'ALTER TABLE  `mortality_staging` ADD INDEX  `mortality_staff_name` (  `mortality_id` ,  `mortality_staff_name` )',
'ALTER TABLE  `clients` ADD INDEX `update_index` ( `client_adm_no` , `client_first_name` , `client_other_name` , `client_last_name` )',
'ALTER TABLE training_staging ADD `training_curriculum` int( 11 ) DEFAULT NULL ,ADD `training_curriculum_desc` varchar( 150 ) DEFAULT NULL'
);

/*foreach ($sqlarr as $sv) {
	@mysql_query($sv);
}
@mysql_query($sql);*/

foreach ( $stagingTables as $stagingTable ) {
	$sql = "truncate " . $stagingTable;
	db_exec ( $sql );
}
//get headers for this worksheet


$sheetcount = 0;
/*
for($sheetcount = 0,$scnt=count ( $data->sheets ); $sheetcount < $scnt; $sheetcount ++) {
	$headers = NULL;
	for($h = 1,$tl=$data->sheets [$sheetcount] ['numCols']; $h <= $tl; $h ++) {
		$headers [] = $data->sheets [$sheetcount] ['cells'] [1] [$h];
	}
	//insert into staging tables

	for($i = 2,$tl1=$data->sheets [$sheetcount] ['numRows']; $i <= $tl1; $i ++) {
		$csheet=$data->sheets [$sheetcount] ;
		$w = 'INSERT INTO ' . $stagingTables [$sheetcount] . ' (' . implode ( $headers, "," ) . ') VALUES (';
		for($j = 1,$tld=$csheet['numCols']; $j <= $tld; $j ++) {
			if (! empty ( $csheet ['cells'] [$i] [$j] ))
				$w .= "'" . addslashes ( $csheet ['cells'] [$i] [$j] ) . "',";
			else
				$w .= 'NULL,';
		}

		//check if we have a comma at the end
		if ($w [strlen ( $w ) - 1] == ",")
			$w = substr ( $w, 0, - 1 );
		$w .= ");";
		db_exec ( $w );
		$count += db_exec ( 'SELECT ROW_COUNT()' );

	}
	//echo $w;
//echo "<br/>";
}*/

$fcc=file_get_contents("$baseDir/files/$oldObj->file_real_filename"/*$baseDir.'/files/tmp/Lea_Toto.bin'*/);
$fcc=explode('===###===',$fcc);
$bats='';
$ind=0;
if(count($fcc) === 0 || !$fcc){
	$AppUI->setMsg ( "File content is invalid!" );
	$AppUI->redirect ( "m=clients" );
}
foreach ($fcc as $key => $value) {
	if (strlen ( $value ) > 0) {
		$bat = gzdecode ( $value );
		//echo $bat.'<br>';
		if (! is_null ( $bat )) {
			eval ( $bat );

			unset ( $bat, $fcc [$key], $value );
			reset ( $arr );
			$curr = current ( $arr );
			/*
		 * Insert into db data from file
		 */

			$prestr = 'INSERT INTO ' . $stagingTables [$ind] . ' (' . implode ( unserialize ( $curr ['keys'] ), "," ) . ') VALUES ';
			$arr = array ();
			$total = 0;
			$cnum = false;
			$vstr = '';
			foreach ( $curr ['data'] as $i => $cdata ) {
				$carr = unserialize ( $cdata );
				$vstr != '' ? $vstr .= ',' : '';
				$vstr .= '(';
				$varr = array ();
				foreach ( $carr as $value ) {
					if (! empty ( $value )) {
						$varr [] = '"' . $value . '"';
					} else {
						$varr [] = 'NULL';
					}
				}
				$vstr .= implode ( ',', $varr ) . ')';
				++ $total;
				if ($total === 50) {
					$sql = $prestr . $vstr;
					$res = db_exec ( $sql );
					$vstr = '';
					$total = 0;
				}
				unset ( $curr ['data'] [$i], $varr );
			}
			if ($vstr !== '') {
				$res = db_exec ( $prestr . $vstr );
				$vstr = '';
				$prestr = '';
			}
		}
	}
	++ $ind;
}

$AppUI->setMsg ( "$count records moved to the various staging tables" );

//move from staging to live tables -- starting with clinics then clients
$q = new DBQuery ();
$q->addTable ( "clinics_staging" );
$q->addQuery ( "clinic_name" );
$q->addWhere ( "clinic_name NOT IN (SELECT concat_ws(',',clinic_name) FROM clinics)" );

//echo 'New clinics sql <br/>';
$sql = $q->prepare ();
//echo $sql . '<br/>';
$new_clinics = $q->loadColumn ();
//var_dump($new_clinics);
//$new_clinics  = array_values($new_clinics);


//var_dump($new_clinics);
//insert new clinics
foreach ( $new_clinics as $new_clinic ) {
	$q = new DBQuery ();
	$q->addTable ( "clinics" );
	$q->addInsert ( "clinic_name", $new_clinic );
	//$sql = $q->prepare();
	//print $sql;
	$q->exec ();
}

//update staging tables


//get new list of clinics
$q = new DBQuery ();
$q->addTable ( "clinics" );
$q->addQuery ( "clinic_name,clinic_id " );
$new_clinics = $q->loadHashList ();

//update staging tables


foreach ( $new_clinics as $clinic_name => $clinic_id ) {
	foreach ( $clinicIdFieldsStagingArray as $field => $table ) {
		$w = "UPDATE $table  SET  $field  = $clinic_id WHERE TRIM(clinic_name) = '$clinic_name'";
		//print "updating clinics <br/>";
		db_exec ( $w );
	}
}

//insert clinic locations


$q = new DBQuery ();
$q->addTable ( "clinic_location_staging" );
$q->addQuery ( "clinic_location, clinic_location_clinic_id" );
$q->addWhere ( "clinic_location NOT IN (SELECT concat_ws(',',clinic_location) FROM clinic_location)" );

//echo 'New clinics sql <br/>';
$sql = $q->prepare ();
//echo $sql . '<br/>';
$new_locations = $q->loadList ();
//$new_clinics  = array_values($new_clinics);


//var_dump($new_clinics);
//insert new clinics
foreach ( $new_locations as $new_location ) {
	$q = new DBQuery ();
	$q->addTable ( "clinic_location" );
	$q->addInsert ( "clinic_location", $new_location ["clinic_location"] );
	$q->addInsert ( "clinic_location_clinic_id", $new_location ["clinic_location_clinic_id"] );
	//$sql = $q->prepare();
	//print $sql;
	$q->exec ();
}

//update staging tables


//get new list of clinics
$q = new DBQuery ();
$q->addTable ( "clinic_location" );
$q->addQuery ( "clinic_location,clinic_location_id " );
$new_locations = $q->loadHashList ();

//update staging tables
$locationTableArray = array ('admission_location' => 'admission_staging' );
foreach ( $new_locations as $clinic_location => $clinic_location_id ) {
	foreach ( $locationTableArray as $field => $table ) {
		$w = "UPDATE $table  SET  $field  = $clinic_location_id WHERE TRIM(location_name) = '$clinic_location'";
		//print "updating clinics <br/>";
		//print $w . "<br/>";
		db_exec ( $w );
		//$ret=db_exec($w);
	//print "Affected rows <br/>";
	//print db_num_rows($ret) . "<br/>";


	}
}
//var_dump($targetTables);
/*foreach ($targetTables as $targetTable)
{

	$field_sql = "SELECT * FROM $targetTable LIMIT 0, 1";
	var_dump($field_sql);
	$ret = db_exec($field_sql);
	$fields = array_keys(db_fetch_array($ret));
	var_dump($fields);
}
exit;*/
function insertStaff() {

	$fields = array ("contact_first_name", "contact_other_name", "contact_last_name", "contact_title", "contact_job", "contact_type", "contact_email", "contact_email2", "contact_phone", "contact_phone2", "contact_fax", "contact_mobile", "contact_address1", "contact_address2", "contact_city", "contact_state", "contact_zip", "contact_country", "contact_notes", "contact_icon" );

	//insert all staff
	$q = new DBQuery ();
	$q->addTable ( "contacts_staging" );
	$q->addQuery ( "contact_first_name, contact_other_name, contact_last_name, contact_title, contact_job, contact_type, contact_email, contact_email2,  contact_phone, contact_phone2, contact_fax, contact_mobile, contact_address1, contact_address2, contact_city, contact_state, contact_zip, contact_country, contact_notes,  contact_icon" );
	$q->addWhere ( "concat( trim(ucase(contact_first_name)), trim(ucase(contact_last_name))) NOT IN (SELECT concat_ws(',',concat(trim(ucase(contact_first_name)), trim(ucase(contact_last_name)))) FROM contacts)" );
	$sql = $q->prepare ();
	//print('New staff sql <br/>');
	//print($sql . '<br/>');
	$new_contacts = $q->loadList ();
	//var_dump($new_contacts);


	//insert new staff
	foreach ( $new_contacts as $new_contact ) {
		$q = new DBQuery ();
		$q->addTable ( "contacts" );
		for($count = 0,$cnt=count ( $new_contact ); $count < $cnt; $count ++) {
			//echo $count;
			$q->addInsert ( $fields [$count], $new_contact [$fields [$count]] );
		}
		$sql = $q->prepare ();
		$q->exec ();

	}
}

function updateStaffIds() {

	global $staffStagingTableArray;
	global $staffNameArray;

	//get new list of staff except for admin
	$q = new DBQuery ();
	$q->addTable ( "contacts" );
	$q->addQuery ( "concat_ws(' ', trim(contact_first_name), trim(contact_other_name), trim(contact_last_name)),contact_id " );
	$q->addWhere ( "contact_id <> 1 AND contact_last_name IS NOT NULL" );

	$sql = $q->prepare ();
	//print "new staff <br/>";
	//print($sql .  "<br/>");


	$new_staff = $q->loadHashList ();

	//update staging tables
	//var_dump($staffStagingTableArray);
	$staff_id_fields = array_values ( $staffStagingTableArray );
	$staff_name_fields = array_values ( $staffNameArray );

	$stafftableNames = array_keys ( $staffNameArray );

	for($count = 0,$cz=count($staff_id_fields); $count < $cz; $count ++) {
		for($fieldcount = 0,$cxs=count($staff_id_fields [$count]); $fieldcount < $cxs; $fieldcount ++) {
			$sql = " UPDATE $stafftableNames[$count] a, contacts b SET " . $staff_id_fields [$count] [$fieldcount] . " = b.contact_id WHERE concat_ws(' ', b.contact_first_name, b.contact_last_name) = " . $staff_name_fields [$count] [$fieldcount];
			$ret = db_exec ( $sql );

		//print "Affected rows <br/>";
		//print db_num_rows($ret) . "<br/>";
		//print "Error is: " . db_error() . "<br/>";
		//print "Affected rows <br/>";
		//print db_num_rows($ret) . "<br/>";


		}
	}
}

function insertClients() {
	//insert all missing clients
	$q = new DBQuery ();
	$q->addTable ( "clients_staging" );
	$q->addQuery ( "DISTINCT client_adm_no, client_first_name, client_last_name, client_entry_date,client_gender,client_doa,client_dob, client_center,client_status,client_nhif,client_immun,client_nhif_n,client_immun_n" );
	$q->addWhere ( "client_adm_no NOT IN (SELECT concat_ws(',',client_adm_no) FROM clients)" );
	//$q->addWhere ( "concat(trim(ucase(client_adm_no)),trim(ucase(client_first_name)), trim(ucase(client_last_name))) NOT IN (SELECT concat_ws(',',concat(trim(ucase(client_adm_no)),trim(ucase(client_first_name)), trim(ucase(client_last_name)))) FROM clients)" );
	$q->addGroup ( "client_adm_no" );
	$sql = $q->prepare ();
	//print "fetching new clients not added <br/>";
	//print $sql . "<br/>";
	//exit;
	$new_clients = $q->loadArrayList ();
	//print "count of new clients <br/>";
	//print count($new_clients) . "<br/>";
	//exit;
	$fields = array ("client_adm_no", "client_first_name", "client_last_name", "client_entry_date",
					'client_gender', 'client_doa', 'client_dob' ,'client_center','client_status','client_nhif','client_immun',
			    'client_nhif_n','client_immun_n');
	//insert new clients
	foreach ( $new_clients as $new_client ) {
		$q = new DBQuery ();
		$q->addTable ( "clients" );
		for($fieldcount = 0, $fecnt = count ( $new_client ); $fieldcount < $fecnt; $fieldcount ++) {
			//echo $count;
			if (! empty ( $new_client [$fieldcount] )) {
				$q->addInsert ( $fields [$fieldcount], $new_client [$fieldcount] );
			}
		}
		$sql = $q->prepare ();
		//print "Clients to be inserted will be from this sql<br/>";
		//print $sql . '<br/>';
		$q->exec ();
	}
}

function updateClientIds() {

	$clientIdStagingArray = array (
		'clients_staging' => 'client_id',
		'counselling_staging' => 'counselling_client_id',
		'clinical_visits_staging' => 'clinical_client_id',
		'counselling_visit_staging' => 'counselling_client_id',
		'social_staging' => 'social_client_id',
		'social_services_staging' => 'social_services_client_id',
		'nutrition_staging' => 'nutrition_client_id',
		'medical_staging' => 'medical_client_id',
		'admission_staging' => 'admission_client_id',
		'household_staging' => 'household_client_id',
		'medical_history_staging' => 'medical_history_client_id',
		'medications_history_staging' => 'medications_history_client_id',
		'mortality_staging' => 'mortality_client_id',
		'activity_clients_staging' => 'activity_clients_client_id',
		'admission_caregivers_staging' => 'client_id',
		'followup_info_staging'				=> 'followup_client_id',
		'chw_info_staging'				=> 'chw_client_id',
		'cbc_info_staging'				=> 'cbc_client_id',
	);

	//get  list of clients
	/*$q = new DBQuery ();
	$q->addTable ( "clients" );
	$q->addQuery ( "DISTINCT client_adm_no,client_first_name, client_other_name, client_last_name, client_id " );
	$all_clients = $q->loadArrayList ();*/

	//get the current clinic id
	foreach ( $clientIdStagingArray as $table => $field ) {
		////*AND UCASE(TRIM(a.client_first_name)) = UCASE(TRIM(b.client_first_name)) AND UCASE(TRIM(a.client_last_name)) = UCASE(TRIM(b.client_last_name))*/
		$w = " UPDATE $table a, clients b SET a.$field  = b.client_id  WHERE a.client_adm_no = b.client_adm_no ";
		//need to further optimise this by limiting to the clinic being worked on
		//print "updating staging table with client ids <br/>";
		//print $w . '<br/>';
		db_exec ( $w );
	}
}

function insertCaregivers(){

}

function updateCaregiverIds(){
	$q = new DBQuery ();
	$q->addTable ( 'admission_caregivers_staging' );
	$q->addWhere ( "concat(role,trim(ucase(fname)), trim(ucase(lname)),client_id) NOT IN (SELECT concat(role,trim(ucase(fname)), trim(ucase(lname)),client_id) FROM admission_caregivers)" );
	$incarez = $q->loadHashListMine ();

	if (count ( $incarez ) > 0) {
		foreach ( $incarez as $cid => $crow ) {
			$sql = 'insert into admission_caregivers (fname, lname,age,health_status,marital_status,educ_level,employment,idno,mobile,client_id,reason,datesoff,role,relationship )
			values("' . $crow ['fname'] . '","' . $crow ['lname'] . '","' . $crow ['age'] . '","' . $crow ['health_status'] . '","' . $crow ['marital_status'] . '","' . $crow ['educ_level'] . '","' . $crow ['employment'] . '",
				"' . $crow ['idno'] . '","' . $crow ['mobile'] . '","' . $crow ['client_id'] . '","' . $crow ['reason'] . '","' . $crow ['datesoff'] . '","' . $crow ['role'] . '","' . $crow ['relationship'] . '")';
			$resin = mysql_query ( $sql );
			if ($resin) {
				$new_id = mysql_insert_id (  );
				$sql = 'update admission_caregivers_staging set new_id="' . $new_id . '" where id="' . $crow ['id'] . '"';
				db_exec ( $sql );
			}
			unset ( $incarez [$cid] );
		}
		$tar_id='new_id';
	}else{
		$tar_id='id';
	}
	$carelist = array ('admission_staging' =>
						array ('client_id' => 'admission_client_id',
								'fields' => array ('admission_father', 'admission_mother', 'admission_caregiver_pri', 'admission_caregiver_sec' )
						),
					'social_staging' =>
						array ('client_id' => 'social_client_id',
								'fields' => array ('social_caregiver_pri', 'social_caregiver_sec' )
						)
			);
		foreach ( $carelist as $table => $fields ) {
			foreach ( $fields ['fields'] as $field ) {
				preg_match ( "/_([^_]*)$/", $field, $roles );

				$w = " UPDATE $table a, admission_caregivers_staging b SET a.$field  = b.".$tar_id."
				   WHERE
				    a.$field is not null and
				    a.$field = b.id AND
				   	b.role='" . $roles [1] . "'
				   	";
				db_exec ( $w );
			}
		}
		$sql='update activity_caregivers_staging acs ,admission_caregivers ac,clients c set activity_caregivers_caregiver_id=ac.id
			where ac.client_id=c.client_id and c.client_adm_no=acs.client_adm_no and
			acs.caregiver_fname = ac.fname and acs.caregiver_lname=ac.lname and
			acs.caregiver_role = ac.role';
		$res=mysql_query($sql);

}

function insertActivities (){
	$q=new DBQuery();
	$q->addTable('activities_staging');
	$acts=$q->loadHashListMine();
	$act_list=array(
		'activity_facilitator_staging' 	=> 'facilitator_activity_id',
		'activity_caregivers_staging'	=> 'activity_caregivers_activity_id',
		'activity_staff_staging'		=> 'activity_contacts_activity_id',
		'activity_clients_staging'		=> 'activity_clients_activity_id'
	);
	if(count($acts) >0){
		foreach ($acts as $activ) {
			$oldid=$activ['activity_id'];
			$sql='insert into activity (activity_date,activity_curriculum,activity_curriculum_desc,activity_entry_date,activity_description,activity_clinic,activity_male_count,activity_female_count,activity_notes,activity_custom,activity_hpd,activity_visiters_total,activity_cadres,activity_end_date)
				values("'.$activ['activity_date'].'","'.$activ['activity_curriculum'].'","'.$activ['activity_curriculum_desc'].'","'.$activ['activity_entry_date'].'","'.$activ['activity_description'].'","'.$activ['activity_clinic'].'","'.$activ['activity_male_count'].'","'.$activ['activity_female_count'].'","'.$activ['activity_notes'].'","'.$activ['activity_custom'].'","'.$activ['activity_hpd'].'","'.$activ['activity_visiters_total'].'","'.$activ['activity_cadres'].'","'.$activ['activity_end_date'].'")' ;
			$resin=mysql_query($sql);
			if($resin){
				$new_id=mysql_insert_id();
				foreach ($act_list as $vkey=> $alv) {
					$sql='update '.$vkey.' set '.$alv.'="'.$new_id.'" where '.$alv.'="'.$oldid.'" limit 1';
					mysql_query($sql);
				}
			}
		}
	}
}

insertStaff ();
updateStaffIds ();
insertClients ();
updateClientIds ();
insertCaregivers();
updateCaregiverIds();
insertActivities();

//print "niko hapa after insertclientids";
//get all new clients
$q = new DBQuery ();
$q->addTable ( "clients_staging" );
$q->addQuery ( "DISTINCT client_adm_no,client_id" );
$q->addWhere ( "client_id IS NOT NULL" );
//$q->addWhere("client_id NOT IN (SELECT client_id FROM clients)");
$sql = $q->prepare ();
//print "fetching new clients not added";
//print $sql;
$new_clients = $q->loadList ();
//var_dump($new_clients);
if (! empty ( $new_clients )) {
	foreach ( $new_clients as $new_client ) {
		$new_client_ids [] = $new_client ["client_id"];
	}

	$new_client_ids = implode ( ",", array_values ( $new_client_ids ) );
}
//var_dump($new_client_ids);
//$destTables = array_values($clientsDestArray);
$destFields = array_values ( $tableArray );
$destTables = array_keys ( $tableArray );
//var_dump($stagingTables);
//var_dump($destClientIdFields);
//$stagingTables = array_keys($clientIdStagingArray);
foreach ( $new_clients as $new_client ) {
	for($count = 0,$dcnt=count ( $destTables ); $count < $dcnt; $count ++) {
		$target_table = $targetTables [$count];
		$staging_table = $targetTablesStagingTablesMapping [$targetTables [$count]];
		//print $staging_table;

		//print $clientIdDestArray[$staging_table];
		if($target_table !== 'admission_caregivers'){

			$whereStr = " WHERE $clientIdDestArray[$target_table] = '" . $new_client ["client_id"] . "'";

			if (! empty ( $dateFieldsArray [$target_table] )) {
				$whereStr .= " AND ($dateFieldsArray[$target_table] NOT IN (SELECT CONCAT_WS(',',$dateFieldsArray[$target_table]) FROM " . $targetTables [$count] . " WHERE $clientIdDestArray[$target_table] = '" . $new_client ["client_id"] . "') OR $dateFieldsArray[$target_table] IS NULL) ";
			}
			if (! empty ( $metaData [$count] )) {
				//$fieldsToCheck = explode(",",$destFields[$count]);
				$whereStr .= " AND $clientIdDestArray[$target_table] NOT IN (SELECT CONCAT_WS(',',$clientIdDestArray[$target_table]) FROM $targetTables[$count] WHERE $clientIdDestArray[$target_table] = '" . $new_client ["client_id"] . "')";
			}
			//$w = " INSERT INTO $destTables[$count] ($destFields[$count]) SELECT distinct $destFields[$count] FROM " . $stagingTables[$count+1] . $whereStr;
			if (! empty ( $tableArray [$target_table] )) {
				$w = "INSERT INTO $target_table ( " . $tableArray [$target_table] . " ) SELECT distinct " . $tableArray [$target_table] . " FROM " . $targetTablesStagingTablesMapping [$target_table] . $whereStr;
			}

			//print $w;
			//print "inserting into dest table<br/>";


			//print "SELECT distinct $destFields[$count] FROM " . $stagingTables[$count+1] . $whereStr . ";<br/>";
			$ret = db_exec ( $w );
		}
	}
	//print $w . '<br/>';
}
//exit;
//postprocessing for household info,medical info and medication info
//update staging tables with new medical ids
$q = new DBQuery ();
$q->addTable ( 'medical_assessment' );
$q->addQuery ( 'medical_id,medical_client_id, medical_entry_date' );
$medical_records = $q->loadList ();
//var_dump($medical_records);
//exit;
//update medical ids in staging and history tables
/*foreach ($medical_records as $medical_record)
	{
		//var_dump($medical_record);
		$sql = "UPDATE medical_staging SET new_medical_id = " . $medical_record["medical_id"] .  " WHERE medical_client_id = " . $medical_record["medical_client_id"] . " AND medical_entry_date  = '" . $medical_record["medical_entry_date"] . "'";
		//print $sql;
		db_exec($sql);
	}*/

$sql = "UPDATE medical_staging a, medical_assessment b SET a.new_medical_id = b.medical_id WHERE a.medical_client_id = b.medical_client_id AND a.medical_entry_date  = b.medical_entry_date";
db_exec ( $sql );

$q = new DBQuery ();
$q->addTable ( 'medical_staging' );
$q->addQuery ( 'medical_id, new_medical_id' );
$q->addWhere ( 'new_medical_id IS NOT NULL' );
$new_medical_records = $q->loadList ();

/*foreach ($new_medical_records as $medical_record)
	{

			$sql = "UPDATE medical_history SET medical_history_medical_id = " . $medical_record["new_medical_id"] .  " WHERE medical_history_medical_id = " . $medical_record["medical_id"];
			//print $sql . '<br/>';
			db_exec($sql);
			//print $sql;
			$sql = "UPDATE medications_history SET medications_history_medical_id = " . $medical_record["new_medical_id"] . " WHERE medications_history_medical_id = " . $medical_record["medical_id"];
			//print $sql . '<br/>';
			db_exec($sql);
			//print $sql;

	}*/

$sql = "UPDATE medical_history a, medical_staging b SET a.medical_history_medical_id = b.new_medical_id WHERE a.medical_history_medical_id = b.medical_id";
//print $sql . '<br/>';
db_exec ( $sql );
//print $sql;
$sql = "UPDATE medications_history a, medical_staging b SET a.medications_history_medical_id = b.new_medical_id WHERE a.medications_history_medical_id = b.medical_id";
//print $sql . '<br/>';
db_exec ( $sql );
//print $sql;


//update staging tables with new admission and social ids
$q = new DBQuery ();
$q->addTable ( 'admission_info' );
$q->addQuery ( 'admission_id,admission_client_id, admission_entry_date' );

$admission_records = $q->loadList ();
//update admission ids in staging  table


/*foreach ($admission_records as $admission_record)
	{

		$sql = "UPDATE admission_staging SET new_admission_id = " . $admission_record["admission_id"] .  " WHERE admission_client_id = " . addslashes($admission_record["admission_client_id"]) . " AND admission_client_id  = '" . $admission_record["admission_client_id"] . "'";
		db_exec($sql);
	}*/

$sql = "UPDATE admission_staging a , admission_info b SET a.new_admission_id = b.admission_id WHERE a.admission_client_id = b.admission_client_id ";
db_exec ( $sql );

//update social staging table
$q = new DBQuery ();
$q->addTable ( 'social_visit' );
$q->addQuery ( 'social_id,social_client_id, social_entry_date' );
$social_records = $q->loadList ();
//update admission ids in staging  table
/*foreach ($social_records as $social_record)
	{
		$sql = "UPDATE social_staging SET new_social_id = " . $social_record["social_id"] .  " WHERE social_client_id = " . $social_record["social_client_id"] . " AND social_client_id  = '" . $social_record["social_client_id"] . "'";
		db_exec($sql);
	}*/
$sql = "UPDATE social_staging a , social_visit b SET a.new_social_id = b.social_id WHERE a.social_client_id = b.social_client_id ";
db_exec ( $sql );

$q = new DBQuery ();
$q->addTable ( 'admission_staging' );
$q->addQuery ( 'admission_id, new_admission_id' );
$q->addWhere ( 'new_admission_id IS NOT NULL' );
$new_admission_records = $q->loadList ();
//var_dump($new_medical_records);
/*foreach ($new_admission_records as $admission_record)
	{


		$sql = "UPDATE household_info SET household_admission_id = " . $admission_record["new_admission_id"] .  " WHERE household_admission_id = " . $admission_record["admission_id"];
		//print $sql . '<br/>';
		db_exec($sql);
	}*/
$sql = "UPDATE household_info a , admission_staging b SET a.household_admission_id = b.new_admission_id WHERE a.household_admission_id = b.admission_id ";
db_exec ( $sql );

$q = new DBQuery ();
$q->addTable ( 'social_staging' );
$q->addQuery ( 'social_id, new_social_id' );
$q->addWhere ( 'new_social_id IS NOT NULL' );
$new_social_records = $q->loadList ();
//var_dump($new_medical_records);
/*foreach ($new_social_records as $social_record)
	{
		$sql = "UPDATE household_info SET household_social_id = " . $social_record["new_social_id"] .  " WHERE household_social_id = " . $social_record["social_id"];
		//print $sql . '<br/>';
		db_exec($sql);
	}*/

$sql = "UPDATE household_info a , social_staging b SET a.household_social_id = b.new_social_id WHERE a.household_social_id = b.social_id ";
db_exec ( $sql );

//update social services
$sql = "UPDATE social_services a , social_staging b SET a.social_services_social_id = b.new_social_id WHERE a.social_services_social_id = b.social_id ";
db_exec ( $sql );

function colsinsert($data,$cols){
	$res=array();
	foreach ($cols as $col) {
		$val=$data[$col];
		if(!empty($val)){
			$sval='"'.$val.'"';
		}else {
			$sval='NULL';
		}
		$res[]=$sval;
	}
	return '('.implode(',',$res).')';
}


$act_list=array(
'activity_facilitator'	 	=> array('src'=>'activity_facilitator_staging','fields'=>array(  'facilitator_activity_id','facilitator_training_id','facilitator_training','facilitator_name','facilitator_custom','facilitator_topic')),
'activity_caregivers'		=> array('src'=>'activity_caregivers_staging', 'fields'=>array('activity_caregivers_activity_id','activity_caregivers_caregiver_id')),
'activity_contacts'			=> array('src'=>'activity_staff_staging','fields'=>array( 'activity_contacts_activity_id','activity_contacts_contact_id')),
'activity_clients'			=> array('src'=>'activity_clients_staging','fields'=>array('activity_clients_activity_id','activity_clients_client_id')) );

foreach ($act_list as $tab_name => $cols) {
	$q = new DBQuery();
	$q->addTable($cols['src']);
	$rows=$q->loadHashListMine();
	if(count($rows) > 0){
		$rsql=array();
		$presql='insert into '.$tab_name.' ('.implode(',',$cols['fields']).') values';
		foreach ($rows as $rdata) {
			$rsql[]=colsinsert($rdata,$cols['fields']);
		}
		$res=mysql_query($presql.implode(',',$rsql));
	}

}

//construct message showing records uploaded into staging tables
$q = new DBQuery ();
$q->addTable ( 'files' );
$q->addUpdate ( 'file_upload', "{$AppUI->user_id}" );
$q->addWhere ( "file_id = $file_id" );
$q->exec ();
$q->clear ();
$AppUI->setMsg ( "Please review the records imported into the database" );
$obj->doImported();
$AppUI->redirect ( "m=clients" );
?>