<?php
namespace App\Modules\TingkatWilayah\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\TingkatWilayah\Models\TingkatWilayah;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TingkatWilayahController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Tingkat Wilayah";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = TingkatWilayah::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('TingkatWilayah::tingkatwilayah', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'tingkat_wilayah' => ['Tingkat Wilayah', Form::text("tingkat_wilayah", old("tingkat_wilayah"), ["class" => "form-control","placeholder" => ""]) ],
			'warna' => ['Warna', Form::text("warna", old("warna"), ["class" => "form-control colorpicker","placeholder" => ""]) ],
			'opacity' => ['Opacity', Form::range("opacity", old("opacity"), ["class" => "form-range","min" => "0", "max" => "100", "placeholder" => "", "required" => "required"]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('TingkatWilayah::tingkatwilayah_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'tingkat_wilayah' => 'required',
			'warna' => 'required',
			'opacity' => 'required',
			
		]);

		$tingkatwilayah = new TingkatWilayah();
		$tingkatwilayah->tingkat_wilayah = $request->input("tingkat_wilayah");
		$tingkatwilayah->warna = $request->input("warna");
		$tingkatwilayah->opacity = $request->input("opacity");
		
		$tingkatwilayah->created_by = Auth::id();
		$tingkatwilayah->save();

		$text = 'membuat '.$this->title; //' baru '.$tingkatwilayah->what;
		$this->log($request, $text, ['tingkatwilayah.id' => $tingkatwilayah->id]);
		return redirect()->route('tingkatwilayah.index')->with('message_success', 'Tingkat Wilayah berhasil ditambahkan!');
	}

	public function show(Request $request, TingkatWilayah $tingkatwilayah)
	{
		$data['tingkatwilayah'] = $tingkatwilayah;

		$text = 'melihat detail '.$this->title;//.' '.$tingkatwilayah->what;
		$this->log($request, $text, ['tingkatwilayah.id' => $tingkatwilayah->id]);
		return view('TingkatWilayah::tingkatwilayah_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, TingkatWilayah $tingkatwilayah)
	{
		$data['tingkatwilayah'] = $tingkatwilayah;

		
		$data['forms'] = array(
			'tingkat_wilayah' => ['Tingkat Wilayah', Form::text("tingkat_wilayah", $tingkatwilayah->tingkat_wilayah, ["class" => "form-control","placeholder" => "", "id" => "tingkat_wilayah"]) ],
			'warna' => ['Warna', Form::text("warna", $tingkatwilayah->warna, ["class" => "form-control colorpicker","placeholder" => "", "id" => "warna"]) ],
			'opacity' => ['Opacity', Form::range("opacity", $tingkatwilayah->opacity, ["class" => "form-range","min" => "0", "max" => "100","placeholder" => "", "id" => "opacity"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$tingkatwilayah->what;
		$this->log($request, $text, ['tingkatwilayah.id' => $tingkatwilayah->id]);
		return view('TingkatWilayah::tingkatwilayah_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'tingkat_wilayah' => 'required',
			'warna' => 'required',
			'opacity' => 'required',
			
		]);
		
		$tingkatwilayah = TingkatWilayah::find($id);
		$tingkatwilayah->tingkat_wilayah = $request->input("tingkat_wilayah");
		$tingkatwilayah->warna = $request->input("warna");
		$tingkatwilayah->opacity = $request->input("opacity");
		
		$tingkatwilayah->updated_by = Auth::id();
		$tingkatwilayah->save();


		$text = 'mengedit '.$this->title;//.' '.$tingkatwilayah->what;
		$this->log($request, $text, ['tingkatwilayah.id' => $tingkatwilayah->id]);
		return redirect()->route('tingkatwilayah.index')->with('message_success', 'Tingkat Wilayah berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$tingkatwilayah = TingkatWilayah::find($id);
		$tingkatwilayah->deleted_by = Auth::id();
		$tingkatwilayah->save();
		$tingkatwilayah->delete();

		$text = 'menghapus '.$this->title;//.' '.$tingkatwilayah->what;
		$this->log($request, $text, ['tingkatwilayah.id' => $tingkatwilayah->id]);
		return back()->with('message_success', 'Tingkat Wilayah berhasil dihapus!');
	}

}
