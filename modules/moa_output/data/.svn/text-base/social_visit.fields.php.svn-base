<?php
$partShow=true;

$selects = array(
"social_clinic_id" => 'select clinic_id as id,clinic_name as name from clinics order by name asc',
"social_staff_id" => 'select contact_id as id, CONCAT_WS(" ",contact_first_name,contact_last_name) as name from contacts  where contact_id<>"13" and contact_active="1" order by name asc'
);

$fields = array(
// "social_entry_date" => "Visit Date", 
// "social_clinic_id" => array('title'=>"Center",'value'=>'sql','query'=>'select clinic_name from clinics where clinic_id="%d" limit 1','rquery'=>'select clinic_id from clinics where clinic_name="%s" limit 1'),
// "social_staff_id" => array('title'=>"Officer",'value'=>'sql','query'=>'select CONCAT_WS(", ",contact_last_name,contact_first_name) from contacts where contact_id="%d" limit 1','rquery'=>'select contact_id from contacts where lower(CONCAT_WS(", ",contact_last_name,contact_first_name))="%s" limit 1'),
"social_visit_type" =>  array('title'=>"3a.Visit Type", 'value'=>'sysval','query'=>'SocialVisitTypes'),
'social_client_health' => array('title'=>"3b.Client Health", 'value'=>'sysval','query'=>'ClientHealth'),
'social_client_status' => array('title'=>"4.Client Status", 'value'=>'sysval','query'=>'ClientStatus'),
"social_change" =>  array('title'=>"6.Any life events", 'value'=>'sysval','query'=>'YesNo'),
"social_death" =>  array('title'=>"7.Death", 'value'=>'sysval','query'=>'DeathTypes'),
"social_death_notes" => "7.Other", 
"social_death_date" => array('title'=>"7b.Date",'xtype'=>'date'), 
"social_caregiver_pri_fname" => array('title'=>"8d.First Name (pri)",'value'=>'presql-db','query'=>'careStuff2','field'=>'fname','bfield'=>'social_caregiver_pri','xrole'=>'pri'), 
"social_caregiver_pri_lname" => array('title'=>"8e.Last Name (pri)",'value'=>'presql-db','query'=>'careStuff2','field'=>'lname','bfield'=>'social_caregiver_pri','xrole'=>'pri'), 
"social_caregiver_pri_age" => array('title' => "8f.Age (pri)",'value'=>'presql-db','query'=>'careStuff2','field'=>'age','bfield'=>'social_caregiver_pri','xrole'=>'pri'), 
"social_caregiver_pri_health_status" => array('title'=>"8g.Status (pri)",'value'=>'sysval','query'=>'CaregiverHealthStatus','bfield'=>'social_caregiver_pri','xrole'=>'pri'),
"social_caregiver_pri_relationship" => array('title'=>"8h.Relationship (pri)",'value'=>'presql-db','query'=>'careStuff2','bfield'=>'social_caregiver_pri','field'=>'relationship','xrole'=>'pri'),
"social_caregiver_pri_educ_level" => array('title'=>"8i.Education Level (pri)",'value'=>'presql-db','field'=>'educ_level','bfield'=>'social_caregiver_pri'
			,'query'=>array(
					'sysval'	=>'EducationLevel',
					'func'		=>'careStuff2'
			),'xrole'=>'pri'),
"social_caregiver_pri_employment" => array('title'=>"8j.Employment (pri)",'value'=>'presql-db','field'=>'employment','bfield'=>'social_caregiver_pri','xrole'=>'pri', 'query'=>array(
					'sysval'	=>'EmploymentType',
					'func'		=>'careStuff2'	
	)),
"social_caregiver_pri_idno"	=>  array('title'=>"8l.ID# (pri)",'value'=>'presql-db','field'=>'idno','bfield'=>'social_caregiver_pri', 'query'=>'careStuff2','xrole'=>'pri'),
"social_caregiver_pri_mobile"	=>  array('title'=>"8m.Mobile# (pri)",'value'=>'presql-db','field'=>'mobile','bfield'=>'social_caregiver_pri', 'query'=>'careStuff2','xrole'=>'pri'),
"social_caregiver_pri_health_status" => array('title'=>"9a.Health Status (pri)",'value'=>'presql-db','field'=>'health_status','bfield'=>'social_caregiver_pri' ,'xrole'=>'pri',
			'query'=>array(
					'sysval'	=>'CaregiverHealthStatus',
					'func'		=>'careStuff2'
			)),
'social_caregiver_pri_health_child_impact' => array('title' => '9b.Condition is hindrance on care for the child (pri)', 'value'=>'sysval','xrole'=>'pri',
			'query'=>array(
					'sysval'	=>'YesNoND',
					'func'		=>'careStuff2'
			)),

"social_caregiver_sec_fname" => array('title'=>"8d.First Name (sec)",'value'=>'presql-db','query'=>'careStuff2','field'=>'fname','bfield'=>'social_caregiver_sec','xrole'=>'sec'), 
"social_caregiver_sec_lname" => array('title'=>"8e.Last Name (sec)",'value'=>'presql-db','query'=>'careStuff2','field'=>'lname','bfield'=>'social_caregiver_sec','xrole'=>'sec'), 
"social_caregiver_sec_age" => array('title' => "8f.Age (sec)",'value'=>'presql-db','query'=>'careStuff2','field'=>'age','bfield'=>'social_caregiver_sec','xrole'=>'sec'), 
"social_caregiver_sec_health_status" => array('title'=>"8g.Status (sec)",'value'=>'sysval','query'=>'CaregiverHealthStatus','bfield'=>'social_caregiver_sec','xrole'=>'sec'),
"social_caregiver_sec_relationship" => array('title'=>"8h.Relationship (sec)",'value'=>'presql-db','query'=>'careStuff2','bfield'=>'social_caregiver_sec','field'=>'relationship','xrole'=>'sec'),
"social_caregiver_sec_educ_level" => array('title'=>"8i.Education Level (sec)",'value'=>'presql-db','field'=>'educ_level','bfield'=>'social_caregiver_sec'
			,'query'=>array(
					'sysval'	=>'EducationLevel',
					'func'		=>'careStuff2'
			),'xrole'=>'sec'),
"social_caregiver_sec_employment" => array('title'=>"8j.Employment (sec)",'value'=>'presql-db','field'=>'employment','bfield'=>'social_caregiver_sec', 'query'=>array(
					'sysval'	=>'EmploymentType',
					'func'		=>'careStuff2'	
	),'xrole'=>'sec'),
"social_caregiver_sec_idno"	=>  array('title'=>"8l.ID# (sec)",'value'=>'presql-db','field'=>'idno','bfield'=>'social_caregiver_sec', 'query'=>'careStuff2','xrole'=>'sec'),
"social_caregiver_sec_mobile"	=>  array('title'=>"8m.Mobile# (sec)",'value'=>'presql-db','field'=>'mobile','bfield'=>'social_caregiver_sec', 'query'=>'careStuff2','xrole'=>'sec'),
"social_caregiver_sec_health_status" => array('title'=>"9a.Health Status (sec)",'value'=>'presql-db','field'=>'health_status','bfield'=>'social_caregiver_sec','xrole'=>'sec' ,
			'query'=>array(
					'sysval'	=>'CaregiverHealthStatus',
					'func'		=>'careStuff2'
			)),
'social_caregiver_sec_health_child_impact' => array('title' => '9b.Condition is hindrance on care for the child (sec)', 'value'=>'sysval','xrole'=>'sec',
	'query'=>array(
					'sysval'	=>'YesNoND',
					'func'		=>'careStuff2'
			)),


/*"social_caregiver_change" => array('title'=>"Reason", 'value'=>'sysval','query'=>'CaregiverChangeTypes'),
"social_caregiver_change_notes" => "Other", 
"social_caregiver_fname" => "First Name", 
"social_caregiver_lname" => "Last Name", 
"social_caregiver_age" => "Age", 
"social_caregiver_status" => array('title'=>"Status", 'value'=>'sysval','query'=>'CaregiverHealthStatus'),

"social_caregiver_relationship" => "Relationship to Child", 
"social_caregiver_education" => array('title'=>"Education level", 'value'=>'sysval','query'=>'EducationLevel'),
"social_caregiver_employment" => array('title'=>"Employment", 'value'=>'sysval','query'=>'EmploymentType'),
"social_caregiver_income" => array('title'=>"Income level", 'value'=>'sysval','query'=>'IncomeLevels'),
"social_caregiver_idno" => "ID #", 
"social_caregiver_mobile" => "Mobile #", 
"social_caregiver_health" => array('title'=>"Health", 'value'=>'sysval','query'=>'CaregiverHealthChanges'),
"social_caregiver_health_child_impact" => array('title'=>"Condition is hindrance on care for the child", 'value'=>'sysval','query'=>'YesNoND'),*/
"social_residence_mobile" => "10a.Mobile ", 
"social_residence" => "10b.physical address/landmarks)", 
'social_household' =>array('title'=>'11.Change in household composition','value'=> 'plural',
			'query'=>array(
			'set' => 'select * from household_info where household_client_id="%d"',
			'fields'=>array(
						'household_name'		=>'Name',
						'household_yob'			=>array('title'=>'YOB','xtype'=>'date'),
						'household_gender'		=>array('title'=>'Gender','value'=>'sysval','query'=>'GenderType'),
						'household_relationship'=>'Relationship to child',
						'household_notes'		=>'if registered, ADM #',
						'household_custom'		=>'Comments'
					)
		)
),
"social_caregiver_employment_change" =>  array('title'=>"12a.Change due to employment type of primary caregiver", 'value'=>'sysval','query'=>'YesNo'),
"social_caregiver_new_employment" => array('title'=>"12b.If yes, new employment", 'value'=>'sysval','query'=>'EmploymentType'),
"social_caregiver_new_employment_desc" => "12b.Employment - Other", 
"social_caregiver_new_income" => array('title'=>"12c.New income range", 'value'=>'sysval','query'=>'IncomeLevels'),
"social_school_attendance" => array('title'=>"13a.School attendance", 'value'=>'sysval','query'=>'ChildSchoolStatus'),
"social_school" => array('title'=>"13b.New school level", 'value'=>'sysval','query'=>'ChildSchoolLevels'),
'social_class_form' => '13c.Current class/form',
"social_reason_not_attending" => array('title'=>"13d.If not attending, why", 'value'=>'sysval','query'=>'ReasonsNotAttendingSchool'),
"social_reason_not_attending_notes" => "13e.Not attending - Other",
'social_services'=>array('title'=>'14.Needs Supported','value'=>'plural',
			'query'=>array(
				'set'=>'select * from social_services where social_services_social_id="%d"',
				'fields'=>array(
					'social_services_service_id'=>array('title'=>'Service','value'=>'sysval','query'=>'ServiceTypes'),
					'social_services_date'=>array('title'=>'Date','xtype'=>'date'),
					'social_services_notes'=>'Comments',
					'social_services_value'=>'Value'
				)
			)			
),
"social_any_needs" => array('title'=>"15.Any Needs", 'value'=>'sysval','query'=>'YesNo'),
"social_relocation" => array('title'=>"16a.Relocation", 'value'=>'sysval','query'=>'RelocationType','mode'=>'multi'),
"social_iga" => array('title'=>"16b.IGA", 'value'=>'sysval','query'=>'IGAOptions','mode'=>'multi'),
"social_placement" => array('title'=>"16c.Placement", 'value'=>'sysval','query'=>'PlacementType','mode'=>'multi'),
"social_permanency_value" => "16c.Placement Value", 
"social_succession_planning" =>  array('title'=>"17.Succession Planning", 'value'=>'sysval','query'=>'SuccessionPlanningTypes','mode'=>'multi'),
"social_succession_value" => "17.Succession Value", 
"social_legal" => array('title'=>"18.Legal", 'value'=>'sysval','query'=>'LegalIssues','mode'=>'multi'),
"social_legal_value" => "18.Legal Value", 
"social_nursing" =>  array('title'=>"19.Nursing/Palliative Care", 'value'=>'sysval','query'=>'NursingCareTypes','mode'=>'multi'),
"social_nursing_value" => "19.Nursing Value", 
"social_transport" => array('title'=>"20.Transport", 'value'=>'sysval','query'=>'TransportNeeds','mode'=>'multi'),
"social_transport_value" => "20.Transport Value", 
"social_education" => array('title'=>"21.Education", 'value'=>'sysval','query'=>'EducationNeeds','mode'=>'multi'),
"social_education_value" => "21.Education Value", 
"social_food" => array('title'=>"22.Food",'value'=>'sysval','query'=>'FoodNeeds','mode'=>'multi'),
"social_food_value" => "22.Food Value", 
"social_rent" => array('title'=>"23.Rent", 'value'=>'sysval','query'=>'RentNeeds','mode'=>'multi'),
"social_rent_value" => "23.Rent Value", 
"social_solidarity" => array('title'=>"24.Solidarity",  'value'=>'sysval','query'=>'SolidarityNeeds','mode'=>'multi'),
"social_solidarity_value" => "24.Solidarity Value", 
"social_direct_support" => array('title'=>"25.Direct Support",  'value'=>'sysval','query'=>'DirectSupportNeeds','mode'=>'multi'),
"social_directsupport_value" => "25.Direct Support Value",
"social_directsupport_value" => "25b.Direct Support - Other", 
"social_medical_support" => array('title'=>"28.Medical Support", 'value'=>'sysval','query'=>'MedicalSupportNeeds','mode'=>'multi'),
"social_medicalsupport_value" => "26.Medical support value", 
"social_medical_support_desc" => "26b.Medical Support - Other", 
'social_training' => array('title'=>"27.Training Support", 'value'=>'sysval','query'=>'TrainingSupport'),
'social_training_value' => '27.Training Support Value',
'social_training_desc' => '27b.Training Support - Other',
"social_other_support" => "28.Other Needs Assessed ", 
"social_othersupport_value" => "28.Other Support Value", 

"social_risk_level" => array('title'=>"29a.New Risk level", 'value'=>'sysval','query'=>'RiskLevel'),

/* "social_services_date" => array('title'=>'Service rendered date',
				'value'=>'sql',
				'query'=>'select social_services_date from social_services where social_services_client_id="%d" and social_services_social_id="%d"',
				'xtype'=>'date'),
"social_services_service" => array('title'=>'Service rendered type',
				'value'=>'sql-db',
				'query'=>array(
    		    		'sql'=>'select social_services_service_id from social_services where social_services_client_id="%d" and social_services_social_id="%d"',
					    'sysval'=>'ServiceTypes'
				)

			),      */
// 'social_next_visit' => array('title'=>'29b.Next appointment','xtype'=>'date'),
// "social_referral" => array('title'=>"30.Referral to",'value'=>'sysval','query'=>'ClinicalReference'),
"social_notes" => "31.Comments",



);
?>
