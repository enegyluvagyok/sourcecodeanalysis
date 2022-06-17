<?php


namespace App;

use Illuminate\Http\Request;
use App\Models\Secrets;
use SimpleXMLElement;

class SecretApi
{

    public static function prepareJsonGetResponse($hash, $type)
    {
        Secrets::whereRaw('ttl <> created_at and ttl < SYSDATE()')->delete();

        $secret = Secrets::where('hash', $hash)->first();

        if ($secret->viewsnum == 0 || !$secret) {
            Secrets::destroy($secret->id);
            return 'Secret not found';
        } else {
            $secret->viewsnum = $secret->viewsnum - 1;
            $secret->save();

            if ($type == 'json') {
                $response = array(
                    "hash" => $secret->hash,
                    "secretText" => $secret->secret,
                    "createdAt" => $secret->created_at,
                    "expiresAt" => $secret->ttl,
                    "remainingViews" => $secret->viewsnum
                );
                return json_encode($response);
            } else  return self::createXmlResponse($secret);
        }
    }


    public static function prepareJsonPostResponse(Request $request, $type)
    {
        Secrets::whereRaw('ttl <> created_at and ttl < SYSDATE()')->delete();

        $ttl = date("Y-m-d H:i:s");
        if (isset($request->expireAfter) && $request->expireAfter > 0) {
            $ttl = date("Y-m-d H:i:s", time() + $request->expireAfter * 60);
        }

        $secret = Secrets::create(
            [
                'secret' => $request->secret,
                'hash' => md5($request->secret),
                'ttl' => $ttl,
                'viewsnum' => $request->expireAfterViews
            ]
        );

        if ($secret->viewsnum > 0) {
            if ($type == 'json') {
                $response = array(
                    "hash" => $secret->hash,
                    "secretText" => $secret->secret,
                    "createdAt" => $secret->created_at,
                    "expiresAt" => $secret->ttl,
                    "remainingViews" => $secret->viewsnum
                );
                return json_encode($response);
            } else {
                return self::createXmlResponse($secret);
            }
        } else return 'Invalid input';
    }


    public static function createXmlResponse(Secrets $secret)
    {
        $XMLRoot = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Secret></Secret>');

        $XMLRoot->addChild('hash', $secret->hash);
        $XMLRoot->addChild('secretText', $secret->secret);
        $XMLRoot->addChild('createdAt', $secret->created_at);
        $XMLRoot->addChild('expiresAt', $secret->ttl);
        $XMLRoot->addChild('remainingViews', $secret->viewsnum);

        $dom = dom_import_simplexml($XMLRoot)->ownerDocument;
        $dom->formatOutput = true;
        echo $dom->saveXML();
    }
}
