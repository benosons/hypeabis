<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('d')) {
    function d($value)
    {
        print_r("<pre>");
        print_r($value);
        print_r("</pre>");
    }
}

if (!function_exists('dd')) {
    function dd($value)
    {
        print_r("<pre>");
        print_r($value);
        print_r("</pre>");
        die();
    }
}

if (!function_exists('uris')) {
    function uris($segment)
    {
        $CI = &get_instance();
        return $CI->uri->segment($segment);
    }
}

if (!function_exists('setFlashData')) {
    function setFlashData($identifier, $data)
    {
        $CI = &get_instance();
        $CI->session->set_flashdata($identifier, $data);
    }
}

if (!function_exists('setMessage')) {
    function setMessage($message, $type = '')
    {
        $CI = &get_instance();
        $message_html = "<div class='alert alert-" . $type . "' role='alert'><button class='close' data-dismiss='alert'></button>";
        $message_html .= $message;
        $message_html .= "</div>";
        $CI->session->set_flashdata('message', $message_html);
    }
}

if (!function_exists('checkRole')) {
    function checkRole()
    {
        $CI = &get_instance();

        //check login
        if ($CI->session->userdata('is_logged_in') !== true) {
            redirect("Adminarea/index");
        }
        //check access module
        $controller = strtolower(trim($CI->uri->segment(1)));
        $privileges = $CI->session->userdata('account_grant');
        $is_granted = $CI->session->userdata('account_level') == '1';
        if (!$is_granted) {
            if (isset($privileges[$controller]['redirect']) && strtolower(trim($privileges[$controller]['redirect'])) == $controller) {
                $is_granted = true;
            }
        }
        if (!$is_granted) {
            redirect("Adminarea/accessViolated");
        }
    }
}

if (!function_exists('checkPermission')) {
    function checkPermission($function)
    {
        $is_granted = can($function);
        if (!$is_granted) {
            redirect("Adminarea/accessViolated");
        }
    }
}

if (!function_exists('can')) {
    function can($function)
    {
        $CI = &get_instance();
        $controller = strtolower(trim($CI->uri->segment(1)));
        $privileges = $CI->session->userdata('account_grant');
        $is_granted = $CI->session->userdata('account_level') == '1';
        if (!$is_granted) {
            if (isset($privileges[$controller]['function']) && is_array($privileges[$controller]['function'])) {
                foreach ($privileges[$controller]['function'] as $granted_function) {
                    if (strtolower(trim($function)) == strtolower(trim($granted_function))) {
                        $is_granted = true;
                        break;
                    }
                }
            }
        }
        return $is_granted;
    }
}

if (!function_exists('validateDate')) {
    function validateDate($date, $format)
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $original_format, $expected_format)
    {
        $date_formatted = "";
        if (validateDate($date, $original_format)) {
            $date_temp = DateTime::createFromFormat($original_format, $date);
            $date_formatted = $date_temp->format($expected_format);
        }
        return $date_formatted;
    }
}

if (!function_exists('insertLog')) {
    function insertLog($function = null, $log_string = null, $data = null)
    {
        $CI = &get_instance();
        $CI->loglib->insertLog($function, $log_string, $data);
    }
}

if (!function_exists('insertLogUser')) {
    function insertLogUser($function = null, $log_string = null, $data = null)
    {
        $CI = &get_instance();
        $CI->loglib->insertLogUser($function, $log_string, $data);
    }
}

if (!function_exists('getGlobalData')) {
    function getGlobalData()
    {
        $CI = &get_instance();
        return $CI->globalsetting->get();
    }
}

if (!function_exists('getModuleDetail')) {
    function getModuleDetail()
    {
        $CI = &get_instance();
        return $CI->globallib->getModuleDetail();
    }
}

if (!function_exists('generateModule')) {
    function generateModule($id_module = '')
    {
        $CI = &get_instance();
        return $CI->globallib->generateModule($id_module);
    }
}

