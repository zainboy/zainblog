<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */
use Lcobucci\JWT;
class LoginController extends Controller
{

    public function index()
    {
        if($this->validateToken()) {
            redirectTo('/admin');
        } else {
            zCookie('token',null);
            zCsrf();
            zView('admin.login');
        }
    }

    public function login() {
        zCsrf();
        $username = post('username');
        $password = post('password');
        if($username && $password) {
            $hashPassword = User::where('username',$username)->value('password');
            if (password_verify($password, $hashPassword)) {
                $token = strval($this->getToken());
                if($token) {
                    $tokenArray = explode('.',$token);
                    $header = base64_decode(array_shift($tokenArray));
                    $payload = json_decode(base64_decode(array_shift($tokenArray)));
                    zCookie('token',$token,$payload->exp-time()-5);
                }
                //zSession('admin', $username);
            }
        }
        redirectTo('/admin');
    }

}