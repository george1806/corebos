<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once 'Smarty_setup.php';

global $mod_strings, $app_strings, $currentModule, $current_user, $theme, $log;

$smarty = new vtigerCRM_Smarty();

require_once 'modules/Vtiger/DetailView.php';

$pdochk = $adb->pquery(
	'select 1
		from '.$focus->table_name.'
		inner join vtiger_inventoryproductrel on '.$focus->table_index.'=id
		inner join vtiger_crmobject on vtiger_crmobject.crmid=vtiger_inventoryproductrel.productid
		where vtiger_crmobject.deleted = 1 and '.$focus->table_index.'=?',
	array($record)
);
if ($adb->num_rows($pdochk)>0) {
	$smarty->assign('ERROR_MESSAGE_CLASS', 'cb-alert-warning');
	$smarty->assign('ERROR_MESSAGE', getTranslatedString('DeletedProducts', $currentModule));
}

//Get the associated Products and then display above Terms and Conditions
$smarty->assign('ASSOCIATED_PRODUCTS', getDetailAssociatedProducts($currentModule, $focus));
$smarty->assign('CREATEPDF', 'permitted');
$purchaseorder_no = getModuleSequenceNumber($currentModule, $record);
$smarty->assign('PO_NO', $purchaseorder_no);

$smarty->display('DetailView.tpl');
?>
