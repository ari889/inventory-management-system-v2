<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_name', 'deletable'];

    /**
     * menuItems with module one to many relationship
     */
    public function menuItems(){
        return $this->hasMany(Module::class)->doesntHave('parent')->orderBy('order', 'asc');
    }
}
