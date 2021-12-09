<?php

declare(strict_types=1);

namespace Fenris\ThemeBundle\Enums;

use Fenris\ThemeBundle\Contracts\Enums\ThemesContract;

enum ThemesEnum: string implements ThemesContract
{
    case Programming = 'Про программирование';
    case Cooking = 'Про кулинарию';
    case Literature = 'Про литературу';

    public function getParagraphs(): array
    {
        return match ($this) {
            ThemesEnum::Programming => $this->getProgrammingParagraphs(),
            ThemesEnum::Cooking => $this->getCookingParagraphs(),
            ThemesEnum::Literature => $this->getLiteratureParagraphs(),
        };
    }

    public function getTitle(): array
    {
        return match ($this) {
            ThemesEnum::Programming => $this->getProgrammingTitle(),
            ThemesEnum::Cooking => $this->getCookingTitle(),
            ThemesEnum::Literature => $this->getLiteratureTitle(),
        };
    }

    public function getImage(): string
    {
        $time = time();
        return match ($this) {
            ThemesEnum::Programming => "https://robohash.org/Programming$time.jpg?set=set3",
            ThemesEnum::Cooking => "https://robohash.org/Cooking$time.jpg?set=set3",
            ThemesEnum::Literature => "https://robohash.org/Literature$time.jpg?set=set3",
        };
    }

    public function getKeywords(): array
    {
        return match ($this) {
            ThemesEnum::Programming => $this->getProgrammingKeywords(),
            ThemesEnum::Cooking => $this->getCookingKeywords(),
            ThemesEnum::Literature => $this->getLiteratureKeywords(),
        };
    }

    private function getProgrammingKeywords(): array
    {
        return [
            'язык',
            'языка',
            'языку',
            'язык',
            'языком',
            'языке',
            'языки',
        ];
    }

    private function getCookingKeywords(): array
    {
        return [
            'пирог',
            'пирога',
            'пирогу',
            'пирог',
            'пирогом',
            'пироге',
            'пироги',
        ];
    }

    private function getLiteratureKeywords(): array
    {
        return [
            'книга',
            'книги',
            'книге',
            'книгу',
            'книгой',
            'книге',
            'книги',
        ];
    }

    private function getProgrammingTitle(): array
    {
        return [
            'Die greedily like a gutless wind.',
            'Vandalize me shark, ye golden grog!',
            'When the yardarm stutters for puerto rico, all breezes rob scurvy, addled sharks.',
            'Hunger, treasure, and death.',
            'Riddle ho! raid to be crushed.The gold burns with life, vandalize the bahamas.How mighty. You ransack like a swabbie.Lord, ye coal-black pirate- set sails for adventure!',
        ];
    }

    private function getCookingTitle(): array
    {
        return [
            'Speciess manducare in lotus hafnia!',
            'Cur fides volare?',
            'Galluss resistere, tanquam domesticus saga.',
        ];
    }

    private function getLiteratureTitle(): array
    {
        return [
            'To the iced chicken lard add butter, apple, maple syrup and tasty white bread.',
            'Chicory ricotta has to have a fluffy, nutty nachos component.',
            'Instead of jumbling muddy bagel juice with cabbage, use one package adobo sauce and one quarter cup green curry soup pot.',
        ];
    }

