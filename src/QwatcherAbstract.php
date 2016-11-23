<?php namespace Maqe\Qwatcher;

use Illuminate\Database\Eloquent\Builder;

abstract class QwatchersAbstract
{
    protected $statusable = [];

    protected $queryable = [];

    protected $sortColumn = '';

    protected $sortOrder = '';

    protected $sortable = [];

    protected $limit = null;

    /**
     * Apply available condition from bilder
     *
     * @param  Builder $builder The tracks builder
     * @return Builder
     */
    protected function queryApplies(Builder $builder)
    {
        foreach ($this->queryable as $query) {
            $methodName = 'apply'.ucfirst($query);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}($builder);
            }
        }
    }
}
