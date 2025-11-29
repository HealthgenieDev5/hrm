<?php 
$conn=mysqli_connect('localhost','healthgenie','incorrect@404','healthgenie');

$get_data=mysqli_query($conn,"select * from sheet");

if(file_exists('data.csv')){
	unlink('data.csv');
}

$file = fopen('data2.csv', 'w');

fputcsv($file, array('prefix','req_qty','act_qty','suffix','product_sku','sku_id','price','product_category','product_name','brand','portal','warranty_available','warranty_tenure','response_message'));

foreach($get_data as $data){
	$prefix=$data['prefix'];
	$req_qty=$data['req_qty'];
	$act_qty=(int)$data['act_qty'];
	$product_sku=$data['product_sku'];
	$sku_id=$data['sku_id'];
	$price=$data['price'];
	$product_category=$data['product_category'];
	$product_name=$data['product_name'];
	$brand=$data['brand'];
	$portal=$data['portal'];
	$warranty_available=$data['warranty_available'];
	$warranty_tenure=$data['warranty_tenure'];
	$response_message=$data['response_message'];
	
	for($i=0;$i<$act_qty;$i++){
		$get_suffix=mysqli_query($conn,"select * from suffix where used='no' limit 1");
		$suffix=mysqli_fetch_assoc($get_suffix)['suffix'];
		fputcsv($file, array($prefix, $req_qty, $act_qty, $suffix, $product_sku, $sku_id, $price, $product_category, $product_name, $brand, $portal, $warranty_available, $warranty_tenure, $response_message));
		$update_suffix=mysqli_query($conn,"update suffix set used='yes' where suffix='".$suffix."'");
	}
}	
?>