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
use Gibbon\Domain\DataSet;
use Gibbon\Forms\DatabaseFormFactory;
use Gibbon\Forms\Form;
use Gibbon\Services\Format;
use Gibbon\Tables\DataTable;
use Gibbon\Module\HelpDesk\Domain\IssueGateway;

//Module includes
require_once __DIR__ . '/moduleFunctions.php';

$page->breadcrumbs->add(__('Issues'));

if (isModuleAccessible($guid, $connection2) == false) {
    //Acess denied
    $page->addError('You do not have access to this action.');
} else {

    if (isset($_GET['return'])) {
        $editLink = null;
        if (isset($_GET['issueID'])) {
            $editLink = $_SESSION[$guid]["absoluteURL"] . '/index.php?q=/modules/' . $_SESSION[$guid]['module'] . '/issues_discussView.php&issueID=' . $_GET['issueID'];
        }
        returnProcess($guid, $_GET['return'], $editLink, null);
    }

    $highestAction = getHighestGroupedAction($guid, $_GET["q"], $connection2) ;
    if ($highestAction == false) {
        $page->addError(__('The highest grouped action cannot be determined.'));
        exit();
    }

    $year = $_GET['year'] ?? $_SESSION[$guid]['gibbonSchoolYearID'];

    $issueGateway = $container->get(IssueGateway::class);
    $criteria = $issueGateway->newQueryCriteria(true)
        ->searchBy($issueGateway->getSearchableColumns(), $_GET['search'] ?? '')
        ->filterBy('year', $year)
        ->sortBy('issueID')
        ->fromPOST();
        
    $criteria->addFilterRules([
        'issue' => function ($query, $issue) use ($guid) {
            if ($issue == 'My Issues') {
                $query->where('helpDeskIssue.gibbonPersonID = :gibbonPersonID')
                        ->bindValue('gibbonPersonID', $_SESSION[$guid]['gibbonPersonID']);
            } else if ($issue == "My Assigned") {
                $query->where('techID.gibbonPersonID=:techPersonID')
                        ->bindValue('techPersonID', $_SESSION[$guid]["gibbonPersonID"]);
            }
            return $query;
        },
    ]);

    $form = Form::create('searchForm', $_SESSION[$guid]['absoluteURL'].'/index.php', 'get');
    $form->setFactory(DatabaseFormFactory::create($pdo));

    $form->addHiddenValue('q', '/modules/' . $_SESSION[$guid]['module'] . '/issues_view.php');
    $form->addHiddenValue('address', $_SESSION[$guid]['address']);

    $form->setClass('noIntBorder fullWidth standardForm');
    $form->setTitle(__('Search & Filter'));

    $row = $form->addRow();
        $row->addLabel('search', __('Search'))
            ->description(__('Issue ID, Name or Description.'));
        $row->addTextField('search')
            ->setValue($criteria->getSearchText());
    
    $row = $form->addRow();
        $row->addLabel('year', __('Year Filter'));
        $row->addSelectSchoolYear('year', 'All')
            ->selected($year);
    
    $row = $form->addRow();
        $row->addSearchSubmit($gibbon->session, __('Clear Filters'));

    echo $form->getOutput();   
        
    
    if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "viewIssue") || getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "fullAccess")) {
        $issues = $issueGateway->queryIssues($criteria);
    } else if (isTechnician($connection2, $_SESSION[$guid]["gibbonPersonID"])) {
        $issues = $issueGateway->queryIssuesTechnician($criteria, $gibbon->session->get('gibbonPersonID'));
    } else {
         $issues = $issueGateway->queryIssuesOwner($criteria, $gibbon->session->get('gibbonPersonID'));
    }
    
    $table = DataTable::createPaginated('issues', $criteria);
    $table->setTitle("Issues");
    
    //FILTERS START
    if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "viewIssue")) {
        $table->addMetaData('filterOptions', ['issue:All'    => __('Issues').': '.__('All')]);
    }
    if (isTechnician($connection2, $_SESSION[$guid]["gibbonPersonID"])) {
        $table->addMetaData('filterOptions', ['issue:My Assigned'    => __('Issues').': '.__('My Assigned')]);
    }
    $table->addMetaData('filterOptions', ['issue:My Issues'    => __('Issues').': '.__('My Issues')]);
    if (isTechnician($connection2, $_SESSION[$guid]["gibbonPersonID"])) {
        if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "viewIssueStatus")=="All") {        
            $table->addMetaData('filterOptions', [
                'status:Unassigned'           => __('Status').': '.__('Unassigned'),
                'status:Pending'          => __('Status').': '.__('Pending'),
                'status:Resolved'           => __('Status').': '.__('Resolved')
            ]);
        } else if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "viewIssueStatus")=="UP") {            
            $table->addMetaData('filterOptions', [
                'status:Unassigned'           => __('Status').': '.__('Unassigned'),
                'status:Pending'          => __('Status').': '.__('Pending')
            ]);            
        } else if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "viewIssueStatus")=="PR") {                    
            $table->addMetaData('filterOptions', [
                'status:Pending'          => __('Status').': '.__('Pending'),
                'status:Resolved'           => __('Status').': '.__('Resolved')
            ]);
        }
    } else {
        $table->addMetaData('filterOptions', [
                'status:Unassigned'           => __('Status').': '.__('Unassigned'),
                'status:Pending'          => __('Status').': '.__('Pending'),
                'status:Resolved'           => __('Status').': '.__('Resolved')
            ]);
    }

    $categoryFilters = array_filter(array_map('trim', explode(',', getSettingByScope($connection2, $_SESSION[$guid]['module'], 'issueCategory', false))));
    foreach  ($categoryFilters as $category) {
        $table->addMetaData('filterOptions', [
            'category:'.$category => __('Category').': '.$category,
        ]);
    }

    $priorityFilters = array_filter(array_map('trim', explode(',', getSettingByScope($connection2, $_SESSION[$guid]['module'], 'issuePriority', false))));
    foreach  ($priorityFilters as $priority) {
        $table->addMetaData('filterOptions', [
            'priority:'.$priority => __('Priority').': '.$priority,
        ]);
    }
    //FILTERS END
    
    $table->modifyRows(function($issue, $row) {
        if ($issue['status'] == 'Resolved') {
            $row->addClass('current');
        } else if ($issue['status'] == 'Unassigned') {
            $row->addClass('error');
        } else if ($issue['status'] == 'Pending') {
            $row->addClass('warning');
        }
        return $row;
    });

    $table->addHeaderAction('add', __("Create"))
            ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_create.php")
            ->displayLabel();

    $table->addColumn('issueID', __("Issue ID"))
            ->format(Format::using('number', ['issueID'])); 
    $table->addColumn('issueName', __('Name'))
          ->description(__('Description'))
          ->format(function ($issue) {
            return '<strong>' . __($issue['issueName']) . '</strong><br/><small><i>' . __($issue['description']) . '</i></small>';
          });
    $table->addColumn('gibbonPersonID', __('Owner')) 
                ->description(__('Category'))
                ->format(function ($row) use ($connection2) {
                    $owner = getPersonName($connection2, $row['gibbonPersonID']);
                    return Format::name($owner['title'], $owner['preferredName'], $owner['surname'], 'Staff') . '<br/><small><i>'. __($row['category']) . '</i></small>';
                });

    if (!empty($priorityFilters)) {
        $table->addColumn('priority', __(getSettingByScope($connection2, $_SESSION[$guid]['module'], 'issuePriorityName', false)));
    }
    
    $table->addColumn('technicianID', __('Technician'))
                ->format(function ($row) use ($connection2) {
                    $tech = getPersonName($connection2, $row['techPersonID']);
                    return Format::name($tech['title'], $tech['preferredName'], $tech['surname'], 'Staff');
                });         
    $table->addColumn('status', __('Status'))
          ->description(__('Date'))
          ->format(function ($issue) {
            return '<strong>' . __($issue['status']) . '</strong><br/><small><i>' . Format::date($issue['date']) . '</i></small>';
            });
    
    $table->addActionColumn()
            ->addParam('issueID')
            ->format(function ($issues, $actions) use ($guid, $connection2) {
            $actions->addAction('view', __("Open"))
                ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_discussView.php");
                
            if (isPersonsIssue($connection2, ($issues['issueID']), $_SESSION[$guid]["gibbonPersonID"]) || getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "fullAccess")) { 
                $actions->addAction('edit', __("Edit"))
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_discussEdit.php");
            }
            if ($issues['status'] != "Resolved") {
                if ($issues['technicianID'] == null) { //issues_acceptProcess
                    if (isTechnician($connection2, $_SESSION[$guid]["gibbonPersonID"]) || getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "fullAccess")) {
                        $actions->addAction('accept', __("Accept"))
                        ->directLink()
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_acceptProcess.php")
                        ->setIcon('page_new');
                    }
                    if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "assignIssue")) {
                    $actions->addAction('assign', __("Assign"))
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_assign.php")
                        ->setIcon('attendance');
                    }
                } else if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "reassignIssue")) { 
                    $actions->addAction('assign', __("Reassign"))
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_assign.php")
                        ->setIcon('attendance');
                }
                if(getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "resolveIssue") || isPersonsIssue($connection2, $issues['issueID'], $_SESSION[$guid]["gibbonPersonID"])) {
                    $actions->addAction('resolve', __("Resolve"))
                        ->directLink()
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_resolveProcess.php")
                        ->setIcon('iconTick');
                }
            }  if ($issues['status'] == "Resolved") {
                if (getPermissionValue($connection2, $_SESSION[$guid]["gibbonPersonID"], "reincarnateIssue") || isPersonsIssue($connection2, $issues['issueID'], $_SESSION[$guid]["gibbonPersonID"])) {
                    $actions->addAction('reincarnate', __("Reincarnate"))
                        ->directLink()
                        ->setURL("/modules/" . $_SESSION[$guid]["module"] . "/issues_reincarnateProcess.php")
                        ->setIcon('reincarnate');
                }
            }
            });
    
    echo $table->render($issues);    
 
}
?>
