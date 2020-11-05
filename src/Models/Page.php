<?php

namespace Rapidez\Core\Models;

use Rapidez\Core\Models\Model;
use Rapidez\Core\Models\Scopes\ForCurrentStoreScope;
use Rapidez\Core\Models\Scopes\IsActiveScope;
use Rapidez\Core\Models\Traits\HasContentAttributeWithVariables;

class Page extends Model
{
    use HasContentAttributeWithVariables;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_page';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'page_id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new IsActiveScope);
        static::addGlobalScope(new ForCurrentStoreScope);
    }
}
