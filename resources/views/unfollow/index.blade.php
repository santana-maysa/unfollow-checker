
@extends('layouts.app')

@section('title', 'Analisar seguidores | Unfollow Checker')

@section('content')

<div class="mx-auto flex min-h-screen max-w-3xl flex-col justify-center px-6 py-12">

    <div class="mb-10 text-center">

        <span class="text-sm font-semibold uppercase tracking-widest text-pink-600">
            Unfollow Checker
        </span>

        <h1 class="mt-4 text-4xl font-bold tracking-tight">
            Descubra quem não segue você de volta.
        </h1>

        <p class="mx-auto mt-4 max-w-xl text-gray-500">
            Envie seus arquivos de seguidores e seguindo do Instagram
            para comparar suas conexões de forma simples e privada.
        </p>

    </div>

    {{-- Tutorial de exportação --}}
    <div
        x-data="{ open: false }"
        class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
    >

        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between text-left"
        >
            <span class="font-semibold">
                Como baixar meus dados do Instagram?
            </span>

            <span x-text="open ? '−' : '+'"
                class="text-2xl text-gray-500">
            </span>
        </button>

        <div x-show="open" class="mt-5 space-y-5">

            <p class="text-sm text-gray-600">
                Siga os passos abaixo para solicitar seus dados:
            </p>

            <ol class="list-inside list-decimal space-y-3 text-sm text-gray-700">

                <li>
                    Abra o Instagram e acesse seu perfil.
                </li>

                <li>
                    Toque no menu de três linhas e entre na
                    <strong>Central de Contas</strong>.
                </li>

                <li>
                    Acesse <strong>Suas informações e permissões</strong>
                    e selecione a opção de baixar suas informações.
                </li>

                <li>
                    Escolha sua conta do Instagram e selecione
                    as informações relacionadas a seguidores e seguindo.
                </li>

                <li>
                    Configure o período como
                    <strong>Todo o período</strong>, quando disponível,
                    e escolha o formato <strong>JSON</strong>.
                </li>

                <li>
                    Solicite a exportação e aguarde o Instagram
                    disponibilizar o download.
                </li>

                <li>
                    Baixe o arquivo ZIP e extraia seu conteúdo
                    no computador.
                </li>

            </ol>

            <div class="rounded-lg bg-pink-50 p-4">

                <p class="font-semibold text-pink-800">
                    Onde encontrar os arquivos?
                </p>

                <p class="mt-2 text-sm text-pink-700">
                    Procure a pasta:
                </p>

                <code class="mt-2 block break-all text-xs text-pink-900">
                    connections/followers_and_following/
                </code>

                <p class="mt-3 text-sm text-pink-700">
                    Dentro dela estarão os arquivos necessários
                    para realizar a análise.
                </p>

            </div>

            <p class="text-xs text-gray-500">
                Os nomes e a organização dos arquivos podem variar
                conforme a versão da exportação do Instagram.
            </p>

           <a
                href="https://help.instagram.com/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-block text-sm font-semibold text-pink-600 hover:text-pink-700"
            >
                Consultar a Central de Ajuda do Instagram →
            </a>

        </div>

    </div>

    {{-- Formulário --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">

        <form
            action="{{ route('unfollow.analyze') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf

            <div>
                <label for="followers" class="mb-2 block font-semibold">
                    Arquivos de seguidores
                </label>

                <input
                    type="file"
                    id="followers"
                    name="followers[]"
                    accept=".json"
                    multiple
                    required
                    class="block w-full rounded-lg border border-gray-300 p-3 text-sm"
                >

                <p class="mt-2 text-sm text-gray-500">
                    Selecione todos os arquivos followers_1.json,
                    followers_2.json etc.
                </p>

                @error('followers')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('followers.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="following" class="mb-2 block font-semibold">
                    Arquivo de seguindo
                </label>

                <input
                    type="file"
                    id="following"
                    name="following"
                    accept=".json"
                    required
                    class="block w-full rounded-lg border border-gray-300 p-3 text-sm"
                >

                <p class="mt-2 text-sm text-gray-500">
                    Selecione o arquivo following.json.
                </p>

                @error('following')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="pending_follow_requests" class="mb-2 block font-semibold">
                    Solicitações pendentes
                    <span class="font-normal text-gray-400">(opcional)</span>
                </label>

                <input
                    type="file"
                    id="pending_follow_requests"
                    name="pending_follow_requests"
                    accept=".json"
                    class="block w-full rounded-lg border border-gray-300 p-3 text-sm"
                >

                <p class="mt-2 text-sm text-gray-500">
                    Selecione pending_follow_requests.json, caso esteja
                    disponível na exportação.
                </p>

                @error('pending_follow_requests')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @error('files')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                class="w-full rounded-lg bg-pink-600 px-6 py-3 font-semibold text-white transition hover:bg-pink-700"
            >
                Analisar seguidores
            </button>

        </form>

    </div>

    <p class="mt-6 text-center text-sm text-gray-400">
        Seus arquivos são processados temporariamente e não ficam armazenados
        permanentemente pela aplicação.
    </p>

</div>

@endsection
