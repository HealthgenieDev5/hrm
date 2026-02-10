<?php

/*begin::Minimum Wages Category*/

use App\Controllers\Master\MinWagesCategory;

$routes->match(['get', 'post'], '/backend/master/minimum-wages-category', [MinWagesCategory::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/master/minimum-wages-category/get-all', [MinWagesCategory::class, 'getAllMinWagesCategory']);
$routes->match(['get', 'post'], '/ajax/backend/master/minimum-wages-category/add', [MinWagesCategory::class, 'addMinWagesCategory']);
$routes->match(['get', 'post'], '/ajax/backend/master/minimum-wages-category/get', [MinWagesCategory::class, 'getMinWagesCategory']);
$routes->match(['get', 'post'], '/ajax/backend/master/minimum-wages-category/update', [MinWagesCategory::class, 'updateMinWagesCategory']);
$routes->match(['get', 'post'], '/ajax/backend/master/minimum-wages-category/delete', [MinWagesCategory::class, 'deleteMinWagesCategory']);
/*end::Minimum Wages Category*/
