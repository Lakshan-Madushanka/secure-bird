<nav x-data="{showMenu:false}" class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div @click="showMenu = !showMenu" class="relative flex h-16 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button type="button"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!--
                      Icon when menu is closed.

                      Menu open: "hidden", Menu closed: "block"
                    -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <!--
                      Icon when menu is open.

                      Menu open: "block", Menu closed: "hidden"
                    -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 items-center justify-end sm:items-stretch sm:justify-start">
                <div class="flex flex-shrink-0 items-center">
                    <a href="{{route('home')}}">
                        <img class="block h-8 w-auto lg:hidden" src="{{asset('images/favicon.svg')}}" alt="Main logo">
                    </a>
                    <a href="{{route('home')}}">
                        <img class="hidden h-8 w-auto lg:block" src="{{asset('images/favicon.svg')}}" alt="Main logo">
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:block">
                    <div class="flex space-x-4">
                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                        <a
                            href="{{route('home')}}"
                            @class([
                            'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium',
                            'active' => request()->routeIs('home')
                            ])
                            aria-current="{{request()->routeIs('home') ?'page' : ''}}"
                        >
                            Get Link
                        </a>
                        <a
                            href="#"
                            @class([
                            'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium',
                            ])
                            onclick="Livewire.emit('openModal', 'show-message-form')"
                        >
                            Show Messages
                        </a>
                    </div>
                </div>
            </div>
            <div class="hidden sm:flex absolute inset-y-0 right-0 items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 space-x-2">
                <a
                    href="{{route('howItWorks')}}"
                    @class([
                    'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium',
                    'active' => request()->routeIs('howItWorks')
                    ])
                    aria-current="{{request()->routeIs('howItWorks') ? 'how it works' : ''}}"
                >
                    How It Works
                </a>
                <a
                    href="{{route('about')}}"
                    @class([
                    'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium',
                    'active' => request()->routeIs('about')
                    ])
                    aria-current="{{request()->routeIs('about') ? 'about' : ''}}"
                >
                    About
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="showMenu" class="sm:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a
                href="#"
                @class([
                'text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium',
                'active' => request()->routeIs('home')
                ])
                aria-current="{{request()->routeIs('home') ?'page' : ''}}"
            >
                Get Link
            </a>
            <a
                href="#"
                @class([
                'text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium',
                'active' => request()->routeIs('home')
                ])
                aria-current="{{request()->routeIs('home') ?'page' : ''}}"
            >
                Show Messages
            </a>
            <a
                href="#"
                @class([
                'text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium',
                'active' => request()->routeIs('home')
                ])
                aria-current="{{request()->routeIs('home') ?'page' : ''}}"
            >
                How It Works
            </a>
            <a
                href="#"
                @class([
                'text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium',
                'active' => request()->routeIs('home')
                ])
                aria-current="{{request()->routeIs('home') ?'page' : ''}}"
            >
                About
            </a>
        </div>
    </div>
</nav>
