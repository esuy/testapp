<?php /* COUNSELLING INFO $Id: view.php,v 1.48 2005/03/30 14:11:01 gregorerhardt Exp $ */
$counselling_id = intval( dPgetParam( $_GET, "counselling_id", 0 ) );
$client_id = intval( dPgetParam( $_GET, "client_id", 0 ) );


require_once ($AppUI->getModuleClass('clients'));

// check permissions for this record
$perms =& $AppUI->acl();
$canRead = $perms->checkModuleItem( $m, 'view', $counselling_id );
$canEdit = $perms->checkModuleItem( $m, 'edit', $counselling_id );


if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}



// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCounsellingInfo();
$canDelete = $obj->canDelete( $msg, $counselling_id );

// load the record data
$q  = new DBQuery;
$q->addTable('counselling_info');
$q->addQuery('counselling_info.*');
$q->addWhere('counselling_info.counselling_id = '.$counselling_id);
$sql = $q->prepare();
$q->clear();
$q->addTable('clinics', 'c');
$q->addQuery('c.clinic_id, c.clinic_name');
$q->addOrder('c.clinic_name');

$clinics = $q->loadHashList();

if (!db_loadObject( $sql, $obj )) {
	$AppUI->setMsg( 'Counselling Info.' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();
} else {
	$AppUI->savePlace();
}



// setup the title block

//load client
$client_id = $client_id ? $client_id : $obj->counselling_client_id;
$clientObj = new CClient();
if ($clientObj->load($client_id))
{
	$ttl = "View Counselling Info : " . $clientObj->getFullName();

}
else
{
   $ttl = "View Counselling Info ";

}

$titleBlock = new CTitleBlock($ttl, '', $m, "$m.$a" );
$entry_date = intval($obj->counselling_entry_date) ? new CDate($obj->counselling_entry_date ) :  null;
$mother_status_date = intval($obj->counselling_date_mothers_status_known) ? new CDate($obj->counselling_date_mothers_status_known ) :  null;

$boolTypes = dPgetSysVal('YesNoND');
$birthPlaces = dPgetSysVal('BirthPlaceType');
$birthTypes = dPgetSysVal('BirthType');
$ageTypes = dPgetSysVal('AgeType');
$awareStages = dPgetSysVal('StatusAwareType');
$df = $AppUI->getPref('SHDATEFORMAT');


$dob = intval( $obj->counselling_dob) ? new CDate( $obj->counselling_dob ) : null;
$child_nvp_date = intval( $obj->counselling_child_nvp_date) ? new CDate( $obj->counselling_child_nvp_date ) : null;
$child_azt_date = intval( $obj->counselling_child_azt_date) ? new CDate( $obj->counselling_child_azt_date ) : null;
$mother_date_art = intval( $obj->counselling_mother_date_art) ? new CDate( $obj->counselling_mother_date_art ) : null;
$mother_date_cd4 = intval( $obj->counselling_mother_date_cd4) ? new CDate( $obj->counselling_mother_date_cd4 ) : null;
$rapid18_date = intval( $obj->counselling_rapid18_date) ? new CDate( $obj->counselling_rapid18_date ) : null;
$determine_date = intval( $obj->counselling_determine_date) ? new CDate( $obj->counselling_determine_date ) : null;
$bioline_date = intval( $obj->counselling_bioline_date) ? new CDate( $obj->counselling_bioline_date ) : null;
$unigold_date = intval( $obj->counselling_unigold_date) ? new CDate( $obj->counselling_unigold_date ) : null;
$elisa_date = intval( $obj->counselling_elisa_date) ? new CDate( $obj->counselling_elisa_date ) : null;
$pcr1_date = intval( $obj->counselling_pcr1_date) ? new CDate( $obj->counselling_pcr1_date ) : null;
$pcr2_date = intval( $obj->counselling_pcr2_date) ? new CDate( $obj->counselling_pcr2_date ) : null;
$rapid12_date = intval( $obj->counselling_rapid12_date) ? new CDate( $obj->counselling_rapid12_date ) : null;
$other_date = intval( $obj->counselling_other_date) ? new CDate( $obj->counselling_other_date ) : null;
		
		
		
if ($canEdit) {
	$titleBlock->addCell();
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new counselling record').'" />', '',
		'<form action="?m=counsellinginfo&a=addedit" method="post">', '</form>'
	);

}
$titleBlock->addCrumb( "?m=clients", "Clients" );
if ($client_id > 0)
	$titleBlock->addCrumb( "?m=clients&a=view&client_id=$client_id", "view " . $clientObj->getFullName() );
	
if ($canEdit) {
	$titleBlock->addCrumb( "?m=counsellinginfo&a=addedit&counselling_id=$counselling_id&client_id=$client_id", "Edit" );
	
	if ($canDelete) {
		$titleBlock->addCrumbDelete( 'delete counselling record', $canDelete, $msg );
	}
}
$titleBlock->show();
?>
<script language="javascript">
<?php
// security improvement:
// some javascript functions may not appear on client side in case of user not having write permissions
// else users would be able to arbitrarily run 'bad' functions
if ($canDelete) {
?>
function delIt() {
	if (confirm( "<?php echo $AppUI->_('doDelete').' '.$AppUI->_('Counselling Record').'?';?>" )) {
		document.frmDelete.submit();
	}
}
<?php } ?>
</script>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std">

