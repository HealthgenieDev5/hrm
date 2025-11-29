<?php

/*begin::Leave Master*/

use App\Controllers\Master\Leave as LeaveMaster;

$routes->match(['get', 'post'], '/backend/master/leave', [LeaveMaster::class, 'index']);
$routes->match(['get', 'post'], '/ajax/load-leaves', [LeaveMaster::class, 'getAllLeaves']);
$routes->match(['get', 'post'], '/ajax/add-leave', [LeaveMaster::class, 'addLeave']);
$routes->match(['get', 'post'], '/ajax/delete-leave', [LeaveMaster::class, 'deleteLeave']);
$routes->match(['get', 'post'], '/ajax/get-leave', [LeaveMaster::class, 'getLeave']);
$routes->match(['get', 'post'], '/ajax/update-leave', [LeaveMaster::class, 'updateLeave']);
/*end::Leave Master*/
