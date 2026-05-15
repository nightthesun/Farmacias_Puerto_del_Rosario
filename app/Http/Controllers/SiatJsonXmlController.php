<?php

namespace App\Http\Controllers;

use App\Models\Siat_Json_xml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiatJsonXmlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $index = DB::table('siat__json_xmls')
    ->select('*')
     ->orderBy('id', 'desc')
            ->get(); 
            return $index; 
           
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $nombre=$request->nomGesPrueba;
            $sector=$request->sector;
            $xmlString=$request->xmlGesPrueba;
            // VALIDAR XML
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlString);


        // ARRAY A JSON
        $jsonConvertido = json_encode(
            $xml,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

            $crear = new Siat_Json_xml();
            $crear->nombre = $nombre;
             $crear->sector = $sector;
              $crear->xml = $xmlString;
              $crear->json = $jsonConvertido;
              $crear->modalidad = $request->modalidad;
            $crear->save();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siat_Json_xml $siat_Json_xml)
    {
        try {
            DB::beginTransaction();
            $nombre=$request->nomGesPrueba;
            $sector=$request->sector;
            $xmlString=$request->xmlGesPrueba;
            // VALIDAR XML
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlString);


        // ARRAY A JSON
        $jsonConvertido = json_encode(
            $xml,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

            $a =Siat_Json_xml::findOrFail($request->id);
            $a->nombre = $nombre;
             $a->sector = $sector;
              $a->xml = $xmlString;
              $a->json = $jsonConvertido;
            $a->modalidad = $request->modalidad;

            $a->save();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
             $a = Siat_Json_xml::findOrFail($request->id);
             $a->delete();
            DB::commit();
            return 0;
        } catch (\Throwable $th) {
            return $th;
        }
      
    }

    public function pruebaSiat(Request $request)
    {
        
    }
}
