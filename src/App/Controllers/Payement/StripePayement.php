<?php
namespace App\Controllers\Payement;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePayement
{
    public function __construct(readonly private string $clientSecret)
    {
        
        Stripe::setApiKey($clientSecret);
        Stripe::setApiVersion('2020-08-27');
    }

    public function createSession($data)
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $data['type'],
                        ],
                        'unit_amount' => $data['prix'] * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/index.php?action=coursReserver',
            'cancel_url' => 'http://localhost:8000/index.php?action=planning',
        ]);

}

}
      

?>