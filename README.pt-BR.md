# Unfollow Checker

[English](README.md) | **Português**

Uma aplicação web para analisar relações de seguidores e contas seguidas no Instagram.

## Demonstração

Acesse a aplicação: **[Unfollow Checker](http://3.137.136.158)**

## Sobre o projeto

O **Unfollow Checker** compara os dados de seguidores e contas seguidas exportados diretamente pelo Instagram.

Em vez de utilizar ferramentas de scraping ou solicitar as credenciais do Instagram, a aplicação permite que você envie os arquivos JSON oficiais da sua conta e visualize os resultados de forma organizada, com filtros de pesquisa, estatísticas e exportação em CSV.

## Privacidade

O projeto foi desenvolvido com foco na privacidade dos usuários.

- **Sem banco de dados:** a aplicação não utiliza banco de dados para armazenar os dados do Instagram ou os resultados da comparação.
- **Processamento temporário:** os arquivos enviados são lidos a partir dos diretórios temporários de upload do PHP e não são armazenados permanentemente pela aplicação.
- **Sem login do Instagram:** não é necessário informar sua senha ou conectar sua conta.
- **Sem integração com a API do Instagram:** toda a análise é feita a partir dos arquivos JSON baixados pelo próprio usuário.

## Funcionalidades

- **Quem não segue de volta:** identifica as contas que você segue, mas que não seguem você.
- **Seguidores mútuos:** exibe as contas que seguem você e que também são seguidas por você.
- **Seguidores que você não segue:** identifica quem segue você, mas não está na sua lista de contas seguidas.
- **Solicitações pendentes:** exibe solicitações de seguimento enviadas que ainda não foram aceitas.
- **Suporte a múltiplos arquivos:** permite enviar e combinar automaticamente vários arquivos JSON de seguidores.
- **Pesquisa instantânea:** filtra os resultados por nome de usuário diretamente no navegador.
- **Exportação CSV:** permite baixar relatórios organizados de cada categoria.
- **Painel de estatísticas:** apresenta informações gerais sobre suas conexões no Instagram.

## Tecnologias utilizadas

- **Backend:** PHP 8.4+ e Laravel 13
- **Frontend:** Blade e Alpine.js
- **Estilização:** Tailwind CSS 4
- **Build:** Vite 8
- **Testes:** Pest 4

## Como funciona

A aplicação extrai e padroniza os nomes de usuário dos arquivos JSON exportados pelo Instagram e compara os conjuntos de dados.

| Resultado | Descrição |
| --- | --- |
| **Não seguem de volta** | Contas presentes na lista de pessoas que você segue, mas ausentes na lista de seguidores. |
| **Seguidores mútuos** | Contas presentes nas duas listas. |
| **Você não segue de volta** | Contas presentes na lista de seguidores, mas ausentes na lista de pessoas que você segue. |
| **Solicitações pendentes** | Solicitações enviadas, extraídas separadamente do arquivo `pending_follow_requests.json`. |

As solicitações pendentes são exibidas separadamente e não são incluídas nas estatísticas de seguidores.

## Como executar localmente

### Pré-requisitos

Antes de começar, instale:

- PHP 8.4.1 ou superior
- Composer
- Node.js 22 ou superior e npm

### Instalação

1. Clone o repositório:

```bash
git clone https://github.com/santana-maysa/unfollow-checker.git
```

2. Acesse a pasta do projeto:

```bash
cd unfollow-checker
```

3. Instale as dependências do PHP:

```bash
composer install
```

4. Instale as dependências do frontend:

```bash
npm install
```

5. Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

6. Gere a chave da aplicação:

```bash
php artisan key:generate
```

7. Compile os arquivos do frontend:

```bash
npm run build
```

8. Inicie o servidor de desenvolvimento:

```bash
php artisan serve
```

A aplicação estará disponível em **http://127.0.0.1:8000**.

Durante o desenvolvimento, você também pode executar `npm run dev` para acompanhar as alterações nos arquivos do frontend.

## Executando os testes

O projeto utiliza **Pest** para testes unitários e de funcionalidades.

Para executar toda a suíte de testes:

```bash
php artisan test
```

### Git Pre-Push Hook

O repositório inclui um hook Git `pre-push` que executa automaticamente a suíte de testes do Pest antes de enviar alterações ao GitHub.

Se algum teste falhar, o push será interrompido. Isso ajuda a evitar o envio de alterações com testes quebrados.

Para habilitar o hook após clonar o repositório, execute:

```bash
git config core.hooksPath .githooks
```

## Como exportar seus dados do Instagram

1. Abra o Instagram e acesse a **Central de Contas**.
2. Entre em **Suas informações e permissões > Baixar suas informações**.
3. Solicite o download e selecione o formato **JSON**.
4. Quando o arquivo estiver disponível, baixe e extraia o ZIP.
5. Localize a pasta `followers_and_following`.
6. Envie os arquivos necessários para a aplicação:
   - `followers_1.json` (e os demais arquivos de seguidores, se existirem).
   - `following.json`.
   - `pending_follow_requests.json` (opcional).

Depois, execute a análise para visualizar suas conexões no Instagram.

**Aviso:** algumas contas podem estar desativadas, suspensas ou temporariamente indisponíveis no Instagram. Por isso, a presença de um perfil nos resultados não significa necessariamente que a pessoa deixou de seguir você. Confira diretamente no Instagram antes de tirar conclusões.

## Licença

Nenhuma licença foi definida para este projeto até o momento.
