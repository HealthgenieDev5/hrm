<?php
/*begin::Phone Bill Master*/

use App\Controllers\Master\PhoneBill;

$routes->match(['get', 'post'], '/ajax/backend/master/phone-bill-master/get/(:any)', [PhoneBill::class, 'get/$1']);
$routes->match(['get', 'post'], '/ajax/backend/master/phone-bill-master/add', [PhoneBill::class, 'add']);
$routes->match(['get', 'post'], '/ajax/backend/master/phone-bill-master/delete', [PhoneBill::class, 'delete']);
/*end::Phone Bill Master*/
