
<?php
include 'db.php';

$message = "";
$order_id = "";

$total = $_POST['cart_total'] ?? '';


// ==========================================
// PLACE ORDER
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['customer_name'])
) {

    $name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $total = $_POST['cart_total'];


    // Validate details
    if (
        $name == ""
        || $phone == ""
        || $address == ""
        || !is_numeric($total)
        || $total <= 0
    ) {

        $message = "Please enter valid details.";

    } else {

        // Insert order into database
        $stmt = $conn->prepare(
            "INSERT INTO orders
            (customer_name, phone, address, total_amount)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssd",
            $name,
            $phone,
            $address,
            $total
        );


        if ($stmt->execute()) {

            $order_id = $conn->insert_id;

            $message = "Order placed successfully!";

        } else {

            $message = "Error placing order.";

        }


        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Amazon Clone - Checkout</title>

    <link rel="stylesheet" href="style1.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    >


    <style>

        /* ==========================================
           CHECKOUT PAGE
        ========================================== */

        .checkout-page {
            background: #eaeded;
            min-height: calc(100vh - 60px);
            padding: 45px 20px;
        }


        /* ==========================================
           MAIN CONTAINER
        ========================================== */

        .checkout-container {

            width: 600px;
            max-width: 95%;

            margin: auto;

            background: white;

            padding: 35px;

            border-radius: 8px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
        }


        /* ==========================================
           TITLE
        ========================================== */

        .checkout-title {

            text-align: center;

            font-size: 28px;

            margin-bottom: 8px;
        }


        .checkout-subtitle {

            text-align: center;

            color: #666;

            margin-bottom: 30px;
        }


        /* ==========================================
           TOTAL BOX
        ========================================== */

        .total-box {

            display: flex;

            justify-content: space-between;

            align-items: center;

            background: #f7f7f7;

            border: 1px solid #ddd;

            border-radius: 5px;

            padding: 18px;

            margin-bottom: 30px;
        }


        .total-box span {

            font-size: 17px;

            font-weight: bold;
        }


        .total-box strong {

            font-size: 23px;

            color: #b12704;
        }


        /* ==========================================
           FORM
        ========================================== */

        .form-group {

            margin-bottom: 20px;
        }


        .form-group label {

            display: block;

            font-weight: bold;

            margin-bottom: 8px;
        }


        .form-group input,
        .form-group textarea {

            width: 100%;

            padding: 13px;

            border: 1px solid #aaa;

            border-radius: 5px;

            font-size: 15px;

            outline: none;
        }


        .form-group textarea {

            height: 110px;

            resize: vertical;
        }


        /* ==========================================
           INPUT FOCUS
        ========================================== */

        .form-group input:focus,
        .form-group textarea:focus {

            border-color: #ff9900;

            box-shadow: 0 0 0 2px rgba(255,153,0,0.15);
        }


        /* ==========================================
           CONFIRM BUTTON
        ========================================== */

        .confirm-btn {

            width: 100%;

            padding: 14px;

            margin-top: 5px;

            background: #ffd814;

            border: 1px solid #fcd200;

            border-radius: 25px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;
        }


        .confirm-btn:hover {

            background: #f7ca00;

            transform: translateY(-1px);
        }


        /* ==========================================
           ERROR MESSAGE
        ========================================== */

        .error-message {

            margin-top: 20px;

            padding: 12px;

            background: #ffe6e6;

            border: 1px solid #ffb3b3;

            color: #b00000;

            border-radius: 5px;

            text-align: center;
        }


        /* ==========================================
           SUCCESS BOX
        ========================================== */

        .success-box {

            text-align: center;
        }


        .success-icon {

            width: 75px;

            height: 75px;

            margin: 0 auto 20px;

            background: #067d62;

            color: white;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 45px;

            font-weight: bold;
        }


        .success-title {

            color: #067d62;

            font-size: 28px;

            margin-bottom: 12px;
        }


        .success-message {

            color: #555;

            margin-bottom: 25px;
        }


        /* ==========================================
           ORDER DETAILS
        ========================================== */

        .order-details {

            background: #f7f7f7;

            border: 1px solid #ddd;

            border-radius: 5px;

            padding: 20px;

            text-align: left;

            margin-bottom: 25px;
        }


        .order-details p {

            margin-bottom: 12px;

            font-size: 16px;
        }


        .order-details p:last-child {

            margin-bottom: 0;
        }


        /* ==========================================
           HOME BUTTON
        ========================================== */

        .home-btn {

            display: inline-block;

            padding: 13px 30px;

            background: #ffd814;

            color: #111;

            text-decoration: none;

            border: 1px solid #fcd200;

            border-radius: 25px;

            font-weight: bold;

            transition: 0.2s;
        }


        .home-btn:hover {

            background: #f7ca00;
        }


        /* ==========================================
           MOBILE
        ========================================== */

        @media (max-width: 600px) {

            .checkout-page {

                padding: 25px 10px;
            }


            .checkout-container {

                padding: 25px 20px;
            }


            .checkout-title {

                font-size: 24px;
            }


            .total-box strong {

                font-size: 19px;
            }

        }

    </style>

</head>


<body id="top">


<!-- ==========================================
     AMAZON NAVBAR
========================================== -->

<header>

    <div class="navbar">

        <!-- Logo -->

        <div class="nav-logo border">

            <div class="logo"></div>

        </div>


        <!-- Address -->

        <div class="nav-address border">

            <p class="add-first">
                Deliver to
            </p>

            <div class="add-icon">

                <i class="fa-solid fa-location-dot"></i>

                <p class="add-second">
                    India
                </p>

            </div>

        </div>


        <!-- Search -->

        <div class="nav-search">

            <select
                class="search-select"
                id="category"
            >

                <option>All</option>

                <option>Clothes</option>

                <option>Skin Care</option>

                <option>Furniture</option>

                <option>Electronics</option>

                <option>Make-up</option>

                <option>Home Decor</option>

                <option>Books</option>

                <option>Fashion</option>

            </select>


            <input
                class="search-input"
                type="text"
                id="searchInput"
                placeholder="Search Amazon"
            >


            <div
                class="search-icon"
                id="searchButton"
            >

                <i class="fa-solid fa-magnifying-glass"></i>

            </div>

        </div>


        <!-- Sign in -->

        <div class="nav-signin border">

            <p>
                <span>Hello, Sign in</span>
            </p>

            <p class="nav-second">
                Account & Lists
            </p>

        </div>


        <!-- Returns -->

        <div class="nav-return border">

            <p>
                <span>Returns</span>
            </p>

            <p class="nav-second">
                & orders
            </p>

        </div>


        <!-- Cart -->

        <div
            class="nav-cart border"
            onclick="window.location.href='cart.html'"
        >

            <i class="fa-solid fa-cart-shopping"></i>

            Cart

        </div>

    </div>

</header>


<!-- ==========================================
     CHECKOUT PAGE
========================================== -->

<div class="checkout-page">

    <div class="checkout-container">


        <?php if ($order_id): ?>


            <!-- ==================================
                 SUCCESS MESSAGE
            =================================== -->

            <!-- CLEAR CART AFTER SUCCESSFUL ORDER -->

            <script>
                localStorage.removeItem("cart");
            </script>


            <div class="success-box">

                <div class="success-icon">
                    ✓
                </div>


                <h2 class="success-title">
                    Order Placed Successfully!
                </h2>


                <p class="success-message">
                    Thank you for shopping with Amazon Clone.
                </p>


                <div class="order-details">

                    <p>

                        <strong>
                            Order ID:
                        </strong>

                        #<?php
                        echo htmlspecialchars($order_id);
                        ?>

                    </p>


                    <p>

                        <strong>
                            Total Amount:
                        </strong>

                        ₹<?php
                        echo htmlspecialchars($total);
                        ?>

                    </p>


                    <p>

                        <strong>
                            Status:
                        </strong>

                        Order Confirmed

                    </p>

                </div>


                <a
                    href="home.html"
                    class="home-btn"
                >

                    ← Back to Home

                </a>

            </div>


        <?php else: ?>


            <!-- ==================================
                 CHECKOUT FORM
            =================================== -->

            <h2 class="checkout-title">

                Complete Your Order

            </h2>


            <p class="checkout-subtitle">

                Enter your delivery details below

            </p>


            <!-- TOTAL -->

            <div class="total-box">

                <span>
                    Total Amount
                </span>


                <strong>

                    ₹<?php
                    echo htmlspecialchars($total);
                    ?>

                </strong>

            </div>


            <!-- FORM -->

            <form
                method="POST"
                action="checkout.php"
            >


                <!-- Cart Total -->

                <input
                    type="hidden"
                    name="cart_total"
                    value="<?php
                    echo htmlspecialchars($total);
                    ?>"
                >


                <!-- CUSTOMER NAME -->

                <div class="form-group">

                    <label for="customer_name">

                        Customer Name

                    </label>


                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <!-- PHONE -->

                <div class="form-group">

                    <label for="phone">

                        Phone Number

                    </label>


                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="Enter your phone number"
                        required
                    >

                </div>


                <!-- ADDRESS -->

                <div class="form-group">

                    <label for="address">

                        Delivery Address

                    </label>


                    <textarea
                        id="address"
                        name="address"
                        placeholder="Enter your complete delivery address"
                        required
                    ></textarea>

                </div>


                <!-- CONFIRM ORDER -->

                <button
                    type="submit"
                    class="confirm-btn"
                >

                    Confirm Order

                </button>


            </form>


            <!-- ERROR -->

            <?php if ($message): ?>

                <div class="error-message">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


        <?php endif; ?>


    </div>

</div>


</body>
</html>

