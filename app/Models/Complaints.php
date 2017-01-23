<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    protected $table = 'complaints';

    public function multiOptions($keys)
    {
        if ( request()->input('action') == 'notSee' ) {
            Complaints::whereIn('id', $keys)
                    ->update(['status' => 2]);
        } elseif ( request()->input('action') == 'inProcess' ) {
            Complaints::whereIn('id', $keys)
                    ->update(['status' => 1]);
        } elseif ( request()->input('action') == 'closed' ) {
            Complaints::whereIn('id', $keys)
                    ->update(['status' => 0]);
        } elseif ( request()->input('action') == 'delete' ) {
            Complaints::whereIn('id', $keys)
                      ->delete();
        }
    }
}
