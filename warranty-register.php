<?php
header('Content-Type: application/json');
require '../../includes/config.php';


$secret = "dTdQRHJ0QTRPeGJwbVhKNWNWVzNHME5RTWxTV29rZkJmMVd1MGlmT0RhSTo=";

$payload = file_get_contents("php://input");
$headers = getallheaders();

file_put_contents("debug_headers.log", print_r($headers, true));
file_put_contents("webhook_log.log", $payload . "\n\n", FILE_APPEND);

// Verify Signature if present
if (isset($headers["Interakt-Signature"])) {
    $signature = $headers["Interakt-Signature"];

    if (!hash_is_valid($secret, $payload, $signature)) {
        //     http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
        exit;
    }
} else {
    file_put_contents("signature_log.log", "Signature missing\n", FILE_APPEND);
}

$data = json_decode($payload, true);
if ($data === null) {
    file_put_contents("json_error.log", json_last_error_msg());
    // http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

// Handle based on webhook type

$action = $data['data']['customer']['traits']['action'] ?? 'message_api_clicked';


switch ($action) {

    // We only process certain webhook types for warranty registration

    case 'is_exist':
        is_already_registered($data, $conn);
        break;
    case 'message_api_clicked':
        process_warranty_data($data, $conn);
        break;

    default:
        // For other webhooks, just acknowledge
        echo json_encode(['status' => 'ignored', 'message' => 'Webhook type not handled']);
        break;
}

http_response_code(200);

function process_warranty_data($data, $conn)
{
    $traits = $data['data']['customer']['traits'] ?? [];
    $customer = $data['data']['customer'] ?? [];

    $contact_number = $customer['phone_number'] ?? ($customer['channel_phone_number'] ?? '');
    $product_category = mysqli_real_escape_string($conn, ucwords($traits['product_category'] ?? ''));
    $product_category = ($product_category == 'Bathroom Scales') ? 'Weighing Scale' : $product_category;

    $name = mysqli_real_escape_string($conn, $traits['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $traits['email'] ?? '');
    $portal = mysqli_real_escape_string($conn, $traits['portal'] ?? '');
    $invoice_file_path = $traits['invoice'] ?? '';
    $source = 'WhatsApp Bot';
    $disposition_id = 1;
    $sub_disposition_id = 1;

    $local_path = save_interakt_file($invoice_file_path);
    if ($invoice_file_path && $local_path === false) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or unsupported file type']);
        exit;
    }

    if (!empty($product_category) && !empty($contact_number)) {

        // Check for existing entry
        $check_query = "SELECT id, customer_email, portal, invoice_attachment FROM hg_scratch_card WHERE product_category = ? AND customer_mobile = ? AND (invoice_attachment IS NULL OR invoice_attachment = '') AND source = 'WhatsApp Bot' ORDER BY id DESC LIMIT 1";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ss", $product_category, $contact_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        //if ($row && !empty($invoice_file_path)) {

        if ($row) {


            // Record exists – Update it
            $id = $row['id'];

            // $update_query = "UPDATE hg_scratch_card SET 
            //     customer_mobile = IF(?, ?, customer_mobile),
            //     customer_email = IF(?, ?, customer_email),
            //     portal = IF(?, ?, portal),
            //     invoice_attachment = IF(?, ?, invoice_attachment)
            //     WHERE id = ?";

            // $stmt = mysqli_prepare($conn, $update_query);
            // mysqli_stmt_bind_param(
            //     $stmt,
            //     "sssssssssi",
            //     $contact_number,
            //     $contact_number,
            //     $email,
            //     $email,
            //     $portal,
            //     $portal,
            //     $local_path,
            //     $local_path,
            //     $id
            // );

            $update_query = "UPDATE hg_scratch_card SET 
            customer_mobile = ?, 
            customer_name = ?,
            customer_email = ?, 
            portal = ?, 
            invoice_attachment = ?
            WHERE id = ?";

            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param(
                $stmt,
                "sssssi", // 4 strings + 1 int
                $contact_number,
                $name,
                $email,
                $portal,
                $local_path,
                $id
            );



            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'DB update error: ' . mysqli_error($conn)]);
            }

            mysqli_stmt_close($stmt);
        } else {
            // New Record – Insert
            // $sql = "INSERT INTO hg_scratch_card 
            //     (product_category, customer_mobile, customer_email, customer_name, portal, invoice_attachment, source, disposition_id, sub_disposition_id) 
            //     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // $sql = "INSERT INTO hg_scratch_card 
            //     (product_category, customer_mobile, customer_email, customer_name, portal, invoice_attachment, source, disposition_id, sub_disposition_id, date) 
            //     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $customer_id = get_customer_id($contact_number, $conn);
            $sql = "INSERT INTO hg_scratch_card 
                (customer_id, product_category, customer_mobile, customer_email, customer_name, portal, invoice_attachment, source, disposition_id, sub_disposition_id, date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $customer_id, $product_category, $contact_number, $email, $name, $portal, $local_path, $source, $disposition_id, $sub_disposition_id);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['status' => 'success', 'message' => 'New record inserted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'DB insert error: ' . mysqli_error($conn)]);
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing product_category or name']);
    }
}

function is_already_registered($data, $conn)
{
    $traits = $data['data']['customer']['traits'] ?? [];
    $customer = $data['data']['customer'] ?? [];

    $contact_number = $customer['phone_number'] ?? ($customer['channel_phone_number'] ?? '');
    $product_category = mysqli_real_escape_string($conn, ucwords($traits['product_category'] ?? ''));
    $product_category = ($product_category == 'Bathroom Scales') ? 'Weighing Scale' : $product_category;

    if (empty($contact_number) || empty($product_category)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        return false;
    }

    // Check if user is already registered for this product category
    $check_query = "SELECT id, customer_name, customer_email, portal, invoice_attachment, date
                    FROM hg_scratch_card
                    WHERE product_category = ?
                    AND customer_mobile = ?
                    -- AND invoice_attachment IS NOT NULL
                    -- AND invoice_attachment != ''
                    AND source = 'WhatsApp Bot'
                    ORDER BY id DESC
                    LIMIT 1";

    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ss", $product_category, $contact_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && $row['invoice_attachment'] != '') {
        echo json_encode([
            'status' => 'exists',
            'message' => 'User already registered for warranty' . json_encode($row),
            'data' => [
                'id' => $row['id'],
                'name' => $row['customer_name'],
                'email' => $row['customer_email'],
                'portal' => $row['portal'],
                'has_invoice' => !empty($row['invoice_attachment']),
                'registration_date' => $row['date']
            ]
        ]);
        return true;
    } else {
        echo json_encode([
            'status' => 'not_found',
            'message' => 'User not registered for warranty yet'
        ]);
        return false;
    }
}


