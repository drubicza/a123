<?php
function fn_00()
{
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,"http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

    $c_res = curl_exec($ch);
    preg_match_all("~(&bull; (.*?)<br/>&bull; )~",$c_res,$a_res);
    return $a_res[2][mt_rand(0,14)];
}

function fn_01($p_phone)
{
    $s_rname = fn_00();
    $s_email = str_replace(" ","",$s_rname).mt_rand(100,999);
    $s_dpost = '{"name":"'.$s_rname.'","email":"'.$s_email.'@gmail.com","phone":"+'.$p_phone.',"signed_up_country":"ID"}';
    $s_rjson = fn_07("/v5/customers","",$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["otp_token"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
        return false;
    }
}

function fn_02($p_otp,$p_tkn)
{
    $s_dpost = '{"client_name":"gojek:cons:android","data":{"otp":"'.$p_otp.'","otp_token":"'.$p_tkn.'"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
    $s_rjson = fn_07("/v5/customers/phone/verify","",$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["access_token"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
        return false;
    }
}

function fn_03($p_phone)
{
    $s_rname = fn_00();
    $s_email = str_replace(" ","",$s_rname).mt_rand(100,999);
    $s_dpost = '{"phone":"+'.$p_phone.'"}';
    $s_rjson = fn_07("/v4/customers/login_with_phone","",$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["login_token"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
        return false;
    }
}

function fn_04($p_otp,$p_tkn)
{
    $s_dpost = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$p_otp.'","otp_token":"'.$p_tkn.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
    $s_rjson = fn_07("/v4/customers/login/verify","",$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["access_token"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
    return false;
    }
}

function fn_05($p_auth)
{
    $s_dpost = '{"promo_code":"GOFOODNASGOR07"}';
    $s_rjson = fn_07("/go-promotions/v1/promotions/enrollments",$p_auth,$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["message"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
        return false;
    }
}

function fn_06($p_auth)
{
    $s_dpost = '{"promo_code":"GOJEK17"}';
    $s_rjson = fn_07("/go-promotions/v1/promotions/enrollments",$p_auth,$s_dpost);

    if ($s_rjson["success"] == 1) {
        return $s_rjson["data"]["message"];
    } else {
        fn_save("error_log.txt",json_encode($s_rjson));
        return false;
    }
}

echo "\n";
echo "=================\n";
echo "| VEREL GANSSSS |\n";
echo "=================\n\n";
echo "1. Login \n";
echo "2. Daftar \n";
echo "Masukan Pilihan : ";
$s_pilih = trim(fgets(STDIN));

if ($s_pilih == 2) {
    echo "\n";
    echo "Kamu Memilih Daftar\n";
    echo "========================================\n";
    echo "INFO :Awali 62 untuk Indo dan 1 untuk US\n";
    echo "========================================\n";
    echo "Masukan Nomer : ";
    $s_hp  = trim(fgets(STDIN));
    $s_otp = fn_01($s_hp);

    if ($s_otp == false) {
        echo "\n";
        echo "Gagal Ngambil OTP";
    } else {
        echo "Masukan Kode OTP : ";
        $s_kode = trim(fgets(STDIN));
        $s_acct = fn_02($s_kode,$s_otp);

        if ($s_acct == false) {
            echo "\n";
            sleep(1);
            echo "> Gagal Daftarin nomer kamu!\n";
        } else {
            echo "\n";
            sleep(1);
            echo "> Pendaftaran Akun Berhasil";
            echo "\n";
            sleep(2);
            echo "> Sedang Claim Voucher Go-Food...\n";
            $s_vcf = fn_05($s_acct);

            if ($s_vcf == false) {
                sleep(3);
                echo "> Gagal Claim Voucher\n";
            } else {
                sleep(3);
                echo "> Berhasil Claim Voucher Go-Food\n";
                sleep(1);
                echo $s_vcf."\n\n";
                sleep(1);
                echo "> Mencoba Claim Voucher Ke-2...\n";
                sleep(2);
                echo "> Mohon Tunggu Sebentar...\n\n";
                sleep(5);
                echo "> Sedang Claim Voucher Go-Ride\n";
                $s_vcr = fn_06($s_acct);

                if ($s_vcr == false) {
                    sleep(3);
                    echo "> Gagal Claim Voucher\n";
                } else {
                    sleep(3);
                    echo "> Berhasil Claim Voucher Go-Ride\n";
                    sleep(1);
                    echo $s_vcf."\n\n";
                }
            }
        }
    }
} else if ($s_pilih == 1) {
    echo "\n";
    echo "Kamu Memilih Login\n";
    echo "========================================\n";
    echo "INFO :Awali 62 untuk Indo dan 1 untuk US\n";
    echo "========================================\n";
    echo "Masukan Nomer : ";
    $s_hp  = trim(fgets(STDIN));
    $s_tkn = fn_03($s_hp);

    if ($s_tkn == false) {
        echo "\n";
        echo "Gagal Ngambil OTP";
    } else {
        echo "Masukan Kode OTP : ";
        $s_kode = trim(fgets(STDIN));
        $s_acct = fn_04($s_kode,$s_tkn);

        if ($s_acct == false) {
            sleep(1);
            echo "> Gagal Login dengan nomer kmu!\n";
        } else {
            echo "\n";
            sleep(1);
            echo "> Login Berhasil";
            echo "\n";
            sleep(2);
            echo "> Sedang Claim Voucher Go-Food...\n";
            $s_vcf = fn_05($s_acct);

            if ($s_vcf == false) {
                sleep(3);
                echo "> Gagal Claim Voucher\n";
            } else {
                sleep(3);
                echo "> Berhasil Claim Voucher\n";
                sleep(1);
                echo $s_vcf."\n";
            }
        }
    }
}

function fn_07($p_path,$p_auth=null,$p_post=null,$p_pin=null)
{
    $a_hdr[] = "Host: api.gojekapi.com";
    $a_hdr[] = "User-Agent: okhttp/3.10.0";
    $a_hdr[] = "Accept: application/json";
    $a_hdr[] = "Accept-Language: en-ID";
    $a_hdr[] = "Content-Type: application/json; charset=UTF-8";
    $a_hdr[] = "X-AppVersion: 3.30.2";
    $a_hdr[] = "X-UniqueId: ".time()."57".mt_rand(1000,9999);
    $a_hdr[] = "Connection: keep-alive";
    $a_hdr[] = "X-User-Locale: en_ID";

    if ($p_pin) {
        $a_hdr[] = "pin: $p_pin";
    }

    if ($p_auth) {
        $a_hdr[] = "Authorization: Bearer $p_auth";
    }

    $ch = curl_init("https://api.gojekapi.com".$p_path);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

    if ($p_post) {
        curl_setopt($ch,CURLOPT_POSTFIELDS,$p_post);
        curl_setopt($ch,CURLOPT_POST,true);
    }

    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$a_hdr);

    $c_res = curl_exec($ch);
    $c_nfo = curl_getinfo($ch);

    if (!$c_nfo) {
        return false;
    } else {
        $a_hdr = substr($c_res,0,curl_getinfo($ch,CURLINFO_HEADER_SIZE)); // WTF?
        $j_res = substr($c_res,curl_getinfo($ch,CURLINFO_HEADER_SIZE));
        $j_hdr = json_decode($j_res,true);
        return $j_hdr;
}

function fn_save($p_file,$p_data)
{
    $fh = fopen($p_file,"a");
    fputs($fh,"$p_data\r\n");
    fclose($fh);
}
?>
