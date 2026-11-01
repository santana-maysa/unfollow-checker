# Unfollow Checker

[**English**](README.md) | [Português](README.pt-BR.md)

A web application to analyze Instagram follower and following relationships.

## Live Demo

Try the application: **[Unfollow Checker](http://3.137.136.158)**

## About the Project

**Unfollow Checker** compares follower and following data exported directly from Instagram.

Instead of relying on web scrapers or requesting Instagram credentials, the application lets users upload their official JSON data exports and view organized results with search filters, statistics, and CSV exports.

## Privacy

This project was designed with user privacy in mind.

- **No database:** the application does not use a database to store Instagram data or comparison results.
- **Temporary processing:** uploaded files are read from PHP temporary upload locations and are not permanently stored by the application.
- **No Instagram login required:** users never need to provide their Instagram credentials or connect their accounts.
- **No Instagram API integration:** all analysis is performed using JSON files downloaded directly by the user.

## Features

- **Not Following Back:** identify accounts you follow that do not follow you back.
- **Mutual Followers:** view accounts with reciprocal following relationships.
- **Followers You Don't Follow Back:** identify accounts that follow you but that you don't follow.
- **Pending Outgoing Requests:** view follow requests that have not yet been accepted.
- **Multi-File Support:** upload and automatically combine multiple follower JSON files.
- **Instant Search:** filter results by username directly in the browser.
- **CSV Export:** download structured reports for each connection category.
- **Analytics Dashboard:** view key statistics about your Instagram connections.

## Tech Stack

- **Backend:** PHP 8.4+ and Laravel 13
- **Frontend:** Blade and Alpine.js
- **Styling:** Tailwind CSS 4
- **Build Tool:** Vite 8
- **Testing:** Pest 4

## How It Works

The application extracts and normalizes usernames from Instagram's official JSON export files, then compares the resulting datasets.

| Result | Description |
| --- | --- |
| **Not Following Back** | Accounts in your following list that are not in your followers list. |
| **Mutual Followers** | Accounts present in both lists. |
| **Followers You Don't Follow Back** | Accounts in your followers list that are not in your following list. |
| **Pending Requests** | Outgoing requests extracted separately from `pending_follow_requests.json`. |

Pending requests are displayed separately and are not included in follower statistics.

## Getting Started

### Prerequisites

Make sure you have the following installed:

- PHP 8.4.1 or higher
- Composer
- Node.js 22 or higher and npm

### Local Installation

1. Clone this repository:

```bash
git clone https://github.com/santana-maysa/unfollow-checker.git
```

2. Navigate to the project directory:

```bash
cd unfollow-checker
```

3. Install PHP dependencies:

```bash
composer install
```

4. Install frontend dependencies:

```bash
npm install
```

5. Create your environment file:

```bash
cp .env.example .env
```

6. Generate the application key:

```bash
php artisan key:generate
```

7. Build frontend assets:

```bash
npm run build
```

8. Start the development server:

```bash
php artisan serve
```

The application will be available at **http://127.0.0.1:8000**.

During development, you can also run `npm run dev` to watch frontend changes.

## Running Automated Tests

The project uses **Pest** for unit and feature tests.

Run the complete test suite with:

```bash
php artisan test
```

### Git Pre-Push Hook

The repository includes a Git `pre-push` hook that automatically runs the Pest test suite before pushing changes to GitHub.

If any test fails, the push is blocked, helping prevent changes from being pushed with failing tests.

To enable the hook after cloning the repository, run:

```bash
git config core.hooksPath .githooks
```

## How to Export Your Instagram Data

1. Open Instagram and go to **Accounts Center**.
2. Navigate to **Your Information and Permissions > Download Your Information**.
3. Request a download and select the **JSON** format.
4. Download and extract the ZIP archive when it becomes available.
5. Locate the `followers_and_following` folder.
6. Upload the required files to the application:
   - `followers_1.json` (and any additional follower files).
   - `following.json`.
   - `pending_follow_requests.json` (optional).

Submit the files to analyze your Instagram connections.

**Disclaimer:** Some accounts may be deactivated, suspended, or temporarily unavailable on Instagram. Therefore, a profile appearing in the results does not necessarily mean that the person has unfollowed you. Always verify directly on Instagram before drawing conclusions.

## License

No license has been specified for this project yet.
