<?php
/**
 * User: martyn ling <mling@str8-4ward.com>
 * Date: 25/08/14
 * Time: 21:12
 */

use Illuminate\Hashing\HashServiceProvider;
use \BotangleHasher;

class BotangleHashProvider extends HashServiceProvider {

    public function boot()
    {
        App::bindShared('hash', function()
            {
                return new BotangleHasher;
            });

        parent::boot();
    }

}
