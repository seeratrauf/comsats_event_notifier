📌 Event Notifier System (CS Department)
📖 Overview

The Event Notifier System is a web-based application developed using Laravel (PHP Framework).
It is designed for the CS Department to manage and notify students about upcoming events.

The system allows administrators to create and manage events, while students can view and register for them.

🎯 Features

👨‍💼 Admin Panel
Create new events
View all events
Delete events
Dashboard with statistics (Total Events, Registrations)
<img width="1280" height="916" alt="scrnli_eh0M1oIjfi0t83" src="https://github.com/user-attachments/assets/6508c984-c0b4-4836-a23a-b99d460d07bc" />


🎓 Student Panel (Upcoming / Extendable)
<img width="1280" height="916" alt="scrnli_eh0M1oIjfi0t83" src="https://github.com/user-attachments/assets/bf773a7d-c98f-45d9-aee4-654acab9d11f" />

View available events
Register for events
Free & Paid event handling
<img width="1280" height="1014" alt="scrnli_YYZ1cmWP2HqAXn" src="https://github.com/user-attachments/assets/4447acad-e553-4850-8521-8bcb777e99ea" />

Notification system (Email/SMS)
🛠️ Technologies Used
Backend: PHP (Laravel 13)
Frontend: Blade Templates (HTML, CSS)
Database: MySQL
Server: XAMPP
Version Control: Git & GitHub
📂 Project Structure
event-notifier/
│── app/
│   └── Http/Controllers/Admin/
│       ├── DashboardController.php
│       └── EventController.php
│
│── resources/views/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   └── events/
│   │       ├── index.blade.php
│   │       └── create.blade.php
│
│── routes/
│   └── web.php
│
│── database/
│   └── migrations/
⚙️ Installation Guide
1️⃣ Clone Repository
git clone https://github.com/your-username/event-notifier.git
cd event-notifier
2️⃣ Install Dependencies
composer install
3️⃣ Setup Environment
cp .env.example .env
php artisan key:generate
4️⃣ Configure Database

Edit .env file:

DB_DATABASE=event_notifier
DB_USERNAME=root
DB_PASSWORD=
5️⃣ Run Migrations
php artisan migrate
6️⃣ Run Server
php artisan serve
🌐 Open in Browser
http://127.0.0.1:8000
📸 Screenshots (Optional)
Admin Dashboard<img width="1280" height="699" alt="scrnli_UnB6DPT5TI75rX" src="https://github.com/user-attachments/assets/9c8be2aa-f287-4afe-bf7a-8cf1ea1af4f4" />


Events Page
<img width="1280" height="808" alt="scrnli_KDAHISP33i8rDq" src="https://github.com/user-attachments/assets/d6b9b969-6f11-44db-be1c-f5fe1e0f8d4c" />

Create Event Form
🚀 Future Enhancements
Student registration system
Payment integration (for paid events)
Email & SMS notifications
Event categories & filtering
Admin authentication & roles
👨‍🎓 Author

Seerat Rauf
BS Software Engineering – COMSATS University Islamabad (Vehari Campus)

📜 License

This project is for educational purposes.
