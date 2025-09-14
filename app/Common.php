<?php

if (!defined('USD_VALUE')) {
    define('USD_VALUE', 90);
}


if (!function_exists('resJson')) {
    function resJson(array $data, int $statusCode = 200): \CodeIgniter\HTTP\ResponseInterface
    {
        return response()->setJSON($data, true)->setStatusCode($statusCode);
    }
}
if (!function_exists('escape')) {
    function escape(int|string $text): null|int|string
    {
        if (empty($text))
            return '';

        return htmlspecialchars($text);
    }
}

if (!function_exists('route')) {
    function route(string $routeName, ...$params): string
    {
        static $routeService = null;
        if (!$routeService)
            $routeService = \App\Services\RouteService::getInstance();

        return $routeService->route($routeName, ...$params);
    }
}

if (!function_exists('a_percent_of_b')) {
    function a_percent_of_b(string|float $a, string|float $b, int $scale = 8): string|float
    {
        // a % of b
        // 5% of 100 -> 5
        return bcmul($b, bcdiv($a, 100, $scale), $scale);
    }
}



if (!function_exists('isProduction')) {
    function isProduction(): bool
    {
        return ENVIRONMENT == 'production';
    }
}

if (!function_exists('data')) {
    function data(string $key, mixed $default = null)
    {
        static $arr = [];

        if (!isset($arr[$key])) {
            $res = \App\Twebsol\Data::getData(key: $key, default: $default);
            $arr[$key] = is_string($res) ? escape($res) : $res;
        }

        return $arr[$key];
    }
}

if (!function_exists('label')) {
    function label(string $key, int $type = 0): string
    {
        static $arr = [];

        $_key = "$key$type";

        if (!isset($arr[$_key])) {
            $label = \App\Twebsol\Labels::getLabel($key);

            if ($type == 1) // lowercase
                $label = strtolower($label);
            else if ($type == 2) // uppercase
                $label = strtoupper($label);
            else if ($type == 3) // for url
                $label = str_replace(' ', '-', strtolower($label));

            $arr[$_key] = $label;
        }

        return $arr[$_key];
    }
}
if (!function_exists('wallet_label')) {
    function wallet_label(string $wallet_field, int $type = 0): string
    {
        static $arr = [];
        $_key = "$wallet_field$type";
        if (!isset($arr[$_key])) {
            $label = \App\Twebsol\Labels::$walletLabels[$wallet_field];
            if ($type == 1) // lowercase
                $label = strtolower($label);
            else if ($type == 2) // uppercase
                $label = strtoupper($label);
            else if ($type == 3) // for url
                $label = str_replace(' ', '-', strtolower($label));
            $arr[$_key] = $label;
        }
        return $arr[$_key];
    }
}

if (!function_exists('_setting')) {
    function _setting(string $key, $default = null)
    {
        static $arr = [];

        if (!isset($arr[$key]))
            return $arr[$key] = \App\Twebsol\Settings::get_setting($key, $default);

        return $arr[$key];
    }
}
if (!function_exists('croute')) {
    function croute(): string
    {
        static $croute = null;
        if (!$croute)
            $croute = service('uri')->getRoutePath();
        return $croute;
    }
}

if (!function_exists('admin_component')) {
    function admin_component(string $name, array $data = []): string
    {
        return view("admin_components/$name", $data, [
            'saveData' => false
        ]);
    }
}
if (!function_exists('user_component')) {
    function user_component(string $name, array $data = []): string
    {
        return view("user_components/$name", $data, [
            'saveData' => false
        ]);
    }
}


if (!function_exists('inputPost')) {
    function inputPost(string $inputName, bool $null_if_empty = false)
    {
        static $request = null;

        if (!$request)
            $request = request();

        $input = $request->getPost($inputName);
        if (!empty($input) && !is_array($input)) {
            $input = trim($input);
        }

        if ($null_if_empty and empty($input))
            return null;

        return $input;
    }
}
if (!function_exists('inputGet')) {
    function inputGet(string $inputName, bool $null_if_empty = false)
    {
        static $request = null;

        if (!$request)
            $request = request();

        $input = $request->getGet($inputName);
        if (!empty($input) && !is_array($input)) {
            $input = trim($input);
        }

        if ($null_if_empty and empty($input))
            return null;

        return $input;
    }
}



