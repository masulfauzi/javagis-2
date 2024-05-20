<?php
namespace App\Modules\BatasAdministrasi\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\BatasAdministrasi\Models\BatasAdministrasi;
use App\Modules\TingkatWilayah\Models\TingkatWilayah;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BatasAdministrasiController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Batas Administrasi";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = BatasAdministrasi::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('BatasAdministrasi::batasadministrasi', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_tingkat_wilayah = TingkatWilayah::all()->pluck('tingkat_wilayah','id');
		
		$data['forms'] = array(
			'id_tingkat_wilayah' => ['Tingkat Wilayah', Form::select("id_tingkat_wilayah", $ref_tingkat_wilayah, null, ["class" => "form-control select2"]) ],
			'nama' => ['Nama', Form::text("nama", old("nama"), ["class" => "form-control","placeholder" => ""]) ],
			'koordinat' => ['Koordinat', Form::textarea("koordinat", old("koordinat"), ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('BatasAdministrasi::batasadministrasi_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_tingkat_wilayah' => 'required',
			'nama' => 'required',
			'koordinat' => 'required',
			
		]);

		$batasadministrasi = new BatasAdministrasi();
		$batasadministrasi->id_tingkat_wilayah = $request->input("id_tingkat_wilayah");
		$batasadministrasi->nama = $request->input("nama");
		$batasadministrasi->koordinat = $request->input("koordinat");
		
		$batasadministrasi->created_by = Auth::id();
		$batasadministrasi->save();

		$text = 'membuat '.$this->title; //' baru '.$batasadministrasi->what;
		$this->log($request, $text, ['batasadministrasi.id' => $batasadministrasi->id]);
		return redirect()->route('batasadministrasi.index')->with('message_success', 'Batas Administrasi berhasil ditambahkan!');
	}

	public function show(Request $request, BatasAdministrasi $batasadministrasi)
	{
		$data['batasadministrasi'] = $batasadministrasi;

		$text = 'melihat detail '.$this->title;//.' '.$batasadministrasi->what;
		$this->log($request, $text, ['batasadministrasi.id' => $batasadministrasi->id]);
		return view('BatasAdministrasi::batasadministrasi_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, BatasAdministrasi $batasadministrasi)
	{
		$data['batasadministrasi'] = $batasadministrasi;

		$ref_tingkat_wilayah = TingkatWilayah::all()->pluck('tingkat_wilayah','id');
		
		$data['forms'] = array(
			'id_tingkat_wilayah' => ['Tingkat Wilayah', Form::select("id_tingkat_wilayah", $ref_tingkat_wilayah, null, ["class" => "form-control select2"]) ],
			'nama' => ['Nama', Form::text("nama", $batasadministrasi->nama, ["class" => "form-control","placeholder" => "", "id" => "nama"]) ],
			'koordinat' => ['Koordinat', Form::text("koordinat", $batasadministrasi->koordinat, ["class" => "form-control","placeholder" => "", "id" => "koordinat"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$batasadministrasi->what;
		$this->log($request, $text, ['batasadministrasi.id' => $batasadministrasi->id]);
		return view('BatasAdministrasi::batasadministrasi_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_tingkat_wilayah' => 'required',
			'nama' => 'required',
			'koordinat' => 'required',
			
		]);
		
		$batasadministrasi = BatasAdministrasi::find($id);
		$batasadministrasi->id_tingkat_wilayah = $request->input("id_tingkat_wilayah");
		$batasadministrasi->nama = $request->input("nama");
		$batasadministrasi->koordinat = $request->input("koordinat");
		
		$batasadministrasi->updated_by = Auth::id();
		$batasadministrasi->save();


		$text = 'mengedit '.$this->title;//.' '.$batasadministrasi->what;
		$this->log($request, $text, ['batasadministrasi.id' => $batasadministrasi->id]);
		return redirect()->route('batasadministrasi.index')->with('message_success', 'Batas Administrasi berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$batasadministrasi = BatasAdministrasi::find($id);
		$batasadministrasi->deleted_by = Auth::id();
		$batasadministrasi->save();
		$batasadministrasi->delete();

		$text = 'menghapus '.$this->title;//.' '.$batasadministrasi->what;
		$this->log($request, $text, ['batasadministrasi.id' => $batasadministrasi->id]);
		return back()->with('message_success', 'Batas Administrasi berhasil dihapus!');
	}

}
