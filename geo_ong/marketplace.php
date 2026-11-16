<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>E-commerce</title>
</head>

<body>
   

<?php
include 'db.php';

$sql = "SELECT * FROM product_tbl";
$result = $conn->query($sql);
?>

<div class="product-grid" id="products_tbl">     
     <?php
     if($result && $result->num_rows > 0){
         while($product = $result->fetch_assoc()){
     ?>
<div class="ehey">

      <div class="img-holder">
           <img src="images/<?php echo htmlspecialchars($product['img']); ?>" alt="Product"> 
      </div>

       <div class="product-name">
             <?php echo htmlspecialchars($product['product_name']); ?>
       </div>

       <div class="product-price">
             P<?php echo number_format($product['price'], 2); ?>
       </div>

       <div class="product-stock">
            Stocks: <?php echo (int)$product['quantity']; ?>
       </div>

       <div class="btn-group">
         <button class="buy-now"
         onclick='openBuyNow(
             <?php echo (int)$product['product_id']; ?>,
             <?php echo json_encode($product['product_name'], ENT_QUOTES); ?>,
             <?php echo (float)$product['price']; ?>,
             <?php echo (int)$product['quantity']; ?>,
             <?php echo json_encode($product['img'], ENT_QUOTES); ?>
         )'>
             Buy Me
         </button>
         
         <button class="addcart"
         onclick='openBuyNow(
             <?php echo (int)$product['product_id']; ?>,
             <?php echo json_encode($product['product_name'], ENT_QUOTES); ?>,
             <?php echo (float)$product['price']; ?>,
             <?php echo (int)$product['quantity']; ?>,
             <?php echo json_encode($product['img'], ENT_QUOTES); ?>
         )'>
         Add to Cart mo 'ko
         </button>
       </div>
    </div>

<?php
     }
} else {    
    echo "<p>No products available.</p>";
}

$conn->close();
?>
</div>

<div class="modal-overlay" id="buyNowModal">
    <div class="checkout-modal">
       <div class="modal-header">
            <h2>Product Checkout</h2>
            <button type="button" class="modal-close" onclick="closeBuyNow()">
                &times;
            </button>
       </div>

       <form id="checkoutForm" method="POST">
          <input type="hidden" name="product_id" id="checkoutProductId" value="">

          <div class="checkout-product">
            <div class="checkout-image">
               <img id="checkoutImage" src="" alt="Product Image">
            </div>
            <div class="checkout-details">
                 <h3 id="checkoutName">Product Name</h3>
                 <p>
                     Price:
                     <strong id="checkoutPrice">P0.00</strong>
                </p>
                <p>
                     Available Stocks:
                     <strong id="checkoutStocks">0</strong>
                </p>
           </div>
        </div>

          <hr>

              <h3 class="form-heading">Customer Information</h3>
             
              <div class="form-group">
                  <label for="customerName">Full Name</label>
             <input type="text"
                    id="customerName"
                    name="customer_name"
                    placeholder="Enter your full name"
                    required>
         </div>

      <div class="form-group">
           <label for="customerAddress">Delivery Address</label>
          <textarea
             id="customerAddress"
             name="customer_address"
             placeholder="Enter your complete delivery address"
             rows="3"
             required></textarea>
     </div>

    <div class="form-group">
    <label for="customerContact">Contact Number</label>
          <input type="tel"
                 id="customerContact"
                 name="customer-contact"
                 placeholder="Enter your contact number"
                 required>
    </div>

          <div class="form-group">
                     <label for="orderQuantity">Quantity to Order</label>
              <input type="number" 
                     id="orderQuantity"
                     name="quantity"
                     value="1"
                     min="1"
                     step="1"
                     required>
                <small id="quantityMessage">
                    Minimum order: 1
                </small>
         </div>

                <div class="checkout-total">
                <span>Total Amount:</span>
                    <strong id="checkoutTotal">P0.00</strong>
                </div>

             <div class="checkout-buttons">
                    <button type="button"
                          class="cancel-btn"
                           onclick="closeBuyNow()">
                          Cancel
                    </button>
                    <button type="submit"
                          class="purchase-btn">
                          Confirm Purchase 
                    </button>
               </div>
        </form>
       </div>
    </div>
</body>
<script src="lamoha.js"></script>
</html>