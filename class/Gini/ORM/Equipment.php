<?php

namespace Gini\ORM;

class Equipment extends \Gini\Module\Object
{
    public $site            = 'object:site';
    public $lims_id         = 'int';
    public $name            = 'string:100';
    public $icon            = 'string:500'; 
    public $url             = 'string:500'; // 仪器url 
    public $phone           = 'string:500';
    public $email           = 'string:500';
    public $contact         = 'string:100';
    public $incharge        = 'string:100';
    public $location        = 'string:250';
    public $can_sample      = 'bool';
    public $sample_url      = 'string:500';
    public $can_reserv      = 'bool';
    public $reserv_url      = 'string:500';
    public $price           = 'double';
    public $status          = 'int';
    public $ref             = 'string:50'; // 仪器编号
    public $cat             = 'string:50'; // 分类号
    public $model           = 'string:50'; // 型号
    public $current_user    = 'string:50';// 当前使用者
    public $specification   = 'string:*'; // 规格
    public $tech_specs      = 'string:*'; // 主要规格及技术指标
    public $features        = 'string:*'; // 主要功能及特色
    public $configs         = 'string:*'; // 主要附件及配置
    public $manu_at         = 'string:50'; // 制造国家
    public $manufacturer    = 'string:50'; // 生产厂家
    public $manu_date       = 'datetime'; // 出厂日期
    public $purchased_date  = 'datetime'; // 购置日期
    public $net_date        = 'datetime'; // 入网日期
    public $group           = 'array'; // 组织机构
    public $tag             = 'string:100'; // 仪器分类
    public $show            = 'bool'; // 展示
    public $sync_time       = 'datetime';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'unique:lims_id,site',
        'name', 'can_sample', 'can_reserv',
        'price', 'tag', 'show', 'sync_time'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

    public function links() {
        if ($this->show) {
            $links['hidden'] = [
                'title' => T('隐藏'),
                'class' => 'btn btn-sm btn-link p-0',
                'url' => "gini-ajax:ajax/admin/equipment/switch/{$this->id}",
            ];
        }
        else {
            $links['show'] = [
                'title' => T('显示'),
                'class' => 'btn btn-sm btn-link p-0',
                'url' => "gini-ajax:ajax/admin/equipment/switch/{$this->id}",
            ];
        }
        
        return \Gini\Module\Widget::factory('links', ['items' => $links]);
    }

}
