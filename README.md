# 🌊 HydroFlow: Water Supply Management System

A comprehensive, robust, and web-based **Water Supply Management System** tailored specifically for agricultural irrigation. Developed as a BCA Final Year Project, this application streamlines the management of agricultural customers, motor operations, dynamic water supply tracking, and automated billing workflows with a modern, high-performance UI.

---

## 🚀 Key Features

### 💻 Smart Dashboard & Analytics
- **Live Analytics Bento Grid:** Real-time tracking of total customers, active motors, today's water supply hours, and revenue collection.
- **Weekly Supply Trends:** Visualized supply data using interactive bar charts (Chart.js) to monitor usage patterns over the last 7 days.
- **Quick Action Center:** One-click shortcuts for adding new supply, generating bills, and registration.

### 🚜 Agricultural Resource Management
- **Customer & Farm Registry:** Maintain detailed records of farmers, connection numbers, pipe sizes, and village locations.
- **Motor Fleet Control:** Track pumping units, their Horsepower (HP) ratings, and operational status across different sources.

### 💧 Supply & Billing Automation
- **Real-time Supply Logs:** Log start and end times with automated duration calculation (in hours).
- **Dynamic Billing Engine:** Instant bill generation based on motor power ratings and precise usage time.
- **Professional Invoicing:** Generate and print industry-standard A4 format invoices/receipts directly from the web interface.

### 🌐 Advanced UI & UX
- **Multi-Language Support:** Fully localized in **English** and **Hindi** with persistent user preference saving.
- **Premium Aesthetics:** Sleek Glassmorphism design using Material Design principles, CSS Grid/Flexbox, and native Dark/Light mode syncing.
- **Offline First Architecture:** All assets (CSS, JS, Fonts, Icons) are hosted locally, ensuring the system works perfectly without an internet connection.
- **Custom Avatar System:** Dynamic initials-based avatars for users and customers.

---

## 🛠️ Technology Stack

- **Backend:** Core PHP (Procedural with MVC-inspired separation)
- **Database:** MySQL Server (Relational Schema)
- **Frontend:** 
  - HTML5 & CSS3 (Custom Design System with Variables)
  - JavaScript & jQuery
  - **Chart.js** (Data Visualization)
  - **DataTables** (Advanced Table Management)
  - **Material Symbols** (UI Iconography)
- **Environment:** XAMPP (Apache/MySQL)

---

## ⚙️ Installation Guide

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Dark-Priyanshu/water_Supply_System-HydroFlow-.git
   ```

2. **Server Placement:**
   Move the project folder into your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\waterS`).

3. **Database Setup:**
   - Start Apache and MySQL via the XAMPP Control Panel.
   - Access `http://localhost/phpmyadmin`.
   - Create a new database named `watersupply`.
   - Import the `database.sql` file located in the project root.

4. **Launch Application:**
   Open your browser and visit:
   ```
   http://localhost/waterS/
   ```

---

## 📁 Project Structure

```text
/waterS
├── assets/          # Local CSS, JS, and Fonts
├── config/          # Database and Global Constants
├── controllers/     # Business Logic & Form Handlers
├── includes/        # UI Components (Header, Sidebar, Footer)
├── lang/            # Translation Files (en.php, hi.php)
├── models/          # Database Interaction Classes
├── views/           # Page Templates (Customers, Billing, Reports)
├── database.sql     # Database Schema Export
└── index.php        # Application Entry Point
```

---

## 👨‍💻 Academic Context

This project was developed for the **Bachelor of Computer Applications (BCA)** degree.

- **Student Name:** Priyanshu Shakya
- **Enrollment No:** AZ149050055
- **Study Center:** Subhash Academy (9050)
- **Project Guide:** Mr. Mahaboob Hussain

---

## ⚖️ License
This project is open-source and available under the [MIT License](LICENSE).
