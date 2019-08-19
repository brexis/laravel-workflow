# Laravel workflow

Use the Symfony Workflow component in Laravel
The owner of the library didn't want it, so now it is ours.

### Installation

    composer require Vimily/laravel-workflow

#### For laravel <= 5.7+

Add a ServiceProvider to your providers array in `config/app.php`:

```php
<?php

'providers' => [
    ...
    Vimily\LaravelWorkflow\WorkflowServiceProvider::class,

]
```

Add the `Workflow` facade to your facades array:

```php
<?php
    ...
    'Workflow' => Vimily\LaravelWorkflow\Facades\WorkflowFacade::class,
```

### Configuration

Publish the config file

```
    php artisan vendor:publish --provider="Vimily\LaravelWorkflow\WorkflowServiceProvider"
```

Configure your workflow in `config/workflow.php`

```php
<?php

return [
    'straight'   => [
        'type'          => 'workflow', // or 'state_machine'
        'marking_store' => [
            'type'      => 'multiple_state',
            'arguments' => ['currentPlace']
        ],
        'supports'      => ['App\BlogPost'],
        'places'        => ['draft', 'review', 'rejected', 'published'],
        'transitions'   => [
            'to_review' => [
                'from' => 'draft',
                'to'   => 'review'
            ],
            'publish' => [
                'from' => 'review',
                'to'   => 'published'
            ],
            'reject' => [
                'from' => 'review',
                'to'   => 'rejected'
            ]
        ],
    ]
];
```

Use the `WorkflowTrait` inside supported classes

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Vimily\LaravelWorkflow\Traits\WorkflowTrait;

class BlogPost extends Model
{
  use WorkflowTrait;

}
```
### Usage

```php
<?php

use App\BlogPost;
use Workflow;

$post = BlogPost::find(1);
$workflow = Workflow::get($post);
// if more than one workflow is defined for the BlogPost class
$workflow = Workflow::get($post, $workflowName);

$workflow->can($post, 'publish'); // False
$workflow->can($post, 'to_review'); // True
$transitions = $workflow->getEnabledTransitions($post);

// Apply a transition
$workflow->apply($post, 'to_review');
$post->save(); // Don't forget to persist the state

// Using the WorkflowTrait
$post->workflow_can('publish'); // True
$post->workflow_can('to_review'); // False

// Get the post transitions
foreach ($post->workflow_transitions() as $transition) {
    echo $transition->getName();
}

// Apply a transition
$post->workflow_apply('publish');
$post->save();
```

### Use the events
This package provides a list of events fired during a transition

```php
    Vimily\LaravelWorkflow\Events\Guard
    Vimily\LaravelWorkflow\Events\Leave
    Vimily\LaravelWorkflow\Events\Transition
    Vimily\LaravelWorkflow\Events\Enter
    Vimily\LaravelWorkflow\Events\Entered
```

You can subscribe to an event

```php
<?php

namespace App\Listeners;

use Vimily\LaravelWorkflow\Events\GuardEvent;

class BlogPostWorkflowSubscriber
{
    /**
     * Handle workflow guard events.
     */
    public function onGuard(GuardEvent $event) {
        /** Symfony\Component\Workflow\Event\GuardEvent */
        $originalEvent = $event->getOriginalEvent();

        /** @var App\BlogPost $post */
        $post = $originalEvent->getSubject();
        $title = $post->title;

        if (empty($title)) {
            // Posts with no title should not be allowed
            $originalEvent->setBlocked(true);
        }
    }

    /**
     * Handle workflow leave event.
     */
    public function onLeave($event) {}

    /**
     * Handle workflow transition event.
     */
    public function onTransition($event) {}

    /**
     * Handle workflow enter event.
     */
    public function onEnter($event) {}

    /**
     * Handle workflow entered event.
     */
    public function onEntered($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Vimily\LaravelWorkflow\Events\GuardEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onGuard'
        );

        $events->listen(
            'Vimily\LaravelWorkflow\Events\LeaveEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onLeave'
        );

        $events->listen(
            'Vimily\LaravelWorkflow\Events\TransitionEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onTransition'
        );

        $events->listen(
            'Vimily\LaravelWorkflow\Events\EnterEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEnter'
        );

        $events->listen(
            'Vimily\LaravelWorkflow\Events\EnteredEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEntered'
        );
    }

}
```

### Dump Workflows
Symfony workflow uses GraphvizDumper to create the workflow image. You may need to install the `dot` command of [Graphviz](http://www.graphviz.org/)

    php artisan workflow:dump workflow_name --class App\\BlogPost

You can change the image format with the `--format` option. By default the format is png.

    php artisan workflow:dump workflow_name --format=jpg
