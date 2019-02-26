<?php

namespace App\Core\Endpoints;

use App\Core\Response;

use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * ArrayInput represents an input provided as an array.
 *
 * Usage:
 *
 *     $input = new ArrayInput(array('name' => 'foo', 'bar' => 'foobar'));
 *
 * @see Input
 */
class ArrayInput extends Input
{
    protected $rawArguments = [];
    protected $rawOptions = [];

    /**
     * Constructor.
     *
     * @param array           $arguments  An array of arguments
     * @param array           $options    An array of options
     * @param InputDefinition $definition A InputDefinition instance
     */
    public function __construct(array $arguments, array $options, InputDefinition $definition = null)
    {
        $this->rawArguments = $arguments;
        $this->rawOptions = $options;

        parent::__construct($definition);
    }

    public function getFirstArgument()
    {
        throw new \Exception("Not implemented");
    }

    public function hasParameterOption($values, $onlyParams = false)
    {
        throw new \Exception("Not implemented");
    }

    public function getParameterOption($values, $default = false, $onlyParams = false)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * Processes command line arguments.
     */
    protected function parse()
    {
        foreach ($this->rawArguments as $key => $value) {
            $this->addArgument($key, $value);
        }
        foreach ($this->rawOptions as $key => $value) {
            $this->addOption($key, $value);
        }
    }

    /**
     * Adds a option value.
     *
     * @param string $name  The option name
     * @param mixed  $value The value for the option
     *
     * @throws \InvalidArgumentException When option given doesn't exist
     * @throws \InvalidArgumentException When a required value is missing
     */
    private function addOption($name, $value)
    {
        if (!$this->definition->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $name));
        }

        $option = $this->definition->getOption($name);

        if (null === $value) {
            if ($option->isValueRequired()) {
                throw new \InvalidArgumentException(sprintf('The "%s" option requires a value.', $name));
            }

            $value = $option->isValueOptional() ? $option->getDefault() : true;
        }

        $this->options[$name] = $value;
    }

    /**
     * Adds an argument value.
     *
     * @param string $name  The argument name
     * @param mixed  $value The value for the argument
     *
     * @throws \InvalidArgumentException When argument given doesn't exist
     */
    private function addArgument($name, $value)
    {
        if (!$this->definition->hasArgument($name)) {
            throw new \InvalidArgumentException(sprintf('The "%s" argument does not exist.', $name));
        }

        $this->arguments[$name] = $value;
    }

    /**
     * Validates the input.
     *
     * @throws \RuntimeException When not enough arguments are given
     */
    public function validate()
    {
        if (count($this->arguments) < $this->definition->getArgumentRequiredCount()) {
            throw new \RuntimeException(Response::EXCEPTION_NOT_ENOUGH_ARGUMENT);
        }
    }
}
