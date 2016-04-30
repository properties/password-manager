# password-manager
Password Manager

# Install
This code uses GoogleAuth by @PHPGangsta and Clipboard by @zenorocha, you need to have these 2 to make this code work:
```sh
https://github.com/zenorocha/clipboard.js
https://github.com/PHPGangsta/GoogleAuthenticator
```

When everything is installed, first time login with the passwords you want to use!
2FA key needs to be added in system.php, you can generate one with the GoogleAuthenticator.

```sh
$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
echo "Secret is: ".$secret."\n\n";
echo '<img src="'.$ga->getQRCodeGoogleUrl('SITEURL', $secret.'">';
```
The code above will generate a QR code, and provide the secret code to add in system.php.

Now you can add sites and accounts, fully encrypted in AES256!

![alt tag](https://vgy.me/8sCEeG.png)

Tip: host website localhost or on hosting with SSL!

![alt tag](https://vgy.me/9TheBn.gif)
