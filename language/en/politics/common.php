<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
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
/*
* Note that You should also activate every language also uploaded in the main phpBB/language directory. 
* 
 To Do: We should think on a dedicated directory for multilangual files such as 'multilang' 
			for the files we are currently storing in language subdirectories from were we can import 
			or export using a ACP feature all the keys and values using DB table as FAQ Manager does.
*/
$lang = array_merge($lang, array(
	//Transcript: Donald Trump's Foreign Policy Speech, The New York Times (27 April 2016)
	'Donald Trump on Israel, Foreign Policy Speech (27 April 2016).'	=> '"Israel, our great friend and the one true democracy in the Middle East has been snubbed and criticized by an administration that lacks moral clarity..."',
	//WikiQuotes from Trump: The Art of the Deal (1987) by Donald J. Trump with Tony Schwartz.
	'Donald Trump: The Art of the Deal (1987)'	=> '"I try to learn from the past, but I plan for the future by focusing exclusively on the present."',
	//BARACK HUSSEIN OBAMA ON ISLAM AND CHRISTIANITY BY GEOFFREY GRIDER
	//www.nytimes.com/2006/06/28/us/politics/2006obamaspeech.html
	'Barack Obama on Bible, Washigton D.C., June 28, 2006.'	=> '“Which passages of scripture should guide our public policy? Should we go with Leviticus, which suggests slavery is OK and that eating shellfish is an abomination?  Or we could go with Deuteronomy, which suggests stoning your child if he strays from the faith?”',
	//Cairo University, Cairo, Egypt (4 June 2009), Full text at Wikisource
	'Barack Obama on Koran, A New Beginning (June 2009)'	=> 'The Holy Koran tells us, "O mankind! We have created you male and a female; and we have made you into nations and tribes so that you may know one another."',
	'Barack Obama on Torah, A New Beginning (June 2009)'	=> 'The Talmud tells us, "The whole of the Torah is for the purpose of promoting peace."',
	'Barack Obama on Bible, A New Beginning (June 2009)'	=> 'The Holy Bible tells us, "Blessed are the peacemakers, for they shall be called sons of God."',
	'Barack Obama on Islam.'	=> '“These rituals remind us of the principles that we hold in common, and Islam’s role in advancing justice, progress, tolerance, and the dignity of all human beings.”',
	//Trump's interview with David Brody, CBN News (29 January 2017)
	'Donald Trump on God, interview with David Brody, CBN News (29 January 2017).'	=>  '“I\'ve always felt the need to pray... I would say that the office is so powerful that you need God even more.”',
	//Donald Trump 2016 RNC draft speech transcript, Politico (21 July 2016)
	'Donald Trump on America, 2016 Republican National Convention (21 July 2016).'	=>  'I make this promise: We will make America proud again, we will make America strong again, we will make America safe again, and we will Make America Great Again! God bless you and good night! I love you!.”',
	//HuffPost
	'Barack Obama, From an Easter Prayer Breakfast, 06.04.2010, White House.'	=> '“For even after the passage of 2,000 years, we can still picture the moment in our mind’s eye. The young man from Nazareth marched through Jerusalem; [...] — that the Son of Man was not to be found in his tomb and that Jesus Christ had risen..',
	//en.wikiquote.org/wiki/Vladimir_Putin#Cooperation,_Terrorism,_UK_&_USA,_President_Trump,_Resolving_Conflict,_Defense,_Crimea,_The_Media,_Nuclear_Weapons_Policy:_15th_Plenary_Session_(18_October_2018)
	'Vladimir Putin on Terrorism, 15.11.2001.'	=> '“I bow my head to the victims of terrorism. I am highly impressed of the courage of New York residents. The great city and the great American nation are to win!”',
	//content.time.com/time/magazine/article/0,9171,1734814,00.html
	'Vladimir Putin on Demoralisation, 24 Apr 2008'	=> '“I have always reacted negatively to those who with their snotty noses and erotic fantasies prowl into others\' lives.”',
	'Vladimir Putin on Soviet Union'	=> '“Whoever does not miss the Soviet Union has no heart. Whoever wants it back has no brain.”',
	'Adrian Năstase\'s as Prime Minister on Taxes and Chicken Farm'	=> '“And to those who started to count my hens, I invite them to count also my eggs.”',
	'Traian Băsescu about Israel, 18-21 Jan 2014, at Ramallah'	=> '“...Nobody can deny the history, but the porpose of the peace process is not the reestablishment of truth, but of peace.”',
	//www.bugetul.ro/traian-basescu-face-un-anunt-exploziv-cum-poate-romania-sa-zgaltaie-ue-in-urmatoarele-sase-luni/
	//Andreea Corina Chiriac, redactor, actual
	'Traian Băsescu about European Union,  09.01.2019.'	=> '“Romania may shake the European Union that appears debusolated and without curage in defining his own future, loading from the high of the Rotating European Union Presidency three big projecs...”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=8096&_PRID=arh
	'Traian Băsescu about terorism, at Embassy of U.K. & Northern Ireland, 12 June 2005.'	=> '“We will have to remain strong, united, because terrorism strikes blind, anywhere in the world, wherever he can and always it always strikes the innocent.”',
	'Emil Constantinescu as President of Romania, about family, 31 Dec 1997'	=> '“It is the institution for which all other institutions have to work and without which this nation would not have neither the memory of the past nor the hope of the future.”',
	'Emil Constantinescu about Democracy, 09 Dec 2018.'	=> '“We are within a moment in witch ... the Elites dissolve, ... the Political discourse collapses in populism, the democracies need democratic conscience, not just democratic institutions.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=6361&_PRID=arh
	//Zilei Solidaritatii Nationale Împotriva Dictaturii
	'Ion Iliescu, the Ending of the “Eminescu Year”, Botoşani, 14 Jan 2001.'	=> '“In a world that is searching for new foundations for a solidarity existence, preoccupied to resiste some multiple challenges and unimaginable with decades or just years ago, we come with the treasure of amazing parable of inter-human communion, lived under the sign of that intraductible “humaneness”.',
	'Ion Iliescu, the Ending of the “Eminescu Year”, Botoşani, 14 Jan 2001.'	=> '“For the Romanian people, humanness represents the eternal expression of the conviction that the supreme value is the human soul; but the integral "expression" of the Romanian soul”, after a famous formula, was Mihai Eminescu.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=936&_PRID=arh
	'Emil Constantinescu about Revolution and Democracy, 16 Dec 1998.'	=> '“...the people got arisen, they fought and some of them died to obtain rights then forbidden and which today already seems natural to us.”', 
	'Emil Constantinescu about Romanian Revolution, 16 Dec 1998.'	=> '“Then it proved once again that many people can owe almost everything to very few people.”', 
	'Emil Constantinescu about Romanian Revolution, 16 Dec 1998.'	=> '“People who in a decisive moment had the courage to change our destiny. Regardless the weight of the present or the future, I do not think we have the right to forget them either, nor the spirit that led them.”',  
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=5988&_PRID=arh
	'Ion Iliescu, Holocaust Day in Romania, 12 Oct 2004.'	=> '“Such a tragedy must not be repeated, and for this nothing should be spared for the younger generations to know and to understand the entire truth.”',
	'Klaus Iohannis as president, in the Day of Commemoration of the Victims of Fascism and Communism, 23 Aug 2018.'	=> 'The totalitarisms have destroyed the state of law, have tried to shatter the freedom of expression, have trampled, flagrantly, the rights and freedoms of citizens, but they did not succeed to repress the desire of freedom and the attachment toward the democratic values of millions of people who strongly believed that these regimes can be defeated.',
	//www.presidency.ro/ro/media/mesaje/mesajul-presedintelui-romaniei-domnul-klaus-iohannis-transmis-in-cadrul-galei-regaseste-romania
	'Klaus Iohannis as president, within the Gala „Refind Romania”, 22 Dec 2018.'	=> '"The Nature, in its its ensemble, should not be perceived as a definite given, but must be prized and cared for continuously by all of us."',
	'Benjamin Netanyahu, The Times of Israel, 17 May 2015.'	=> '"There is no room for racism and discrimination in our society, none... We will turn racism into something contemptible and despicable."',
	//www.gov.il/he/departments/news/speechcong030315
	'Benjamin Netanyahu despre ISIS, 03 Mar 2015'	=> '"They just disagree among themselves who will be the ruler of that empire. In this deadly game of thrones, there is no place for America or for Israel, no peace for Christians, Jews, or Muslims who don\'t share the Islamist medieval creed. No rights for women. No freedom for anyone. So when it comes to Iran and ISIS, the enemy of your enemy is your enemy."',
	//www.haaretz.com/transcript-of-netanyahu-speech-1.5343049
	'Benjamin Netanyahu on Iran\'s Nuclear Weapons, before the UN General Assembly, 01 Oct 2013.'	=> '“If Israel is forced to stand alone, Israel will stand alone. Yet, in standing alone, Israel will know that we will be defending many, many others.”',
	'Benjamin Netanyahu vowed, “as PM of Israel in a speech before a joint session of US Congress, 03 Mar 2015.'	=> '“Even if Israel has to stand alone, Israel will stand. But I know that Israel does not stand alone, I know that America stands with Israel, I know that you stand with Israel.”',
));
