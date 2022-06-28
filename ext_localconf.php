<?php

defined('TYPO3') or die();

// registering icons
$iconProviderClassName = \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class;

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$icons = [
    'directmail-attachment' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/attach.gif'],
    'directmail-dmail' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/dmail.gif'],
    'directmail-dmail-list' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/dmail_list.gif'],
    'directmail-folder' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/ext_icon_dmail_folder.gif'],
    'directmail-category' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/icon_tx_directmail_category.gif'],
    'directmail-mail' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/mail.gif'],
    'directmail-mailgroup' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/mailgroup.gif'],
    'directmail-page-modules-dmail' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/modules_dmail.gif'],
    'directmail-page-modules-dmail-inactive' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/modules_dmail__h.gif'],
    'directmail-dmail-new' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/newmail.gif'],
    'directmail-dmail-preview-html' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/preview_html.gif'],
    'directmail-dmail-preview-text' => ['source' => 'EXT:direct_mail/Resources/Public/Icons/preview_txt.gif'],
];

foreach ($icons as $identifier => $options) {
    $iconRegistry->registerIcon($identifier, $iconProviderClassName, $options);
}

// Register hook for simulating a user group
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PreProcessing']['direct_mail'] = 'DirectMailTeam\\DirectMail\\Hooks\TypoScriptFrontendController->simulateUsergroup';

// Get extension configuration so we can use it here:
$extConf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('direct_mail');

/**
 * Language of the cron task:
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['cron_language'] = $extConf['cron_language'] ? $extConf['cron_language'] : 'en';

/**
 * Number of messages sent per cycle of the cron task:
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['sendPerCycle'] = $extConf['sendPerCycle'] ? $extConf['sendPerCycle'] : 50;

/**
 * Default recipient field list:
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['defaultRecipFields'] = 'uid,name,title,email,phone,www,address,company,city,zip,country,fax,firstname,first_name,last_name';

/**
 * Additional DB fields of the recipient:
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['addRecipFields'] = $extConf['addRecipFields'];

/**
 * Admin email for sending the cronjob error message
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['adminEmail'] = $extConf['adminEmail'];

/**
 * Direct Mail send a notification every time a job starts or ends
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['notificationJob'] = $extConf['notificationJob'];

/**
 * Interval of the cronjob
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['cronInt'] = $extConf['cronInt'];

/**
 * Use HTTP to fetch contents
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['UseHttpToFetch'] = $extConf['UseHttpToFetch'];

/**
 * Use implicit port in URL for fetching Newsletter-Content: Even if your TYPO3 Backend is on a non-standard-port, 
 * the URL for fetching the newsletter contents from one of your Frontend-Domains will not use the PORT you are using to access your TYPO3 Backend, 
 * but use implicit port instead (e.g. no explicit port in URL)
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['direct_mail']['UseImplicitPortToFetch'] = $extConf['UseImplicitPortToFetch'];

/**
 * Registering class to scheduler
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['DirectMailTeam\\DirectMail\\Scheduler\\DirectmailScheduler'] = [
    'extension' => 'direct_mail',
    'title' => 'Direct Mail: Mailing Queue',
    'description' => 'This task invokes dmailer in order to process queued messages.',
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['DirectMailTeam\\DirectMail\\Scheduler\\MailFromDraft'] = [
    'extension'            => 'direct_mail',
    'title'                => 'Direct Mail: Create Mail from Draft',
    'description'        => 'This task allows you to select a DirectMail draft that gets copied and then sent to the. This allows automatic (periodic) sending of the same TYPO3 page.',
    'additionalFields'    => 'DirectMailTeam\\DirectMail\\Scheduler\\MailFromDraftAdditionalFields'
];

// bounce mail per scheduler
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['DirectMailTeam\\DirectMail\\Scheduler\\AnalyzeBounceMail'] = [
    'extension' => 'direct_mail',
    'title' => 'Direct Mail: Analyze bounce mail',
    'description' => 'This task will get bounce mail from the configured mailbox',
    'additionalFields' => 'DirectMailTeam\\DirectMail\\Scheduler\\AnalyzeBounceMailAdditionalFields'
];
