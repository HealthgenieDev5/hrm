<?php
/*begin::RH Override*/

use App\Controllers\Override\Rh;

$routes->match(['get', 'post'], '/backend/hr/rh-override', [Rh::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/hr/override-rh', [Rh::class, 'overrideRH']);
/*end::RH Override*/
