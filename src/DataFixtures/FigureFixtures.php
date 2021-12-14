<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{

    private array $figures = [
        [
          'name' => 'Rotation 180',
          'description' => "Il s'agit d'une figure relativement simple, et plus précisément d'un saut sans rotation qui se fait généralement dans un pipe (un U). Le rider s'élance dans les airs et retombe dans le sens inverse.",
        ],
        [
            'name' => 'Rotation 360',
            'description' => "C'est un mot qui revient souvent dans la bouche des snowboardeurs. Mais pas que, puisqu'on parle aussi de carving en skis. Mais alors qu'est-ce que c'est ? Carver, c'est tout simplement faire un virage net en se penchant et sans déraper.",
        ],
        [
            'name' => 'Rotation 540',
            'description' => "Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception.",
        ],
        [
            'name' => 'Rotation 720',
            'description' => "Les grabs sont la base des figures freestyle en snowboard. C’est le fait d’attraper sa planche avec une ou deux mains pendant un saut. On en compte six de base : indy, mute, nose grab, melon, stalefish et tail grab.",
        ],
        [
            'name' => 'Rotation 810',
            'description' => "Le Jib (aussi appelé slide ou grind) est une pratique du snow freestyle qui consiste à glisser sur tous types de modules autres que la neige (rails, troncs d'arbre, tables etc.)",
        ],
        [
            'name' => 'Indy',
            'description' => "Le lispslide consiste à glisser sur un obstacle en mettant la planche perpendiculaire à celui-ci. Un jib à 90 degrés en d'autres termes. Le lipslide peut se faire en avant ou en arrière. Frontside ou backside, donc.",
        ],
        [
            'name' => 'Japanair',
            'description' => "Le Mc Twist est un flip (rotation verticale) agrémenté d'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.",
        ],
        [
            'name' => 'Mute',
            'description' => "C'est un jib que le rider effectue sur le nose de la planche, soit la spatule qui se trouve devant lui. La spatule arrière s'appelle le tail. Le noseslide peut être frontside ou backside.",
        ],
        [
            'name' => 'Nose grab',
            'description' => "Il s'agit d'un concept assez flou qu'il est difficile de définir. Le pop est le fait de faire décoller sa board avec un mouvement assez énergique. Certains riders ont plus de pop que d'autres et ça se voit quand ils sautent par dessus un obstacle.",
        ],
        [
            'name' => 'Sad',
            'description' => "Comme son nom l'indique, le quarter-pipe est un demi half-pipe, soit un module avec pente ascendante se terminant à la verticale. Certains quarter-pipes peuvent atteindre plus de 8 mètres de haut. neige.",
        ],
        [
            'name' => 'Seat belt',
            'description' => "C'est une figure qui consiste à faire un salto arrière en y ajoutant une rotation d'un demi-tour. Le rodeo est back quand le snowboarder part de dos et front quand il part de face.",
        ],
        [
            'name' => 'Stale fish',
            'description' => "Les twin-tip - ou shape twin - sont les formes les plus communes des boards freestyle. Une planche twin est une planche symétrique à 100%. Si on pouvait la plier en deux, le nose se superposerait parfaitement au tail, tout comme les inserts. En d'autres termes, s'il n'y avait pas de déco, on ne pourrait pas discerner l'avant et l'arrière.",
        ],
        [
            'name' => 'Tail grab',
            'description' => "Le frontside underflip 540 est une figure qui mêle un frontside 180 et un backflip. Ce trick peut paraître intimidant, mais il n'est pas si compliqué. Hormis le décollage, bien sûr. Ensuite, les mouvements peuvent s'enchaîner assez naturellement.",
        ],
        [
            'name' => 'True nose',
            'description' => "Le nom de ce trick qui requiert un maximum d'équilibre vient de Serge Vitelli, son inventeur. Il s'agit d'un virage que le rider effectue couché sur le sol, avec la main posée à terre. Prends ça, le bâton de ski.",
        ],
        [
            'name' => 'Inverse door',
            'description' => "Aussi appelé backflip, le wildcat est un salto arrière que le rider effectue dans les airs après avoir pris de la vitesse. C'est un trick qui peut être difficile à réaliser puisque le snowboardeur doit veiller à rester dans le bon axe.",
        ],
        [
            'name' => 'Truck driver',
            'description' => "C'est un jib pendant lequel la planche n'est pas parallèle ou perpendiculaire à l'obstacle et qui manque de style.",
        ],
        [
            'name' => 'Misty',
            'description' => "La plupart des figures sont suivies d'un nombre. Il s'agit du nombre de degrés de la rotation. Ainsi, pour une double rotation on parle d'un 720, une triple correspond à un 1.080, et ainsi de suite.",
        ],
        [
            'name' => 'Misty top',
            'description' => "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.",
        ],
        [
            'name' => 'Switch',
            'description' => "Saisie de la carre backside de la planche entre les deux pieds avec la main arrière",
        ],
        [
            'name' => 'Switch backflip',
            'description' => "Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n'est pas fixé. Ce type de figure est extrêmement dangereuse pour les ligaments du genou en cas de mauvaise réception.",
        ],
    ];

    private ObjectManager $manager;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Generate 15 figures

        $this->generateFigures(20);

        $manager->flush();
    }

    public function generateFigures(int $number)
    {
        for ($f=0;$f < $number;$f++) {

            $slug = $this->slugger->slug($this->figures[$f]['name'], '_');

            /**
 * @var User $user 
*/
            $user = $this->getReference("user".mt_rand(0, 4));

            /**
 * @var Category $category 
*/
            $category = $this->getReference("category".mt_rand(0, 4));

            $updatedAt = new \DateTimeImmutable("now - $f day");

            if ($f%2 == 1) {
                $updatedAt = null;
            }

            $figure = new Figure();
            $figure
                ->setName($this->figures[$f]['name'])
                ->setDescription($this->figures[$f]['description'])
                ->setCreatedAt(new \DateTimeImmutable("now - $f day"))
                ->setSlug($slug)
                ->setCategory($category)
                ->setUser($user)
                ->setUpdatedAt($updatedAt);

            $this->addReference("figure$f", $figure);

            $this->manager->persist($figure);

        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }

}
