# React Native X Tweet — Backend (Laravel API)

A Laravel 12 REST API that powers the React Native X Tweet app(https://github.com/alpharameeztech/react-native-x-tweet). It provides authentication, user profiles, tweeting, global and following feeds, follow/unfollow actions, and pagination. Authentication is handled with Laravel Sanctum personal access tokens.

## Features
- User registration, login, logout
- Sanctum personal access tokens for user verification
- Create, view, and delete tweets
- Global tweets feed and “following” feed
- Follow and unfollow users; check following status
- Public user profiles and user-specific timelines
- Pagination across list endpoints
- MySQL database and database-backed queues

## Tech Stack
- PHP 8.2, Laravel 12.x
- MySQL
- Laravel Sanctum
- Queue: database
- Composer + npm (tooling/scripts)

## Prerequisites
- PHP >= 8.2 and Composer
- MySQL (or compatible)
- Node.js + npm (optional for tooling)
- OpenSSL extension enabled

## Installation

1) Install dependencies:
2) 2) Copy environment file:
3) 3) Configure environment variables in .env:
4) 4) Generate the app key:
5) 5) Run migrations:
6) 6) Seed sample data (users and tweets via factories):
7) - This creates demo users and tweets to test feeds and pagination.
- Recreate schema and seed from scratch:
- 7) Start the queue worker (if needed):
7) Base URL: http://127.0.0.1:8000

All API routes are available under /api — for example: http://127.0.0.1:8000/api/login

## Authentication with Sanctum

This API uses Laravel Sanctum personal access tokens to verify users. Obtain a token through the login endpoint, then include it as a Bearer token in subsequent requests.

- Required headers for protected endpoints:

- Tokens are device-scoped: login requires a device_name, and logout revokes only the current token.

### Register
- POST /api/register
- Body:json { "name": "Your Name", "email": "user@example.com", "password": "your-strong-password", "password_confirmation": "your-strong-password" }

- Response: user details (and token depending on implementation).

### Login (Issue Sanctum Token)
- POST /api/login
- Body:- Response: user details (and token depending on implementation).

### Login (Issue Sanctum Token)
- POST /api/login
- Body:json { "email": "user@example.com", "password": "your-strong-password", "device_name": "my-device-name" }
- Response:json { "token": "<SANCTUM_PERSONAL_ACCESS_TOKEN>", "user": { "id": 1, "name": "Your Name", "username": "your_username", "avatar": "https://..." } }
### Logout (Revoke Token)
- POST /api/logout (requires Authorization header)
- Revokes the current access token.

## API Reference

Unless specified otherwise, endpoints require Authorization: Bearer <token>.

- GET /api/user
    - Returns the authenticated user profile.

### Tweets
- GET /api/tweets
    - Following feed (tweets from users you follow). Supports pagination.
- GET /api/tweets_all
    - Global feed (all users’ tweets). Supports pagination.
- GET /api/tweets/{tweet}
    - Retrieve a single tweet by ID.
- POST /api/tweets
    - Create a tweet.
    - Body:
  ```json
  { "body": "Hello from the API!" }
  ```
- DELETE /api/tweets/{tweet}
    - Delete a tweet you own.

### Follows
- POST /api/follow/{user}
    - Follow a user by ID.
- POST /api/unfollow/{user}
    - Unfollow a user by ID.
- GET /api/is_following/{user}
    - Check if the authenticated user follows the specified user ID.
    - Example response:
  ```json
  { "is_following": true }
  ```

### Profiles
- GET /api/users/{user}
    - Public user profile by ID.
- GET /api/users/{user}/tweets
    - Tweets authored by the specified user. Supports pagination.

## Pagination

Most listing endpoints support:
- page (1-based)
- per_page (optional; default is server-defined)

Example:
GET /api/tweets_all?page=1&per_page=20
Typical paginated response (shape may vary):

json { "data": , "current_page": 1, "per_page": 20, "total": 200, "last_page": 10 }
## cURL Examples

- Register:
- bash curl -X POST [http://127.0.0.1:8000/api/register](http://127.0.0.1:8000/api/register)
  -H "Content-Type: application/json"
  -d '{"name":"Demo","email":"demo@example.com","password":"<PLACEHOLDER_PASSWORD>","password_confirmation":"<PLACEHOLDER_PASSWORD>"}'
- Login (Sanctum token):
  bash curl -X POST [http://127.0.0.1:8000/api/login](http://127.0.0.1:8000/api/login)
  -H "Content-Type: application/json"
  -d '{"email":"demo@example.com","password":"<PLACEHOLDER_PASSWORD>","device_name":"react-native-device"}'
- Create tweet:
  bash curl -X POST [http://127.0.0.1:8000/api/tweets](http://127.0.0.1:8000/api/tweets)
  -H "Authorization: Bearer <PLACEHOLDER_TOKEN>"
  -H "Content-Type: application/json"
  -d '{"body":"Hello from cURL!"}'
- Global feed (paginated):
  bash curl "[http://127.0.0.1:8000/api/tweets_all?page=1&per_page=20](http://127.0.0.1:8000/api/tweets_all?page=1&per_page=20)"
  -H "Authorization: Bearer <PLACEHOLDER_TOKEN>"
  -H "Accept: application/json"
- Follow a user:
  bash curl -X POST [http://127.0.0.1:8000/api/follow/123](http://127.0.0.1:8000/api/follow/123)
  -H "Authorization: Bearer <PLACEHOLDER_TOKEN>"
  -H "Accept: application/json"
## Development

Useful commands:
- `php artisan migrate`
- `php artisan migrate:fresh --seed`
- `php artisan db:seed`
- `php artisan tinker`
- `php artisan queue:work`
- `php artisan serve`

If available in composer.json:
