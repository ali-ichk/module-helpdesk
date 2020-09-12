<?php
namespace Gibbon\Module\HelpDesk\Domain;

use Gibbon\Domain\Traits\TableAware;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Domain\QueryableGateway;

/**
 * Technician Gateway
 *
 * @version v20
 * @since   v20
 */
class DepartmentGateway extends QueryableGateway
{
    use TableAware;

    private static $tableName = 'helpDeskDepartments';
    private static $primaryKey = 'departmentID';
    private static $searchableColumns = [];
}
