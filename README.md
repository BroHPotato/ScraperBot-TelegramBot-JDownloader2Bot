# ScraperBot-TelegramBot-JDownloader2Bot
This bot allows you to automate the download process from a page where links to download files are periodically added

## Setup

The setup is pretty straightforward, the first step is to clone this repository with the command `git clone https://github.com/BroHPotato/ScraperBot-TelegramBot-JDownloader2Bot`.
Once the clone is finished you need to setup the *config.ini* file in the folder.
In this file you need to specify all the requested parameter.
Then you need to modify the file `scraperbot.php` in the folder `Bot/`, here you need to change the variable `$inipath`.

the last thing to do is to add the periodical run to the system. To do that you need to run the following comand:
`crontab -e`

and than add the string `* 00 * * /path/to/the/folder/scraperbot.sh`

than `Ctrl + X` -> `Y`

You've succesfuly installed this bot!

If you want to install the web UI you can:
 - move the entire folder in the shared folder with `mv /path/to/folder/ScraperBot /var/www/ScraperBot` 
 - use the apache2 guide to enable the site
 
Or:
 - keep the folder in the same place and enable a new virtual host with server-root equal to the path to the ScraperBot folder(see the apache2 guide)

## Use

If you want to check if everything works properly you can run the command `./path/to/folder/scraperbot.sh`
For the WUI you can go at http://localhost or your virtual host

Use the bot very simple, if you don't use the WebUI you need to put the link of the pages in the file *Links.txt* with the following syntax:
`https://link.to.the.download.page##https://link.to.image.page##thetvdbID##Relative/path/to/download/folder` between the different parameter you need to insert the *delimiter* present in the file *config.ini*. Each line of the file rappresents one page to check for downloads, so you need to make sure the file is formatted correctly.
You can find the thetvdbID in the description of the series in the page of thetvdb

Otherwise simply use the WebUI!!

## Generals

This bot create file *.crawljob* for jdownloader, you need to setup it with the watchfolder extention!
For other useful script see https://github.com/BroHPotato/JDownloader-2-Scripts

Now you're good to go, **Bye~~ and have fun**
