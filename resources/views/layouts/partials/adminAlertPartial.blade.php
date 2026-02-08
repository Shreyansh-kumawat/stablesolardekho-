 <div id="alertContainer"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-2xl w-full px-4 pointer-events-none">
        <!-- Server-side messages -->
        @if (session('success'))
            <div class="alert-enter bg-white rounded-lg shadow-lg border-l-4 border-green-500 p-3 mb-3 pointer-events-auto"
                role="alert">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-green-900">Success</h3>
                        <p class="text-xs text-green-800 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-1 h-1 bg-green-100 rounded-full overflow-hidden">
                    <div class="progress-bar h-full bg-green-500"></div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-enter bg-white rounded-lg shadow-lg border-l-4 border-red-500 p-3 mb-3 pointer-events-auto"
                role="alert">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-red-900">Error</h3>
                        <p class="text-xs text-red-800 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-1 h-1 bg-red-100 rounded-full overflow-hidden">
                    <div class="progress-bar h-full bg-red-500"></div>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-enter bg-white rounded-lg shadow-lg border-l-4 border-yellow-500 p-3 mb-3 pointer-events-auto"
                role="alert">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-yellow-900">Warning</h3>
                        <p class="text-xs text-yellow-800 mt-0.5">{{ session('warning') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-yellow-600 hover:text-yellow-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-1 h-1 bg-yellow-100 rounded-full overflow-hidden">
                    <div class="progress-bar h-full bg-yellow-500"></div>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="alert-enter bg-white rounded-lg shadow-lg border-l-4 border-blue-500 p-3 mb-3 pointer-events-auto"
                role="alert">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-blue-900">Information</h3>
                        <p class="text-xs text-blue-800 mt-0.5">{{ session('info') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-1 h-1 bg-blue-100 rounded-full overflow-hidden">
                    <div class="progress-bar h-full bg-blue-500"></div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-enter bg-white rounded-lg shadow-lg border-l-4 border-red-500 p-3 mb-3 pointer-events-auto"
                role="alert">
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xs font-semibold text-red-900">Validation Errors</h3>
                        <ul class="mt-1 text-xs text-red-800 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>