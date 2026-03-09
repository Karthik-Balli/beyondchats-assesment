# BeyondInbox – Gmail Integration Dashboard

## Overview

This project is a **full-stack dashboard application** that integrates with **Gmail** to fetch, store, and manage email conversations in a **chat-style interface**.

The system allows users to:

* Connect their Gmail account securely
* Sync emails from the last *N* days
* Store email threads and messages in a database
* View email threads in a conversation-style dashboard
* Reply to emails directly from the dashboard

The goal of this project is **not just to clone Gmail**, I'd like to scale up as a product, and to create a **modern support-style inbox experience**, where email threads behave like chat conversations.

---

# Problem Understanding

Many teams rely on Gmail to communicate with customers, but Gmail is not optimized for:

* customer support workflows
* collaboration

A dashboard like this enables:

* structured email conversations
* easier navigation through threads
* better integrations with applications
* a more **chat-like experience for email communication**

This project demonstrates how a **product like BeyondEmails could integrate Gmail as a communication channel**.

---

# Key Requirements

Based on the assignment instructions, the system must support:

1. Gmail account integration using OAuth
2. Fetching and syncing emails from the user's Gmail account
3. Allowing the user to choose how many days of emails to sync
4. Storing emails in a database
5. Displaying synced email threads
6. Preserving:

   * email formatting
   * sender and receiver information
   * attachments
7. Allowing users to reply to email threads directly from the dashboard
8. A responsive UI that works well on mobile devices

---

# High-Level Architecture

```
React Frontend
       │
       │ REST API
       ▼
Laravel Backend
       │
       │
Database (MySQL)
       │
       ▼
Gmail API
```

### Components

**Frontend**

* React dashboard
* Integration settings
* Inbox thread view
* Reply interface

**Backend**

* Gmail OAuth authentication
* Email synchronization service
* Thread and message APIs
* Background sync jobs

**Database**

* Stores users
* email threads
* messages
* attachments

**External Integration**

* Gmail API for fetching and sending emails

---

# Technology Stack

## Frontend

React.js

Chosen for:

* component-based UI architecture
* strong ecosystem
* easy state management
* good responsiveness support

---

## Backend

Laravel (PHP)

Chosen for:

* built-in authentication tools
* powerful ORM (Eloquent)
* queue system for background jobs
* mature ecosystem

---

## Database

MySQL

Used to store:

* users
* email threads
* messages
* attachments

---

## External APIs

Gmail API

Used for:

* reading email threads
* fetching messages
* sending replies

---

# Planned Features

### Core Features

* Gmail OAuth integration
* Email sync for configurable date range
* Email thread storage
* Thread-based dashboard UI
* Reply to email functionality

---

## Development planning
1. Local Setup
2. Backend Initalization
3. Google OAuth Integration
4. Fronend UI Devlopment
5. Implementing New Features
6. Improvising the Application Performance
7. Testing the Application
8. Deployment

## Backend Architecture

The backend is built using **Laravel** and exposes REST APIs that power the React dashboard.

The system is responsible for:

* handling Gmail authentication
* syncing emails from Gmail
* storing threads and messages
* providing APIs for the frontend dashboard

The backend follows a **layered architecture** consisting of:

* Routes
* Controllers
* Services
* Models
* Background Jobs

---

# Routes

Laravel routes define how HTTP requests are handled by the application.

This project uses two route groups:

### Web Routes

Located in:

routes/web.php

These routes handle **Google OAuth authentication** because OAuth requires session support.

Example:

```
use App\Http\Controllers\AuthController;

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
```

Endpoints:

```
GET /auth/google
GET /auth/google/callback
```

Purpose:

* Redirect users to Google for authentication
* Receive OAuth callback after user grants access

---

### API Routes

Located in:

routes/api.php

These routes provide the **REST APIs consumed by the React frontend**.

Example:

```
Route::prefix('v1')->group(function () {

    Route::get('/threads', [ThreadController::class, 'index']);

    Route::get('/threads/{id}', [ThreadController::class, 'show']);

    Route::post('/emails/sync', [EmailController::class, 'sync']);

    Route::post('/threads/{id}/reply', [EmailController::class, 'reply']);

});
```

