<x-filament::page>
    <img src="{{ auth()->user()->profile_photo_url }}" class="rounded-full w-20 h-20">
    {{ $this->form }}
    <x-filament::button wire:click="save" class="mt-4">
        Guardar cambios
    </x-filament::button>
</x-filament::page>