if (!function_exists('getIPAddress')) {
    function getIPAddress()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        }
        elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        }
        else {
            $ip = $remote;
        }
        return $ip;
    }
}

if (!function_exists('generateHash')) {
    function generateHash($salt = '')
    {
        $uid = uniqid("", true);
        $txt = "";
        $txt .= $_SERVER['REQUEST_TIME'] ?? '';
        $txt .= $_SERVER['HTTP_USER_AGENT'] ?? '';
        $txt .= $_SERVER['LOCAL_ADDR'] ?? '';
        $txt .= $_SERVER['LOCAL_PORT'] ?? '';
        $txt .= $_SERVER['REMOTE_ADDR'] ?? '';
        $txt .= $_SERVER['REMOTE_PORT'] ?? '';
        $txt .= date("YmdHis");
        $txt .= $salt;
        return hash('ripemd128', $uid . sha1($txt));
    }
}

if (!function_exists('getLink')) {
    function getLink($url)
    {
        if (strpos(strtolower($url), 'http://') !== false || strpos(strtolower($url), 'https://') !== false) {
            $redirect_url = $url;
        }
        else {
            if (strpos(strtolower($url), 'www.') !== false) {
                $redirect_url = 'http://' . $url;
            }
            else {
                $redirect_url = base_url() . $url;
            }
        }
        return $redirect_url;
    }
}

if (!function_exists('getEncodedUrl')) {
    function getEncodedUrl()
    {
        $CI = &get_instance();
        $url = $CI->uri->uri_string();
        $url = ($_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url);
        return rtrim(base64_encode(urlencode($url)), "=");
    }
}

if (!function_exists('encodeUrl')) {
    function encodeUrl($url)
    {
        if (isset($url) && strlen(trim($url)) > 0) {
            return rtrim(base64_encode(urlencode($url)), "=");
        }
        else {
            return '';
        }
    }
}

if (!function_exists('decodeUrl')) {
    function decodeUrl($encoded_url)
    {
        return urldecode(base64_decode($encoded_url));
    }
}

if (!function_exists('getInputDate')) {
    function getInputDate($input, $format)
    {
        $CI = &get_instance();
        $result = null;
        $input_value = $CI->input->post($input);
        $is_valid_date = validateDate($input_value, $format);
        if ($is_valid_date) {
            $result = formatDate($input_value, $format, 'Y-m-d');
        }
        return $result;
    }
}

if (!function_exists('getInputRichText')) {
    function getInputRichText($input)
    {
        $CI = &get_instance();
        return str_replace(base_url() . "assets/content/", "##BASE_URL##", $CI->input->post($input));
    }
}

if (!function_exists('printRichText')) {
    function printRichText($data)
    {
        return str_replace("##BASE_URL##", base_url() . "assets/content/", html_entity_decode($data));
    }
}

if (!function_exists('validateData')) {
    function validateData($rules)
    {
        $base_validation = 'htmlentities|strip_tags|trim|xss_clean';
        $CI = &get_instance();
        $CI->form_validation->set_rules(array_merge(
            array_map(function ($rule) use ($base_validation) {
                return [
                    'field' => $rule[0],
                    'rules' => $base_validation . (isset($rule[1]) ? '|' . $rule[1] : ''),
                    'label' => $rule[2] ?? ''
                ];
            }, $rules)
        ));
        return $CI->form_validation->run();
    }
}

