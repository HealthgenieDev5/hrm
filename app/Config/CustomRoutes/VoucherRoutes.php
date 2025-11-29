<?php
/*begin::Voucher Master*/

use App\Controllers\Master\Voucher;

$routes->match(['get', 'post'], '/ajax/backend/master/voucher-master/get/(:any)', [Voucher::class, 'get/$1']);
$routes->match(['get', 'post'], '/ajax/backend/master/voucher-master/add', [Voucher::class, 'add']);
$routes->match(['get', 'post'], '/ajax/backend/master/voucher-master/delete', [Voucher::class, 'delete']);
/*end::Voucher Master*/
