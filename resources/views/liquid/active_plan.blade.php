
@if ( $activePlan != null )
    <div class="mt-0 mb-1 active-plan mr-auto rounded-full bg-zinc-600 w-auto flex items-center p-1">
        <span class="font-semibold sm:text-xs text-sm bg-white text-dark tracking-wide text-xs w-fit m-1 inline-block rounded-full py-1 px-2">{{ $activePlan->name }}</span>
        <span class="w-fit mr-2 ml-1 sm:w-fit text-white sm:text-xs text-sm text-indigo-lightest">{{ $activePlan->interval }}</span>
    </div>
@endif

@if ( $activePlan == null )
    <div class="mt-0 mb-1 active-plan mr-auto rounded-full bg-zinc-600 w-auto flex items-center p-1">
        <span class="font-semibold sm:text-xs text-sm bg-white text-dark tracking-wide text-xs w-fit m-1 inline-block rounded-full py-1 px-2">Free Plan</span>
        <span class="w-fit mr-2 ml-1 sm:w-fit text-white sm:text-xs text-sm text-indigo-lightest">Life Time</span>
    </div>
@endif
