# mailmanager
A simple application designed to manage Email, Domains, and Aliases on a standalone Postfix + Dovecot system that uses MySQL as its dynamic storage backend. The application is minimal, far from perfect, and uses a lightweight login method based on the "secret.txt" file, with password hashes generated using any online MD5 tool.
Message delivery in this setup typically uses "LMTP", as long as your Postfix configuration is directed to Dovecot’s LMTP service.

Before getting started, prepare the database.
Create a database named "mailserver", then import the provided SQL file.
After that, open `config.php` and update the password to match your database credentials.

To access the panel, open:

http://localhost/mailmanager

Use the default credentials:

User: admin
Password: admin
