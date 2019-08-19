<?php

namespace Vimily\LaravelWorkflow\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Boris Koumondji <Vimily@yahoo.fr>
 */
class WorkflowFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'workflow';
    }
}
