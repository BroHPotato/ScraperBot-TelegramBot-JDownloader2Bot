# ScraperBot-TelegramBot-JDownloader2Bot
This bot allows you to automate the download process from a page where links to download files are periodically added.

## Setup

The setup is pretty straightforward, the first step is to clone this repository with the command
```shell
git clone https://github.com/BroHPotato/ScraperBot-TelegramBot-JDownloader2Bot
```
Once the clone is finished you need to setup the *config.ini* file in the folder.
In this file you need to specify all the requested parameter (if you don't use something just delete it or put `;` in front of the line).

Now add the periodical run to the system. To do that you need to run the following comand:
```shell
crontab -e
```

and than add the string `* 00 * * /path/to/the/folder/ScraperBot-TelegramBot-JDownloader2Bot/scraperbot.sh`

than `Ctrl + X` -> `Y`

You've succesfuly installed this bot!

If you want to install the web UI you can:
 - move the entire folder in the shared folder with
 ```shell
 mv /path/to/folder/ScraperBot-TelegramBot-JDownloader2Bot /var/www/ScraperBot-TelegramBot-JDownloader2Bot
 ```
 - use the apache2 guide to enable the site.

Or:
 - keep the folder in the same place and enable a new virtual host with server-root equal to the path to the ScraperBot folder(see the apache2 guide);
 es.
 ```ini
 <VirtualHost *:81>
  ServerAdmin webmaster@localhost
  ServerName ScraperBot
  DocumentRoot /path/to/folder/ScraperBot-TelegramBot-JDownloader2Bot

  <Directory /path/to/folder/ScraperBot-TelegramBot-JDownloader2Bot>
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
    Allow from all
  </Directory>
</VirtualHost>
```
> Note: i raccomand to keep your UI port `81` not exposed to the internet.

Now we need to set up the permissions to some folder to allow the creation and reading of files:
- Logs, Watcher and Saves need rw permissions so
```shell
chmod -R 766 /path/to/folder/ScraperBot-TelegramBot-JDownloader2Bot{Logs,Saves,Watcher,}
```
- config.ini needs same permision so
```shell
chmod -R 766 config.ini
```
- scraperbot.sh and scraperbot.php need rx permissions so
```shell
chmod -R 755 scraperbot.sh scraperbot.php
```

## Use

If you want to check if everything works properly you can run the command
```shell
./path/to/folder/scraperbot.sh
```
For the WebUI you can go at http://localhost:port or your virtual host.

Use the bot very simple, if you don't use the WebUI you need to put the link of the pages in the file *name of series.json* with the following syntax:
```json
{
  "name":"name of series",
  "the_tvdb_id":"000000",
  "image":"https:\/\/someimage.net\/image.jpg",
  "downloaded_episode":[9,8,7,6,5,4,2,3,1],
  "download_folder":"\/somedirectory\/name of series\/Season 01\/",
  "save_folder":"\/somedirectory\/name of series\/Season 01\/",
  "link_downloads":"https:\/\/wheredoidownload.net"
}
```
however i strongly raccomand to use the WebUI!!
> Note: if your are planning to not use nor telegram nor the tvdb you need to input in any case the value, they simply will not be used


## Generals

> Tested on RPI 3B+;<br>
> Tested on EasyPHP with php7.1

This bot can create file *.crawljob* for jdownloader, you need to setup it with the watchfolder extention!
For other useful script see https://github.com/BroHPotato/JDownloader-2-Scripts

Now you're good to go, **Bye~~ and have fun**
