<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Enums\PaymentStatus;
use App\Models\Enums\SourcePayment;
use App\Models\Payment;
use App\Models\User;
use App\Service\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaymentController extends Controller
{

    private $client_paypal;

    public function __construct()
    {
        $environment = new SandboxEnvironment(config('paymnet_service.paypal.paypal_client_id'), config('paymnet_service.paypal.paypal_client_secret'));
        $this->client_paypal = new PayPalHttpClient($environment);
    }
//    public function getPayment(Request $request, $id, $slug){
//        $document = Document::where('id', $id)
//            ->where('slug', $slug)
//            ->first();
//        return view('frontend_v4.pages.payment.payment');
//    }
//
//    public function postPayment(){
//        dd(1);
//    }

    public function VNPayRedirectPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:10000|max:10000000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('common_error', 'Minimum amount is 10.000 VND and maximum amount is 10.000.000 VND');
        }
        $vnpay_service = VNPayService::create($request->price, route('frontend_v4.getVNPay'));
        return redirect($vnpay_service);
    }

    public function VNPayGetResponse()
    {
        $vnpay_service = VNPayService::response();
        if ($vnpay_service) {
            return redirect()->route('document.home.index')->with('common_success', 'Payment success');
        }
        return redirect()->route('document.home.index')->with('common_error', 'Transaction failed, please try again');
    }

    /**
     * @throws \Exception
     */
    public function PaypalRedirectPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_paypal' => 'required|numeric|digits_between:1,7|min:1.00|max:1000.00',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('common_error', 'Minimum amount is 1.00$ and maximum amount is 1000.00$');
        }

        // Tạo yêu cầu tạo đơn hàng PayPal
        $order_request = new OrdersCreateRequest();
        $order_request->prefer('return=representation');
        $order_request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $request->price_paypal
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => route('frontend_v4.responsePaypal'),
                'cancel_url' => route('frontend_v4.redirectPaypal')
            ]
        ];

        try {
            // Gửi yêu cầu tạo dịch đến PayPal
            $response = $this->client_paypal->execute($order_request);

            // Lấy URL chuyển hướng để người dùng tiến hành thanh toán
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect()->away($link->href);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function PaypalGetResponse(Request $request)
    {
        $orderId = $request->input('token');

        // Tạo yêu cầu xác nhận đơn hàng PayPal
        $request = new OrdersCaptureRequest($orderId);
        try {
            // Gửi yêu cầu xác nhận đơn hàng đến PayPal
            $response = $this->client_paypal->execute($request);

            $trading_code = $response->result->id;
            $result = $response->result->purchase_units[0]->payments->captures[0];
            $price = $result->amount->value;
            if ($result->status == "COMPLETED" ){
                Payment::create([
                    'user_id' => \Auth::id() ?? 1,
                    'status' => PaymentStatus::SUCCESS,
                    'price' => $price,
                    'trading_code' => $trading_code,
                    'transaction_id' => $result->id,
                    'message' => $response->result->purchase_units[0]->shipping->name->full_name,
                    'source' => SourcePayment::PAYPAL
                ]);

                $user = User::where('id', \Auth::id())->first();
                $money = $price + $user->money;
                $user->update([
                    'money' => $money
                ]);
                return redirect()->route('document.home.index')->with('common_success', 'Payment success');
            }
            return redirect()->route('document.home.index')->with('common_error', 'Transaction failed, please try again');
        } catch (\Exception $e) {
            return redirect()->route('document.home.index')->with('common_error', 'Transaction failed, please try again');
        }
    }
}
