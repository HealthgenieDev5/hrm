<?php
/*begin::Shift Override*/

use App\Controllers\Override\Shift;

$routes->match(['get', 'post'], '/backend/hr/shift-override', [Shift::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/override-shift', [Shift::class, 'overrideShift']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-shift-overrides', [Shift::class, 'existingShiftOverrides']);
$routes->match(['get', 'post'], '/backend/reports/shift-override-all', [Shift::class, 'getShiftOverrideAll']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-shift-override', [Shift::class, 'deleteShiftOverride']);
/*end::Shift Override*/