if (!function_exists('server_error_ajax')) {
    function server_error_ajax(\Exception $e)
    {
        if (isProduction())
            return resJson(['success' => false], 500);

        throw $e;
    }
}

if (!function_exists('user_asset')) {
    function user_asset(string $asset = ''): string
    {
        return base_url("assets/$asset");
    }
}

if (!function_exists('admin_asset')) {
    function admin_asset(string $asset): string
    {
        return base_url("xassets/$asset");
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $rules, array $messages = []): array
    {
        $validator = \Config\Services::validation();

        $validator->setRules($rules, $messages)->run($data);

        return $validator->getErrors();
    }
}

if (!function_exists('user_model')) {
    function user_model(bool $static = false): \App\Models\UserModel
    {
        if ($static) {
            static $um = null;
            if (!$um)
                $um = new \App\Models\UserModel;
            return $um;
        }

        return new \App\Models\UserModel;
    }
}

if (!function_exists('hash_password')) {
    function hash_password(string $plain_password): string
    {
        return password_hash($plain_password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('db')) {
    function db(bool $static = false): \CodeIgniter\Database\BaseConnection
    {
        static $db = null;
        if (!$db)
            $db = \Config\Database::connect();
        return $db;
    }
}

if (!function_exists('transaction')) {
    // db() must be decalare before it
    function transaction(Closure $closure)
    {
        db()->transBegin();

        try {

            $closure();

            db()->transCommit();
        } catch (\Exception $e) {

            db()->transRollback();

            throw $e;
        }
    }
}

if (!function_exists('admin')) {
    function admin(string $prop = null): \stdClass|string|null
    {
        static $admin = null;

        if (!$admin) {
            $admin = \Config\Services::session()->get('admin');
        }

        return $prop ? $admin->{$prop} : $admin;
    }
}
if (!function_exists('user')) {
    function user(string $prop = null): \stdClass|string|int|null
    {
        static $user = null;

        if (!$user)
            $user = \Config\Services::session()->get('user');

        return $prop ? $user->{$prop} : $user;
    }
}

if (!function_exists('is_current_url')) {
    function is_current_url(string $url): bool
    {
        return current_url() === $url;
    }
}


if (!function_exists('load_helper_if_not_function')) {
    function load_helper_if_not_function(string $helper, string $function)
    {
        if (!function_exists($function))
            helper($helper);
    }
}

if (!function_exists('prefixed_cookie')) {
    function prefixed_cookie(string $cookieName): string|null
    {
        $prefix = config('Cookie')->prefix ?? '';
        return $_COOKIE[$prefix . $cookieName] ?? null;
    }
}

if (!function_exists('send_email')) {
    function send_email(string $toEmail, string $subject = '', string $message = '', bool $html = true): bool
    {
        $email = \Config\Services::email();

        $sent = $email->setTo($toEmail)
            ->setSubject($subject)
            ->setMailType($html ? 'html' : 'text')
            ->setMessage($message)
            ->send();

        // Send the email
        if (!$sent) {
            if (!isProduction()) {
                $error = $email->printDebugger(['headers']);
                echo 'Email sending failed. Error: ' . $error;
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('show_404')) {
    function show_404(string $message = null)
    {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($message);
    }
}
if (!function_exists('ajax_404_response')) {
    function ajax_404_response(string $message = 'Resource Not Found!')
    {
        return resJson(['message' => $message], 404);
    }
}

if (!function_exists('array_has_non_null_values')) {
    function array_has_non_null_values(array $array): bool
    {
        foreach ($array as $value) {
            if ($value !== null) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('format_array_of_object')) {
    function format_array_of_object(array &$arrayOfObjects, array|string $properties, string $formatFunction)
    {
        if (!function_exists($formatFunction))
            throw new Exception("Format Function $formatFunction is not defined.");

        if (is_string($properties))
            $properties = [$properties];

        foreach ($arrayOfObjects as &$el)
            foreach ($properties as &$prop)
                $el->$prop = $el->$prop ? $formatFunction($el->$prop) : '';
    }
}

if (!function_exists('uploaded_file_url')) {
    function uploaded_file_url(string $filename = ''): string
    {
        return base_url("uploads/$filename");
    }
}

if (!function_exists('memory')) {
    function memory(string $key, $value = null)
    {
        static $memory = [];

        if ($value) {
            $memory[$key] = $value;
            return $value;
        }

        return $memory[$key] ?? null;
    }
}

if (!function_exists('f_date')) {
    function f_date(string|int $dateTime, string $format = 'jS M Y, h:i A'): string
    {
        if (is_int($dateTime))
            $fdate = date($format, $dateTime);
        else
            $fdate = date($format, strtotime($dateTime));

        return $fdate;
    }
}

if (!function_exists('f_amount')) {
    function short_amount($num)
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $num >= 1000; $i++) {
            $num /= 1000;
        }
        return round($num, 2) . $units[$i];
    }
    function f_amount(string $amount, bool $shortForm = false, bool $both = false, bool $isUser = false, string $symbol = '$'): string|array
    {
        // requires bcmath extension
        $max = 3;

        $number = bcmul($amount, '1', $max);

        // Remove trailing zeros and the decimal point
        $number = rtrim($number, '0');

        // appending 2 zeroes if all zeroes are trimmed
        if (substr($number, -1) === '.')
            $number .= '00';

        $shortNum = null;

        // $shortNum = ($shortForm or $both) ? short_amount($number) : null;

        // if ($both)
        // return ["₹$shortNum", "₹$number"];

        if ($symbol === '$') {
            return $shortNum ? "{$symbol}{$shortNum}" : "{$symbol}{$number}";
        }


        return $shortNum ? "{$shortNum} {$symbol}" : "{$number} {$symbol}";
    }
}


if (!function_exists('pager_init_serial_number')) {
    function pager_init_serial_number(\CodeIgniter\Pager\Pager &$pager): int
    {
        return ($pager->getCurrentPage() - 1) * $pager->getPerPage();
    }
}


if (!function_exists('get_user')) {
    function get_user(int|string $user_id, array|string $columns = '*', bool $is_user_id_pk = true): object|null
    {
        return $is_user_id_pk ?
            user_model(static: true)->getUserFromUserIdPk(user_id_pk: $user_id, columns: $columns) :
            user_model(static: true)->getUserFromUserId(user_id: $user_id, columns: $columns);
    }
}

if (!function_exists('get_user_name')) {
    function get_user_name(int|string $user_id, bool $is_user_id_pk = true): string|null
    {
        return $is_user_id_pk ?
            user_model(static: true)->getUserFullNameFromUserIdPk(user_id_pk: $user_id) :
            user_model(static: true)->getUserFullNameFromUserId(user_id: $user_id);
    }
}

if (!function_exists('qr_url')) {
    function qr_url(string $data): string
    {
        $url = route('tools.qrcode');
        return "$url?data=$data";
    }
}



if (!function_exists('admin_role')) {
    function admin_role(int $role): bool
    {
        $admin = admin();
        return $admin and $admin->role and ($admin->role == $role);
    }
}

if (!function_exists('is_root_user')) {
    function is_root_user(string $user_id_pk): bool
    {
        return $user_id_pk == 57;
    }
}

if (!function_exists('is_user_usd')) {
    function is_user_usd(): bool
    {
        static $userIsUsd = null;
        if (is_null($userIsUsd)) {
            $userIsUsd = !!(user()?->is_usd ?? false);
        }
        return $userIsUsd;
    }
}

if (!function_exists('_c')) {
    function _c(string|int $amount): string|int
    {
        return $amount;

        // return is_user_usd() ? bcdiv($amount, USD_VALUE, 8) : $amount;
    }
}

if (!function_exists('_cm')) {
    function _cm(string|int $amount): string|int
    {
        return $amount;
        // return is_user_usd() ? bcmul($amount, USD_VALUE, 8) : $amount;
    }
}

if (!function_exists('wallet_famount')) {
    function wallet_famount(string $amount, ?string $wallet = null)
    {
        // if (in_array($wallet, ['fund'])) {
        //     return f_amount($amount, symbol: '$');
        // }

        return f_amount($amount);
    }
}

if (!function_exists('simple_encrypt_array')) {
    function simple_encrypt_array(array $data): string
    {
        return base64_encode(json_encode($data));
    }
}

if (!function_exists('simple_decrypt_array')) {
    function simple_decrypt_array(string $encoded): array
    {
        $json = base64_decode($encoded);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}

if (!function_exists('is_email')) {
    function is_email(string $string): bool
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('addIncomeStat')) {
    function addIncomeStat(int $user_id_pk, string|float $amount, string $stat_field)
    {
        model(\App\Models\IncomeStatModel::class)->add(
            $user_id_pk,
            $amount,
            $stat_field
        );
    }
}