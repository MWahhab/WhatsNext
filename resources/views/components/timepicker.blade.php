@props([
    "id"   => 1,
    "name" => "timepicker"
    ])

<label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time</label>
<div class="flex repeat-off">
    <input type="time" id="{{ $id }}" name="{{  $name }}"
           class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
           value="00:00" required>
</div>
