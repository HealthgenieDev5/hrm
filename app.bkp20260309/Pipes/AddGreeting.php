<?php

namespace App\Pipes;

use Closure;

class AddGreeting
{
    public function handle($data, Closure $next)
    {
        $data['greeting'] = "Hello, {$data['name']}!";
        return $next($data);
    }
}
