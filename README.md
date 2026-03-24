# 🌊 HydroFlow: Water Supply Management System

A comprehensive, robust, and web-based **Water Supply Management System** tailored specifically for farm irrigation. Developed as a BCA Final Year Project, this application streamlines the management of agricultural customers, motor operations, dynamic water supply tracking, and automated billing workflows.

---

## 🚀 Key Features

*   **Customers & Farm Management:** Easily maintain records of farm owners, individual connection locations, and contract numbers.
*   **Motor Operations Control:** Track active motors, specific water-pumping capacities (HP), active status, and geographical village locations.
*   **Automated Supply Tracking:** Log `start` and `stop` times for dynamic water supply. The system calculates precise supply duration in hours instantly.
*   **Dynamic Billing Automation:** Automatic computation of total costs based on exact water usage duration and precise motor-rate-per-hour metrics.
*   **Professional Invoice Generation:** Format and print professional, industry-standard A4 invoices/receipts directly from the dashboard.
*   **Payment Ledger Tracking:** Comprehensive tracking of total generated bill values versus received payments, allowing clear revenue reporting.
*   **Premium Interactive UI:** Features natively-compiled, totally offline CSS architecture with sleek Glassmorphism designs and automatic OS-level Dark Mode syncing.

---

## 🛠️ Technology Stack

*   **Backend:** Core PHP 
*   **Database:** MySQL Server
*   **Frontend UI:** HTML5, Tailwind-compiled CSS (Offline Native Static Architecture), jQuery, DataTables
*   **Environment Setup:** XAMPP (Apache)

---

## ⚙️ Installation Instructions

**1. Clone the Repository:**
```bash
git clone https://github.com/Dark-Priyanshu/water_Supply_System-HydroFlow-.git
```

**2. Setup XAMPP Server:**
Extract or move the project folder (rename to `waterS`) directly into your XAMPP root directory (`C:\xampp\htdocs\waterS`).

**3. Initialize Database Ecosystem:**
*   Start your Apache and MySQL modules in the XAMPP Control Panel.
*   Open phpMyAdmin via your browser at `http://localhost/phpmyadmin`.
*   Create a clean, new database named `watersupply`.
*   Import the included `database.sql` file provided in the repository root folder.

**4. Run the Application:**
Launch your browser and navigate to the local portal:
```
http://localhost/waterS/
```

**5. Default Admin Credentials:**
*   **Admin Access:** Ensure you have added an admin entry in your newly populated database to bypass the login portal securely. 

---

## 👨‍💻 Developer Note
This repository constitutes a final year project submission for a Bachelor of Computer Applications (BCA) degree. It demonstrates proficiency in full-stack procedural web development, relational database integration, and modern UI engineering.
