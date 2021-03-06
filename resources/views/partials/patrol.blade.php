<patrol :is-enabled="isEnabled && isSelectedTab('patrol')"
        :building="building"
        :grid="grid"
        :close="close"
        :planet="planet"
        :data="data"
        store-url="{{ route('api_movement_patrol_store', '__grid__') }}" inline-template>
    <div v-if="isEnabled" class="patrol">
        @include('partials.support')
    </div>
</patrol>
