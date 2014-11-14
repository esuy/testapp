<?php

$client_id = intval( dPgetParam( $_GET, "client_id", 0 ));
$client_type = intval( dPgetParam( $_GET, "client_type", 3 ));


if 	($contact_unique_update == 0)
  $contact_unique_update = uniqid("");
	
$perms = & $AppUI->acl();

if ($client_id)
  $canEdit = $perms->checkModuleItem( $m, "edit", $client_id );
else
  $canEdit = $perms->checkModule($m, "add");

if (!$canEdit)
{
  $AppUI->redirect("m=public&a=access_denied");
}



$q  = new DBQuery;
$q->addTable('clients');
$q->addQuery('clients.*');
$q->addWhere('clients.client_id = '.$client_id);
$sql = $q->prepare();
$q->clear();

$obj = new CClient();

if (!db_loadObject($sql, $obj) && ($client_id > 0))
{
  	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
}
//load all sales reps
$q  = new DBQuery;
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 13');
$q->addOrder('c.contact_first_name');

//load contacts
$chw_contacts = arrayMerge(array(0=> '-Select CHW -'),$q->loadHashList());
$q->clear();
$q->addTable('contacts', 'c');
$q->addQuery('c.contact_id');
$q->addQuery('CONCAT_WS(", ",c.contact_last_name,c.contact_first_name)');
$q->innerJoin('client_contacts', 'b', 'b.client_contacts_contact_id = c.contact_id');
$q->addWhere('b.client_contacts_contact_type = 14');
$q->addOrder('c.contact_first_name');


$shw_contacts = arrayMerge(array(0=> '-Select SHW -'),$q->loadHashList());

//load status stuff
$status = arrayMerge(array(-1=>'-Select Current Client Status-'), dPgetSysVal('ClientStatus'));
//load priority stuff
$priority = arrayMerge(array(-1=>'-Select Current Client Priority-'), dPgetSysVal('ClientPriority'));

//load cities
$citiesArray = arrayMerge(array(-1=>'-Select City-'), dPgetSysVal('ClientCities'));
//load clinics

$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');

$clinics = arrayMerge(array(0=> '-Select Center -'),$q->loadHashList());

$boolTypes = dPgetSysVal('YesNo');
$birthPlaces = dPgetSysVal('BirthPlaceType');
$birthTypes = dPgetSysVal('BirthType');
$ageTypes = dPgetSysVal('AgeType');
$awareStages = dPgetSysVal('StatusAwareType');
/*$q->clear();
$q->addTable('client_status');
$q->addQuery('client_status_id');
$q->addQuery('client_status_desc');
$q->addOrder('client_status_desc');


//load priorities
$q->clear();
$q->addTable('client_priority');
$q->addQuery('client_priority_id');
$q->addQuery('client_priority_desc');
$q->addOrder('client_priority_id');
$priority = arrayMerge(array(0=>'-Select Client Class-'), $q->loadHashList());
//load company types
$types = dPgetSysVal('ClientStatus');
$type = $types[$client_type];

//load cities
$citiesArray = arrayMerge(array(0=>'-Select City-'), dPgetSysVal('ClientCities'));
*/
//$ttl = "$type :: ";
$ttl .= $client_id > 0 ? "Edit Intake & PCR" : "New Intake & PCR";

$titleBlock = new CTitleBlock( $ttl, '', $m, "$m.$a" );
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id != 0)
  $titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "View" );
$titleBlock->show();

?>
<script language="javascript">
var selected_contacts_id = "<?php echo $obj->main_contacts; ?>";
var contact_unique_update = "<?php echo $contact_unique_update; ?>";
var client_id = '<?php echo $obj->client_id;?>';
var client_name_msg = "<?php echo $AppUI->_('Please enter a name for the client');?>";


var calendarField = '';
var calWin = null;


function popCalendar( field ){
	calendarField = field;
	idate = eval( 'document.editFrm.log_' + field + '.value' );
	window.open( 'index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin', 'top=250,left=250,width=251, height=220, scollbars=false, resizable' );
}

function setCalendar( idate, fdate ) 
{
	fld_date = eval( 'document.editFrm.log_' + calendarField );
	fld_fdate = eval( 'document.editFrm.client_' + calendarField );
	fld_date.value = idate;
	fld_fdate.value = fdate;
}

function checkDate()
{
           if (document.frmDate.log_start_date.value == "" || document.frmDate.log_end_date.value== ""){
                alert("<?php echo $AppUI->_('You must fill fields', UI_OUTPUT_JS) ?>");
                return false;
           } 
           return true;
}

function popClinic() {
//        window.open('./index.php?m=public&a=selector&dialog=1&callback=setCompany&table=companies', 'company','left=50,top=50,height=250,width=400,resizable');
	window.open("./index.php?m=clients&a=select_client_clinic&dialog=1&table_name=clinics&clinic_id=<?php echo $clinic_detail['clinic_id'];?>", "clinic", "left=50,top=50,height=250,width=400,resizable");
}

