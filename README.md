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