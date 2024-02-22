<?php

namespace App\Modules\Kecamatan\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Kabupaten\Models\Kabupaten;


class Kecamatan extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'kecamatan';
	protected $fillable   = ['*'];	

	public function kabupaten(){
		return $this->belongsTo(Kabupaten::class,"id_kabupaten","id");
	}

}
