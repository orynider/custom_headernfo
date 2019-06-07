<?php
/**
 *
 * @package phpBB Extension - Custom Header Logo
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, orynider, https://mxpcms.sourceforge.net.org
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_HEADER_INFO_TITLE'				=> 'Custom Header Info',
	'ACP_HEADER_INFO_CONFIG' 			=> 'תצורת פרטי כותרת',
	'ACP_MANAGE_CONFIG' 					=> 'תצורה ',
	
	'ACP_MANAGE_FORUMS' 					=> 'פורומים',
	'ACP_MANAGE_PAGES' 					=> 'דפים',
	'ACP_NO_BANNER' 							=> 'אין באנר',

	'HEADER_INFO_INTRO' 					=> '.הארכה של אורינדר Custom Header Info זהו דף תצורה עבור אל',
	'HEADER_INFO_DONATE' 					=> '<a href="https://www.paypal.me/orynider"><strong>תרום</strong></a>',
	'HEADER_INFO_DONATE_EXPLAIN' 	=> 'אם אתה אוהב את התוסף הזה לשקול תרומה',

	'HEADER_INFO_VERSION' 				=> 'גרסה',
	'HEADER_INFO_CHECK' 					=> 'בדוק ידנית בכתובת <a href="http://mxpcms.sf.net/forum/index.php"><strong> פורום ראשי </strong></a>',
	'HEADER_INFO_EDIT' 						=> '.ערוך פרטי כותרת למסד הנתונים',

	'ROW_HEIGHT' 									=> 'גובה כל שורה של טיקר ב- PX',
	'ROW_HEIGHT_EXPLAIN' 						=> 'כל מודעת באנר נטענת בטיקר המידע כשורה באמצעות יישום JavaScript זה. גובה כל שורה טיקר צריך להיות אחיד. ',

	'THUMBNAIL_FONT_DADABASE_ERROR_FOR_ID' => 'שגיאת dadabase של גופן ממוזערת עבור מזהה. <br /> לערוך את הבאנר ב- ACP ולבחור מחדש את הגופן המועלה עם קובצי css של הגופן. ',
	
	'SPEED' 											=> 'מהירות של אנימציה מעבר למילי-שניות',
	'SPEED_EXPLAIN' 								=> '.מהירות של אנימציה מעבר בין גלילה באנר בטיקר מידע',

	'INTERVAL' 										=> 'זמן בין שינויים במילי-שניות',
	'INTERVAL_EXPLAIN' 						=> 'הזמן לקרוא את המידע לפני גלילה את הטיקר',

	'MOUSESTOP' 									=> 'עצור את העכבר על הטיקר',
	'MOUSESTOP_EXPLAIN' 					=> 'אם מוגדר כ \'אמת\', הטיקר יפסיק לפעול עם העכבר. ',

	'INFO_DIRECTION' 						=> 'כיוון הגלילה',
	'INFO_DIRECTION_EXPLAIN' 		=> 'כיוון המכשפה הרשימה תגלגל. זה למעלה או למטה. ',

	'HEADER_INFO_EDIT' 					=> 'ערוך פרטי כותרת למסד נתונים',
	'HEADER_INFO_ADD' 					=> '. ץהוספת פרטי כותרת למסד הנתונים',

	'HEADER_INFO_NAME' 					=> 'שם כותרת באנר',
	'HEADER_INFO_NAME_EXPLAIN' 	=> 'כותרת פריט פרטי כותרת חדש. הכותרת תופיע ב- header_info tooltip כשאתה מנווט עם העכבר למעלה. ',

	'HEADER_INFO_TITLE_PIXELS' 		=> 'גודל כותרת באנר',
	'HEADER_INFO_TITLE_PIXELS_EXPLAIN' => 'קובע את מספר הפיקסלים עבור כותרת הבאנר. <br /> <em>ברירת המחדל עבור prosilver הוא 12 פיקסלים.</em>',

	'HEADER_INFO_DESC' 					=> 'תיאור קצר',
	'HEADER_INFO_DESC_EXPLAIN' 	=> 'תיאור קצר של פריט פרטי כותרת חדש. התיאור יופיע על-ידי הסבר קצר על הכותרת בעת ניווט עם העכבר לעיל. ',

	'HEADER_INFO_DESC_PIXELS' 					=> 'תיאור מודעת באנר',
	'HEADER_INFO_DESC_PIXELS_EXPLAIN'		=> 'קובע את מספר הפיקסלים לתיאור הבאנר.',

	'HEADER_INFO_LONGDESC' 					=> 'תיאור ארוך',
	'HEADER_INFO_LONGDESC_EXPLAIN'	=> 'תיאור ארוך של פריט פרטי כותרת חדש. התיאור יופיע בטקסט הסבר הכותרת של הכותרת כאשר אתה מנווט עם העכבר לעיל. הערות: 255 תווים לכל היותר',

	'HEADER_INFO_FORUM_DESC'				=> 'הערה: פורומים שכבר נוספו מושבתים. במקום זאת, ערוך את הערך. ',
	'CLICK_TO_SELECT' 								=> 'לחץ בתיבה כדי לבחור צבע',

	'USE_EXTENED_SITE_DESC' 					=> 'השתמש בתיאור האתר המורחב',
	'USE_EXTENED_SITE_DESC_EXPLAIN' 	=> 'השתמש בתיאור האתר הרגיל עם פריט פרטי כותרת זה. <br /> <strong> תיאור האתר המורחב</em> נבחר.</strong>',

	'HEADER_INFO_RADIUS' 						=> 'כותרת הרדיוס של מודעת כותרת הכותרת',
	"HEADER_INFO_RADIUS_EXPLAIN" 			=> 'קובע את מספר הפיקסלים עבור עיגול מידע בכותרת באנר \" s פינות. הגדרת 0 זה אומר שלבאנר יהיו פינות מרובעים. ',

	'HEADER_INFO_PIXELS' 						=> 'מידע ברדיו של הרדיוס', 
	'HEADER_INFO_PIXELS_EXPLAIN' 			=> 'קובע את מספר הפיקסלים עבור עיגול בכותרת הלוגו מידע \' s פינות. <br /> <em> ברירת המחדל עבור prosilver הוא 7px. </em>',

	'HEADER_INFO_LEFT' 							=> 'פינות בפינה השמאלית של כותרת הכותרת',
	'HEADER_INFO_LEFT_EXPLAIN' 				=> 'סובב את פינות הצד השמאלי כדי להתאים את כותרת הבאנר',

	'HEADER_INFO_RIGHT' 							=> 'כותרת בפינה הימנית',
	'HEADER_INFO_RIGHT_EXPLAIN' 			=> 'סיבוב בפינות הצד הימני כדי להתאים את כותרת הבאנר',

	'HEADER_INFO_TITLE_COLOUR' 					=> '.צבע כותרת כותרת כותרת בתוך לוגו או כרזה',
	'HEADER_INFO_TITLE_COLOUR_EXPLAIN' 		=> 'בחר צבע עבור בכותרת של טקסט המידע בכותרת. <br /> הגדרת אפשרות זו תחול, במידת האפשר, את אותה מנטרלת את צבע רקע השיפוע הפנימי המשמש \'prosilver\'. <br /> <em> ברירת מחדל = # 12A3EB </em>',
	
	'HEADER_INFO_DESC_COLOUR' 					=> '.תיאור צבע כותרת כותרת בתוך לוגו או כרזה',
	'HEADER_INFO_DESC_COLOUR_EXPLAIN'		=> 'בחר צבע עבור הכותרת',
	
	'HEADER_INFO_OPTIONS' => 'אפשרויות כותרת',

	'FILE_NOT_EXISTS' => 'קובץ זה אינו קיים.',

	'HEADER_INFO_URL' => 'URL',
	'HEADER_INFO_URL_EXPLAIN' => 'הזן את כתובת האתר של פרטי הכותרת, אם ברצונך שהקישורים הפנימיים והחיצוניים יזוהו באופן אוטומטי.',

	'HEADER_INFO_IMAGE' => 'תמונה',
	'HEADER_INFO_IMAGE_EXPLAIN' => 'כתובת אתר של תמונה של מודעת באנר של כותרת. לקבלת תמונות נכונות של תמונות חזותיות בגודל <strong> 458x50px </ strong>. כרזות כותרת ניתן להעלות לתיקייה תמונות / באנרים. ',

	'HEADER_INFO_NAME_B' => 'שם או תיאור של פרטי הכותרת',
	'HEADER_INFO_IMAGE_B' => 'תמונה של פרטי הכותרת',
	'HEADER_INFO_URL_B' => 'כתובת אתר של פרטי הכותרת',

	'HEADER_INFO_COPYRIGHT' => '<strong> פרטי כותרת מותאמת אישית של הרחבה על ידי <a href="http://mxpcms.sf.net/"> orynider </a> </ strong>',
	'HEADER_INFO_ADDED' => 'פרטי כותרת חדשים נוספו!',
	'HEADER_INFO_UDPATED' => 'כותרת עודכנה!',
	
	'HEADER_INFO_NAVBAR' => 'BreadCrumbs לאחר (1)',
	'HEADER_INFO_SEARCHBOX' => 'SearchBox לפני (2)',
	'HEADER_INFO_HEADER' => 'HeaderBar לאחר (4)',
	'HEADER_INFO_INDEX' => 'PageBody לפני (3)',	
	'HEADER_INFO_POSITION' => 'מיקום מותאם אישית של פרטי כותרת',
	'HEADER_INFO_POSITION_EXPLAIN' => 'האם ברצונך להציג את פרטי הכותרת המותאמת אישית ב- NavBar או בכותרת?',
	'HEADER_INFO_ENABLE' => 'PageHeader לפני (0)',
	'HEADER_INFO_ENABLE_EXPLAIN' => 'אפשר פרטי כותרת מותאמים אישית בלוח הכללי.',

	'THUMB_CACHE' => 'השתמש בקובץ שמור של תמונות ממוזערות',
	'THUMB_CACHE_EXPLAIN' => 'אפשר אחסון במטמון של תמונה ממוזערת. אם אתה משתמש בתכונה \'מטמון תמונות ממוזערות\', עליך לנקות את המטמון של התמונות הממוזערות לאחר עריכת הבנרים שלך כדי ליצור אותם מחדש. ',

	'HEADER_INFO_PIC_WIDTH' => 'רוחב (פיקסל)',
	'HEADER_INFO_PIC_WIDTH_EXPLAIN' => '.בחר את רוחב הבאנר',

	'HEADER_INFO_PIC_HEIGHT' => 'גובה (פיקסל)',
	'HEADER_INFO_PIC_HEIGHT_EXPLAIN' => 'בחר את גובה הכרזה.',

	'HEADER_INFO_LICENSE' => 'רישיון',
	'HEADER_INFO_LICENSE_EXPLAIN' => 'זהו סוג הרישיון והגירסה של משתמש או מפתח חייב להסכים להוריד ולהשתמש בבאנר או בטקסט מתוך הכותרת.',

	'HEADER_INFO_PINNED' => 'פרטי פין',
	'HEADER_INFO_PINNED_EXPLAIN' => 'בחרו אם ברצונכם להצמיד או לא. ההצמדה תוצג תמיד בראש הרשימה. ',

	'HEADER_INFO_DISABLE' => 'השבת תצוגת פריטים', 
	'HEADER_INFO_DISABLE_EXPLAIN' => 'הגדרה זו הופכת את הפריט למושבת, אך עדיין גלויה. הודעה מודיעה למשתמש על פריט זה או שקישור כתובת האתר אינו זמין כרגע. ',

	'HEADER_INFO_CONF_UDPATED' => 'הגדרות התצורה של מידע כותרת מותאמת אישית עודכנו בהצלחה.',
	'HEADER_INFO_ERROR' => '.עדכון הגדרות תצורה של פרטי כותרת מותאמת אישית',

	'MODULE_NAME' => 'שם מסד נתונים',
	'MODULE_NAME_EXPLAIN' => 'זהו שם מסד הנתונים, כגון\' פרטי כותרת מותאמים אישית ',

	'HEADER_INFO_LINK' => 'אפשר קישורים',
	'HEADER_INFO_LINKS_MESSAGE' => 'ברירת מחדל \' ללא קישורים \'הודעה',
	'HEADER_INFO_LINKS_EXPLAIN' => 'אם קישורים אינם מותרים, הטקסט יוצג במקום זאת',

	'WYSIWYG_PATH' => 'נתיב לתוכנת WYSIWYG',
	'WYSIWYG_PATH_EXPLAIN' => 'זהו השביל (מתוך השורש MX-Publisher / phpBB) לתיקיית התוכנה WYSIWYG, למשל \' נכסים / JavaScript \ \'אם העליתם, לדוגמה, את TinyMCE בנכסים /javascript/tinymce.',

	'HEADER_INFO_TYPE' => '.ברירת המחדל של הקטגוריה',
	'HEADER_INFO_TYPE_EXPLAIN' => 'בחר ברירת מחדל של הקטגוריה הדו-לשונית עבור פרטי מודעת הבאנר או הכותרת. כל קטגוריה לשוני הוא תיקיה בספריית השפה הרחבה ויש לי קובץ common.php בפנים עם הטקסט מטופל כמו תמונה ב thumnail או כמו טקסט באנר. ',

	'MULTI_LANGUAGE_BANNER' => 'מודעת באנר רב-לשונית', 
	'HTML_MULTI_LANGUAGE_TEXT' => 'טקסט רב-לשוני',
	'SIMPLE_DB_TEXT' => 'טקסט מסד נתונים פשוט',
	'SIMPLE_BG_LOGO' => 'לוגו רקע פשוט',

	'SP_WATERMARK' => 'הגדרות WaterMark',
	'SP_WATERMARK_EXPLAIN' => 'קביעת תצורה של WaterMark i.e. המדריך לקטגוריה categoty כדי להציב באנר על התמונה הממוזערת.',

	'WATERMARK' => 'השתמש ב- WaterMark',

	'WATERMARK_PLACENT' => 'סימן מים על הדגל',
	'WATERMARK_PLACENT_EXPLAIN' => 'בחר את המיקום היו WaterMark להיות ממוקם על הדגל.',

	'BACKGROUNDS_DIR' => '.מידע על רקע של כותרת',
	'BACKGROUNDS_PATH' => 'מידע רקע של רקע\' נתיב אחסון תמונות',
	'BACKGROUNDS_DIR_EXPLAIN' => 'נתיב תחת ספריית השורש של phpBB, למשל. <samp> תמונות / רקעים </samp>.',

	'HEADER_INFO_DIR' => '.ספריית מידע רב-לשונית\' של header ',
	'HEADER_INFO_PATH' => '.כותרת של מידע\' נתיב אחסון טקסט רב-לשוני ',
	'HEADER_INFO_DIR_EXPLAIN' => 'הנתיב שמתחת',
	
	'HEADER_INFO_FONT' => 'header \' s מידע תמונה ממוזערת של קובץ גופן. ',
	'HEADER_INFO_FONT_EXPLAIN' => 'בחר שם גופן בתיקיית השורש של התוסף \' נכסים \ גופנים \', למשל. <samp>tituscbz</samp>. ',

	'BANNERS_DIR' => '.מידע על באנרים',
	'BANNERS_PATH' => 'כותרת של מידע מידע באנרים נתיב אחסון תמונות.',
	'BANNERS_DIR_EXPLAIN' => 'נתיב תחת ספריית השורש של phpBB, למשל. <samp>תמונות / כרזות</ samp>. ',

	'ACP_NO_HEADER_INFO' => 'אין פריט.',
	    
	'ACL_A_HEADER_INFO' => 'יכול לנהל מידע כותרת מותאמת אישית',

	'SHOW_AMOUNT' => 'פריטים מינימליים להצגה',
	'SHOW_AMOUNT_EXPLAIN' => '.פריטים מינימליים לשאילתה לתצוגה בפרטי כותרת',

));