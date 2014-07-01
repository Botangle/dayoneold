<?php
$response = array(
    'lessons'   => array(
        'active_lessons'    => array(
            'lesson' => $this->LessonXmlTransformer->transformLessons($activeLessons),
        ),
        'upcoming_lessons'  => array(
            'lesson' => $this->LessonXmlTransformer->transformLessons($upcomingLessons),
        ),
        'past_lessons'      => array(
            'lesson' => $this->LessonXmlTransformer->transformLessons($pastLessons),
        ),
    ),
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();