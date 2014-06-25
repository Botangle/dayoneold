<?php
$newCategories = array();

foreach ($categories as $k => $v) {
    $category = $v['Category'];
    $newCategories[] = array(
        'name'          => $category['name'],
        'id'            => $category['id'],
        'user_count'    => $this->UserCount->getUserCount($category['name']),
    );
}

$categories = array(
    'categories' => array(
        'category' => $newCategories,
    ),
);

$xml = Xml::fromArray($categories);
echo $xml->asXML();
