<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once 'modules/CustomView/CustomView.php';
if (isset($_REQUEST['record']) == false || $_REQUEST['record']=='') {
	$oReport = new Reports();
	$primarymodule = vtlib_purify($_REQUEST['primarymodule']);

	$BLOCK1 = getPrimaryStdFilterHTML($primarymodule);
	if (!empty($ogReport->related_modules[$primarymodule])) {
		foreach ($ogReport->related_modules[$primarymodule] as $key => $value) {
			$BLOCK1 .= getSecondaryStdFilterHTML($_REQUEST['secondarymodule_'.$value]);
		}
	}
	$report_std_filter->assign('BLOCK1_STD', $BLOCK1);
	$BLOCKJS = $oReport->getCriteriaJS();
	$report_std_filter->assign('BLOCKJS_STD', $BLOCKJS);
	$BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria();
	$report_std_filter->assign('BLOCKCRITERIA_STD', $BLOCKCRITERIA);
} elseif (isset($_REQUEST['record']) == true) {
	global $current_user;

	$reportid = vtlib_purify($_REQUEST['record']);
	$oReport = new Reports($reportid);
	$oReport->getSelectedStandardCriteria($reportid);

	$oRep = new Reports();
	$secondarymodule = '';
	$secondarymodules =array();

	if (!empty($oRep->related_modules[$oReport->primodule])) {
		foreach ($oRep->related_modules[$oReport->primodule] as $key => $value) {
			if (isset($_REQUEST['secondarymodule_'.$value])) {
				$secondarymodules[]= $_REQUEST['secondarymodule_'.$value];
			}
		}
	}
	$secondarymodule = implode(':', $secondarymodules);

	if ($secondarymodule!='') {
		$oReport->secmodule = $secondarymodule;
	}

	$BLOCK1 = getPrimaryStdFilterHTML($oReport->primodule, $oReport->stdselectedcolumn);
	$BLOCK1 .= getSecondaryStdFilterHTML($oReport->secmodule, $oReport->stdselectedcolumn);
	//added to fix the ticket #5117
	$selectedcolumnvalue = '"'. $oReport->stdselectedcolumn . '"';
	if (!is_admin($current_user) && isset($oReport->stdselectedcolumn) && strpos($BLOCK1, $selectedcolumnvalue) === false) {
		$BLOCK1 .= "<option selected value='Not Accessible'>".$app_strings['LBL_NOT_ACCESSIBLE'].'</option>';
	}

	$report_std_filter->assign('BLOCK1_STD', $BLOCK1);

	$BLOCKJS = $oReport->getCriteriaJS();
	$report_std_filter->assign('BLOCKJS_STD', $BLOCKJS);

	$BLOCKCRITERIA = $oReport->getSelectedStdFilterCriteria($oReport->stdselectedfilter);
	$report_std_filter->assign('BLOCKCRITERIA_STD', $BLOCKCRITERIA);

	$report_std_filter->assign('STARTDATE_STD', $oReport->startdate);
	$report_std_filter->assign('ENDDATE_STD', $oReport->enddate);
}

/** Function to get the HTML strings for the primarymodule standard filters
 * @param $module : Type String
 * @param $selected : Type String(optional)
 * @return HTML combo srings
 */
function getPrimaryStdFilterHTML($module, $selected = '') {
	global $ogReport;
	$ogReport->oCustomView=new CustomView();
	$result = $ogReport->oCustomView->getStdCriteriaByModule($module);
	$shtml = '';
	if (isset($result)) {
		$i18nModule = getTranslatedString($module, $module);
		foreach ($result as $key => $value) {
			if ($key == $selected) {
				$shtml .= '<option selected value="'.$key.'">'.$i18nModule.' - '.getTranslatedString($value, $module).'</option>';
			} else {
				$shtml .= '<option value="'.$key.'">'.$i18nModule.' - '.getTranslatedString($value, $module).'</option>';
			}
		}
	}
	return $shtml;
}

/** Function to get the HTML strings for the secondary  standard filters
 * @param $module : Type String
 * @param $selected : Type String(optional)
 * @return HTML combo srings for the secondary modules
 */
function getSecondaryStdFilterHTML($module, $selected = '') {
	global $ogReport;
	$ogReport->oCustomView=new CustomView();
	$shtml = '';
	if ($module != '') {
		$secmodule = explode(':', $module);
		for ($i=0; $i < count($secmodule); $i++) {
			$result = $ogReport->oCustomView->getStdCriteriaByModule($secmodule[$i]);
			if (isset($result)) {
				$i18nSecModule = getTranslatedString($secmodule[$i], $secmodule[$i]);
				foreach ($result as $key => $value) {
					if ($key == $selected) {
						$shtml .= '<option selected value="'.$key.'">'.$i18nSecModule.' - '.getTranslatedString($value, $secmodule[$i]).'</option>';
					} else {
						$shtml .= '<option value="'.$key.'\'>'.$i18nSecModule.' - '.getTranslatedString($value, $secmodule[$i]).'</option>';
					}
				}
			}
		}
	}
	return $shtml;
}
?>