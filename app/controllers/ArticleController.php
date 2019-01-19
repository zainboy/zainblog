<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */

class ArticleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        $where = '1=1';
        if(isset($_GET['m']) && $_GET['m']) {
            $where = 'DATE_FORMAT(created_at,\'%Y%m\') = '.$_GET['m'];
        }
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if($where==='1=1' && $currentPage === 1) {
            $articles = zCache('articles');
            if(!$articles) {
                $articles = Article::with('sort')->orderBy('created_at', 'desc')->paginate($this->article_limit);
                zCache('articles',$articles);
            }
        } else {
            Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $articles = Article::with('sort')->orderBy('created_at', 'desc')->whereRaw($where)->paginate($this->article_limit);
        }
        $countArticleByMonth = $this->countArticleByMonth();
        $links = $this->getLinks();
        $homeData = ['articles'=>$articles,'countArticleByMonth'=>$countArticleByMonth,'links'=>$links];
        zView('home',$homeData);
    }

    public function find($id=0) {
        if($id) {
            Article::find($id)->increment('views');
            $articles = zCache('articles');
            if($articles) {
                foreach ($articles as $v) {
                    if($v->id === intval($id)) {
                        $v->views++;
                        break;
                    }
                }
                zCache('articles',$articles);
            }
            $article = Article::where('id',$id)->with('sort')->first();
            if(!$article) {
                zView('errors.404');
            }
            zCsrf();
            $comments = Article::find($id)->comments()->orderBy('created_at','desc')->get();
            $comments = $this->formatComments($comments);
            $countArticleByMonth = $this->countArticleByMonth();
            $links = $this->getLinks();
            zView('article',['article'=>$article,'comments'=>$comments,'countArticleByMonth'=>$countArticleByMonth,'links'=>$links]);
        } else {
            zView('errors.404');
        }
    }

    public function sort($id=0) {
        if($id) {
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $sort = Sort::find($id);
            $articles = Sort::find($id)->article()->orderBy('created_at', 'desc')->paginate($this->article_limit);
            $countArticleByMonth = $this->countArticleByMonth();
            $links = $this->getLinks();
            zView('sort', ['sort'=>$sort,'articles' => $articles, 'countArticleByMonth' => $countArticleByMonth, 'links' => $links]);
        } else {
            zView('errors.404');
        }
    }

    public function search() {
        $keyword = $_GET['keyword'];
        if($keyword) {
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $articles = Article::with('sort')->orderBy('created_at', 'desc')->where('title','like','%'.$keyword.'%')->paginate($this->article_limit);
            $countArticleByMonth = $this->countArticleByMonth();
            $links = $this->getLinks();
            zView('home',['articles' => $articles, 'countArticleByMonth' => $countArticleByMonth, 'links' => $links]);
        } else {
            redirectTo('/');
        }
    }

    public function comment($articleId=0) {
        if($this->isPost && $articleId) {
            zCsrf();
            $post = post();
            $nickname = isset($post['nickname']) ? $post['nickname'] : '';
            $mail = isset($post['mail']) ? $post['mail'] : '';
            $comment = isset($post['comment']) ? $post['comment'] : '';
            if(! $nickname || ! $mail  || ! $comment) {
                $this->tips = '缺少参数';
            }
            $guestData = ['nickname'=>$nickname,'mail'=>$mail];
            $exists = Guest::where($guestData)->first();
            if(!$exists) {
                $nicknameUsed =  Guest::where(['nickname'=>$nickname])->first();
                if($nicknameUsed) {
                    $this->status = -1;
                    $this->ajax();
                } else {
                    $emailUsed =  Guest::where(['mail'=>$mail])->first();
                    if($emailUsed) {
                        $this->status = -2;
                        $this->ajax();
                    } else {
                        Guest::create($guestData);
                    }
                }
            } else {
                $post['admin'] = $exists['admin'];
            }
            zCookie('foream_blog_guest_nickname',$nickname,31104000);
            zCookie('foream_blog_guest_mail',$mail,31104000);
            $post['ip'] = get_client_ip();
            $post['article_id'] = $articleId;
            $comment = Comment::create($post);
            if($comment) {
                $this->status = 1;
            }
        }
        $this->ajax();
    }

    private function formatComments($comments) {
        $commentList = $comments;
        foreach ($comments as $k => $v) {
            if($v->pid) {
                foreach ($commentList as $key => $value) {
                    if($value->id === $v->pid) {
                        $comments[$key]->children = $comments[$k];
                        $comments[$key]->children->at = $commentList[$key]->nickname;
                    }
                }
            }
        }
        foreach ($comments as $k => $v) {
            if($v->pid) {
                unset($comments[$k]);
            }
        }
        foreach ($comments as $k => $v) {
            if(isset($v->children)) {
                $comments[$k]->floor = 0;
                $i = 0;
                while(isset($v->children->children)) {
                    $i++;
                    $v->children->floor = $i;
                    $v = $v->children;
                }
                $v->children->floor = $i+1;
            }
        }
        return $comments;
    }

}