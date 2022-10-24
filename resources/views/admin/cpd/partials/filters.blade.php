<span v-for="(filterKey,filter) in filters">
    <label for="@{{ filter.id }}">
        <small>@{{ filter.title }}</small>
    </label>
    <select id="@{{ filter.id }}" v-model="filters[filterKey].selected"
            class="form-control">
        <option v-for="(optionKey, option) in filter.options" value="@{{ option }}">@{{ optionKey }}</option>
    </select>
    &nbsp;
</span>