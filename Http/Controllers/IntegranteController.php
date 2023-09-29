<?php

namespace App\Http\Controllers;

use App\Models\Integrante;
use Illuminate\Http\Request;

class IntegranteController extends Controller {
    
    private $path = "fotos/integrantes";
   
    public $integrante = [[
        'id' => 7,
        'nome' => 'Christianaaaa',
        'biografia'  => 'CHRISTINAAAAA',
        'foto' => ''
    ]];


    public function __construct() {

        $aux = session('integrante');

        if(!isset($aux)) {
            session(['integrante' => $this->integrante]);
        }
    }

    public function index() {
        
        $data = Integrante::orderBy('nome')->get();
        return view('integrante.index', compact('data'));
    }

    public function create() {
        return view('integrante.create');
    }

    public function store(Request $request) {
        
        // php artisan storage:link
        // Colocar os arquivos de imagem dentro da pasta "/storage/app/public"

        $regras = [
            'nome' => 'required|max:100|min:10',
            'biografia' => 'required|max:1000|min:20',
            'foto' => 'required'
        ];

        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($regras, $msgs);

        if($request->hasFile('foto')) {

            // Insert no Banco
            $reg = new Integrante();
            $reg->nome = $request->nome;
            $reg->biografia = $request->biografia;
            $reg->save();    

            // Upload da Foto
            $id = $reg->id;
            $extensao_arq = $request->file('foto')->getClientOriginalExtension();
            $nome_arq = $id.'_'.time().'.'.$extensao_arq;
            $request->file('foto')->storeAs("public/$this->path", $nome_arq);
            $reg->foto = $this->path."/".$nome_arq;
            $reg->save();
        }
        
        return redirect()->route('integrante.index');
    }

    public function show($id) {
           $aux = session('integrante');

           $indice = array_search($id, array_column($aux, 'id'));

           $dados = $aux[$indice];

           return view('integrante.show', compact('dados'));
        
    }

    public function edit($id) {
       $aux = session('integrante');

       $indice = array_search($id, array_column($aux, 'id'));

       $dados = $aux[$indice];

       return view('integrante.edit', compact('dados'));
    }

    public function update(Request $request, $id) {
        
    }

    public function destroy($id) {
        
    }
}