if (!function_exists('cleanString')) {
    function cleanString($string = '')
    {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}

if (!function_exists('convertNumber')) {
    function convertNumber($number)
    {
        list($integer, $fraction) = explode(".", (string)$number);
        $output = "";
        if ($integer[0] == "-") {
            $output = "negative ";
            $integer = ltrim($integer, "-");
        }
        else {
            if ($integer[0] == "+") {
                $output = "positive ";
                $integer = ltrim($integer, "+");
            }
        }
        if ($integer[0] == "0") {
            $output .= "zero";
        }
        else {
            $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
            $group = rtrim(chunk_split($integer, 3, " "), " ");
            $groups = explode(" ", $group);

            $groups2 = array();
            foreach ($groups as $g) {
                $groups2[] = convertThreeDigit($g[0], $g[1], $g[2]);
            }

            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != "") {
                    $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11][0] == '0'
                            ? " and "
                            : ", "
                        );
                }
            }
            $output = rtrim($output, ", ");
        }

        if ($fraction > 0) {
            $output .= " point";
            for ($i = 0; $i < strlen($fraction); $i++) {
                $output .= " " . convertDigit($fraction[$i]);
            }
        }
        return $output;
    }
}

if (!function_exists('convertGroup')) {
    function convertGroup($index)
    {
        switch ($index) {
            case 11:
                return " Decillion";
            case 10:
                return " Nonillion";
            case 9:
                return " Octillion";
            case 8:
                return " Septillion";
            case 7:
                return " Sextillion";
            case 6:
                return " Quintrillion";
            case 5:
                return " Quadrillion";
            case 4:
                return " Trillion";
            case 3:
                return " Billion";
            case 2:
                return " Million";
            case 1:
                return " Thousand";
            default:
                return "";
        }
    }
}

if (!function_exists('convertThreeDigit')) {
    function convertThreeDigit($digit1, $digit2, $digit3)
    {
        $buffer = "";
        if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
            return "";
        }

        if ($digit1 != "0") {
            $buffer .= convertDigit($digit1) . " Hundred";
            if ($digit2 != "0" || $digit3 != "0") {
                $buffer .= " and ";
            }
        }

        if ($digit2 != "0") {
            $buffer .= convertTwoDigit($digit2, $digit3);
        }
        else {
            if ($digit3 != "0") {
                $buffer .= convertDigit($digit3);
            }
        }

        return $buffer;
    }
}

if (!function_exists('convertTwoDigit')) {
    function convertTwoDigit($digit1, $digit2)
    {
        if ($digit2 == "0") {
            switch ($digit1) {
                case "1":
                    return "Ten";
                case "2":
                    return "Twenty";
                case "3":
                    return "Thirty";
                case "4":
                    return "Forty";
                case "5":
                    return "Fifty";
                case "6":
                    return "Sixty";
                case "7":
                    return "Seventy";
                case "8":
                    return "Eighty";
                case "9":
                    return "Ninety";
                default:
                    return "";
            }
        }
        else {
            if ($digit1 == "1") {
                switch ($digit2) {
                    case "1":
                        return "Eleven";
                    case "2":
                        return "Twelve";
                    case "3":
                        return "Thirteen";
                    case "4":
                        return "Fourteen";
                    case "5":
                        return "Fifteen";
                    case "6":
                        return "Sixteen";
                    case "7":
                        return "Seventeen";
                    case "8":
                        return "Eighteen";
                    case "9":
                        return "Nineteen";
                    default:
                        return "";
                }
            }
            else {
                $temp = convertDigit($digit2);
                switch ($digit1) {
                    case "2":
                        return "Twenty-$temp";
                    case "3":
                        return "Thirty-$temp";
                    case "4":
                        return "Forty-$temp";
                    case "5":
                        return "Fifty-$temp";
                    case "6":
                        return "Sixty-$temp";
                    case "7":
                        return "Seventy-$temp";
                    case "8":
                        return "Eighty-$temp";
                    case "9":
                        return "Ninety-$temp";
                    default:
                        return "";
                }
            }
        }
    }
}

if (!function_exists('convertDigit')) {
    function convertDigit($digit)
    {
        switch ($digit) {
            case "0":
                return "Zero";
            case "1":
                return "One";
            case "2":
                return "Two";
            case "3":
                return "Three";
            case "4":
                return "Four";
            case "5":
                return "Five";
            case "6":
                return "Six";
            case "7":
                return "Seven";
            case "8":
                return "Eight";
            case "9":
                return "Nine";
            default:
                return "";
        }
    }
}