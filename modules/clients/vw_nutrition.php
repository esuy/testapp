<?php

global $AppUI,$dPconfig,$loadFromTab;
global $client_id, $obj,$tab;


?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<form name="helpFrm" action="?m=clients&a=addedit&client_id=<?php echo $client_id; ?>" method="post">
<?php
	echo "<br />";
	echo $AppUI->_("This section will display all details concerning all nutrition work related to a client.");
	echo "<br />";
	echo $AppUI->_('Users will be able to view past visits and callup details of each, and also be notified when visits are overdue');
	echo "<br /><br />";

?>
</form>
</table>
