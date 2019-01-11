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
	'J.R.R. Tolkien, The Hobbit.'	=> '“Fie ca vântul sub aripile tale să te poarte unde soarele vâsleşte şi luna umblă.”',
	'J.R.R. Tolkien, The Hobbit.'	=> '“Unde`i viaţă acolo`i speraţă.”',
	'J.R.R. Tolkien, The Hobbit.'	=> '“Înntr-o gaură în pământ acolo trăia un hobbit.”',
	//Credit: Thanks to Laura Formisano at rd.com/quotes
	'Christofer Lloyd ca Dr. Emmeth Brown, Înapoi în Viitor.'	=> '“Unde te duci tu, nu avem nevoie de drumuri.”',
	'Mae West ca Tira, Eu Nu Sunt Înger.'	=> '“Aşadar, nu`s bărbaşii în viaţa ta care contează, este viaşa în bărbaţii tăi.”',
	'Mae West ca Lady Lou, Ea Face Lui Rău.'	=> '“De ce Tu nu urci câteodată să mă vezi?”',
	'Robert Armstrong ca Carl Denham, King Kong.'	=> '“O, nu, n`au fost aeroplanenele. A fost Frumoasa omorând Fiara.”',
	'Tom Hanks ca Forrest Gump, Forrest Gump.'	=> 'Mama Mea mereu spunea: `Viața era ca o cutie de ciocolate; Niciodată tu nu știi cu ce o să te alegi.`',
	'Clint Eastwood ca Harry Callahan, Sudden Impact.'	=> '“Hai odată... fă-mi ziua.”',
	'Harrison Ford ca Indiana Jones, Cavalerii Chivotului Pierdut.'	=> '“Nu`s anii, dulceaşo. Este kilometrajul.”',
	'Judy Garland ca Dorothy Gale, Vrăjitorul de Oz.'	=> '“Nici unde nu-i ca acasă.”',
	'Matthew Broderick ca Ferris Bueller, Ziua Liberă a lui Ferris Bueller.'	=> '“Viaţa se mişcă foarte repede. Dacă nu te opreşti şi te uiţi din când în când, s-ar putea să o pierzi.”',
	'Humphrey Bogart ca Rick Blaine, Casablanca.'	=> '“Întotdeauna vom avea Paris.”',
	'Christian Bale ca Batman, Batman Începe.'	=> '“Nu este cine sunt pe dedesupt ci ceea ce mă defineşte.”',
	'Tim Robbins ca Andy Dufresne, Mânuirea lui Shawshank.'	=> '“Fă-te ocupat trăind ori ajungi ocupat murind.”',
	'Kevin Spacey ca Roger “Verbal” Kint, Sustectul Uzual.'	=> '“Cel mai mare truc pe care Dracu la scos era să convingă lumea, el nu există.”',
	'Alec Baldwin ca Blake, Glengarry Glen Ross.'	=> '“Un singut lucru contează în viaţă—  Fă-i să cânte pe linia care e punctată.”',
	'Mel Gibson ca William Wallace, Braveheart.'	=> '“Ei ne-ar putea lua viaţa, dar ei niciodată nu ne vor lua libertatea!”',
	'Tom Hanks ca Jimmy Dugan, O Ligă pe Cont Propiriu.'	=> '“Nu este plângere în baseball!”',
	'Al Pacino ca Michael Corleone, The Godfather: Part II.'	=> '“Ţine-ţi prietenii apoape, dar your enemies closer.”',
	'Brad Pitt ca Benjamin Button, Cazul Curious al lui Benjamin Button.'	=> '“Vieţele nostre sunt definite de oportunităţi, chiar acelea ce noi ratăm.”',
	'Ali MacGraw ca Jennifer Cavilleri, Poveste de Iubire.'	=> '“Dragoste înseamnă, niciodată nu trebuie să spui că îţi pare rău.”',
	'Kathleen Turner ca Jessica Rabbit, Cine a Ramat Roger Rabbit.'	=> '“Nu`s rău. Doar sunt creonat în acest fel.”',
	'Renee Zellweger ca Dorothy Boyd, Jerry Maguire.'	=> '“Mai avut la “Alo.”',
	'Frank Oz ca Yoda, Star Wars Episod V: Imperiul Contra Atacă.'	=> '“Nu încerca. Fă-ori nu fă. Nu este şi încercare.”',
	'Samuel E. Wright ca Sebastian, Mica Sirenă.'	=> '“Alga marină e întotdeauna mai verde în lacul altuia.”',
	'Takashi Shimura ca Kambei Shimada, Şapte Samurai.'	=> '“Prin protejarea altora vă salvaţi înşivă.”',
	'Paul Newman ca Luke, Luke Mână Rece.'	=> '“Numind-un jobul tău nu-l fă corect, boss.”',
	'Strother Martin ca Captain, Luke Mână Rece.'	=> '“Ceea ce-am obţinut aici este faliment de comunicare.”',
	'Jack Nicholson ca The Joker, Batman.'	=> '“Chirurgul meu estetic întotdeauna spunea, dacă trebuie să mergi, pleacă cu un zâmbet.”',
	'Jack Nicholson ca Col. Nathan R. Jessup .'	=> '“Tu nu poți mânui adevărul!”',
	//Originally Published on Readers Digest
	//Credit: starwars.com  Ewan Gordon McGregor replaced Sir Alec Guinness
	'Liam Neeson ca Qui-Gon Jinn, Star Wars Episod I: Fantoma Ameninţă.'	=> '“Abilitatea să vorbeşti nu te face intelligent! Acum dute afară de aici.”',
	'Liam Neeson ca Qui-Gon Jinn, Star Wars Episod I: Fantoma Ameninţă.'	=> '“Întotdeauna este un peşte mai mare.”',
	'Ewan McGregor ca Obi-Wan Kenobi, Star Wars Episod II: Atacul Clonelor.'	=> '“Fii reticent de gândurile tale, Anakin, ele te trădează.”',
	'Ewan McGregor ca Obi-Wan Kenobi, Star Wars Episod II: Atacul Clonelor.'	=> '“Dacă te defineşti însuţi prin puterea să iei viaţă, dorinţa să domini, să posezi… atunci nu ai nimic.”',
	//Originally Published on starwars.com
	//moviequotedb.com
	'Andy Secombe ca Watto, Star Wars Episod I: Fantoma Ameninţă.'	=> '“Nu, ei nu mi-ar! Ce, tu crezi că tu eşti vre-un fel de jedi, vânturându-ţi mâna în jur astfel? Eu sunt un Toydarian! Trucuri mintale nu funcţioneză pe mine, doar bani! Nu bani, nu piese, nu afacere!”',
	'Sir Alec Guinness ca Obi-Wan Kenobi, Star Wars Episod IV: O Nouă Speranţă.'	=> '“Ochii ţăi te pot înşela; nu te-ncrede în ei.”',
	'Frank Oz ca Yoda, Star Wars Episod V: Imperiul Contra Atacă.'	=> '“Fiinţe luminoase suntem noi, nu această crudă materie.”',
	'Sir Alec Guinness ca Obi-Wan Kenobi, Star Wars Episod IV: O Nouă Speranţă.'	=> '“În experienţa mea, nu este astfel de lucru precum noroc.”',
	'Harrison Ford ca Han Solo, Star Wars Episod IV: O Nouă Speranţă.'	=> '“Forța să fie cu tine!”',
	'Harrison Ford as Han Solo, Star Wars Episod V: Imperiul Contra Atacă.'	=> '“Niciodată nu-mi spune pronosticurile.”',
	'Frank Oz ca Yoda, Star Wars Episod V: Imperiul Contra Atacă.'	=> '“Odată ce-ţi începi partea întunecoasă, veşnic îţi va domina destinul.”',
	'Frank Oz as Yoda, Wars Episod I: Ameninţarea Fantomei.'	=> '“Frica conduce la mânie, mânia conduce la ură, ura conduce la suferinţă.”',
	'Tom Hanks ca Jim Lovell, Apollo 13.'	=> '“Houston, avem o problemă.”',
	'Vivien Leigh ca Scarlett O`Hara, Dus cu Vântul.'	=> '“După toate, mâine este o altă zi!”',
	//Originally Published on https://ro.wikipedia.org/wiki/100_de_ani...100_de_replici_memorabile
));
