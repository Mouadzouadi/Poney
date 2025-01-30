<?php

require 'vendor/autoload.php';
use App\Controllers\Payement\StripePayement;
use Config\ConfigStripe;

$data = $_GET;


$stripe = new StripePayement(ConfigStripe::$STRIPE_SECRET_KEY);
$session = $stripe->createSession($data);
if (!isset($session->url)) {
    die("Erreur lors de la création de la session Stripe.");
}

// Redirection vers Stripe
header("Location: " . $session->url);
exit();


?>