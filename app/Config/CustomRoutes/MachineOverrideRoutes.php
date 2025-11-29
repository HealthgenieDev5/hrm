<?php
/*begin::Machine Override*/

use App\Controllers\Override\Machine;

$routes->match(['get', 'post'], '/backend/hr/machine-override', [Machine::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/override-machine', [Machine::class, 'overrideMachine']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-machine-overrides', [Machine::class, 'existingMachineOverrides']);
$routes->match(['get', 'post'], '/backend/reports/machine-override-all', [Machine::class, 'getMachineOverrideAll']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-machine-override', [Machine::class, 'deleteMachineOverride']);
/*end::Machine Override*/
