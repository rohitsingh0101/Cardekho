<?php
header('Content-Type: application/json');


$data = $_POST;


if (
    empty($data['name']) ||
    empty($data['phone']) ||
    empty($data['email']) ||
    empty($data['address']) ||
    empty($data['selected_cars'])
) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit;
}


$conn = new mysqli("localhost", "root", "", "cardekho");

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}


$stmt = $conn->prepare("
    INSERT INTO car_inquiries (name, phone, email, address, selected_cars)
    VALUES (?, ?, ?, ?, ?)
");

$selectedCarsJson = json_encode($data['selected_cars']);

$stmt->bind_param(
    "sssss",
    $data['name'],
    $data['phone'],
    $data['email'],
    $data['address'],
    $selectedCarsJson
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Your inquiry has been submitted successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit inquiry'
    ]);
}

$stmt->close();
$conn->close();
