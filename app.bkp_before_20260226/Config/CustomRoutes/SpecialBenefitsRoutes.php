<?php
/*begin::Special Benifits*/

use App\Controllers\Override\SpecialBenefits;

$routes->match(['get', 'post'], '/backend/hr/special-benifits', [SpecialBenefits::class, 'index']);
// $routes->match(['get', 'post'], '/ajax/backend/hr/update-special-benifits', [SpecialBenefits::class, 'updateSpecialBenefit']);
$routes->match(['get', 'post'], '/ajax/backend/hr/update-special-benefits', [SpecialBenefits::class, 'updateSpecialBenefit']);
/*end::Special Benifits*/
