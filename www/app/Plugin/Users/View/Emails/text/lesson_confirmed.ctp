<?php
/**
 * @var $contactName string
 * @var $date string
 * @var $notes string
 * @var $confirmedBy array
 * @var $subject string
 * @var $time string
 */
extract($emailLessonData);
?>
<?php echo __d('croogo', 'Hello %s', $contactName); ?>,

<?php echo $confirmedBy['fullName'] . ' (' . $confirmedBy['username'] . ')'?> confirmed the lesson detailed below:

<?php /*
Date:       <?php echo date('M j Y', strtotime($date)); ?> (UTC / GMT)
Time:       <?php echo $time ?>
*/ ?>
Subject:    <?php echo $subject ?>

Notes:
<?php echo $notes ?>


Please visit your lessons page to see the confirmation and the lesson time:
<?php echo Router::url(array(
    'controller' => 'users',
    'action' => 'lessons',
), true); ?>