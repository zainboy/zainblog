<?php
/**
 * user functions
 * User: zain
 * Date: 2017/1/17
 * Time: 11:02
 */

/**
 *
 * @param $size
 * @return bool|string
 */
function readableFilesize($size) {
    if($size < 0) {
        return false;
    }
    $mod = 1024;
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }
    return number_format(round($size, 2),2 ).' '.$units[$i];
}

/**
 * Get client IP address
 * @return mixed
 */
function get_client_ip() {
    $ip = '';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
    }
    return $ip;
}

function get($name = null) {
    if(is_null($name)) {
        return $_GET;
    }
    return isset($_GET[$name]) ? trim($_GET[$name]) : false;
}

function post($name = null) {
    if(is_null($name)) {
        return $_POST;
    }
    return isset($_POST[$name]) ? $_POST[$name] : false;
}

if(!function_exists('comments_children')) {
    function comments_children($children)
    {
        $html = '<div class="children" id="comment_'.$children->id.'" style="margin-left: '.(($children->floor+1)*10).'px;"><div class="row"><div class="pull-left"><img src="/img/avatar_s.png" alt=""></div>';
        $html .= '<div class="pull-left">';
        $html .= '<h5><strong>'.$children->nickname.'</strong><a href="javascript:void(0)" onclick="showReply('.$children->id.')">回复</a></h5>';
        $html .= '<h5>'.$children->create_time.'</h5>';
        $html .= '<p>@'.$children->at.': '.$children->comment.'</p>';
        $html .= '</div></div></div>';
        echo $html;
        if(isset($children->children)) {
            comments_children($children->children);
        }
    }
}