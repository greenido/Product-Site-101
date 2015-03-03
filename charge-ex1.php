<?php
// Get the latest version from: https://github.com/stripe/stripe-php/releases
require_once("stripe-php-1.18.0/lib/Stripe.php");

// Let's see if we getting a new card with details to charge
if ($_POST) {
  
  // Set your secret key: remember to change this to your live secret key in production
  // We are using the test seceret key here just for our demo
  Stripe::setApiKey("sk_test_Hta3le08tAzxbMq4NvgQVnqe");
  $error = '';
  $success = '';
  try {
    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");
    Stripe_Charge::create(array("amount" => 99999999, // amount in cents - so it's 9,999.99$ (cheap!)
                                "currency" => "usd",
                                "card" => $_POST['stripeToken']));
    $success = 'Your payment was successful. Good day!';
  }
  catch (Exception $e) {
    $error = $e->getMessage();
    error_log("Err: could not create a payment: " . $error);
  }
}
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Stripe - Getting Started Form</title>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">

            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_test_2HBorPiB3CC68c1ujnhqGMd0');

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");

                    // createToken returns immediately
                    // the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    // submit from callback
                    return false; 
                });
            });
        </script>
    </head>
    <body>
        <h1>Charge $9,999.99 For an hour with Ido</h1>
        <h5>* Price is what you pay value is what you get.</h5>
        <!-- to display errors returned by createToken -->
        <span class="payment-errors"><?= $error ?></span>
        <span class="payment-success"><?= $success ?></span>
        <form action="" method="POST" id="payment-form">
            <div class="form-row">
                <label>Card Number</label>
                <input type="text" size="20" autocomplete="off" class="card-number" />
            </div>
            <div class="form-row">
                <label>CVC</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc" />
            </div>
            <div class="form-row">
                <label>Expiration (MM/YYYY)</label>
                <input type="text" size="2" class="card-expiry-month"/>
                <span> / </span>
                <input type="text" size="4" class="card-expiry-year"/>
            </div>
            <button type="submit" class="submit-button">Submit Payment</button>
        </form>
    </body>
</html>