<?php
session_start();


$data = [
    'data' => $_POST['data']
];


// Create event logic



$service = new Google_Service_Calendar($client);

print_r($service);


//print_r($calendarClient);

$event = new Google_Service_Calendar_Event(array(
    'summary' => 'Google I/O 2015',
    'location' => '800 Howard St., San Francisco, CA 94103',
    'description' => 'A chance to hear more about Google\'s developer products.',
    'start' => array(
      'dateTime' => '2022-06-08T09:30:00+02:00',
      'timeZone' => 'Denmark/Copenhagen',
    ),
    'end' => array(
      'dateTime' => '2022-06-08T10:00:00+02:00',
      'timeZone' => 'Denmark/Copenhagen',
    ),
    'recurrence' => array(
      'RRULE:FREQ=DAILY;COUNT=2'
    ),
    'attendees' => array(
      array('email' => 'maoh@html24.net')
    ),
    'reminders' => array(
      'useDefault' => FALSE,
      'overrides' => array(
        array('method' => 'email', 'minutes' => 24 * 60),
        array('method' => 'popup', 'minutes' => 10),
      ),
    ),
  ));
  
  $calendarId = 'html24.net_3u70aooh2tn8j6cschgu2em0v0@group.calendar.google.com';
  $event = $service->events->insert($calendarId, $event);
  printf('Event created: %s\n', $event->htmlLink);


?>