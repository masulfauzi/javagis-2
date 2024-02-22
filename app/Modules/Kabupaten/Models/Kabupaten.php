<?php

namespace App\Modules\Kabupaten\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Provinsi\Models\Provinsi;


class Kabupaten extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'kabupaten';
	protected $fillable   = ['*'];	

	public function provinsi(){
		return $this->belongsTo(Provinsi::class,"id_provinsi","id");
	}

}
