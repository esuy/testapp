<?php

global $AppUI, $file_id, $obj, $tab, $baseDir;
require_once( "$baseDir/lib/Excel/Reader.php" ) ;
//open spreadsheet reader
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('CP1251');
if (!$file_id)
{
	echo $AppUI->_("No data available") . '<br />'.  $AppUI->getMsg();
	

}
else
{
	$data->read("$baseDir/files/$obj->file_real_filename");
	
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<?php
		for ($i = 1; $i <= $data->sheets[$tab]['numRows']; $i++) 
		{
			$w .= '<tr>';
			for ($j = 1; $j <= $data->sheets[$tab]['numCols']; $j++) 
			{
				$w.= "<td>\"".$data->sheets[$tab]['cells'][$i][$j]."\"</td>";
			}
			$w .= "</tr>";

		}
}

	echo $w;
		
?>

</table>