<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MassAddController extends Controller
{
    public function massAddProducts(){

        $data = [
            [
                "name" => "Apples per 3 PCs",
                "slug" => "apples-3pcs",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh and juicy apples, perfect for a healthy snack or fruit salad."
            ],[
                "name" => "Avocado per PC",
                "slug" => "avocado",
                "price" => "40",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Rich and creamy avocado, great for salads, sandwiches, or guacamole."
            ],[
                "name" => "Butternuts per Kg",
                "slug" => "butternuts-kg",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet and nutty butternut squash, ideal for soups and roasting."
            ],[
                "name" => "Fresh Peas per Kg",
                "slug" => "fresh-peas-kg",
                "price" => "400",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet and tender green peas, a nutritious addition to meals."
            ],[
                "name" => "Grapes per pack",
                "slug" => "grapes-pack",
                "price" => "400",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Seedless and juicy grapes, great for snacking or making juice."
            ],[
                "name" => "Green Banana (Cooking) per bunch",
                "slug" => "green-banana-cooking-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Raw green bananas, perfect for cooking and making delicious dishes."
            ],[
                "name" => "Green Maize corn per Kg",
                "slug" => "green-maize-corn-kg",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh green maize, ideal for roasting, boiling, or making flour."
            ],[
                "name" => "Lemons per PC",
                "slug" => "lemons",
                "price" => "10",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Zesty and tangy lemons, perfect for adding flavor to dishes and drinks."
            ],[
                "name" => "Mangoes per PC",
                "slug" => "mangoes",
                "price" => "40",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet and juicy mangoes, great for smoothies, desserts, or eating fresh."
            ],[
                "name" => "Oranges-Imported per 3 PCs",
                "slug" => "oranges-imported-3pcs",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Premium imported oranges, rich in vitamin C and bursting with flavor."
            ],[
                "name" => "Oranges-Local per PC",
                "slug" => "oranges-local",
                "price" => "20",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Locally grown oranges, fresh and naturally sweet."
            ],[
                "name" => "Passion Fruits per Kg",
                "slug" => "passion-fruits-kg",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Aromatic and tangy passion fruits, ideal for juices and desserts."
            ],[
                "name" => "Pawpaw per Kg",
                "slug" => "pawpaw-kg",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Soft and sweet pawpaw, great for digestion and making smoothies."
            ],[
                "name" => "Pineapples per Kg",
                "slug" => "pineapples-kg",
                "price" => "120",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh and juicy pineapples, ideal for snacking and fruit salads."
            ],[
                "name" => "Pixies Oranges per 500g",
                "slug" => "pixies-oranges-500g",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Small and sweet pixie oranges, rich in vitamins and antioxidants."
            ],[
                "name" => "Plantain per bunch",
                "slug" => "plantain-bunch",
                "price" => "300",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious plantains, perfect for frying, boiling, or baking."
            ],[
                "name" => "Tangarine (Sandara) per 3 PCs",
                "slug" => "tangerine-sandara-3pcs",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet and juicy tangerines, easy to peel and full of flavor."
            ],[
                "name" => "Tomatoes per Kg",
                "slug" => "tomatoes-kg",
                "price" => "110",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Ripe and fresh tomatoes, perfect for salads, sauces, and cooking."
            ],[
                "name" => "Water Melon per Kg",
                "slug" => "watermelon-kg",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Refreshing and hydrating watermelon, perfect for hot days."
            ],[
                "name" => "Green Maize corn kernels per Kg",
                "slug" => "green-maize-corn-kernels-kg",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Freshly harvested maize kernels, great for cooking or milling."
            ],[
                "name" => "Arrow Root per Kg",
                "slug" => "arrow-root-kg",
                "price" => "180",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious arrow root, ideal for thickening soups and stews."
            ],[
                "name" => "Carrots per Kg",
                "slug" => "carrots-kg",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Crisp and sweet carrots, perfect for snacking and cooking."
            ],[
                "name" => "Dry Onions per Kg",
                "slug" => "dry-onions-kg",
                "price" => "130",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Aromatic dry onions, essential for cooking and flavoring dishes."
            ],[
                "name" => "Garlic per bulb",
                "slug" => "garlic-bulb",
                "price" => "30",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh garlic bulbs, adding robust flavor to any dish."
            ],[
                "name" => "Ginger per 250g",
                "slug" => "ginger-250g",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Spicy ginger, ideal for teas, cooking, and health remedies."
            ],[
                "name" => "Sweet Potatoes per Kg",
                "slug" => "sweet-potatoes-kg",
                "price" => "130",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious sweet potatoes, great for roasting, mashing, or baking."
            ],[
                "name" => "White Irish Potatoes per Kg",
                "slug" => "white-irish-potatoes-kg",
                "price" => "120",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Versatile white Irish potatoes, perfect for various dishes."
            ],[
                "name" => "Amaranthus ( Terere) per bunch",
                "slug" => "amaranthus-terere-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh amaranthus leaves, ideal for traditional dishes and salads."
            ],[
                "name" => "Black  nightshade (Osuga) per bunch",
                "slug" => "black-nightshade-osuga-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Bitter black nightshade, commonly used in local cuisine."
            ],[
                "name" => "Cabbage White per PC",
                "slug" => "cabbage-white-pc",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Crisp white cabbage, perfect for salads and stir-fries."
            ],[
                "name" => "Cabbages Red per PC",
                "slug" => "cabbages-red-pc",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Vibrant red cabbage, excellent for coleslaw and salads."
            ],[
                "name" => "Cauliflower per PC",
                "slug" => "cauliflower-pc",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh cauliflower, versatile for steaming, roasting, or raw consumption."
            ],[
                "name" => "Chinese Cabbage per PC",
                "slug" => "chinese-cabbage-pc",
                "price" => "300",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Crisp Chinese cabbage, ideal for stir-fries and salads."
            ],[
                "name" => "Jute Plant (Mrenda) per bunch",
                "slug" => "jute-plant-mrenda-bunch",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious jute plant leaves, used in traditional dishes."
            ],[
                "name" => "Kales/Sukuma Wiki per bunch",
                "slug" => "kales-sukuma-wiki-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh sukuma wiki, a staple leafy green for cooking."
            ],[
                "name" => "Spider Flower (Saga) per bunch",
                "slug" => "spider-flower-saga-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Unique spider flower, known for its vibrant foliage."
            ],[
                "name" => "Spinach per bunch",
                "slug" => "spinach-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh spinach, rich in iron and vitamins, perfect for salads and cooking."
            ],[
                "name" => "Basil per bunch",
                "slug" => "basil-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fragrant basil, ideal for seasoning and enhancing flavors."
            ],[
                "name" => "Blackberry per punnet",
                "slug" => "blackberry-punnet",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Juicy blackberries, perfect for desserts, jams, or snacking."
            ],[
                "name" => "Blueberry per punnet",
                "slug" => "blueberry-punnet",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet blueberries, ideal for smoothies, desserts, and cereals."
            ],[
                "name" => "Broccoli per PC",
                "slug" => "broccoli-pc",
                "price" => "150",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious broccoli, rich in vitamins and perfect for steaming or stir-fries."
            ],[
                "name" => "Celery per bunch",
                "slug" => "celery-bunch",
                "price" => "120",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Crunchy celery, great for salads, soups, or as a snack."
            ],[
                "name" => "chillies per 250g",
                "slug" => "chillies-250g",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Spicy chillies, adding a kick to any dish."
            ],[
                "name" => "Coriander (Dhania) per bunch",
                "slug" => "coriander-dhania-bunch",
                "price" => "20",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh coriander leaves, perfect for garnishing and flavoring."
            ],[
                "name" => "Courgette per Kg",
                "slug" => "courgette-kg",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Tender courgettes, ideal for grilling, stews, or salads."
            ],[
                "name" => "Cowpea Leaves (Kunde) per bunch",
                "slug" => "cowpea-leaves-kunde-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious cowpea leaves, great for traditional dishes."
            ],[
                "name" => "Cucumber per Kg",
                "slug" => "cucumber-kg",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Refreshing cucumber, perfect for salads, sandwiches, and snacking."
            ],[
                "name" => "Dill per bunch",
                "slug" => "dill-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Aromatic dill, ideal for flavoring fish, sauces, and pickles."
            ],[
                "name" => "Egg Plant per bunch",
                "slug" => "eggplant-bunch",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh eggplant, perfect for grilling, stews, or dips."
            ],[
                "name" => "Ethiopian Kales - Kanzira per bunch",
                "slug" => "ethiopian-kales-kanzira-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Local Ethiopian kales, ideal for traditional cooking."
            ],[
                "name" => "Green Capsicums per 500g",
                "slug" => "green-capsicums-500g",
                "price" => "75",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh green capsicums, crisp and perfect for salads and stir-fries."
            ],[
                "name" => "Green Onion per bunch",
                "slug" => "green-onion-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh green onions, adding a mild onion flavor to dishes."
            ],[
                "name" => "Honey per 800g",
                "slug" => "honey-800g",
                "price" => "850",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Natural honey, perfect for sweetening and as a healthy treat."
            ],[
                "name" => "Indiginous Crotolaria (Mito/Miro) per bunch",
                "slug" => "indigenous-crotolaria-bunch",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Local indigenous crotolaria, used in traditional recipes."
            ],[
                "name" => "Kiwi fruit per piece",
                "slug" => "kiwi-fruit-piece",
                "price" => "70",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Tangy kiwi fruit, perfect for snacking and salads."
            ],[
                "name" => "Leeks per bunch",
                "slug" => "leeks-bunch",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh leeks, ideal for soups and stews."
            ],[
                "name" => "Lettuce per bunch",
                "slug" => "lettuce-bunch",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Crisp lettuce, perfect for salads and sandwiches."
            ],[
                "name" => "Mint per bunch",
                "slug" => "mint-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Refreshing mint, great for flavoring drinks, desserts, and dishes."
            ],[
                "name" => "Mushroom per punnet",
                "slug" => "mushroom-punnet",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh mushrooms, perfect for salads, pizzas, and stir-fries."
            ],[
                "name" => "Okra per Kg",
                "slug" => "okra-kg",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Tender okra, ideal for stews and soups."
            ],[
                "name" => "Peanut Butter ( Natural) per 800g",
                "slug" => "peanut-butter-natural-800g",
                "price" => "600",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Creamy natural peanut butter, perfect for spreading and snacks."
            ],[
                "name" => "Pumpkin per piece",
                "slug" => "pumpkin-piece",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh pumpkin, ideal for soups, baking, or roasting."
            ],[
                "name" => "Pumpkin Leaves per bunch",
                "slug" => "pumpkin-leaves-bunch",
                "price" => "50",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Nutritious pumpkin leaves, used in traditional recipes."
            ],[
                "name" => "Raspberry per punnet",
                "slug" => "raspberry-punnet",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Fresh raspberries, bursting with flavor and antioxidants."
            ],[
                "name" => "Raw Ground Nuts per Kg",
                "slug" => "raw-ground-nuts-kg",
                "price" => "300",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Raw ground nuts, perfect for snacking and cooking."
            ],[
                "name" => "Red/Yellow Capsicums per 500g",
                "slug" => "red-yellow-capsicums-500g",
                "price" => "200",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Colorful red/yellow capsicums, great for salads and stir-fries."
            ],[
                "name" => "Roasted Ground Nuts per Kg",
                "slug" => "roasted-ground-nuts-kg",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Roasted ground nuts, ideal for snacking and adding crunch to dishes."
            ],[
                "name" => "Rosemary per bunch",
                "slug" => "rosemary-bunch",
                "price" => "30",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Aromatic rosemary, perfect for seasoning meats and vegetables."
            ],[
                "name" => "Strawberry per punnet",
                "slug" => "strawberry-punnet",
                "price" => "350",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Sweet strawberries, ideal for desserts, jams, or snacking."
            ],[
                "name" => "Tamarind per packet",
                "slug" => "tamarind-packet",
                "price" => "100",
                "category_id" => Category::where('name', 'like', '%General%')->first()->id,
                "description" => "Tangy tamarind, perfect for sauces, chutneys, and traditional dishes."
            ]
        ];

        foreach ($data as $productData) {
            // You can either use create() if you have set up mass assignment (fillable) on your model
            Product::create([
                'name'          => $productData['name'],
                'slug'          => $productData['slug'],
                'description'   => $productData['description'],
                'price'         => (float) $productData['price'],
                'stock'         => 0,  // default value if not provided
                'total_stock'   => 0,  // default value if not provided
                'image_url'     => null, // default value if not provided
                'is_active'     => true, // default value
                'category_id'   => $productData['category_id'],
                'user_id'       => Auth::user()->id,  // default value if not provided
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Products added successfully');
    }
}
