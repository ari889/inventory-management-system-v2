<?php
/**
 * check user permission has or not
 */
if(!function_exists('permission')){
    function permission(string $value){
        if(collect(\Illuminate\Support\Facades\Session::get('permission'))->contains($value)){
            return true;
        }
        return false;
    }
}

/**
 * datatable bulk delete checkbox
 */
if(!function_exists('table_checkbox')){
    function table_checkbox($id){
        return '
            <div class="custom-control custom-checkbox">
                <input type="checkbox" value="'.$id.'" class="custom-control-input select_data" onchange="select_single_item('.$id.')" id="checkbox'.$id.'" />
                <label class="custom-control-label" for-"checkbox'.$id.'"></label>
            </div>
        ';
    }
}

/**
 * data deletable status
 */
define('DELETABLE', ['1' => 'Yes', '2' => 'No']);

/**
 * action button dropdown menu
 */
if(!function_exists('action_button')){
    function action_button($action){
        return '
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-th-list text-white"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        '.$action.'
                    </ul>
                </div>
        ';
    }
}

/**
 * user gender
 */
define('GENDER', ['1' => 'Male', '2' => 'Female']);

/**
 * set user status label
 */
define('STATUS_LABEL', [
    '1' => '<span class="badge bg-success">Active</span>',
    '2' => '<span class="badge bg-danger">Inactive</span>'
]);
define('STATUS', [
    '1' => 'Active',
    '2' => 'Inactive'
]);

/**
 * user avatar path
 */
define('USER_AVATAR_PATH', 'user/');
/**
 * logo path
 */
define('LOGO_PATH', 'logo/');
/**
 * logo path
 */
define('FAVICON_PATH', 'favicon/');

/**
 * change status label
 */
if(!function_exists('change_status')){
    function change_status(int $id,int $status,string $name = null){
        return $status == 1 ? '<span class="badge bg-success change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">Active</span>' : 
        '<span class="badge bg-danger change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">Inactive</span>';
    }
}

/**
 * mailer
 */
define('MAIL_MAILER', ['smtp', 'sendmail', 'mail']);

/**
 * mail encryption
 */
define('MAIL_ENCRYPTION', ['NONE' => 'null', 'TLS' => 'tls', 'SSL' => 'ssl']);
