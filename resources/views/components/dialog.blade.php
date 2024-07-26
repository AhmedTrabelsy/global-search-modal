@php
    use function Filament\Support\prepare_inherited_attributes;
    use Filament\Support\Facades\FilamentAsset;
    $debounce = filament()->getGlobalSearchDebounce();
    $keyBindings = filament()->getGlobalSearchKeyBindings();
    $suffix = filament()->getGlobalSearchFieldSuffix();
    $isNative=$this->getConfigs()->isNative();
@endphp
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}
<div 
    x-ignore 
    ax-load 
    x-load-css="[@js(FilamentAsset::getStyleHref('global-search-modal', 'charrafimed/global-search-modal'))]" 
    x-data="observer"
    >
    <x-global-search-modal::modal>
        @if ($isNative)
            <div
                @class([
                    'max-h-[96px]',
                    'overflow-y-hidden',
                ])
            >
                <x-global-search-modal::search.native.field />
                <div 
                    @class([
                        'h-full  border-white/10 overflow-hidden',
                    ])>

                    @if ($results !== null)
                        <x-global-search-modal::search.native.results :results="$results" />
                    @endif
                </div>
            </div>
        @else
        {{-- not native --}}
        <x-slot:header>
            <form 
                class="relative flex w-full items-center px-1 py-0.5"
                >
                    <label 
                        class="flex h-4 w-4 items-center justify-center text-gray-300/40 dark:text-white/30"
                        id="search-label" 
                        for="search-input"
                        >
                          <x-global-search-modal::icon.search wire:loading.class="hidden"/>
                          <div class="hidden" wire:loading.class.remove="hidden">
                                <x-global-search-modal::icon.loading-indicator/>
                          </div>
                    </label>
                    <x-global-search-modal::search.input 
                        x-data="{}"
                        :attributes="prepare_inherited_attributes(
                        new \Illuminate\View\ComponentAttributeBag([
                            'wire:model.live.debounce.' . $debounce => 'search',
                            'x-mousetrap.global.' .
                            collect($keyBindings)
                                ->map(fn(string $keyBinding): string => str_replace('+', '-', $keyBinding))
                                ->implode('.') => $keyBindings ? 'document.getElementById($id(\'input\')).focus()' : null,
                        ]),
                    )"
                    />
            </form>
        </x-slot:header>

        <x-slot:dropdown>
            {{-- the user start searching --}}
            @unless(empty($search))
                <x-global-search-modal::search.results 
                    :results="$results"
                />
            @else
            <div class="w-full">
                <x-global-search-modal::search.empty-query-text/>
            </div>
            @endunless
        </x-slot:dropdown>

        <x-slot:footer>
            <x-global-search-modal::search.footer/>    
        </x-slot:footer>
        @endif

    </x-global-search-modal::modal>    
</div>

