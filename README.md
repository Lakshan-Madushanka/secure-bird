<div align="center">
    
# <img src="https://github.com/user-attachments/assets/8097dd59-7b96-4499-8cf2-b6898e39de82" width="300" alt="Secure Bird Logo">

**[About](https://github.com/Lakshan-Madushanka/secure-bird/blob/main/resources/views/pages/about.md)** |
**[How It Works](https://github.com/Lakshan-Madushanka/secure-bird/blob/main/resources/views/pages/howItWorkd.md)** 

<a href="https://github.com/Lakshan-Madushanka/secure-bird" target="_blank">
<img src="https://github.com/Lakshan-Madushanka/secure-bird/blob/main/public/images/favicon.svg" width="300" alt="Secure Bird Logo">
</a>

 **Share your confidential information securely.**
</div>

## Overview
Secure Bird is a secure data-sharing system that enables you to share sensitive information instantly.

<a href="https://www.youtube.com/embed/254ZJYzKInQ" target="_blank" title="Secure Bird Demo">
<img src="https://github.com/user-attachments/assets/5c3a51c2-7db7-433b-86b7-57b1f033fdc0" width="853">    
</a>

## Features
- 🔓 No Login Required
- 🗃️ Supports Media (images, videos, etc.)
- 🔐 Offers Various Types of Security Constraints:
  - 🔑 Password Protection
  - 📅 Expiration Date
  - 🔢 Visit Limits
- 📧 Status Notifications Sent to the Owner via Email (when a reference email address is provided).

## Requirements
- PHP >= 8.1
- [Queue Connection (sync not working)](https://laravel.com/docs/11.x/queues)
- [Broadcasting](https://laravel.com/docs/11.x/broadcasting)
- [Mail Driver (when reference email field is provided)](https://laravel.com/docs/11.x/mail)

## Installation
1. Clone the repository.
2. Run ```composer install``` command
3. Create .env file and copy contents of .env.example to it.
4. Run ```php artisan key:generate``` command to generate application key.
5. Setup required [drivers](#requirements).
6. Build assets `npm run build` or `npm run dev`

