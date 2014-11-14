<?php

global $AppUI,$dPconfig,$loadFromTab;
global $company_id, $obj,$tab;


?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<form name="helpFrm" action="?m=companies&a=addedit&company_id=<?php echo $company_id; ?>" method="post">
<?php
	echo "<br />";
	echo $AppUI->_("Use this form and its multiple tabs to add a new organisation into the system.");
	echo "<br />";
	echo $AppUI->_('Navigate using the clickable tabs to add the various details of the organisation');
	echo "<br /><br />";

?>
</form>
</table>
<script language="javascript">
 subForm.push(new FormDefinition(<?php echo $tab;?>, document.helpFrm, checkDetail, saveDetail));
</script>