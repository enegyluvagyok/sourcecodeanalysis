<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SecretApi;
use SimpleXMLElement;

class SecretController extends Controller
{

    /**
     * Get the specified secret.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public static function getSecret(Request $request, $hash)
    {
        if ($request->header('accept') == 'application/json') {
            return SecretApi::prepareJsonGetResponse($hash, 'json');
        } else if ($request->header('accept') == 'application/xml') {
            return SecretApi::prepareJsonGetResponse($hash, 'xml');
        }
    }

    /**
     * Create new secret with specified parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public static function postSecret(Request $request)
    {
        if ($request->header('accept') == 'application/json') {
            return SecretApi::prepareJsonPostResponse($request, 'json');
        } else if ($request->header('accept') == 'application/xml') {
            return SecretApi::prepareJsonPostResponse($request, 'xml');
        }
    }
}