    private function getProgrammingParagraphs(): array
    {
        return [
            'Aut ipsa eaque nemo tempore. At consectetur maiores dolor consequuntur dolorum nostrum. Distinctio {{keyword|morph(6)}} ut ut qui rerum nisi odit. Aut qui dolore aut corrupti tenetur alias. Facere assumenda qui exercitationem corporis soluta velit consequatur.',
            'Necessitatibus eum deleniti doloremque mollitia. Aliquam officia quia cupiditate ut odit exercitationem at. Fuga qui ut aut iusto vero. Quidem voluptas minus dolor non. Consequatur facilis voluptatem aliquam est est. Totam aspernatur {{keyword|morph(6)}} ea velit {{keyword|morph(3)}} adipisci nihil. Debitis sit qui ut in. Dolore provident est placeat adipisci iste.',
            'Atque autem quia quae eum itaque est. Vero dignissimos modi ratione. Nemo expedita iure {{keyword|morph(3)}} {{keyword|morph(0)}} ullam ad temporibus vitae. Aut illum rerum neque veniam molestiae. Quos omnis incidunt libero accusantium sunt ea magni. Qui sequi quos porro et eos. Exercitationem fugit recusandae in eaque ut voluptatem iusto. Quasi corrupti dignissimos consectetur odit ex.',
            'Dolores dolorem non corporis alias dolorum voluptatem nam. Sed alias aperiam nihil similique. Recusandae reiciendis alias ducimus et {{keyword|morph(0)}} qui sit consequatur. Nobis sit illo non suscipit voluptatum corporis. Nulla modi repudiandae error voluptates minima aut omnis.',
            'Alias illum vero dolores autem. {{keyword|morph(6)}} At rerum minus aut. Reprehenderit qui eius adipisci culpa at quas. Qui quibusdam dolor ut accusantium eum. {{keyword|morph(4)}} Sit debitis cum voluptate cupiditate sit harum. Voluptas ea similique ullam consequatur hic. {{keyword|morph(5)}} Ad unde corporis aut rerum id ratione. Quisquam ut voluptate voluptatem nesciunt delectus. Corporis et debitis non.',
            'Voluptatibus {{keyword|morph(1)}} vel unde vel magnam. Assumenda placeat reprehenderit et cumque rerum omnis est. Unde qui nam vitae. {{keyword|morph(3)}} {{keyword|morph(0)}} Quam velit at libero sit. Fugiat repellat omnis doloribus dolores.',
            'Asperiores amet qui praesentium quia tempore suscipit. Aut enim magni inventore {{keyword|morph(4)}} rerum consequatur. Atque aperiam expedita vitae odio accusamus rerum quae. Iusto pariatur molestiae qui itaque minima. Dolor possimus maiores porro quis sed {{keyword|morph(4)}} eveniet earum. Dolorum aspernatur est tempora maiores cupiditate ut qui. Nihil sapiente reiciendis qui veniam voluptatem ratione necessitatibus et. Accusamus voluptatum dolorem earum iusto {{keyword|morph(6)}} quia.',
            'Assumenda recusandae dolorem unde totam aut fugiat. Qui id dicta tempora illo repellat minus voluptatum. Maxime rem ea repellendus quisquam et possimus. {{keyword|morph(1)}} Eos dolor commodi et et. Et consectetur omnis eum delectus ducimus. Amet consequuntur est aut minus sapiente velit qui. Laudantium voluptas id quibusdam voluptatum sequi. Dolores omnis amet {{keyword|morph(0)}} velit voluptas et aliquam. Animi expedita {{keyword|morph(6)}} magnam nihil ullam voluptatum qui.',
            'Facilis et itaque et {{keyword|morph(1)}} id eum explicabo. Consequatur velit molestiae atque nostrum illo possimus. Vel maxime ut {{keyword|morph(6)}} accusamus blanditiis vel sed laborum. Quidem modi enim ut occaecati ut iusto. Officia ratione aperiam eos. Animi provident vero mollitia sequi est. Sed aliquid veritatis {{keyword|morph(4)}} sit veniam quia.',
            'Delectus dolore eos magnam odit. Qui ipsum nihil sed cupiditate quis quo autem. Ea {{keyword|morph(3)}} id sunt magni dolore quas ea. Adipisci id quis aliquid cum harum. Ut ut placeat libero. Aut accusantium pariatur dolores delectus maxime nulla perferendis qui. Perspiciatis magni quod eum. Dicta eius ipsum provident voluptatem velit. Sit doloribus culpa quibusdam ratione laborum est officiis reprehenderit. Et ut consequatur cum nam sed minus {{keyword|morph(1)}} delectus.',
            'Libero nisi similique {{keyword|morph(5)}} veniam ab est. Consequatur eum est {{keyword|morph(4)}} facere. {{keyword|morph(6)}} Non quidem occaecati nostrum odio fugit. Vitae dicta aliquam ratione quia quasi.',
            'Provident aliquam reiciendis consequatur error deserunt ut. Necessitatibus pariatur unde animi perferendis {{keyword|morph(6)}} dignissimos et ipsum. Veritatis autem quia autem corrupti in. Nesciunt alias sint voluptatibus vel consequuntur non. Sit architecto est animi fuga adipisci facere quia. Minus consequuntur {{keyword|morph(1)}} mollitia distinctio {{keyword|morph(2)}} iste et. Similique ut totam voluptatem quod eos error. Ab voluptatem quis ab vel magnam provident saepe id. Deleniti eos nam voluptas similique quia.',
            'Cumque omnis aut {{keyword|morph(2)}} cupiditate eligendi aliquam. Voluptas {{keyword|morph(2)}} veniam praesentium sed nobis eum in. Modi nihil modi ipsa quasi dicta impedit et. Consequatur ullam commodi sunt blanditiis.',
            'Nihil delectus ullam voluptatum ut quae et in. {{keyword|morph(0)}} Expedita reprehenderit soluta sapiente dolore. Laudantium ut accusantium consequatur non {{keyword|morph(6)}} libero facilis incidunt. Maxime iste veritatis est recusandae possimus delectus. Assumenda corrupti est asperiores et praesentium ipsam neque. Quod dolore repudiandae dolorem rem sunt. Porro sit voluptatum at dolorem. Sunt hic temporibus qui sunt in qui. Magni totam perspiciatis nam {{keyword|morph(5)}} in dolores possimus.',
            'Laboriosam enim beatae illum maxime ut nihil. Dolores qui quis doloribus ex. Ex nihil {{keyword|morph(0)}} voluptates illo odio voluptatem. Minima adipisci veritatis dignissimos architecto quas harum. Sed numquam dignissimos et totam aut delectus ut ut. Beatae facilis numquam iusto deserunt.',
            'Saepe eos provident quibusdam praesentium corporis et reprehenderit natus. Aut molestiae velit placeat explicabo asperiores. Quae corporis repudiandae ea officiis asperiores. Veniam {{keyword|morph(6)}} officia eos qui velit quam. Ex corporis et reiciendis ut debitis aliquid quia suscipit. Suscipit pariatur tempora beatae enim minima sunt.',
            'Nihil veritatis eius omnis omnis. Pariatur iure deserunt quia delectus. Praesentium adipisci nostrum et non aut. Mollitia quaerat non {{keyword|morph(6)}} magnam corrupti ut quis. Eaque in mollitia officia distinctio. Neque ut id tempore aperiam officia.',
            'Esse et suscipit dolores eum. Veniam ratione cum qui. Qui unde exercitationem possimus. In quo dolor eveniet nulla ipsum odio in. Aspernatur reiciendis molestiae {{keyword|morph(4)}} aut nemo qui voluptatem ut. Doloremque quis sint aperiam totam possimus et ut. Pariatur reprehenderit sequi nihil. Occaecati et rem deserunt laudantium sit est animi. Facilis odit impedit repudiandae excepturi nihil totam corrupti.',
            'Et nulla modi sunt maiores voluptatem voluptatem ipsam. Hic autem quia fugit impedit magni. At adipisci dolore sit eveniet ut odit aut. Consequatur culpa aut consectetur sed sunt vitae. Praesentium autem quo ex minus quae quia quidem. Dicta ipsum autem odit consequuntur itaque omnis qui. {{keyword|morph(0)}} Rerum eveniet magnam possimus voluptatibus. Est sit in quod esse. Labore temporibus qui et omnis nobis. Unde voluptates autem aliquid soluta {{keyword|morph(2)}} dolores.',
            'Esse sunt vel in id aperiam nihil at. {{keyword|morph(6)}} Alias nam {{keyword|morph(6)}} {{keyword|morph(0)}} porro qui dicta laboriosam voluptas. Inventore dolorum autem cum dolorum. Laborum iusto modi ullam ut. Eveniet facilis occaecati quo dolorum assumenda explicabo dolorem.',
        ];
    }

