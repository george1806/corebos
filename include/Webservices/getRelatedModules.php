<?php
/***********************************************************************************
 * Copyright 2012-2014 JPL TSolucio, S.L.  --  This file is a part of coreBOSCP.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 ************************************************************************************/
require_once 'include/database/PearDatabase.php';
require_once 'include/ComboUtil.php';
require_once 'include/utils/CommonUtils.php';
require_once 'vtlib/Vtiger/Language.php';
require_once 'modules/PickList/PickListUtils.php';

function getRelatedModulesInfomation($module) {
	global $log, $adb, $current_user;
	$log->debug('Entering getRelatedModulesInfomation(' . $module . ') method ...');
	require 'user_privileges/user_privileges_' . $current_user->id . '.php';

	$cur_tab_id = getTabid($module);
	$sql1 = "select * from vtiger_relatedlists where tabid=?";
	$result = $adb->pquery($sql1, array($cur_tab_id));

	$num_row = $adb->num_rows($result);
	for ($i = 0; $i < $num_row; $i++) {
		$rel_tab_id = $adb->query_result($result, $i, 'related_tabid');
		//$function_name = $adb->query_result($result, $i, 'name');
		$label = $adb->query_result($result, $i, 'label');
		$actions = $adb->query_result($result, $i, 'actions');
		$relationId = $adb->query_result($result, $i, 'relation_id');
		if ($rel_tab_id != 0) {
			if ($is_admin || $profileTabsPermission[$rel_tab_id] == 0) {
				if ($is_admin || $profileActionPermission[$rel_tab_id][3] == 0) {
					// vtlib customization: Send more information (from module, related module) to the callee
					$focus_list[$label] = array('related_tabid' => $rel_tab_id, 'label'=> $label, 'labeli18n' =>getTranslatedString('SINGLE_'.$label, $label), 'actions' => $actions, 'relationId' => $relationId);
				}
			}
		} else {
			// vtlib customization: Send more information (from module, related module) to the callee
			$focus_list[$label] = array('related_tabid' => $rel_tab_id, 'label'=> $label, 'labeli18n' =>getTranslatedString('SINGLE_'.$label, $label), 'actions' => $actions, 'relationId' => $relationId);
		}
	}
	return $focus_list;
}
?>