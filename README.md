# Unfollow Checker

A lightweight, privacy-focused web application designed to analyze your Instagram followers and following relationships and identify accounts that don't follow you back.

## &#x20;About the Project

**Unfollow Checker** is a Laravel application that compares follower and following data exported directly through Instagram's official data download feature.

Instead of relying on web scrapers or Instagram credentials, users can upload their official JSON data exports to get organized results, search filters, mutual connection tracking, statistics, and CSV exports.

## &#x20;Privacy by Design

This project was designed with user privacy in mind, following Privacy by Design principles and considering data protection practices relevant to frameworks such as GDPR (Europe) and LGPD (Brazil).

- **No Database:** The application does not use a database to store Instagram data or comparison results.
- **Temporary Processing:** Uploaded files are read from PHP temporary upload locations and are not permanently stored by the application.
- **No Instagram Login Required:** Users never need to provide their Instagram credentials.
- **No Instagram API Integration:** All analysis is performed using JSON files downloaded directly by the user.

## &#x20;Core Features

- **Unfollow Tracking:** Identify accounts you follow that do not follow you back.
- **Mutual Followers:** View accounts with reciprocal following relationships.
- **Followers You Don't Follow Back:** Identify accounts that follow you but that you don't follow.
- **Pending Outgoing Requests:** View follow requests that have not yet been accepted.
- **Multi-File Support:** Upload and automatically combine multiple follower JSON files.
- **Instant Search:** Filter results by username directly in the browser.
- **CSV Export:** Download structured reports for each connection category.
- **Analytics Dashboard:** View key statistics about your Instagram connections.

## Tech Stack

- **Backend:** PHP 8.5 & Laravel 13
- **Frontend:** Blade & Alpine.js
- **Styling:** Tailwind CSS 4
- **Build Tool:** Vite
- **Testing:** Pest PHP Testing Framework

## How It Works

The application extracts and normalizes usernames from Instagram's official JSON export files, then compares the resulting datasets using set operations.

| Result                              | Description                                                                        |
| ----------------------------------- | ---------------------------------------------------------------------------------- |
| **Not Following Back**              | Accounts in your following list that are not in your followers list.               |
| **Mutual Followers**                | Accounts present in both lists.                                                    |
| **Followers You Don't Follow Back** | Accounts in your followers list that are not in your following list.               |
| **Pending Requests**                | Outgoing follow requests extracted separately from `pending_follow_requests.json`. |

Pending requests are displayed separately and are not included in follower statistics.

## Getting Started

### Prerequisites

Make sure you have the following installed:

- PHP 8.3 or higher
- Composer
- Node.js and npm

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

The application will be available at:

`http://127.0.0.1:8000`

## Running Automated Tests

The project uses **Pest** to run unit and feature tests.

To execute the complete test suite:

```bash
php vendor/bin/pest
```

### Git Pre-Push Hook

The repository includes a Git `pre-push` hook that automatically executes the Pest test suite before pushing changes to GitHub.

If any test fails, the push is blocked, helping prevent changes from being pushed with failing tests.

To enable the hook after cloning the repository, run:

```bash
git config core.hooksPath .githooks
```

## &#x20;How to Export Your Instagram Data

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

No license has been specified yet.
