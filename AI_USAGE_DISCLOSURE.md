# AI Usage Disclosure - LockIn Project

## Overview

This project was developed through a combination of independent research, learning from educational resources (YouTube tutorials, lab exercises, team project), and incremental AI assistance. I took significant initiative in planning, designing, and problem-solving throughout the development process. This document provides transparency about all assistance received.

---

## AI Tools Used

### Primary Tool
- **Tool Name:** Cursor AI (Auto/Cursor IDE Assistant)
- **Provider:** Cursor
- **Model/Version:** Auto (Agent Router)
- **Primary Use Period:** Throughout incremental development

---

## Development Process & Learning Approach

### My Initiative & Research Process

I took a **research-driven, learning-focused approach** to development:

1. **Planning Phase:** Created detailed sketches and visual designs in notebook before coding
2. **Learning Resources:** Studied YouTube tutorials for Pomodoro timer implementation and login/signup patterns
3. **Reference Materials:** Collected screenshots from 4 different Pomodoro apps for design inspiration
4. **Problem-Solving:** When AI suggested complex solutions (e.g., PHPMailer), I researched alternatives and developed simpler solutions
5. **Code Understanding:** Researched and understood all AI-generated code before implementation, building on concepts learned in class and from YouTube
6. **Iterative Refinement:** Heavily modified all AI suggestions to match my exact project requirements

---

## Extent of AI Usage

### Overall Project Contribution Estimate
**AI Contribution: ~35%**  
**My Contribution: ~65%**

### Detailed Breakdown by Component

#### 1. **Project Planning & Architecture** (0% AI, 100% Me)
- **My Contribution:** 100%
- **Process:** 
  - Created detailed sketches in notebook
  - Planned folder structure after learning MVC in class
  - Designed database schema independently
  - Organized all folders and files
- **AI Assistance:** None - completely original planning

#### 1. **Project Planning & Architecture** (0% AI, 100% Me)
- **My Contribution:** 100%
- **Process:** 
  - Created detailed sketches in notebook
  - Planned folder structure after learning MVC in class
  - Designed database schema independently
  - Organized all folders and files
- **AI Assistance:** None - completely original planning

#### 2. **Database Design** (10% AI, 90% Me)
- **My Contribution:** 90%
- **Process:**
  - Wrote initial database schema independently
  - Designed all tables, relationships, and constraints
  - Did not want to overcomplicate the structure
- **AI Assistance:** ~10% - Cursor provided some improvements over time (optimization suggestions)
- **Files:** `Database/LockIn.sql`

#### 3. **Connection & Authentication** (20% AI, 80% Me)
- **My Contribution:** 80%
- **Process:**
  - Borrowed concepts from lab exercises for register/login PHP
  - Studied YouTube tutorials for authentication patterns
  - Implemented connection.php independently
  - Understood and modified all code
- **AI Assistance:** ~20% - Code review, syntax help, error handling patterns
- **Files:** `Connection/connection.php`, `Authentication/login.php`, `Authentication/register.php`

#### 4. **Account Activation System** (30% AI, 70% Me)
- **My Contribution:** 70%
- **Process:**
  - Asked Cursor how to do account activation
  - Cursor suggested email-based activation (PHPMailer)
  - **I spent an entire day researching PHPMailer** - found it too complex as the errors were too complex to handle
  - **I independently developed a frontend popup solution** (current implementation)
  - This demonstrates significant problem-solving initiative
- **AI Assistance:** ~30% - Initial suggestion, but I developed the final solution
- **Files:** `Authentication/activate.php`, `Authentication/register.php`
- **Future Improvement:** I plan to explore email activation later

#### 5. **Pomodoro Timer** (15% AI, 85% Me)
- **My Contribution:** 85%
- **Process:**
  - Watched YouTube video for Pomodoro timer implementation 
  - Implemented core timer logic independently
  - Created state management system
  - Designed timer UI and functionality
- **AI Assistance:** ~15% - Minor refactoring, code organization suggestions
- **Files:** `PomodoroPages/pomodoro.js`, `PomodoroPages/pomodoro-html.php`

