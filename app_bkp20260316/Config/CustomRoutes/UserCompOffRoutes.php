<?php
/*begin::User Comp Off*/

use App\Controllers\Requests\CompOffCredit;

$routes->get('/backend/user/comp-off-credit-requests', [CompOffCredit::class, 'index']);
$routes->match(['get', 'post'], '/ajax/backend/user/create-comp-off-credit-request', [CompOffCredit::class, 'createCompOffCreditRequest']);
$routes->match(['get', 'post'], '/ajax/backend/user/get-all-comp-off-credit-requests', [CompOffCredit::class, 'getAllCompOffCreditRequests']);
/*end::User Comp Off*/
