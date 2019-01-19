<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->validateToken()) {
            redirectTo('/login');
        }
        zViewShare('admin',User::first());
        /*
        if(! zSession('admin')) {
            redirectTo('/login');
        }
        */
    }

    public function index()
    {
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'];
        $mysqlVerson = zDB()->getPdo()->getAttribute(constant('PDO::ATTR_SERVER_VERSION'));

        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $gdVersion = '不支持';
        if (function_exists("imagecreate")) {
            if (function_exists('gd_info')) {
                $gdVersion = gd_info()['GD Version'];
            } else{
                $gdVersion = '支持';
            }
        }
        $articleCount = Article::count();
        $commentCount = Comment::count();
        $assign = [
            'serverSoftware'    =>  $_SERVER['SERVER_SOFTWARE'],
            'phpVersion'        =>  PHP_VERSION,
            'mysqlVersion'      =>  $mysqlVerson,
            'uploadMaxFilesize' =>  ini_get('upload_max_filesize'),
            'gdVersion'         =>  $gdVersion,
            'articleCount'         =>  $articleCount,
            'commentCount'      =>  $commentCount
        ];
        zView('admin.home',$assign);
    }

    public function write() {
        zCsrf();
        if($this->isPost) {
            if(post('title') && post('content')) {
                $data = [
                    'title' =>  post('title'),
                    'content' =>  post('content'),
                    'sort_id' =>  post('sort_id')
                ];
                if(post('allow_remark')) {
                    $data['allow_remark'] = 'y';
                }
                $res = Article::create($data);
                if($res) {
                    $this->clearCache('articles');
                    $this->clearCache('countArticleByMonth');
                    redirectTo('/admin/articles');
                } else {
                    redirectTo('/admin/write');
                }
            }
        } else {
            $sortList = Sort::orderBy('taxis')->get();
            zView('admin.write', ['sortList' => $sortList]);
        }
    }

    public function article($id=0) {
        zCsrf();
        if($this->isPost) {
            $update = ['sort_id'=>post('sort_id'),'title'=>post('title'),'content'=>post('content')];
            $update['allow_remark'] = 'y';
            if(! post('allow_remark')) {
                $update['allow_remark'] = 'n';
            }
            $save = Article::where('id',$id)->update($update);
            if($save) {
                $this->clearCache('articles');
                $this->clearCache('countArticleByMonth');
                redirectTo('/admin/articles');
            } else {
                redirectTo('/admin/articles/'.$id);
            }
        } else {
            $article = Article::find($id);
            $sortList = $this->getSorts();
            zView('admin.article', ['article' => $article,'sortList'=>$sortList]);
        }
    }

    public function articles($sortId=0) {
        zCsrf();
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $where = '1=1';
        if($sortId) {
            $where = 'sort_id=' . intval($sortId);
        }
        $articleList = Article::orderBy('created_at', 'desc')->paginate($this->article_limit);
        $sortList = $this->getSorts();
        foreach($articleList as $v) {
            $v->comments = Comment::where('article_id',$v->id)->count();
        }
        zView('admin.articles',['articleList'=>$articleList,'sortList'=>$sortList]);
    }

    public function articleDelete() {
        zCsrf();
        if($this->isPost) {
            $res = Article::whereIn('id',post('ids'))->delete();
            if($res) {
                $this->clearCache('articles');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function search() {
        zCsrf();
        if($this->isPost && post('keyword')) {
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $articleList = Article::where('title','like','%'.post('keyword').'%')->orderBy('created_at', 'desc')->paginate($this->article_limit);
            $sortList = $this->getSorts();
            foreach ($articleList as $k=>$v) {
                foreach ($sortList as $sort) {
                    if($sort->id === $v->sort_id) {
                        $articleList[$k]->sort_name = $sort->name;
                    }
                }
            }
            zView('admin.articles',['articleList'=>$articleList,'sortList'=>$sortList]);
        } else {
            redirectTo('/admin/articles');
        }
    }

    public function changeSort($sortId = 0) {
        zCsrf();
        if($this->isPost && $sortId) {
            $res = Article::whereIn('id',post('ids'))->update(['sort_id'=>$sortId]);
            if($res) {
                $this->clearCache('articles');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function sortDelete() {
        zCsrf();
        if($this->isPost) {
            $res = Sort::where('id',post('id'))->delete();
            if($res) {
                $this->clearCache('sorts');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function sortReOrder() {
        zCsrf();
        if($this->isPost) {
            $data = post('data');
            if($data) {
                foreach ($data as $v) {
                    $sortOrder = explode(':',$v);
                    Sort::where('id',$sortOrder[0])->update(['taxis'=>$sortOrder[1]]);
                }
                $this->clearCache('sorts');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    private function sortAdd() {
        $taxis = post('taxis');
        $name = post('name');
        if (is_numeric($taxis) && $name) {
            $existName = Sort::where('name', $name)->select('id')->first();
            if ($existName) {
                $this->tips = '分类已存在';
            } else {
                $data = [
                    'taxis' => $taxis,
                    'name' => $name,
                    'pid' => intval(post('pid')),
                    'description' => post('description')
                ];
                $res = Sort::create($data);
                if ($res) {
                    $this->clearCache('sorts');
                    $this->status = 1;
                }
            }
        } else {
            $this->tips = '添加失败';
        }
    }

    private function sortEdit($id=0) {
        $name = post('name');
        if ($id && $name) {
            $data = [
                'name' => $name,
                'pid' => intval(post('pid')),
                'description' => post('description')
            ];
            $res = Sort::where('id',$id)->update($data);
            if ($res) {
                $this->clearCache('sorts');
                $this->status = 1;
            }
        } else {
            $this->tips = '修改失败';
        }
    }

    public function sort($id=0) {
        zCsrf();
        if($this->isPost) {
            if($id) {
                $this->sortEdit($id);
            } else {
                $this->sortAdd();
            }
            $this->ajax();
        } else {
            $sortList = Sort::orderBy('taxis')->get();
            if($id) {
                $sort = Sort::find($id);
                zView('admin.sort_edit',['sort'=>$sort,'sortList' => $sortList]);
            } else {
                zView('admin.sort', ['sortList' => $sortList]);
            }
        }
    }

    public function commentDelete() {
        zCsrf();
        if($this->isPost) {
            $res = Comment::whereIn('id',post('data'))->delete();
            if($res) {
                $this->clearCache('links');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function comment() {
        zCsrf();
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $commentList = Comment::orderBy('created_at', 'desc')->with('article')->paginate($this->comment_limit);
        zView('admin.comment',['commentList' => $commentList]);
    }

    public function linkDelete() {
        zCsrf();
        if($this->isPost) {
            $res = Link::where('id',post('id'))->delete();
            if($res) {
                $this->clearCache('links');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function linkReOrder() {
        zCsrf();
        if($this->isPost) {
            $data = post('data');
            if($data) {
                foreach ($data as $v) {
                    $linkOrder = explode(':',$v);
                    Link::where('id',$linkOrder[0])->update(['taxis'=>$linkOrder[1]]);
                }
                $this->clearCache('links');
                $this->status = 1;
            }
            $this->ajax();
        }
    }

    public function linkHide() {
        zCsrf();
        if($this->isPost) {
            $id = post('id');
            $hide = post('hide');
            if($id && $hide) {
                $res = Link::where('id',$id)->update(['hide'=>$hide]);
                if($res) {
                    $this->clearCache('links');
                    $this->status = 1;
                }
            }
            $this->ajax();
        }
    }

    private function linkAdd() {
        $taxis = post('taxis');
        $name = post('name');
        $url = post('url');
        if (is_numeric($taxis) && $name && $url) {
            $existName = Link::where('name',$name)->select('id')->first();
            if ($existName) {
                $this->tips = '链接已存在';
            } else {
                $data = [
                    'taxis' => $taxis,
                    'name' => $name,
                    'url' => $url,
                    'hide' => 'y',
                    'description' => post('description')
                ];
                $res = Link::create($data);
                if ($res) {
                    $this->clearCache('links');
                    $this->status = 1;
                }
            }
        } else {
            $this->tips = '添加失败';
        }
    }

    private function linkEdit($id=0) {
        $name = post('name');
        $url = post('url');
        if ($id && $url && $name) {
            $data = [
                'name' => $name,
                'url' => $url,
                'description' => post('description')
            ];
            $res = Link::where('id',$id)->update($data);
            if ($res) {
                $this->clearCache('links');
                $this->status = 1;
            }
        } else {
            $this->tips = '修改失败';
        }
    }

    public function link($id=0) {
        zCsrf();
        if($this->isPost) {
            if($id) {
                $this->linkEdit($id);
            } else {
                $this->linkAdd();
            }
            $this->ajax();
        } else {
            $linkList = Link::orderBy('taxis')->get();
            if($id) {
                $link = Link::find($id);
                zView('admin.link_edit',['link'=>$link,'linkList' => $linkList]);
            } else {
                zView('admin.link', ['linkList' => $linkList]);
            }
        }
    }

    public function setting() {
        zCsrf();
        $setting = Option::first();
        zView('admin.setting',['setting'=>$setting]);
    }

    public function personal() {
        zCsrf();
        $user = User::first();
        zView('admin.personal',['user'=>$user]);
    }

    public function changeSetting() {
        zCsrf();
        if($this->isPost) {
            $post = post();
            unset($post['csrf_token']);
            $setting = Option::first();
            if(isset($post['allow_comment'])) {
                $post['allow_comment'] = 1;
            }
            if(isset($post['comment_verify_code'])) {
                $post['comment_verify_code'] = 1;
            }
            if(isset($post['login_verify_code'])) {
                $post['login_verify_code'] = 1;
            }
            if($setting) {
                $res = Option::where('id',$setting['id'])->update($post);
            } else {
                $res = Option::create($post);
            }
            redirectTo('/admin/setting');
        }
    }

    public function changePersonal() {
        zCsrf();
        if($this->isPost) {
            $post = post();
            if(isset($_FILES['avatar']) && $_FILES['avatar']['tmp_name']) {
                if($_FILES['avatar']['type'] === 'image/png') {
                    $name = 'user.png';
                } elseif ($_FILES['avatar']['type'] === 'image/jpeg') {
                    $name = 'user.jpg';
                }
                move_uploaded_file($_FILES['avatar']['tmp_name'],BASE_PATH.'public/img/'.$name);
                $post['avatar'] = '/img/'.$name;
            }
            unset($post['csrf_token']);
            $password = $post['password'];
            $password2 = $post['repeat_password'];
            if($password === $password2) {
                unset($post['password']);
                unset($post['repeat_password']);
            }
            $post['password'] = password_hash($password2,PASSWORD_DEFAULT );
            $res = User::where('id',$post['id'])->update($post);

            $admin = User::first();
            $post['avatar'] = $admin->avatar;
            $guestData = ['nickname'=>$post['nickname'],'mail'=>$post['email'],'admin'=>1];
            $exists = Guest::where('admin',1)->first();
            if(!$exists) {
                Guest::create($guestData);
            } else {
                Guest::where('admin',1)->update($guestData);
            }
            zCookie('foream_blog_guest_nickname',$post['nickname'],31104000);
            zCookie('foream_blog_guest_mail',$post['email'],31104000);

            redirectTo('/logout');
        }
    }

}