#### 6. **Styling & UI Design** (50% AI, 50% Me)
- **My Contribution:** 50%
- **Process:**
  - Created folder with screenshots from 4 different Pomodoro apps
  - Showed images to Cursor asking "how do I achieve this?"
  - **Heavily tweaked all AI-generated CSS** to match my exact vision
  - Adjusted colors, spacing, layouts based on personal preferences
  - Made responsive design improvements
- **AI Assistance:** ~50% - Generated initial CSS based on reference images for the analytics, profile and modal pages, history session
- **My Modifications:** Significant customization of all styling
- **Files:** All CSS files

#### 7. **Login & Signup Pages** (10% AI, 90% Me)
- **My Contribution:** 90%
- **Process:**
  - Watched YouTube tutorials for login/signup patterns
  - Borrowed code from lab project and team project (I built the interface for the team project)
  - Implemented form validation and submission logic
- **AI Assistance:** ~10% - Code organization, error handling
- **Files:** `LoginAndSignUpPages/loginandsignup.js`, `LoginAndSignUpPages/login-html.php`, `LoginAndSignUpPages/signup-html.php`

#### 8. **Feynman Notes** (25% AI, 75% Me)
- **My Contribution:** 75%
- **Process:**
  - Used YouTube div style as reference
  - Imported and integrated all components independently
  - Designed the three-step reflection interface
- **AI Assistance:** ~25% - Code structure, auto-save implementation
- **Files:** `FeynmanPages/feynmannotes-html.php`, `FeynmanPages/feynmannotes.js`

#### 9. **Backend CRUD Operations** (50% AI, 50% Me)
- **My Contribution:** 50%
- **Process:**
  - Described what functionality was needed
  - Cursor generated the PHP code
  - I researched and understood all generated code
  - Tested all endpoints to ensure backend works correctly
  - Modified code as needed for project requirements
- **AI Assistance:** ~50% - Generated CRUD endpoints based on my descriptions
- **My Contribution:** Requirements definition, testing, understanding, modification
- **Files:** 
  - `Tasks/create_task.php`, `Tasks/get_tasks.php`, `Tasks/update_task.php`, `Tasks/delete_task.php`
  - `Sessions/create_session.php`, `Sessions/get_session.php`, `Sessions/delete_session.php`
  - `Notes/save_notes.php`, `Notes/get_notes.php`, `Notes/delete_notes.php`
  - `Drafts/save_draft.php`, `Drafts/get_draft.php`
  - `User/update_profile.php`, `User/delete_account.php`

#### 10. **JavaScript Files (Most)** (70% AI, 30% Me)
- **My Contribution:** 30%
- **Process:**
  - Described desired functionality to Cursor
  - Cursor generated JavaScript code
  - I researched and understood all code
  - Tested and modified as needed
- **AI Assistance:** ~70% - Generated JS based on my descriptions
- **Exceptions (based on YouTube videos, Lab Project and Team Project):**
  - `LoginAndSignUpPages/loginandsignup.js` - 90% me (YouTube + lab concepts)
  - `PomodoroPages/pomodoro.js` - 90% me (YouTube tutorial)
- **Files:** 
  - `Analytics/lockinanalytics.js` 
  - `History/history.js` 
  - `FeynmanPages/feynmannotes.js` 
  - `User/profile.js` 

#### 11. **Shared Utilities** (50% AI, 50% Me)
- **My Contribution:** 50%
- **Process:**
  - Identified need for code reuse (my initiative)
  - Asked Cursor to create shared utilities
  - Borrowed SweetAlert concepts from team project (learned from Wendy)
  - Integrated across entire codebase
- **AI Assistance:** ~50% - Created `apiRequest()` and `SwalAlert` wrapper
- **Files:** `SharedUtilities/utils.js`, `SharedUtilities/utils-css.php`

#### 12. **Navigation Bar** (40% AI, 60% Me)
- **My Contribution:** 60%
- **Process:**
  - Designed navigation structure
  - Implemented user dropdown concept
