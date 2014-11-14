<?php

global $dPconfig,$loadFromTab;
global $AppUI, $clinic_id, $obj,$tab;
global $convert;


require_once $AppUI->getModuleClass('contacts');

$contacts = $obj->getContacts();

if ( count($contacts) > 0)
{
	
	$contactsList = $contacts;
	if (count($contacts) > 1)
	{
		$contactsList = implode (",",$contacts);
	}

	$q = new DBQuery;
	$q->addTable('clinic_contacts', 'cc');
	$q->leftJoin ('contact_types', 'ct', 'ct.typ_id = cc.clinic_contacts_contact_type');
	$q->leftJoin ('contacts', 'c', 'c.contact_id = cc.clinic_contacts_contact_id');
	$q->addQuery ('c.contact_id, c.contact_title, c.contact_first_name, c.contact_last_name, c.contact_email, ct.typ_desc');
	$q->addWhere("cc.clinic_contacts_clinic_id = $clinic_id");
	$q->addWhere("cc.clinic_contacts_contact_id in ($contactsList)");


	$w='';
	$sql= $q->prepare();
	//print_r($sql);
	$q->clear();
	$contacts = db_loadList( $sql );
}

?>
<script language="javascript">
var contact_unique_update = document.editFrm.insert_id.value;
function popAddContacts() 
{
	window.open('./index.php?m=public&a=contact_adder&type_ui_active=1&dialog=1&contact_unique_update='+contact_unique_update, 'contacts','height=600,width=800,resizable,scrollbars=yes');
}
</script>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<form name="contactsEdit" action="?m=clinics&a=addedit&clinic_id=<?php echo $clinic_id; ?>" method="post">
<?php
if (count($contacts) == 0)
{
  echo $AppUI->_('No data available') . '<br />' .  $AppUI->getMsg();
}
else
{
?>
<tr>
	<th><?php echo $AppUI->_( 'Name' );?></td>
	<th><?php echo $AppUI->_( 'Contact e-mail' );?></td>
	<th><?php echo $AppUI->_( 'Contact Role' );?></td>
</tr>
<?php

	foreach ($contacts as $contact)
    {
        
		$w .= '<tr><td>';
		$w .= '<a href="./index.php?m=contacts&a=addedit&contact_id='.$contact["contact_id"].'&clinic_id='.$clinic_id. '">'. $contact["contact_title"].  $contact["contact_first_name"]." ". $contact["contact_last_name"].'</a>';
		$w .= '<td><a href="mailto:'.$contact["contact_email"] .'">' .$contact["contact_email"] .'</a></td>';
		$w .= '<td>'.$contact["typ_desc"] .'</td>';
		$w .= '</tr>';
	}
}


	$w .= '<tr><td colspan="3" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new contact' ).'" onClick="javascript:popAddContacts()">';
	$w .= '&nbsp;<input type="button" class=button value="'.$AppUI->_( 'select pre-existing contact' ).'" onClick="javascript:window.location=\'./index.php?m=contacts&a=addedit&clinic_id='.$clinic_id.'&clinic_name='.$obj->clinic_name.'\'">';
	$w .= '</td></tr>';
	echo $w;
?>
</form>
</table>
<script language="javascript">
 subForm.push(new FormDefinition(<?php echo $tab;?>, document.contactsEdit, checkDetail, saveDetail));
</script>
