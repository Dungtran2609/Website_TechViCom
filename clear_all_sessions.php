<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Session;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Clearing All Sessions ===\n";

// Clear all checkout and coupon related sessions
Session::forget('repayment_order_id');
Session::forget('show_repayment_message');
Session::forget('applied_coupon');
Session::forget('restored_coupon');
Session::forget('selected_items');
Session::forget('buynow');
Session::forget('force_cod_for_order_id');
Session::forget('payment_cancelled_message');
Session::forget('guest_vnpay_cancel_count');

echo "✅ All sessions cleared successfully!\n";
echo "\nCleared sessions:\n";
echo "- repayment_order_id\n";
echo "- show_repayment_message\n";
echo "- applied_coupon\n";
echo "- restored_coupon\n";
echo "- selected_items\n";
echo "- buynow\n";
echo "- force_cod_for_order_id\n";
echo "- payment_cancelled_message\n";
echo "- guest_vnpay_cancel_count\n";

echo "\n=== Done ===\n";
