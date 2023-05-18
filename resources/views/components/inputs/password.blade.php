

    <div x-data="{type: 'password', text: 'show'}" class="relative w-full">
        <div class="absolute inset-y-0 right-0 flex items-center px-2">
            <input
                @click="
                            if (type === 'password') {
                                type = 'text';
                                text = 'hide';
                            } else {
                                type = 'password';
                                text = 'show';
                            }
                        "
                class="hidden js-password-toggle" id="toggle" type="checkbox"
            />
            <label
                class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-password-label"
                for="toggle" x-text="text"></label>
        </div>
        <input {{$attributes->merge(['class' => 'appearance-none border-2 rounded w-full py-3 px-3 leading-tight border-gray-300 bg-gray-100 focus:outline-none
                    focus:border-indigo-700 focus:bg-white text-gray-700 pr-16 font-mono js-password'])}}
            :type="type"
            autocomplete="off"
        />
    </div>

