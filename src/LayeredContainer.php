<?php namespace E1Stuff\LayeredContainer;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * Container that extends another container.
 * All getters check if parent (inner) container has that service after checking this (outer) container.
 * All setters always affect only this (outer)
 *
 * .----------------------------------.
 * | Outer container (this)           |
 * |   .--------------------------.   |
 * |   | Inner container (parent) |   |
 * |   '--------------------------'   |
 * '----------------------------------'
 */
class LayeredContainer extends Container implements ContainerContract
{
    /** @var ContainerContract (inner, parent container) */
    private $parentContainer;

    /**
     * @param ContainerContract $extended
     */
    function __construct(ContainerContract $extended)
    {
        $this->parentContainer = $extended;
    }

    /**
     * Determine if the given type has been bound.
     *
     * @param  string $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return parent::bound($abstract) || $this->parentContainer->bound($abstract);
    }

    /**
     * Resolve all of the bindings for a given tag.
     *
     * @param  array $tag
     * @return array
     */
    public function tagged($tag)
    {
        return array_merge(parent::tagged($tag), $this->parentContainer->tagged($tag));
    }

    /**
     * Register a binding if it hasn't already been registered.
     *
     * @param  string $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool $shared
     * @return void
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        if ( ! $this->parentContainer->bound($abstract) && ! $this->bound($abstract)) {
            parent::bindIf($abstract, $concrete, $shared);
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @param  array $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        if (parent::bound($abstract)) {
            return parent::make($abstract, $parameters);

        } elseif ($this->parentContainer->bound($abstract)) {
            return $this->parentContainer->make($abstract, $parameters);

        } else {
            return parent::make($abstract, $parameters);
        }
    }

    /**
     * Determine if the given type has been resolved.
     *
     * @param  string $abstract
     * @return bool
     */
    public function resolved($abstract)
    {
        return parent::resolved($abstract) || $this->parentContainer->resolved($abstract);
    }
}