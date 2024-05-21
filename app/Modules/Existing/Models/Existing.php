<?php

namespace App\Modules\Existing\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Jenislahan\Models\Jenislahan;


class Existing extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'existing';
	protected $fillable   = ['*'];	

	public function jenislahan(){
		return $this->belongsTo(Jenislahan::class,"id_jenislahan","id");
	}

}
