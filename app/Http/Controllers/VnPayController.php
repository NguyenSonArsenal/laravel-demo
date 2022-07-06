<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Backend\Base\BackendController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnPayController extends Controller
{
    public function getVnpay()
    {
        return view('vnpay.index');
    }

    public function checkout()
    {
        try {
            $vnp_TxnRef = time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'des';
            $vnp_OrderType = '1';
            $vnp_Amount = 10000 * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

            $inputData = array(
                "vnp_Version" => "2.0.0",
                "vnp_TmnCode" => env('VNP_TMN_CODE'),
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => 'http://dev.blog.jp/checkout/return-vnpay',
                "vnp_TxnRef" => $vnp_TxnRef,
            );
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . $key . "=" . $value;
                } else {
                    $hashdata .= $key . "=" . $value;
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }


            $vnp_Url = env('VNP_URL') . "?" . $query;
            if (env('VNP_HASH_SECRET')) {
                $vnpSecureHash = hash('sha256', env('VNP_HASH_SECRET') . $hashdata);
                $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
            }
            Log::info($vnp_Url);
            return redirect($vnp_Url);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function returnVnpay(Request $request)
    {
        dd($request->vnp_ResponseCode, 1);
    }
}
