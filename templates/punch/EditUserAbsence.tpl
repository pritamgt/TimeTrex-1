{include file="sm_header.tpl" enable_ajax=true body_onload="fixHeight(); getAbsencePolicyBalance()"}

<script	language=JavaScript>
{literal}
function fixHeight() {
	resizeWindowToFit(document.getElementById('body'), 'height', 45);
}

var hwCallback = {
		getAbsencePolicyBalance: function(result) {
			if ( result == false ) {
				result = 'N/A';
			}
			document.getElementById('accrual_policy_balance').innerHTML = result;
		},
		getAbsencePolicyData: function(result) {
			if ( result == false ) {
				result = 'None';
			} else {
				result = result.accrual_policy_name;
			}
			document.getElementById('accrual_policy_name').innerHTML = result;
		}
	}

var remoteHW = new AJAX_Server(hwCallback);

function getAbsencePolicyBalance() {
	document.getElementById('accrual_policy_name').innerHTML = 'None';
	document.getElementById('accrual_policy_balance').innerHTML = 'N/A';

	if ( document.getElementById('absence_policy_id').value != 0 ) {
		remoteHW.getAbsencePolicyBalance( document.getElementById('absence_policy_id').value, {/literal}{$udt_data.user_id}{literal});
		remoteHW.getAbsencePolicyData( document.getElementById('absence_policy_id').value );
	}
}
{/literal}
</script>

<div id="rowContent">
  <div id="titleTab"><div class="textTitle"><span class="textTitleSub">{$title}</span></div>
</div>
<div id="rowContentInner">

		<form method="post" name="wage" action="{$smarty.server.SCRIPT_NAME}">
		    <div id="contentBoxTwoEdit">
				{if !$udtf->Validator->isValid()}
					{include file="form_errors.tpl" object="udtf"}
				{/if}

				<table class="editTable">

				<tr>
					<td class="cellLeftEditTable">
						{t}Employee:{/t}
					</td>
					<td class="cellRightEditTable">
						{$udt_data.user_full_name}
					</td>
				</tr>

				<tr>
					<td class="cellLeftEditTable">
						<a href="javascript:toggleRowObject('advance');toggleImage(document.getElementById('advance_img'), '{$IMAGES_URL}/nav_bottom_sm.gif', '{$IMAGES_URL}/nav_top_sm.gif')"><img style="vertical-align: middle" id="advance_img" src="{$IMAGES_URL}/nav_bottom_sm.gif"></a> {t}Date:{/t}
					</td>
					<td class="cellRightEditTable">
						{getdate type="DATE" epoch=$udt_data.date_stamp}
					</td>
				</tr>

				<tbody id="advance" style="display:none">
				<tr>
					<td class="{isvalid object="udtf" label="repeat" value="cellLeftEditTable"}">
						{t}Repeat Absence for:{/t}
					</td>
					<td class="cellRightEditTable">
						<input type="text" size="3" id="time_stamp" name="udt_data[repeat]" value="{$udt_data.repeat|default:0}"> {t}day(s) after above date.{/t}
					</td>
				</tr>
				</tbody>

				<tr onClick="showHelpEntry('total_time')">
					<td class="{isvalid object="udtf" label="total_time" value="cellLeftEditTable"}">
						{t}Time:{/t}
					</td>
					<td class="cellRightEditTable">
						<input type="text" size="8" name="udt_data[total_time]" value="{gettimeunit value=$udt_data.total_time}">
						{t}ie:{/t} {$current_user_prefs->getTimeUnitFormatExample()}
					</td>
				</tr>

				<tr onClick="showHelpEntry('absence_policy')">
					<td class="{isvalid object="udtf" label="absence_policy" value="cellLeftEditTable"}">
						{t}Type:{/t}
					</td>
					<td class="cellRightEditTable">
						<select id="absence_policy_id" name="udt_data[absence_policy_id]" onChange="getAbsencePolicyBalance();">
							{html_options options=$udt_data.absence_policy_options selected=$udt_data.absence_policy_id}
						</select>
						<br>
						{t}Accrual Policy:{/t} <span id="accrual_policy_name">{t}None{/t}</span><br>
						{t}Available Balance:{/t} <span id="accrual_policy_balance">{t}N/A{/t}</span><br>
						<input type="hidden" name="udt_data[old_absence_policy_id]" value="{$udt_data.absence_policy_id}">
					</td>
				</tr>

				<tr onClick="showHelpEntry('branch')">
					<td class="{isvalid object="udtf" label="branch" value="cellLeftEditTable"}">
						{t}Branch:{/t}
					</td>
					<td class="cellRightEditTable">
						<select id="branch_id" name="udt_data[branch_id]">
							{html_options options=$udt_data.branch_options selected=$udt_data.branch_id}
						</select>
					</td>
				</tr>

				<tr onClick="showHelpEntry('department')">
					<td class="{isvalid object="udtf" label="department" value="cellLeftEditTable"}">
						{t}Department:{/t}
					</td>
					<td class="cellRightEditTable">
						<select id="department_id" name="udt_data[department_id]">
							{html_options options=$udt_data.department_options selected=$udt_data.department_id}
						</select>
					</td>
				</tr>
				<tr onClick="showHelpEntry('override')">
					<td class="{isvalid object="udtf" label="override" value="cellLeftEditTable"}">
						{t}Override:{/t}
					</td>
					<td class="cellRightEditTable">
						<input type="checkbox" class="checkbox" name="udt_data[override]" value="1" {if $udt_data.override == TRUE}checked{/if}>
					</td>
				</tr>

			</table>
		</div>

		<div id="contentBoxFour">
			<input type="submit" class="btnSubmit" name="action:submit" value="{t}Submit{/t}" onClick="return singleSubmitHandler(this)">
			{if $udt_data.id != '' AND ( $permission->Check('absence','delete') OR $permission->Check('absence','delete_own') OR $permission->Check('absence','delete_child') ) }
			<input type="submit" class="btnSubmit" name="action:delete" value="{t}Delete{/t}" onClick="return singleSubmitHandler(this)">
			{/if}
		</div>

		<input type="hidden" name="udt_data[id]" value="{$udt_data.id}">
		<input type="hidden" name="udt_data[user_id]" value="{$udt_data.user_id}">
		<input type="hidden" name="udt_data[user_full_name]" value="{$udt_data.user_full_name}">
		<input type="hidden" name="udt_data[date_stamp]" value="{$udt_data.date_stamp}">
		<input type="hidden" name="udt_data[user_date_id]" value="{$udt_data.user_date_id}">
		</form>
	</div>
</div>
{include file="sm_footer.tpl"}