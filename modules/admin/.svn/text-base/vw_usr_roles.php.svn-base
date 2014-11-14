<?php /* ADMIN $Id: vw_usr_roles.php,v 1.10 2005/03/14 02:28:06 gregorerhardt Exp $ */
GLOBAL $AppUI, $user_id, $role_id, $role_name, $canEdit, $canDelete, $tab, $baseDir;
//$roles
// Create the roles class container
require_once "$baseDir/modules/system/roles/roles.class.php";

$perms =& $AppUI->acl();
$user_roles = $perms->getUserRoles($user_id);
$crole =& new CRole;
$roles = $crole->getRoles();
// Format the roles for use in arraySelect
$roles_arr = array();
foreach ($roles as $role) {
  $roles_arr[$role['id']] = $role['name'];
}

?>

<script language="javascript">
<?php
// security improvement:
// some javascript functions may not appear on client side in case of user not having write permissions
// else users would be able to arbitrarily run 'bad' functions
if ($canEdit) {
?>
function delIt(id) {
	if (confirm( 'Are you sure you want to delete this role?' )) {
		var f = document.frmPerms;
		f.del.value = 1;
		f.role_id.value = id;
		f.submit();
	}
}
<?php
}?>

</script>

<table width="100%" border="0" cellpadding="2" cellspacing="0">
<tr>
<td width="50%" valign="top">

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th width="100%"><?php echo $AppUI->_('Role');?></th>
	<th>&nbsp;</th>
</tr>

<?php
foreach ($user_roles as $row){
	$buf = '';
    if (empty($role_id))
	{
		$role_id = 	$row['id'];
		$role_name = $row['name'];
	}
	$style = '';
	$buf .= "<td><a href='?m=admin&a=viewuser&user_id=$user_id&role_id=". $row['id'] . "&role_name=\"". $row['name'] . "\"'>" . $row['name'] . "</a></td>";

	$buf .= '<td nowrap>';
	if ($canEdit) {
		$buf .= "<a href=\"javascript:delIt({$row['id']});\" title=\"".$AppUI->_('delete')."\">"
			. dPshowImage( './images/icons/stock_delete-16.png', 16, 16, '' )
			. "</a>";
	}
	$buf .= '</td>';
	
	echo "<tr>$buf</tr>";
}
?>

</table>

</td><td width="50%" valign="top">

<?php if ($canEdit) {?>

<table cellspacing="1" cellpadding="2" border="0" class="std" width="100%">
<form name="frmPerms" method="post" action="?m=admin">
	<input type="hidden" name="del" value="0">
	<input type="hidden" name="dosql" value="do_userrole_aed">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
	<input type="hidden" name="user_name" value="<?php echo $user_name;?>">
	<input type="hidden" name="role_id" value="">
<tr>
	<th colspan='2'><?php echo $AppUI->_('Add Role');?></th>
</tr>
<tr>
	<td colspan='2' width="100%"><?php echo arraySelect($roles_arr, 'user_role', 'size="1" class="text"','', true);?></td>
</tr>
<tr>
	<td>
		<input type="reset" value="<?php echo $AppUI->_('clear');?>" class="button" name="sqlaction" onClick="clearIt();">
	</td>
	<td align="right">
		<input type="submit" value="<?php echo $AppUI->_('add');?>" class="button" name="sqlaction2">
	</td>
</tr>
</table>
</form>

<?php } ?>

</td>
</tr>
</table>
<table width="50%">
<tr><td width="50%" valign="top">
<strong><?php echo $AppUI->_('Permissions for role: ' . $role_name)?></strong>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th width="100%"><?php echo $AppUI->_('Item');?></th>
	<th nowrap><?php echo $AppUI->_('Type');?></th>
	<th nowrap><?php echo $AppUI->_('Status');?></th>
</tr>

<?php

$perms =& $AppUI->acl();
$module_list = $perms->getModuleList();
$pgos = array();
$count = 0;
$modules = array();
foreach ($module_list as $module)
  $modules[$module['type'] . ',' . $module['id']] = $module['name'];

//Pull User perms
$role_acls = $perms->getRoleACLs($role_id);
if (! is_array($role_acls)) {
  $role_acls = array(); // Stops foreach complaining.
}
$perm_list = $perms->getPermissionList();

foreach ($role_acls as $acl){
	$buf = '';
	$permission = $perms->get_acl($acl);

	$style = '';
	// TODO: Do we want to make the colour depend on the allow/deny/inherit flag?
	// Module information.
	if (is_array($permission)) {
		$buf .= "<td $style>";
		$modlist = array();
		$itemlist = array();
		if (is_array($permission['axo_groups'])) {
			foreach ($permission['axo_groups'] as $group_id) {
				$group_data = $perms->get_group_data($group_id, 'axo');
				$modlist[] = $AppUI->_($group_data[3]);
			}
		}
		if (is_array($permission['axo'])) {
			foreach ($permission['axo'] as $key => $section) {
				foreach ($section as $id) {
					$mod_data = $perms->get_object_full($id, $key, 1, 'axo');
					$modlist[] = $AppUI->_($mod_data['name']);
				}
			}
		}
		$buf .= implode("<br />", $modlist);
		$buf .= "</td>";
		// Item information TODO:  need to figure this one out.
	// 	$buf .= "<td></td>";
		// Type information.
		$buf .= "<td>";
		$perm_type = array();
		if (is_array($permission['aco'])) {
			foreach ($permission['aco'] as $key => $section) {
				foreach ($section as $value) {
					$perm = $perms->get_object_full($value, $key, 1, 'aco');
					$perm_type[] = $AppUI->_($perm['name']);
				}
			}
		}
		$buf .= implode("<br />", $perm_type);
		$buf .= "</td>";

		// Allow or deny
		$buf .= "<td>" . $AppUI->_( $permission['allow'] ? 'allow' : 'deny' ) . "</td>";
		
		echo "<tr>$buf</tr>";
	}
}
?>
</table>
</td>
</tr>
</table>

</tr>

</table>
