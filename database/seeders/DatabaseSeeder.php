<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Models\Step;
use App\Models\SubCategory;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@niwa.local',
            'password' => Hash::make('password'),
        ]);

        $this->seedSettings();
        $this->seedHeroSlides();
        $this->seedSteps();
        $this->seedTestimonials();
        $this->seedLocations();
        $this->seedMenu();
    }

    private function seedSettings(): void
    {
        $settings = [
            // Identity
            ['key' => 'site_name', 'value' => 'NIWA FOOD', 'group' => 'identity'],
            ['key' => 'site_description', 'value' => 'Burgers, tacos et pizzas préparés minute, 100% faits maison. Sur place, à emporter, ou livrés chez vous.', 'group' => 'identity'],
            ['key' => 'footer_credit', 'value' => 'Développé par Mehdi Abdi', 'group' => 'identity'],
            // Hero
            ['key' => 'hero_eyebrow', 'value' => 'Fast-food fait maison', 'group' => 'hero'],
            ['key' => 'hero_title', 'value' => 'Commandez vos plats préférés en toute simplicité', 'group' => 'hero'],
            ['key' => 'hero_description', 'value' => 'Tacos, pizzas, burgers et salades préparés minute, 100% faits maison. Sur place, à emporter, ou livrés directement chez vous.', 'group' => 'hero'],
            ['key' => 'hero_cta', 'value' => 'Passer votre commande', 'group' => 'hero'],
            // Best-sellers
            ['key' => 'bestseller_eyebrow', 'value' => 'La sélection Niwa', 'group' => 'bestseller'],
            ['key' => 'bestseller_title', 'value' => 'Les best-sellers', 'group' => 'bestseller'],
            ['key' => 'bestseller_desc', 'value' => 'Les recettes que nos clients choisissent encore et encore.', 'group' => 'bestseller'],
            ['key' => 'bestseller_cta', 'value' => 'Explorer tout le menu', 'group' => 'bestseller'],
            // Steps
            ['key' => 'steps_eyebrow', 'value' => 'En quatre temps', 'group' => 'steps'],
            ['key' => 'steps_title', 'value' => 'Comment ça marche', 'group' => 'steps'],
            ['key' => 'steps_desc', 'value' => 'De votre écran à votre table, en quatre étapes.', 'group' => 'steps'],
            // About (home)
            ['key' => 'about_eyebrow', 'value' => "L'esprit Niwa", 'group' => 'about'],
            ['key' => 'about_title', 'value' => 'Le burger artisanal, c\'est notre spécialité', 'group' => 'about'],
            ['key' => 'about_text_1', 'value' => "Chez Niwa Food, tout part d'une idée simple : préparer chaque burger, tacos et pizza comme s'il était le premier. Nous sélectionnons les meilleurs ingrédients pour vous offrir une expérience gustative inoubliable.", 'group' => 'about'],
            ['key' => 'about_text_2', 'value' => "Depuis nos cuisines à Kouba et Chéraga, on sert celles et ceux qui veulent manger vite sans sacrifier le goût. Du pain toasté minute aux sauces maison, tout est fait avec soin.", 'group' => 'about'],
            // Stats
            ['key' => 'stat_1_value', 'value' => '2', 'group' => 'stats'],
            ['key' => 'stat_1_label', 'value' => 'Adresses à Alger', 'group' => 'stats'],
            ['key' => 'stat_2_value', 'value' => '100', 'group' => 'stats'],
            ['key' => 'stat_2_label', 'value' => '% Fait maison', 'group' => 'stats'],
            ['key' => 'stat_3_value', 'value' => '13', 'group' => 'stats'],
            ['key' => 'stat_3_label', 'value' => "D'ouverture/jour", 'group' => 'stats'],
            // Testimonials
            ['key' => 'testimonials_eyebrow', 'value' => 'La parole aux habitués', 'group' => 'testimonials'],
            ['key' => 'testimonials_title', 'value' => 'Ce que disent nos clients', 'group' => 'testimonials'],
            ['key' => 'testimonials_desc', 'value' => "Des expériences partagées après un passage chez Niwa Food.", 'group' => 'testimonials'],
            // Locations
            ['key' => 'locations_eyebrow', 'value' => 'Venez nous voir', 'group' => 'locations'],
            ['key' => 'locations_title', 'value' => 'Deux adresses, un seul régal', 'group' => 'locations'],
            ['key' => 'locations_desc', 'value' => 'Deux cuisines, la même exigence de fraîcheur et de générosité.', 'group' => 'locations'],
            // Social
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/niwafood', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/niwafood/', 'group' => 'social'],
            ['key' => 'tiktok_url', 'value' => 'https://tiktok.com/@niwafood', 'group' => 'social'],
            ['key' => 'social_cta', 'value' => "Suivez l'aventure", 'group' => 'social'],
            // Menu page
            ['key' => 'menu_eyebrow', 'value' => 'Notre carte', 'group' => 'menu'],
            ['key' => 'menu_title', 'value' => 'Composez votre commande', 'group' => 'menu'],
            ['key' => 'menu_desc', 'value' => 'Sur place, à emporter ou en livraison. Vous choisirez à la fin.', 'group' => 'menu'],
            // About page
            ['key' => 'about_page_hero', 'value' => 'Notre carte se lit comme un garage', 'group' => 'about_page'],
            ['key' => 'about_page_subtitle', 'value' => 'Niwa Food est un fast-food fait maison, né à Kouba et installé depuis à Chéraga. Notre spécialité tient en deux mots : le burger artisanal.', 'group' => 'about_page'],
            ['key' => 'about_page_philosophy', 'value' => "Chez Niwa, tout est préparé à la commande. Pas de stock, pas de réchauffé. Chaque plat est assemblé minute pour garantir fraîcheur et qualité.", 'group' => 'about_page'],
            // Contact
            ['key' => 'contact_hero', 'value' => 'Deux adresses, un seul régal', 'group' => 'contact'],
            ['key' => 'contact_subtitle', 'value' => 'Kouba et Chéraga, ouvertes tous les jours. Un appel suffit pour commander.', 'group' => 'contact'],
            // Footer
            ['key' => 'footer_text', 'value' => '© 2026 Niwa Food. Tous droits réservés.', 'group' => 'footer'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }

    private function seedHeroSlides(): void
    {
        $slides = [
            ['title' => 'Burger juteux, pain toasté', 'image' => 'images/hero-burger1.png', 'sort_order' => 1],
            ['title' => 'Tacos généreux, sauce signature', 'image' => 'images/tacos-gilera.png', 'sort_order' => 2],
            ['title' => 'Pizza maison, pâte du jour', 'image' => 'images/hero-pizza.png', 'sort_order' => 3],
            ['title' => 'Frites croustillantes dorées', 'image' => 'images/frites.png', 'sort_order' => 4],
            ['title' => 'Salade César, croquante et fraîche', 'image' => 'images/salade.png', 'sort_order' => 5],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }

    private function seedSteps(): void
    {
        $steps = [
            ['number' => '01', 'title' => 'Choisissez', 'description' => 'Parcourez notre menu et composez votre commande : burgers, tacos, pizzas ou salades.', 'sort_order' => 1],
            ['number' => '02', 'title' => 'Commandez', 'description' => 'Sur place, à emporter ou en livraison à domicile — vous choisissez ce qui vous arrange.', 'sort_order' => 2],
            ['number' => '03', 'title' => 'On prépare', 'description' => 'Chaque plat est préparé minute dans nos cuisines, jamais à l\'avance.', 'sort_order' => 3],
            ['number' => '04', 'title' => 'Récupérez', 'description' => 'Récupérez sur place ou faites-vous livrer directement chez vous, encore chaud.', 'sort_order' => 4],
        ];

        foreach ($steps as $step) {
            Step::create($step);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['name' => 'Sofiane B.', 'location' => 'Chéraga', 'content' => 'Le burger PIRELLI est devenu mon rituel du vendredi soir. Pain toasté, viande juteuse, rien à dire. Je recommande les yeux fermés.', 'rating' => 5, 'sort_order' => 1],
            ['name' => 'Amina K.', 'location' => 'Kouba', 'content' => 'On a commandé pour toute la famille un dimanche, tacos et pizza. Tout est arrivé chaud et bien emballé. Les enfants ont adoré.', 'rating' => 5, 'sort_order' => 2],
            ['name' => 'Yacine M.', 'location' => 'Chéraga', 'content' => 'Très bon rapport qualité-prix. J\'ai mangé sur place, service rapide même en heure de pointe. Le T-MAX vaut vraiment le détour.', 'rating' => 5, 'sort_order' => 3],
            ['name' => 'Lina H.', 'location' => 'Kouba', 'content' => 'La salade César est vraiment fraîche, pas comme certains fast-foods qui la préparent la veille. Ici tout est fait minute.', 'rating' => 5, 'sort_order' => 4],
            ['name' => 'Nabil A.', 'location' => 'Chéraga', 'content' => 'Client depuis l\'ouverture. La sauce maison sur le tacos GILERA est unique, je ne l\'ai trouvée nulle part ailleurs.', 'rating' => 5, 'sort_order' => 5],
            ['name' => 'Meriem D.', 'location' => 'Kouba', 'content' => 'Bonne pizza, pâte bien fine et croustillante. On a commandé à emporter, prêt en 15 minutes. Nickel.', 'rating' => 5, 'sort_order' => 6],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }

    private function seedLocations(): void
    {
        Location::create([
            'name' => 'Kouba',
            'address' => 'Kouba, Alger',
            'phone' => '0552 52 00 76',
            'hours' => "Lun – Jeu, Sam – Dim : 11h00 – 00h30\nVendredi : 18h00 – 00h30",
            'map_url' => 'https://maps.google.com',
            'sort_order' => 1,
        ]);

        Location::create([
            'name' => 'Chéraga',
            'address' => 'Chéraga, Alger',
            'phone' => '0549 18 97 27',
            'hours' => "Lun – Jeu, Sam – Dim : 11h00 – 00h30\nVendredi : 18h00 – 00h30",
            'map_url' => 'https://maps.google.com',
            'sort_order' => 2,
        ]);
    }

    private function seedMenu(): void
    {
        // --- BURGERS ---
        $burgers = Category::create(['name' => 'Burgers', 'slug' => 'burgers', 'sort_order' => 1]);
        $burgersClassique = SubCategory::create(['category_id' => $burgers->id, 'name' => 'Classique', 'slug' => 'burgers-classique', 'sort_order' => 1]);
        $burgersSignature = SubCategory::create(['category_id' => $burgers->id, 'name' => 'Signature', 'slug' => 'burgers-signature', 'sort_order' => 2]);

        $classiqueBurgers = [
            ['name' => 'NMAX', 'image' => 'images/menu/nmax.png', 'price' => 490, 'description' => 'Pain brioché toasté, steak haché, cheddar, salade, tomate, oignon, sauce maison'],
            ['name' => 'POLINI', 'image' => 'images/menu/polini.png', 'price' => 490, 'description' => 'Pain brioché, steak haché, cheddar fumé, salade, tomate, sauce fromagère'],
            ['name' => 'J-COSTA', 'image' => 'images/menu/jcosta.png', 'price' => 490, 'description' => 'Pain brioché, filet de poulet pané, cheddar, salade, tomate, sauce ranch'],
            ['name' => 'LEOVINCE', 'image' => 'images/menu/leovince.png', 'price' => 490, 'description' => 'Pain brioché, steak haché, emmental, oignons caramélisés, sauce BBQ maison'],
            ['name' => 'ROOF', 'image' => 'images/menu/roof.png', 'price' => 490, 'description' => 'Pain brioché, poulet croustillant, coleslaw, pickles, sauce moutarde-miel'],
            ['name' => 'SHARK', 'image' => 'images/menu/shark.png', 'price' => 490, 'description' => 'Pain brioché, double steak, cheddar, bacon, oignon frit, sauce secrète'],
            ['name' => 'TMAX', 'image' => 'images/menu/tmax.png', 'price' => 550, 'description' => 'Pain brioché, double steak, cheddar fumé, bacon, oignons croustillants, sauce T-MAX'],
        ];

        foreach ($classiqueBurgers as $i => $b) {
            $p = Product::create([
                'sub_category_id' => $burgersClassique->id,
                'name' => $b['name'],
                'slug' => Str::slug($b['name']),
                'image' => $b['image'],
                'description' => $b['description'],
                'price' => $b['price'],
                'is_composable' => true,
                'is_best_seller' => in_array($b['name'], ['NMAX', 'TMAX', 'SHARK']),
                'sort_order' => $i + 1,
            ]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Simple', 'price' => $b['price'], 'type' => 'size', 'sort_order' => 1]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Double', 'price' => $b['price'] + 150, 'type' => 'size', 'sort_order' => 2]);
        }

        $signatureBurgers = [
            ['name' => 'MALOSSI', 'image' => 'images/menu/malossi.png', 'price' => 590, 'description' => 'Pain brioché, steak haché, raclette, champignons, oignons caramélisés, sauce truffe'],
            ['name' => 'HARLEY', 'image' => 'images/menu/harley.png', 'price' => 590, 'description' => 'Pain brioché, poulet grillé, mozzarella, roquette, tomates séchées, pesto'],
            ['name' => 'XMAX', 'image' => 'images/menu/xmax.png', 'price' => 650, 'description' => 'Pain brioché, double steak, triple cheddar, bacon croustillant, sauce signature'],
        ];

        foreach ($signatureBurgers as $i => $b) {
            $p = Product::create([
                'sub_category_id' => $burgersSignature->id,
                'name' => $b['name'],
                'slug' => Str::slug($b['name']),
                'image' => $b['image'],
                'description' => $b['description'],
                'price' => $b['price'],
                'is_composable' => true,
                'is_best_seller' => true,
                'sort_order' => $i + 1,
            ]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Simple', 'price' => $b['price'], 'type' => 'size', 'sort_order' => 1]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Double', 'price' => $b['price'] + 200, 'type' => 'size', 'sort_order' => 2]);
        }

        // --- TACOS ---
        $tacos = Category::create(['name' => 'Tacos', 'slug' => 'tacos', 'sort_order' => 2]);
        $tacosClassique = SubCategory::create(['category_id' => $tacos->id, 'name' => 'Classique', 'slug' => 'tacos-classique', 'sort_order' => 1]);
        $tacosSignature = SubCategory::create(['category_id' => $tacos->id, 'name' => 'Signature', 'slug' => 'tacos-signature', 'sort_order' => 2]);

        $p = Product::create([
            'sub_category_id' => $tacosClassique->id,
            'name' => 'Tacos Classique',
            'slug' => 'tacos-classique-item',
            'image' => 'images/menu/tacos-classique.png',
            'description' => 'Tortilla de blé, viande au choix, frites, fromage fondu, sauce algérienne',
            'price' => 500,
            'is_composable' => true,
            'sort_order' => 1,
        ]);
        ProductVariant::create(['product_id' => $p->id, 'name' => 'Simple', 'price' => 500, 'type' => 'size', 'sort_order' => 1]);
        ProductVariant::create(['product_id' => $p->id, 'name' => 'Double', 'price' => 650, 'type' => 'size', 'sort_order' => 2]);

        $tacosSignatureItems = [
            ['name' => 'SAMOURAI', 'image' => 'images/menu/samourai.png', 'price' => 600, 'description' => 'Tortilla, viande hachée épicée, frites, cheddar fondu, jalapeños, sauce chipotle'],
            ['name' => 'FUEGO', 'image' => 'images/menu/fuego.png', 'price' => 650, 'description' => 'Tortilla, poulet grillé, frites, mozzarella, sauce piquante maison'],
            ['name' => 'GILERA', 'image' => 'images/menu/gilera.png', 'price' => 750, 'description' => 'Tortilla XXL, double viande, frites, triple fromage, sauce signature'],
        ];

        foreach ($tacosSignatureItems as $i => $t) {
            $p = Product::create([
                'sub_category_id' => $tacosSignature->id,
                'name' => $t['name'],
                'slug' => Str::slug('tacos-' . $t['name']),
                'image' => $t['image'],
                'description' => $t['description'],
                'price' => $t['price'],
                'is_composable' => true,
                'is_best_seller' => $t['name'] === 'GILERA',
                'sort_order' => $i + 1,
            ]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Simple', 'price' => $t['price'], 'type' => 'size', 'sort_order' => 1]);
            ProductVariant::create(['product_id' => $p->id, 'name' => 'Double', 'price' => $t['price'] + 100, 'type' => 'size', 'sort_order' => 2]);
        }

        // --- PIZZAS ---
        $pizzas = Category::create(['name' => 'Pizzas', 'slug' => 'pizzas', 'sort_order' => 3]);
        $sauceBlanche = SubCategory::create(['category_id' => $pizzas->id, 'name' => 'Sauce blanche', 'slug' => 'pizzas-sauce-blanche', 'sort_order' => 1]);
        $sauceRouge = SubCategory::create(['category_id' => $pizzas->id, 'name' => 'Sauce rouge', 'slug' => 'pizzas-sauce-rouge', 'sort_order' => 2]);

        $pizzasBlanches = [
            ['name' => 'KAWASAKI', 'image' => 'images/menu/kawasaki.png', 'price' => 750, 'description' => 'Sauce crème, mozzarella, cheddar, emmental, chèvre'],
            ['name' => 'DUCATTI', 'image' => 'images/menu/ducatti.png', 'price' => 700, 'description' => 'Sauce crème, mozzarella, champignons frais, persil, ail'],
            ['name' => 'PIRELLI', 'image' => 'images/menu/pirelli.png', 'price' => 800, 'description' => 'Sauce ranch, mozzarella, poulet grillé, maïs, oignons'],
        ];

        foreach ($pizzasBlanches as $i => $pz) {
            Product::create([
                'sub_category_id' => $sauceBlanche->id,
                'name' => $pz['name'],
                'slug' => Str::slug('pizza-' . $pz['name']),
                'image' => $pz['image'],
                'description' => $pz['description'],
                'price' => $pz['price'],
                'sort_order' => $i + 1,
            ]);
        }

        $pizzasRouges = [
            ['name' => 'CUXI', 'image' => 'images/menu/cuxi.png', 'price' => 500, 'description' => 'Sauce tomate, mozzarella, basilic frais'],
            ['name' => 'GIVI', 'image' => 'images/menu/givi.png', 'price' => 650, 'description' => 'Sauce tomate, mozzarella, pepperoni'],
            ['name' => 'TENERE', 'image' => 'images/menu/tenere.png', 'price' => 600, 'description' => 'Sauce tomate, mozzarella, poivrons, olives, champignons, oignons'],
            ['name' => 'APRILIA', 'image' => 'images/menu/aprilia.png', 'price' => 700, 'description' => 'Sauce tomate, mozzarella, viande hachée épicée, oignons, poivrons'],
            ['name' => 'VESPA', 'image' => 'images/menu/vespa.png', 'price' => 700, 'description' => 'Sauce tomate, mozzarella, thon, olives, câpres'],
            ['name' => 'PIAGGIO', 'image' => 'images/menu/piaggio.png', 'price' => 800, 'description' => 'Sauce BBQ, mozzarella, poulet grillé, oignons rouges, poivrons'],
            ['name' => 'NOLAN', 'image' => 'images/menu/nolan.png', 'price' => 850, 'description' => 'Sauce tomate, mozzarella, jambon, champignons, poivrons, olives'],
            ['name' => 'ARAI', 'image' => 'images/menu/arai.png', 'price' => 950, 'description' => 'Sauce tomate, triple fromage, viande hachée, poulet, champignons, poivrons, oignons'],
        ];

        foreach ($pizzasRouges as $i => $pz) {
            Product::create([
                'sub_category_id' => $sauceRouge->id,
                'name' => $pz['name'],
                'slug' => Str::slug('pizza-' . $pz['name']),
                'image' => $pz['image'],
                'description' => $pz['description'],
                'price' => $pz['price'],
                'is_best_seller' => $pz['name'] === 'CUXI',
                'sort_order' => $i + 1,
            ]);
        }

        // --- BOISSONS ---
        $boissons = Category::create(['name' => 'Boissons', 'slug' => 'boissons', 'sort_order' => 4]);
        $canettes = SubCategory::create(['category_id' => $boissons->id, 'name' => 'Canettes', 'slug' => 'boissons-canettes', 'sort_order' => 1]);
        $bouteilles = SubCategory::create(['category_id' => $boissons->id, 'name' => 'Bouteilles', 'slug' => 'boissons-bouteilles', 'sort_order' => 2]);

        $canettesItems = [
            ['name' => 'Coca cola', 'image' => 'images/menu/coca-cola.png', 'price' => 100, 'description' => 'Canette 33cl'],
            ['name' => 'Coca zero', 'image' => 'images/menu/coca-zero.png', 'price' => 100, 'description' => 'Canette 33cl'],
            ['name' => 'Selecto', 'image' => 'images/menu/selecto.png', 'price' => 100, 'description' => 'Canette 33cl'],
        ];
        foreach ($canettesItems as $i => $item) {
            Product::create([
                'sub_category_id' => $canettes->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'image' => $item['image'],
                'description' => $item['description'],
                'price' => $item['price'],
                'sort_order' => $i + 1,
            ]);
        }

        $bouteillesItems = [
            ['name' => "Bouteille d'eau", 'image' => 'images/menu/bouteille-eau.png', 'price' => 50, 'description' => 'Bouteille 50cl'],
            ['name' => 'Coca cola 1L', 'image' => 'images/menu/coca-1l.png', 'price' => 200, 'description' => 'Bouteille 1L'],
        ];
        foreach ($bouteillesItems as $i => $item) {
            Product::create([
                'sub_category_id' => $bouteilles->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'image' => $item['image'],
                'description' => $item['description'],
                'price' => $item['price'],
                'sort_order' => $i + 1,
            ]);
        }

        // --- ACCOMPAGNEMENTS ---
        $accompagnements = Category::create(['name' => 'Accompagnements', 'slug' => 'accompagnements', 'sort_order' => 5]);
        $accompSub = SubCategory::create(['category_id' => $accompagnements->id, 'name' => 'Accompagnements', 'slug' => 'accompagnements-all', 'sort_order' => 1]);

        $accompItems = [
            ['name' => 'Frites Simple', 'image' => 'images/menu/frites-simple.png', 'price' => 150, 'description' => 'Frites fraîches coupées main'],
            ['name' => 'Galets Box', 'image' => 'images/menu/galets-box.png', 'price' => 250, 'description' => 'Box de galets de pommes de terre croustillants'],
            ['name' => 'Frites Fromage', 'image' => 'images/menu/frites-fromage.png', 'price' => 250, 'description' => 'Frites maison nappées de sauce fromagère'],
            ['name' => 'Frites Niwa', 'image' => 'images/menu/frites-niwa.png', 'price' => 300, 'description' => 'Frites maison avec sauce Niwa signature'],
            ['name' => 'Frites Mix', 'image' => 'images/menu/frites-mix.png', 'price' => 350, 'description' => 'Mix de frites et galets avec sauces au choix'],
        ];
        foreach ($accompItems as $i => $item) {
            Product::create([
                'sub_category_id' => $accompSub->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'image' => $item['image'],
                'description' => $item['description'],
                'price' => $item['price'],
                'is_best_seller' => $item['name'] === 'Frites Niwa',
                'sort_order' => $i + 1,
            ]);
        }

        // --- SALADES ---
        $salades = Category::create(['name' => 'Salades', 'slug' => 'salades', 'sort_order' => 6]);
        $saladesSub = SubCategory::create(['category_id' => $salades->id, 'name' => 'Salades', 'slug' => 'salades-all', 'sort_order' => 1]);

        Product::create([
            'sub_category_id' => $saladesSub->id,
            'name' => 'Salade César',
            'slug' => 'salade-cesar',
            'image' => 'images/menu/salade-cesar.png',
            'description' => 'Laitue romaine, poulet grillé, parmesan, croûtons, sauce César maison',
            'price' => 450,
            'sort_order' => 1,
        ]);
    }
}