function setClinic( key, val ){
	var f = document.editFrm;
 	if (val != '') {
    	f.contact_company.value = key;
			f.client_clinic_name.value = val;
    	if ( window.clinic_id != key )
		{
    		f.contact_department.value = "";
				f.contact_department_name.value = "";
    	}
    	window.clinic_id = key;
    	window.clinic_value = val;
    }
}
</script>
<?php
$date_reg = date("Y-m-d");
$entry_date = intval( $date_reg) ? new CDate( dPgetParam($_REQUEST, "client_entry_date", date("Y-m-d") ) ) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

?>
 <form name="editFrm" action="?m=clients&client_id=<?php echo $client_id; ?>" method="post">
   <input type="hidden" name="dosql" value="do_newclient_aed" />
   <input type="hidden" name="client[client_id]" value="<?php echo $client_id; ?>" />
   <input type="hidden" name="client[client_type]" value="<?php echo $client_type; ?>" />
   <input type="hidden" name="client[client_date_entered]" value="<?php echo date('Y-m-d h:i:s A'); ?>" />
   <table cellspacing="1" cellpadding="1" border="0" width="100%" class="std">
   <td align="left">
	<tr>
	  <td>
		<table>
			<tr>
			 <td align="left" nowrap><?php echo $AppUI->_('Admission No');?>: </td>
			  <td>
					<input type="text" class="text" name="client[client_adm_no]" id="client_adm_no" value="<?php echo dPformSafe(@$obj->client_adm_no);?>"
					size="20" maxlength="255" /> (<?php echo $AppUI->_('required');?>)
			</td>
		   </tr>
        <tr>
			<td align="left"><?php echo $AppUI->_('Center');?>: </td>
			<td>
				<?php echo arraySelect( $clinics, 'client[client_clinic]', 'size="1" class="text"', @$obj->client_clinic ? $obj->client_clinic:0); ?>        
			</td>
		</tr>		   
		   <tr>
			 <td align="left" nowrap><?php echo $AppUI->_('Date');?>: </td>
			<td>
				<input type="hidden" name="log_entry_date" value="<?php echo $entry_date ? $entry_date->format( FMT_TIMESTAMP_DATE ) : "" ;?>" />
				<input type="text" name="client_entry_date" value="<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>" class="text" />
				<a href="#" onClick="popCalendar('entry_date')">
				<img src="./images/calendar.gif" width="24" height="12" alt="<?php echo $AppUI->_('Calendar');?>" border="0" ></a>
			</td>
		   </tr>
		   <tr>
			 <td align="left"><?php echo $AppUI->_('First Name');?>: </td>
			  <td>
					<input type="text" class="text" name="client[client_first_name]" id="client_first_name" value="<?php echo dPformSafe(@$obj->client_first_name);?>"
					size="50" maxlength="255" /> 
			</td>
		   </tr>
		   <tr>
			 <td align="left"><?php echo $AppUI->_('Last Name');?>: </td>
			  <td>
					<input type="text" class="text" name="client[client_last_name]" id="client_last_name" value="<?php echo dPformSafe(@$obj->client_last_name);?>"
					size="50" maxlength="255" /> 
			</td>
		   </tr>
		   <tr>
			 <td align="left"><?php echo $AppUI->_('Other Name(s)');?>: </td>
			  <td>
					<input type="text" class="text" name="client[client_other_name]" id="client_other_name" value="<?php echo dPformSafe(@$obj->client_other_name);?>"
					size="50" maxlength="255" /> 
			</td>
		   </tr>
		
        <tr>
			<td align="left"><?php echo $AppUI->_('Current Status');?>: </td>
			<td>
				<?php echo arraySelect( $status, 'client[client_status]', 'size="1" class="text"', @$obj->client_status ? $obj->client_status:0); ?>        
			</td>
		</tr>
		<tr>
         <td align="left"><?php echo $AppUI->_('Referral Source');?>:</td>
         <td align="left">
          <input type="text" class="text" name="counselling[counselling_referral_source]" value="<?php echo @$row->counselling_referral_source;?>" maxlength="150" size="20" />
         </td>
       </tr>
       </table>	  
        </td>
       <td>
       <table>
   <tr>
		<td align="left"><?php echo $AppUI->_('Total Orphan');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_total_orphan]", 'onclick=toggleButtons()', $row->counselling_total_orphan ? $row->counselling_total_orphan : -1, $identifiers ); ?></td>
     </tr>	  

     <tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
			<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_dob]" id="counselling_dob" value="<?php echo $obj->counselling_dob;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
			
	  </tr> 
