<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */

class LogoutController extends Controller
{

    public function index()
    {
        //session_destroy();
        zCookie('token',null);
        redirectTo('/login');
    }

}