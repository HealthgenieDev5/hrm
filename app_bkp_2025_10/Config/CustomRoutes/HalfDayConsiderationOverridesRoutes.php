<?php

/*begin::Wave Off Minutes*/

use App\Controllers\Override\HalfDayConsideration;

$routes->match(['get', 'post'], '/backend/hr/half-day-consideration', [HalfDayConsideration::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/update-half-day-consideration', [HalfDayConsideration::class, 'updateWaveOffHalfDayWhoDidNotWorkForHalfDay']);
$routes->match(['get', 'post'], '/ajax/backend/hr/existing-half-day-consideration', [HalfDayConsideration::class, 'existingWaveOffHalfDayWhoDidNotWorkForHalfDay']);
$routes->match(['get', 'post'], '/ajax/backend/hr/delete-half-day-consideration', [HalfDayConsideration::class, 'deleteWaveOffHalfDayWhoDidNotWorkForHalfDay']);
/*end::Wave off minutes*/
