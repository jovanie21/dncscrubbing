<?php

namespace App\Http\Traits;

use Spatie\Dropbox\Client;

trait DropBox
{

    public function upload($path, $content)
    {
        try {
            $client = new Client(env('DROPBOX_OAUTH_TOKEN'));
            $client->upload($path, $content, 'add');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function download($path)
    {
        try {
            $client = new Client(env('DROPBOX_OAUTH_TOKEN'));
            $link = $client->getTemporaryLink($path);
            return $link;
        } catch (Exception $e) {
            return false;
        }
    }
}
