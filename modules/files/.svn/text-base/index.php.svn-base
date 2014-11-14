<?php /* FILES $Id: index.php,v 1.33.10.1 2006/04/18 11:22:52 pedroix Exp $ */
$AppUI->savePlace();

// retrieve any state parameters
if (isset( $_REQUEST['project_id'] )) {
	$AppUI->setState( 'FileIdxProject', $_REQUEST['project_id'] );
}

$project_id = $AppUI->getState( 'FileIdxProject', 0 );

$AppUI->setState( 'FileIdxTab', dPgetParam($_GET, 'tab'));
$tab = $AppUI->getState( 'FileIdxTab', 0 );
$active = intval( !$AppUI->getState( 'FileIdxTab' ) );

// setup the title block
$titleBlock = new CTitleBlock( 'Uploaded Files', '', $m, "$m.$a" );
$titleBlock->addCell( $AppUI->_('Filter') . ':' );
if ($canEdit) {
	$titleBlock->addCell(
		'<input type="submit" class="button" value="'.$AppUI->_('new file').'">', '',
		'<form action="?m=files&a=addedit" method="post">', '</form>'
	);
}
$titleBlock->show();

$file_types = dPgetSysVal("FileType");
if ( $tab != -1 ) {
        array_unshift($file_types, "All Files");
}

$tabBox = new CTabBox( "?m=files", "{$dPconfig['root_dir']}/modules/files/", $tab );
$tabbed = $tabBox->isTabbed();
$i = 0;

foreach($file_types as $file_type)
{
        $tabBox->add("index_table", $file_type);
        ++$i;
}
                                                                                
$tabBox->show();

?>
