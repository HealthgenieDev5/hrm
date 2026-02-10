<?php
/*begin::Wave Off Minutes*/

use App\Controllers\Override\WaveOffMinutes;

$routes->match(['get', 'post'], '/backend/hr/wave-off-minutes', [WaveOffMinutes::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/update-wave-off-minutes', [WaveOffMinutes::class, 'updateWaveOffMinutes']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-wave-off-minutes', [WaveOffMinutes::class, 'existingWaveOffMinutes']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-wave-off-minutes', [WaveOffMinutes::class, 'deleteWaveOffMinutes']);
/*end::Wave off minutes*/
