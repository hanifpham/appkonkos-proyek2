@props(['rows' => 5, 'cols' => 5])

<tbody {{ $attributes->merge(['class' => 'divide-y divide-gray-100 dark:divide-gray-700 animate-pulse']) }}>
    @for ($i = 0; $i < $rows; $i++)
        <tr>
            @for ($j = 0; $j < $cols; $j++)
                <td class="px-6 py-5">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                </td>
            @endfor
        </tr>
    @endfor
</tbody>
