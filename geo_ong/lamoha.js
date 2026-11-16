let selectedProduct = {
    id: 0,
    name: "",
    price: 0,
    stocks: 0,
    image: ""
};

// OPEN BUY NOW MODAL
function openBuyNow(id, name, price, stocks, image) {
    selectedProduct.id = Number(id);
    selectedProduct.name = name;
    selectedProduct.price = Number(price);
    selectedProduct.stocks = Number(stocks);

    // Build image path
    selectedProduct.image = image.startsWith("images/")
        ? image
        : "images/" + image;

    // Set product ID in hidden input for PHP backend
    const productIdInput = document.getElementById("checkoutProductId");
    if (productIdInput) {
        productIdInput.value = selectedProduct.id;
    }

    // Display product information
    document.getElementById("checkoutImage").src = selectedProduct.image;
    document.getElementById("checkoutName").textContent = selectedProduct.name;
    document.getElementById("checkoutPrice").textContent =
        "P" + selectedProduct.price.toFixed(2);
    document.getElementById("checkoutStocks").textContent = selectedProduct.stocks;

    // Reset checkout form fields
    document.getElementById("checkoutForm").reset();
    if (productIdInput) productIdInput.value = selectedProduct.id;

    // Set quantity limits
    const quantityInput = document.getElementById("orderQuantity");
    quantityInput.min = 1;
    quantityInput.max = selectedProduct.stocks;
    quantityInput.value = 1;

    // Update quantity message
    document.getElementById("quantityMessage").textContent =
        "Minimum order: 1 | Maximum order: " + selectedProduct.stocks;

    // Update total
    updateCheckoutTotal();

    // Show modal
    document.getElementById("buyNowModal").classList.add("active");

    // Prevent background scrolling
    document.body.style.overflow = "hidden";
}

// CLOSE BUY NOW MODAL
function closeBuyNow() {
    document.getElementById("buyNowModal").classList.remove("active");
    document.body.style.overflow = "auto";
}

// UPDATE TOTAL AMOUNT
function updateCheckoutTotal() {
    const quantityInput = document.getElementById("orderQuantity");
    let quantity = Number(quantityInput.value);

    if (!Number.isInteger(quantity) || quantity < 1) {
        quantity = 1;
    }

    const total = selectedProduct.price * quantity;

    document.getElementById("checkoutTotal").textContent =
        "P" + total.toFixed(2);
}

// VALIDATE ORDER QUANTITY
document.getElementById("orderQuantity").addEventListener("input", function () {
    let quantity = Number(this.value);
    const message = document.getElementById("quantityMessage");

    if (this.value === "") {
        message.textContent = "Please enter a quantity.";
        updateCheckoutTotal();
        return;
    }

    if (!Number.isInteger(quantity) || quantity < 1) {
        message.textContent = "Quantity must be at least 1.";
    } else if (quantity > selectedProduct.stocks) {
        message.textContent = "Only " + selectedProduct.stocks + " stocks available.";
    } else {
        message.textContent =
            "Minimum order: 1 | Maximum order: " + selectedProduct.stocks;
    }

    updateCheckoutTotal();
});

// CONFIRM PURCHASE (AJAX Submission to show alert and update DB)
document.getElementById("checkoutForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const customerName = document.getElementById("customerName").value.trim();
    const customerAddress = document.getElementById("customerAddress").value.trim();
    const customerContact = document.getElementById("customerContact").value.trim();
    const quantity = Number(document.getElementById("orderQuantity").value);

    // Validate customer information
    if (!customerName || !customerAddress || !customerContact) {
        alert("Please complete all customer information.");
        return;
    }

    // Validate quantity
    if (!Number.isInteger(quantity) ||
        quantity < 1 ||
        quantity > selectedProduct.stocks) {
        alert("Please enter a valid quantity within the available stocks.");
        return;
    }

    // Prepare form data to send to PHP
    const formData = new FormData(this);
    formData.append("product_id", selectedProduct.id);

    // Send data to process_order.php asynchronously
    fetch("purchase.php", {
        method: "POST",
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        if (!response.ok) {
            throw new Error(text || "Failed to place order.");
        }
        return text;
    })
    .then(data => {
        alert("Order placed successfully!");
        closeBuyNow();
        location.reload();
    })
    .catch(error => {
        console.error("Error:", error);
        alert(error.message);
    });
});

// CLOSE MODAL WHEN CLICKING OUTSIDE
document.getElementById("buyNowModal").addEventListener("click", function (event) {
    if (event.target === this) {
        closeBuyNow();
    }
});

// CLOSE MODAL USING ESCAPE KEY
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeBuyNow();
    }
});