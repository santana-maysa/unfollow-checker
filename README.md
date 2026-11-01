# Unfollow Checker

A lightweight, privacy-focused web application designed to analyze your Instagram followers and following relationships to immediately spot who doesn't follow you back.

## About the Project

**Unfollow Checker** is a high-performance Laravel application that securely compares your follower and following datasets, exported directly through Instagram's official data privacy panel. 

Instead of dealing with unstable or rule-breaking web scrapers, this tool allows users to upload their official JSON data exports to get immediate analytics, search filters, mutual connection tracking, and custom CSV data exports.

## Privacy by Design & GDPR Compliance

This project was intentionally engineered around **Privacy by Design** principles to ensure strict compliance with global data protection frameworks such as **GDPR (Europe)** and **LGPD (Brazil)**:

- **Zero Persistence:** The application does not use a database. User records are processed 100% *In-Memory* using fast Laravel Collections and instantly destroyed after the lifecycle of the request.
- **No Instagram Login Required:** Your social credentials are completely safe. The app operates entirely isolated from Meta's authentication servers.
- **Isolated Sandbox processing:** Files are analyzed strictly within ephemeral temporary upload directories.

## Core Features

- **Unfollow Tracking:** Instantly spot accounts you follow, but that do not follow you back.
- **Mutual Followers:** View clean lists of reciprocal connections.
- **Fans Directory:** Identify users who follow you, but you haven't followed back yet.
- **Pending Outgoing Requests:** Keep track of follow requests you sent that are still waiting for approval.
- **Multi-File Suppor:** Smart automated merging for chunked follower JSON files.
- **Instant Search:** Full client-side/server-side filtering to search connections by username.
- **CSV Data Exporting:** Download structured reports of your connection groups with a single click.
- **Analytics Dashboard:** Clean UI presenting key statistics of your profile's social health.

##  Tech Stack

- **Backend:** PHP 8.5+ & Laravel 13
- **Frontend Architecture:** Alpine.js (Reactive States) & Blade Components
- **Styling UI:** Tailwind CSS v4
- **Build Asset Pipeline:** Vite
- **Testing Suite:** Pest PHP Testing Framework

##  How It Works (Algorithmic Logic)

The software leverages efficient array mapping and set operations over official JSON data structures provided by Meta:

| Result Target | Technical Computation (Laravel Collections) |
|---|---|
| **Not Following Back** | Computes the directional difference (`following` entries not matching `followers`). |
| **Mutual Connections** | Computes the intersection subset of both datasets. |
| **You Don't Follow Back** | Computes the directional difference (`followers` entries not matching `following`). |
| **Pending Requests** | Isolated array mapping over `pending_follow_requests.json`. |

##  Getting Started

### Prerequisites

Ensure you have the following environmental setups configured on your local machine:
- PHP 8.3 or higher
- Composer
- Node.js & NPM

### Local Installation

1. Clone this repository:
```bash
git clone https://github.com
```

2. Navigate to the project root directory:
```bash
cd unfollow-checker
```

3. Install PHP dependencies via Composer:
```bash
composer install
```

4. Install frontend node dependencies:
```bash
npm install
```

5. Setup your local environment file:
```bash
cp .env.example .env
```

6. Generate your application security key:
```bash
php artisan key:generate
```

7. Compile asset builds:
```bash
npm run build
```

8. Start your local development server:
```bash
php artisan serve
```

The app will instantly launch and become available at `http://127.0.0.1:8000`.

## Running Automated Tests

Quality assurance and type safety are critical pillars of this codebase. The system relies on **Pest** for running quick, isolated feature and unit tests.

To run the complete test suite, execute:
```bash
php vendor/bin/pest
```

### CI/CD Automation: Git Pre-Push Hook

To ensure main branch stability, this repository features an active `.githooks/pre-push` script. The hook automatically executes your Pest test suite before any local code is successfully pushed upstream to GitHub. If a single test fails, the push is safely blocked.

To activate this automated hook workflow locally post-cloning, execute:
```bash
git config core.hooksPath .githooks
```

## How to Extract Your Data from Instagram

1. Go to your Instagram Settings and navigate to **Accounts Center > Your Information and Permissions > Download Your Information**.
2. Request a download and ensure you select the **JSON format** option.
3. Once you receive the email link, download and extract your profile ZIP archive.
4. Locate the folder named `followers_and_following`.
5. Upload your target files (e.g., `followers_1.json`, `following.json`, and `pending_follow_requests.json`) into the application dropzone to instantly query results.

***Disclaimer:** Suspended, deactivated, or private sandbox restrictions on Meta's end can occasionally affect raw data exports. Always verify connection state variables live before taking definitive actions.*

## License

This project is open-source and ready for global community distribution.
