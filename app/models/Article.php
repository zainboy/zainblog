<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2017/2/17
 * Time: 9:32
 */

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

    protected $fillable = ['title','content','sort_id','allow_remark'];

    public function sort()
    {
        return $this->belongsTo('Sort');
    }

    public function comments() {
        return $this->hasMany('Comment');
    }

}