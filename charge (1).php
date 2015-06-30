<?php
// Get the latest version of stripe from: https://github.com/stripe/stripe-php/releases
require_once("stripe-php-1.18.0/lib/Stripe.php");

//
// Set your secret key: remember to change this to your live secret key in production
// We are using the test seceret key here just for our demo
//
Stripe::setApiKey("sk_test_Hta3le08tAzxbMq4NvgQVnqe");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// Create the charge on Stripe's servers - this will charge the user's card
try {
  $charge = Stripe_Charge::create(array(
  "amount" => 999999, // amount in cents - so it's 9,999.99$ (cheap!)
  "currency" => "usd",
  "card" => $token,
  "description" => "OurPayingUser@example.com")
);
  error_log("Charge obj: $charge");
  $charge = str_replace("{", "<br>{", $charge);
  $charge = str_replace("}", "}<br>", $charge);
  $charge = str_replace(",", "<br>", $charge);
  $htmlCharge = "<h4> {$charge} </h4>";
  echo $htmlCharge;
} catch(Stripe_CardError $e) {
  // The card has been declined
  error_log("Err: We could not charge. The card has been declined.\n Error Details: " . $e);
}


