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

$lang = array_merge($lang, array(
	//Transcript: Donald Trump's Foreign Policy Speech, The New York Times (27 April 2016)
	'Donald Trump despre Israel, Foreign Policy Speech (27 April 2016).'	=> '"Israel, marele nost prieten şi o adevărată democraţie în Orientul Mijlociu a fost înmărmurit şi criticizat de o administrare care nevoieşte claritate morală..."',
	//WikiQuotes from Trump: The Art of the Deal (1987) by Donald J. Trump with Tony Schwartz.
	'Donald Trump: Arta Afacerii (1987)'	=> '"Încerc să învăţ din trecut, dar planific pentru viitor prin focalizare exclusivă pe prezent."',
	//BARACK HUSSEIN OBAMA ON ISLAM AND CHRISTIANITY BY GEOFFREY GRIDER
	//www.nytimes.com/2006/06/28/us/politics/2006obamaspeech.html
	'Barack Obama despre Bible, Washigton D.C., Iunie 28, 2006.'	=> '“Care pasaj din scriptură ar trebui să ne ghideze poliţa publică? Ar trebui să mergem cu Leviticul, care sugerează că slavia este OK şi că a mânca scoici este o abominaţie?  Ori ar trebui să mergem cu Deuteronom, care sugerează pietruirea copilului tău dacă se abate de la credinţă?”',
	//Cairo University, Cairo, Egypt (4 June 2009), Full text at Wikisource
	'Barack Obama despre Koran, Un Nou Început (Iunie 2009)' => 'Sfântul Coran ne spune, "O omenire! Noi v-am creat mascul şi o femelă; şi v-am făcut întru naţiuni şi triburi astfel ca voi să vă puteţi cunoaşte unii pe alţii."',
	'Barack Obama despre Torah, Un Nou Început (Iunie 2009)' => 'Talmudul ne spune, "Plinătatea Torei este pentru scopul să promoveze pace."',
	'Barack Obama despre Bible, Un Nou Început (Iunie 2009)' => 'Biblia Sfântă ne spune, "Binecuvântaţi sunt pacificatorii, căci ei vor fi chemaţi fii de Dumnezeu."',
	'Barack Obama despre Islam.'	=> '“Aceste rituale ne amintesc de principiile care noi le ţinem în common, şi rolul Islamului în a avansa justiţie, progres, toleranţă, şi dignitatea a tuturor fiinţelor umane.”',
	//Trump's interview with David Brody, CBN News (29 January 2017)
	'Donald Trump despre God, intervieu cu David Brody, CBN News (29 Ianuarie 2017).'	=>  '“Eu întotdeauna am simţit nevoia să mă rog... Eu aşi spune căci biroul este atât de puternic căci ai nevoie de Dumnezeu chiar mai mult.”',
	//Donald Trump 2016 RNC draft speech transcript, Politico (21 July 2016)
	'Donald Trump despre America, 2016 Convenţia Naţională Republicană (21 Iulie 2016).'	=>  '	Eu fac această promisiune: Noi vom face America mândră iar, noi vom face America puternică iar, noi vom face America sigură iar, şi noi vom Face America Măreaţă Iar! Dumnezeu să să te binecuvinte şi noapte bună! Vă iubesc!.”',
	//HuffPost
	'Barack Obama, Dintr-o Rugăciune cu ocazia unui Mic Dejun Paşti, 06.04.2010, Casa Albă.'	=> '“Pentru chiar după trecerea a 2,000 ani, noi încă putem picture the moment in our mind’s eye. The young man from Nazareth marched through Jerusalem; [...] — that the Son of Man was not to be found in his tomb and that Jesus Christ had risen..',
	//en.wikiquote.org/wiki/Vladimir_Putin#Cooperation,_Terrorism,_UK_&_USA,_President_Trump,_Resolving_Conflict,_Defense,_Crimea,_The_Media,_Nuclear_Weapons_Policy:_15th_Plenary_Session_(18_October_2018)
	'Vladimir Putin despre Terrorism, 15.11.2001.'	=> '“Î-mi plec capul victimelor terorismului. Sunt puternic impresionat de courajul rezidenţilor din New York. Marea cetate şi marea naţiune Americană are să câştige!”',
	//content.time.com/time/magazine/article/0,9171,1734814,00.html
	'Vladimir Putin despre Demoralisation, 24.04.2008'	=> '“Eu întotdeauna am reacţionat negativ la cei care cu nasul lor mucos şi fantazii erotice buznesc întru vieţile altora.”',
	'Vladimir Putin despre Soviet Union'	=> '“Oricărui nu-i lipseşte Uniunea Sovietică n-are inimă. Oricine o vrea înapoi n-are creier.”',
	'Adrian Năstase\'s as Prime Minister despre Taxes and Chicken Farm'	=> '“Iar pe cei care s-au apucat să-mi numere găinile, îi rog să-mi numere şi ouăle.”',
	'Traian Băsescu despre Israel, 18-21.01.2014, la Ramallah'	=> '“...Nimeni nu va nega istoria, dar scopul procesului de pace nu este restabilirea adevarului istoric, ci pacea.”',
	//www.bugetul.ro/traian-basescu-face-un-anunt-exploziv-cum-poate-romania-sa-zgaltaie-ue-in-urmatoarele-sase-luni/
	//Andreea Corina Chiriac, redactor, actual
	'Traian Băsescu despre Uniunea Europeană,  09.01.2019.'	=> '“România poate zgâlţâi Uniunea Europeană care pare debusolată şi fără curaj în a-şi defini propriul viitor, lansând de la înălţimea Preşedinţiei Rotative a Uniunii Europene trei mari proiecte...”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=8096&_PRID=arh
	'Traian Băsescu despre terorism, la Ambasada U.K. & Irlandei de Nord, 12.06.2005.'	=> '“Va trebui să rămânem puternici, uniţi, pentru că terorismul loveşte orb, oriunde în lume, oriunde poate şi întotdeauna îi loveşte pe cei nevinovaţi.”',
	'Emil Constantinescu ca Preşedinte al României, despre familie, 31.12.1997'	=> '“Ea e institutia pentru care trebuie sa lucreze toate celelalte institutii si fara care aceasta natiune n-ar avea nici memoria trecutului si nici speranta viitorului.”',
	'Emil Constantinescu despre Democraţie, 09.10.2018.'	=> '“Suntem într-un moment în care ... Elitele se dizolvă, ... Discursul politic se prăbuşeşte în populism, democraţiile au nevoie de conştiinţă democratică, nu numai de instituţii democratice.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=6361&_PRID=arh
	//Zilei Solidaritatii Nationale Împotriva Dictaturii
	'Ion Iliescu, încheierea “Anului Eminescu”, Botoşani,14.01.2001.'	=> '“Într-o lume care-şi caută noi fundamente pentru o existenţă solidară, preocupată să reziste unor provocări multiple şi inimaginabile cu decenii sau chiar numai cu ani în urmă, noi venim cu tezaurul experienţei pilduitoare a comuniunii inter-umane, trăită sub semnul acelei intraductibile “omenii”.',
	'Ion Iliescu, încheierea “Anului Eminescu”, Botoşani,14.01.2001.'	=> '“Pentru poporul român, omenia reprezintă expresia eternă a convingerii că valoarea supremă este sufletul omenesc; iar “expresia” integrală a sufletului românesc”, după o formulă celebră, a fost Mihai Eminescu.”',
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=936&_PRID=arh
	'Emil Constantinescu despre Revoluţia Română şi Democraţie, 16.12.1998.'	=> '“...oamenii s-au ridicat, au luptat şi unii dintre ei au murit pentru a obţine drepturi atunci interzise şi care astăzi deja ni se par fireşti.”', 
	'Emil Constantinescu despre Revoluţia Română, 16.12.1998.'	=> '“Atunci s-a dovedit încă o dată că foarte multi oameni pot datora aproape totul foarte puţinor oameni.”', 
	'Emil Constantinescu despre Revoluţia Română, 16.12.1998.'	=> '“Oameni care într-un moment decisiv au avut curajul să ne schimbe destinul. Indiferent de greutatea prezentului sau viitorului, nu cred că avem dreptul sa-i uitam nici pe ei, nici spiritul care i-a condus.”',  
	//old.presidency.ro/index.php?_RID=det&tb=date_arhiva&id=5988&_PRID=arh
	'Ion Iliescu, Ziua Holocaustului în România, 12.10.2004.'	=> '“O astfel de tragedie nu trebuie să se mai repete, iar pentru aceasta nimic nu trebuie precupeţit pentru ca tinerele generaţii să cunoască şi să înţeleagă întreg adevărul.”',
	'Klaus Iohannis ca preşedinte, cu ocazia Zilei Comemorării Victimelor Fascismului şi Comunismului, 23.08.2018.'	=> 'Totalitarismele au distrus statul de drept, au încercat să spulbere libertatea de expresie, au încălcat, în mod flagrant, drepturile şi libertăţile cetăţeneşti, însă nu au reuşit să reprime dorinţa de libertate şi ataşamentul faţă de valorile democratice ale milioanelor de oameni care au crezut cu tărie că aceste regimuri pot fi învinse.',
	//www.presidency.ro/ro/media/mesaje/mesajul-presedintelui-romaniei-domnul-klaus-iohannis-transmis-in-cadrul-galei-regaseste-romania
	'Klaus Iohannis ca preşedinte, în cadrul Galei „Regăsește România”, 22.10.2018.'	=> '"Natura, în ansamblul ei, nu trebuie percepută ca un dat definitiv, ci trebuie preţuită şi îngrijită continuu de către noi toţi.."',
	'Benjamin Netanyahu, The Times of Israel,17 May 2015.'	=> '"There is no room for racism and discrimination in our society, none... We will turn racism into something contemptible and despicable."',
	//www.gov.il/he/departments/news/speechcong030315
	'Benjamin Netanyahu despre ISIS, 03.03.2015'	=> '"They just disagree among themselves who will be the ruler of that empire. In this deadly game of thrones, there is no place for America or for Israel, no peace for Christians, Jews, or Muslims who don\'t share the Islamist medieval creed. No rights for women. No freedom for anyone. So when it comes to Iran and ISIS, the enemy of your enemy is your enemy."',
	'בנימין נתניהו על , 03.03.2015 ISIS'	=> '"במשחקי כס קטלניים אלה, אין כל מקום לאמריקה או לישראל, אין כל שלום לנוצרים, יהודים או מוסלמים שאינם חולקים את אותה אמונה אסלאמיסטית ימי ביניימית, אין כל זכויות לנשים ואין כל חירות לאיש. לכן, ככל שהדבר נוגע לאיראן ודאע"ש, האויב של האויב שלך הוא האויב שלך."',
	//www.haaretz.com/transcript-of-netanyahu-speech-1.5343049
	'Benjamin Netanyahu despre Iran\'s Nuclear Weapons, before the UN General Assembly, 01.10.2013.'	=> '“If Israel is forced to stand alone, Israel will stand alone. Yet, in standing alone, Israel will know that we will be defending many, many others.”',
	'Benjamin Netanyahu vowed, “as PM of Israel in a speech before a joint session of US Congress, 03.03.2015.'	=> '“Even if Israel has to stand alone, Israel will stand. But I know that Israel does not stand alone, I know that America stands with Israel, I know that you stand with Israel.”',
));
