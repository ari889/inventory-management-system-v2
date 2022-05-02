<?php

namespace Modules\Product\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\ProductFormRequest;
use Modules\System\Entities\Brand;
use Modules\System\Entities\Tax;
use Modules\System\Entities\Unit;
use Keygen;

class ProductController extends BaseController
{
    use UploadAble;
    /**
     * load brand model
     */

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('product-access')){
            $this->setPageData('Product', 'Product', 'fas fa-box');
            $data = [
                'brands' => Brand::all(),
                'categories' => Category::all(),
                'units' => Unit::all(),
                'taxes' => Tax::all(),
            ];
            return view('product::index', $data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('product-access')){
                if(!empty($request->name)){
                    $this->model->setName($request->name);
                }
                if(!empty($request->code)){
                    $this->model->setCode($request->code);
                }
                if(!empty($request->brand_id)){
                    $this->model->setBrandID($request->brand_id);
                }
                if(!empty($request->category_id)){
                    $this->model->setCategoryID($request->category_id);
                }
    
                $this->model->setOrderValue($request->input('order.0.column'));
                $this->model->setDirValue($request->input('order.0.dir'));
                $this->model->setLengthValue($request->input('length'));
                $this->model->setStartValue($request->input('start'));
    
                $list = $this->model->getDatatableList();
    
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
    
                    /**
                     * menu edit link
                     */
                    if(permission('product-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }

                    /**
                     * menu edit link
                     */
                    if(permission('product-view')){
                        $action .= '<a href="#" class="dropdown-item view_data" data-id="'.$value->id.'"><i class="fas fa-eye text-warning"></i> View</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('product-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->title.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('product-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = table_image(PRODUCT_IMAGE_PATH, $value->image, $value->name);
                    $row[] = $value->name;
                    $row[] = $value->code;
                    $row[] = $value->brand->title;
                    $row[] = $value->category->name;
                    $row[] = $value->unit->unit_name;
                    $row[] = number_format($value->cost, 2);
                    $row[] = number_format($value->price, 2);
                    $row[] = number_format($value->qty, 2);
                    $row[] = $value->alert_qty ? number_format($value->alert_qty, 2) : 0;
                    $row[] = $value->tax->name;
                    $row[] = TAX_METHOD[$value->tax_method];
                    $row[] = permission('product-edit') ? change_status($value->id, $value->status, $value->name) : STATUS_LABEL[$value->status];
                    $row[] = action_button($action);
                    $data[] = $row;
                }
    
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * store or update category
     */
    public function store_or_update(ProductFormRequest $request){
        if($request->ajax()){
            if(permission('product-add') || permission('product-edit')){
                $collection = collect($request->validated())->except('image', 'qty', 'alert_qty');
                $collection = $this->track_data($collection, $request->update_id);
                $qty = $request->qty ? $request->qty : null;
                $alert_qty = $request->alert_qty ? $request->alert_qty : null;
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'), PRODUCT_IMAGE_PATH);
                    
                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image, PRODUCT_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image', 'qty', 'alert_qty'));
                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                $output = $this->store_message($result, $request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * show product
     */
    public function show(Request $request){
        if($request->ajax()){
            if(permission('product-view')){
                $product = $this->model->with('brand', 'category', 'unit', 'purchase_unit', 'sale_unit', 'tax')->findOrFail($request->id);
                return view('product::details', compact('product'))->render();
            }
        }
    }

    /**
     * edit category
     */
    public function edit(Request $request){
        if($request->ajax()){
            if(permission('product-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * delete category
     */
    public function delete(Request $request){
        if($request->ajax()){
            if(permission('product-delete')){
                $product = $this->model->find($request->id);
                $image = $product->image;
                $result = $product->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image, PRODUCT_IMAGE_PATH);
                    }
                }
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * bulk delete
     */
    public function bulk_delete(Request $request){
        if($request->ajax()){
            if(permission('product-bulk-delete')){
                $products = $this->model->toBase()->select('image')->whereIn('id', $request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($products)){
                        foreach ($products as $product) {
                            if($product->image){
                                $this->delete_file($product->image, PRODUCT_IMAGE_PATH);
                            }
                        }
                    }
                }
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * change brand status
     */
    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('product-edit')){
                $result = $this->model->find($request->id)->update(['status' => $request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * generate 8 digit key generator
     */
    public function generate_code(){
        return Keygen::numeric(8)->generate();
    }

    /**
     * populate product unit
     */
    public function populate_unit($id){
        $units = Unit::where('base_unit', $id)->orWhere('id', $id)->pluck('unit_name', 'id');
        return json_encode($units);
    }
}
