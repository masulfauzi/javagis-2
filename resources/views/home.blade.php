<?php
// dd($batas_administrasi)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ config('app.name', 'Laralag') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="{{ asset('assets/plugins/leafletstyledlayercontrol/css/styledLayerControl.css') }}" />



</head>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <a href="{{ route('frontend.index') }}">
                                <h5 class="mb-0"> <i class="bi bi-tornado"></i> {{ config('app.name') }}</h5>
                            </a>
                        </div>
                        <div class="header-top-right">

                            <!-- Burger button responsive -->
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <nav class="main-navbar">
                    <div class="container">
                        <ul>
                            <li class="menu-item">
                                <a href="{{ route('frontend.index') }}" class='menu-link'>
                                    <i class="bi bi-grid-fill"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('login') }}" class='menu-link'>
                                    <i class="bi bi-door-open-fill"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <hr class="m-0 bg-primary">
            </header>
            <div class="content-wrapper container">

                <div class="page-heading">
                    <h3>Selamat Datang!</h3>
                </div>
                <div class="page-content">
                    <section class="row">
                        <div class="col-12 col-lg-">

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        {{-- <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div> --}}
                                        <div class="card-body">
                                            <div id="map" style="height: 500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </section>
                </div>

            </div>

            <footer>
                <div class="container">
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2021 &copy; Mazer</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                    href="https://saugi.me">Saugi</a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    {{-- <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script> --}}
    <script src="{{ asset('assets/js/pages/horizontal-layout.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" ß
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="{{ asset('assets/plugins/leafletstyledlayercontrol/src/styledLayerControl.js') }}"></script>
    <script src="{{ asset('assets/plugins/bing/Bing.js') }}"></script>

    <script>
        // OSM layers
        var osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
        var osmAttrib = 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
        var osm = new L.TileLayer(osmUrl, {
            attribution: osmAttrib
        });

        var googlestreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        




        var map = L.map('map', {
            doubleClickZoom: false,
            layers: [osm],
            cursor: true
        }).setView([-5, 106], 6);

        <?php $no = 1; ?>
        @foreach ($jenis_lahan as $item_jenis_lahan)

            <?php
            $penggunaan_lahan = \App\Modules\PenggunaanLahan\Models\PenggunaanLahan::where('id_jenislahan', $item_jenis_lahan->id)->get();
            // dd($penggunaan_lahan);
            ?>

            @if (count($penggunaan_lahan) > 0)

                var jenis_lahan_{{ $no }} = {
                    "type": "FeatureCollection",
                    "features": [
                        @foreach ($penggunaan_lahan as $item_penggunaan_lahan)
                            {!! $item_penggunaan_lahan->koordinat !!},
                        @endforeach
                    ]
                }

                var layer_jenis_{{ $no }} = L.geoJSON(jenis_lahan_{{ $no }}, {
                    style: {
                        color: "{{ $item_jenis_lahan->warna }}",
                        opacity: "{{ $item_jenis_lahan->opacity / 100 }}",
                        fillColor: "{{ $item_jenis_lahan->warna }}",
                        fillOpacity: "{{ $item_jenis_lahan->opacity / 100 }}",
                    }
                }).bindTooltip("{{ $item_jenis_lahan->jenis_lahan }}").addTo(map);
            @endif



            <?php $no++; ?>
        @endforeach


        <?php $no = 1; ?>
        @foreach ($batas_administrasi as $item_batas_administrasi)

            var batas_administrasi_{{ $no }} = {!! $item_batas_administrasi->koordinat !!}

            var layer_batas_{{ $no }} = L.geoJSON(batas_administrasi_{{ $no }}, {
                style: {
                    color: "{{ $item_batas_administrasi->TingkatWilayah->warna }}",
                    // opacity: "{{ $item_batas_administrasi->TingkatWilayah->opacity / 100 }}",
                    // fillColor: "{{ $item_batas_administrasi->TingkatWilayah->warna }}",
                    fillOpacity: "{{ $item_batas_administrasi->TingkatWilayah->opacity / 100 }}",
                }
            }).bindTooltip("{{ $item_batas_administrasi->nama }}").addTo(map);

            <?php $no++; ?>
        @endforeach

        map.addLayer(osm);

        var baseMaps = [{
            groupName: "Base Maps",
            expanded: true,
            layers: {
                "OpenStreetMaps": osm,
                "Google Streets": googlestreet,
                "Google Satellite": googleSat,
                "Google Terrain": googleTerrain
            }
        }];


        var overlays = [{
            groupName: "Penggunaan Lahan",
            expanded: true,
            layers: {
                <?php $no = 1; ?>
                @foreach ($jenis_lahan as $item_jenis_lahan)

                    <?php
                    $penggunaan_lahan = \App\Modules\PenggunaanLahan\Models\PenggunaanLahan::where('id_jenislahan', $item_jenis_lahan->id)->get();
                    // dd($penggunaan_lahan);
                    ?>

                    @if (count($penggunaan_lahan) > 0)

                        "{{ $item_jenis_lahan->jenis_lahan }}": layer_jenis_{{ $no }},
                    @endif


                    <?php $no++; ?>
                @endforeach
            }
        }, {
            groupName: "Batas Administrasi",
            expanded: true,
            layers: {
                <?php $no = 1; ?>
                @foreach ($batas_administrasi as $item_batas_administrasi)

                    "{{ $item_batas_administrasi->nama }}" : layer_batas_{{ $no }}

                    <?php $no++; ?>
                @endforeach
            }
        }];

        

        var options = {
            container_width: "300px",
            group_maxHeight: "180px",
            //container_maxHeight : "350px", 
            exclusive: false,
            collapsed: true,
            position: 'topright'
        };

        var control = L.Control.styledLayerControl(baseMaps, overlays, options);
        map.addControl(control);

        // test for adding new overlay layers dynamically
        control.addOverlay(corn_bh, "Corn Plant", {
            groupName: "Belo Horizonte"
        });

        //control.removeLayer( corn_sp );

        //control.removeGroup( "Rio de Janeiro");

        control.selectLayer(corn_sp);
        //control.unSelectLayer(corn_sp); 

        control.selectGroup("Rio de Janeiro");
        //control.unSelectGroup("Rio de Janeiro");
    </script>
</body>

</html>
