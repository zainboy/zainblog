<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:30
 */

use App\Post;
use App\Sort;
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $where = '1=1';
        if(isset($_GET['m']) && $_GET['m']) {
            $where = 'DATE_FORMAT(create_time,\'%Y%m\') = '.$_GET['m'];
        }
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        //zCache('homeData',null);

        if($where==='1=1' && $currentPage === 1) {
            if(zCache('homeData')) {
                zView('home',zCache('homeData'));
            }
        }
        /*
         * has comments
        $postList = zTable('z_post')->selectRaw('z_post.*,z_sort.name as sort_name')->join('z_sort','z_sort.id','=','z_post.sort_id')->orderBy('z_post.create_time','desc')->whereRaw($where)->simplePaginate(3,'*','page',$currentPage);
        $cid = [];
        foreach($postList->items() as $k=>$v) {
            array_push($cid,$v->id);
        }
        $comments = zTable('z_comment')->whereIn('cid',$cid)->groupBy('cid')->selectRaw('cid,count(id) as comments')->get();
        if(count($comments)) {
            foreach ($comments as $comment)
            foreach($postList->items() as $k=>$v) {
                $v->comments = 0;
                if($v->id === $comment->cid) {
                    $v->comments = $comment->comments;
                }
            }
        }
        */
        $postList = zTable('z_post')->selectRaw('z_post.*,z_sort.name as sort_name')->join('z_sort','z_sort.id','=','z_post.sort_id')->orderBy('z_post.create_time','desc')->whereRaw($where)->simplePaginate(3,'*','page',$currentPage);
        $monthList = zTable('z_post')->selectRaw('DATE_FORMAT(create_time,\'%Y%m\') as month,count(id) as count')->groupBy('month')->get();
        $linkList = zTable('z_link')->where('hide','n')->get();
        $homeData = ['postList'=>$postList,'monthList'=>$monthList,'linkList'=>$linkList];
        if($where==='1=1' && $currentPage === 1) {
            $this->flushHomeCache();
        }
        zView('home',$homeData);
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

    public function post($id=0) {
        if($id) {
            zTable('z_post')->where('z_post.id',$id)->increment('views');
            $post = Zpost::where('id',$id)->with('sort')->first();
            dd($post);
            $post = zTable('z_post')->where('z_post.id',$id)->join('z_sort','z_post.sort_id','=','z_sort.id')->selectRaw('z_post.*,z_sort.name as sort_name')->first();
            if(!$post) {
                zView('errors.404');
            }
            zCsrf();
            $comments = Zpost::find($id)->comments()->orderBy('create_time','desc')->get();
            dd($comments);
            $comments = zTable('z_comment')->where('cid',$id)->orderBy('create_time','desc')->get();
            $monthList = zTable('z_post')->selectRaw('DATE_FORMAT(create_time,\'%Y%m\') as month,count(id) as count')->groupBy('month')->get();
            $linkList = zTable('z_link')->where('hide','n')->get();
            $comments = $this->formatComments($comments);
            zView('post',['post'=>$post,'comments'=>$comments,'monthList'=>$monthList,'linkList'=>$linkList]);
        } else {
            zView('errors.404');
        }
    }

    public function sort($id=0) {
        if($id) {
            $sort = zTable('z_sort')->find($id);
            $postList = zDB()->select('select z_post.*,B.cid,B.comments from z_post left join (select cid,count(z_comment.id) as comments from z_comment group by cid) B ON z_post.id=B.cid  where sort_id='.$id.' order by z_post.create_time desc');

            //$postList = zTable('z_post')->join('z_sort','z_post.sort_id','=','z_sort.id')->selectRaw('z_post.*,z_sort.name as sort_name')->toSql();
            $monthList = zTable('z_post')->selectRaw('DATE_FORMAT(create_time,\'%Y%m\') as month,count(id) as count')->groupBy('month')->get();
            $linkList = zTable('z_link')->where('hide', 'n')->get();
            zView('sort', ['sort'=>$sort,'postList' => $postList, 'monthList' => $monthList, 'linkList' => $linkList]);
        } else {
            zView('errors.404');
        }
    }

    public function search() {
        $keyword = $_GET['keyword'];
        if($keyword) {
            $where = 'where title like "%'.$keyword.'%"';
            $postList = zDB()->select('select z_post.*,z_sort.name as sort_name,B.cid,B.comments from z_post left join (select cid,count(z_comment.id) as comments from z_comment group by cid) B ON z_post.id=B.cid INNER JOIN z_sort on z_sort.id=z_post.sort_id '.$where.' order by z_post.create_time desc');
            $monthList = zTable('z_post')->selectRaw('DATE_FORMAT(create_time,\'%Y%m\') as month,count(id) as count')->groupBy('month')->get();
            $linkList = zTable('z_link')->where('hide','n')->get();
            zView('home',['postList'=>$postList,'monthList'=>$monthList,'linkList'=>$linkList]);
        } else {
            redirectTo('/');
        }
    }

    public function comment($postId=0) {
        if($this->isPost && $postId) {
            zCsrf();
            $data = post();
            $post = zTable('z_post')->find($postId);
            if(!$post) {
                zView('errors.404');
            }
            unset($data['csrf_token']);
            $data['cid'] = $postId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['ip'] = get_client_ip();
            $insert = zTable('z_comment')->insert($data);
            if ($insert) {
                redirectTo('/post/' . $postId);
            }
        } else {
            zView('errors.404');
        }
    }

}