<tr>
         <td valign="top"><?php echo $AppUI->_('Age');?>:</td>
		 <td>
		 <table>
		  <tr>
		   <td>
	       <input type="text" class="text" name="counselling[counselling_age_yrs]" value="<?php echo dPformSafe(@$obj->counselling_age_yrs);?>" maxlength="30" size="20" />
		    <?php echo $AppUI->_('years');?>:
		    </td>
          </tr>
		  <tr>
		   <td>
	         <input type="text" class="text" name="counselling[counselling_age_months]" value="<?php echo dPformSafe(@$obj->counselling_age_months);?>" maxlength="30" size="20" />
		      <?php echo $AppUI->_('months');?>:
		   </td>
		   </tr>
		<tr>
		<td><?php echo arraySelectRadio($ageTypes, "counselling[counselling_age_status]", 'onclick=toggleButtons()', $row->counselling_age_status ? $row->counselling_age_status : -1, $identifiers ); ?></td>		
		</tr>
		</table>
	 </tr>
    	
	<tr>
         <td align="left"><?php echo $AppUI->_('Place of Birth');?>:</td>
		 <td align="left">
		 <?php echo arraySelectRadio($birthPlaces, "counselling[counselling_place_of_birth]", 'onclick=toggleButtons()', $row->counselling_place_of_birth ? $row->counselling_place_of_birth : -1, $identifiers ); ?></td>

		 </td>
      </tr>       
		</table>
    </td>
   </tr>
  <tr>
   <td>
    <table>   
	 <tr>
		<td align="left"><?php echo $AppUI->_('Area of birth');?>:</td>
		 <td align="left">&nbsp;&nbsp;
	    <input type="text" class="text" name="counselling[counselling_birth_area]" value="<?php echo @$row->counselling_birth_area;?>" maxlength="150" size="20" />
		 </td>
		 </tr>  
	 <tr>
		<td align="left"><?php echo $AppUI->_('Mode of birth');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($birthTypes, "counselling[counselling_mode_birth]", 'onclick=toggleButtons()', $row->counselling_mode_birth ? $row->counselling_mode_birth : -1, $identifiers ); ?></td>		
		
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Gestation period (months)');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_gestation_period]" id="counselling_gestation_period" value="<?php echo $obj->counselling_gestation_period;?>" maxlength="150" size="20"/></td>
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_birth_weight]" id="counselling_birth_weight" value="<?php echo $obj->counselling_birth_weight;?>" maxlength="150" size="20"/></td>
     </tr>
	  
	  


      <tr>
			<td align="left"><?php echo $AppUI->_('Mother aware of status');?>:</td>
			<td align="left" nowrap>&nbsp;&nbsp;<?php echo arraySelectRadio($awareStages, "counselling[counselling_mothers_status_known]", 'onclick=toggleButtons()', $row->counselling_mothers_status_known ? $row->counselling_mothers_status_known : -1, $identifiers ); ?>
			</td>	
      </tr>
	  <tr>
		<td align="left" nowrap><?php echo $AppUI->_('Did mother receive any antenatal care');?>:</td>
		<td align="left">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_antenatal]", 'onclick=toggleButtons()', $row->counselling_mother_antenatal ? $row->counselling_mother_antenatal : -1, $identifiers ); ?></td>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother enrolled in a PMTCT program');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_pmtct]", 'onclick=toggleButtons()', $row->counselling_mother_pmtct ? $row->counselling_mother_pmtct : -1, $identifiers ); ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Illness/STI at pregnancy');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_illness_pregnancy]", 'onclick=toggleButtons()', $row->counselling_mother_illness_pregnancy ? $row->counselling_mother_illness_pregnancy : -1, $identifiers ); ?></td>
	 	
     </tr>
	 <tr>
	 <td align="left" valign="top"><?php echo $AppUI->_('If Y please describe');?>:</td>
		<td align="left" valign="top">
		<textarea cols="30" rows="5" class="textarea" name="counselling[counselling_mother_illness_pregnancy_notes]"><?php echo @$obj->counselling_mother_illness_pregnancy_notes;?></textarea>
		</td>
	 </tr> 	   
	</table>
    </td>
    <td>
	<table>
	<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Exclusive breastfeeding');?></td>
		<td align="left" valign="top"><?php echo arraySelectRadio($boolTypes, "counselling[counselling_breastfeeding]", 'onclick=toggleButtons()', $row->counselling_breastfeeding ? $row->counselling_breastfeeding : -1, $identifiers ); ?></td>
	</tr>
    <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('If Y, duration (months)');?></td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_breastfeeding_duration]" id="counselling_breastfeeding_duration" value="<?php echo $obj->counselling_breastfeeding_duration;?>" maxlength="150" size="20"/></td>
	</tr>
    <tr>	
		<td align="left" valign="top"><?php echo $AppUI->_('Duration other breastfeeding (months)');?></td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_other_breastfeeding_duration]" id="counselling_other_breastfeeding_duration" value="<?php echo $obj->counselling_other_breastfeeding_duration;?>" maxlength="150" size="20"/>
		
		</td>
	  </tr>
<tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Child perinatal ARV exposure');?>:</td>
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_perinatal]", 'onclick=toggleButtons()', $row->counselling_child_perinatal ? $row->counselling_child_perinatal : -1 ); ?>
        </td>
	</tr>
	<tr>
	
		<td align="left" valign="top"><?php echo $AppUI->_('If Y single dose NVP?') ?> </td>
		<td valign="top">
		<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_single_nvp]", 'onclick=toggleButtons()', $row->counselling_child_single_nvp? $row->counselling_child_single_nvp : -1 ); ?>
		</td>		
     </tr>	
	 <tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('Date given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_child_nvp_date]" id="counselling_child_nvp_date" value="<?php echo $obj->counselling_child_nvp_date;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>

		
     </tr>
	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Was AZT given (normally twice daily for one week after birth)');?>:</td>
		
		<td align="left" valign="top">
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_child_azt]", 'onclick=toggleButtons()', $row->counselling_child_azt? $row->counselling_child_azt : -1); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Date AZT given');?>:</td>
		<td align="left" valign="top"><input type="text" class="text" name="counselling[counselling_child_azt_date]" id="counselling_child_azt_date" value="<?php echo $obj->counselling_child_azt_date;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	</tr>
    <tr>	
		<td nowrap><?php echo $AppUI->_('Number of doses') ?> </td>
		<td align="left"><input type="text" class="text" name="counselling[counselling_no_doses]" id="counselling_no_doses" value="<?php echo $obj->counselling_no_doses;?>" maxlength="150" size="20"/></td>		
     </tr>	  
	</table>
    </td>	
	</tr>
	<tr>
	<td>
	 <table>
     <tr>
		<td align="left" ><?php echo $AppUI->_('Mother in treatment program');?>:</td>
		<td align="left" >&nbsp;&nbsp;
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_treatment]", 'onclick=toggleButtons()', $row->counselling_mother_treatment ? $row->counselling_mother_treatment : -1 ); ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother on ART in pregnancy');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;
			<?php echo arraySelectRadio($boolTypes, "counselling[counselling_mother_art_pregnancy]", 'onclick=toggleButtons()', $row->counselling_mother_art_pregnancy ? $row->counselling_mother_art_pregnancy : -1 ); ?>
        </td>
     </tr>	 
	 <tr>
		<td align="left" valign="top" nowrap><?php echo $AppUI->_('Date began ART');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_date_art]" id="counselling_mother_date_art" value="<?php echo $obj->counselling_mother_date_art;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	 </tr>	 
	 </table>
	</td>
	<td>
	<table>
    <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Most recent maternal CD4 count');?>:</td>
		<td align="left">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_cd4]" id="counselling_mother_cd4" value="<?php echo $obj->counselling_mother_cd4;?>" maxlength="150" size="20"/></td>

	 </tr>	
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Date of CD4 test');?>:</td>
		<td align="left" valign="top">&nbsp;&nbsp;<input type="text" class="text" name="counselling[counselling_mother_date_cd4]" id="counselling_mother_date_cd4" value="<?php echo $obj->counselling_mother_date_cd4;?>" maxlength="150" size="20"/>&nbsp;dd/mm/yyyy</td>
	 </tr>	
	 </table>
	</td>
	</tr>
<tr>
	 
	 	<td align="left" valign="top"><?php echo $AppUI->_('History');?>:</td>
</tr>
<tr>		
		<td colspan="2">
		<textarea cols="150" rows="10" class="textarea" name="counselling[counselling_history]"><?php echo @$obj->counselling_history;?></textarea>
		</td>
     </tr>	

	 
  </table>
   <tr>
   		<td><input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onClick="javascript:history.back(-1);" /></td>
		<td colspan="5" align="right"><input type="button" name="btnFuseAction" value="<?php echo $AppUI->_('submit');?>" class="button" onClick="javascript:submitIt(document.editFrm)" /></td>
   </tr>

<?php
	if (isset($_GET['tab']))
		$AppUI->setState('ClientAeTabIdx', dPgetParam($_GET, 'tab', 0));

		$moddir = $dPconfig['root_dir'] . '/modules/clients/';	
	
	$tab = $AppUI->getState('ClientAeTabIdx', 0);
	$tabBox =& new CTabBox("?m=clients&a=addedit&client_id=$client_id", "", $tab, "");
	$tabBox->add($moddir . "ae_counselling", "PCR Tests");
	//$tabBox->add($moddir . "ae_contacts", "Contacts");
	$tabBox->loadExtras('clients', 'addedit');
	$tabBox->show('', true);
?>
</td>
</table>
</form>