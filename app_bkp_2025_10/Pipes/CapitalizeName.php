<?php

namespace App\Pipes;

use Closure;

class CapitalizeName
{
    public function handle($data, Closure $next)
    {
        $data['name'] = ucfirst($data['name']);
        return $next($data);
    }
}
