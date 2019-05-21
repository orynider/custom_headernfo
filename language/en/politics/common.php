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
	'Vladimir Putin on Demoralisation, 24.04.2008'	=> '“I have always reacted negatively to those who with their snotty noses and erotic fantasies prowl into others\' lives.”',
	'Vladimir Putin on Soviet Union'	=> '“Whoever does not miss the Soviet Union has no heart. Whoever wants it back has no brain.”',
	'Adrian Năstase\'s as Prime Minister on Taxes and Chicken Farm'	=> '“Iar pe cei care s-au apucat să-mi numere găinile, îi rog să-mi numere and ouăle. (I invite those who started to count my hens, to count also my eggs.)”',
	'Traian Băsescu about Israel, 18-21.01.2014, at Ramallah'	=> '“...Nimeni nu va nega istoria, dar scopul procesului de pace nu este restabilirea adevarului istoric, ci pacea.”',
	//www.bugetul.ro/traian-basescu-face-un-anunt-exploziv-cum-poate-romania-sa-zgaltaie-ue-in-urmatoarele-sase-luni/
	//Andreea Corina Chiriac, redactor, actual
	'Traian Băsescu about European Union,  09.01.2019.'	=> '“România poate zgâlţâi Union Europeană care pare debusolată and fără curaj în a-and defini propriul viitor, lansând de at înălţimea Preşedinţiei Rotative a Uniunii Europene trei mari proiecte...”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=8096&_PRID=arh
	'Traian Băsescu about terorism, at Ambasada U.K. & Irlandei de Nord, 12.06.2005.'	=> '“Va trebui să rămânem puternici, uniţi, pentru că terorismul loveşte orb, oriunde în lume, oriunde poate and întotdeauna îi loveşte pe cei nevinovaţi.”',
	'Emil Constantinescu as President of Romania, about family, 31.12.1997'	=> '“Ea e institutia pentru care trebuie sa lucreze toate celelalte institutii si fara care aceasta natiune n-ar avea nici memoria trecutului si nici speranta viitorului.”',
	'Emil Constantinescu about Democracy, 09.10.2018.'	=> '“Suntem într-un moment în care ... Elitele se dizolvă, ... Discursul politic se prăbuşeşte în populism, democraţiile au nevoie de conştiinţă democratică, nu numai de instituţii democratice.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=6361&_PRID=arh
	//Zilei Solidaritatii Nationale Împotriva Dictaturii
	'Ion Iliescu, încheierea “Anului Eminescu”, Botoşani,14.01.2001.'	=> '“Într-o lume care-and caută noi fundamente pentru o existenţă solidară, preocupată să reziste unor provocări multiple and inimaginabile cu decenii sau chiar numai cu ani în urmă, noi venim cu tezaurul experienţei pilduitoare a comuniunii inter-umane, trăită sub semnul acelei intraductibile “omenii”.',
	'Ion Iliescu, încheierea “Anului Eminescu”, Botoşani,14.01.2001.'	=> '“Pentru poporul român, omenia reprezintă expresia eternă a convingerii că valoarea supremă este sufletul omenesc; iar “expresia” integrală a sufletului românesc”, după o formulă celebră, a fost Mihai Eminescu.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=936&_PRID=arh
	'Emil Constantinescu about Romanian Revolution and Democracy, 16.12.1998.'	=> '“...oamenii s-au ridicat, au luptat and unii dintre ei au murit pentru a obţine drepturi atunci interzise and care astăzi deja ni se par fireşti.”', 
	'Emil Constantinescu about Romanian Revolution, 16.12.1998.'	=> '“Atunci s-a dovedit încă o dată că foarte multi oameni pot datora aproape totul foarte puţinor oameni.”', 
	'Emil Constantinescu about Romanian Revolution, 16.12.1998.'	=> '“Oameni care într-un moment decisiv au avut curajul să ne schimbe destinul. Indiferent de greutatea prezentului sau viitorului, nu cred că avem dreptul sa-i uitam nici pe ei, nici spiritul care i-a condus.”',  
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=5988&_PRID=arh
	'Ion Iliescu, Ziua Holocaustului în România, 12.10.2004.'	=> '“O astfel de tragedie nu trebuie să se mai repete, iar pentru aceasta nimic nu trebuie precupeţit pentru as tinerele generaţii să cunoască and să înţeleagă întreg adevărul.”',
	'Klaus Iohannis as president, cu ocazia Zilei Comemorării Victimelor Fascismului and Comunismului, 23.08.2018.'	=> 'Totalitarismele au distrus statul de drept, au încercat să spulbere libertatea de expresie, au încălcat, în mod flagrant, drepturile and libertăţile cetăţeneşti, însă nu au reuandt să reprime dorinţa de libertate and ataşamentul faţă de valorile democratice ale milioanelor de oameni care au crezut cu tărie că aceste regimuri pot fi învinse.',
	//www.presidency.ro/ro/media/mesaje/mesajul-presedintelui-romaniei-domnul-klaus-iohannis-transmis-in-cadrul-galei-regaseste-romania
	'Klaus Iohannis as president, în cadrul Galei „Regăsește România”, 22.10.2018.'	=> '"Natura, în ansamblul ei, nu trebuie percepută as un dat definitiv, ci trebuie preţuită and îngrijită continuu de către noi toţi.."',
	'Benjamin Netanyahu, The Times of Israel,17 May 2015.'	=> '"There is no room for racism and discrimination in our society, none... We will turn racism into something contemptible and despicable."',
	//www.gov.il/he/departments/news/speechcong030315
	'Benjamin Netanyahu on ISIS, 03.03.2015'	=> '"They just disagree among themselves who will be the ruler of that empire. In this deadly game of thrones, there is no place for America or for Israel, no peace for Christians, Jews, or Muslims who don\'t share the Islamist medieval creed. No rights for women. No freedom for anyone. So when it comes to Iran and ISIS, the enemy of your enemy is your enemy."',
	//www.haaretz.com/transcript-of-netanyahu-speech-1.5343049
	'Benjamin Netanyahu on Iran\'s Nuclear Weapons, before the UN General Assembly, 01.10.2013.'	=> '“If Israel is forced to stand alone, Israel will stand alone. Yet, in standing alone, Israel will know that we will be defending many, many others.”',
	'Benjamin Netanyahu vowed, “as PM of Israel in a speech before a joint session of US Congress, 03.03.2015.'	=> '“Even if Israel has to stand alone, Israel will stand. But I know that Israel does not stand alone, I know that America stands with Israel, I know that you stand with Israel.”',
));
