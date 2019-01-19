<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 16:44
 */

if(!function_exists('zDB')) {
    function zDB()
    {
        return \Illuminate\Database\Capsule\Manager::connection();
    }
}

if(!function_exists('zTable')) {
    function zTable($table)
    {
        return \Illuminate\Database\Capsule\Manager::table($table);
    }
}

if (!function_exists('zView')) {
    /**
     * @param $view
     * @param array $data
     */
    function zView($view='errors.404',$data=[])
    {
        $blade = \duncan3dc\Laravel\Blade::getInstance();
        echo $blade->render($view,$data);exit;
    }
}

if (!function_exists('zViewShare')) {
    /**
     * @param $view
     * @param array $data
     */
    function zViewShare($key,$value=null)
    {
        return \duncan3dc\Laravel\Blade::getInstance()->share($key,$value);
    }
}

if (!function_exists('zLogs')) {
    /**
     * @param $message
     * @param array $array
     * @param string $level
     * @return mixed
     */
    function zLogs($message, $array = [], $level = 'info')
    {
        $name = date('Y-m-d');
        if ($level === 'critical' || $level === 'info') {
            $name = $level;
        }
        $log = new \Monolog\Logger('zainphp');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(BASE_PATH . '/storage/logs/' . $name . '.log'));
        return $log->$level($message, $array);
    }
}

if(!function_exists('redirectTo')) {
    function redirectTo($url='/') {
        header('Location:'.$url);
    }
}

if (!function_exists('zCsrf')) {
    function zCsrf()
    {
        \Athens\CSRF\CSRF::init();
    }
}

if (!function_exists('zErrorHandler')) {
    /**
     * Log the error
     * @param int $err_no
     * @param string $err_msg
     * @param string $err_file
     * @param int $err_line
     */
    function zErrorHandler($err_no = 0, $err_msg = '', $err_file = '', $err_line = 0)
    {
        if ($err_no) {
            $level = 'error';
            switch ($err_no) {
                case E_NOTICE:
                    $level = 'notice';
                    break;
                case E_WARNING:
                    $level = 'warning';
                    break;
            }
            $errorInfo = array(
                'type' => $err_no,
                'message' => $err_msg,
                'file' => $err_file,
                'line' => $err_line
            );
            zLogs('', $errorInfo, $level);
        }
    }
}

if (!function_exists('zExceptionHandler')) {
    /**
     * Log the fatal error
     */
    function zExceptionHandler($e)
    {

        if ($e) {
            $errorInfo = array(
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            );
            zLogs('exception:', $errorInfo, 'critical');
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
            {
                zView('errors.404');
            }
        }
    }
}

if (!function_exists('zFatalErrorHandler')) {
    /**
     * Log the fatal error
     */
    function zFatalErrorHandler()
    {
        $e = error_get_last();
        if ($e) {
            zLogs('', $e, 'critical');
        }
    }
}

if (!function_exists('zSession')) {
    /**
     * Session管理
     * @param string|array  $name session名称，如果为数组表示进行session设置
     * @param mixed         $value session值
     * @param string        $prefix 前缀
     * @return mixed
     */
    function zSession($name, $value = '', $prefix = null)
    {
        if (is_array($name)) {
            // 初始化
            \Zain\Session::init($name);
        } elseif (is_null($name)) {
            // 清除
            \Zain\Session::clear('' === $value ? null : $value);
        } elseif ('' === $value) {
            // 判断或获取
            return 0 === strpos($name, '?') ? \Zain\Session::has(substr($name, 1), $prefix) : \Zain\Session::get($name, $prefix);
        } elseif (is_null($value)) {
            // 删除
            return \Zain\Session::delete($name, $prefix);
        } else {
            // 设置
            return \Zain\Session::set($name, $value, $prefix);
        }
    }
}

if (!function_exists('zCookie')) {
    /**
     * Cookie管理
     * @param string|array  $name cookie名称，如果为数组表示进行cookie设置
     * @param mixed         $value cookie值
     * @param mixed         $option 参数
     * @return mixed
     */
    function zCookie($name, $value = '', $option = null)
    {
        if (is_array($name)) {
            // 初始化
            \Zain\Cookie::init($name);
        } elseif (is_null($name)) {
            // 清除
            \Zain\Cookie::clear($value);
        } elseif ('' === $value) {
            // 获取
            return 0 === strpos($name, '?') ? \Zain\Cookie::has(substr($name, 1), $option) : \Zain\Cookie::get($name);
        } elseif (is_null($value)) {
            // 删除
            return \Zain\Cookie::delete($name);
        } else {
            // 设置
            return \Zain\Cookie::set($name, $value, $option);
        }
    }
}

if (!function_exists('zCache')) {
    /**
     * 缓存管理
     * @param mixed     $name 缓存名称，如果为数组表示进行缓存设置
     * @param mixed     $value 缓存值
     * @param mixed     $options 缓存参数
     * @param string    $tag 缓存标签
     * @return mixed
     */
    function zCache($name, $value = '', $options = null, $tag = null)
    {
        if (is_array($options)) {
            // 缓存操作的同时初始化
            \Zain\Cache::connect($options);
        } elseif (is_array($name)) {
            // 缓存初始化
            return \Zain\Cache::connect($name);
        }
        if ('' === $value) {
            // 获取缓存
            return 0 === strpos($name, '?') ? \Zain\Cache::has(substr($name, 1)) : \Zain\Cache::get($name);
        } elseif (is_null($value)) {
            // 删除缓存
            return \Zain\Cache::rm($name);
        } else {
            // 缓存数据
            if (is_array($options)) {
                $expire = isset($options['expire']) ? $options['expire'] : null; //修复查询缓存无法设置过期时间
            } else {
                $expire = is_numeric($options) ? $options : null; //默认快捷缓存设置过期时间
            }
            if (is_null($tag)) {
                return \Zain\Cache::set($name, $value, $expire);
            } else {
                return \Zain\Cache::tag($tag)->set($name, $value, $expire);
            }
        }
    }
}