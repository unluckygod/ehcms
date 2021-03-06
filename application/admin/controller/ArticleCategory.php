<?php
// +----------------------------------------------------------------------
// | ehcms [ Efficient Handy Content Management System ]
// +----------------------------------------------------------------------
// | Copyright (c) http://ehcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zky < zky@ehcms.com >
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Db;

/**
 * 文章分类（暂仅支持两级分类）
 */
class ArticleCategory extends Init{

    /**
     * 文章分类列表页面
     */
	public function index(){
		$parentID = input('parent_id') ?: 0;
		$lists = Db::name('article_category')->where('parent_id', $parentID)->order('id', 'desc')->paginate(10);
		
		if ($parentID > 0){
			$parent = Db::name('article_category')->field('name')->where('id', $parentID)->find();
			$this->assign('parentName', $parent['name']);
		}
		$this->assign('parentID', $parentID);
		$this->assign('category', $lists);
		return $this->fetch();
	}

    /**
     * 文章分类新增
     */
	public function save(){
		$parentID = input('parent_id') ?: 0;
		$name = input('name');
		
		$id = Db::name('article_category')->insertGetId(['name' => $name, 'parent_id' => $parentID]);
		
		if ($id > 0){
			$this->success('文章分类新增成功！');
		}
	}

    /**
     * 文章分类更新
     */
	public function update(){
		$id = input('id');
		$name = input('name');
	
		if (Db::name('article_category')->where('id', $id)->update(['name' => $name]) == 1){
			$this->success('文章分类更新成功！');
		}
	}
	
}