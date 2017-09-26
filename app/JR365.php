<?php
namespace jobready365;

use jobready365\Http\Controllers\RegistrationController;

/**
* class JR365 send SMS to User Mobile Numbers.
* @author San Win Maung
*/
class JR365 {

    public static function sendOtp($sendConfirm)
    {
        /*
        * curl_init — Initialize a cURL session
        */
        $ch = curl_init();

        /*
        * Insert your variable data 
        */
        // $u="jobready";
        // $p="9eebe9690362b13fd58b667251db80b3";
        // $k="JR365";

        $u="jobreadyotp";
        $p="9eebe9690362b13fd58b667251db80b3";
        $k="JR365OTP";

        /* Check customer phone no and delete '0' */
        $phone = $sendConfirm['phone'];

        $phone = str_split($phone);

        if ($phone[0] == '0')
        {
            unset($phone[0]);
        }

        $phone = implode("", $phone);

        $callerid = '95' . $phone;

        // $callerid = $phone;
        // $callerid = "phone";
        // $otp = "123456";
        $otp = $sendConfirm['otp'];
        $m = "Welcome to www.jobready365.com ! Your confirmation code is " . urlencode($otp);

        /*
        * Make variable change to url 
        */
        // $query_string = 'apiv2.blueplanet.com.mm/mptsdp/sendsmsapi.php?u=' . urlencode($u) . '&p=' . urlencode($p) . '&k=' . urlencode($k) . '&callerid=' . urlencode($callerid) . '&m=' . urlencode($m);
        $query_string = 'apiv2.blueplanet.com.mm/mptsdp/bizsendsmsapi.php?callerid=' . urlencode($callerid) . '&k=' . urlencode($k) . '&u=' . urlencode($u) . '&p=' . urlencode($p) . '&m=' . urlencode($m);

        curl_setopt($ch, CURLOPT_URL, $query_string);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // grab URL and pass it to the browser
        curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

    }

    public static function resendOtp($user)
    {
        /*
        * curl_init — Initialize a cURL session
        */
        $ch = curl_init();

        /*
        * Insert your variable data 
        */
        // $u="jobready";
        // $p="9eebe9690362b13fd58b667251db80b3";
        // $k="JR365";

        $u="jobreadyotp";
        $p="9eebe9690362b13fd58b667251db80b3";
        $k="JR365OTP";

        /* Check customer phone no and delete '0' */
        $phone = $user['telephone_no'];

        $phone = str_split($phone);

        if ($phone[0] == '0')
        {
            unset($phone[0]);
        }

        $phone = implode("", $phone);

        $callerid = '95' . $phone;

        // $callerid = $phone;
        // $callerid = "phone";
        // $otp = "123456";
        $otp = $user['activation_code'];
        $m = "Welcome to www.jobready365.com ! Your confirmation code is " . urlencode($otp);

        /*
        * Make variable change to url 
        */
        // $query_string = 'apiv2.blueplanet.com.mm/mptsdp/sendsmsapi.php?u=' . urlencode($u) . '&p=' . urlencode($p) . '&k=' . urlencode($k) . '&callerid=' . urlencode($callerid) . '&m=' . urlencode($m);
        $query_string = 'apiv2.blueplanet.com.mm/mptsdp/bizsendsmsapi.php?callerid=' . urlencode($callerid) . '&k=' . urlencode($k) . '&u=' . urlencode($u) . '&p=' . urlencode($p) . '&m=' . urlencode($m);

        curl_setopt($ch, CURLOPT_URL, $query_string);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // grab URL and pass it to the browser
        curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

    }

}
?>