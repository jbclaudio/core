<label for="autocomplete-input" class="sr-only">@lang('Search')</label>
<reactive-base
    :app="config.es_prefix + '_products_' + config.store"
    :url="config.es_url"
    v-cloak
>
    <data-search
        placeholder="@lang('Search')"
        v-on:value-selected="search"
        component-id="autocomplete"
        :inner-class="{ input: 'rounded' }"
        :data-field="Object.keys(config.searchable)"
        :field-weights="Object.values(config.searchable)"
        :show-icon="false"
        fuzziness="AUTO"
        :debounce="100"
        :size="9"
    >
        <div
            slot="render"
            slot-scope="{ downshiftProps: { isOpen }, data: suggestions }"
        >
            <ul class="absolute left-2/3 right-auto z-20 bg-white border shadow-xl rounded-b-lg lg:rounded-t-lg w-screen sm:w-full lg:w-[960px] xl:ml-0 sm:left-1/2 transform -translate-x-1/2 xl:rounded-t-lg mt-px flex flex-wrap" v-if="isOpen && suggestions.length">
                <li
                    class="flex w-1/2 sm:w-1/2 md:w-1/3 px-4 my-4"
                    v-for="suggestion in suggestions"
                    :key="suggestion._id"
                >
                    <a :href="suggestion.source.url" class="flex flex-wrap w-full h-full" key="suggestion._id">
                        <picture class="contents">
                            <source :srcset="'/storage/resizes/100x100/catalog/product' + suggestion.source.thumbnail + '.webp'" type="image/webp">
                            <img :src="'/storage/resizes/100x100/catalog/product' + suggestion.source.thumbnail" class="object-contain lg:w-3/12 self-center" />
                        </picture>
                        <div class="px-2 flex flex-wrap flex-grow lg:w-1/2">
                            <strong class="block hyphens w-full">@{{ suggestion.source.name }}</strong>
                            <div class="self-end">@{{ suggestion.source.price | price }}</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </data-search>
</reactive-base>
