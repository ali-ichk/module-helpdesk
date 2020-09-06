<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

//Gibbon system-wide include
require_once '../../gibbon.php';

if (empty($_SESSION[$guid]['gibbonPersonID']) || empty($_SESSION[$guid]['gibbonRoleIDPrimary'])
	|| !isActionAccessible($guid, $connection2, '/modules/Help Desk/helpDesk_manageTechnicianGroup.php')) {
    die(__('Your request failed because you do not have access to this action.'));
} else {
	$currentGroupName = $_POST['currentGroupName'] ?? null;
    $groupName = $_POST['groupName'] ?? '';

    if ($currentGroupName != null && $currentGroupName == $groupName) {
    	echo 0;
    	die();
    }

    $data = array('groupName' => $groupName);
    $sql = 'SELECT COUNT(*) FROM helpDeskTechGroups WHERE groupName=:groupName';
    $result = $pdo->executeQuery($data, $sql);

    echo ($result && $result->rowCount() == 1)? $result->fetchColumn(0) : -1;
}

?>
