<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository extends BaseRepository{
    /**
     * load module model
     */
    public function __construct(Module $model)
    {
        $this->model = $model;
    }

    /**
     * get module list
     */
    public function module_list(int $menu_id){
        $modules = $this->model->orderBy('order', 'asc')
        ->where(['type' => 2, 'menu_id' => $menu_id])
        ->get()
        ->nest()
        ->setIndent('-- ')
        ->listsFlattened('module_name');

        return $modules;
    }

    /**
     * get session module list/menu
     */
    public function session_module_list(){
        return $this->model->doesnthave('parent')
        ->orderBy('order', 'asc')
        ->with('children')->get();
    }

    /**
     * delete child of the module
     */
    public function delete_child(int $module_id){
        return $this->model->where('parent_id', $module_id)->delete();
    }


}