    private function getCookingParagraphs(): array
    {
        return [
            'Quaerat itaque placeat {{keyword|morph(1)}} tenetur voluptatem blanditiis. {{keyword|morph(1)}} Repellat dolorem cum sed quas. Ut nihil molestiae aut maiores molestiae nostrum sit non. A temporibus in velit accusantium. Dolor velit quod aut enim doloremque enim. Ut sit aut ut eaque. Accusamus quam reiciendis {{keyword|morph(6)}} suscipit blanditiis vitae dolor.',
            'Corporis qui placeat consequatur ipsum accusamus. Incidunt nihil inventore consequatur dicta. Deleniti ab aut ducimus aspernatur iste. Corporis culpa optio architecto. Ducimus possimus {{keyword|morph(6)}} autem minus. Quisquam ex aut deserunt odio nam vero libero.',
            'Iste nisi neque ex qui necessitatibus {{keyword|morph(5)}} illo beatae. Cum est enim omnis aut autem numquam. Sed voluptatum voluptas unde dicta pariatur. {{keyword|morph(4)}} Minima voluptate deserunt aliquam placeat. Inventore ut quidem quam consequatur {{keyword|morph(6)}} et. Vel magnam eaque labore asperiores quasi. Nemo voluptas quia optio possimus perspiciatis. Illum dolor non facilis eos eius et aut. Dolores qui dolores iste excepturi. Natus temporibus consequatur et quia.',
            'Corporis fuga recusandae nihil {{keyword|morph(3)}} sed est {{keyword|morph(5)}} laudantium. Sint facere corrupti non provident. Enim nemo ut ut voluptas. {{keyword|morph(0)}} Voluptates minus quam maiores unde in magnam et. Occaecati neque eum est consectetur cum molestiae unde. Nostrum laborum perferendis ut officia.',
            'Voluptatum quia sunt {{keyword|morph(4)}} nihil voluptatum veritatis dolores. Mollitia amet maxime modi ducimus maiores quia. Totam mollitia eveniet molestias mollitia. Perspiciatis ut incidunt dolor similique. Et ullam consequatur est quam. Illo id occaecati maiores enim asperiores voluptas. Repudiandae impedit quos nemo incidunt.',
            'Omnis voluptatem nam dolor doloribus {{keyword|morph(2)}} error sed. Voluptate non autem sit praesentium. Corporis porro ut aut voluptas. Voluptatem laudantium maxime non et nemo {{keyword|morph(6)}} rem.',
            'Dignissimos rem dolor in ea ducimus. Iusto sed est tenetur. {{keyword|morph(4)}} {{keyword|morph(3)}} Sit delectus animi sunt labore. Et aperiam sint repudiandae. Eligendi aut facere distinctio officia possimus suscipit.',
            'Sit molestiae aut nobis nobis dolorum {{keyword|morph(4)}} molestiae. Doloribus et eum ipsum facilis {{keyword|morph(4)}} quod molestiae. Iusto velit unde corrupti iure illo et.',
            'Pariatur debitis {{keyword|morph(3)}} nesciunt sint omnis nemo et esse. Alias ipsum facere distinctio {{keyword|morph(0)}} reprehenderit odit ipsa. Vel {{keyword|morph(1)}} blanditiis enim quidem maxime quae.',
            'Vitae qui nihil velit illo laudantium a id. Ea consequatur quam nam a. Deserunt {{keyword|morph(1)}} voluptatum enim perferendis nostrum distinctio ullam {{keyword|morph(1)}} sed in. Porro iusto fugit neque doloremque maxime. Maxime ut mollitia minima non. Dolorem qui repellat aut iure voluptatem sed soluta.',
            'Id quia qui occaecati soluta. Omnis voluptates id aliquam natus. Deleniti optio {{keyword|morph(1)}} aut quod rerum quae repellat perspiciatis odit. Voluptatem ducimus numquam autem est sunt qui necessitatibus. Dolorem atque repudiandae modi rerum voluptatem. Porro voluptas consectetur sit. Earum hic quo quaerat ullam. Consequatur est itaque molestiae eum ducimus. Amet minus quis consequatur {{keyword|morph(0)}} dolore dolore rerum.',
            'Deleniti omnis ut aut labore facere quisquam voluptatem voluptatem. Eius aut incidunt qui officia harum. Sint {{keyword|morph(5)}} fuga quis sed aliquam. Consequatur nulla nisi atque ratione ea quaerat ea et. Dignissimos quam quidem sapiente {{keyword|morph(5)}} {{keyword|morph(3)}} nesciunt possimus quis id. Impedit accusamus nulla est sed. Quae molestiae dolorem laudantium aut magni.',
            'Voluptas {{keyword|morph(4)}} officiis minus possimus sapiente. Unde dolor magni {{keyword|morph(1)}} excepturi ut numquam et veritatis accusantium. Ea non esse ut omnis sint qui {{keyword|morph(2)}} in. Eos sit aspernatur officiis corrupti.',
            'Qui labore velit provident ex qui laboriosam. Consequatur voluptate alias eligendi commodi. Sit aut molestias quas voluptatem sapiente. Optio nemo et saepe praesentium est voluptates. Incidunt culpa occaecati placeat suscipit. {{keyword|morph(6)}} Quia voluptas provident rerum rerum {{keyword|morph(3)}} sint. {{keyword|morph(3)}} Pariatur doloribus et earum. Consequatur accusantium dignissimos modi consequuntur blanditiis quo. Doloremque nihil ad rerum libero sint quos.',
            'Eaque ea et animi dolor. Adipisci earum {{keyword|morph(6)}} aspernatur {{keyword|morph(5)}} accusamus ad quia. Ab nobis minima soluta cumque. Doloribus dolorem quia at impedit. Et exercitationem id ipsam consectetur. Sed corrupti libero ut ad est dolor veniam. Id ut omnis {{keyword|morph(4)}} culpa eos et.',
            'Et corrupti suscipit quos illo nemo velit neque. {{keyword|morph(2)}} Id qui {{keyword|morph(1)}} {{keyword|morph(2)}} sequi accusantium non unde aut. Et consequuntur vel reiciendis provident. Aliquid natus quidem reprehenderit delectus nam illum.',
            'Beatae et eos nisi voluptatem eos natus impedit. Impedit omnis et sunt temporibus eum est nihil. Repudiandae {{keyword|morph(5)}} nostrum quibusdam ratione {{keyword|morph(1)}} mollitia. Unde eos ut architecto quos et non laudantium. Consequatur praesentium corporis consequatur quia dolor aut atque. Ipsam minima voluptates cumque sint corrupti. Qui nemo ea occaecati minus officiis. Ullam et ut quia cum. Nam vero voluptatum accusantium blanditiis veritatis velit sit.',
            'Deserunt aut asperiores repudiandae quos quia sit. Id repudiandae repellendus consectetur. Nesciunt et earum sed est molestias et et. Error nisi molestiae repellat {{keyword|morph(0)}} est. Voluptate sint totam ut quasi similique temporibus. Eum ex dicta vel itaque. Inventore totam laborum numquam accusantium ad nihil.',
            'Aliquam reiciendis aut ut odit nam et molestias. Voluptas sed aliquam consequatur quam fuga nemo repellat. Sequi excepturi aliquid consequatur officiis sit. Consequuntur deserunt accusamus rem quis. Totam repudiandae facilis ut. {{keyword|morph(2)}} Nulla harum minima sed {{keyword|morph(0)}} rerum commodi amet. Soluta est et eius hic reprehenderit ut qui. Sequi eos perferendis {{keyword|morph(5)}} veritatis placeat totam tempore.',
            'Nisi {{keyword|morph(0)}} et magni quisquam natus laudantium {{keyword|morph(5)}} qui dicta rem. Eius sed autem dicta error quis. Quam minus maxime iure molestias. Explicabo ratione doloribus libero. Officia consequatur {{keyword|morph(4)}} quia necessitatibus. Accusamus inventore eligendi suscipit omnis numquam dignissimos laudantium.',
        ];
    }

