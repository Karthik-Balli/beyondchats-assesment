Hey, I'm **Karthik balli** 👋

Welcome to the BeyondInbox, a **full-stack dashboard application** that integrates with **Gmail** to fetch, store, and manage email conversations in a **chat-style interface**.

Here we disscuss about *BeyondInbox* Developement, I will try to go through phase of developement and try to mentions the points  that to be disscussed like, articulating the ideas, listing the merits and demerits of the approach, discussing the new features, idelogy beyond the implementation, Tech that how it works underhood. It's just a Notes for the *BeyondInbox* Developement journey.

BeyondInbox📩 is an email integration dashboard built for BeyondChats.

It transforms traditional email threads into a chat-style inbox experience,
allowing users to sync Gmail conversations, manage threads, and reply to emails
directly from a unified dashboard.

-----------

## Developement Flow

Firstly, I had a brainstorming session with an LLM, dissucssed about the tech stack, libraries, technolgies, coding patterns, bussiness logic and UX design.

### Local Setup
*Backend using Laravel*

- Actually Backend setup takes more time that I expected, Quite good experience!
- Larvel, needs composer no manage all these packages, while installing these packages got an error `Failed to extract laravel/laravel`. Then got some perssion oriented and packages installation issuses, fixed sequestionally. got learned lot things from `Laravel`.
- Bcoz, Most of my work on the JS evnivronments, and also profiecient in Python, built couple of projects.

- At Final we completed the Backend project intalization.
- From here we are following a branching strategy, to maintian two different branches for frontend, and backen. Then If I'd like to add any specific future to the application now or in feature, we will create a new branch for that feature and merge into the main.

---

**Stuck in the confiuration error loop**
- While connecting the mysql to the application, Intially faced some difficulty with setting up the `Xaamp` Server. Later resolved eventually.
- But While migration ar error occured about the `PDOException::("could not find driver")`.
- The issue occurred because the system PHP CLI was using `C:\php\php.ini` instead of the XAMPP PHP configuration at `C:\xampp\php\php.ini`. 
- As a result, required extensions like `pdo_mysql` were not enabled, causing Laravel database connection errors (e.g., “could not find driver”). 
- This happens when the system PATH prioritizes a different PHP installation than XAMPP. The fix enabled in the correct `php.ini`.
- However, I tried to created a table named `beyondinbox`, and migrated that table.

### Controller and Models
- Threaded Data Model: Organizes emails into a "chat" format by grouping Gmail messages and their formatting into Threads, Messages, and Attachments tables.
- Layered Architecture: Uses a production-grade structure with Services for Gmail API logic, Jobs for background syncing, and Models for database relationships.
- RESTful API Endpoints: Provides secure routes in api.php to fetch paginated chat lists and detailed conversation histories for the React frontend.
- Sync & Reply Logic: Implements backend controllers to fetch a specific number of days of history and handle outgoing replies to existing email threads.

### Here we added multiple routes
Example Endpoints:
```
GET /api/v1/threads
GET /api/v1/threads/{id}
POST /api/v1/emails/sync
POST /api/v1/threads/{id}/reply
```


### Controllers
- Built Authentication logic in **AuthController**, It can redirect users to Google OAuth, handle the OAuth callback, retrieve Gmail access tokens.
- **Thread Controller** handles fetching email threads and messages.
- **Email Controller** handles email-related actions.

### Google OAuth Integration
- The application integrates with **Google OAuth 2.0** to securely access Gmail data.
- The OAuth flow allows users to grant permission to the application to read and send emails.
- Laravel Socialite is used to simplify OAuth integration.

*Note*: Added Flowcharts and sequence diagrams to get a visual understanding of the application.

### Technical Challenges & Resolutions
During the integration of Laravel and the Google Graph API, the following hurdles were resolved:

- Google OAuth Sandbox: Fixed "Access Blocked" errors by explicitly adding developer emails as Test Users in the Google Cloud Console.
- Redirect URI Mismatch: Resolved redirect_uri_mismatch by synchronizing the Laravel callback routes with the authorized URIs in the Google Credentials dashboard.
- SSL/cURL Handshake: Fixed OAuth request failures by configuring the cacert.pem certificate path in the php.ini file to allow secure communication with Google servers.
- Session Persistence: Resolved "Session store not set" errors by moving OAuth callback routes from api.php to web.php, enabling necessary middleware for stateful authentication.
- Authentication Flow: Handled temporary 401 Unauthorized responses by ensuring the Gmail Access Token is correctly passed from the OAuth handshake to the background Sync Service.
