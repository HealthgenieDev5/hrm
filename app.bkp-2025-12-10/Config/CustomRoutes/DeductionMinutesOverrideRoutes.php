<?php
/*begin::Deduction Minutes*/

use App\Controllers\Override\DeductionMinute;

$routes->match(['get', 'post'], '/backend/hr/deduction-minutes', [DeductionMinute::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/update-deduction-minutes', [DeductionMinute::class, 'updateDeductionMinutes']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-deduction-minutes', [DeductionMinute::class, 'existingDeductionMinutes']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-deduction-minutes', [DeductionMinute::class, 'deleteDeductionMinutes']);
/*end::Deduction minutes*/
