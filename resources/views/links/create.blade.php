<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Создать короткую ссылку
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('links.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="original_url" value="Оригинальный URL" />
                            <x-text-input
                                id="original_url"
                                name="original_url"
                                type="url"
                                class="mt-1 block w-full"
                                :value="old('original_url')"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('links.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">
                                Назад к списку
                            </a>

                            <x-primary-button>
                                Создать
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
