# password-manager

Password Manager by @properties
This password manager works with 2 passwords and 2FA (Google Authenticator).
All user information is encrypted with AES256, and is only viewable/decryptable with your 2 passwords.

# Install

Upload all the files to a host, I suggest uploading it on localhost or a SSL host for best security.

First step, open lib/config.php,  and define database information and site url (for Google Auth):
```sh
  define('siteUrl', 'github.com');
  define('dbHost', 'localhost');
  define('dbName', 'properties_db');
  define('dbUser', 'properties_user');
  define('dbPassword', 'coolgithub');
```

After you defined your information, open install.html in your browser.
Provide 2 passwords you want to use, main password and phrase. You need both to use the Password Manager.
Make sure you remember the 2 passwords, if needed write it down on a piece of paper.
All data is encrypted with AES256, if you ever lose your 2 main passwords, you lose everything!


To use 2FA (2 Factor Authenticator), you need to install Google Authenticator on your iOS, Android or Blackberry device.
Once installed, open the app and scan the QR code. Now click the Install button and you are done!
The install.html file will be automaticly deleted, if it failed to delete, delete it yourself.

You can now login, provide your 2 passwords and the code you see in your 2FA app.
Once logged in, you can add site's and accounts.


![alt tag](https://vgy.me/sMcmn1.png)

![alt tag](https://vgy.me/Xd3u1G.png)


This code uses GoogleAuth by @PHPGangsta and Clipboard by @zenorocha, you can find them here:
```sh
https://github.com/zenorocha/clipboard.js
https://github.com/PHPGangsta/GoogleAuthenticator
```
