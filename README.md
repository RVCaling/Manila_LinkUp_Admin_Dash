# Manila LinkUp — Admin Dashboard

Laravel 12 admin panel for the Manila LinkUp job-matching platform. Communicates exclusively with the `manilalinkup-api` backend — no direct Firestore access.

---

## Stack

- **Backend:** Laravel 12 (PHP 8.2+), Blade, SQLite (sessions/cache/queue)
- **Frontend:** Bootstrap 5, vanilla JS (`public/js/`)
- **Auth:** Firebase Auth (JS SDK) + server-side token verification via `kreait/laravel-firebase`
- **Data:** All reads/writes go through `ApiService` → `manilalinkup-api`

---

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

`.env` values to fill in:

```
FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
FIREBASE_DATABASE_URL=https://manilalinkup-default-rtdb.firebaseio.com/
FIREBASE_WEB_API_KEY=        # Firebase Console → Project Settings → General → Web API Key
API_URL=http://127.0.0.1:8000
```

Copy Firebase credentials from the API project:

```bash
cp ../manilalinkup-api/storage/app/firebase/firebase_credentials.json storage/app/firebase/
```

### Admin account prerequisite

The admin user must exist in **both**:
1. **Firebase Auth** — email/password account
2. **Firestore `admins` collection** — document whose ID is that user's Firebase UID

---

## Running

Two servers must run simultaneously:

```bash
# Terminal 1 — API (port 8000)
cd /Applications/XAMPP/xamppfiles/htdocs/manilalinkup-api
php artisan serve

# Terminal 2 — Admin Dash (port 8001)
cd /Applications/XAMPP/xamppfiles/htdocs/Manila_LinkUp_Admin_Dash
composer run dev
```

Admin dash: `http://localhost:8001/admin/login`

### Commands

| Command | Description |
|---|---|
| `composer run dev` | PHP server (8001) + queue + logs + Vite HMR |
| `composer run test` | PHPUnit tests |
| `php artisan migrate` | Run DB migrations |
| `npm run build` | Production JS/CSS build |

---

## Architecture

**Auth flow:**
1. Login page calls Firebase JS SDK `signInWithEmailAndPassword()`
2. Gets ID token + refresh token → POSTs to `AdminDashboardController::authenticate()`
3. Laravel verifies token, checks UID in Firestore `admins` collection
4. Stores `{ uid, email, idToken, refreshToken, tokenExpiry }` in session
5. `ApiService` attaches token as `Authorization: Bearer` on every API call
6. Token auto-refreshes when within 5 min of expiry

**Frontend pattern:** Blade injects server data as `window.<varName> = @json($data)` before JS loads. JS reads `window.<varName>` on init.

---

## Routes & Controllers

### Auth — `AdminDashboardController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/login` | `showLogin` | Render login page |
| POST | `/admin/login` | `authenticate` | Verify Firebase token, start session |
| GET | `/admin/dashboard` | `index` | Main dashboard with overview stats |
| POST | `/admin/logout` | `logout` | Clear session |

### Users — `Admin\UserController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/users` | `index` | All seekers + employers |
| GET | `/admin/users/{uid}/photo` | `photo` | Proxy user profile photo |

### Seekers — `Admin\SeekerController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/seekers` | `index` | Pending seeker verification queue |

### Employers — `Admin\EmployerController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/employers` | `index` | Pending employer verification queue |

### Verifications — `Admin\VerificationController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/verifications` | `index` | Full verification queue |
| GET | `/admin/notifications` | `notifications` | Pending verifications for bell icon |
| POST | `/admin/{type}/{uid}/verify` | `verify` | Approve a user verification |
| POST | `/admin/{type}/{uid}/reject` | `reject` | Reject a user verification |

### Analytics — `Admin\AnalyticsController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/analytics` | `index` | Overview stats + charts |
| GET | `/admin/analytics/filter` | `filter` | Filtered analytics data |

### Profile — `Admin\ProfileController`

| Method | Route | Function | Description |
|---|---|---|---|
| GET | `/admin/profile` | `edit` | Admin profile page |
| POST | `/admin/profile/update` | `update` | Update admin profile |

---

## ApiService

`app/Services/ApiService.php` — central HTTP client used by all controllers.

| Method | Description |
|---|---|
| `get(endpoint, query)` | Authenticated GET request to the API |
| `post(endpoint, data)` | Authenticated POST request to the API |

Handles token refresh and 401 → force logout automatically.

### API endpoints consumed

| Endpoint | Description |
|---|---|
| `GET /api/admin/users` | All seekers + employers |
| `GET /api/admin/seekers?verified=false` | Pending seeker queue |
| `GET /api/admin/employers?verified=false` | Pending employer queue |
| `GET /api/admin/analytics/overview` | Job/application/hire counts |
| `GET /api/admin/analytics/users` | Seeker/employer counts + verified counts |
| `GET /api/admin/pendingVerifications` | Users pending verification (bell icon) |
| `POST /api/admin/verifyUser` | `{ type, userUid }` — approve |
| `POST /api/admin/rejectVerification` | `{ type, userUid }` — reject |
