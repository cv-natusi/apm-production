<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Helpers\apm;
use Throwable,Request,Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if(request()->ajax()){
            Handler::log($e);
            // Log::info($e->getMessage());
            Log::info('ajax error');
            // Log::info([$e->getMessage(),'testings']);
            response()->json([
                'success' => false,
                'code'    => 500,
                'message' => 'Internal server error',
            ], 500)->send();die();
        }
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){ # Catch url not found
            Handler::log($e);
            return response(view('errors.custom',[ # Catch url web admin
                'title' => 'Not Found',
                'message' => 'Halaman yang Anda cari tidak ditemukan',
                'detail' => 'Bagaimana Anda sampai di sini adalah sebuah misteri.<br>Tetapi Anda dapat mengklik tombol di bawah untuk kembali ke halaman sebelumnya',
                'code' => 404,
            ]));
        }
        $redis = $e instanceof \Predis\Connection\ConnectionException; # Instance redis connection {catch true or false}
        $database = $e instanceof \PDOException; # Instance database connection {catch true or false}
        if($redis || $database){ # Catch {redis or database} if not connecting
            Handler::log($e);
            $error = $redis?'redis':'database';
            return response(view('errors.custom',[
                'title' => 'Connection refused',
                'message' => "Koneksi $error terputus",
                'detail' => "Harap perbaiki koneksi $error terlebih dahulu.",
                'code' => 500,
            ]))->send();
            die(); # The report ends or stops here
        }

        Handler::log($e);
        return response(view('errors.custom',[
            'title' => 'Internal server error',
            'message' => 'Kami tidak dapat menemukan apa yang terjadi!',
            'detail' => 'Silahkan hubungi admin atau kunjungi halaman ini nanti.',
            'code' => 500,
        ]))->send();
        die();
        return parent::render($request, $e);
    }

    public static function log($e){ # Custom log
        try{
            if(!stripos(Request::url(),'assets')!==false){
                apm::logging([ # Helper
                    'url'     => Request::url(),
                    'file'    => $e->getFile(),
                    'title'   => 'ERROR SYSTEM',
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                ]);
            }
        }catch(\Throwable $e){ # Lakukan logging manual jika helper terjadi error
            Log::info(
                json_encode([
                    'ERROR SYSTEM' => [
                        'url'     => Request::url(),
                        'file'    => $e->getFile(),
                        'message' => $e->getMessage(),
                        'line'    => $e->getLine(),
                    ]
                ],JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
            );
        }
    }
}
