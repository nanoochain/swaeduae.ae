<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createCheckout(Request $request)
    {
        return response()->json([
            'status'  => 'disabled',
            'message' => 'Payments are not enabled on this environment.'
        ], 501);
    }
}