- **AI Assistance:** ~40% - Code implementation, dropdown functionality
- **Files:** `SharedNavigationBar/navbar.js`, `SharedNavigationBar/navbar-css.php`

#### 13. **Testing** (0% AI, 100% Me)
- **My Contribution:** 100%
- **Process:**
  - Tested all backend endpoints to ensure functionality
  - Verified all features work correctly
  - Debugged issues independently
- **AI Assistance:** None

#### 14. **Documentation** (100% Me)
- **My Contribution:** 100%
- **Process:**
  - Provided all project knowledge and context
  - Explained architecture decisions

---

## Specific AI Interactions & My Modifications

### Example 1: Account Activation - Problem Solving Initiative
**Date:** 13/12/2025
**Prompt:** "How do I do account activation?"
**AI Suggestion:** Email-based activation using PHPMailer
**My Response:**
- Spent an entire day researching and implenting PHPMailer, I have all the files downloaded
- Found it too complex as the errors were too complex to handle
- **I independently thought of a frontend popup solution and Cursor helped me figure this out**
- Implemented token-based activation via popup modal
- This demonstrates significant problem-solving and initiative

**Extent of Use:** 30% AI (suggestion), 70% Me (final solution)

---

### Example 2: Styling - Design-Driven Development
**Date:** N/A - Throughout development
**Process:**
1. I collected screenshots from 4 different Pomodoro apps
2. Created folder with reference images
3. Showed images to Cursor: "How do I achieve this?"
4. Cursor generated initial CSS
5. **I heavily tweaked all styling** to match my exact vision and taste
6. Adjusted colors, spacing, layouts, responsive breakpoints

**Extent of Use:** 50% AI (initial generation ), 50% Me (significant customization)

---

### Example 3: Backend CRUD - Description-to-Code
**Date:** N/A- Throughout development
**Process:**
1. I described needed functionality (e.g., "Create endpoint to save tasks")
2. Cursor generated PHP code
3. **I researched and understood all generated code**
4. I tested endpoints
5. I modified code as needed

**Extent of Use:** 50% AI (code generation), 50% Me (requirements, understanding, testing, modification)

---

### Example 4: Shared Utilities - Code Reuse Initiative
**Date:** 14/12/2025
**Prompt:** "Remove redundant code, create shared utilities for common elements"
**AI Output:** Created `apiRequest()` helper and `SwalAlert` wrapper
**My Modifications:**
- Integrated across 10+ files manually
- Borrowed SweetAlert concepts from team project (learned from Wendy)
- Customized colors to match brand
- Tested all integrations

**Extent of Use:** 50% AI (utility creation), 50% Me (integration, customization)

---

## Learning Resources Used

### YouTube Tutorials
- **Pomodoro Timer Implementation:** https://youtu.be/sVSAklXy1uE?si=QSeVsr6NTxthUa6o
- **Login/Signup Pages:** https://youtu.be/bVl5_UdcAy0?si=j7mVIzepHiI-kmtR
- **Feynman Notes Div Style (Same video on Login. I also watched a couple of videos on this channel):** https://youtu.be/bVl5_UdcAy0?si=j7mVIzepHiI-kmtR

### Lab Exercises & Team Projects
- **Register/Login PHP:** Borrowed concepts and code from class lab
- **SweetAlert:** Borrowed code concepts from team project (learned from Wendy)
- **Team Project Interface:** I built the interface for the team project, which I later adapted for this project so it would be similar styling and feel

### Design References
- **Pomodoro App Screenshots:** Folder with screenshots of different app designs 
- **Visual Planning:** Sketches in notebook (attached in write-up)

### Project Progression Documentation
- **Initial Codebase:** Folder containing project progression over weeks, showing incremental development (to be uploaded as zip folder)
- **Purpose:** Demonstrates the evolution of the project and my learning process over time

---

## Human Validation & Post-Processing

