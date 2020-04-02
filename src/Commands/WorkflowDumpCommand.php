<?php

namespace Brexis\LaravelWorkflow\Commands;

use Config;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Workflow;

/**
 * @author Boris Koumondji <brexis@yahoo.fr>
 */
class WorkflowDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:dump
        {workflow : name of workflow from configuration}
        {--class= : the support class name}
        {--format=png : the image format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GraphvizDumper dumps a workflow as a graphviz file.
        You can convert the generated dot file with the dot utility (http://www.graphviz.org/):';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $workflowName = $this->argument('workflow');
        $format = $this->option('format');
        $class = $this->option('class');
        $config = Config::get('workflow');

        if (!isset($config[$workflowName])) {
            throw new Exception("Workflow $workflowName is not configured.");
        }

        if (false === array_search($class, $config[$workflowName]['supports'])) {
            throw new Exception("Workflow $workflowName has no support for class $class.".
                ' Please specify a valid support class with the --class option.');
        }

        $subject = new $class;
        $workflow = Workflow::get($subject, $workflowName);
        $definition = $workflow->getDefinition();

        $dumper = new GraphvizDumper();

        $dotCommand = "dot -T$format -o $workflowName.$format";

        $process = new Process($dotCommand);
        $process->setInput($dumper->dump($definition, null, $config[$workflowName]['options'] ?? []));
        $process->mustRun();
    }
}
