<?php

namespace Gini\Controller\CLI;

class Equipment extends \Gini\Controller\CLI {

    function actionGet($args = []) {
        if ($args) {
            $site = a('site', $args[0]);
            if (!$site->id || !$site->sync) return false;
            $this->sync($site);
        }
        else {
            $sites = those('site')->whose('sync')->is(true);
            foreach ($sites as $site) {
                $this->sync($site);
            }
        }
    }

    private function sync($site) {
        $api = rtrim($site->url, '/') . '/api';
        try {
            $rpc = new \Gini\RPC($api);
            $rpc->connectTimeout = 5000;
            
            $result = $rpc->equipment->searchEquipments([]);
            $total = $result['total'];
            $token = $result['token'];
            $start = 0;
            $step = 1;

            $syncTime = date('Y-m-d H:i:s');

            while ($start <= $total) {
                $equs = $rpc->equipment->getEquipments($token, $start, $step);
                if (count($equs)) foreach ($equs as $equ) {
                    $equipment = a('equipment')->whose('site')->is($site)
                        ->andWhose('lims_id')->is($equ['id']);
                    $equipment->site = $site;
                    $equipment->lims_id = $equ['id'];
                    $equipment->name = $equ['name'];
                    $equipment->icon = $equ['iconreal_url'] ? : $equ['icon128_url'];
                    $equipment->url = $equ['url'];
                    $equipment->phone = $equ['phone'];
                    $equipment->email = $equ['email'];
                    $equipment->contact = $equ['contact'];
                    $equipment->incharge = $equ['incharges'];
                    $equipment->location = $equ['location'] . ' ' .  $equ['location2'];
                    $equipment->can_sample = !!$equ['accept_sample'];
                    $equipment->sample_url = $equ['sample_url'];
                    $equipment->can_reserv = !!$equ['accept_reserv'];
                    $equipment->reserv_url = $equ['sample_url'];
                    $equipment->price = $equ['price'] ? : 0.0;
                    $equipment->status = $equ['status'];
                    $equipment->ref = $equ['ref_no'] ? : '';
                    $equipment->cat = $equ['cat_no'] ? : '';
                    $equipment->model = $equ['model_no'] ? : '';
                    $equipment->current_user = $equ['current_user'] ? : '';
                    $equipment->specification = $equ['specification'] ? : '';
                    $equipment->tech_specs = $equ['tech_specs'] ? : '';
                    $equipment->features = $equ['features'] ? : '';
                    $equipment->configs = $equ['configs'] ? : '';
                    $equipment->manu_at = $equ['manu_at'] ? : '';
                    $equipment->manufacturer = $equ['manufacturer'] ? : '';
                    $equipment->manu_date = date('Y-m-d H:i:s', $equ['manu_date']);
                    $equipment->purchased_date = date('Y-m-d H:i:s', $equ['purchased_date']);
                    $equipment->net_date = date('Y-m-d H:i:s', $equ['atime']);
                    $equipment->group = $equ['group'] ? : [];
                    $equipment->tag = $equ['tags'] ? : '';
                    $equipment->sync_time = $syncTime;
                    $equipment->save();

                    foreach (explode(',', $equipment->tag) as $name) {
                        if (!$name) continue;
                        $name = trim($name);
                        $tag = a('equipment/tag')->whose('name')->is($name);
                        $tag->name = $name;
                        $tag->save();
                    }
                }
                $start += $step;
            }

            $equipments = those('equipment')->whose('site')->is($site)
                ->andWhose('mtime')->isLessThan($syncTime);
            if ($equipments->totalCount()) foreach ($equipments as $equipment) {
                $equipment->delete();
            }

            $site->error = 0;
            $site->sync_time = $syncTime;
            $site->save();
        }
        catch (\Gini\RPC\Exception $e) {
            error_log($e->getMessage());
            $site->error = 1;
            $site->save();
        }
    }
}
