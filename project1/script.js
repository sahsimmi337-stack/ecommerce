
// ==========================================
// GET CART FROM LOCAL STORAGE
// ==========================================

let cart = JSON.parse(localStorage.getItem("cart")) || [];


// ==========================================
// ADD TO CART
// ==========================================

let buttons = document.querySelectorAll(".cart-btn");

buttons.forEach((button) => {

    button.addEventListener("click", function () {

        let product = this.closest(".box-content");

        if (!product) {
            return;
        }

        // Product name
        let nameElement = product.querySelector("h2, h3");

        if (!nameElement) {
            alert("Product name not found!");
            return;
        }

        let name = nameElement.innerText.trim();


        // Product price
        let priceElement = product.querySelector(".price");

        if (!priceElement) {
            alert("Product price not found!");
            return;
        }

        let price = Number(
            priceElement.innerText
                .replace("₹", "")
                .replace(",", "")
                .trim()
        );


        // Product ID
        let id = this.dataset.id;

        // If no ID is present, create one
        if (!id) {
            id = name.toLowerCase().replace(/\s+/g, "-");
        }


        // Product image
        let image = "";

        let imageElement = product.querySelector(".box-img");

        if (imageElement) {

            let imageStyle = imageElement.getAttribute("style");

            if (imageStyle) {

                let imageMatch = imageStyle.match(
                    /url\(['"]?(.*?)['"]?\)/
                );

                if (imageMatch) {
                    image = imageMatch[1];
                }
            }
        }


        // Check if product already exists
        let existingProduct = cart.find(
            item => item.id === id
        );


        if (existingProduct) {

            // Increase quantity
            existingProduct.quantity++;

        } else {

            // Add new product
            cart.push({

                id: id,
                name: name,
                price: price,
                image: image,
                quantity: 1

            });
        }


        // Save cart
        localStorage.setItem(
            "cart",
            JSON.stringify(cart)
        );


        alert(name + " added to cart!");

    });

});


// ==========================================
// DISPLAY CART
// ==========================================

function displayCart() {

    let cartItems =
        document.getElementById("cart-items");

    let totalElement =
        document.getElementById("total");


    // If cart elements don't exist
    // For example, on home page
    if (!cartItems) {
        return;
    }


    // Get latest cart
    cart =
        JSON.parse(localStorage.getItem("cart")) || [];


    // Clear old content
    cartItems.innerHTML = "";


    let total = 0;


    // ==========================================
    // EMPTY CART
    // ==========================================

    if (cart.length === 0) {

        cartItems.innerHTML = `
            <div class="empty-cart">

                <h2>Your cart is empty 🛒</h2>

                <p>Add some products to your cart.</p>

            </div>
        `;

        if (totalElement) {
            totalElement.innerText = "0";
        }

        return;
    }


    // ==========================================
    // DISPLAY PRODUCTS
    // ==========================================

    cart.forEach((item, index) => {

        let itemTotal =
            Number(item.price) * Number(item.quantity);

        total += itemTotal;


        cartItems.innerHTML += `

            <div class="cart-item">

                <img
                    src="${item.image}"
                    class="cart-image"
                    alt="${item.name}"
                >

                <div class="cart-product-info">

                    <h2>${item.name}</h2>

                    <p class="cart-price">
                        Price: ₹${item.price}
                    </p>


                    <div class="quantity-section">

                        <button
                            class="quantity-btn"
                            onclick="decreaseQuantity(${index})"
                        >
                            −
                        </button>


                        <span class="quantity">
                            ${item.quantity}
                        </span>


                        <button
                            class="quantity-btn"
                            onclick="increaseQuantity(${index})"
                        >
                            +
                        </button>

                    </div>


                    <p class="item-total">
                        Item Total: ₹${itemTotal}
                    </p>


                    <button
                        class="remove-btn"
                        onclick="removeItem(${index})"
                    >
                        Remove
                    </button>

                </div>

            </div>

        `;
    });


    // Display total
    if (totalElement) {
        totalElement.innerText = total;
    }
}


// ==========================================
// INCREASE QUANTITY
// ==========================================

function increaseQuantity(index) {

    cart =
        JSON.parse(localStorage.getItem("cart")) || [];


    if (cart[index]) {

        cart[index].quantity++;

    }


    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );


    displayCart();
}


// ==========================================
// DECREASE QUANTITY
// ==========================================

function decreaseQuantity(index) {

    cart =
        JSON.parse(localStorage.getItem("cart")) || [];


    if (!cart[index]) {
        return;
    }


    if (cart[index].quantity > 1) {

        cart[index].quantity--;

    } else {

        // Remove product if quantity becomes 0
        cart.splice(index, 1);

    }


    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );


    displayCart();
}


// ==========================================
// REMOVE PRODUCT
// ==========================================

function removeItem(index) {

    cart =
        JSON.parse(localStorage.getItem("cart")) || [];


    if (!cart[index]) {
        return;
    }


    cart.splice(index, 1);


    localStorage.setItem(
        "cart",
        JSON.stringify(cart)
    );


    displayCart();
}


// ==========================================
// PLACE ORDER - PHP BACKEND
// ==========================================

let placeOrderButton =
    document.getElementById("place-order");


if (placeOrderButton) {

    placeOrderButton.addEventListener(
        "click",
        function () {

            cart =
                JSON.parse(localStorage.getItem("cart")) || [];


            // Check empty cart
            if (cart.length === 0) {

                alert("Your cart is empty!");

                return;
            }


            // Calculate total amount
            let total = cart.reduce(
                (sum, item) => {

                    return sum +
                        Number(item.price) *
                        Number(item.quantity);

                },
                0
            );


            // Create form
            let form =
                document.createElement("form");


            form.method = "POST";

            form.action = "checkout.php";


            // Send cart total
            let input =
                document.createElement("input");


            input.type = "hidden";

            input.name = "cart_total";

            input.value = total.toFixed(2);


            form.appendChild(input);

            document.body.appendChild(form);


            // Send data to PHP
            form.submit();

        }
    );

}


// ==========================================
// CONTINUE SHOPPING
// ==========================================

let continueShopping =
    document.getElementById("continue-shopping");


if (continueShopping) {

    continueShopping.addEventListener(
        "click",
        function () {

            window.location.href = "home.html";

        }
    );

}


// ==========================================
// DISPLAY CART WHEN PAGE LOADS
// ==========================================

displayCart();

