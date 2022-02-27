<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Api\Logger;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     * 
     * @param array $data
     * @param int $response_key
     * @param int $response_code
     * @param array $response_header
     * @return \Illuminate\Http\Response
     */
    public function generateResult(
        $data,
        int $response_key,
        int $response_code,
        array $response_header = [],
    ) {
        if ($response_key == 1) {
            $key = "SUCCESS";
            $message = [
                "title_idn" => "SUKSES",
                "title_eng" => "SUCCESS",
                "desc_idn"  => "SUKSES",
                "desc_eng"  => "SUCCESS"
            ];
        } else {
            $key = "FAILED";
            $message = [
                "title_idn" => "GAGAL",
                "title_eng" => "FAILED",
                "desc_idn"  => "GAGAL",
                "desc_eng"  => "FAILED"
            ];
        }

        $trace_id = Str::random(15);

        $response = response()->json(
            [
                "timestamp"     => Carbon::now()->format('Y-m-d H:i:s'),
                "trace_id"      => $trace_id,
                "source_system" => "TODO APP",
                "response_key"  => $key,
                'message'       => $message,
                'data'          => $data,
            ],
            $response_code,
            $response_header,
        );


        Logger::create([
            'trace_id'  => $trace_id,
            'result'    => $response->content(),
        ]);

        return $response;
    }
}
