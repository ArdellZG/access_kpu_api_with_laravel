<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SuaraController extends Controller
{
    public function extractAPI()
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );          
        $response = file_get_contents("https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json", false, stream_context_create($arrContextOptions));
        
        $result_array = (array) json_decode($response);
        $result = (array) $result_array['chart'];

        return response()->json([            
            'Suara01' => $result['21'],
            'Suara02' => $result['22'],
        ], 200);
    }
}
