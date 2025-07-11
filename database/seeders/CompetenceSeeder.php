<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Competence;
use Illuminate\Database\Seeder;

final class CompetenceSeeder extends Seeder
{
    // phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
    /** @SuppressWarnings("PHPMD.ExcessiveMethodLength") */
    public static function run(): void
    {
        Competence::create([
            'description' => 'Chápe, jak je podstata skautingu vyjádřena ve třech principech, poslání a metodě, rozumí jim a ztotožňuje se s nimi. Chápe, jak skautský zákon a slib vyjadřují tři principy, a snaží se podle nich žít. Ví, proč jej oslovují myšlenky a ideje skautingu. Dokáže přiměřeně představit skauting a vysvětlit, proč je skautem. Umí předávat skautské hodnoty a v případě potřeby se jich zastávat.',
            'name' => 'Poslání a principy hnutí',
            'number' => '1',
        ]);
        Competence::create([
            'description' => 'Přiměřeně rozumí dějinám skautingu a jeho vztahu ke společnosti. Ví, z jakých kořenů skauting vychází, jakým prošel vývojem a jaká byla a je jeho role ve společnosti.',
            'name' => 'Poslání a principy hnutí',
            'number' => '2',
        ]);
        Competence::create([
            'description' => 'Rozumí jednotlivým prvkům skautské výchovné metody i metodě jako celku, rozpoznává ji v činnosti oddílu a přijímá ji. V oddílové činnosti uplatňuje prvky skautské výchovné metody k dosahování výchovných cílů. Rozumí významu oddílové rady pro fungující družinový systém.',
            'name' => 'Skautská výchovná metoda',
            'number' => '3',
        ]);
        Competence::create([
            'description' => 'Ovládá skautskou praxi a tábornické dovednosti umožňující skautskou činnost v přírodě (například bezpečnou manipulaci s nožem, sekyrou a pilou, rozdělání ohně a uvaření jídla na něm, postavení stanu). Učí se rozumět přírodě a vede k tomu členy oddílu.',
            'name' => 'Skautské dovednosti',
            'number' => '4',
        ]);
        Competence::create([
            'description' => 'Zná základní práva a povinnosti členů a činovníků Junáka – českého skauta a principy jeho řízení dle Stanov. Ví, že existují další vnitřní předpisy a kde je najde. Zná svoje středisko – jeho název, sídlo, oddíly střediska, nejdůležitější činovníky v něm. Ví, jak by středisko mělo být řízeno a jak by mělo fungovat. Ví, jaké je postavení střediska v organizační struktuře Junáka – českého skauta. Ví, jak se může zapojit do fungování střediska a organizace.',
            'name' => 'Junák – český skaut jako organizace',
            'number' => '5',
        ]);
        Competence::create([
            'description' => 'Umí pracovat s výchovnými nástroji využívanými v Junáku – českém skautu (zejména se stezkami a odborkami, dále s časopisy, závody, online nástroji...) a rozumí jejich přínosu. Používá-li jeho oddíl vlastní výchovné nástroje, umí pojmenovat jejich výhody a nedostatky. Ví, že existují skautské metodické příručky a weby a kde je najít.',
            'name' => 'Metodické nástroje Junáka – českého skauta',
            'number' => '6',
        ]);
        Competence::create([
            'description' => 'Zohledňuje psychické a fyzické limity členů oddílu s ohledem na aktuální situaci. Má přehled o omezeních svých svěřenců a umí tomu vhodně přizpůsobit program. Upozorňuje účastníky na potenciální rizika aktivit, vhodně je poučí. Na akci zajistí lékárničku. Zná a zohledňuje rizika spojená s pobytem v klubovně či v přírodě, i se specifickými činnostmi zde prováděnými. Zná požadavky na personální a technické zajištění rizikových aktivit. Nepouští se do aktivit, které přesahují jeho schopnosti pro zajištění bezpečnosti účastníků, nepřeceňuje se. Zná Kodex jednání dospělých a chápe ho jako důležitý pilíř prevence v oblasti bezpečného skautingu.',
            'name' => 'Zajištění bezpečnosti',
            'number' => '7',
        ]);
        Competence::create([
            'description' => 'Zná rizika aktuální doby a zohledňuje je při činnosti oddílu (např. závislostní chování, rizikové chování na sociálních sítích, další podobné jevy).',
            'name' => 'Řízení rizika',
            'number' => '8',
        ]);
        Competence::create([
            'description' => 'V krizové situaci se zorientuje, odhadne svoji roli a vhodně reaguje. Přivolá pomoc, pokud je třeba. Zná zásady chování při požáru, úrazu, tonutí, převržení lodi, dopravní nehodě, nepřízni počasí, nedostatku jídla či pití, ztracení členů oddílu apod. Ví, jaká jsou krizová doporučení a postupy v Junáku – českém skautu. O úrazu informuje vedoucího a dohodne s ním, kdo a jak bude informovat rodiče.',
            'name' => 'Řešení krizových situací',
            'number' => '9',
        ]);
        Competence::create([
            'description' => 'Ví, za jakých podmínek může sám právně jednat za své středisko (a kdy tak činí) a kdy už má právně jednat někdo jiný. Rozumí pojmům fyzická osoba, právnická osoba a statutární orgán (v kontextu Junáka – českého skauta).',
            'name' => 'Jednání v souladu s právem',
            'number' => '10',
        ]);
        Competence::create([
            'description' => 'Rozumí základům odpovědnosti za přestupek, trestný čin a za škodu. Ví, kdy nese jakou právní odpovědnost za své jednání a za škodu způsobenou nezletilými. Ví, že může být pojištěn na odpovědnost za škodu. Zná trestné činy a přestupky, které se mohou stát během činnosti. Je si vědom povinnosti oznámit/překazit trestný čin. Zná a dodržuje základní zákonná pravidla pro skautskou činnost v lesích, v přírodě a v dopravě a pravidla chránící osobnost členů organizace.',
            'name' => 'Jednání v souladu s právem',
            'number' => '11',
        ]);
        Competence::create([
            'description' => 'Umí posoudit závažnost zdravotního stavu a podle potřeby poskytne laickou první pomoc (včetně život ohrožujících stavů a přivolání RZS). Zná zásady poskytování první pomoci včetně zajištění vlastní bezpečnosti. Zajišťuje dodržování základních hygienických pravidel při činnosti.',
            'name' => 'Zdravověda',
            'number' => '12',
        ]);
        Competence::create([
            'description' => 'Vytváří fungující vztahy s lidmi, které vede, i se svými spolupracovníky. Respektuje druhé. Uvědomuje si, že družinu a oddíl tvoří dobrá parta kamarádů. Aktivně podporuje ducha dobré party, přiměřeně k nárokům příslušné věkové kategorie uplatňuje v činnosti principy družinového systému. Nemanipuluje s ostatními a nenechá manipulovat sebou.',
            'name' => 'Budování vztahů',
            'number' => '13',
        ]);
        Competence::create([
            'description' => 'Uvědomuje si, že ne všechnu svěřenou práci zvládne vykonat sám. Umí ostatním vhodně rozdělit úkoly, dostatečně je vysvětlit. Pohlídá jejich splnění.',
            'name' => 'Koordinace práce',
            'number' => '14',
        ]);
        Competence::create([
            'description' => 'Inspiruje ostatní v oddílové činnosti. Rozumí významu vnitřní motivace členů oddílu a podporuje její uplatňování. Rozumí významu hranic pro lidské jednání. Ví, jak hranice nastavovat a jakým způsobem vést k jejich dodržování. Rozumí tomu, jaké negativní dopady mohou mít tresty a nevhodné používání odměn. Při výchově přednostně hledá společně s členem oddílu možnosti nápravy a pracuje s přirozenými důsledky jednání. Vede členy k přijímání chyby jako příležitosti k vlastnímu rozvoji.',
            'name' => 'Práce s motivací',
            'number' => '15',
        ]);
        Competence::create([
            'description' => 'Ví, že za společné dílo jsou zodpovědní všichni, a přijímá svůj díl odpovědnosti za oddíl. V případě potřeby je schopen a ochoten dočasně převzít roli vedoucího.',
            'name' => 'Spolupráce',
            'number' => '16',
        ]);
        Competence::create([
            'description' => 'Podílí se na procesech sebehodnocení oddílu, zná skautské nástroje pro sebehodnocení oddílů a táborů. Umí získat zpětnou vazbu na konkrétní aktivitu a vhodně naložit s jejími výsledky.',
            'name' => 'Analýza současného stavu',
            'number' => '17',
        ]);
        Competence::create([
            'description' => 'Formuluje vhodné krátkodobé cíle a nastavuje jejich úroveň vzhledem k cílové skupině. Podílí se na stanovování dlouhodobých cílů oddílu.',
            'name' => 'Práce s cíli',
            'number' => '18',
        ]);
        Competence::create([
            'description' => 'V daném kontextu rozlišuje cíle a prostředky. Ke svým cílům používá přiměřené, efektivní a pro cílovou skupinu atraktivní prostředky, které jsou v souladu se skautskou výchovnou metodou. Hru využívá jako jeden z více možných prostředků k naplnění cílů, a nikoliv jako cíl sám o sobě. Ví, že skautský program nestojí jen na hraní her. Rozumí významu symbolického rámce.',
            'name' => 'Volba prostředku',
            'number' => '19',
        ]);
        Competence::create([
            'description' => 'Do programu vhodně zařazuje různé typy aktivit s ohledem na cíle, časový rozsah, specifika a potřeby účastníků, případná rizika (plán B pro případ silného deště, ujetého autobusu apod.). Umí připravený program dobře zrealizovat. Umí zareagovat na nečekanou situaci a dovede smysluplně pozměnit plánovaný program. Nepoužívá improvizaci jako zástěrku nepřipravenosti.',
            'name' => 'Stanovení časového plánu',
            'number' => '20',
        ]);
        Competence::create([
            'description' => 'Dokáže vysvětlit, kdy a proč se má vyhodnocovat naplnění krátkodobých cílů. Dokáže popsat jak dobře naplněné části plánu, tak rozdíly mezi plánem a realitou, pojmenovat jejich příčiny a vyvodit z toho důsledky.',
            'name' => 'Vyhodnocení plánu',
            'number' => '21',
        ]);
        Competence::create([
            'description' => 'Sestavuje odpovídající rozpočet malé akce, během akce si udržuje přehled o příjmech a výdajích. Rozpozná platné prvotní doklady různých typů a ví, jak je získat při různých způsobech nákupu (např. nákup na internetu, na dobírku či s platební kartou). Dovede vyplnit příjmový a výdajový pokladní doklad a vést pokladní knihu akce. Ví, kde začíná a končí jeho odpovědnost za hospodaření akce. Ví, komu patří majetek používaný oddílem a jak se získává, jak a kde se berou peníze v oddíle a jak se s nimi hospodaří. Řídí se pravidly oddílu a střediska pro nakládání s prostředky a majetkem.',
            'name' => 'Hospodaření',
            'number' => '22',
        ]);
        Competence::create([
            'description' => 'Všestranně se rozvíjí. Bere skauting jako příležitost k seberozvoji a ví, jak mu skauting v rozvoji pomáhá.',
            'name' => 'Rozvoj a osobní cíle',
            'number' => '23',
        ]);
        Competence::create([
            'description' => 'Zná své silné a slabé stránky. Umí zhodnotit, na co stačí jeho schopnosti. Pečuje o své duševní zdraví. Je si vědom, proč jako dobrovolník působí ve skautském hnutí.',
            'name' => 'Náhled na sebe',
            'number' => '24',
        ]);
        Competence::create([
            'description' => 'Umí přijímat zpětnou vazbu, včetně konstruktivní kritiky. Dle potřeby o zpětnou vazbu žádá nebo ji aktivně získává. Chápe, kdy je cílem kritiky poukázat na prostor ke zlepšení, umí posoudit oprávněnost vznesených námětů, přijmout je a vzít si z nich to nejlepší.',
            'name' => 'Přijímání zpětné vazby',
            'number' => '25',
        ]);
        Competence::create([
            'description' => 'Skautský slib a zákon vztahuje na svůj osobní život i na role, které zastává. Přijímá odpovědnost za své jednání. Odpovědně plní své úkoly, dodržuje termíny. Přijímá přiměřený objem práce (přijímá příležitosti rozvíjet a podporovat oddíl, ale nenabírá si práci nad vlastní možnosti).',
            'name' => 'Sebeřízení',
            'number' => '26',
        ]);
        Competence::create([
            'description' => 'Své znalosti a dovednosti z dalších zájmů a odborností umí přiměřeně nabízet ve skautské činnosti. Uvědomuje si svůj přínos pro oddíl.',
            'name' => 'Unikátní odbornost',
            'number' => '27',
        ]);
        Competence::create([
            'description' => 'Vhodnou formou poskytuje zpětnou vazbu. Zná její náležitosti, aby byla přínosná a aby neuškodila. Se zpětnou vazbou pracuje jako s rozvojovým prostředkem.',
            'name' => 'Poskytování zpětné vazby',
            'number' => '28',
        ]);
        Competence::create([
            'description' => 'Připravované programy přizpůsobuje cílovým skupinám (např. různým věkovým kategoriím). Ví, jaká jsou specifika práce s jednotlivými věkovými kategoriemi, a respektuje to při přípravě programu. Uvědomuje si odlišný styl práce v koedukovaném či věkově smíšeném kolektivu. Ví, že volbou a zadáním programů může působit na podobu vztahů mezi členy oddílu. Umí se zapojit do her a jiných aktivit se svými svěřenci. Dokáže vhodně uzpůsobit program účastníkům se speciálními potřebami.',
            'name' => 'Rozvoj skupiny',
            'number' => '29',
        ]);
        Competence::create([
            'description' => 'Poznává své svěřence. Zajímá se o ně, o jejich životní situaci i mimo skautský oddíl. Dokáže u nich pojmenovat jejich přednosti a možnosti dalšího rozvoje, respektuje jejich zvláštnosti a podporuje u nich vědomí vlastní hodnoty i respekt k druhým. Ví, že u členů oddílu se mohou projevit specifické potřeby, a ví, kam se obrátit, když s nimi potřebuje pomoci. Dokáže u svého svěřence rozpoznat, že se chová jinak než obvykle nebo že se ocitl v problémové situaci, a přiměřeně na to zareaguje. (Člen oddílu např. začne být plačtivý, nadměrně agresivní, úzkostný, i když takový nebýval.) V závažných případech konzultuje a koordinuje postup s vedoucím. Zná možné projevy sociálně patologických jevů v oddíle (šikana, manipulace, ...) a spolupracuje s vůdcem na jejich odstranění.',
            'name' => 'Osobní přístup',
            'number' => '30',
        ]);
        Competence::create([
            'description' => 'Jde příkladem mladším i vrstevníkům a nezneužívá svého postavení. Ví, že jako vzor hraje důležitou roli v utváření osobnosti mladších členů oddílu. Nepřetvařuje se, není pokrytecký.',
            'name' => 'Vlastní příklad',
            'number' => '31',
        ]);
        Competence::create([
            'description' => 'Umí jednat s dětmi i dospělými. Při komunikaci respektuje individualitu svého protějšku. Uplatňuje zásady aktivního naslouchání. Komunikační styl a kanály přizpůsobuje aktuální situaci a osobám, s nimiž hovoří. Přiměřeně komunikuje s rodiči členů oddílu.',
            'name' => 'Komunikační styly a kanály',
            'number' => '32',
        ]);
        Competence::create([
            'description' => 'Zná významné trendy ve světě dětí, sleduje dění ve světě dospělých. Pro svou činnost získává informace (o akcích, základnách, inspiraci pro program) ze skautských i jiných informačních zdrojů. K informacím přistupuje kriticky a je schopen je ověřovat.',
            'name' => 'Získávání informací',
            'number' => '33',
        ]);
        Competence::create([
            'description' => 'Srozumitelně (jednoduše) sděluje informace. V diskusi vyjadřuje své názory a dává prostor k vyjádření ostatním.',
            'name' => 'Verbální a neverbální projev',
            'number' => '34',
        ]);
    }
    // phpcs::enable
}
