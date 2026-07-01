<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Мои короткие ссылки
            </h2>

            <a href="{{ route('links.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                Создать ссылку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($shortLinks->isEmpty())
                        <p class="text-sm text-gray-600">У вас пока нет коротких ссылок.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Оригинальный URL</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Короткий URL</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Создана</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Действия</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($shortLinks as $shortLink)
                                        <tr>
                                            <td class="max-w-md truncate px-4 py-4 text-sm text-gray-900">
                                                {{ $shortLink->original_url }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                <a href="{{ $shortLink->shortUrl() }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $shortLink->shortUrl() }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ $shortLink->created_at?->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('links.show', $shortLink) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Открыть
                                                    </a>

                                                    <form method="POST" action="{{ route('links.destroy', $shortLink) }}">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $shortLinks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