<?php if ($canDelete) {
?>
<form name="frmDelete" action="./index.php?m=counsellinginfo" method="post">
	<input type="hidden" name="dosql" value="do_counsellinginfo_aed" />
	<input type="hidden" name="del" value="1" />
	<input type="hidden" name="counselling_id" value="<?php echo $counselling_id;?>" />
</form>
<?php } ?>

<tr>
	<td valign="top" width="50%">
		
		<table cellspacing="1" cellpadding="4" width="95%">
		<tr>
			<td colspan="2" align="left">
				<strong><?php echo $AppUI->_('Details'); ?><br /></strong>
				<hr width="500" align="left" size="1" />
			</td>
		</tr>		
	    <tr>
		 <td align="left"><?php echo $AppUI->_('Center');?>:</td>
		 <td align="left" class="hilite"><?php echo $clinics[$obj->counselling_clinic]; ?></td>
       </tr>
	   <tr>
			<td nowrap="nowrap"><?php echo $AppUI->_('Registration Date');?>: </td>
			<td class="hilite">
				<?php echo $entry_date ? $entry_date->format( $df ) : "" ;?>
			</td>
		   </tr>

       <tr>
         <td nowrap="nowrap"><?php echo $AppUI->_('Referral Source');?>:</td>
         <td class="hilite">
          <?php echo @$obj->counselling_referral_source;?>
         </td>
       </tr>
      <tr>
         <td nowrap="nowrap"><?php echo $AppUI->_('Total Orphan');?>:</td>
		 <td class="hilite">
	    <?php echo $boolTypes[@$obj->counselling_total_orphan];?>
		 </td>
      </tr>  
	<tr>
			<td align="left"><?php echo $AppUI->_('Date of birth');?>:</td>
		   <td class="hilite">
	         <?php echo ($dob != NULL) ? $dob->format($df) : "";?>
		 </td>
	  </tr> 	

	<tr>
         <td align="left"><?php echo $AppUI->_('Age (years)');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->counselling_age_yrs);?>&nbsp;
		
		 </td>
    </tr>
    <tr>	
		<td><?php echo $AppUI->_('Age (months)');?>:</td>	
		<td align="left" class="hilite">
	    <?php echo dPformSafe(@$obj->counselling_age_months);?>&nbsp;
    	 </td>
		 
	</tr>
    <tr>
        <td>&nbsp;</td>	
		<td align="left" class="hilite"><?php echo $ageTypes[$obj->counselling_age_status]; ?></td>		
    </tr>
   	
	<tr>
         <td align="left"><?php echo $AppUI->_('Place of Birth');?>:</td>
		 <td align="left" class="hilite"><?php echo $birthPlaces[$obj->counselling_place_of_birth]; ?></td>		
	</tr>
    <tr>	
		<td align="left"><?php echo $AppUI->_('Area of birth');?>:</td>
		 <td align="left" class="hilite">
	    <?php echo @$obj->counselling_birth_area;?>
		 </td>
      </tr>
	 <tr>
		<td align="left"><?php echo $AppUI->_('Mode of birth');?>:</td>
		<td align="left" class="hilite"><?php echo $birthTypes[$obj->counselling_mode_birth]; ?></td>		
		
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Gestation period (months)');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_gestation_period;?></td>
     </tr>	 
	 <tr>
		<td align="left"><?php echo $AppUI->_('Birth Weight');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_birth_weight;?></td>
     </tr>
	  
	  


      <tr>
			<td align="left"><?php echo $AppUI->_('Mother aware of status');?>:</td>
			<td align="left" class="hilite"><?php echo $awareStages[$obj->counselling_mothers_status_known]; ?>
			</td>	
      </tr>
	  <tr>
		<td align="left"><?php echo $AppUI->_('Did mother receive any antenatal care?');?></td>
		<td align="left" class="hilite"><?php echo $boolTypes[$obj->counselling_mother_antenatal]; ?></td>
     </tr>	  
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother enrolled in a PMTCT program?');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->counselling_mother_pmtct]; ?></td>
     </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Illness/STI at pregnancy?');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->counselling_mother_illness_pregnancy]; ?></td>
	 </tr>
     <tr>	 
		<td align="left" valign="top">...<?php echo $AppUI->_('If Y please describe');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo @$obj->counselling_mother_illness_pregnancy_notes;?>
		</td>
     </tr>

 	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Exclusive breastfeeding? ');?></td>
		<td align="left" valign="top" class="hilite"><?php echo $boolTypes[$obj->counselling_breastfeeding]; ?></td>
	 </tr>
     <tr>	 
		<td align="left" valign="top">...<?php echo $AppUI->_('If Y, duration (months) ');?></td>
		<td align="left" class="hilite"><?php echo $obj->counselling_breastfeeding_duration;?>
		</td>
	 </tr>
     <tr>	 
		<td align="left" valign="top"><?php echo $AppUI->_('Duration other breastfeeding (months) ');?></td>
		<td align="left" class="hilite"><?php echo $obj->counselling_other_breastfeeding_duration;?>
		</td>
	  </tr>
 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Child prenatal ARV exposure?');?></td>
		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypes[$obj->counselling_child_prenatal]; ?>
        </td>
	</tr>
	<tr>
	
		<td align="left" valign="top">...<?php echo $AppUI->_('If Y single dose NVP?') ?> </td>
		<td valign="top" class="hilite">
		<?php echo $boolTypes[$obj->counselling_child_single_nvp]; ?>
		</td>		
     </tr>	
	 <tr>
	 
	 	<td align="left" valign="top">...<?php echo $AppUI->_('Date given');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $child_nvp_date ? $child_nvp_date->format( $df ) : "" ;?>
		</td>
	 </tr>
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Was AZT given?');?></td>
		
		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypes[$obj->counselling_child_azt]; ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Date AZT given');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $child_azt_date ? $child_azt_date->format( $df ) : "" ;?>
		</td>
	 </tr>
     <tr>	 
		<td nowrap="nowrap">...<?php echo $AppUI->_('Number of doses') ?> </td>
		<td align="left" class="hilite"><?php echo $obj->counselling_no_doses;?></td>		
     </tr>
 
	 
	 <tr>
		<td align="left" ><?php echo $AppUI->_('Mother in treatment program?');?></td>
		<td align="left"class="hilite" >
			<?php echo $boolTypes[$obj->counselling_mother_treatment]; ?>
        </td>
	 </tr>	 
	 <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Mother on ART in pregnancy');?>:</td>
		<td align="left" valign="top" class="hilite">
			<?php echo $boolTypes[$obj->counselling_mother_art_pregnancy]; ?>
        </td>
     </tr>	 
	 <tr>
		<td align="left" valign="top" nowrap>...<?php echo $AppUI->_('Date began ART');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $mother_date_art ? $mother_date_art->format( $df ) : "" ;?>
		
		</td>
	 </tr>
     <tr>
		<td align="left" valign="top"><?php echo $AppUI->_('Most recent maternal CD4 count');?>:</td>
		<td align="left" class="hilite"><?php echo $obj->counselling_mother_cd4;?></td>

	 </tr>	
	 <tr>
		<td align="left" valign="top">...<?php echo $AppUI->_('Date of CD4 test');?>:</td>
		<td align="left" valign="top" class="hilite">
		<?php echo $mother_date_cd4 ? $mother_date_cd4->format( $df ) : "" ;?>
		</td>
	 </tr>	  
	</table>

    </td>

		
	<td width="50%" valign="top">
		<strong><?php echo $AppUI->_('PCR Tests');?></strong>
		<table cellspacing="1" cellpadding="2"  class="std" width="100%">
		 <tr>
		    <th><?php echo $AppUI->_('Test');?></th>
		    <th><?php echo $AppUI->_('Date PCR');?></th>
		    <th><?php echo $AppUI->_('Result');?></th>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Determine');?> </td>
		    <td class="hilite"> <?php echo $determine_date ? $determine_date->format( $df ) : "" ;?></td>
		    <td class="hilite"> <?php echo $obj->counselling_determine;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Bio-line');?> </td>
		    <td class="hilite"> <?php echo $bioline_date ? $bioline_date->format( $df ) : "" ;?></td>
		    <td class="hilite"> <?php echo $obj->counselling_bioline;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Uni-gold');?> </td>
		    <td class="hilite"> <?php echo $unigold_date ? $unigold_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"> <?php echo $obj->counselling_unigold;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Elisa');?> </td>
		    <td class="hilite"> <?php echo $elisa_date ? $elisa_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"> <?php echo $obj->counselling_elisa;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('PCR 1');?> </td>
		    <td class="hilite"><?php echo  $pcr1_date ? $pcr1_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"><?php echo $obj->counselling_pcr1;?></td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('PCR 2');?> </td>
		    <td class="hilite"> <?php echo $pcr2_date ? $pcr2_date->format( $df ) : "" ;?>  </td>
		    <td class="hilite"> <?php echo $obj->counselling_pcr2;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Rapid @ 12 months');?> </td>
		    <td class="hilite"> <?php echo $rapid12_date ? $rapid12_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"><?php echo $obj->counselling_rapid12;?></td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Rapid @ 18 months');?> </td>
		    <td class="hilite"> <?php echo $rapid18_date ? $rapid18_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"> <?php echo $obj->counselling_rapid18;?> </td>
		 </tr>
		 <tr>
		    <td class="hilite"> <?php echo $AppUI->_('Other');?> </td>
		    <td class="hilite"> <?php echo $other_date ? $other_date->format( $df ) : "" ;?> </td>
		    <td class="hilite"> <?php echo $obj->counselling_other;?> </td>
		 </tr>
		 </table>
	</td>
	<?php
			require_once("./classes/CustomFields.class.php");
			$custom_fields = New CustomFields( $m, $a, $obj->counselling_id, "view" );
			$custom_fields->printHTML();
	?>
		
 </tr>
</table>


