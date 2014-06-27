<?php
$response = array(
    'lesson_create' => array(
        'redirect' => $redirect,
        'lesson' => $this->LessonXmlTransformer->transformCreatedLesson($lesson['Lesson']),
    ),
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();