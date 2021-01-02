<?php

namespace Brexis\LaravelWorkflow\Traits;

use Workflow;

/**
 * @author Boris Koumondji <brexis@yahoo.fr>
 */
trait WorkflowTrait
{
    public function workflow_apply($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->apply($this, $transition);
    }

    public function workflow_can($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->can($this, $transition);
    }

    public function workflow_transitions($workflow = null)
    {
        return Workflow::get($this, $workflow)->getEnabledTransitions($this);
    }

    public function getCurrentPlace()
    {
        return $this->{$markingProperty};
    }

    public function setCurrentPlace($currentPlace, $context = [])
    {
        $this->{$markingProperty} = $currentPlace;
    }
}
