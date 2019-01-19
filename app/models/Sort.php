<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2017/2/17
 * Time: 9:32
 */

use Illuminate\Database\Eloquent\Model;

class Sort extends Model {

    protected $fillable = ['taxis', 'name'];

    public function article()
    {
        return $this->hasMany('Article');
    }

}