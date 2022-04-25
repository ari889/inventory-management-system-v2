<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Repositories\RoleRepository as Role;

class RoleService extends BaseService{
    
    /**
     * load menu and module model
     */
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * get datatable data from menu repository
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(!empty($request->role_name)){
                $this->role->setRoleName($request->role_name);
            }

            $this->role->setOrderValue($request->input('order.0.column'));
            $this->role->setDirValue($request->input('order.0.dir'));
            $this->role->setLengthValue($request->input('length'));
            $this->role->setStartValue($request->input('start'));

            $list = $this->role->getDatatableList();

            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';

                /**
                 * menu edit link
                 */
                if(permission('role-edit')){
                    $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                }

                /**
                 * menu delete link
                 */
                if(permission('role-delete')){
                    if($value->deletable == 1){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->role_name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                }


                $row = [];
                if(permission('role-bulk-delete')){
                    $row[] = ($value->deletable == 1) ? table_checkbox($value->id) : '';
                }
                $row[] = $no;
                $row[] = $value->role_name;
                $row[] = DELETABLE[$value->deletable];
                $row[] = action_button($action);
                $data[] = $row;
            }

            return $this->datatable_draw($request->input('draw'), $this->role->count_all(), $this->role->count_filtered(), $data);
        }
    }

    /**
     * store or update data in database
     */
    public function store_or_update_data(Request $request){
        $collection = collect($request->validated());
        $created_at = $updated_at = Carbon::now();
        if($request->update_id){
            $collection = $collection->merge(compact('updated_at'));
        }else{
            $collection = $collection->merge(compact('created_at'));
        }

        return $this->role->updateOrCreate(['id' => $request->update_id], $collection->all());
    }

    /**
     * get menu edit info
     */
    public function edit(Request $request){
        return $this->role->find($request->id);
    }

    /**
     * delete menu single data
     */
    public function delete(Request $request){
        return $this->role->delete($request->id);
    }

    /**
     * bulk delete data from database
     */
    public function bulk_delete(Request $request){
        return $this->role->destroy($request->ids);
    }
    
}