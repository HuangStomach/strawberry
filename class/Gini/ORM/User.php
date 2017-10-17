<?php

namespace Gini\ORM;

class User extends \Gini\Module\Object
{
    public $name            = 'string:50';
    public $username        = 'string:50';
    public $ref             = 'string:10';
    public $email           = 'string:120';
    public $phone           = 'string:120';
    public $ctime           = 'datetime';

    protected static $db_index = [
        'name', 'ref',
        'email', 'phone'
    ];

    public function save() {
        if ($this->ctime == '0000-00-00 00:00:00' || !isset($this->ctime)) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function sidebarItems() {
        $items = [];
        if ($this->isCharger() && !$this->isAdmin()) {
            $items['distribution'] = [
                'icon' => 'fa fa-address-card-o',
                'text' => '分配结果'
            ];
        }
      
        $items['authorized/my'] = [
            'icon' => 'fa fa-address-card-o',
            'text' => '我的授权'
        ];

        $items['transaction/my'] = [
            'icon' => 'fa fa-newspaper-o',
            'text' => '我的明细'
        ];
        
        //经费管理，经费充值 合并为经费列表
        if ($this->isAllowedTo('充值')) {
            $items['fund'] = [
                'icon' => 'fa fa-cubes',
                'text' => '经费列表',
            ];
             $items['deposit'] = [
                'icon' => 'fa fa-jpy',
                'text' => '充值冻结'
            ];
            $items['transaction'] = [
                'icon' => 'fa fa-list',
                'text' => '财务明细'
            ];
        }

        //经费授权”与“授权信息”合并为授权管理
        if ($this->isAllowedTo('授权')) {
            $items['authorized'] = [
                'icon' => 'fa fa-handshake-o',
                'text' => '授权管理'
            ];
        }

        if ($this->isAllowedTo('审核')) {
            $items['distribution'] = [
                'icon' => 'fa fa-check-square-o',
                'text' => '分配审批'
            ];
        } 

        if ($this->isAdmin()) {
            $items['transaction/system'] = [
                'icon' => 'fa fa-list',
                'text' => '系统财务明细'
            ];
            $items['info/overview'] = [
                'icon' => 'fa fa-tachometer',
                'text' => '系统财务总览'
            ];
            $items['limit'] = [
                'icon' => 'fa fa-lock',
                'text' => '充值限制'
            ];
            $items['rate'] = [
                'icon' => 'fa fa-percent',
                'text' => '补贴基金分配比例'
            ];
        }
       
        return $items;
    }

}
