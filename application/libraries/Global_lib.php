<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Global_lib
{

    var $page_index = 0;
    var $page_list = array();
    var $module_index = 0;
    var $module_list = array();

    function __construct()
    {
        //load CI instance..
        $this->CI = &get_instance();

        //load model
        $this->CI->load->model('mdl_global');
        $this->CI->load->model('mdl_page');
        $this->CI->load->model('mdl_module');

        //construct script..
        date_default_timezone_set("Asia/Jakarta");

        //initialize session and language library..
    }

    public function paginationConfig()
    {
        $config['display_pages'] = FALSE;
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['next_link'] = 'BERIKUTNYA';
        $config['prev_link'] = 'SEBELUMNYA';
        $config['full_tag_open'] = '<nav class="m-t-20"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link btn-info" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link text-muted');
        return $config;
    }

    public function paginationConfigAdmin()
    {
        $config['display_pages'] = TRUE;
        $config['first_link'] = ' &laquo;&laquo; ';
        $config['last_link'] = ' &raquo;&raquo; ';
        $config['next_link'] = 'BERIKUTNYA';
        $config['prev_link'] = 'SEBELUMNYA';
        $config['full_tag_open'] = '<nav class="m-t-20"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link btn-info" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link text-muted');
        return $config;
    }

    public function getModuleDetail($module_name = null)
    {
        $module_name = $module_name ?? $this->CI->uri->segment(1);
        $controller = strtolower($module_name);

        $module = $this->CI->mdl_module->getModuleByControllerName($controller);
        return $module;
    }

    public function generateAdminModule($id_module = '')
    {
        $controller = strtolower($this->CI->uri->segment(1));
        $module_str = "";
        $modules = array();
        $module_badge_counts = [
            'admin_content' => ['mdl_article', 'count_requires_approval'],
            'admin_photo' => ['mdl_photo', 'count_requires_approval'],
            'admin_polling' => ['mdl_poll', 'count_requires_approval'],
            'admin_quiz' => ['mdl_quiz', 'count_requires_approval'],
            'admin_comment' => ['mdl_comment', 'getRequireApprovalCommentCount'],
            'admin_ads' => ['mdl_ads', 'getRequireApprovalAdsCount'],
            'admin_verifiedmember' => ['mdl_verifiedmembersubmission', 'count_requires_approval'],
            'admin_ads_cancel' => ['mdl_ads_cancel', 'count_requires_approval'],
            'admin_contact' => ['mdl_contact', 'getUnreadContactCount'],
        ];

        $modules = $this->CI->load->model('mdl_content2');

        if ($id_module > 0) {
            $modules = $this->CI->mdl_module->getActiveModuleChildArr($id_module);
        }
        else {
            $modules = $this->CI->mdl_module->getAllActiveModuleParentArr();
        }

        if (isset($modules) && is_array($modules)) {
            foreach ($modules as $x => $module) {
                $module_controller = strtolower(explode('/', $module['module_redirect'])[0]);

                $module_child = array();
                $module_has_child = $this->CI->mdl_module->hasChild($module['id_module']);
                $module_child = $this->CI->mdl_module->getActiveModuleChildArr($module['id_module']);

                //tentukan punya akses atau tidak.
                $granted = false;
                if (strpos($this->CI->session->userdata('admin_grant'), $module_controller . '|') !== false || $this->CI->session->userdata('admin_level') == '1') {
                    $granted = true;
                }
                else {
                    if ($module_has_child && count($module_child) > 0) {
                        foreach ($module_child as $y => $child) {
                            $child_controller = strtolower(explode('/', $child['module_redirect'])[0]);
                            if (strpos($this->CI->session->userdata('admin_grant'), $child_controller . '|') !== false || $this->CI->session->userdata('admin_level') == '1') {
                                $granted = true;
                                break;
                            }
                        }
                    }
                }

                if ($granted) {
                    //tentukan state open atau tidak..
                    $opened = false;
                    if ($controller == $module_controller) {
                        $opened = true;
                    }
                    else {
                        if ($module_has_child && count($module_child) > 0) {
                            foreach ($module_child as $y => $child) {
                                if (!$opened) {
                                    $child_controller = strtolower(explode('/', $child['module_redirect'])[0]);
                                    if ($controller == $child_controller) {
                                        $opened = true;
                                        break;
                                    }
                                    //check punya child / tidak (level 2)
                                    if (!$opened) {
                                        $submodule_has_child = $this->CI->mdl_module->hasChild($child['id_module']);
                                        $submodule_child = $this->CI->mdl_module->getActiveModuleChildArr($child['id_module']);
                                        if ($submodule_has_child && count($submodule_child) > 0) {
                                            foreach ($submodule_child as $z => $subchild) {
                                                $subchild_controller = strtolower(explode('/', $subchild['module_redirect'])[0]);
                                                if ($controller == $subchild_controller) {
                                                    $opened = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $module_str .= "<li class='" . ($opened ? "open active " . $module_controller : "") . "' id='" . $controller . " - " . $module_controller . "'>";
                    //cek apakah punya child.
                    if ($module_has_child && count($module_child) > 0) {
                        $module_str .= "<a href='javascript:;'><span class='title'>" . $module['module_name'] . "</span>";
                        $module_str .= "<span class='arrow " . ($opened ? "open active" : "") . "'></span>";
                        $module_str .= "</a>";
                        $module_str .= "<span class='icon-thumbnail " . ($opened && $module['module_parent'] == 0 ? 'bg-complete' : '') . "'>";
                        $module_str .= $this->generateModuleIcon($module['module_icon']);
                        $module_str .= "</span>";
                        $module_str .= "<ul class='sub-menu' " . ($opened ? "style='display:block;'" : "style='display:none;'") . ">";
                        $module_str .= $this->generateAdminModule($module['id_module']);
                        $module_str .= "</ul>";
                    }
                    else {
                        $margin = $module['module_parent'] == 0 ? '1em' : '0.25em';
                        $badge = '';

                        if (isset($module_badge_counts[$module['module_redirect']])) {
                            list($model_name, $method_name) = $module_badge_counts[$module['module_redirect']];
                            $this->CI->load->model($model_name);
                            $model = $this->CI->$model_name;
                            $count = call_user_func([$model, $method_name]);
                            $badge = $count > 0
                                ? "<span class='badge badge-danger badge-pill float-right' style='margin-top: {$margin};padding-top: 4px;'>{$count}</span>"
                                : '';
                        }

                        $module_str .= "<a href='" . base_url() . $module['module_redirect'] . "'>";
                        $module_str .= "<span class='title'>" . $module['module_name'] . "</span>";
                        $module_str .= $badge;
                        $module_str .= "</a>";
                        $module_str .= "<span class='icon-thumbnail " . ($opened && $module['module_parent'] == 0 ? 'bg-complete' : '') . "'>";
                        $module_str .= $this->generateModuleIcon($module['module_icon']);
                        $module_str .= "</span>";
                    }
                    $module_str .= "</li>";
                }
            }
        }

        return $module_str;
    }

    public function getModule($id_module = '')
    {
        $modules = array();
        if ($id_module > 0) {
            $modules = $this->CI->mdl_module->getActiveModuleChildArr($id_module);
        }
        else {
            $modules = $this->CI->mdl_module->getAllActiveModuleParentArr();
        }

        foreach ($modules as $x => $module) {
            //cek apakah punya child.
            if ($this->CI->mdl_module->hasChild($module['id_module'])) {
                $modules[$x]['has_child'] = 1;
                $modules[$x]['child'] = $this->getModule($module['id_module']);
            }
            else {
                $modules[$x]['has_child'] = 0;
            }
        }

        return $modules;
    }

    public function getModuleList()
    {
        //ambil data semua module utama / parent..
        $data['main_module'] = $this->CI->mdl_module->getAllActiveModuleParentArr();

        //ambil semua module child dan urutkan berdasarkan levelnya.
        foreach ($data['main_module'] as $x => $pg) {
            $has_child = $this->CI->mdl_module->hasChild($pg['id_module']);
            $this->module_list[$this->module_index] = $pg;
            $this->module_list[$this->module_index]['has_child'] = ($has_child ? '1' : '0');
            $this->module_index++;

            //cek apakah punya child.
            if ($has_child) {
                $this->getModuleChildList($pg['id_module']);
            }
        }

        foreach ($this->module_list as $y => $pl) {
            // cek apakah module mempunyai parent.
            $modulecheck = $this->CI->mdl_module->getModuleByID($pl['id_module']);
            if (($modulecheck[0]->module_parent != null ? true : false)) {
                $indentation = $this->generateIndentationStr($pl['id_module']);
                $this->module_list[$y]['module_name'] = $indentation . " " . $this->module_list[$y]['module_name'];
            }
        }

        return $this->module_list;
    }

    public function getModuleListWithoutIndentation()
    {
        //ambil data semua module utama / parent..
        $data['main_module'] = $this->CI->mdl_module->getAllActiveModuleParentArr();

        //ambil semua module child dan urutkan berdasarkan levelnya.
        foreach ($data['main_module'] as $x => $pg) {
            $has_child = $this->CI->mdl_module->hasChild($pg['id_module']);
            $this->module_list[$this->module_index] = $pg;
            $this->module_list[$this->module_index]['has_child'] = ($has_child ? '1' : '0');
            $this->module_index++;

            //cek apakah punya child.
            if ($has_child) {
                $this->getModuleChildList($pg['id_module']);
            }
        }

        return $this->module_list;
    }

    public function getIPAddress()
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

    public function generateHash($salt = '')
    {
        $uid = uniqid("", true);
        $txt = "";
        $txt .= isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : '';
        $txt .= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $txt .= isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : '';
        $txt .= isset($_SERVER['LOCAL_PORT']) ? $_SERVER['LOCAL_PORT'] : '';
        $txt .= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $txt .= isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : '';
        $txt .= date("YmdHis");
        $txt .= $salt;
        $hash = hash('ripemd128', $uid . sha1($txt));

        return $hash;
    }

    public function getGlobalData()
    {
        $data = $this->CI->mdl_global->getGlobalData();
        return $data;
    }

    public function getLink($url)
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

    public function generateMessage($message = '', $type = '')
    {
        $message_html = "<div class='alert alert-" . $type . "' role='alert'><button class='close' data-dismiss='alert'></button>";
        $message_html .= $message;
        $message_html .= "</div>";

        return $message_html;
    }

    public function cleanString($string = '')
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return $string;
    }

    public function clearBreadcrumb()
    {
        $this->CI->session->set_userdata('breadcrumb', array());
    }

    public function convertNumber($number)
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
                $groups2[] = $this->convertThreeDigit($g[0], $g[1], $g[2]);
            }

            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != "") {
                    $output .= $groups2[$z] . $this->convertGroup(11 - $z) . (
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
                $output .= " " . $this->convertDigit($fraction[$i]);
            }
        }
        return $output;
    }

    public function validateDate($date, $format)
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function formatDate($date, $original_format, $expected_format)
    {
        $date_formatted = "";
        if ($this->validateDate($date, $original_format)) {
            $date_temp = DateTime::createFromFormat($original_format, $date);
            $date_formatted = $date_temp->format($expected_format);
        }
        return $date_formatted;
    }

    public function insertAdminLog($log = array())
    {
        $log['related_role'] = 'admin';
        $log['role_id'] = $this->CI->session->userdata('id_admin');
        $log['log_date'] = date('Y-m-d H:i:s');
        if (!(isset($log['log_title']) && strlen(trim($log['log_title'])) > 0)) {
            $log['log_title'] = 'Administrator (' . $this->CI->session->userdata('admin_username') . ')';
        }

        //insert log ke database..
        $this->CI->load->model('mdl_log');
        $this->CI->mdl_log->insertLog($log);
    }

    public function doInBackground($url, $params)
    {
        $this->CI->db->insert('tbl_debug', array('content' => 'Global_lib - doInBackground'));

        foreach ($params as $key => &$val) {
            if (is_array($val)) {
                $val = implode(',', $val);
            }
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $parts = parse_url($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        $server_output = curl_exec($ch);
        curl_close($ch);

        $this->CI->db->insert('tbl_debug', array('content' => json_encode($server_output)));
    }

    public function timeElapsedString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

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
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            }
            else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' lalu' : 'just now';
    }

    private function convertGroup($index)
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
            case 0:
                return "";
        }
    }

    private function generateModuleIcon($icon = '')
    {
        $icon_str = "";
        if (strpos($icon, "fa-") !== false) {
            $icon_str = "<i class='fa " . $icon . "'></i>";
        }
        else {
            if (strpos($icon, "pg") !== false) {
                $icon_str = "<i class='" . $icon . "'></i>";
            }
            else {
                $icon_str = substr($icon, 0, 2);
            }
        }

        return $icon_str;
    }

    private function getModuleChildList($id_module = "")
    {
        $data['module_child'] = $this->CI->mdl_module->getActiveModuleChildArr($id_module);
        foreach ($data['module_child'] as $x => $pg) {
            $has_child = $this->CI->mdl_module->hasChild($pg['id_module']);
            $this->module_list[$this->module_index] = $pg;
            $this->module_list[$this->module_index]['has_child'] = ($has_child ? '1' : '0');
            $this->module_index++;

            // cek apakah punya child.
            if ($has_child) {
                $this->getModuleChildList($pg['id_module']);
            }
        }
    }

    private function generateIndentationStr($id_module = "")
    {
        $flag = true;
        $count = 0;
        $id = $id_module;
        $str = "";

        while ($flag) {
            $count++;
            //ambil data detail module lalu cek module parent nya.
            $module = $this->CI->mdl_module->getModuleByID($id);
            if (isset($module[0]->module_parent) && $module[0]->module_parent != null) {
                $id = $module[0]->module_parent;
            }
            else {
                $flag = false;
            }
        }
        for ($x = 1; $x < ($count - 1); $x++) {
            $str .= "---";
        }
        return $str;
    }

    private function convertThreeDigit($digit1, $digit2, $digit3)
    {
        $buffer = "";
        if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
            return "";
        }

        if ($digit1 != "0") {
            $buffer .= $this->convertDigit($digit1) . " Hundred";
            if ($digit2 != "0" || $digit3 != "0") {
                $buffer .= " and ";
            }
        }

        if ($digit2 != "0") {
            $buffer .= $this->convertTwoDigit($digit2, $digit3);
        }
        else {
            if ($digit3 != "0") {
                $buffer .= $this->convertDigit($digit3);
            }
        }

        return $buffer;
    }

    private function convertTwoDigit($digit1, $digit2)
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
                }
            }
            else {
                $temp = $this->convertDigit($digit2);
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
                }
            }
        }
    }

    private function convertDigit($digit)
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
        }
    }

}