### Verification Steps Taken
1. **Code Research:** I researched and understood all AI-generated code, building on concepts learned in class and from YouTube
2. **Testing:** I tested all backend endpoints to ensure functionality
3. **Integration:** I manually integrated all code into the project
4. **Customization:** I heavily modified all AI suggestions to match my exact requirements
5. **Problem-Solving:** I developed alternative solutions when AI suggestions were too complex
6. **Learning:** I studied tutorials, lab exercises, and team projects for foundational knowledge

### Changes Made to AI Outputs
- **Styling:** Heavily tweaked all CSS to match my exact design vision
- **Account Activation:** Developed simpler frontend solution instead of PHPMailer
- **Code Integration:** Manually integrated all utilities across codebase
- **Error Handling:** Enhanced based on testing
- **Responsive Design:** Adjusted breakpoints and layouts
- **Code Understanding:** Researched and verified all AI code before use

---

## Data Disclosed to AI Tools

### Code Shared
- **Scope:** Entire project codebase (for context during assistance)
- **Purpose:** AI needed project context to provide relevant suggestions


### Design References
- **Screenshots:** 4 different Pomodoro app designs (shared with AI for styling reference)
- **Purpose:** To help AI understand desired visual style

### Sensitive Data
- **Database Credentials:** Never shared (stored in `env/connect.env`, not in code)
- **User Data:** Never shared, user fake filler for account set ups.

---

## Source Code Comments

### Areas with Significant AI Assistance

```php
// Tasks/create_task.php, Sessions/create_session.php, etc.
// AI-generated: ~50% - Generated based on my functional descriptions
// Me: Researched, understood, tested, and modified all code
// My Contribution: Requirements definition, testing, integration

// SharedUtilities/utils.js
// AI-generated: apiRequest() helper and SwalAlert wrapper
// Me: Integrated across 10+ files, customized theme, borrowed from team project
// My Contribution: 50% - Integration and customization
```

### Areas with My-Dominant Work

```php
// Connection/connection.php
// Me: 80% - Implemented independently, borrowed lab concepts
// AI: 20% - Minor improvements over time

// Authentication/login.php, register.php
// Me: 80% - YouTube tutorials + lab concepts
// AI: 20% - Code organization

// PomodoroPages/pomodoro.js
// Me: 85% - YouTube tutorial + independent implementation
// AI: 15% - Minor refactoring
```

---

## Academic/Professional Statement

### My Contribution & Initiative

I, Stephanie Klenam Klomegah, developed the LockIn application as my final project. I took significant initiative throughout the development process:

#### Planning & Design (100% Me)
- I created detailed sketches and visual designs in notebook
- I planned the entire project architecture after learning MVC in class
- I organized all folders and files independently
- I designed the database schema

#### Learning & Research (100% Me)
- I studied YouTube tutorials for Pomodoro timer, login/signup patterns
- I collected and analyzed 4 different Pomodoro app designs for inspiration
- I borrowed and adapted concepts from class lab exercises and team projects
- I researched and understood all AI-generated code before implementation, building on concepts from class

#### Problem-Solving Initiative
- **Account Activation:** When AI suggested complex PHPMailer solution, I spent a full day researching it, found it too complex, and independently thought of a simpler frontend popup solution
- **Styling:** Showed AI reference images and heavily customized all generated CSS to match my exact vision
- **Code Understanding:** Researched every AI-generated function to ensure I understood the code since I already understood concepts from classes and YouTube

#### Development Process
- **Backend CRUD:** Described functionality needs to AI, then researched, tested, and modified all generated code
- **JavaScript:** Most JS files were AI-generated based on my descriptions, but I researched and understood all code
- **Integration:** Manually integrated all code across the project
- **Testing:** Tested all backend endpoints to ensure functionality

### AI Assistance

AI tools (Cursor AI) were used as a **development aid** for:

