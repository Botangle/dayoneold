<?php
$response = array(
    'lessons'   => array(
        'active_lessons'    => $this->LessonXmlTransformer->transformLessons($activeLessons),
        'upcoming_lessons'  => $this->LessonXmlTransformer->transformLessons($upcomingLessons),
        'past_lessons'      => $this->LessonXmlTransformer->transformLessons($pastLessons),
    ),
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();