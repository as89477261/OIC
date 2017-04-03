<?php
/**
 * Portlet : คำสั่งเพิ่มเติม
 * @author Mahasak Pijittum
 * @version 1.0.0
 * @package portlet
 * @category portlet
 *
 */
class ExtraCommandPortlet {
	function getUI() {
		global $config;
		global $lang;
		global $sessionMgr;
		global $serverName;
		global $policy;
		
		checkSessionPortlet();
		
		if($policy->canReserveBookno()) {
			$reserveSPAN = "<span id=\"reserveRegNoCmd\" class=\"portletCommandSpan\"><img src=\"/{$config ['appName']}/images/th/ReserveBookNoExt.png\"/></span>";
			$reserveJS = "Ext.get('reserveRegNoCmd').on('click',function() {
				reserveBooknoWindow.show();
        	},this);";
		} else {
			$reserveSPAN = "&nbsp;";
			$reserveJS = "";
		}
		
		//include_once 'Role.Entity.php';

		$currentYear = $sessionMgr->getCurrentYear();
		if($config['datemode'] == 'B') {
			$currentYear = $currentYear +543;
		}
		$role = new RoleEntity();
		$roleID = $sessionMgr->getCurrentRoleID();
		$role->Load("f_role_id = '{$roleID}'");
		
		
		
		$html = "<table width=\"100%\">
			<tr>
				<td>
					<span style=\"cursor: pointer;\" id=\"changeYearSpan\"><img src=\"/{$config ['appName']}/images/th/ChangeYear.png\"/></span><br/>
					<span>{$lang['common']['currentYear'] } [{$currentYear}]</span>
				</td>
				<td >
					<span style=\"cursor: pointer;\" id=\"changePasswordSpan\"><img src=\"/{$config ['appName']}/images/th/ChangePassword.png\"/></span><br/>
					<span>{$lang['common']['lastChangePwd'] } [{$sessionMgr->getLastChangePassword()}]</span>
				</td>
			</tr>
			<tr>
				<td>
					<span  style=\"cursor: pointer;\" id=\"changeRoleSpan\"><img src=\"/{$config ['appName']}/images/th/ChangeRole.png\"/></span><br/>
					<span>{$lang['common']['currentRole'] } [{$role->f_role_name}]</span>
				</td>
			</tr>
			<tr>
				<td>
					{$reserveSPAN}
				</td>
			</tr>			
		</table>
		<script type=\"text/javascript\">

		Ext.get('changePasswordSpan').on('click',function() {
			top.window.location = 'https://{$serverName}/{$config['appName']}/force/change-pwd';
		},this);
		
		Ext.get('changeRoleSpan').on('click',function() {
			changeRoleWindow.show();
		},this);
		
		Ext.get('changeYearSpan').on('click',function() {
			changeYearWindow.show();
		},this);

		{$reserveJS}
		</script>";
		echo $html;
		/*
		echo "<span class=\"portletCommandSpan\">เปลี่ยนปีเอกสาร</span><br/>";
		echo "<span class=\"portletCommandSpan\">รายการเอกสารที่ต้องติดตาม</span><br/>";
		echo "<span class=\"portletCommandSpan\">เปลี่ยนรหัสผ่าน</span><br/>";
		echo "<span class=\"portletCommandSpan\">เปลี่ยนตำแหน่งงาน/หน่วยงาน</span><br/>";
		*/
	}
}
