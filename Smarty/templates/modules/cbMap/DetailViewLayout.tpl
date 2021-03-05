<script src="modules/com_vtiger_workflow/resources/functional.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/fieldexpressionpopup.js" type="text/javascript" charset="utf-8"></script>
<script src="modules/com_vtiger_workflow/resources/functionselect.js" type="text/javascript" charset="utf-8"></script>
<input type="hidden" name="MapID" value="{$MapID}" id="MapID">
<input type="hidden" name="MapName" id="MapName" value="{$NameOFMap}">

<table class="slds-table slds-no-row-hover slds-table-moz map-generator-table">
	<tbody>
		<tr id="DivObjectID">
			<td class="detailViewContainer" valign="top">
				<div>
					<article class="slds-card" aria-describedby="header">
						<div class="slds-card__header slds-grid">
							<header class="slds-media_center slds-has-flexi-truncate">
								<h1 id="mapNameLabel" class="slds-page-header__title slds-m-right_small slds-truncate">
									{if $NameOFMap neq ''} {$NameOFMap} {/if}
								</h1>
								<p class="slds-text-heading_label slds-line-height_reset">{$MapFields.maptype|@getTranslatedString:$MODULE}</p>
							</header>
							<div class="slds-no-flex">
								<div class="slds-section-title_divider">
									<button class="slds-button slds-button_small slds-button_neutral" id="SaveAsButton" onclick="saveDetailLayoutMapAction();">{'LBL_SAVE_LABEL'|@getTranslatedString}</button>
								</div>
							</div>
						</div>
					</article>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<div class="slds-p-around_x-small slds-grid  slds-gutters">
		<div class="slds-col slds-size_2-of-4 slds-p-around_xxx-small">
			<div class="slds-form-element__control">
				<div class="slds-select_container">
					<select id="msmodules" required name="msmodules" class="slds-select" onchange="detailViewSetValues(this.value)">
						{foreach item=arr from=$MODULES}
							<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<div class="slds-col slds-size_2-of-4 slds-p-around_xxx-small">
			<button class="slds-button slds-button_neutral" id="addfield" onclick="addBlockDetails();">
				<svg class="slds-button__icon slds-button__icon_left" aria-hidden="true">
					<use xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#add"></use>
				</svg>Add Block
			</button>
		</div>
</div>

<div class="slds-p-around_x-small slds-form-element" id="blockDiv" >
	<div class="slds-p-around_x-small slds-grid slds-gutters">
		<div class="slds-col slds-size_1-of-3 slds-form-element">
			<legend class="slds-form-element__label" for="fieldtype">{'Type'|@getTranslatedString:'cbMap'}</legend>
            <div class="slds-select_container">
                <select class="slds-select" name ="fieldtype" id="fieldtype" onChange="changeFieldTypeListener(this.value)">
                    <option value="">{'Select type'|@getTranslatedString}</option>
                    <option value="ApplicationFields">{'Application Fields'|@getTranslatedString}</option>
                    <option value="FieldList">{'Field List'|@getTranslatedString}</option>
                </select>
            </div>
		</div>
		<div class="slds-col slds-size_1-of-3 slds-form-element">
			<label class="slds-form-element__label" for="label">
            <legend class="slds-form-element__legend slds-form-element__label">{'Label'|@getTranslatedString:'cbMap'}</legend>
			<div class="slds-form-element__control">
				<input type="text" name="label" id="label" required="" class="slds-input" value=""/>
			</div>
		</div>
		<div class="slds-col slds-size_1-of-3 slds-form-element">
			<label class="slds-form-element__label" for="sequence">
            <legend class="slds-form-element__legend slds-form-element__label">{'Sequence'|@getTranslatedString:'cbMap'}</legend>
			<div class="slds-form-element__control">
				<input type="text" name="sequence" id="sequence" required="" class="slds-input" value=""/>
			</div>
		</div>
	</div>
</div>

<div class="slds-p-around_x-small slds-grid slds-gutters" id="AppFieldselectedDiv" style = "display:none">
    <div class="slds-col slds-form-element slds-text-align_center">
        <legend class="slds-form-element__legend slds-form-element__label">{'LBL_BLOCK'|@getTranslatedString:'cbMap'}</legend>
        <div class="slds-form-element__control">
            <div class="slds-select_container">
                <select id="appfield_block" name="appfield_block" class="slds-select">
                </select>
            </div>
        </div>
    </div>
</div>

