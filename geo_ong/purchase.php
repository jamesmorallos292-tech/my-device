<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $product_id = $_POST['product_id'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 0);
    $client_name = $_POST['customer_name'] ?? 'Unknown';
    $client_contact = $_POST['customer-contact'] ?? 'Unknown';

    // Fetch product details from database
    $sql = "SELECT product_id, product_name, img, price, quantity FROM product_tbl WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if (!$product){
        http_response_code(404);
        exit("Product not found.");
    }

    $product_name = $product['product_name'];
    $product_img = $product['img'];
    $price = (float)$product['price'];
    $current_stock = (int)$product['quantity'];

    // Calculate total price
    $total_price = $price * $quantity;

    // Check if ordered quantity exceeds stock
    if ($quantity > $current_stock) {
        http_response_code(400);
        exit("Order exceeds available stocks!");
    }

    // Calculate new stock value
    $new_stock = $current_stock - $quantity;

    // Insert order into database (matches order_tbl and cllient_name)
    $insert = "INSERT INTO order_tbl (orderQ_name, order_img, quantity, total, cllient_name, client_contact)
    VALUES ('$product_name', '$product_img', '$quantity', '$total_price', '$client_name', '$client_contact')";
    mysqli_query($conn, $insert);

    // Update product stock
    $sql_update = "UPDATE product_tbl SET quantity = '$new_stock' WHERE product_id = '$product_id'";
    mysqli_query($conn, $sql_update);

    // Send success response back to JavaScript
    http_response_code(200);
    echo "Success";
}
?>