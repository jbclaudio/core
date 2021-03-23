<?php

namespace Rapidez\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Rapidez\Core\Models\Attribute;
use Rapidez\Core\Models\Config;

class ValidateCommand extends Command
{
    protected $signature = 'rapidez:validate';

    protected $description = 'Validates all settings';

    public function handle()
    {
        $this->call('cache:clear');

        if (!Config::getCachedByPath('catalog/frontend/flat_catalog_category', 0) || !Config::getCachedByPath('catalog/frontend/flat_catalog_product', 0)) {
            $this->error('The flat tables are disabled!');
        }

        $nonFlatAttributes = Arr::pluck(Attribute::getCachedWhere(function ($attribute) {
            return !$attribute['flat'] && ($attribute['listing'] || $attribute['filter'] || $attribute['productpage']);
        }), 'code');

        if (!empty($nonFlatAttributes)) {
            $this->warn('Not all attributes are in the flat table: '.PHP_EOL.'- '.implode(PHP_EOL.'- ', $nonFlatAttributes));
        }

        $superAttributesCount = count(Attribute::getCachedWhere(fn ($attribute) => $attribute['super']));
        $joinCount = ($superAttributesCount * 2) + (count($nonFlatAttributes) * 3) + 4;

        if ($joinCount > 61) {
            $this->error('Most likely the queries needed for Rapidez will exceed 61 joins when indexing or viewing products so you have to reduce them by adding more attributes to the flat tables');
        }

        $this->info('The validation finished, if there where any errors; fix them before you continue. See: https://rapidez.io/docs/0.x/installation#flat-tables');
    }
}