- **Code Generation:** Generating CRUD operations and JavaScript based on my functional descriptions (~50% of backend, ~70% of JS, with exceptions for login/signup and Pomodoro which are 90% me)
- **Styling:** Generating initial CSS based on reference images I provided (~50% of styling for analytics, profile, modal, and history pages)
- **Code Organization:** Suggestions for code structure and organization
- **Problem-Solving:** Initial suggestions that I then researched and often modified

**All AI-generated code was:**
- Researched and understood by me
- Tested thoroughly by me
- Heavily modified to fit project requirements
- Integrated into the codebase by me

### Learning Outcomes

Through this project, I learned:
- Full-stack web development (PHP, JavaScript, MySQL)
- RESTful API design and implementation
- Database design and relationships
- Session management and authentication
- Responsive web design
- Code organization and architecture (MVC concepts)
- Problem-solving and research skills
- How to effectively use AI as a development tool while maintaining understanding

The use of AI was a learning tool that helped me understand implementation patterns and solve specific technical challenges. However, the project design, architecture, planning, and significant portions of implementation (especially core features like Pomodoro timer, authentication, and database design) are my own work, supported by educational resources and research.

---

## Quantification Summary

| Component | My % | AI % | Learning Resources | Notes |
|-----------|------|------|-------------------|-------|
| Planning & Architecture | 100% | 0% | Class (MVC) | Sketches, folder organization |
| Database Design | 90% | 10% | Independent | Cursor provided optimizations |
| Connection & Auth | 80% | 20% | YouTube + Lab | Borrowed lab concepts |
| Account Activation | 70% | 30% | Research (PHPMailer) | I developed final solution |
| Pomodoro Timer | 85% | 15% | YouTube | Core logic independent |
| Styling & UI | 50% | 50% | Reference images | Heavily customized (analytics, profile, modal, history) |
| Login/Signup Pages | 90% | 10% | YouTube + Lab + Team Project | Borrowed code, I built team interface so i just repurposed some parts|
| Feynman Notes | 75% | 25% | YouTube | Imported independently |
| Backend CRUD | 50% | 50% | Research | Described needs, understood code |
| JavaScript (Most) | 30% | 70% | Research | Described needs, understood code |
| JavaScript (Exceptions) | 90% | 10% | YouTube + Lab + Team | Login/signup & Pomodoro |
| Shared Utilities | 50% | 50% | Team project (SweetAlert from Wendy) | Integration & customization |
| Navigation Bar | 60% | 40% | Independent | Designed structure |
| Testing | 100% | 0% | Independent | All testing by me |
| Documentation | 100% | 0% | My knowledge | I provided all content |
| **Overall Project** | **~65%** | **~35%** | **Multiple sources** | **Strong initiative** |

---

## Key Demonstrations of Initiative

1. **Planning:** I created detailed sketches and planned the entire architecture
2. **Learning:** I studied YouTube tutorials, lab exercises, and team projects for foundational knowledge
3. **Design Research:** I collected and analyzed 4 different app designs for inspiration
4. **Problem-Solving:** I developed an alternative solution for account activation when the AI suggestion, i.e PHPMailer was too complex
5. **Code Understanding:** I researched and understood all AI-generated code, building on class concepts
6. **Customization:** I heavily modified all AI suggestions to match my exact requirements and taste
7. **Testing:** I independently tested all functionality to ensure the backend works correctly
8. **Organization:** I solely organized all folders and files based on MVC learning from class
9. **Project Progression:** I maintained an initial codebase folder showing progression over weeks

---

## Declaration

I, Stephanie Klenam Klomegah, declare that:

- The project concept, design, architecture, and planning are my original work
- I took significant initiative in learning, research, and problem-solving throughout the development process
- AI was used as a development tool to generate code based on my descriptions, which were informed by my understanding of concepts taught in class
- All AI-generated code was researched, understood, tested, and modified by me
- I am responsible for all code in the final project
- Educational resources (YouTube tutorials, lab exercises, team projects) were used for learning foundational concepts
- I have maintained documentation of project progression over time (initial codebase folder)
- This disclosure accurately represents the extent of AI usage and my contributions

**Date:** 15/12/2025
**Project:** LockIn - Final Project

---
