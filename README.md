# mailmanager
This application is a simple panel designed to manage Email, Domains, and Aliases on a Postfix + Dovecot setup that uses MySQL as its dynamic storage backend. It is minimal, far from perfect, and uses a lightweight login mechanism through a secret.txt file. The login password must be stored as an MD5 hash, which can be generated using any online MD5 tool.

When creating or editing an email account, the password is automatically hashed using the Dovecot-style CRYPT512 method, ensuring full compatibility with Dovecot without requiring additional processing. Message delivery typically works through LMTP, as long as Postfix is configured to use Dovecot’s LMTP service.

To start using the application, create a database named “mailserver” and import the provided SQL file. Then open config.php and adjust the database connection settings as needed.

To access the panel, go to http://localhost/mailmanager and log in using the default credentials: username admin and password admin.
