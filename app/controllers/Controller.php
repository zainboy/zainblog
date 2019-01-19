<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */
use Lcobucci\JWT;
class Controller
{
    protected $controller;
    protected $action;
    protected $isPost;
    protected $status = 0;
    protected $tips = '';
    protected $data = [];
    protected $article_limit = 10;
    protected $comment_limit = 10;
    public function __construct()
    {
        $this->isPost = ($_SERVER['REQUEST_METHOD'] === 'POST') ? true:false;
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segment = explode('/',$urlPath);
        if(count($segment)) {
            if(count($segment)>1) {
                $this->controller = $segment[1];
                if(count($segment)>2) {
                    $this->action = $segment[2];
                } else {
                    $segment[2] = '';
                }
            }
        }
        $setting = Option::first();
        if($setting) {
            $this->article_limit = $setting->article_limit;
            $this->comment_limit = $setting->comment_limit;
        }
        zViewShare('setting',$setting);
        zViewShare('segment',$segment);
        $sorts = $this->getSorts();
        zViewShare('sorts',$sorts);
    }

    protected function ajax() {
        exit(json_encode(['status'=>$this->status,'data'=>$this->data,'tips'=>$this->tips]));
    }

    protected function countArticleByMonth() {
        $countArticleByMonth = zCache('countArticleByMonth');
        if(!$countArticleByMonth) {
            $countArticleByMonth = zTable('articles')->selectRaw('DATE_FORMAT(created_at,\'%Y%m\') as month,count(id) as count')->groupBy('month')->get();
            zCache('countArticleByMonth',$countArticleByMonth);
        }
        return $countArticleByMonth;
    }

    protected function getSorts() {
        $sorts = zCache('sorts');
        if(!$sorts) {
            $sorts = Sort::all();
            zCache('sorts',$sorts);
        }
        return $sorts;
    }

    protected function getLinks() {
        $links = zCache('links');
        if(!$links) {
            $links = zTable('links')->where('hide','n')->get();
            zCache('links',$links);
        }
        return $links;
    }

    protected function clearCache($cache) {
        zCache($cache,null);
    }

    protected function getToken() {
        $signer = new JWT\Signer\Hmac\Sha256();
        $key = \Zain\Config::get('jwt.key');
        $token = (new JWT\Builder())->setIssuer('zain')
            ->setAudience('f.com')
            ->setIssuedAt(time())
            ->setExpiration(time()+3600)
            ->sign($signer,$key)
            ->getToken();
        return $token;
    }

    protected function validateToken() {
        try {
            $token = zCookie('token');
            $token = (new JWT\Parser())->parse($token);
            $signer = new JWT\Signer\Hmac\Sha256();
            $key = \Zain\Config::get('jwt.key');
            $verify = $token->verify($signer,$key);
        } catch (Exception $exception) {
            return false;
        }
        return $verify;
    }

}