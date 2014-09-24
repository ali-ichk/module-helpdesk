<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 20$actionCount0, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//This file describes the module, including database tables

//Basic variables
$name="Help Desk" ;
$description="Gibbon Help Desk Module";
$entryURL="index.php" ;
$type="Additional" ;
$category="Other" ;
$version="0.0.01" ;
$author="Adrien Tremblay & Ray Clark" ;
$url="https://github.com/adrientremblay/helpdesk" ;

//Module tables & gibbonSettings entries
$moduleTables[0]="CREATE TABLE `gibbonTechnicians` (
  `technicianID` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `gibbonPersonID` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`technicianID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;" ;

$moduleTables[1]="CREATE TABLE `gibbonIssue` (
  `issueID` int(12) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `technicianID` int(4) unsigned zerofill NOT NULL,
  `gibbonPersonID` int(10) unsigned zerofill NOT NULL,
  `title` varchar(55) NOT NULL,
  `desc` text NOT NULL,
  `active` boolean DEFAULT TRUE,
  PRIMARY KEY (`issueID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;" ;

$moduleTables[2]="CREATE TABLE `gibbonIssueDiscuss` (
  `issueDiscussID` int(12) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `issueID` int(12) unsigned zerofill NOT NULL,
  `gibbonPersonID` int(10) unsigned zerofill NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`issueDiscussID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;" ;

//Action rows
//One array per action
$actionCount = 0 ;

$actionRows[$actionCount]["name"]="Submit Issue" ; //The name of the action (appears to user in the right hand side module menu)
$actionRows[$actionCount]["precedence"]="0" ; //If it is a grouped action, the precedence controls which is highest action in group
$actionRows[$actionCount]["category"]="" ; //Optional: subgroups for the right hand side module menu
$actionRows[$actionCount]["description"]="Submits an IT related issue to be resolved by the help desk staff" ; //Text description
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ; //Default permission for built in role Admin
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ; //Default permission for built in role Teacher
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ; //Default permission for built in role Student
$actionRows[$actionCount]["defaultPermissionParent"]="N" ; //Default permission for built in role Parent
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ; //Default permission for built in role Support
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ; //Should this action be available to user roles in the Staff category?
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ; //Should this action be available to user roles in the Student category?
$actionRows[$actionCount]["categoryPermissionParent"]="N" ; //Should this action be available to user roles in the Parent category?
$actionRows[$actionCount]["categoryPermissionOther"]="N" ; //Should this action be available to user roles in the Other category?

$actionCount++ ;
$actionRows[$actionCount]["name"]="Assign a tech an issue" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]="Assign any tech an existing unresolved issue" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

$actionCount++ ;
$actionRows[$actionCount]["name"]="View issues" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]="Lists all existing issues" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

$actionCount++ ;
$actionRows[$actionCount]["name"]="View all my submitted issues" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]= "Lists all active issues under my name" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

$actionCount++ ;
$actionRows[$actionCount]["name"]="Assign myself to an issue" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]="Assigns technician to an issue" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

$actionCount++ ;
$actionRows[$actionCount]["name"]="View issues techs are working on" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]="Views lessons that any techs are working on in detail" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

$actionCount++ ;
$actionRows[$actionCount]["name"]="View all issues I am working on" ;
$actionRows[$actionCount]["precedence"]="0" ;
$actionRows[$actionCount]["category"]="" ;
$actionRows[$actionCount]["description"]= "Lists all active issues under my name" ;
$actionRows[$actionCount]["URLList"]="index.php" ;
$actionRows[$actionCount]["entryURL"]="index.php" ;
$actionRows[$actionCount]["defaultPermissionAdmin"]="Y" ;
$actionRows[$actionCount]["defaultPermissionTeacher"]="N" ;
$actionRows[$actionCount]["defaultPermissionStudent"]="N" ;
$actionRows[$actionCount]["defaultPermissionParent"]="N" ;
$actionRows[$actionCount]["defaultPermissionSupport"]="N" ;
$actionRows[$actionCount]["categoryPermissionStaff"]="N" ;
$actionRows[$actionCount]["categoryPermissionStudent"]="N" ;
$actionRows[$actionCount]["categoryPermissionParent"]="N" ;
$actionRows[$actionCount]["categoryPermissionOther"]="N" ;

?>
