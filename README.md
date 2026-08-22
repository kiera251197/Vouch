# 💌 Vouch

![Vouch Banner](https://github.com/kiera251197/Vouch/blob/1204f5d571b0da8c01473ae70b7353d6a83444bc/frontend/assets/githubImages/vouchBanner.jpg)

### Kiera Poley 251197

Skip the bad setups. Trust your inner circle.

## Table of Contents

- [About the Project](#about-the-project)
  - [App Description](#app-description)
  - [Built With](#built-with)
  - [ERD](#erd)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [How to Install](#how-to-install)
  - [File Structure](#file-structure)
- [Features and Functionality](#features-and-functionality)
- [Concept Process](#concept-process)
  - [Ideation](#ideation)
  - [Figma File](#figma-file)
- [Future Implementation](#future-implementation)
- [Final Outcome](#final-outcome)
  - [Mockups](#mockups)
  - [Demo Video](#demo-video)
- [Conclusion](#conclusion)
- [Acknowledgements](#acknowledgements)

---

## About the Project

### App Description

Vouch is a matchmaking dating app built on a simple idea, the people who know you best make better matchmakers than an algorithm. Instead of swiping through strangers, every **Single** links their account to a trusted **Matchmaker**, a friend, sibling, or partner-in-crime who browses candidates on their behalf, Vouches for the ones worth a shot, and Vetoes the rest. Singles can also browse their own curated feed and request a vouch directly from their Matchmaker.

The app supports two distinct user roles from the same login system:

- **Singles** set up a dating profile (bio, photos, hobbies, preferences) and generate a unique 5 digit link code to share with their chosen Matchmaker
- **Matchmakers** create their own profile, enter their Single's link code to connect accounts, then browse candidates and Vouch or Veto on their Single's behalf, leaving notes explaining their reasoning

Both roles get a tailored dashboard showing their profile, their linked partner, live stats (pending vouches, vetoes, matches waiting on the other side) and a running Vouch history with status tracking.

### Built With

This project is built using a PHP/MySQL stack with Cloudinary for image hosting:

- **Frontend:** HTML, CSS, vanilla JavaScript, PHP (templated views)
- **Backend:** PHP (OOP — Controllers & Models pattern), MySQLi (prepared statements)
- **Database:** MySQL
- **Image Hosting:** Cloudinary (unsigned upload preset via cURL)
- **Local Environment:** XAMPP (Apache + MySQL)
- **Security & Auth:** PHP native sessions, `password_hash()` / `password_verify()` (bcrypt)

### ERD

![ERD Diagram](https://github.com/kiera251197/Vouch/blob/2095108e5b844fc04637374be868f42ceb467a56/frontend/assets/githubImages/vouchERD.png)

---

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any local PHP + MySQL environment)
- PHP 8+
- MySQL / MariaDB
- A [Cloudinary](https://cloudinary.com/) account (free tier is fine) with an **unsigned** upload preset
- A code editor (e.g. VS Code)

### How to Install

1. Clone the repository into your XAMPP `htdocs` folder:
   ```bash
   git clone https://github.com/<your-username>/Vouch.git
   ```
2. Start Apache and MySQL from the XAMPP control panel.
3. Import the database schema via phpMyAdmin:
   - Create a database named `vouch_db`
   - Import the provided `.sql` file (Users, Profiles, Account_Linking, Singles_Preferences, Profile_Photos, Vouching tables)
4. Update your database credentials in `backend/config/database.php` if they differ from the defaults:
   ```php
   new mysqli("127.0.0.1", "root", "", "vouch_db", 3307);
   ```
5. Add your own Cloudinary `cloudName` and `uploadPreset` in `backend/controller/profileController.php`:
   ```php
   private string $cloudName = 'your-cloud-name';
   private string $uploadPreset = 'your-upload-preset';
   ```
6. Visit `localhost/Vouch/frontend/pages/index.php` in your browser to sign up and start testing.

### File Structure

```
Vouch/
├── backend/
│   ├── config/
│   │   └── database.php
│   ├── controller/
│   │   ├── authController.php
│   │   └── profileController.php
│   ├── models/
│   │   ├── accountLinking.php
│   │   ├── profile.php
│   │   └── user.php
│   └── routes/
│       └── regenerateCode.php
│
└── frontend/
    ├── assets/
    │   └── images/
    └── pages/
        ├── index.php
        ├── index.css
        ├── setupProfile.php
        ├── setupProfile.css
        ├── dashboardSingle.php
        ├── dashboardSingle.css
        ├── dashboardMatchmaker.php
        ├── dashboardMatchmaker.css
        ├── browseCandidatesModal.php
        ├── browseCandidatesModal.css
        ├── browseCandidatesModalSingle.php
        ├── browseCandidatesModalSingle.css
        └── logout.php
```

---

## Features and Functionality

Users are greeted with a login/sign up screen, after which a guided, multi step profile setup walks them through choosing a role (**Single** or **Matchmaker**), entering their details, uploading a profile photo and gallery (via Cloudinary) and either generating or claiming a 5 digit link code to connect the two accounts together.

Once linked, **Singles** land on a dashboard showing their own profile, their Matchmaker's profile, live stats on pending and waiting vouches, their most recent vouch with full candidate details and a scrollable vouch history table with status tags and hover tooltips showing their Matchmaker's notes. They can browse curated candidates in a modal and request a vouch directly.

**Matchmakers** get a parallel dashboard, their linked Single's profile, their own profile, stats on vouches awaiting review and total vetoes and a candidate review panel where they can browse a candidate's full profile, leave a message for their Single and either **Vouch** or **Veto** all logged to the vouch history table visible on both dashboards.

---

## Concept Process

### Ideation

Dating apps typically rely on algorithmic matching or cold swiping through strangers. Vouch flips that model, it treats a trusted friend's judgment as the filter, formalising the age old practice of asking a friend to vet your dates, but with a proper interface, tracked history and shared visibility between both parties.

[Planning Document](https://drive.google.com/file/d/1OX6P4GUx8lOGQBCbQyEWwhgOuO6wPkC7/view?usp=sharing)

### Figma File

Want to view my wireframes? [Figma Document](https://www.figma.com/design/Fm0n3g1jnpOEoExkRdxxnZ/DV-200-Term-3?node-id=26-2&t=9qVzwx5GEMlkGORi-1)

---

## Future Implementation

### In-App Messaging

Building out the "Message Candidate" and "Message to Single" fields into a full conversation thread, letting Matchmakers and Singles (and eventually matched candidates) communicate directly inside the app rather than relying on external channels.

### Multiple Matchmakers per Single

Currently a Single links to one Matchmaker via a single unclaimed code. A natural extension would be allowing a Single to link several trusted Matchmakers at once, with vouch history distinguishing who suggested which candidate.

---

## Final Outcome

### Mockups

#### Desktop
![Desktop Mockup](https://github.com/kiera251197/Vouch/blob/1b38d407eb48a4e5af95b8ae20756b2f012b558c/frontend/assets/githubImages/desktopVouchMockup.png)

#### Tablet
![Tablet Mockup](https://github.com/kiera251197/Vouch/blob/1b38d407eb48a4e5af95b8ae20756b2f012b558c/frontend/assets/githubImages/tabletVouchMockup.png)

#### Mobile
![Mobile Mockup](https://github.com/kiera251197/Vouch/blob/1b38d407eb48a4e5af95b8ae20756b2f012b558c/frontend/assets/githubImages/mobileVouchMockup.png)

### Demo Video

[Vouch Walkthrough](https://drive.google.com/file/d/1M9Skrh9qcLX_jThLiolRfz6wtDfR4KOp/view?usp=sharing)

---

## Conclusion

Vouch reimagines matchmaking as a shared social experience putting a trusted third party at the centre of the process. Building the linked dual-role system (Single & Matchmaker) surfaced real challenges around session handling, file uploads and keeping two dashboards in sync from a single source of truth, all of which shaped the final architecture.

## Acknowledgements

Cloudinary, XAMPP/phpMyAdmin, and my lecturer, Tsungai Katsuro for guidance throughout the project.
