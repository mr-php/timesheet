# Heroku

## Deploy

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/mr-php/timesheet/tree/master)

## Run Migrations

Make sure you have the [Heroku Toolbelt](https://toolbelt.heroku.com) installed.

After you have an instance running on Heroku, run these commands in your terminal.

```
heroku git:clone -a <your-app-name>
cd <your-app-name>
heroku run yii migrate --interactive=0
```
