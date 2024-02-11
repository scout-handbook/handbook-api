SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Table structure for table `competences`
--

CREATE TABLE IF NOT EXISTS `competences` (
  `id` binary(16) NOT NULL,
  `number` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` text COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `competences`
--

TRUNCATE TABLE `competences`;
--
-- Dumping data for table `competences`
--

INSERT INTO `competences` (`id`, `number`, `name`, `description`) VALUES
(0x02f1f6b073a942d88a4bebe80c824531, 14, 'Koordinace práce', 'Uvědomuje si, že ne všechnu svěřenou práci zvládne vykonat sám. Umí ostatním vhodně rozdělit úkoly, dostatečně je vysvětlit. Pohlídá jejich splnění.'),
(0x17254afeb62a4cee99301eb680172239, 8, 'Řízení rizika', 'Zná rizika aktuální doby a zohledňuje je při činnosti oddílu (např. závislostní chování, rizikové chování na sociálních sítích, další podobné jevy).'),
(0x1b30d88289254cdc96aa41dec94ef99b, 2, 'Poslání a principy hnutí', 'Přiměřeně rozumí dějinám skautingu a jeho vztahu ke společnosti. Ví, z jakých kořenů skauting vychází, jakým prošel vývojem a jaká byla a je jeho role ve společnosti.'),
(0x20df97dd60ef4bcdb4a2b099e93c8a3b, 21, 'Vyhodnocení plánu', 'Dokáže vysvětlit, kdy a proč se má vyhodnocovat naplnění krátkodobých cílů. Dokáže popsat jak dobře naplněné části plánu, tak rozdíly mezi plánem a realitou, pojmenovat jejich příčiny a vyvodit z toho důsledky.'),
(0x2338e4afea47462bb3879f98ac248342, 1, 'Poslání a principy hnutí', 'Chápe, jak je podstata skautingu vyjádřena ve třech principech, poslání a metodě, rozumí jim a ztotožňuje se s nimi. Chápe, jak skautský zákon a slib vyjadřují tři principy, a snaží se podle nich žít. Ví, proč jej oslovují myšlenky a ideje skautingu. Dokáže přiměřeně představit skauting a vysvětlit, proč je skautem. Umí předávat skautské hodnoty a v případě potřeby se jich zastávat.'),
(0x25ee3e2ef3cc4eda8e4ea7b7cf059937, 20, 'Stanovení časového plánu', 'Do programu vhodně zařazuje různé typy aktivit s ohledem na cíle, časový rozsah, specifika a potřeby účastníků, případná rizika (plán B pro případ silného deště, ujetého autobusu apod.). Umí připravený program dobře zrealizovat. Umí zareagovat na nečekanou situaci a dovede smysluplně pozměnit plánovaný program. Nepoužívá improvizaci jako zástěrku nepřipravenosti.'),
(0x26acbcdf717941bd8537825c73faf8d0, 18, 'Práce s cíli', 'Formuluje vhodné krátkodobé cíle a nastavuje jejich úroveň vzhledem k cílové skupině. Podílí se na stanovování dlouhodobých cílů oddílu.'),
(0x29e7faf60bc14ac2a10da5198ddfda52, 23, 'Rozvoj a osobní cíle', 'Všestranně se rozvíjí. Bere skauting jako příležitost k seberozvoji a ví, jak mu skauting v rozvoji pomáhá.'),
(0x2dd37a6f8678475796a977ecca4b7317, 19, 'Volba prostředku', 'V daném kontextu rozlišuje cíle a prostředky. Ke svým cílům používá přiměřené, efektivní a pro cílovou skupinu atraktivní prostředky, které jsou v souladu se skautskou výchovnou metodou. Hru využívá jako jeden z více možných prostředků k naplnění cílů, a nikoliv jako cíl sám o sobě. Ví, že skautský program nestojí jen na hraní her. Rozumí významu symbolického rámce.'),
(0x32f2c0b37fc042e6990cd2e112ea97b2, 33, 'Získávání informací', 'Zná významné trendy ve světě dětí, sleduje dění ve světě dospělých. Pro svou činnost získává informace (o akcích, základnách, inspiraci pro program) ze skautských i jiných informačních zdrojů. K informacím přistupuje kriticky a je schopen je ověřovat.'),
(0x4e93517ac66a426883b2a8111d7fb710, 11, 'Jednání v souladu s právem', 'Rozumí základům odpovědnosti za přestupek, trestný čin a za škodu. Ví, kdy nese jakou právní odpovědnost za své jednání a za škodu způsobenou nezletilými. Ví, že může být pojištěn na odpovědnost za škodu. Zná trestné činy a přestupky, které se mohou stát během činnosti. Je si vědom povinnosti oznámit/překazit trestný čin. Zná a dodržuje základní zákonná pravidla pro skautskou činnost v lesích, v přírodě a v dopravě a pravidla chránící osobnost členů organizace.'),
(0x4f4610267ee4489ea21a87c85e3e48d6, 27, 'Unikátní odbornost', 'Své znalosti a dovednosti z dalších zájmů a odborností umí přiměřeně nabízet ve skautské činnosti. Uvědomuje si svůj přínos pro oddíl.'),
(0x4fd51cb4f11d406b8a43274d0c06820f, 9, 'Řešení krizových situací', 'V krizové situaci se zorientuje, odhadne svoji roli a vhodně reaguje. Přivolá pomoc, pokud je třeba. Zná zásady chování při požáru, úrazu, tonutí, převržení lodi, dopravní nehodě, nepřízni počasí, nedostatku jídla či pití, ztracení členů oddílu apod. Ví, jaká jsou krizová doporučení a postupy v Junáku – českém skautu. O úrazu informuje vedoucího a dohodne s ním, kdo a jak bude informovat rodiče.'),
(0x576d1b460b8d488a9fe800cf7f2b2637, 22, 'Hospodaření', 'Sestavuje odpovídající rozpočet malé akce, během akce si udržuje přehled o příjmech a výdajích. Rozpozná platné prvotní doklady různých typů a ví, jak je získat při různých způsobech nákupu (např. nákup na internetu, na dobírku či s platební kartou). Dovede vyplnit příjmový a výdajový pokladní doklad a vést pokladní knihu akce. Ví, kde začíná a končí jeho odpovědnost za hospodaření akce. Ví, komu patří majetek používaný oddílem a jak se získává, jak a kde se berou peníze v oddíle a jak se s nimi hospodaří. Řídí se pravidly oddílu a střediska pro nakládání s prostředky a majetkem.'),
(0x5772e8b3af124b12a60714cfa31ab060, 13, 'Budování vztahů', 'Vytváří fungující vztahy s lidmi, které vede, i se svými spolupracovníky. Respektuje druhé. Uvědomuje si, že družinu a oddíl tvoří dobrá parta kamarádů. Aktivně podporuje ducha dobré party, přiměřeně k nárokům příslušné věkové kategorie uplatňuje v činnosti principy družinového systému. Nemanipuluje s ostatními a nenechá manipulovat sebou.'),
(0x5bf23dfb985047f484df91d2b146814a, 7, 'Zajištění bezpečnosti', 'Zohledňuje psychické a fyzické limity členů oddílu s ohledem na aktuální situaci. Má přehled o omezeních svých svěřenců a umí tomu vhodně přizpůsobit program. Upozorňuje účastníky na potenciální rizika aktivit, vhodně je poučí. Na akci zajistí lékárničku. Zná a zohledňuje rizika spojená s pobytem v klubovně či v přírodě, i se specifickými činnostmi zde prováděnými. Zná požadavky na personální a technické zajištění rizikových aktivit. Nepouští se do aktivit, které přesahují jeho schopnosti pro zajištění bezpečnosti účastníků, nepřeceňuje se. Zná Kodex jednání dospělých a chápe ho jako důležitý pilíř prevence v oblasti bezpečného skautingu.'),
(0x7102f9c637c14df9a11456554a423219, 24, 'Náhled na sebe', 'Zná své silné a slabé stránky. Umí zhodnotit, na co stačí jeho schopnosti. Pečuje o své duševní zdraví. Je si vědom, proč jako dobrovolník působí ve skautském hnutí.'),
(0x7521c31df6b74f4487356b78636e1b35, 31, 'Vlastní příklad', 'Jde příkladem mladším i vrstevníkům a nezneužívá svého postavení. Ví, že jako vzor hraje důležitou roli v utváření osobnosti mladších členů oddílu. Nepřetvařuje se, není pokrytecký.'),
(0x78e57c06662549ff9b39e0e883e65f8d, 26, 'Sebeřízení', 'Skautský slib a zákon vztahuje na svůj osobní život i na role, které zastává. Přijímá odpovědnost za své jednání. Odpovědně plní své úkoly, dodržuje termíny. Přijímá přiměřený objem práce (přijímá příležitosti rozvíjet a podporovat oddíl, ale nenabírá si práci nad vlastní možnosti).'),
(0x84b5154b9c1e4038b732bc1f7165f242, 16, 'Spolupráce', 'Ví, že za společné dílo jsou zodpovědní všichni, a přijímá svůj díl odpovědnosti za oddíl. V případě potřeby je schopen a ochoten dočasně převzít roli vedoucího.'),
(0x932cdf1ed1064930a21888b29a774c6e, 12, 'Zdravověda', 'Umí posoudit závažnost zdravotního stavu a podle potřeby poskytne laickou první pomoc (včetně život ohrožujících stavů a přivolání RZS). Zná zásady poskytování první pomoci včetně zajištění vlastní bezpečnosti. Zajišťuje dodržování základních hygienických pravidel při činnosti.'),
(0x999c5df126b8486aa64534c47271e29a, 17, 'Analýza současného stavu', 'Podílí se na procesech sebehodnocení oddílu, zná skautské nástroje pro sebehodnocení oddílů a táborů. Umí získat zpětnou vazbu na konkrétní aktivitu a vhodně naložit s jejími výsledky.'),
(0x9a778803358448a489d57dbebacc6a88, 32, 'Komunikační styly a kanály', 'Umí jednat s dětmi i dospělými. Při komunikaci respektuje individualitu svého protějšku. Uplatňuje zásady aktivního naslouchání. Komunikační styl a kanály přizpůsobuje aktuální situaci a osobám, s nimiž hovoří. Přiměřeně komunikuje s rodiči členů oddílu.'),
(0xa27495bca1574e42a05a1d1c06a8ba7f, 30, 'Osobní přístup', 'Poznává své svěřence. Zajímá se o ně, o jejich životní situaci i mimo skautský oddíl. Dokáže u nich pojmenovat jejich přednosti a možnosti dalšího rozvoje, respektuje jejich zvláštnosti a podporuje u nich vědomí vlastní hodnoty i respekt k druhým. Ví, že u členů oddílu se mohou projevit specifické potřeby, a ví, kam se obrátit, když s nimi potřebuje pomoci. Dokáže u svého svěřence rozpoznat, že se chová jinak než obvykle nebo že se ocitl v problémové situaci, a přiměřeně na to zareaguje. (Člen oddílu např. začne být plačtivý, nadměrně agresivní, úzkostný, i když takový nebýval.) V závažných případech konzultuje a koordinuje postup s vedoucím. Zná možné projevy sociálně patologických jevů v oddíle (šikana, manipulace, ...) a spolupracuje s vůdcem na jejich odstranění.'),
(0xa2f952567d984de38fe8ade2139ee955, 15, 'Práce s motivací', 'Inspiruje ostatní v oddílové činnosti. Rozumí významu vnitřní motivace členů oddílu a podporuje její uplatňování. Rozumí významu hranic pro lidské jednání. Ví, jak hranice nastavovat a jakým způsobem vést k jejich dodržování. Rozumí tomu, jaké negativní dopady mohou mít tresty a nevhodné používání odměn. Při výchově přednostně hledá společně s členem oddílu možnosti nápravy a pracuje s přirozenými důsledky jednání. Vede členy k přijímání chyby jako příležitosti k vlastnímu rozvoji.'),
(0xad1a8ed8addc4c0ab856f3b40c784e5a, 3, 'Skautská výchovná metoda', 'Rozumí jednotlivým prvkům skautské výchovné metody i metodě jako celku, rozpoznává ji v činnosti oddílu a přijímá ji. V oddílové činnosti uplatňuje prvky skautské výchovné metody k dosahování výchovných cílů. Rozumí významu oddílové rady pro fungující družinový systém.'),
(0xb6e5c859fb2b45f19df5698540404dee, 28, 'Poskytování zpětné vazby', 'Vhodnou formou poskytuje zpětnou vazbu. Zná její náležitosti, aby byla přínosná a aby neuškodila. Se zpětnou vazbou pracuje jako s rozvojovým prostředkem.'),
(0xd8328e63c7724dcab30f3ffe13d85d31, 29, 'Rozvoj skupiny', 'Připravované programy přizpůsobuje cílovým skupinám (např. různým věkovým kategoriím). Ví, jaká jsou specifika práce s jednotlivými věkovými kategoriemi, a respektuje to při přípravě programu. Uvědomuje si odlišný styl práce v koedukovaném či věkově smíšeném kolektivu. Ví, že volbou a zadáním programů může působit na podobu vztahů mezi členy oddílu. Umí se zapojit do her a jiných aktivit se svými svěřenci. Dokáže vhodně uzpůsobit program účastníkům se speciálními potřebami.'),
(0xdade615e521f4aaaa49d297af167532e, 25, 'Přijímání zpětné vazby', 'Umí přijímat zpětnou vazbu, včetně konstruktivní kritiky. Dle potřeby o zpětnou vazbu žádá nebo ji aktivně získává. Chápe, kdy je cílem kritiky poukázat na prostor ke zlepšení, umí posoudit oprávněnost vznesených námětů, přijmout je a vzít si z nich to nejlepší.'),
(0xe1265fffa47b4ca69c645b148f8a9b1b, 4, 'Skautské dovednosti', 'Ovládá skautskou praxi a tábornické dovednosti umožňující skautskou činnost v přírodě (například bezpečnou manipulaci s nožem, sekyrou a pilou, rozdělání ohně a uvaření jídla na něm, postavení stanu). Učí se rozumět přírodě a vede k tomu členy oddílu.'),
(0xe391c7e06f5c491fbf2db36430585428, 34, 'Verbální a neverbální projev', 'Srozumitelně (jednoduše) sděluje informace. V diskusi vyjadřuje své názory a dává prostor k vyjádření ostatním.'),
(0xe741b43ba4e441dbba00366f0f394348, 10, 'Jednání v souladu s právem', 'Ví, za jakých podmínek může sám právně jednat za své středisko (a kdy tak činí) a kdy už má právně jednat někdo jiný. Rozumí pojmům fyzická osoba, právnická osoba a statutární orgán (v kontextu Junáka – českého skauta).'),
(0xeed873a22fbe4d6580e86a05cebc9f10, 6, 'Metodické nástroje Junáka – českého skauta', 'Umí pracovat s výchovnými nástroji využívanými v Junáku – českém skautu (zejména se stezkami a odborkami, dále s časopisy, závody, online nástroji...) a rozumí jejich přínosu. Používá-li jeho oddíl vlastní výchovné nástroje, umí pojmenovat jejich výhody a nedostatky. Ví, že existují skautské metodické příručky a weby a kde je najít.'),
(0xf68a54c6c9454d17984cbb1982eccce5, 5, 'Junák – český skaut jako organizace', 'Zná základní práva a povinnosti členů a činovníků Junáka – českého skauta a principy jeho řízení dle Stanov. Ví, že existují další vnitřní předpisy a kde je najde. Zná svoje středisko – jeho název, sídlo, oddíly střediska, nejdůležitější činovníky v něm. Ví, jak by středisko mělo být řízeno a jak by mělo fungovat. Ví, jaké je postavení střediska v organizační struktuře Junáka – českého skauta. Ví, jak se může zapojit do fungování střediska a organizace.');

-- --------------------------------------------------------

--
-- Table structure for table `competences_for_lessons`
--

CREATE TABLE IF NOT EXISTS `competences_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `competence_id` binary(16) NOT NULL,
  KEY `lesson_id` (`lesson_id`) USING BTREE,
  KEY `competence_id` (`competence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `competences_for_lessons`
--

TRUNCATE TABLE `competences_for_lessons`;
-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `description` text COLLATE utf8mb4_czech_ci NOT NULL,
  `image` binary(16) NOT NULL,
  `icon` binary(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `fields`
--

TRUNCATE TABLE `fields`;
--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `name`, `description`, `image`) VALUES
(0x12e594cedd3548e9befba440e0403762, 'Hospodaření', '', 0x00000000000000000000000000000000),
(0x1db1540ff8294b449c013cb9ecd56fa1, 'Zdravověda a bezpečnost', '', 0x00000000000000000000000000000000),
(0x26399db899e84e909309ccd2937d3544, 'Právo', '', 0x00000000000000000000000000000000),
(0x29d7c37f9d674e2086d74ae8846fb931, 'Organizace', '', 0x00000000000000000000000000000000),
(0x2e5bf7bba48f497f881c230ccb7fce56, 'Osobnost čekatele', '', 0x00000000000000000000000000000000),
(0x4919e4b8da7140da9d0f2e96261a0e42, 'Příprava programu, metodika skautské výchovy', '', 0x00000000000000000000000000000000),
(0x834e889c03664959a6f674707a33c1ce, 'Skauting', '', 0x00000000000000000000000000000000),
(0xdd8ac1b9b22241788715e826167cdfce, 'Pedagogika, psychologie a komunikace', '', 0x00000000000000000000000000000000);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `groups`
--

TRUNCATE TABLE `groups`;
--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(0x00000000000000000000000000000000, 'Veřejné');

-- --------------------------------------------------------

--
-- Table structure for table `groups_for_lessons`
--

CREATE TABLE IF NOT EXISTS `groups_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `group_id` binary(16) NOT NULL,
  KEY `lesson_id` (`lesson_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `groups_for_lessons`
--

TRUNCATE TABLE `groups_for_lessons`;
-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` binary(16) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `images`
--

TRUNCATE TABLE `images`;
-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE IF NOT EXISTS `lessons` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `version` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `body` text COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `lessons`
--

TRUNCATE TABLE `lessons`;
-- --------------------------------------------------------

--
-- Table structure for table `lessons_in_fields`
--

CREATE TABLE IF NOT EXISTS `lessons_in_fields` (
  `field_id` binary(16) NOT NULL,
  `lesson_id` binary(16) NOT NULL,
  UNIQUE KEY `lesson_id` (`lesson_id`) USING BTREE,
  KEY `field_id` (`field_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `lessons_in_fields`
--

TRUNCATE TABLE `lessons_in_fields`;
-- --------------------------------------------------------

--
-- Table structure for table `lesson_history`
--

CREATE TABLE IF NOT EXISTS `lesson_history` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `version` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `body` text COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `lesson_history`
--

TRUNCATE TABLE `lesson_history`;
-- --------------------------------------------------------

--
-- Table structure for table `mutexes`
--

CREATE TABLE IF NOT EXISTS `mutexes` (
  `id` binary(16) NOT NULL,
  `timeout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `holder` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `mutexes`
--

TRUNCATE TABLE `mutexes`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `role` enum('user','editor','administrator','superuser') COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
-- --------------------------------------------------------

--
-- Table structure for table `users_in_groups`
--

CREATE TABLE IF NOT EXISTS `users_in_groups` (
  `user_id` int UNSIGNED NOT NULL,
  `group_id` binary(16) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Truncate table before insert `users_in_groups`
--

TRUNCATE TABLE `users_in_groups`;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons` ADD FULLTEXT KEY `body` (`body`);

--
-- Indexes for table `lesson_history`
--
ALTER TABLE `lesson_history` ADD FULLTEXT KEY `body` (`body`);
COMMIT;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
