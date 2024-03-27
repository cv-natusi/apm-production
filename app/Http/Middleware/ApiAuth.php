<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Models\Customer;
use App\Http\Models\rsu_customer;
use Auth, Request;

class ApiAuth
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->KodeCust){
            // $cek = Customer::where('KodeCust',$request->KodeCust)->first(); //get data user berdasarkan KodeCust db lokal
            $cek = rsu_customer::where('KodeCust',$request->KodeCust)->first(); //get data user berdasarkan KodeCust db rsu
            /* cek data user*/
            if ($cek) {
                return $next($request);
            } else {
                $return = ['status'=>'unauthorized', 'code'=>401, 'data' => 'Permintaan anda tidak bisa diproses.'];
                return response()->json($return);
            }
        }else {
            $return = ['status'=>'unauthorized', 'code'=>401, 'data' => 'Permintaan anda tidak bisa diproses.'];
            return response()->json($return);
        }
    }
}
