
@extends('layouts.app')

@section('title', 'Resultados | Unfollow Checker')

@section('content')

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('unfollowResults', () => ({
            activeTab: 'notFollowingBack',
            search: '',

            lists: {
                notFollowingBack: @js($result->notFollowingBack),
                notFollowing: @js($result->notFollowing),
                mutualFollowers: @js($result->mutualFollowers),
                pendingFollowRequests: @js($pendingFollowRequests),
            },

            get filteredUsers() {
                return this.lists[this.activeTab].filter(username =>
                    username.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            exportCsv() {
                const usernames = this.lists[this.activeTab];

                const safeUsernames = usernames.map(username => {
                    const value = String(username);

                    return /^[=+\-@\t\r]/.test(value)
                        ? "'" + value
                        : value;
                });

                const rows = [
                    ['Username'],
                    ...safeUsernames.map(username => [username])
                ];

                const csv = rows
                    .map(row => row.map(value =>
                        '"' + String(value).replace(/"/g, '""') + '"'
                    ).join(';'))
                    .join('\r\n');

                const blob = new Blob(
                    ['\uFEFF', csv],
                    { type: 'text/csv;charset=utf-8;' }
                );

                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');

                const fileNames = {
                    notFollowingBack: 'nao-seguem-de-volta',
                    notFollowing: 'nao-sigo-de-volta',
                    mutualFollowers: 'conexoes-mutuas',
                    pendingFollowRequests: 'solicitacoes-pendentes',
                };

                link.href = url;
                link.download = `unfollow-checker-${fileNames[this.activeTab]}.csv`;

                document.body.appendChild(link);
                link.click();
                link.remove();

                URL.revokeObjectURL(url);
            }
        }));
    });
</script>

<div
    class="mx-auto max-w-6xl px-6 py-12"
    x-data="unfollowResults"
>

    @if (isset($successMessage))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition.opacity.duration.300ms
            class="fixed right-5 top-5 z-50 flex items-center gap-3 rounded-xl border border-pink-200 bg-white px-5 py-4 shadow-lg"
            role="status"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-pink-100">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-pink-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </div>

            <div>
                <p class="font-semibold text-gray-900">
                    Sucesso!
                </p>

                <p class="text-sm text-gray-600">
                    {{ $successMessage }}
                </p>
            </div>

            <button
                type="button"
                @click="show = false"
                class="ml-2 text-gray-400 hover:text-gray-600"
                aria-label="Fechar notificação"
            >
                &times;
            </button>
        </div>
    @endif
    <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="text-sm font-semibold uppercase tracking-widest text-pink-600">
                Unfollow Checker
            </span>

            <h1 class="mt-2 text-3xl font-bold">
                Resultado da análise
            </h1>

            <p class="mt-2 text-gray-500">
                Confira um resumo das suas conexões no Instagram.
            </p>
        </div>

        <a
            href="{{ route('unfollow.index') }}"
            class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-semibold transition hover:bg-gray-100"
        >
            Nova análise
        </a>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Seguidores</p>
            <p class="mt-2 text-3xl font-bold">
                {{ $statistics['totalFollowers'] }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Seguindo</p>
            <p class="mt-2 text-3xl font-bold">
                {{ $statistics['totalFollowing'] }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Não seguem de volta</p>
            <p class="mt-2 text-3xl font-bold text-pink-600">
                {{ $statistics['notFollowingBack'] }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <p class="text-sm text-gray-500">Conexões mútuas</p>
            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $statistics['mutualFollowers'] }}
            </p>
        </div>
    </div>

    {{-- Listas --}}
    <div class="mt-10 rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="text-xl font-bold">
            Contas encontradas
        </h2>

        <div class="mt-6 flex flex-wrap gap-2">
            <button
                type="button"
                @click="activeTab = 'notFollowingBack'; search = ''"
                :class="activeTab === 'notFollowingBack' ? 'bg-pink-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="rounded-lg px-4 py-2 text-sm font-semibold"
            >
                Não seguem de volta ({{ $statistics['notFollowingBack'] }})
            </button>

            <button
                type="button"
                @click="activeTab = 'notFollowing'; search = ''"
                :class="activeTab === 'notFollowing' ? 'bg-pink-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="rounded-lg px-4 py-2 text-sm font-semibold"
            >
                Não sigo de volta ({{ $statistics['notFollowing'] }})
            </button>

            <button
                type="button"
                @click="activeTab = 'mutualFollowers'; search = ''"
                :class="activeTab === 'mutualFollowers' ? 'bg-pink-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="rounded-lg px-4 py-2 text-sm font-semibold"
            >
                Mútuos ({{ $statistics['mutualFollowers'] }})
            </button>

            <button
                type="button"
                @click="activeTab = 'pendingFollowRequests'; search = ''"
                :class="activeTab === 'pendingFollowRequests' ? 'bg-pink-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="rounded-lg px-4 py-2 text-sm font-semibold"
            >
                Solicitações pendentes ({{ count($pendingFollowRequests) }})
            </button>
        </div>

        </div>

        {{-- Aviso sobre contas indisponíveis --}}
        <div
            x-show="activeTab === 'notFollowingBack'"
            x-cloak
            class="mt-5 flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                />
            </svg>

            <div>
                <p class="text-sm font-semibold text-amber-800">
                    Atenção: contas possivelmente indisponíveis
                </p>

                <p class="mt-1 text-sm leading-relaxed text-amber-700">
                    Algumas contas podem estar desativadas, suspensas ou temporariamente indisponíveis no Instagram.
                    Por isso, um perfil listado aqui pode não representar necessariamente alguém que deixou de seguir você.
                    Recomendamos verificar o perfil diretamente no Instagram antes de tirar conclusões.
                </p>
            </div>
        </div>

        {{-- Pesquisa e exportação --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <input
                type="search"
                x-model="search"
                placeholder="Pesquisar username..."
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-pink-500 focus:ring-2 focus:ring-pink-100"
            >

            <button
                type="button"
                @click="exportCsv()"
                :disabled="lists[activeTab].length === 0"
                class="shrink-0 rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
            >
                Exportar CSV
            </button>
        </div>

        {{-- Resultados --}}
        <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4">
            <div class="divide-y divide-gray-100">
                <template x-for="username in filteredUsers" :key="username">
                    <div class="flex items-center justify-between py-4">
                        <span
                            class="font-medium text-gray-800"
                            x-text="'@' + username"
                        ></span>

                        <a
                            :href="'https://www.instagram.com/' + encodeURIComponent(username) + '/'"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-sm font-semibold text-pink-600 hover:text-pink-700"
                        >
                            Ver perfil
                        </a>
                    </div>
                </template>

                <div
                    x-show="filteredUsers.length === 0"
                    x-cloak
                    class="py-10 text-center text-sm text-gray-500"
                >
                    Nenhuma conta encontrada.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('pageshow', () => {
        const navigation = performance.getEntriesByType('navigation')[0];

        if (navigation?.type === 'reload') {
            window.location.replace(@js(route('unfollow.index')));
        }
    });
</script>

@endsection
