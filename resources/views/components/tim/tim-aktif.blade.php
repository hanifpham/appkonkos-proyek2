@props(['team', 'component' => 'layout.tautan-dropdown'])

<form method="POST" action="{{ route('current-team.update') }}" x-data>
    @method('PUT')
    @csrf

    <!-- Hidden Team ID -->
    <input type="hidden" name="team_id" value="{{ $team->id }}">

    <button
        type="submit"
        @class([
            'block w-full text-start transition duration-150 ease-in-out focus:outline-none',
            'px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100' => $component === 'layout.tautan-dropdown',
            'border-l-4 border-transparent py-2 pe-4 ps-3 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800' => $component !== 'layout.tautan-dropdown',
        ])
    >
        <div class="flex items-center">
            @if (Auth::user()->isCurrentTeam($team))
                <svg class="me-2 size-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif

            <div class="truncate">{{ $team->name }}</div>
        </div>
    </button>
</form>
