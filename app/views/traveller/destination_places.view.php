<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['category']->name ?? $data['category']->title ?? 'Destination') ?> - TravelMate</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Traveller/usermain.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Traveller/beach.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            position: relative;
            height: 350px;
            overflow: hidden;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-image: url('<?= ROOT ?><?= $data['category']->image ?? '/assets/images/default-destination.jpg' ?>');
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-content {
            text-align: center;
            color: white;
            padding: 20px;
        }
        
        .hero-content h1 {
            font-size: 48px;
            margin: 0 0 15px 0;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }
        
        .hero-content p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.95;
        }
        
        .breadcrumb-nav {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        
        .breadcrumb-nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 25px;
            transition: background 0.3s;
        }
        
        .breadcrumb-nav a:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .places-section {
            padding: 50px 0;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-header h2 {
            font-size: 32px;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .section-header p {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .places-count {
            display: inline-block;
            background: #1abc5b;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 15px;
        }
        
        .places-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .place-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .place-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .place-card .card-image {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        
        .place-card .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .place-card:hover .card-image img {
            transform: scale(1.1);
        }
        
        .place-card .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .place-card:hover .card-overlay {
            opacity: 1;
        }
        
        .explore-btn {
            background: #1abc5b;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .explore-btn:hover {
            background: #16a34a;
            transform: scale(1.05);
        }
        
        .place-card .card-content {
            padding: 20px;
        }
        
        .place-card .card-content h3 {
            font-size: 20px;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .place-card .card-content p {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
            grid-column: 1 / -1;
        }
        
        .empty-state i {
            font-size: 60px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .empty-state p {
            color: #7f8c8d;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 32px;
            }
            
            .hero-section {
                height: 280px;
            }
            
            .places-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/../traveller/header.view.php'; ?>

<?php 
$category = $data['category'];
$places = $data['places'];
$categoryName = $category->name ?? $category->title ?? 'Destination';
$categoryDesc = $category->description ?? '';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="breadcrumb-nav">
        <a href="<?= ROOT ?>/homet">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>
    <div class="hero-background"></div>
    <div class="hero-overlay">
        <div class="hero-content">
            <h1><?= htmlspecialchars($categoryName) ?></h1>
            <p><?= htmlspecialchars($categoryDesc) ?></p>
        </div>
    </div>
</section>

<!-- Places Section -->
<section class="places-section">
    <div class="container">
        <div class="section-header">
            <h2>Explore <?= htmlspecialchars($categoryName) ?> Places</h2>
            <p>Discover amazing destinations in this category</p>
            <span class="places-count">
                <i class="fas fa-map-marker-alt"></i> <?= count($places) ?> Places
            </span>
        </div>

        <div class="places-grid">
            <?php if (!empty($places)): ?>
                <?php foreach ($places as $place): ?>
                    <?php 
                    $placeName = $place->name ?? $place->title;
                    $placeDesc = $place->description ?? '';
                    $placeImage = $place->image ?? '/assets/images/default-place.jpg';
                    ?>
                    <div class="place-card">
                        <div class="card-image">
                            <img src="<?= ROOT ?><?= $placeImage ?>" 
                                 alt="<?= htmlspecialchars($placeName) ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/default-place.jpg'">
                            <div class="card-overlay">
                                <a href="<?= ROOT ?>/beachdetail?place=<?= $place->id ?>" class="explore-btn">Explore</a>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($placeName) ?></h3>
                            <p><?= htmlspecialchars(substr($placeDesc, 0, 150)) ?><?= strlen($placeDesc) > 150 ? '...' : '' ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>No Places Found</h3>
                    <p>There are no places in this category yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../traveller/footer.view.php'; ?>

</body>
</html>
