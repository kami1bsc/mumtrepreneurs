<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;

class StripeController extends Controller
{
    public function stripe()
    {
        return view('stripe');
    }

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Xofer App"
        ]);
   
        Session::flash('success', 'Payment successful!');
           
        return back();
    }

    public function charge_payment(Request $request)
    {
        // try{
            // $stripe = new \Stripe\StripeClient(
            //     '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'
            //   );
            
             $stripe = new \Stripe\StripeClient(
                'pk_test_crwKgwLBaPlD6PyegWa6ln6E00AowPrKUI'
              );
              
              
            $token = $stripe->tokens->create([
                'card' => [
                  'number' => $request->card_number,
                  'exp_month' => $request->exp_month,
                  'exp_year' => $request->exp_year,
                  'cvc' => $request->cvv,
                ],
            ]);

            // return $token->id;
            // Stripe\Stripe::setApiKey('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');
            
            Stripe\Stripe::setApiKey('sk_test_BHlJPzC6PloLo7ELEKksI1uy00LlQbLa2X');

            $pay = Stripe\Charge::create ([
                    "amount" => 100 * $request->amount,
                    "currency" => "USD",
                    "source" => $token->id,
                    "description" => "Sentinels Payment"
            ]);
            // return $pay->status;
            
            // $stripe = new \Stripe\StripeClient('sk_test_BHlJPzC6PloLo7ELEKksI1uy00LlQbLa2X');

            // $stripe->payouts->create(['amount' => 1000, 'currency' => 'aed']);
            
            // return $stripe;
            
            if($pay->status == 'succeeded')
            {
                return response()->json([
                    'status' => true,
                    'message' => $pay->paid == true || $pay->paid == 1 ? 'Payment Successfull' : 'Payment not Successfull',
                    'data' => [
                        'status' => $pay->status,
                        'transaction_id' => $pay->id,
                        // 'receipt_url' => $pay->receipt_url,
                    ],
                ], 200);    
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot process payment. Please try again later.',
                ], 200);
            }

        // }catch(\Exception $e)
        // {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Cannot process payment. Please try again later.',
        //     ], 200);
        // }
    }
}
