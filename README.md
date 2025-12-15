# LockIn - Focus. Learn. Reflect.

A productivity web application that combines Pomodoro-style focus timers with Feynman-inspired reflection techniques to help you study more effectively.


##  LockIn Live And Hosted on School Server
Live site: http://169.239.251.102:341/~stephanie.klomegah/FinalProject_Klomegah

## Features

###  Pomodoro Timer
- **25-minute focus sessions** with break periods
- **Task management** integrated with timer
- **Session tracking** to monitor your productivity
- **Multiple modes:** Pomodoro, Short Break (5min), Long Break (15min)

###  Feynman Reflection Notes
- **Three-step reflection process:**
  1. Initial Explanation - Write what you learned
  2. Simplified Explanation - Explain it simply
  3. Key Concepts - Extract 3 main ideas 
- **Auto-save drafts** every 30 seconds (never lose your work)
- **Linked to sessions** - Reflect on each completed Pomodoro

###  Analytics & Reports
- **Visual charts** showing your study progress
- **Statistics:** Total sessions, study hours, task completion rate, streaks
- **Time period filters:** All Time, This Month, This Week, Today
- **Feynman notes analytics** - Track your reflection habits

### Session History
- **View all past Pomodoro sessions and Feynman Notes**
- **Filter by time period** (All, Today, Week, Month)
- **View associated Feynman notes** for each session
- **Delete sessions** you no longer need

###  User Profile
- **Update personal information** (name, email)
- **Change password** securely
- **Account management** with safe deletion option

---


##  Technology Stack

### Backend
- **PHP** - Server-side logic
- **MySQL** - Database
### Frontend
- **HTML5** - Structure
- **CSS** - Styling 
- **JavaScript** - Client-side logic
- **Chart.js** - Data visualization
- **SweetAlert2** - Enhanced alerts and confirmations

### Architecture
- **RESTful API** design
- **Session-based authentication**
- **Feature-based folder organization**
- **Separation of concerns** (UI vs. API)

---

##  Security Features

-  **Password hashing** using PHP's `password_hash()`
-  **Prepared statements** to prevent SQL injection
-  **Session management** for authentication
-  **Input validation** on both client and server side
-  **Environment variables** for sensitive configuration
-  **Account activation** system

---

##  How to Use

### 1. **Create an Account**
   - Click "Sign Up" on the landing page
   - Fill in your information
   - Activate your account using the provided token

### 2. **Start a Pomodoro Session**
   - Log in and navigate to the Pomodoro timer
   - Add tasks you want to complete
   - Select timer mode (Pomodoro, Short Break, Long Break)
   - Click "START" and focus for 25 minutes

### 3. **Reflect with Feynman Notes**
   - After completing a session, you'll be redirected to Feynman notes
   - Write your initial explanation
   - Simplify it in your own words
   - Extract key concepts
   - Click "Save" to store your reflection

### 4. **Track Your Progress**
   - Visit **Analytics** to see charts and statistics
   - Check **History** to review past sessions
   - View your profile to manage account settings

---

## Design 

- **Clean and minimal** - Focus on productivity, not distractions
- **Purple and white theme** - Professional and calming
- **Responsive design** - Works on desktop, tablet, and mobile
- **Intuitive navigation** - Easy to find what you need

---

##  Development

### File Naming Conventions
- `*-html.php` - HTML structure files
- `*-css.php` - CSS styling files
- `*.js` - JavaScript logic files
- `*.php` - API endpoints (GET, POST, PUT, DELETE)

### API Endpoints
All API endpoints follow RESTful conventions:
- `GET` - Retrieve data
- `POST` - Create new resource
- `PUT` - Update existing resource
- `DELETE` - Remove resource

---


##  Author

**Stephanie Klenam Klomegah**

---

## Acknowledgments & References

- **Pomodoro Technique Concept** - Francesco Cirillo
- **Feynman Technique Concept** - Richard Feynman
- **Chart.js** - Data visualization library
- **SweetAlert2** - Beautiful alert library
- **Poppins Font** - Google Fonts

---

## AI Assistance Disclosure
- **[AI_USAGE_DISCLOSURE.md](./AI_USAGE_DISCLOSURE.md)** - AI assistance disclosure

---


## Extra Documentation Folders

 This folder `ExtraDocumentation` contains some screenshots of progress and sub-folders of initial iterations and planning materials (screenshots of other Pomodoro websites I wanted to emulate, particularly the styling) in `ProgressFolder` and `DesignInspiration` respectively.

---

## Known Issues & Future Improvements

### Current Limitations
- Account activation uses frontend popup (email activation planned)
- Timer settings are fixed (customizable durations planned)
- No background music feature (planned for future)
- Analytics data cannot be exported (planned for future)
- Database not optimized for large datasets and scalability (currently sufficient for small user base)

### Planned Features
- Email-based account activation
- Customizable Pomodoro durations
- Background music/ambient sounds
- Export analytics data
- Mobile app version


