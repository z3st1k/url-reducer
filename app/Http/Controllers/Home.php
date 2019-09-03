<?php

namespace App\Http\Controllers;

use App\Rules\CacheValidationRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Home extends Controller
{
    public function index(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'url' => ['required', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
                'key' => ['nullable', new CacheValidationRule()],
                'expire' => 'nullable|numeric'
            ]);


            if ($validator->fails()) {
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $url = $request->input('url');
                $key = $request->input('key');
                $expire = $request->input('expire') ?: 10;

                // If user did not entered uniq key, generate key while it will not be unique.
                if (!$key) {
                    do {
                        $key = Str::random();
                    } while (Cache::has($key));
                }

                Cache::put($key, $url, now()->addMinutes($expire));

                return redirect()->action('Home@success', ['key' => $key]);
            }
        }

        return view('index');
    }

    public function success($key) {
        return view('index', ['url' => url("/{$key}")]);
    }

    public function redirect($key) {
        return Redirect::to(Cache::get($key));
    }
}
