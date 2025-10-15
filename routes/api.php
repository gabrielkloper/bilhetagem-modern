<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment status endpoint
Route::get('/payment-status/{payment_id}', function (Request $request, $payment_id) {
    try {
        $mercadoPago = app(\App\Services\MercadoPagoService::class);
        $status = $mercadoPago->getPaymentStatus($payment_id);
        
        return response()->json([
            'payment_id' => $payment_id,
            'status' => $status
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->middleware('auth.admin:admin,operador,supervisor');

// Webhook endpoint for Mercado Pago notifications
Route::post('/webhook/mercadopago', function (Request $request) {
    try {
        \Log::info('Mercado Pago Webhook received:', $request->all());
        
        // Process webhook notification
        if ($request->has('data.id') && $request->input('type') === 'payment') {
            $paymentId = $request->input('data.id');
            
            // Find entrada by payment_id and update status
            $entrada = \App\Models\Entrada::where('payment_id', $paymentId)->first();
            
            if ($entrada) {
                $mercadoPago = app(\App\Services\MercadoPagoService::class);
                $payment = $mercadoPago->getPayment($paymentId);
                
                if ($payment) {
                    $entrada->update([
                        'payment_status' => $payment->status
                    ]);
                }
            }
        }
        
        return response()->json(['status' => 'ok']);
    } catch (\Exception $e) {
        \Log::error('Webhook processing error: ' . $e->getMessage());
        return response()->json(['error' => 'Webhook processing failed'], 500);
    }
});