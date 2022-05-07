<?php

namespace Yajra\DataTables\Html\Tests;

use Illuminate\Support\Arr;
use Mockery as m;
use Yajra\DataTables\Factory;
use Yajra\DataTables\Html\Builder;

function app($instance, $config = [])
{
    switch ($instance) {
        case 'datatables.html':
            $configMock = m::mock('Illuminate\Contracts\Config\Repository');
            $configMock->shouldReceive('get')->andReturn($config);

            return new Builder(
                $configMock,
                m::mock('Illuminate\Contracts\View\Factory'),
                m::mock('Collective\Html\HtmlBuilder')
            );
        case 'config':
            return new Config;
        case 'view':
            return m::mock('Illuminate\Contracts\View\Factory', function ($mock) {
                $mock->shouldReceive('exists')->andReturn(false);
            });
    }

    return new Factory();
}

function view($view = null, array $data = [])
{
    if (! $view) {
        return new BladeView();
    }

    return (new BladeView())->exists($view);
}

/**
 * Blade View Stub.
 */
class BladeView
{
    public function exists($view)
    {
        return false;
    }
}

class Config
{
    public function get($key)
    {
        $keys               = explode('.', $key);
        $config             = require __DIR__ . '/../src/config/config.php';
        $config['builders'] = Arr::add($config['builders'], 'Mockery_8_Illuminate_Database_Query_Builder', 'query');

        return $config[$keys[1]];
    }
}
