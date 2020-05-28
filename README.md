# Alma Digitization Addon
This project provides an [ILLiad Client Addon](https://atlas-sys.atlassian.net/wiki/spaces/ILLiadAddons/pages/3149440/Client+Addons) to submit a digitization request to an institution's Alma using the Users API.

## Addon Architecture
ILLiad client addons use the lua scripting language to embed forms and other functional widgets to the ILLiad client user interface. This addon creates a form that is prepopulated with the ILLiad digitization request data (e.g. article title, author). Item identifier fields must be entered on the ILLiad form by the person doing the processing by looking the item up in Alma.  Using the Physical Items search is the easiest way, as both the MMS ID and Item ID show up on the record display.

Submitting the form posts the fields to a PHP application running on a web server. This app will create and post a call to the users/requests API to create the request in the institution's IZ.

## Installing the ILLiad Client Addon
General instructions for Installing Addons are here:
https://atlas-sys.atlassian.net/wiki/spaces/ILLiadAddons/pages/3149384/Installing+Addons

### Download
Copy the AlmaDigitizationFiles folder to the ILLiad addons folder: C:\program files\illiad\addons

### Configuration
Addon settings are configured in the ILLiad Client Manage Addons form.

| Setting | Default | Type | Description |
| ---- | ---- | ---- | ---- |
| AutoSearch | false | boolean | Determines whether or not the search should be done automatically when the request is opened. |
| InstitutionCode | 01WRLC_DOC | string | Institution Code for Alma. |
| regionalURL | https://api-na.hosted.exlibrisgroup.com/ | string | Alma API Regional URL. Sourced from table: https://developers.exlibrisgroup.com/alma/apis/#calling |
| SubmissionURL | https://illiad-ada.wrlc.org/index.php? | string | URL for website to handle request submissions (see web setup instructions) |
| UserId | TestDCILLAddon | string | Alma Primary ID for ILL lending patron in Alma (the patron which will receive all of the requests). |

## Web Setup Instructions

Clone this repository to your web server:

   git clone git@github.com:WRLC/Alma_Digitization_Addon.git

Navigate to the `www/utils` directory copy the example configuration file to `config.php` and edit the `$izSettings` array for the IZs supported for your web server:

   cd www/utils
   cp config.php.example config.php
   vi config.php

Configure a named virtual host in your web server to serve up PHP files from the `Alma_Digitization_Addon/www` directory. The resulting URL for that directory is the SubmissionURL setting for the ILLiad client addon.
