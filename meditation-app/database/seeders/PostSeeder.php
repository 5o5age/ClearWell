<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@clearwell.test')->first();

        if (! $admin) {
            return;
        }

        DB::table('taggables')->where('taggable_type', Post::class)->delete();
        Post::query()->delete();

        $tagIds = Tag::pluck('id', 'slug');

        $articles = [
            [
                'title' => 'Apzinātība iesācējiem: ar ko sākt',
                'tags'  => ['meditacija', 'iesacejiem'],
                'body'  => <<<'TXT'
Apzinātība ir prasme pievērst uzmanību tagadnes mirklim ar nodomu un bez vērtējuma. Tā izklausās vienkārši — un patiesībā tā arī ir. Tomēr, kā daudzas vienkāršas lietas, tā prasa pacietību un atkārtošanu.

Ja sāc no nulles, sāc ar piecām minūtēm dienā. Apsēdies ērti tādā vietā, kur tevi netraucēs. Aizver acis vai ļauj skatienam mīksti nokrist uz grīdas. Pievērs uzmanību elpas fiziskajām sajūtām — gaisam pie nāsīm, krūškurvja kustībai, plecu vieglajai krišanai izelpā.

Dažās sekundēs prāts aizklīdīs prom. Tā nav neveiksme. Tas ir viss vingrinājums. Brīdis, kad pamani, ka esi aizklīdis domās, ir prakses brīdis. Tu maigi atzīsti "domāšana" un atgriezies pie elpas. Piecās minūtēs to vari izdarīt simts reižu. Tas ir simts mazi prāta muskuļa atkārtojumi.

Pēc nedēļām pamanīsi mazas izmaiņas: nedaudz garāku pauzi pirms reakcijas, mīkstāku malu stresa pilnā pēcpusdienā, gatavību sajust kaut ko neērtu, nevis bēgt no tā. Šīs pārmaiņas ir klusas. Ja gaidi salūtu, tu tās palaidīsi garām.

Sāc maz. Esi pacietīgs. Tici, ka parādīšanās ir svarīgāka par to, kā konkrētā sesija jutās.
TXT,
            ],
            [
                'title' => 'Elpošanas tehnika 4-7-8: ātrs ceļš uz mieru',
                'tags'  => ['elposana', 'stress'],
                'body'  => <<<'TXT'
Elpošanas tehnika 4-7-8 ir viens no vienkāršākajiem un ātrākajiem veidiem, kā nomierināt nervu sistēmu. To izstrādāja ārsts Endrjū Veils, balstoties uz seno jogas pranajāmas praksi.

Princips ir vienkāršs: tu ieelpo četras sekundes, aiztur elpu septiņas sekundes un izelpo astoņas sekundes.

Tehnika darbojas tāpēc, ka ilgāka izelpa nekā ieelpa stimulē klejotājnervu, kas aktivizē parasimpātisko nervu sistēmu — to daļu, kas atbild par mieru un atveseļošanos. Sirds sitieni palēninās, muskuļi atslābst, asinsspiediens pazeminās.

Atrodi ērtu pozu, vēlams sēdus ar muguru taisni. Pilnībā izelpo caur muti. Aizver muti un ieelpo caur degunu, skaitot līdz četri. Aiztur elpu, skaitot līdz septiņi. Izelpo caur muti ar nelielu šņākšanas skaņu, skaitot līdz astoņi. Tas ir viens cikls.

Sākumā veic ne vairāk kā četrus ciklus. Tehnikas spēks slēpjas regularitātē, nevis ilgumā. Praktizē divreiz dienā — no rīta tūlīt pēc pamošanās un vakarā pirms gulētiešanas.

Pēc dažām nedēļām, kad ķermenis būs iemācījies šo ritmu, tehnika kļūs par uzticamu rīku stresa brīžos — pirms svarīgas sarunas, satiksmes sastrēgumā vai brīžos, kad domas sāk skriet pārāk ātri.
TXT,
            ],
            [
                'title' => 'Vakara rituāls labākam miegam',
                'tags'  => ['miegs', 'elposana'],
                'body'  => <<<'TXT'
Bezmiegs reti ir par nogurumu. Tas gandrīz vienmēr ir par aktivizētu nervu sistēmu, kas sastopas ar klusu istabu. Dienas darbs ir beidzies, uzmanības novērsēji atkrituši, un sešpadsmit iepriekšējo stundu neapstrādātais stress beidzot tiek pie vārda.

Risinājums nav garāks atvaļinājums. Risinājums ir vienkāršs vakara rituāls, kas palīdz ķermenim noregulēties pirms miega.

Stundu pirms gulētiešanas izslēdz spilgto gaismu. Ekrāni un griestu lampas signalizē smadzenēm, ka vēl ir diena. Aizdedz mazu galda lampu vai sveci. Šis vienkāršais žests pasaka nervu sistēmai, ka ir laiks palēnināt tempu.

Piecpadsmit minūtes klusumā — sēdus vai guļus. Ja iespējams, izvēlies ķermeņa skenēšanu vai lēnu elpošanas praksi ar pagarinātu izelpu. Mērķis nav aizmigt; mērķis ir ļaut ķermenim ierasties tur, kur tas patiesībā jau atrodas.

Atstāj telefonu citā istabā. Pati klātbūtne uz naktsgaldiņa ir signāls, ka esi joprojām pieejams pasaulei. Miegs sākas brīdī, kad tu pārstāj būt pieejams.

Pēc dažām nedēļām šis rituāls kļūst automātisks. Ķermenis atpazīst žestu secību un sāk atbrīvoties pat pirms tu apsēdies. Tas ir nosacīta refleksa spēks — un viens no klusajiem ieguvumiem no apzinātas prakses.
TXT,
            ],
            [
                'title' => 'Pastaiga mežā kā meditācija',
                'tags'  => ['daba', 'meditacija'],
                'body'  => <<<'TXT'
Japānā ir vārds, kas latviski tieši nepārtulkojas — shinrin-yoku, "meža pirts". Tā ir prakse uzturēties starp kokiem ar visām maņām atvērtām. Pētījumi rāda mērāmu kortizola pazemināšanos, asinsspiediena samazināšanos un imūnsistēmas uzlabošanos jau pēc divu stundu lēnas pastaigas mežā.

Šī nav pastaiga sporta dēļ. Tā nav iešana, lai kaut kur nokļūtu. Tā ir iešana, lai vienkārši būtu mežā.

Atrodi mierīgu meža taku. Atstāj telefonu kabatā uz lidmašīnas režīma vai vispār mājās. Sāc iet daudz lēnāk, nekā tas šķiet dabiski — divreiz lēnāk par parasto soli. Ļauj uzmanībai krist uz pēdām, kas saskaras ar zemi. Pamani, kā gaiss ož citādi nekā istabā. Klausies, ko dzird mežs aiz tava soļa skaņas.

Apstājies reizi piecās minūtēs. Pacel skatienu uz koka galotnēm. Pieskaries kokam ar plaukstu un pamani tā kreves tekstūru. Nepainteresējies, kas tas par koku — vienkārši pamani, kāds tas ir.

Pēc stundas šādas pastaigas tu atgriezīsies kā kaut kas no nelielas atvaļinājuma. Pēc gada šādu pastaigu reizi nedēļā tu pamanīsi, ka pieturi pastaigas ritmu arī ikdienā — ka kaut kas iekšā ir kļuvis lēnāks un vērīgāks.

Mežs nedara neko īpašu. Tas vienkārši ļauj tev būt tādam, kāds tu esi, bez izpildes mērķiem. Tam pietiek.
TXT,
            ],
            [
                'title' => 'Stress darbā: piecas mikropauzes dienā',
                'tags'  => ['stress'],
                'body'  => <<<'TXT'
Stress darbā reti ir tikai par konkrēto uzdevumu. Tas ir par to, ka nervu sistēma nav radīta nepārtrauktai modrībai — un mūsdienu darba diena tieši to no mums prasa.

Pa dienu uzkrātais stress nekur nepazūd. Tas paliek ķermenī kā saspringums plecos, kā paātrināta elpa, kā nelielas reakcijas, ko pat nepamanām. Vakarā šo svaru ienesam mājās un brīnāmies, kāpēc nespējam atslābt.

Risinājums nav lielāks vakara atslābums. Risinājums ir mazas pauzes dienas laikā, kas neļauj stresam pieaugt slāņos.

Reizi stundā veic divu minūšu mikropauzi. Aizver darba e-pastu. Piecelies no krēsla. Veic piecas lēnas elpas — četras sekundes ieelpā, sešas sekundes izelpā. Iztaisno muguru. Paskaties uz kaut ko tālāku par ekrānu — kokā, debesīs, mājā pretī. Ļauj acīm atpūsties.

Šī pauze neatrisinās tavu darba slodzi. Bet tā neļaus stresam uzkrāties. Cilvēks, kas dienā paņem piecas mikropauzes, pārvalda darbu pavisam citādi nekā cilvēks, kas strādā astoņas stundas pēc kārtas bez apstāšanās.

Otrs solis ir vēl vienkāršāks. Pēc darba dienas beigām pavadi piecas minūtes klusumā, pirms atver telefonu vai sāc ģimenes vakaru. Šis īsais buferis ļauj apzināti pārslēgties no darba lomas uz mājas lomu, nevis ienest tajā visu dienas saspringumu plecos.

Stress ir neizbēgams. Bet veids, kā ar to attiecamies, ir prasme — un kā jebkura prasme, to var trenēt.
TXT,
            ],
            [
                'title' => 'Ķermeņa skenēšana: meditācija bez piepūles',
                'tags'  => ['meditacija', 'iesacejiem'],
                'body'  => <<<'TXT'
Ķermeņa skenēšana ir vadīta meditācija, kurā uzmanība lēni pārvietojas pa ķermeni daļu pa daļai, pamanot to, kas tur ir. Tā ir mānīgi spēcīga un pārsteidzoši grūta.

Apgulies, ja vari, vai apsēdies atbalstītā krēslā. Sāc no pēdu apakšām. Bez kustības novieto uzmanību tur un uzdodi vienkāršu jautājumu: kā tas patiesībā jūtas? Siltums? Aukstums? Spiediens? Tirpoņa? Arī "es nesajūtu" ir atbilde. Tā ir "es nezinu, kā to nosaukt."

Virzies lēni augšup — potītes, ikri, ceļi, augšstilbi. Necenties atslābt ķermeni. Necenties neko mainīt. Tavs vienīgais uzdevums ir sajust precīzi to, kas jau ir.

Lielāko daļu dienas mēs dzīvojam no kakla uz augšu, izturoties pret ķermeni kā pret transporta sistēmu prātam. Ķermeņa skenēšana to apgriež otrādi. Tā atgriež tevi pie dzīva, jūtoša organisma, kas tu patiesībā esi.

Daudzi pamana pretestību — garlaicību, nemieru, vēlmi ātri pāriet tālāk. Tā ir informācija. Vietas, kuras negribam sajust, parasti nes lielāko neapzināto saspringumu. Nav jāforsē. Vienkārši pamani un ļauj uzmanībai palikt tur mirkli ilgāk, nekā jūtas ērti.

Sāc ar piecpadsmit minūtēm. Ar laiku šī prakse kļūst par vienu no uzticamākajiem veidiem, kā nolaisties uz zemes pēc smagas dienas — ne ar disciplīnu, bet ar maigu uzmanību.
TXT,
            ],
            [
                'title' => 'Mīlošā laipnība: pretinde paškritikai',
                'tags'  => ['meditacija'],
                'body'  => <<<'TXT'
Mīlošās laipnības meditācija, pāli valodā saukta par metta, ir prakse apzināti radīt labvēlību — vispirms sev, tad tuviem cilvēkiem, tad neitrāliem, tad kādam grūtam un beigās visām dzīvajām būtnēm.

Tradicionālās frāzes ir vienkāršas. "Lai es esmu drošībā. Lai es esmu laimīgs. Lai es esmu vesels. Lai es dzīvoju ar vieglumu." Tu tās klusi atkārto, lēni, ļaujot tām nogulsnēties. Tad iedomājies kādu, ko mīlēt nāk viegli — bērnu, tuvu draugu, mājdzīvnieku — un atkārto frāzes viņam. "Lai tu esi drošībā. Lai tu esi laimīgs..."

Iesācējus visvairāk pārsteidz, cik grūta ir pirmā kategorija. Piedāvāt sev labus vēlējumus bez nosacījumiem, bez atrunām, bez ierastās iekšējās balss, kas saka "jā, bet vispirms tev jā..." — tas var justies dīvaini, pat nepatiesi.

Turpini tomēr. Prakse nav atkarīga no tā, vai siltumu jūti uzreiz. Tā ir trenēšanās. Pēc nedēļām kaut kas patiešām maina toni. Ierastā paškritiskā balss zaudē daļu sava monopola. Tu sāc izturēties pret sevi mazos brīžos ar to pašu pamata cieņu, ko izrādītu svešiniekam autobusa pieturā.

Tā nav pašlološanās. Izrādās, ka cilvēki, kas izturas pret sevi ar laipnību, vairāk var piedāvāt arī citiem. Akmens, kas pats ir izkaltis, nedod ūdeni avotam.
TXT,
            ],
            [
                'title' => 'Pateicības prakse: vienkāršs rituāls labākai dzīvei',
                'tags'  => ['meditacija'],
                'body'  => <<<'TXT'
Pateicība ir kļuvusi par labsajūtas industrijas klišeju, kas ir žēl, jo pamata prakse patiesi ir viens no spēcīgākajiem rīkiem garīgajai veselībai.

Cilvēka smadzenes pēc savas dabas pievērš lielāku uzmanību negatīvajam nekā pozitīvajam. Mūsu senči, kas atcerējās, kuras ogas bija indīgas, dzīvoja ilgāk nekā tie, kas atcerējās, kuras garšoja labi. Mēs esam šo bažīgo cilvēku pēcteči. Tas ir noderīgi, kad uz spēles ir izdzīvošana, un mazāk noderīgi, kad uz spēles ir tas, vai mums patika otrdiena.

Pateicības prakse ir tīšs labojums šai dabiskajai aizspriedumu sistēmai. Tā neignorē negatīvo. Tā vienkārši pievieno datus, ko prāts neapzināti nepamana.

Katru vakaru pirms gulētiešanas pieraksti trīs konkrētas lietas, par kurām esi bijis pateicīgs šodien. Ne "ģimene", bet "veids, kā meita smējās par savu joku vakariņās." Ne "veselība", bet "pastaiga uz veikalu bez sāpēm celī." Konkrētība ir svarīga.

Vispārīgas pateicības pārvēršas par formulu, ko prāts apiet automātiski. Konkrētas pateicības liek faktiski izstaigāt dienas pieredzi un pamanīt mirkļus, kas citādi paliktu nepamanīti.

Pēc mēneša šī prakse neatrisinās problēmas. Tā mainīs attiecību starp to, ko pamani, un to, ko aizmirsti. Tā pati nedēļa, dzīvota ar un bez prakses, ir atšķirīga nedēļa.
TXT,
            ],
        ];

        foreach ($articles as $article) {
            $post = Post::create([
                'user_id' => $admin->id,
                'title'   => $article['title'],
                'body'    => $article['body'],
            ]);

            $ids = collect($article['tags'])
                ->map(fn (string $slug) => $tagIds[$slug] ?? null)
                ->filter()
                ->values()
                ->all();

            $post->tags()->sync($ids);
        }
    }
}
