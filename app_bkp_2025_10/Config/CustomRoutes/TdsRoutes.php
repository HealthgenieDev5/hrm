<?php
/*begin::TDS Master*/

use App\Controllers\Master\Tds;

$routes->match(['get', 'post'], '/ajax/backend/master/tds-master/get/(:any)', [Tds::class, 'get/$1']);
$routes->match(['get', 'post'], '/ajax/backend/master/tds-master/add', [Tds::class, 'add']);
$routes->match(['get', 'post'], '/ajax/backend/master/tds-master/delete', [Tds::class, 'delete']);
/*end::TDS Master*/
