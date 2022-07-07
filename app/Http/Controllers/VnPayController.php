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
        $startTime = date("YmdHis");
        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_Url = env('VNP_URL');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Returnurl = 'http://dev.blog.jp/checkout/return-vnpay';

        try {
            $vnp_TxnRef = time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = "Mo ta don hang";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = 10000 * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
//Add Params of 2.0.1 Version
            $vnp_ExpireDate = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
//Billing
            $vnp_Bill_Mobile = '0964047111';
            $vnp_Bill_Email = 'nguyenvana@gmail.com';
            $fullName = "Nguyen Van A";
            if (isset($fullName) && trim($fullName) != '') {
                $name = explode(' ', $fullName);
                $vnp_Bill_FirstName = array_shift($name);
                $vnp_Bill_LastName = array_pop($name);
            }
            $vnp_Bill_Address='Thanh Hoa';
            $vnp_Bill_City='Thanh Hoa';
            $vnp_Bill_Country='VN';
            $vnp_Bill_State="";
// Invoice
            $vnp_Inv_Phone="02437764668";
            $vnp_Inv_Email='nguyenson@gmail.com';
            $vnp_Inv_Customer='Nguyen son';
            $vnp_Inv_Address='Thanh Hoa';
            $vnp_Inv_Company='ecomobi';
            $vnp_Inv_Taxcode='xxx';
            $vnp_Inv_Type="I";
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate"=>$vnp_ExpireDate,
                "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
                "vnp_Bill_Email"=>$vnp_Bill_Email,
                "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
                "vnp_Bill_LastName"=>$vnp_Bill_LastName,
                "vnp_Bill_Address"=>$vnp_Bill_Address,
                "vnp_Bill_City"=>$vnp_Bill_City,
                "vnp_Bill_Country"=>$vnp_Bill_Country,
                "vnp_Inv_Phone"=>$vnp_Inv_Phone,
                "vnp_Inv_Email"=>$vnp_Inv_Email,
                "vnp_Inv_Customer"=>$vnp_Inv_Customer,
                "vnp_Inv_Address"=>$vnp_Inv_Address,
                "vnp_Inv_Company"=>$vnp_Inv_Company,
                "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
                "vnp_Inv_Type"=>$vnp_Inv_Type
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
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
