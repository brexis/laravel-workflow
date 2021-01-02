<?php

namespace Brexis\LaravelWorkflow\Traits;

use Workflow;

/**
 * @author Boris Koumondji <brexis@yahoo.fr>
 */
trait WorkflowTrait
{
    protected $markingProperty = 'status';
    
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

    /**
     * Get the marking property for class
     *
     * @return string
     */
    public function getMarking()
    {
        return $this->{$this->markingProperty};
    }

    /**
     * Set the marking property for class
     *
     * @param [type] $currentPlace
     * @param array $context
     * @return void
     */
    public function setMarking($currentPlace, $context = [])
    {
        $this->{$this->markingProperty} = $currentPlace;
    }
}
