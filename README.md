# ScraperBot-TelegramBot-JDownloader2Bot
This bot allows you to automate the download process from a page where links to download files are periodically added

## Setup

The setup is pretty straightforward, the first step is to clone this repository with the command `git clone https://github.com/BroHPotato/ScraperBot-TelegramBot-JDownloader2Bot`.
Once the clone is finished you need to setup the *config.ini* file in the folder.
In this file you need to specify all the requested parameter.

the last thing to do is to add the periodical run to the system. To do that you need to run the following comand:
`crontab -e`

and than add the string `* 00 * * /path/to/the/folder/ScraperBot.sh`

than `Ctrl + X` -> `Y`

You've succesfuly installed this bot

## Use

If you want to check if everything works properly you can run the command `./path/to/folder/ScraperBot.sh`

Use the bot very simple, you need to put the link of the pages in the file *Links.txt* with the following syntax:
`https://link.to.the.download.page##https://link.to.image.page##imdbIdCode##Relative/path/to/download/folder` between the different parameter you need to insert the *delimiter* present in the file *config.ini*. Each line of the file rappresents one page to check for downloads, so you need to make sure the file is formatted correctly.
You can find the imbdIdCode in the description of the series in the page of thetvdb


Now you're good to go, Bye~~ and have fun
