<?php

global $AppUI, $client_id, $obj;

require_once $AppUI->getModuleClass('contacts');


if (!isset($contacts) || $contacts == 0)
{
	$q = new DBQuery;
	$q->addTable('client_contacts', 'cc');
	$q->addQuery ('DISTINCT cc.client_contacts_contact_id');
	$q->addWhere("cc.client_contacts_client_id = $client_id");


	$w='';
	$sql= $q->prepare();
	//print_r($sql);
	$q->clear();
	$contacts = db_loadColumn( $sql );
	
}

if (count($contacts) == 0)
{
  echo $AppUI->_('No data available') . '<br />' .  $AppUI->getMsg();
}
else
{
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th><?php echo $AppUI->_( 'Name' );?></td>
	<th><?php echo $AppUI->_( 'Contact e-mail' );?></td>
	<th><?php echo $AppUI->_( 'Phone Contact (land)' );?></td>
	<th><?php echo $AppUI->_( 'Mobile' );?></td>
	<th><?php echo $AppUI->_( 'Contact Role' );?></td>
</tr>
<?php
	foreach ($contacts as $contact)
    {
        $contactObj = new CContact();
		$contactObj->load($contact);
		//print_r($contactObj);
		
		$w .= '<tr><td>';
		$w .= '<a href="./index.php?m=contacts&a=view&contact_id='.$contactObj->contact_id.'&client_id='.$client_id. '">'. $contactObj->getFullName().'</a></td>';
		$w .= '<td><a href="mailto:'.$contactObj->contact_email .'">' .$contactObj->contact_email .'</a></td>';
		$w .= '<td><a href="./index.php?m=contacts&a=view&contact_id='.$contactObj->contact_id.'&client_id='.$client_id. '">'. $contactObj->contact_phone.'</a></td>';
		$w .= '<td><a href="./index.php?m=contacts&a=view&contact_id='.$contactObj->contact_id.'&client_id='.$client_id. '">'. $contactObj->contact_mobile.'</a></td>';
		$w .= '<td><a href="./index.php?m=contacts&a=view&contact_id='.$contactObj->contact_id.'&client_id='.$client_id. '">'. $contactObj->getContactRolesDesc($client_id).'</a></td>';
		$w .= '</tr>';
	}
}

	$w .= '<tr><td colspan="5" align="right" valign="top" style="background-color:#ffffff">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new contact' ).'" onClick="javascript:window.location=\'./index.php?m=contacts&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new caregiver' ).'" onClick="javascript:window.location=\'./index.php?m=caregivers&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '<input type="button" class=button value="'.$AppUI->_( 'add new relative' ).'" onClick="javascript:window.location=\'./index.php?m=relatives&a=addedit&client_id='.$client_id.'&client_name='.$obj->getFullName().'\'">';
	$w .= '&nbsp;<input type="button" class=button value="'.$AppUI->_( 'select pre-existing contact' ).'" onClick=javascript:popContacts(selected_fw_contacts_id);>';
	$w .= '</td></tr>';
	echo $w;

?>
</table>