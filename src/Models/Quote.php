<?php

namespace Rapidez\Core\Models;

use Rapidez\Core\Casts\QuoteItems;
use Rapidez\Core\Models\Model;
use Rapidez\Core\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Builder;

class Quote extends Model
{
    protected $table = 'quote';

    protected $primaryKey = 'entity_id';

    protected $casts = [
        'items' => QuoteItems::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
        static::addGlobalScope('with-all-information', function (Builder $builder) {
            $builder
                ->select([
                    'quote.entity_id',
                    'items_count',
                    'items_qty',
                ])
                ->selectRaw('
                    MAX(quote_address.subtotal_incl_tax) as subtotal,
                    MAX(quote_address.tax_amount) as tax,
                    MAX(quote_address.grand_total) as total,
                    MAX(quote_address.discount_amount) as discount_amount,
                    MAX(quote_address.discount_description) as discount_name
                ')
                ->selectRaw('JSON_REMOVE(JSON_OBJECTAGG(IFNULL(quote_item.item_id, "null__"), JSON_OBJECT(
                    "item_id", quote_item.item_id,
                    "product_id", quote_item.product_id,
                    "sku", quote_item.sku,
                    "name", quote_item.name,
                    "image", product.thumbnail,
                    "url_key", product.url_key,
                    "qty", quote_item.qty,
                    "price", quote_item.price_incl_tax,
                    "total", quote_item.row_total_incl_tax,
                    "attributes", quote_item_option.value,
                    "type", quote_item.product_type
                )), "$.null__") AS items')
                ->leftJoin('quote_id_mask', 'quote_id_mask.quote_id', '=', 'quote.entity_id')
                ->leftJoin('oauth_token', 'oauth_token.customer_id', '=', 'quote.customer_id')
                ->leftJoin('quote_address', function ($join) {
                    $join->on('quote_address.quote_id', '=', 'quote.entity_id');
                })
                ->leftJoin('quote_item', function ($join) {
                    $join->on('quote_item.quote_id', '=', 'quote.entity_id')->whereNull('parent_item_id');
                })
                ->leftJoin('quote_item_option', function ($join) {
                    $join->on('quote_item.item_id', '=', 'quote_item_option.item_id')->where('code', 'attributes');
                })
                ->leftJoin('catalog_product_flat_'.config('rapidez.store').' AS product', 'product.entity_id', '=', 'quote_item.product_id')
                ->groupBy('quote.entity_id');
        });
    }
}
