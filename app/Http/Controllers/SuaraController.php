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
        
        $result = (array) json_decode($response);        
        $suara  = (array) $result['chart'];
        return response()->json([            
            'Suara01' => $suara['21'],
            'Suara02' => $suara['22'],
            'last_update' => $result['ts']
        ], 200);
    }
}
