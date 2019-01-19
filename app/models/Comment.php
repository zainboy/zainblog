<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2017/2/17
 * Time: 9:32
 */

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $fillable = ['article_id','pid','nickname','ip','mail','comment','admin'];

    public function article()
    {
        return $this->belongsTo('Article');
    }

}