<?php

// +----------------------------------------------------------------------
// | date: 2015-07-14
// +----------------------------------------------------------------------
// | SearchModel.php: 后端搜索工具导航模型
// +----------------------------------------------------------------------
// | Author: yangyifan <yangyifanphp@gmail.com>
// +----------------------------------------------------------------------

namespace App\Model\Admin;

use App\Model\Admin\BaseModel;

class SearchModel extends BaseModel {

    protected $table    = 'search';//定义表名
    protected $guarded  = ['id'];//阻挡所有属性被批量赋值

    /**
     * 搜索
     *
     * @param $map
     * @param $sort
     * @param $order
     * @param $offset
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected static function search($map, $sort, $order, $limit, $offset){
        return [
            'data' => self::mergeData(
                self::multiwhere($map)->
                select('c.cat_name', 'search.*')->
                join('search_cat as c', 'search.search_cat_id', '=', 'c.id')->
                orderBy('search.'.$sort, $order)->
                skip($offset)->
                take($limit)->
                get()
            ),
            'count' =>  self::multiwhere($map)->
                        join('search_cat as c', 'search.search_cat_id', '=', 'c.id')->
                        count(),
        ];
    }

    /**
     * 组合数据
     *
     * @param $roles
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public static function mergeData($data){
        if(!empty($data)){
            foreach($data as &$v){
                //组合状态
                $v->status = self::mergeStatus($v->status);
                //组合操作
                $v->handle  = '<a href="'.url('admin/search/edit', [$v->id]).'" target="_blank" >编辑</a>';
            }
        }
        return $data;
    }

}
