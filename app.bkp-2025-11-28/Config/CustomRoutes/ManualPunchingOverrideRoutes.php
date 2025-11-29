<?php
/*begin::Manual Punches*/

use App\Controllers\Override\ManualPunches;

$routes->match(['get', 'post'], '/backend/hr/manual-punches', [ManualPunches::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/create-manual-punch', [ManualPunches::class, 'createManualPunch']);
$routes->post('/ajax/backend/hr/get-manual-punch', [ManualPunches::class, 'getManualPunch']);
$routes->post('/ajax/backend/hr/delete-manual-punch', [ManualPunches::class, 'deleteManualPunch']);
$routes->match(['get', 'post'], '/backend/reports/manual-punches-all', [ManualPunches::class, 'getManualPunchesAll']);
/*end::Manual Punches*/
