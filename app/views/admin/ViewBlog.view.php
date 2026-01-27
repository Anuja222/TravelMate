<!DOCTYPE html>
<html>
<head>
  <title>View Blog - TravelMate</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/common.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/ViewBlog.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <?php include __DIR__ . '/../traveller/header.view.php'; ?>

  <?php
  // Sample blog data - in a real application, this would come from a database
  $blogId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
  
  $blogs = [
    1 => [
      'title' => 'Exploring the Hidden Beaches of Southern Sri Lanka',
      'subtitle' => 'A journey through the untouched coastal paradise beyond the popular tourist spots',
      'author' => 'Travel Seeker',
      'role' => 'Adventure Blogger & Photographer',
      'date' => 'March 15, 2024',
      'readTime' => '12 min read',
      'views' => '2.4k views',
      'hero' => 'assets/images/hidden beach.jpg',
      'images' => [
        ['src' => 'assets/images/hidden beach.jpg', 'alt' => 'Hidden beach cove']
      ],
      'content' => [
        'intro' => 'When most travelers think of Sri Lanka\'s southern coast, they imagine the popular beaches of Mirissa, Unawatuna, and Hikkaduwa. But venture just a little further, and you\'ll discover a world of pristine, untouched beaches that feel like they\'re from another time. The turquoise waters, golden sands, and complete absence of crowds create an experience that\'s becoming increasingly rare in our connected world.',
        'sections' => [
          ['title' => 'The Journey Begins', 'text' => 'Our adventure started early in the morning from Galle Fort. The air was fresh, carrying the scent of salt and tropical flowers. We\'d hired a local tuk-tuk driver named Saman who promised to show us beaches that even many locals haven\'t visited. As we drove along the coastal road, the sun rising over the Indian Ocean painted the sky in shades of orange and pink. The morning mist still clung to the palm trees, creating an ethereal atmosphere.', 'image' => 'assets/images/hidden beach.jpg'],
          ['title' => 'Dalawella\'s Hidden Cove', 'text' => 'Just beyond the famous Dalawella Beach with its iconic rope swing, there\'s a small path that leads through dense coconut groves. After a 10-minute walk, we emerged at a secluded cove that took our breath away. The water was crystal clear, with shades of turquoise I didn\'t think existed in real life. Massive boulders framed both sides of the cove, creating a natural sanctuary. We spent hours here, swimming in the calm waters and watching crabs scuttle across the rocks.', 'image' => 'assets/images/hidden beach.jpg'],
          ['title' => 'Meeting the Fishermen', 'text' => 'At each beach we visited, we encountered local fishermen who were more than happy to share stories about their lives and the sea. At one particularly remote beach, an elderly fisherman named Rohan showed us how they still use traditional fishing methods passed down through generations. "The tourists go to the big beaches," he told us, "but here, the sea is still pure." He taught us about reading the tides and identifying the best fishing spots.', 'image' => 'assets/images/hidden beach.jpg'],
          ['title' => 'Jungle Beach Discovery', 'text' => 'No visit to the southern coast is complete without hiking to Jungle Beach near Unawatuna. The 30-minute trek through lush forest is challenging but absolutely worth it. Monkeys swung through the canopy above us, and exotic birds called from the undergrowth. When you finally break through the trees and see the crescent-shaped bay below, it feels like discovering a secret world. The beach is framed by towering trees that provide natural shade, and the water here is perfect for snorkeling.', 'image' => 'assets/images/hidden beach.jpg'],
          ['title' => 'Best Time to Visit', 'text' => 'The southern coast is best visited between November and April when the seas are calm and the weather is dry. During these months, the water visibility is excellent for snorkeling, and the beaches are at their most beautiful. Early mornings offer the best light for photography and the coolest temperatures for hiking to hidden beaches.', 'image' => 'assets/images/hidden beach.jpg']
        ]
      ]
    ],
    2 => [
      'title' => 'Wildlife Encounters in Yala National Park',
      'subtitle' => 'An unforgettable safari experience in Sri Lanka\'s most famous wildlife sanctuary',
      'author' => 'Wildlife Explorer',
      'role' => 'Nature Photographer & Conservationist',
      'date' => 'April 2, 2024',
      'readTime' => '14 min read',
      'views' => '2.1k views',
      'hero' => 'assets/images/yala.jpg',
      'images' => [
        ['src' => 'assets/images/yala.jpg', 'alt' => 'Wildlife in Yala'],
        ['src' => 'assets/images/safari.png', 'alt' => 'Safari adventure']
      ],
      'content' => [
        'intro' => 'Yala National Park is home to one of the highest leopard densities in the world. Our early morning safari took us deep into the heart of this incredible wilderness where we encountered elephants, crocodiles, and if lucky, the elusive Sri Lankan leopard. Covering nearly 1,000 square kilometers, Yala is a biodiversity hotspot that supports an incredible variety of wildlife, from massive elephants to tiny jungle fowl.',
        'sections' => [
          ['title' => 'The Morning Safari Begins', 'text' => 'We started our journey at 5:30 AM, when the park gates opened. The golden morning light created perfect conditions for wildlife photography. Within the first hour, we spotted a herd of elephants near a waterhole. The matriarch led her family with a grace that belied her massive size, while the young calves played in the muddy water. Our guide, Nimal, who has worked in Yala for over 20 years, explained the complex social structures of elephant herds.', 'image' => 'assets/images/yala.jpg'],
          ['title' => 'Leopard Territory', 'text' => 'After three hours of searching, our guide spotted fresh leopard tracks on the dusty trail. We followed them carefully, maintaining silence in our jeep. The anticipation built as we navigated through rocky terrain. Then, lounging on a large boulder like it owned the world, was a magnificent Sri Lankan leopard. Its spotted coat was perfectly camouflaged against the granite rock. It surveyed its territory with regal indifference, occasionally yawning to reveal impressive canines. We watched in awe for 45 minutes before it gracefully disappeared into the scrub.', 'image' => 'assets/images/safari.png'],
          ['title' => 'Birdwatcher\'s Paradise', 'text' => 'Yala is also a paradise for bird enthusiasts. We identified over 30 species in a single morning, including the colorful Indian roller, the majestic crested hawk-eagle, and numerous species of kingfishers. The park is a critical habitat for migratory birds, and we were fortunate to see several rare species. The lagoons near the coast attract waders and waterbirds in large numbers.', 'image' => 'assets/images/yala.jpg'],
          ['title' => 'Sloth Bears and Other Wildlife', 'text' => 'Late afternoon brought another surprise - a sloth bear foraging for termites. These shaggy black bears are notoriously difficult to spot, but we watched this one for nearly 30 minutes as it used its long claws to tear open termite mounds. We also encountered spotted deer, wild boar, water buffalo, and countless monitor lizards basking on rocks.', 'image' => 'assets/images/safari.png'],
          ['title' => 'Conservation Efforts', 'text' => 'Yala faces challenges from poaching and habitat loss, but dedicated rangers and conservation groups are working tirelessly to protect this ecosystem. The park\'s successful leopard conservation program has become a model for other reserves. Visitor fees directly support these conservation efforts, making responsible tourism crucial for the park\'s future.', 'image' => 'assets/images/yala.jpg']
        ]
      ]
    ],
    3 => [
      'title' => 'Ancient Temples of the Cultural Triangle',
      'subtitle' => 'Discovering Sri Lanka\'s rich Buddhist heritage and architectural marvels',
      'author' => 'Cultural Heritage',
      'role' => 'History Enthusiast & Travel Writer',
      'date' => 'March 28, 2024',
      'readTime' => '13 min read',
      'views' => '1.8k views',
      'hero' => 'assets/images/temple.jpg',
      'images' => [
        ['src' => 'assets/images/temple.jpg', 'alt' => 'Ancient temple'],
        ['src' => 'assets/images/cultural.png', 'alt' => 'Temple architecture']
      ],
      'content' => [
        'intro' => 'The Cultural Triangle of Sri Lanka encompasses some of the most significant archaeological and religious sites in Asia. From ancient cave temples to massive stupas, this region is a treasure trove of history and spirituality. These UNESCO World Heritage Sites represent 2,500 years of continuous civilization and offer a profound glimpse into the island\'s Buddhist heritage and architectural brilliance.',
        'sections' => [
          ['title' => 'Dambulla Cave Temple Complex', 'text' => 'The golden temple of Dambulla houses five caves filled with over 150 Buddha statues and ancient murals covering 2,100 square meters. Climbing the 160 steps to reach the temple complex, you\'re rewarded with breathtaking views of the surrounding countryside. Inside, the cave walls and ceilings are completely covered with Buddhist murals depicting scenes from Buddha\'s life. The largest cave contains 56 statues, with a massive 15-meter reclining Buddha carved from solid rock. The temperature inside the caves remains cool, providing respite from the tropical heat.', 'image' => 'assets/images/temple.jpg'],
          ['title' => 'Anuradhapura - The Ancient Capital', 'text' => 'Walking among the ruins of Anuradhapura is like stepping back 2,000 years in time. The massive dagobas (stupas) rise above the landscape, testament to the engineering prowess of ancient Sri Lankan civilization. The Ruwanwelisaya stupa, built in 140 BCE, stands 103 meters tall and is still an active pilgrimage site. The sacred Bodhi tree, grown from a cutting of the tree under which Buddha attained enlightenment, is over 2,300 years old, making it the oldest historically documented tree in the world.', 'image' => 'assets/images/cultural.png'],
          ['title' => 'Polonnaruwa\'s Royal Glory', 'text' => 'Polonnaruwa served as Sri Lanka\'s capital from the 11th to 13th centuries and represents the peak of Sinhalese architectural achievement. The Gal Vihara features four massive Buddha statues carved from a single granite wall, including a 7-meter standing Buddha and a 14-meter reclining Buddha. The detail and serenity captured in stone is breathtaking. The ancient city also includes elaborate palace ruins, irrigation systems, and the unique Vatadage circular relic house.', 'image' => 'assets/images/temple.jpg'],
          ['title' => 'Mihintale - Where Buddhism Began', 'text' => 'Mihintale is considered the birthplace of Buddhism in Sri Lanka. In 247 BCE, Buddhist monk Mahinda met King Devanampiya Tissa here and converted him to Buddhism. Climbing the 1,840 steps to the summit is a pilgrimage in itself. At the top, ancient stupas, caves, and temples dot the mountainside, offering stunning panoramic views. The site becomes especially magical during full moon nights when thousands of pilgrims climb by candlelight.', 'image' => 'assets/images/cultural.png']
        ]
      ]
    ],
    4 => [
      'title' => 'Hiking Through Sri Lanka\'s Tea Country',
      'subtitle' => 'Exploring the misty highlands and verdant tea plantations of Nuwara Eliya',
      'author' => 'Mountain Trekker',
      'role' => 'Hiking Enthusiast & Tea Connoisseur',
      'date' => 'April 10, 2024',
      'readTime' => '15 min read',
      'views' => '3.2k views',
      'hero' => 'assets/images/tea.png',
      'images' => [
        ['src' => 'assets/images/tea.png', 'alt' => 'Tea plantations'],
        ['src' => 'assets/images/tea.png', 'alt' => 'Tea picking']
      ],
      'content' => [
        'intro' => 'Sri Lanka\'s hill country is synonymous with tea. The rolling plantations, cool climate, and colonial-era bungalows create a landscape that feels worlds away from the tropical beaches below. Our trek through these highlands was an unforgettable experience. Ceylon tea, as Sri Lankan tea is known worldwide, has been grown here since the 1860s, and the misty mountains between 1,000 and 2,500 meters elevation provide perfect conditions for producing some of the world\'s finest tea.',
        'sections' => [
          ['title' => 'Nuwara Eliya: Little England', 'text' => 'Often called "Little England," Nuwara Eliya served as our base. The town\'s colonial architecture, including the Hill Club and Grand Hotel, transports you to Victorian England. The cool weather - temperatures can drop to 10°C at night - was a welcome change. We set out early to hike through the surrounding tea estates, where the morning mist created an almost mystical atmosphere. The manicured tea bushes carpeted the hillsides in endless rows of vibrant green.', 'image' => 'assets/images/tea.png'],
          ['title' => 'Among the Tea Pickers', 'text' => 'Meeting the tea pluckers was a highlight. These skilled women, many of Tamil descent whose ancestors came from India during British rule, work from dawn to dusk picking only the finest two leaves and a bud. They can pick up to 20 kilograms per day, moving swiftly along the rows with baskets strapped to their backs. Their dexterity and speed is remarkable - what takes them seconds would take us minutes. We tried our hand at tea picking and gained instant appreciation for their skill.', 'image' => 'assets/images/tea.png'],
          ['title' => 'Inside the Tea Factory', 'text' => 'We stopped at Pedro Tea Estate, a working tea factory where we learned the entire process from leaf to cup. The withering process removes moisture from fresh leaves in large troughs with fans. Then comes rolling, which breaks the leaves and releases enzymes for oxidation. The fermentation room smells incredible - fruity and slightly sweet. Finally, the leaves are dried in massive ovens. Watching skilled workers sort and grade the tea by hand was mesmerizing. We participated in a tasting session, learning to identify notes of muscatel, chocolate, and citrus in different grades.', 'image' => 'assets/images/tea.png'],
          ['title' => 'Hiking to World\'s End', 'text' => 'The trek to World\'s End in Horton Plains National Park starts before sunrise. The 9-kilometer circular trail takes you through cloud forests and grasslands to a sheer 880-meter cliff drop. On clear mornings, you can see all the way to the southern coast. The trail also passes Baker\'s Falls, a beautiful 20-meter waterfall. The endemic flora and fauna here, including the elusive leopard, make it a biodiversity hotspot.', 'image' => 'assets/images/tea.png']
        ]
      ]
    ],
    5 => [
      'title' => 'Historic Galle Fort: A Dutch Colonial Legacy',
      'subtitle' => 'Walking through centuries of history in Sri Lanka\'s best-preserved colonial fortress',
      'author' => 'Heritage Walker',
      'role' => 'Architecture Historian & Travel Guide',
      'date' => 'March 20, 2024',
      'readTime' => '11 min read',
      'views' => '1.5k views',
      'hero' => 'assets/images/galle fort.jpg',
      'images' => [
        ['src' => 'assets/images/galle fort.jpg', 'alt' => 'Galle Fort walls']
      ],
      'content' => [
        'intro' => 'Galle Fort is a UNESCO World Heritage Site that beautifully blends European architecture with South Asian traditions. Built by the Portuguese in 1588 and fortified by the Dutch in the 17th century, this 36-hectare fort remains one of Asia\'s finest examples of a fortified city built by Europeans. Walking through its cobbled streets is like traveling back in time.',
        'sections' => [
          ['title' => 'Walking the Ramparts', 'text' => 'The best way to experience Galle Fort is by walking along its massive ramparts at sunset. The 3-kilometer walk offers views of the Indian Ocean crashing against the 12-meter thick walls. Cricket matches take place on the grassy areas atop the walls. The old lighthouse, built in 1939, stands as a sentinel at Flag Rock. Watching the sunset from here while locals jump off the rocks into the sea below is magical.', 'image' => 'assets/images/galle fort.jpg'],
          ['title' => 'Dutch Reformed Church', 'text' => 'Inside the fort, the Dutch Reformed Church dates back to 1755 and showcases beautiful Dutch colonial architecture. Its floor is paved with tombstones of Dutch colonials - each telling a story of lives lived far from home. The church\'s pipe organ, brought from Holland, is one of the oldest in Asia and still functions. The thick walls keep the interior remarkably cool.', 'image' => 'assets/images/galle fort.jpg'],
          ['title' => 'Colonial Architecture', 'text' => 'The fort\'s grid of narrow streets is lined with colonial-era mansions, many converted into boutique hotels and restaurants. Church Street and Pedlar Street showcase the best examples of Dutch architecture with their distinctive gables. The National Maritime Museum occupies a Dutch warehouse and displays artifacts from shipwrecks. Despite the tourist influx, about 4,000 people still live within the fort walls.', 'image' => 'assets/images/galle fort.jpg'],
          ['title' => 'The Japanese Peace Pagoda', 'text' => 'Just outside the fort, the gleaming white Japanese Peace Pagoda sits on Rumassala Hill. Built by Japanese monks, it offers panoramic views of Galle and the coastline. The walk up through jungle makes it worth the visit. From here, you can truly appreciate the fort\'s strategic position and massive scale.', 'image' => 'assets/images/galle fort.jpg']
        ]
      ]
    ],
    6 => [
      'title' => 'Traveling Sri Lanka by Tuk-Tuk: A Local\'s Guide',
      'subtitle' => 'Experiencing the island nation through its most iconic mode of transport',
      'author' => 'Road Adventurer',
      'role' => 'Local Transport Expert & Travel Blogger',
      'date' => 'April 5, 2024',
      'readTime' => '14 min read',
      'views' => '2.7k views',
      'hero' => 'assets/images/hill.png',
      'images' => [
        ['src' => 'assets/images/hill.png', 'alt' => 'Mountain journey']
      ],
      'content' => [
        'intro' => 'The three-wheeled tuk-tuk is more than just transportation in Sri Lanka - it\'s a cultural icon and an experience in itself. From navigating busy city streets to traversing winding mountain roads, our week-long tuk-tuk adventure showed us a side of Sri Lanka that tour buses never see. These colorful, noisy vehicles offer an authentic way to experience the island.',
        'sections' => [
          ['title' => 'Hiring Your Tuk-Tuk', 'text' => 'We hired our tuk-tuk and driver, Chaminda, for the week through a local agency. This gave us incredible flexibility while benefiting from his decades of local knowledge. He knew every shortcut, every authentic restaurant, every hidden waterfall, and every perfect photo spot. His colorfully decorated tuk-tuk, complete with Buddha statues and speakers playing Sinhala pop music, became our home on wheels. The cost was about $50 per day including fuel.', 'image' => 'assets/images/hill.png'],
          ['title' => 'Through the Hill Country', 'text' => 'The tuk-tuk handled the winding mountain roads to Ella surprisingly well. Yes, it was slower than a car, but that allowed us to truly absorb the stunning scenery. We stopped at roadside fruit stands, tea stalls, and viewpoints whenever we wanted. Going uphill, other tuk-tuks would honk in solidarity. The open sides meant we could lean out for photos, wave to children, and feel every temperature change.', 'image' => 'assets/images/hill.png'],
          ['title' => 'Coastal Adventures', 'text' => 'The coastal road from Galle to Tangalle was magical in a tuk-tuk. We could smell the ocean constantly, feel the sea breeze, and stop at any beach that caught our eye. Chaminda took us to his friend\'s beachside restaurant where we had the freshest seafood curry. He also showed us secret surf spots and explained the best times to visit different beaches.', 'image' => 'assets/images/hill.png'],
          ['title' => 'Cultural Immersion', 'text' => 'Traveling by tuk-tuk forced interactions that we might have missed. When we broke down (yes, it happened!), locals immediately stopped to help. The mechanic fixed our issue with a piece of wire and wouldn\'t accept payment. At fuel stops, Chaminda introduced us to other drivers. The tuk-tuk community is tight-knit, and being part of it gave us incredible insights into Sri Lankan culture.', 'image' => 'assets/images/hill.png']
        ]
      ]
    ],
    7 => [
      'title' => 'Best Surfing Spots on the Southern Coast',
      'subtitle' => 'Riding the waves at Sri Lanka\'s premier surf destinations',
      'author' => 'Wave Rider',
      'role' => 'Professional Surfer & Instructor',
      'date' => 'March 12, 2024',
      'readTime' => '12 min read',
      'views' => '1.9k views',
      'hero' => 'assets/images/surfing.jpg',
      'images' => [
        ['src' => 'assets/images/surfing.jpg', 'alt' => 'Surfing waves'],
        ['src' => 'assets/images/hikkaduwa.png', 'alt' => 'Beach surf spot']
      ],
      'content' => [
        'intro' => 'Sri Lanka\'s southern coast offers consistent waves, warm water (27°C year-round), and a laid-back surf culture that\'s hard to beat. Whether you\'re a complete beginner or experienced surfer, there\'s a break for everyone. We spent two weeks exploring the best surf spots from Weligama to Mirissa, catching dawn sessions and sunset surfs.',
        'sections' => [
          ['title' => 'Weligama - Perfect for Beginners', 'text' => 'Weligama Bay is the ideal place to learn surfing. The crescent-shaped bay creates gentle, consistent waves perfect for first-timers. The sandy bottom provides a safe learning environment. Numerous surf schools line the beach, offering lessons from $15-25 including board rental. We saw complete beginners standing up on their first day. The bay\'s protected nature means waves rarely exceed 1.5 meters. Best time: November to April.', 'image' => 'assets/images/surfing.jpg'],
          ['title' => 'Mirissa - Intermediate Paradise', 'text' => 'A 10-minute tuk-tuk ride from Weligama brings you to Mirissa, where waves get more powerful. The main break is a reef break that produces clean, well-formed waves when the swell direction is right. Early morning sessions (before 7 AM) offer the best conditions before onshore winds pick up. The local surf community is welcoming. After surfing, the beach cafes serve excellent fresh juices and rice and curry.', 'image' => 'assets/images/surfingmirissa.png'],
          ['title' => 'Hikkaduwa - The Reef Break', 'text' => 'Hikkaduwa was Sri Lanka\'s first surf town and still has a raw, authentic vibe. The main reef break can get hollow and powerful, producing barrels on good swells. This is for experienced surfers only - the coral reef is shallow and unforgiving. The local surf shops have been running since the 1970s. The town has a more bohemian atmosphere with beach parties and reggae music.', 'image' => 'assets/images/surfinghikkaduwa.png'],
          ['title' => 'Hidden Gems', 'text' => 'Between Weligama and Hikkaduwa lie several lesser-known spots. Kabalana Beach offers a fun beach break with peaks spreading out the crowd. The waves here work on most swells and tides, making it reliable. Nearby Ahangama has a powerful right-hand point break that can produce long rides on big swells. These spots require local knowledge to find safely.', 'image' => 'assets/images/surfingahangama.png']
        ]
      ]
    ],
    8 => [
      'title' => 'Sri Lankan Street Food: A Culinary Adventure',
      'subtitle' => 'Tasting our way through the island\'s most delicious roadside delicacies',
      'author' => 'Food Explorer',
      'role' => 'Culinary Writer & Street Food Enthusiast',
      'date' => 'April 18, 2024',
      'readTime' => '13 min read',
      'views' => '3.5k views',
      'hero' => 'assets/images/street food.jpg',
      'images' => [
        ['src' => 'assets/images/street food.jpg', 'alt' => 'Street food stalls']
      ],
      'content' => [
        'intro' => 'Sri Lankan street food is a symphony of flavors - spicy, sweet, savory, and tangy all at once. From hoppers to kottu roti, the street food scene offers authentic tastes that rival any restaurant. Our gastronomic journey took us from Colombo to Galle, sampling everything from roadside carts to bustling night markets.',
        'sections' => [
          ['title' => 'Kottu Roti - The Street Food King', 'text' => 'The rhythmic clanging of metal blades chopping roti is the soundtrack of Sri Lankan nights. Kottu roti - shredded flatbread stir-fried with vegetables, eggs, and meat - is comfort food at its finest. Each vendor has their own recipe and technique. We found the best kottu at a small cart in Colombo where the cook had been perfecting his craft for 30 years. The key is the right balance of spices and the theatrical chopping technique that mixes everything perfectly.', 'image' => 'assets/images/street food.jpg'],
          ['title' => 'Hoppers for Breakfast', 'text' => 'Egg hoppers are the perfect breakfast. These bowl-shaped pancakes with a crispy edge and soft center are cooked with an egg in the middle. Served with spicy sambol and coconut chutney, they\'re addictively delicious. String hoppers (steamed rice noodles) are another variation, often served with dhal curry. We watched skilled vendors pour the batter into small wok-like pans, swirling it to create the perfect bowl shape.', 'image' => 'assets/images/street food.jpg'],
          ['title' => 'Wade and Short Eats', 'text' => 'Wade (savory lentil donuts) and short eats (small savory snacks) are everywhere in Sri Lanka. Wade are made from ground lentils mixed with onions, curry leaves, and chilies, then deep-fried until crispy. They\'re best eaten fresh and hot with coconut chutney. Short eats include fish cutlets, samosas, and patties - perfect afternoon snacks with Ceylon tea. These are the soul food of Sri Lanka, found in every bakery and tea shop.', 'image' => 'assets/images/street food.jpg'],
          ['title' => 'Fresh Tropical Fruits', 'text' => 'The fruit vendors are artists, cutting up pineapples, mangoes, papayas, and rambutans with incredible speed and precision. For just a dollar, you get a bag of mixed fresh fruit with salt and chili powder - a perfect refreshing snack. The king coconut stands are everywhere, offering tender coconut water straight from the nut. It\'s the most natural, refreshing drink imaginable on a hot day.', 'image' => 'assets/images/street food.jpg']
        ]
      ]
    ],
    9 => [
      'title' => 'Ella: The Mountain Paradise of Sri Lanka',
      'subtitle' => 'Discovering waterfalls, train rides, and breathtaking viewpoints',
      'author' => 'Mountain Explorer',
      'role' => 'Adventure Travel Writer',
      'date' => 'April 22, 2024',
      'readTime' => '15 min read',
      'views' => '4.2k views',
      'hero' => 'assets/images/ella.jpg',
      'images' => [
        ['src' => 'assets/images/ella.jpg', 'alt' => 'Ella landscapes'],
        ['src' => 'assets/images/hiking.jpg', 'alt' => 'Mountain views']
      ],
      'content' => [
        'intro' => 'Ella is a small mountain town that has captured the hearts of travelers worldwide. Nestled in the highlands at 1,041 meters elevation, it offers stunning hikes, the famous Nine Arch Bridge, and some of the most Instagram-worthy views in Sri Lanka. We spent five days exploring every corner of this magical place where mist-covered mountains meet endless tea plantations.',
        'sections' => [
          ['title' => 'Ella Rock Hike', 'text' => 'The hike to Ella Rock begins before sunrise - around 5 AM to catch the dawn light. The 8km trek through tea plantations and forest is challenging but incredibly rewarding. The trail winds through working tea estates where early-morning pickers greet you. Standing on the 1,140-meter summit as the sun rises over the valley below is a moment of pure magic. On clear mornings, you can see all the way to the southern coast.', 'image' => 'assets/images/hiking.jpg'],
          ['title' => 'Nine Arch Bridge', 'text' => 'This architectural marvel from the colonial era is best visited early morning (around 6-7 AM) or late afternoon (4-5 PM). The bridge was built entirely from stone and cement, without any steel, during British rule. Watching the blue train slowly cross the 91-meter bridge, framed by jungle-covered hills, is quintessentially Sri Lankan. We hiked down into the valley below for the best photo angles. The train passes by around 9 AM and 12 PM daily.', 'image' => 'assets/images/ella.jpg'],
          ['title' => 'Little Adam\'s Peak', 'text' => 'An easier hike than Ella Rock, Little Adam\'s Peak offers spectacular 360-degree views for relatively little effort. The 40-minute climb through tea plantations is gentle enough for most fitness levels. At the top, prayer flags flutter in the breeze, and you\'re surrounded by mountain ranges in every direction. It\'s especially beautiful at sunset when the hills glow golden. The trail is well-marked and perfect for a late afternoon adventure.', 'image' => 'assets/images/hiking.jpg'],
          ['title' => 'The Train Journey', 'text' => 'The train ride from Kandy to Ella is considered one of the most scenic in the world. The journey takes about 7 hours, winding through tea country with breathtaking views around every bend. Locals hang out of the open doors, and the relaxed atmosphere makes it feel like a mobile party. Book second or third class for the authentic experience - you can sit in the doorways with your legs dangling out (carefully!). The final stretch into Ella, through tunnels and over viaducts, is absolutely spectacular.', 'image' => 'assets/images/ella.jpg']
        ]
      ]
    ],
    10 => [
      'title' => 'Climbing Sigiriya Rock: The Lion\'s Fortress',
      'subtitle' => 'Ascending the ancient rock fortress that defies imagination',
      'author' => 'Ancient History',
      'role' => 'Archaeological Writer & Historian',
      'date' => 'March 25, 2024',
      'readTime' => '13 min read',
      'views' => '3.8k views',
      'hero' => 'assets/images/sigiriya.jpg',
      'images' => [
        ['src' => 'assets/images/sigiriya.jpg', 'alt' => 'Sigiriya Rock'],
        ['src' => 'assets/images/historical.png', 'alt' => 'Ancient fortress']
      ],
      'content' => [
        'intro' => 'Sigiriya is one of Sri Lanka\'s most iconic landmarks and a UNESCO World Heritage Site. This 5th-century rock fortress rises 200 meters above the surrounding jungle. Climbing its steep steps is a journey through history, from mirror walls to ancient frescoes, culminating in the ruins of a sky palace. Built by King Kashyapa between 477-495 AD, it represents an extraordinary feat of ancient engineering and urban planning.',
        'sections' => [
          ['title' => 'The Water Gardens', 'text' => 'Your ascent begins through the remarkably sophisticated water gardens at the base. These 5th-century hydraulic systems still function during the rainy season. The symmetrical layout includes pools, fountains, and channels that showcase advanced engineering. The boulder gardens and terraced gardens that follow demonstrate the ancient landscape architects\' skill. Starting early (7 AM opening) helps you beat both the heat and the crowds.', 'image' => 'assets/images/sigiriya.jpg'],
          ['title' => 'The Ancient Frescoes', 'text' => 'Halfway up the rock, accessed via a spiral staircase, are the famous Sigiriya frescoes. Tucked into a protected pocket in the rock face, these 1,500-year-old paintings of celestial maidens have retained their vibrant colors remarkably well. Only 22 frescoes remain of the original 500 that once covered the western face. Their beauty, artistry, and the mystery of who they represent continue to fascinate scholars. Photography is strictly prohibited to preserve them.', 'image' => 'assets/images/historical.png'],
          ['title' => 'The Mirror Wall', 'text' => 'Beyond the frescoes, you\'ll pass the Mirror Wall - originally so highly polished that the king could see his reflection. Now it\'s covered in ancient graffiti, verses written by visitors from the 8th to 14th centuries. These inscriptions provide valuable historical insights. The exposed pathway here can be daunting for those with vertigo, but handrails provide security. The views across the surrounding jungle are already spectacular.', 'image' => 'assets/images/sigiriya.jpg'],
          ['title' => 'The Lion\'s Gate and Summit', 'text' => 'The final ascent is through the Lion\'s Gate, where massive lion paws carved from rock flank the entrance. Originally, a full lion\'s head formed the gateway - visitors literally entered through the lion\'s mouth. The steep steps (about 1,200 total) are narrow and exposed, but worth every step. The summit reveals extensive palace ruins covering nearly 2 hectares, including a throne carved from rock, cisterns that collected rainwater, and the foundation of structures. The 360-degree views are breathtaking - you can see for miles across jungle and plains, making it clear why this location was chosen as a fortress.', 'image' => 'assets/images/historical.png']
        ]
      ]
    ]
  ];
  
  $blog = $blogs[$blogId] ?? $blogs[1];
  ?>

  <div class="page-containerr">

    <div class="content">
      <!-- Blog Container -->
      <div class="vlog-container">
        <!-- Blog Header -->
        <div class="vlog-header">
          <div class="vlog-meta">
            <div class="author-info">
              <div class="author-avatar">
                <img src="<?= ROOT ?>/assets/images/profile.jpg" alt="<?php echo htmlspecialchars($blog['author']); ?>">
              </div>
              <div class="author-details">
                <h3><?php echo htmlspecialchars($blog['author']); ?></h3>
                <p><?php echo htmlspecialchars($blog['role']); ?></p>
              </div>
            </div>
            <div class="vlog-stats">
              <div class="stat">📅 <?php echo htmlspecialchars($blog['date']); ?></div>
              <div class="stat">⏱️ <?php echo htmlspecialchars($blog['readTime']); ?></div>
              <div class="stat">👁️ <?php echo htmlspecialchars($blog['views']); ?></div>
            </div>
          </div>
          <h1 class="vlog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
          <p class="vlog-subtitle"><?php echo htmlspecialchars($blog['subtitle']); ?></p>
        </div>

        <!-- Blog Content -->
        <div class="vlog-content">
          <div class="vlog-hero">
            <img src="<?php echo htmlspecialchars($blog['hero']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
          </div>

          <div class="vlog-body">
            <p><?php echo htmlspecialchars($blog['content']['intro']); ?></p>

            <?php foreach ($blog['content']['sections'] as $section): ?>
              <h2><?php echo htmlspecialchars($section['title']); ?></h2>
              <p><?php echo htmlspecialchars($section['text']); ?></p>
            <?php endforeach; ?>

            <h2>Tips for Your Visit</h2>
            <ul>
              <li>Plan your visit during the best season for optimal experience</li>
              <li>Hire a local guide for insider knowledge</li>
              <li>Bring appropriate gear and clothing</li>
              <li>Respect local customs and environment</li>
              <li>Take time to interact with locals and learn their stories</li>
            </ul>

            <p>Sri Lanka continues to amaze travelers with its diversity and beauty. Each destination offers unique experiences that create lasting memories.</p>
          </div>
        </div>

        <!-- Blog Actions -->
        <div class="vlog-actions">
          <button class="btn-action btn-approve" onclick="approveVlog()">
            <span>✓</span> Approve Blog
          </button>
          <button class="btn-action btn-reject" onclick="rejectVlog()">
            <span>✗</span> Reject Blog
          </button>
          <button class="btn-action btn-back" onclick="window.location.href='content.php';">
            <span>←</span> Back to List
          </button>
        </div>
      </div>

      <!-- Related Blogs -->
      <div class="related-vlogs">
        <h3 class="section-title">Related Blogs</h3>
        <div class="related-grid">
          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/cultural.png" alt="Temple visit">
            </div>
            <div class="related-info">
              <h4>Ancient Temples of the Cultural Triangle</h4>
              <div class="related-meta">
                <span>⏱️ 6 min read</span>
                <span>👁️ 1.8k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/tea.png" alt="Tea plantation">
            </div>
            <div class="related-info">
              <h4>Hiking Through Sri Lanka's Tea Country</h4>
              <div class="related-meta">
                <span>⏱️ 10 min read</span>
                <span>👁️ 3.2k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/wild.png" alt="Wildlife">
            </div>
            <div class="related-info">
              <h4>Wildlife Encounters in Yala National Park</h4>
              <div class="related-meta">
                <span>⏱️ 7 min read</span>
                <span>👁️ 2.1k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/cultural.png" alt="Galle Fort">
            </div>
            <div class="related-info">
              <h4>Historic Galle Fort: A Dutch Colonial Legacy</h4>
              <div class="related-meta">
                <span>⏱️ 5 min read</span>
                <span>👁️ 1.5k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/tuktuk.png" alt="Tuk-tuk">
            </div>
            <div class="related-info">
              <h4>Traveling Sri Lanka by Tuk-Tuk: A Local's Guide</h4>
              <div class="related-meta">
                <span>⏱️ 9 min read</span>
                <span>👁️ 2.7k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/coastal.png" alt="Surfing">
            </div>
            <div class="related-info">
              <h4>Best Surfing Spots on the Southern Coast</h4>
              <div class="related-meta">
                <span>⏱️ 6 min read</span>
                <span>👁️ 1.9k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/tea.png" alt="Food">
            </div>
            <div class="related-info">
              <h4>Sri Lankan Street Food: A Culinary Adventure</h4>
              <div class="related-meta">
                <span>⏱️ 8 min read</span>
                <span>👁️ 3.5k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/wild.png" alt="Ella">
            </div>
            <div class="related-info">
              <h4>Ella: The Mountain Paradise of Sri Lanka</h4>
              <div class="related-meta">
                <span>⏱️ 11 min read</span>
                <span>👁️ 4.2k views</span>
              </div>
            </div>
          </div>

          <div class="related-card">
            <div class="related-image">
              <img src="<?= ROOT ?>/assets/images/cultural.png" alt="Sigiriya">
            </div>
            <div class="related-info">
              <h4>Climbing Sigiriya Rock: The Lion's Fortress</h4>
              <div class="related-meta">
                <span>⏱️ 7 min read</span>
                <span>👁️ 3.8k views</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function approveVlog() {
      if (confirm('Approve this blog for publication?')) {
        alert('Blog approved successfully!');
        // Implement actual approval functionality
      }
    }

    function rejectVlog() {
      const reason = prompt('Please enter reason for rejection:');
      if (reason) {
        alert(`Blog rejected. Reason: ${reason}`);
        // Implement actual rejection functionality
      }
    }

    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>
