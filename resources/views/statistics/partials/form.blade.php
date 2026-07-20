<div x-data="statisticForm({{ Js::from($playersByMatch) }}, '{{ old('football_match_id', $statistic?->football_match_id) }}', '{{ old('player_id', $statistic?->player_id) }}')" x-init="syncPlayers()" class="space-y-5">
    <div>
        <label for="football_match_id" class="block text-sm font-medium text-gray-700">Pertandingan</label>
        <select id="football_match_id" name="football_match_id" x-model="matchId" @change="playerId = ''; syncPlayers()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih pertandingan selesai</option>
            @foreach ($matches as $match)
                <option value="{{ $match->id }}">
                    {{ $match->tournament?->name }} - {{ $match->homeClub?->name }} vs {{ $match->awayClub?->name }} ({{ $match->match_date->format('d M Y') }})
                </option>
            @endforeach
        </select>
        @error('football_match_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        @if ($matches->isEmpty())
            <p class="mt-1 text-sm text-gray-600">Belum ada pertandingan selesai. Simpan skor pertandingan terlebih dahulu.</p>
        @endif
    </div>

    <div>
        <label for="player_id" class="block text-sm font-medium text-gray-700">Pemain</label>
        <select id="player_id" name="player_id" x-model="playerId" :disabled="players.length === 0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100">
            <option value="" x-text="matchId ? 'Pilih pemain' : 'Pilih pertandingan dulu'"></option>
            <template x-for="player in players" :key="player.id">
                <option :value="player.id" x-text="`${player.name} - ${player.club}`"></option>
            </template>
        </select>
        @error('player_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="goals" class="block text-sm font-medium text-gray-700">Gol</label>
            <input id="goals" type="number" name="goals" min="0" value="{{ old('goals', $statistic?->goals ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('goals') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="assists" class="block text-sm font-medium text-gray-700">Assist</label>
            <input id="assists" type="number" name="assists" min="0" value="{{ old('assists', $statistic?->assists ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('assists') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="yellow_cards" class="block text-sm font-medium text-gray-700">Kartu Kuning</label>
            <input id="yellow_cards" type="number" name="yellow_cards" min="0" max="2" value="{{ old('yellow_cards', $statistic?->yellow_cards ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('yellow_cards') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="red_cards" class="block text-sm font-medium text-gray-700">Kartu Merah</label>
            <input id="red_cards" type="number" name="red_cards" min="0" max="1" value="{{ old('red_cards', $statistic?->red_cards ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('red_cards') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<script>
    function statisticForm(playersByMatch, initialMatchId, initialPlayerId) {
        return {
            playersByMatch,
            matchId: initialMatchId ? String(initialMatchId) : '',
            playerId: initialPlayerId ? String(initialPlayerId) : '',
            players: [],
            syncPlayers() {
                this.players = this.playersByMatch[this.matchId] || [];
            },
        };
    }
</script>
