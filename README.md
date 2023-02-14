# Birthday Tools
Provides a report on upcoming birthdays and an API for sending 
birthday greetings via e-mail.

## Report
This is a simple CiviCRM report giving you a list of the upcoming birthdays of
your contacts. Simply install the extension, create a new report, and use
"Available for Dashboard" to give you the list upon login.

![image](/docs/images/birthday_report.png)

## Automatical birthday mailings (NEW feature)
With version 1.5 this extension now provides an API for sending 
birthday greetings automatically via e-mail using CiviCRM's scheduled jobs.

Please note: With default settings no e-mail will be sent after upgrading 
to this verison. E-Mail only will be sent if triggering an APIv3 or APIv4
birthday action manually or by using scheduled jobs.

### Settings Menu
This page provides a step by step integrated settings guide on how to setup
your Civi for automated birthday email notifications

Naviage to: "Administer" --> "Administration Console" 
--> "Birthdays Extension Settings"
![image](/docs/images/birthday_settings.png)

### Activities
An activity is written every for successful / faild email greetings
![image](/docs/images/birthday_activities.png)

Activity content
![image](/docs/images/birthday_acitivty.png)


## Localisation

The extension is currently localised for English and German, but it since the
infrastructure is there, adding another language should be pretty easy. We're
also planning add more features (see
[here](https://github.com/systopia/de.systopia.birthdays/issues)), but currently
lack the funding.
