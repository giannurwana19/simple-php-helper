<?php

/**
 * fungsi untuk membatasi limit karakter
 *
 * @param [type] $content
 * @param integer $length panjang kata
 * @param string $more pemisah
 * @return string
 */
function force_excerpt($content, $length = 40, $more = '...')
{
    $excerpt = strip_tags(trim($content));
    $words   = str_word_count($excerpt, 2);
    if (count($words) > $length) {
        $words = array_slice($words, 0, $length, true);
        end($words);
        $position = key($words) + strlen(current($words));
        $excerpt  = substr($excerpt, 0, $position) . $more;
    }
    return $excerpt;
}

/**
 * fungsi untuk membuat random string
 *
 * @param	string	type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
 * @param	int	number of characters
 * @return	string
 */
function random_string($type = 'alnum', $len = 8)
{
    switch ($type) {
        case 'basic':
            return mt_rand();
        case 'alnum':
        case 'numeric':
        case 'nozero':
        case 'alpha':
            switch ($type) {
                case 'alpha':
                    $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'alnum':
                    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric':
                    $pool = '0123456789';
                    break;
                case 'nozero':
                    $pool = '123456789';
                    break;
            }
            return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
        case 'md5':
            return md5(uniqid(mt_rand()));
        case 'sha1':
            return sha1(uniqid(mt_rand(), TRUE));
    }
}

/**
 * sample function untuk get data url
 *
 * @param [type] $id_hash
 * @param [type] $judul
 * @param [type] $message
 * @return void
 */
function send_notifikasi_pengunjung($id_hash, $judul, $message)
{
    $useragent = "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0";
    $url       = 'http://localhost';
    $curl      = curl_init();
    $fields    = array(
        'id_hash' => $id_hash,
        'judul'   => $judul,
        'isi'     => $message,
    );
    $postfields = http_build_query($fields);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
    curl_setopt($curl, CURLOPT_USERPWD, 'username' . ':' . 'password');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);  //Getting jSON result string
    curl_close($curl);
}

/**
 * fungsi untuk generate waktu saat ini
 * contoh: 2022-03-28 08: 54: 00
 *
 * @return DateTime
 */
function current_timestamp()
{
    return date("Y-m-d H:i:s");
}

/**
 * fungsi untuk mengembalikan response json
 *
 * @param array $response
 * @param integer $status_code
 * @param string $content_type
 * @return string json
 */
function response_json($response = [], $status_code = 200)
{
    http_response_code($status_code);
    header("Content-Type: application/json");
    echo json_encode($response);
}

/**
 * fungsi untuk format angka
 *
 * @param integer $angka
 * @return integer
 */
function format_angka(int $angka = 0)
{
    return number_format($angka, 0, ',', '.');
}

/**
 * fungsi untuk format waktu readable / mudah dibaca
 * contoh: 1 hari yang lalu, baru saja, 1 tahun yang lalu
 *
 * @param string|DateTime $time
 * @return string
 */
function time_ago($datetime, $full = false)
{
    $now  = new DateTime;
    $then = new DateTime($datetime);
    $diff = (array) $now->diff($then);

    $diff['w']  = floor($diff['d'] / 7);
    $diff['d'] -= $diff['w'] * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );

    foreach ($string as $k => &$v) {
        if ($diff[$k]) {
            $v = $diff[$k] . ' ' . $v . ($diff[$k] > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}

/**
 * fungsi untuk konversi romawi menjadi angka
 * contoh: V = 5
 *
 * @param string $romawi
 * @return string
 */
function romawi_to_number(string $romawi)
{
    $table  = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
    $result = 0;
    foreach ($table as $key => $value) {
        while (strpos($romawi, $key) === 0) {
            $result += $value;
            $romawi  = substr($romawi, strlen($key));
        }
    }
    return $result;
}

/**
 * fungis untuk konversi angka menjadi romawi
 * contoh: 5 = V
 *
 * @param integer $integer
 * @return string
 */
function number_to_romawi(int $integer)
{
    $table  = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
    $return = '';
    while ($integer > 0) {
        foreach ($table as $rom => $arb) {
            if ($integer >= $arb) {
                $integer -= $arb;
                $return  .= $rom;
                break;
            }
        }
    }

    return $return;
}

/**
 * fungsi date bahasa indonesia
 *
 * @param string|DateTime  $format
 * @param boolean $time
 * @return string
 */
function waktu_indo($format, $time = false)
{
    $day    = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $days   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $month  = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    if (!is_a($time, 'DateTime')) {
        if (is_int($time)) {
            $time = new DateTime(date('Y-m-d H:i:s.u', $time));
        } elseif (is_string($time)) {
            try {
                $time = new DateTime($time);
            } catch (Exception $e) {
                $time = new DateTime();
            }
        } else {
            $time = new DateTime();
        }
    }

    $ret = '';
    for ($i = 0; $i < strlen($format); $i++) {
        switch ($format[$i]) {
            case 'D':
                $ret .= $day[$time->format('w')];
                break;
            case 'l':
                $ret .= $days[$time->format('w')];
                break;
            case 'M':
                $ret .= $month[$time->format('n')];
                break;
            case 'F':
                $ret .= $months[$time->format('n')];
                break;
            case '\\':
                $ret .= $format[$i + 1];
                $i++;
                break;
            default:
                $ret .= $time->format($format[$i]);
                break;
        }
    }

    return $ret;
}

/**
 * helper function curl php
 *
 * @param string $url
 * @param string $method GET, POST, PUT, PATCH, DELETE
 * @param array $data
 * @param array $headers
 * @return json
 */
function fetch_curl($url, $method = 'GET', $data = null, $headers = array())
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($curl, CURLOPT_POST, true);
            break;
        case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            break;
        case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        default:
            curl_setopt($curl, CURLOPT_HTTPGET, true);
    }

    if ($data) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return $error;
    }

    curl_close($curl);

    return $response;
}
