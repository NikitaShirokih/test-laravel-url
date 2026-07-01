<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Короткая ссылка
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-6 text-gray-900">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Оригинальный URL</dt>
                            <dd class="mt-1 break-all text-sm text-gray-900">{{ $shortLink->original_url }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Короткий URL</dt>
                            <dd class="mt-1 break-all text-sm">
                                <a href="{{ $shortLink->shortUrl() }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $shortLink->shortUrl() }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Короткий код</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $shortLink->short_code }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $shortLink->created_at?->format('d.m.Y H:i') }}</dd>
                        </div>
                    </dl>

                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                        <a href="{{ route('links.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">
                            Назад к списку
                        </a>

                        <form method="POST" action="{{ route('links.destroy', $shortLink) }}">
                            @csrf
                            @method('DELETE')

                            <x-danger-button>
                                Удалить
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
