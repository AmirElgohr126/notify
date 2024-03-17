<?php

/*
 * This file is part of notification firebase package .
 *
 * (c) Sean Amir <amirnagy886@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return [
    /*
    |--------------------------------------------------------------------------
    | FIREBASE ID
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your access-tokens. 
    |
    */
    'project-id' => env('FIREBASE_ID'),




    /*
    |--------------------------------------------------------------------------
    | Access token path
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens.
    |
    */
    'access_token_path' => env('FIREBASE_ACCESS_TOKEN_PATH', 'access_token.json'),


    /*
    |--------------------------------------------------------------------------
    | concurrency of request 
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens.
    |
    */
    'concurrency' => env('FIREBASE_CONCURRENCY', 500),

];

