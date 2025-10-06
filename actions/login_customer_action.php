<?php

header('Content-Type: application/json');

session_start();

$response = [];

// TODO: Check if the user is already logged in and redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';


$user= login_user_ctr($email, $password);

if ($user) {
    $_SESSION['user_id'] = $user['customer_id'];
    $_SESSION['user_role'] = $user['user_role'];
    $_SESSION['name'] = $user['customer_name'];

    $response['status'] = 'success';
    $response['message'] = 'Login successful!';
    $response['user_id'] = $user['customer_id'];
    $response['user_role'] = $user['user_role'];
    $response['name'] = $user['customer_name'];
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email or password';
}

echo json_encode($response);