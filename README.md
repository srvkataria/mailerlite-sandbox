# mailerlite-sandbox

MailerLite Sandbox App to manage subscribers & fields using web interface & HTTP JSON endpoints.

[App Demo (web interface) Link](https://www.youtube.com/watch?v=xCi9ggfJntM&feature=youtu.be)

## Installation

Install & Run on any PHP based Web Server like XAMPP, WAMP etc.

## Usage

Update, `config.php` file located in the root directory, with your MySQL user credentials

`config.php`

```
	$dbhost = "localhost";
	$dbuser = "admin"; // Add DB Username
	$dbpass = "admin@123"; // Add DB Password
	$db = "mailerlite";
	
```

Import `mailerlite.sql` (located in the root directory) in MySQL 

The API usage, instructions & examples are documented here, [MailerLite SandBox API Docs](https://documenter.getpostman.com/view/9252054/SVzw51h9?version=latest#intro)
