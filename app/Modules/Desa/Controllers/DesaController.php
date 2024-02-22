<?php
namespace App\Modules\Desa\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Desa\Models\Desa;
use App\Modules\Kecamatan\Models\Kecamatan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DesaController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Desa";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Desa::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Desa::desa', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_kecamatan = Kecamatan::all()->pluck('id_kabupaten','id');
		
		$data['forms'] = array(
			'id_kecamatan' => ['Kecamatan', Form::select("id_kecamatan", $ref_kecamatan, null, ["class" => "form-control select2"]) ],
			'kode' => ['Kode', Form::text("kode", old("kode"), ["class" => "form-control","placeholder" => "", "required" => "required"]) ],
			'nama_desa' => ['Nama Desa', Form::text("nama_desa", old("nama_desa"), ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Desa::desa_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_kecamatan' => 'required',
			'kode' => 'required',
			'nama_desa' => 'required',
			
		]);

		$desa = new Desa();
		$desa->id_kecamatan = $request->input("id_kecamatan");
		$desa->kode = $request->input("kode");
		$desa->nama_desa = $request->input("nama_desa");
		
		$desa->created_by = Auth::id();
		$desa->save();

		$text = 'membuat '.$this->title; //' baru '.$desa->what;
		$this->log($request, $text, ['desa.id' => $desa->id]);
		return redirect()->route('desa.index')->with('message_success', 'Desa berhasil ditambahkan!');
	}

	public function show(Request $request, Desa $desa)
	{
		$data['desa'] = $desa;

		$text = 'melihat detail '.$this->title;//.' '.$desa->what;
		$this->log($request, $text, ['desa.id' => $desa->id]);
		return view('Desa::desa_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Desa $desa)
	{
		$data['desa'] = $desa;

		$ref_kecamatan = Kecamatan::all()->pluck('id_kabupaten','id');
		
		$data['forms'] = array(
			'id_kecamatan' => ['Kecamatan', Form::select("id_kecamatan", $ref_kecamatan, null, ["class" => "form-control select2"]) ],
			'kode' => ['Kode', Form::text("kode", $desa->kode, ["class" => "form-control","placeholder" => "", "required" => "required", "id" => "kode"]) ],
			'nama_desa' => ['Nama Desa', Form::text("nama_desa", $desa->nama_desa, ["class" => "form-control","placeholder" => "", "id" => "nama_desa"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$desa->what;
		$this->log($request, $text, ['desa.id' => $desa->id]);
		return view('Desa::desa_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_kecamatan' => 'required',
			'kode' => 'required',
			'nama_desa' => 'required',
			
		]);
		
		$desa = Desa::find($id);
		$desa->id_kecamatan = $request->input("id_kecamatan");
		$desa->kode = $request->input("kode");
		$desa->nama_desa = $request->input("nama_desa");
		
		$desa->updated_by = Auth::id();
		$desa->save();


		$text = 'mengedit '.$this->title;//.' '.$desa->what;
		$this->log($request, $text, ['desa.id' => $desa->id]);
		return redirect()->route('desa.index')->with('message_success', 'Desa berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$desa = Desa::find($id);
		$desa->deleted_by = Auth::id();
		$desa->save();
		$desa->delete();

		$text = 'menghapus '.$this->title;//.' '.$desa->what;
		$this->log($request, $text, ['desa.id' => $desa->id]);
		return back()->with('message_success', 'Desa berhasil dihapus!');
	}

}
