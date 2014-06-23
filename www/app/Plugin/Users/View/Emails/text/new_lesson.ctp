<?php
/**
 * @var $contactName string
 * @var $date string
 * @var $notes string
 * @var $requestor array
 * @var $subject string
 * @var $time string
 */
extract($emailLessonData);
?>
<?php echo __d('croogo', 'Hello %s', $contactName); ?>,

<?php echo $requestor['fullName'] . ' (' . $requestor['username'] . ')'?> proposed a new lesson as detailed below:

<?php /*
Date:       <?php echo date('M j Y', strtotime($date)); ?> (UTC / GMT)
Time:       <?php echo $time ?>
*/ ?>
Subject:    <?php echo $subject ?>

Notes:
<?php echo $notes ?>


Visit your lessons page to confirm:
<?php echo Router::url(array(
    'controller' => 'users',
    'action' => 'lessons',
), true); ?>


or visit <?php echo $requestor['name'] ?>'s profile page to learn more:
<?php echo Router::url(array(
		'controller' => 'users',
		'action' => 'view',
        'username' => $requestor['username']
	), true); ?>