<swatch-filter v-else-if="filter.text_swatch || filter.visual_swatch" v-slot="slotProps">
    <multi-list
        :component-id="filter.code"
        :data-field="filter.code"
        :inner-class="{
            title: 'capitalize font-semibold',
            list: '!max-h-full flex flex-wrap',
        }"
        :title="filter.name.replace('_', ' ')"
        :react="{and: reactiveFilters}"
        :query-format="filter.input == 'multiselect' ? 'and' : 'or'"
        :show-search="false"
        :show-checkbox="false"
        u-r-l-params
    >
        <div
            v-if="filter.visual_swatch"
            slot="renderItem"
            slot-scope="{ label, isChecked }"
            class="w-6 h-6 border-black mr-1 mb-1 rounded-full hover:opacity-75"
            :class="isChecked ? 'border-2' : 'border'"
            :style="{ background: slotProps.swatches[filter.code].options[label].swatch }"
        >
            <x-heroicon-o-check v-if="isChecked"/>
        </div>

        <div
            v-else
            slot="renderItem"
            slot-scope="{ label, isChecked }"
            class="border-black mr-1 mb-1 px-3 hover:opacity-75"
            :class="isChecked ? 'border-2' : 'border'"
            :style="{ background: slotProps.swatches[filter.code].options[label].swatch }"
        >
            @{{ slotProps.swatches[filter.code].options[label].swatch }}
        </div>
    </multi-list>
</swatch-filter>
