# 📦 StockMaster Inventory & Revenue Tracker

A robust, "Naija-focused" Inventory Management System built with **PHP and MySQL**. This application is designed for small to medium-scale retail businesses (like a water depot or kiosk) to track stock levels, manage supply deliveries, and monitor daily revenue in real-time.



## 🚀 Key Features

* **Real-time Stock Tracking:** Automatically monitors inventory levels with visual alerts.
* **Smart Status Badges:** Items dynamically change status:
    * 🟢 **Good**: Healthy stock levels.
    * 🟡 **Low Stock**: Hits a custom threshold (Restock needed).
    * 🔴 **Out of Stock**: Flashing alert when quantity reaches zero.
* **Unified Activity Log:** A scrollable live feed that tracks every Sale and Restock with timestamps.
* **Financial Dashboard:** Real-time calculation of **Today's Revenue** and total items sold.
* **Automated Reporting:** Generates a professional, printable **PDF Sales Report** using CSS Print Media.
* **Bulk Inventory Editing:** Easily update stock quantities for large supply deliveries.

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3 (Custom Grid/Flexbox), JavaScript.
* **Backend:** PHP (Procedural).
* **Database:** MySQL.
* **Tools:** XAMPP, VS Code.

## 📸 Screenshots

> **Tip:** You can take screenshots of your app and put them in a folder named `screenshots` inside your project, then link them here!

## ⚙️ Installation

1.  Clone the repository:
    ```bash
    git clone [https://github.com/LordS7ark/StockMaster-Inventory.git](https://github.com/LordS7ark/StockMaster-Inventory.git)
    ```
2.  Move the folder to your local server directory (e.g., `C:/xampp/htdocs/`).
3.  Import the `inventory_db.sql` file into your **phpMyAdmin**.
4.  Open your browser and navigate to `http://localhost/inventory`.

## 📈 Learning Outcomes

Through this project, I strengthened my skills in:
- **Relational Databases:** Linking products to sales history via IDs.
- **SQL Aggregate Functions:** Using `SUM()` and `COUNT()` for financial reporting.
- **Data Integrity:** Implementing logic to prevent negative stock levels.
- **UX/UI Design:** Creating a responsive, color-coded dashboard for better business decision-making.

---
Built with 💻 by **LordS7ark**
