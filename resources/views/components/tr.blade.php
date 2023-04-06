<tr x-data class="hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer" role="button"
    @click="items = $el.querySelectorAll('a'); window.location = items[items.length-1].href">
    {{ $slot }}
</tr>
