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
 * 系统设置
 */
class Setting extends Init{

    /**
     * 全局系统设置
     */
	public function system(){
	    $group = db('admin_group')->field('id, name')->order('id', 'desc')->select();
	    $this->assign('group', $group);
		return $this->fetch();
	}

    /**
     * 个人系统设置（个性化）
     */
	public function personal(){
		return $this->fetch();
	}

    /**
     * 全局系统设置（编辑）
     */
	public function systemEdit(){
		if (request()->isPut()){
            $result = db('admin_setting')->where('key', input('key'))->find();

            if ($result){
                db('admin_setting')->where(['uid' => 0, 'key' => input('key')])->update(['name' => input('name'), 'value' => input('value')]);
                $this->successResult();
            }else{
                if (db('admin_setting')->insert(['uid' => 0, 'name' => input('name'), 'key' => input('key'), 'value' => input('value')]) == 1){
                    $this->successResult();
                }
            }
		}
	}

    /**
     * 个人系统设置（编辑）
     */
	public function personalEdit(){
		if (request()->isPut()){
			$result = db('admin_setting')->where(['uid' => cookie('user_id'), 'key' => input('key')])->find();
			if ($result){
				if (db('admin_setting')->where('id', $result['id'])->update(['value' => input('value')]) == 1){
					$this->successResult();
				}
			}else{
				if (db('admin_setting')->insert([
					'uid' => cookie('user_id'),
					'key' => input('key'),
					'value' => input('value')
				]) == 1){
					$this->successResult();
				}
			}
		}
	}

    /**
     * 支付参数设置
     */
	public function alipay(){
        $alipay = db('admin_setting')->where('key', 'alipay')->find();
        $alipay = unserialize($alipay['value']);
        $this->assign('alipay', $alipay);
	    return $this->fetch();
    }

    /**
     * 支付参数设置（编辑）
     */
    public function alipayUpdate(){
        $data = [
            'name' => '支付宝配置',
            'key' => 'alipay',
            'value' => serialize(input('param.'))
        ];

        $alipay = Db::name('admin_setting')->where('key', 'alipay')->find();
        if ($alipay){
            Db::name('admin_setting')->where('key', 'alipay')->update($data);
        }else{
            Db::name('admin_setting')->insert($data);
        }

        $this->successResult();
    }

    /**
     * 微信参数设置
     */
    public function weixin(){
	    $weixin = db('admin_setting')->where('key', 'weixin_pay')->find();
	    $weixin = unserialize($weixin['value']);
        $this->assign('weixin', $weixin);
        return $this->fetch();
    }

    /**
     * 微信参数设置（编辑）
     */
    public function weixinUpdate(){
        $data = [
            'name' => '微信支付配置',
            'key' => 'weixin_pay',
            'value' => serialize(input('param.'))
        ];

        $weixin = Db::name('admin_setting')->where('key', 'weixin_pay')->find();
        if ($weixin){
            Db::name('admin_setting')->where('key', 'weixin_pay')->update($data);
        }else{
            Db::name('admin_setting')->insert($data);
        }

        $this->successResult();
    }
}