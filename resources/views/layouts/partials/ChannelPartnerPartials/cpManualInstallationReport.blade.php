@if(auth()->user()->hasCpPermission('manual_installations'))
<button type="button" data-toggle="submenu-manageManualInstallation"
    class="w-full flex items-center justify-between px-3 py-2 rounded-md transition-colors hover:bg-slate-800 {{ request()->routeIs('newManualEntry','myManualEntries') ? 'bg-slate-700 text-white' : '' }}"
    data-submenu="manageManualInstallation">
    <span class="flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1。109v1。094c0 .55-.397 1。02-.94 1。11l-.893。149c-.425。07-.765。383-.93。78-.165。398-.143。854。107 1。204l。527。738c。32。447。269 1。06-.12 1。45l-.774。773a１。１２５ １。１２５ ０ ０１-１。４４９。１２l-.７３８-．５２７c-．３５-．２５-．８０６-．２７２-１。２０３-．１０７-．３９７。１６５-．７１。５０５-．７８１。９２９l-．１４９。８９４c-．０９。５４２-．５６。９４-１。１１。９４h-１。０９４c-．５５ ０-１。０１９-．３９８-１。１１-．９４l-．１４８-．８９４c-．０７１-．４２４-．３８４-．７６４-．７８１-．９３-．３９８-．１６４-．８５４-．１４２-１。２０４。１０８l-．７３８。５２７c-．４４７。３２-１。０６。２６９-１。４５-" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M₁₅ ₁₂a₃ ₃ ₀ ₁₁₋₆ ₀ ₃ ₃ ₀ ₀₁₆" />
        </svg>
        <span>Manual Installation</span>
    </span>
    <svg class="w-4 h-4 transition-transform {{ request()->routeIs('newManualEntry','myManualEntries') ? 'rotate-180' : '' }}"
        data-arrow="submenu-manageManualInstallation" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
    </svg>
</button>
<ul id="submenu-manageManualInstallation"
    class="ml-8 mt-1 space-y-1 {{ request()->routeIs('newManualEntry','myManualEntries') ? '' : 'hidden' }}">
    <li>
        <a href="{{ route('newManualEntry') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('newManualEntry') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            <span>New Manual Installation</span>
        </a>
    </li>

      <li>
        <a href="{{ route('myManualEntries') }}"
            class="flex items-center gap-2 px-3 py-1.5 rounded transition-colors {{ request()->routeIs('myManualEntries') ? 'bg-slate-700 text-white' : 'hover:bg-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            <span>Manual Installation Report</span>
        </a>
    </li>




</ul>
@endif