// Signature helper functions
function compute_hash($secret, $payload)
{
    return "sha256=" . hash_hmac("sha256", $payload, utf8_encode($secret));
}

function hash_is_valid($secret, $payload, $verify)
{
    $computed_hash = compute_hash($secret, $payload);
    return hash_equals($verify, $computed_hash);
}

// File saving logic
function save_interakt_file($url)
{
    if (empty($url)) return false;

    $parsed_url = parse_url($url);
    $path = $parsed_url['path'];
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($extension), $allowed_extensions)) {
        return false;
    }

    $filename = time() . '_' . rand(100000000, 999999999) . '.' . $extension;

    $year = date('Y');
    $month = date('m');
    // $upload_dir = __DIR__ . "/uploads/$year/$month";
    $upload_dir = BASE_PATH . "/uploads/$year/$month";
    // $upload_dir = realpath(__DIR__ . '/../') . "/uploads/rma-uploads/$year/$month";


    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $full_path = "$upload_dir/$filename";
    $file_data = file_get_contents($url);

    if ($file_data === false) return false;

    file_put_contents($full_path, $file_data);

    // return "/uploads/$year/$month/$filename";
    return str_replace(BASE_PATH, "", $full_path);
}


function get_customer_id($mobile_number, $conn)
{
    $customer_id = '';

    $query = "SELECT id FROM customer WHERE mobile = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $mobile_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $customer_id = $row['id'] ?? '';
        mysqli_stmt_close($stmt);
    }

    return $customer_id;
}
