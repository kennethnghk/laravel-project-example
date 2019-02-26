<?php

namespace App\Core\Endpoints;

use App\Core\Response;
use App\Core\Repositories\RepositoryMixin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

abstract class Base
{
    use RepositoryMixin;

    /**
     * The input interface implementation.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    protected $metricKey;

    public function __construct()
    {
        $this->definition = new InputDefinition();

        $this->specifyParameters();
    }

    /**
     * @param $arguments
     * @param $options
     *
     * @return \App\Core\Response
     */
    public static function run($arguments = [], $options = [])
    {
        /**
         * @var \App\Core\Endpoints\Base
         */
        $endpoint = new static();
        $endpoint->setParameters($arguments, $options);

        return $endpoint->fire();
    }

    /**
     * @return array
     */
    public static function getEndpointsArguments()
    {
        return with(new static())->getArguments();
    }

    /**
     * @return array
     */
    public static function getEndpointsOptions()
    {
        return with(new static())->getOptions();
    }

    /**
     * Specify the arguments and options on the command.
     *
     * @return void
     */
    protected function specifyParameters()
    {
        // We will loop through all of the arguments and options for the command and
        // set them all on the base command instance. This specifies what can get
        // passed into these commands as "parameters" to control the execution.
        foreach ($this->getArguments() as $arguments) {
            call_user_func_array([$this, 'addArgument'], $arguments);
        }

        foreach ($this->getOptions() as $options) {
            call_user_func_array([$this, 'addOption'], $options);
        }
    }

    /**
     * Get the value of a command argument.
     *
     * @param string $key
     *
     * @return string|array
     */
    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }

        return $this->input->getArgument($key);
    }

    /**
     * Get the value of a command option.
     *
     * @param string $key
     *
     * @return string|array
     */
    public function option($key = null)
    {
        if (is_null($key)) {
            return $this->input->getOptions();
        }

        return $this->input->getOption($key);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Adds an argument.
     *
     * @param string $name        The argument name
     * @param int    $mode        The argument mode: InputArgument::REQUIRED or InputArgument::OPTIONAL
     * @param string $description A description text
     * @param mixed  $default     The default value (for InputArgument::OPTIONAL mode only)
     *
     * @return $this The current instance
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        $this->definition->addArgument(new InputArgument($name, $mode, $description, $default));

        return $this;
    }

    /**
     * Adds an option.
     *
     * @param string $name        The option name
     * @param string $shortcut    The shortcut (can be null)
     * @param int    $mode        The option mode: One of the InputOption::VALUE_* constants
     * @param string $description A description text
     * @param mixed  $default     The default value (must be null for InputOption::VALUE_REQUIRED or InputOption::VALUE_NONE)
     *
     * @return $this The current instance
     */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        $this->definition->addOption(new InputOption($name, $shortcut, $mode, $description, $default));

        return $this;
    }

    public function setParameters($arguments = [], $options = [])
    {
        $this->input = new ArrayInput($arguments, $options);

        try {
            $this->input->bind($this->definition);
        } catch (\Exception $e) {
        }

        $this->input->validate();

        return $this;
    }

    public function makeResponse()
    {
        $resp = new Response();
        return $resp;
    }


    /**
     * @return \App\Core\Response
     */
    abstract public function fire();
}