<div class="slds-p-around_x-small slds-form-element" id="FieldListselectedDiv" style = "display:none">
	<div class="slds-p-around_x-small slds-grid slds-gutters">
		<div class="slds-col slds-form-element slds-text-align_center">
			<div class="slds-form-element__control">
				<div class="slds-button-group" role="group">
				<button class="slds-button slds-button_neutral" id = "addRowBtn" onclick = "fillTempContainer('row')">Add Row</button>
				<button class="slds-button slds-button_neutral" id= "addColumnBtn" onclick = "fillTempContainer('column')">Add Column</button>
				</div>
			</div>
		</div>
	</div>
	<div class="slds-grid slds-p-around_x-small slds-gutters">
		<div class="slds-col slds-size_2-of-4 slds-p-around_xxx-small">
			<select id='list_of_fields' class='slds-select'>
			</select>
		</div>
		<div class="slds-col slds-size_2-of-4 slds-p-around_xxx-small">
			<button class="slds-button slds-button_neutral" id="addfield" onclick="fillSelectedField();">
				<svg class="slds-button__icon slds-button__icon_left" aria-hidden="true">
					<use xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#add"></use>
				</svg>Add field
			</button>
		</div>
	</div>
	<div class="slds-p-around_x-small slds-form-element slds-form-element">
		<label class="slds-form-element__label" for="rowcolumnfield_container">Row, Column and Field details</label>
		<div class="slds-form-element__control">
			<textarea id="rowcolumnfield_container" class="slds-textarea"></textarea>
		</div>
	</div>
</div>

<div class="slds-p-around_x-small slds-form-element slds-form-element">
  <label class="slds-form-element__label" for="content_holder">Content Holder</label>
  <div class="slds-form-element__control">
    <textarea id="content_holder" class="slds-textarea"></textarea>
  </div>
</div>

<script>
function changeFieldTypeListener(fieldtype) {
	switch(fieldtype) {
	case 'ApplicationFields':
		handleApplicationFieldsCase();
		break;
	case 'FieldList':
		handleFieldListCase();
		break;
	default:
		document.getElementById('FieldListselectedDiv').style.display = 'none';
		document.getElementById('AppFieldselectedDiv').style.display = 'none';
	}
}

function handleApplicationFieldsCase() {
	appfieldblock = document.getElementById('appfield_block');
	appfieldblock.innerHTML = '';
	var selectedmodule = document.getElementById("msmodules").value;
	jQuery.ajax({
		method: 'POST',
		url: 'index.php?action=cbMapAjax&mode=ajax&file=getModuleDetailsforLayoutSetup&module=cbMap&selmodule='+selectedmodule+'&query=blocks'
	}).done(function (response) {
		response = JSON.parse(response);
		document.getElementById('FieldListselectedDiv').style.display = 'none';
		document.getElementById('AppFieldselectedDiv').style.display = '';
		response.forEach(function(block) {
			var option = document.createElement("option");
			option.value = block.blockid;
			option.text = block.blocklabel;
			appfieldblock.appendChild(option);
		});
	});
}

function detailViewSetValues($selmodule) {
	fieldtype = document.getElementById('fieldtype').value;
	changeFieldTypeListener(fieldtype);
}

function handleFieldListCase() {
	listoffields = document.getElementById('list_of_fields');
	listoffields.innerHTML = '';
	var selectedmodule = document.getElementById("msmodules").value;
	jQuery.ajax({
		method: 'POST',
		url: 'index.php?action=cbMapAjax&mode=ajax&file=getModuleDetailsforLayoutSetup&module=cbMap&selmodule='+selectedmodule+'&query=fields'
	}).done(function (response) {
		response = JSON.parse(response);
		document.getElementById('AppFieldselectedDiv').style.display = 'none';
		document.getElementById('FieldListselectedDiv').style.display = '';
		response.forEach(function(fields) {
			var option = document.createElement("option");
			option.value = fields.fieldname;
			option.text = fields.fieldlabel;
			listoffields.appendChild(option);
		});
	});
}

function fillTempContainer(content) {
	tempdetail = document.getElementById('rowcolumnfield_container').value;
	document.getElementById('rowcolumnfield_container').value = tempdetail + content + '$$';
}

function fillSelectedField() {
	field = document.getElementById('list_of_fields').value;
	tempdetail = document.getElementById('rowcolumnfield_container').value;
	document.getElementById('rowcolumnfield_container').value = tempdetail + field + '$$';
}

function addBlockDetails() {
	maincontent = document.getElementById('content_holder').value;
	type = document.getElementById('fieldtype').value;
	switch(type) {
		case 'ApplicationFields':
			label = document.getElementById('label').value;
			sequence = document.getElementById('sequence').value;
			blockid = document.getElementById('appfield_block').value;
			document.getElementById('content_holder').value = maincontent + 'block' + '$$label##'+label + '$$sequence##' + sequence + '$$type##' + type + '$$blockid##' + blockid + '$$';
			break;
		case 'FieldList':
			label = document.getElementById('label').value;
			sequence = document.getElementById('sequence').value;
			rowdata = document.getElementById('rowcolumnfield_container').value;
			document.getElementById('content_holder').value = maincontent + 'block' + '$$label##'+ label + '$$sequence##' + sequence + '$$type##' + type + '$$' + rowdata;
			document.getElementById('rowcolumnfield_container').value = '';
			break;
		default:
	}
}

function saveDetailLayoutMapAction() {
	type = document.getElementById('fieldtype').value;
	let params = 'mapid={$MapID}&tmodule='+document.getElementById('msmodules').value;
	params += '&type='+document.getElementById('fieldtype').value;
	switch(type) {
		case 'ApplicationFields':
			params += '&content='+document.getElementById('content_holder').value;
			break;
		case 'FieldList':
			params += '&content='+document.getElementById('content_holder').value;
			break;
	}
	params = encodeURI(params);
	saveMapAction(params);
}
</script>