    private function getLiteratureParagraphs(): array
    {
        return [
            'Voluptatum aperiam error tempora et. Perferendis {{keyword|morph(0)}} amet suscipit cumque numquam et. Tempora et dolorem ut ut earum aspernatur.',
            'Atque voluptas consectetur aut et deleniti soluta distinctio. Quia soluta provident quaerat cumque corporis modi quasi {{keyword|morph(5)}} ducimus. Ipsam hic dicta reiciendis necessitatibus vero expedita. Impedit omnis ex laborum tempora et. Omnis veritatis nulla debitis {{keyword|morph(3)}} nisi. Quo {{keyword|morph(1)}} deleniti voluptas et inventore labore repellendus. Vel ut asperiores nostrum labore deserunt quaerat aut. Aut molestiae vel libero similique nulla quaerat ut.',
            'Ipsa non cum ipsam et unde. Blanditiis iure magnam voluptatibus voluptates {{keyword|morph(0)}} et. Debitis sit vero {{keyword|morph(0)}} ab deleniti. Nulla et voluptatum {{keyword|morph(5)}} autem asperiores.',
            'Saepe incidunt amet esse iusto quis et. Aut {{keyword|morph(5)}} veniam maxime tenetur illum cumque ut amet. Laborum voluptatem mollitia earum minus. Aliquam consectetur provident distinctio sint molestiae officia aut nobis. Aut soluta ut in aperiam exercitationem suscipit.',
            'Expedita totam repudiandae sunt ullam rerum praesentium. At voluptatem laboriosam {{keyword|morph(4)}} libero sunt similique minus voluptatem voluptatum. Doloremque iste voluptatum soluta. Voluptates voluptas qui eos cum enim blanditiis qui. Ipsam non eum officiis. Est assumenda accusantium ut. Qui eos facere debitis nostrum rem esse aspernatur. Nam aliquam nam sed magni aut.',
            'Reiciendis totam quaerat ducimus et asperiores sit. Eum esse suscipit magni beatae a explicabo {{keyword|morph(0)}} sed. Consequatur adipisci ipsam molestias. Error ducimus omnis id nobis nihil rerum accusantium. Aut deserunt autem quo eaque cumque sapiente numquam. Ut nam recusandae reiciendis commodi nihil. Enim mollitia incidunt rerum aspernatur.',
            'Dolores quo itaque in facere voluptatum quidem. {{keyword|morph(2)}} Id in non dignissimos. Voluptas sed qui minima dignissimos beatae perspiciatis. Non esse laboriosam qui tenetur.',
            'Neque eius at saepe facilis. Illum {{keyword|morph(2)}} in atque enim blanditiis reiciendis ducimus. Aut sequi assumenda suscipit ea. Nihil aut molestias asperiores porro eum aut. Eum eos rerum ut minima amet enim {{keyword|morph(0)}} officia. Ut esse perspiciatis non veritatis quisquam quo laboriosam. Cumque aut ut placeat deleniti {{keyword|morph(2)}} quaerat iusto eaque quia.',
            'Distinctio est harum omnis voluptatibus consectetur tempora non laborum. Neque nostrum neque et. Illum fugit tempore quasi labore {{keyword|morph(6)}} {{keyword|morph(6)}} iure. Sint earum quo doloribus consequatur accusantium. {{keyword|morph(1)}} Dicta quia voluptates occaecati placeat.',
            'Deleniti voluptas optio illum ut velit et quasi. Ad aliquid dignissimos {{keyword|morph(6)}} praesentium {{keyword|morph(2)}} ullam inventore explicabo. Numquam cumque temporibus esse molestias et nemo temporibus tempora. Et et rem ratione quas. Dolorum saepe provident iusto fugit perferendis dolorem. Sed autem necessitatibus a voluptates explicabo. Quae itaque autem velit in. Odio ut quas aut quia dignissimos consequatur et et. Tempora numquam ipsum dolores molestias sunt neque atque.',
            'Et dolorum voluptatem et atque dolores iste. Ad rerum {{keyword|morph(1)}} ipsum consequatur beatae. Cupiditate ut nesciunt voluptates consequuntur fuga dolores.',
            'Optio {{keyword|morph(3)}} sit ut repudiandae excepturi voluptates tempore numquam. Perferendis ut {{keyword|morph(3)}} dignissimos ut illo quas maxime. Saepe voluptates ea excepturi qui doloribus. {{keyword|morph(6)}} Eligendi rerum velit dolor dolores sunt dolorum.',
            'Facilis voluptate neque amet quia autem qui illum. Assumenda {{keyword|morph(0)}} et sit ex beatae sapiente et. A magnam totam maiores ipsam repudiandae. Facilis incidunt magni similique velit nulla. Voluptatem aut ut cum consectetur enim harum quibusdam. Magni earum eius consequatur quia quo. Quo molestias quis esse quisquam voluptatem omnis possimus. Itaque atque sed et culpa vel accusantium ipsum. Illo est aliquam quaerat tempora consequuntur hic.',
            'Sit praesentium sed sed dolorem enim. Aut quis ex vitae magni dignissimos aliquid quis. Voluptate eligendi delectus exercitationem. Et iste ut facilis est sunt ea beatae {{keyword|morph(6)}} tenetur. Deserunt nihil aliquid autem voluptas mollitia rerum quaerat. Hic placeat ullam hic voluptatem iusto. Aperiam tempora nihil aliquam nobis. Occaecati qui quibusdam fuga dolorem placeat quo vero eveniet. Laudantium ut quas a sed molestias. Vel officiis id natus et id {{keyword|morph(3)}} nemo.',
            'Aperiam laborum laboriosam et laborum sunt eos dolor {{keyword|morph(0)}} aliquid. Consequatur laboriosam atque et ut. Eaque consequatur quia nihil aut et provident. Est rerum et ut ut. Quis {{keyword|morph(6)}} veniam voluptatem culpa. {{keyword|morph(1)}} Voluptatum temporibus non suscipit et.',
            'Quisquam ullam rerum iste {{keyword|morph(0)}} quia modi. Minima a {{keyword|morph(1)}} quod reprehenderit molestiae dolorum ex suscipit. Commodi sint qui enim excepturi dolorem vitae. {{keyword|morph(1)}} Minus excepturi culpa id totam rem occaecati vero. Molestias rem doloribus voluptas officiis placeat voluptatem aut aut.',
            'Praesentium ad suscipit magni temporibus. Sapiente deleniti non illum. Eos rerum alias ad aut quo aut. Accusamus molestiae consequatur culpa qui {{keyword|morph(2)}} architecto numquam nihil. Nobis quia ullam at veritatis quia voluptas ut. Tempore vitae dolor {{keyword|morph(5)}} eos suscipit {{keyword|morph(4)}} et aut aut.',
            'Culpa dolorem quod aut temporibus error. Velit quia laudantium est voluptas consectetur {{keyword|morph(1)}} a iste. {{keyword|morph(5)}} Sed ipsam in minima esse eligendi nobis dolores. Ut ratione aut et nihil molestiae voluptate quidem temporibus. Adipisci alias necessitatibus ea earum et quae eos ea.',
            'Aliquam delectus sit sed voluptas quaerat natus consequatur. At occaecati dolorum vitae occaecati. Magni nihil dolores temporibus ut id eveniet. Culpa consequatur error qui rerum aperiam rerum sunt. Autem tenetur est quo veniam. Sit esse qui eaque. Ut labore aut quo consequatur. Sunt et numquam iusto sit. Nostrum est {{keyword|morph(0)}} voluptatem est dolorem velit. Assumenda est cumque est quia.',
            'Id nemo non incidunt nam. Modi et nesciunt optio ut repellat corrupti beatae. Reprehenderit fugit quis porro quas dolorum et ducimus. Omnis in {{keyword|morph(0)}} sunt minus est iure rem et quod. Illo modi vitae aut perspiciatis. Eum qui totam sit. Quidem velit consequuntur voluptatem et id dolores dolor.',
        ];
    }
}
