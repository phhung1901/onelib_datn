<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Enums\PaymentStatus;
use App\Models\Enums\SourcePayment;
use App\Models\Payment;
use App\Models\User;
use App\Service\DownloadService;
use App\Service\DownloadVNPayService;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class DownloadController extends Controller
{
    protected $document;
    private $client_paypal;
    public function __construct()
    {
        $environment = new SandboxEnvironment(config('paymnet_service.paypal.paypal_client_id'), config('paymnet_service.paypal.paypal_client_secret'));
        $this->client_paypal = new PayPalHttpClient($environment);
    }

    public function download($id, $slug)
    {
        $document = Document::where('id', $id)
            ->where('slug', $slug)
            ->first();
        $this->document = $document;
        if (\Auth::check() && \Auth::id() != $document->user_id){
            $user =\Auth::user();
            if ($user->money >= $document->price){
                $user->money = round(($user->money - $document->price),2);
                $user->save();
                $author = User::where('id', $document->user_id)->first();
                $author->money = round(($author->money + $document->price*config('paymnet_service.percent_received')),2);
                $author->save();
                return DownloadService::download($document);
            }
            else{
                return redirect()->back()->with('common_error', 'Your account balance is not enough, please recharge and try again');
            }
        }
        return DownloadService::download($document);
    }

    public function VNPayRedirectPayment(Request $request, $id, $slug)
    {
        $document = Document::where('id', $id)
            ->where('slug', $slug)
            ->first();
        $vnpay_service = DownloadVNPayService::create($request->price, $document);
        return redirect($vnpay_service);
    }

    public function VNPayGetResponse($id, $slug)
    {
        $vnpay_service = DownloadVNPayService::response();
        if ($vnpay_service) {
            return $this->download($id, $slug);
//            session(['download_file' => ['id' => $document->id, 'slug' => $document->slug]]);
//            return redirect()->route('document.detail', ['slug' => $document->slug]);
        }
        return redirect()->route('document.detail', ['slug' => $slug])->with('common_error', 'Transaction failed, please try again');
    }

    /**
     * @throws \Exception
     */
    public function PaypalRedirectPayment(Request $request, $id, $slug)
    {
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
                'return_url' => route('frontend_v4.document.responsePaypal', ['id' => $id,'slug' => $slug]),
                'cancel_url' => route('frontend_v4.document.redirectPaypal', ['id' => $id,'slug' => $slug])
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

    public function PaypalGetResponse(Request $request, $id, $slug)
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
            if ($result->status == "COMPLETED") {
                Payment::create([
                    'user_id' => \Auth::id() ?? 1,
                    'status' => PaymentStatus::SUCCESS,
                    'price' => $price,
                    'trading_code' => $trading_code,
                    'transaction_id' => $result->id,
                    'message' => $response->result->purchase_units[0]->shipping->name->full_name,
                    'source' => SourcePayment::PAYPAL
                ]);
                return $this->download($id, $slug);
//             return redirect()->route('document.detail', ['slug' => $document->slug]);
            }
            return redirect()->route('document.detail', ['slug' => $slug])->with('common_error', 'Transaction failed, please try again');
        } catch (\Exception $e) {
            return redirect()->route('document.detail', ['slug' => $slug])->with('common_error', 'Transaction failed, please try again');
        }
    }
}
