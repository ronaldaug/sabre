
# Sabre SDK for both REST & SOAP APIs

1- Clone this repo to the root folder of Laravel project.

```sh
git clone https://github.com/ronaldaug/sabre.git
```

2- Add Sabre credentials to .env file
```
SABRE_ENV=test
SABRE_DOMAIN=
SABRE_PCC=
SABRE_CLIENT_ID=
SABRE_SECRET=
```

3- Link Sabre library under **autoload** ➡ **psr-4** in `composer.json`

```sh

"autoload": {
        "psr-4": {
            "App\\": "app/",
            "Up\\Sabre\\": "sabre/src"
        }
}

```

After the above configuration is done, do composer dump autoload.

```sh

composer dump-autoload -o

```

----------

Forked from `https://github.com/tanvir0604/sabre`
Credit goes to `Md Shafkat Hussain Tanvir`