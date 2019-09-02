<?php
/*************************************************************************************************
 * Copyright 2019 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/
$Vtiger_Utils_Log = true;
include_once 'vtlib/Vtiger/Module.php';

if (empty($argv[1]) || empty($argv[2])) {
	echo "\n";
	echo $argv[0]." sourceTimezone destinationTimezone\n";
	echo "\n";
	echo "Missing parameters!\n";
	echo 'For example, '.$argv[0]." UTC CET\n";
	die();
}
$sourceTimeZoneName = $argv[1];
$sourceTimeZone = new DateTimeZone($sourceTimeZoneName);
$targetTimeZoneName = $argv[2];
$targetTimeZone = new DateTimeZone($targetTimeZoneName);
// crmentity
$crme = $adb->query('select crmid,createdtime,modifiedtime,viewedtime from vtiger_crmentity');
while ($rec = $adb->fetch_array($crme)) {
	$cTime = new DateTime($rec['createdtime'], $sourceTimeZone);
	$cTime->setTimeZone($targetTimeZone);
	$mTime = new DateTime($rec['modifiedtime'], $sourceTimeZone);
	$mTime->setTimeZone($targetTimeZone);
	$vTime = new DateTime($rec['viewedtime'], $sourceTimeZone);
	$vTime->setTimeZone($targetTimeZone);
	$adb->query(
		'update vtiger_crmentity set createdtime=?, modifiedtime=?, viewedtime=? where crmid=?',
		array($cTime->format('Y-m-d H:i:s'), $mTime->format('Y-m-d H:i:s'), $vTime->format('Y-m-d H:i:s'), $rec['crmid'])
	);
	echo $adb->convert2Sql(
		'update vtiger_crmentity set createdtime=?, modifiedtime=?, viewedtime=? where crmid=?',
		array($cTime->format('Y-m-d H:i:s'), $mTime->format('Y-m-d H:i:s'), $vTime->format('Y-m-d H:i:s'), $rec['crmid'])
	)."\n";
}
// uitype 50
$flds = $adb->query('select tabid,columnname,tablename from vtiger_field where uitype=50 order by tabid,tablename');
$tabid = 0;
$tname = '';
$updfields = array();
while ($rec = $adb->fetch_array($flds)) {
	if ($tabid!=$rec['tabid'] || $tname!=$rec['tablename']) {
		if (count($updfields)>0) {
			// update
			$rs = $adb->query('select '.implode(',', $updfields).','.$mod->tab_name_index[$tname].' from '.$tname);
			$updsql = 'update '.$tname.' set '.implode('=?,', $updfields).'=? where '.$mod->tab_name_index[$tname].'=?';
			while ($updrec = $adb->fetch_array($rs)) {
				$params = array();
				foreach ($updfields as $field) {
					if (empty($updrec[$field])) {
						$params[] = '';
					} else {
						$fTime = new DateTime($updrec[$field], $sourceTimeZone);
						$fTime->setTimeZone($targetTimeZone);
						$params[] = $fTime->format('Y-m-d H:i:s');
					}
				}
				$params[] = $updrec[$mod->tab_name_index[$tname]];
				$adb->query($updsql, $params);
				echo $adb->convert2Sql($updsql, $params)."\n";
			}
		}
		$tabid = $rec['tabid'];
		$tname = $rec['tablename'];
		$updfields = array();
		$mod = CRMEntity::getInstance(getTabModuleName($tabid));
	};
	$updfields[] = $rec['columnname'];
}
echo "\n";
echo "********************************\n";
echo 'Now change the $default_timezone in your config.inc.php to '.$targetTimeZoneName."\n";
echo "********************************\n";
echo "\n";
?>