Example endpoints:

```
GET /api/v1/threads
GET /api/v1/threads/{id}
POST /api/v1/emails/sync
POST /api/v1/threads/{id}/reply
```

These APIs are responsible for:

* retrieving email threads
* retrieving messages in a thread
* triggering Gmail sync
* sending replies to emails

---

# Controllers

Controllers contain the request-handling logic of the application.

Each controller focuses on a specific responsibility.

### AuthController

Handles Gmail OAuth authentication.

Location:

```
app/Http/Controllers/AuthController.php
```

Responsibilities:

* redirect users to Google OAuth
* handle the OAuth callback
* retrieve Gmail access tokens

Example methods:

```
redirectToGoogle()
handleGoogleCallback()
```

Workflow:

```
User clicks "Connect Gmail"
        ↓
/auth/google
        ↓
Google OAuth login
        ↓
User grants permissions
        ↓
Google redirects to
/auth/google/callback
        ↓
Application receives Gmail access token
```

---

### ThreadController

Handles fetching email threads and messages.

Location:

```
app/Http/Controllers/ThreadController.php
```

Responsibilities:

* retrieving email threads
* retrieving conversation messages
* returning structured data to the frontend

Example endpoints:

```
GET /api/v1/threads
GET /api/v1/threads/{id}
```

---

### EmailController

Handles email-related actions.

Responsibilities include:

* syncing emails from Gmail
* sending replies to threads

Example endpoints:

```
POST /api/v1/emails/sync
POST /api/v1/threads/{id}/reply
```

---

# Google OAuth Integration

The application integrates with **Google OAuth 2.0** to securely access Gmail data.

The OAuth flow allows users to grant permission to the application to read and send emails.

Laravel Socialite is used to simplify OAuth integration.

Package used:

```
laravel/socialite
```

---

## OAuth Flow

```
User clicks "Connect Gmail"
        ↓
Frontend redirects user to backend endpoint
/auth/google
        ↓
Backend redirects user to Google OAuth
        ↓
User authenticates with Google
        ↓
User grants Gmail permissions
        ↓
Google redirects back to
/auth/google/callback
        ↓
Application receives OAuth tokens
```

---

## Required Gmail Permissions

The application requests the following scopes:

```
https://www.googleapis.com/auth/gmail.readonly
https://www.googleapis.com/auth/gmail.send
```

These permissions allow the system to:

* read email threads
* fetch message contents
* send email replies

---

## OAuth Configuration

OAuth credentials are configured in the `.env` file:

```
GOOGLE_CLIENT_ID=xxxx
GOOGLE_CLIENT_SECRET=xxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Laravel loads these values through:

```
config/services.php
```

Example configuration:

```
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

---

### Email Sync
The Email Synchronization feature is the core backend functionality of the application. It allows the system to fetch email threads from a user's Gmail account and store them in the application's database for further processing and display in the dashboard.

### How It Works

After a user successfully connects their Gmail account through Google OAuth, the system receives an **access token** that allows secure communication with the Gmail API.

When the user initiates an email sync request, the backend performs the following steps:

```text
User initiates email sync
        ↓
API endpoint receives request
        ↓
EmailSyncService triggered
        ↓
GmailService communicates with Gmail API
        ↓
Email threads retrieved
        ↓
Threads stored in database
```

---

### Backend Components

The email sync feature is implemented using a **service-based architecture** to keep the code modular and maintainable.

#### GmailService

Responsible for communicating with the Gmail API.

Responsibilities include:

* creating an authenticated Gmail client
* fetching email threads from Gmail
* returning Gmail data to the application

---

#### EmailSyncService

Handles the synchronization logic.

Responsibilities include:

* retrieving Gmail threads using the GmailService
* processing thread data
* storing threads in the database

---

#### EmailController

Handles the API endpoint that triggers email synchronization.

Example endpoint:

```text
POST /api/v1/emails/sync
```

This endpoint accepts a request specifying how many days of emails should be synchronized.

Example request:

```json
{
  "days": 7
}
```

---
