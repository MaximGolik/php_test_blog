<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\ArticleNotTranslatedException;
use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class HandleExceptionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            Log::info('запрос прошёл через кастомного посредника');
            return $next($request);
        }
        catch (RouteNotFoundException $e) {
            $token = $request->bearerToken();

            // auth:sanctum в случае запроса без токена кидает это исключение, пока обрабатывается так
            if (empty($token)) {
                return response([
                    'message' => 'No access token in request',
                ], 401);
            }
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
        catch (ArticleNotTranslatedException $e) {
            return response([
                'message' => $e->getMessage(),
                'deepl_error' => $e->getDetails(),
                'target_lang' => $request->get('lang'),
                'code' => $e->getCode(),
            ], 404);
        }
        catch (HttpException $e) {
            return response([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
        catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }
}
