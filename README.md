[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/felipegirotti/GYG/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/felipegirotti/GYG/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/felipegirotti/GYG/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/felipegirotti/GYG/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/felipegirotti/GYG/badges/build.png?b=master)](https://scrutinizer-ci.com/g/felipegirotti/GYG/build-status/master)

## Overview
Given the provided API endpoint containing product availabilities,
please write a CLI PHP script that retrieves the product_ids of the products that are available to be booked given a period of time and the requested number of travellers.


The script should accept 4 command line arguments:

API Endpoint - The full url to the api endpoint (provided below)
Start time - the start of the time period requested in the following format: “Y-m-d\\TH:i”  e.g. 2017-11-23T19:30.
End time - the end of the time period in the same format as the start_datetime
Number of travelers - an integer between 1 and 30.


The API endpoint is available here: http://www.mocky.io/v2/58ff37f2110000070cf5ff16



The endpoint returns a list of availability items with the following data:



product_id  - integer between 0 and 2^16.
activity_duration_in_minutes - integer between 0 and 1440.
activity_start_datetime - datetime string in the “Y-m-d\\TH:i”  format .
places_available - integer between 0 and 99.



The output of your script should consist of a JSON formatted list of available products containing the product_id and the relevant start times available for the requested interval and number of participants, sorted in chronological order. Below you can find an output example.



Call example:



`$> php solution.php http://www.mocky.io/v2/58ff37f2110000070cf5ff16 2017-11-20T09:30 2017-11-23T19:30 3`



Output example:
```

[

  {

"product_id": 1,

"available_starttimes": [

"2017-11-20T09:30",

"2017-11-20T10:30"

]

},

{

"product_id": 3,

"available_starttimes": [

"2017-11-20T09:30"

]

}

]
```


## Requirement

- PHP 7 or more


## run
Just a simple command

`php solution.php http://www.mocky.io/v2/58ff37f2110000070cf5ff16 2017-11-20T09:30 2017-11-23T19:30 3`


## run tests
`./vendor/bin/phpunit`

