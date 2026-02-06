<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Ensure logs directory exists
$logsDir = __DIR__ . '/../logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0777, true);
}
ini_set('error_log', $logsDir . '/php_error.log');

// Log all requests for debugging
error_log("=== NEW REQUEST ===");
error_log("METHOD: " . $_SERVER['REQUEST_METHOD']);
error_log("URI: " . $_SERVER['REQUEST_URI']);
error_log("GET: " . print_r($_GET, true));
error_log("POST: " . print_r($_POST, true));

require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/BookingController.php';
require_once '../app/controllers/PreferenceController.php';
require_once '../app/controllers/VehicleController.php';
require_once '../app/controllers/DestinationController.php';
require_once '../app/controllers/AccommodationController.php';

use App\Controllers\AuthController;
use App\Controllers\BookingController;
use App\Controllers\PreferenceController;
use App\Controllers\VehicleController;
use App\Controllers\DestinationController;
use App\Controllers\AccommodationController;

session_start();

// Get request URI from .htaccess rewrite or from REQUEST_URI
if (isset($_GET['url'])) {
    // Coming from .htaccess rewrite rule: index.php?url=$1
    $requestUri = '/' . trim($_GET['url'], '/');
} else {
    // Direct access - parse the REQUEST_URI
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove base path if it exists
    $basePath = '/TravelMate/public';
    if (strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    } else {
        // also support URLs that point to /TravelMate/... (without /public)
        $altBase = '/TravelMate';
        if (strpos($requestUri, $altBase) === 0) {
            $requestUri = substr($requestUri, strlen($altBase));
        }
    }
}

// Ensure URI starts with /
if (empty($requestUri) || $requestUri === '/') {
    $requestUri = '/home';
}

// Get page name for simple routes
$page = basename($requestUri);


error_log("Processed URI: $requestUri");
error_log("Page: $page");

// ========== VEHICLE API ROUTES (MUST BE BEFORE PAGE ROUTES) ==========
if ($requestUri === '/api/vehicle/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Vehicle Create");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->create();
    exit; // Important!
} 

elseif ($requestUri === '/api/vehicle/list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Vehicle List");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->listByUser();
    exit;
} 

elseif ($requestUri === '/api/vehicle/listAll' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Vehicle List All");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->listAll();
    exit;
} 

elseif ($requestUri === '/api/vehicle/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Vehicle Get");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->get();
    exit;
} 

elseif ($requestUri === '/api/vehicle/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Vehicle Update");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->update();
    exit;
} 

elseif ($requestUri === '/api/vehicle/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Vehicle Delete");
    require_once __DIR__ . '/../app/controllers/VehicleController.php';
    $vehicleController = new App\Controllers\VehicleController();
    $vehicleController->delete();
    exit;
}

// Accommodation API routes
elseif ($requestUri === '/api/accommodation/upload-temp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation uploadTemp");
    $controller = new AccommodationController();
    $controller->uploadTemp();
    exit;
}
elseif ($requestUri === '/api/accommodation/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation Create");
    $controller = new AccommodationController();
    $controller->create();
    exit;
} 
elseif ($requestUri === '/api/accommodation/list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Accommodation List");
    $controller = new AccommodationController();
    $controller->listByUser();
    exit;
} 
elseif ($requestUri === '/api/accommodation/listAll' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Accommodation List All");
    $controller = new AccommodationController();
    $controller->listAll();
    exit;
} 
elseif ($requestUri === '/api/accommodation/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    error_log(">>> Routing to Accommodation Get");
    $controller = new AccommodationController();
    $controller->get();
    exit;
} 
elseif ($requestUri === '/api/accommodation/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation Update");
    $controller = new AccommodationController();
    $controller->update();
    exit;
} 
elseif ($requestUri === '/api/accommodation/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation Delete");
    $controller = new AccommodationController();
    $controller->delete();
    exit;
}

// Accommodation form page handlers
elseif ($requestUri === '/selectPropertyType' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to selectPropertyType");
    $controller = new AccommodationController();
    $controller->selectPropertyType();
    exit;
}
elseif ($requestUri === '/saveFeatures' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation saveFeatures");
    $controller = new AccommodationController();
    $controller->saveFeatures();
    exit;
}
elseif ($requestUri === '/saveDetails' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation saveDetails");
    $controller = new AccommodationController();
    $controller->saveDetails();
    exit;
}
elseif ($requestUri === '/savePhoto' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation savePhoto");
    $controller = new AccommodationController();
    $controller->savePhoto();
    exit;
}
elseif ($requestUri === '/saveAccommodation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(">>> Routing to Accommodation saveAccommodation");
    $controller = new AccommodationController();
    $controller->saveAccommodation();
    exit;
}

// Accommodation page routes
elseif (strpos($requestUri, '/accommodation/') === 0) {
    $page = substr($requestUri, strlen('/accommodation/'));
    $viewFile = "../app/views/accommodation/{$page}.view.php";
    if (file_exists($viewFile)) {
        require_once $viewFile;
        exit;
    }
}
elseif (preg_match('#^/deleteAccommodation/(\d+)$#', $requestUri, $matches)) {
    $id = $matches[1];
    $controller = new AccommodationController();
    $_POST['id'] = $id;
    $controller->delete();
    exit;
}
elseif (preg_match('#^/viewProperty/(\d+)$#', $requestUri, $matches)) {
    $accommodationId = $matches[1];
    $viewFile = __DIR__ . '/../app/views/accommodation/viewProperty.view.php';
    if (file_exists($viewFile)) {
        require_once $viewFile;
        exit;
    }
}


// ========== BLOG ROUTES (TRAVELER) ==========
elseif ($requestUri === '/traveller/blog/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->create();
    exit;
}
elseif ($requestUri === '/api/blog/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->store();
    exit;
}
elseif ($requestUri === '/traveller/myblogs' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->myBlogs();
    exit;
}
elseif ($requestUri === '/traveller/blog/edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->edit();
    exit;
}
elseif ($requestUri === '/api/blog/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->update();
    exit;
}
elseif ($requestUri === '/api/blog/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->delete();
    exit;
}
elseif ($requestUri === '/traveller/blog/view' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/BlogController.php';
    $blogController = new BlogController();
    $blogController->view();
    exit;
}

// ========== BLOG ROUTES (ADMIN) ==========
elseif ($requestUri === '/admin/blogs' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->moderationQueue();
    exit;
}
elseif (strpos($requestUri, '/admin/blog/view') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->viewBlogDetail();
    exit;
}
elseif ($requestUri === '/api/admin/blog/approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->approve();
    exit;
}
elseif ($requestUri === '/api/admin/blog/reject' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->reject();
    exit;
}
elseif ($requestUri === '/api/admin/blog/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->deleteBlog();
    exit;
}
elseif ($requestUri === '/api/admin/blog/toggle-featured' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->toggleFeatured();
    exit;
}
elseif ($requestUri === '/api/admin/blog/bulk-approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->bulkApprove();
    exit;
}
elseif ($requestUri === '/api/admin/blog/bulk-reject' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->bulkReject();
    exit;
}

// ========== ADMIN USER API ROUTES ==========
elseif ($requestUri === '/api/admin/user/suspend' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->suspend();
    exit;
}
elseif ($requestUri === '/api/admin/user/activate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->activate();
    exit;
}
elseif ($requestUri === '/api/admin/user/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->delete();
    exit;
}
elseif ($requestUri === '/api/admin/user/stats' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->getStats();
    exit;
}
elseif ($requestUri === '/api/admin/user/search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->search();
    exit;
}

// ========== ADMIN LISTING API ROUTES ==========
elseif ($requestUri === '/api/admin/listing/approve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminListingController.php';
    $adminListingController = new AdminListingController();
    $adminListingController->approve();
    exit;
}
elseif ($requestUri === '/api/admin/listing/suspend' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminListingController.php';
    $adminListingController = new AdminListingController();
    $adminListingController->suspend();
    exit;
}
elseif ($requestUri === '/api/admin/listing/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminListingController.php';
    $adminListingController = new AdminListingController();
    $adminListingController->delete();
    exit;
}
elseif ($requestUri === '/api/admin/listing/details' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminListingController.php';
    $adminListingController = new AdminListingController();
    $adminListingController->getDetails();
    exit;
}

// ========== ADMIN NOTIFICATION API ROUTES ==========
elseif ($requestUri === '/api/admin/notification/mark-read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminNotificationController.php';
    $adminNotificationController = new AdminNotificationController();
    $adminNotificationController->markRead();
    exit;
}
elseif ($requestUri === '/api/admin/notification/mark-all-read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminNotificationController.php';
    $adminNotificationController = new AdminNotificationController();
    $adminNotificationController->markAllRead();
    exit;
}
elseif ($requestUri === '/api/admin/notification/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminNotificationController.php';
    $adminNotificationController = new AdminNotificationController();
    $adminNotificationController->delete();
    exit;
}
elseif ($requestUri === '/api/admin/notification/clear-all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminNotificationController.php';
    $adminNotificationController = new AdminNotificationController();
    $adminNotificationController->clearAll();
    exit;
}

// ========== ADMIN ANNOUNCEMENT API ROUTES ==========
elseif ($requestUri === '/api/admin/announcement/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->create();
    exit;
}
elseif ($requestUri === '/api/admin/announcement/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->update();
    exit;
}
elseif ($requestUri === '/api/admin/announcement/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->delete();
    exit;
}
elseif ($requestUri === '/api/admin/announcement/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->get();
    exit;
}
elseif ($requestUri === '/api/admin/announcement/toggle-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->toggleStatus();
    exit;
}

// ========== ADMIN DESTINATION ROUTES (Dynamic Category Management) ==========
// Main categories listing page
elseif ($requestUri === '/admin/destinations' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->index();
    exit;
}
// View category with its places
elseif (strpos($requestUri, '/admin/destinations/category') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->viewCategory();
    exit;
}

// Category API Routes
elseif ($requestUri === '/api/admin/destination/category/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->addCategory();
    exit;
}
elseif ($requestUri === '/api/admin/destination/category/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->updateCategory();
    exit;
}
elseif ($requestUri === '/api/admin/destination/category/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->deleteCategory();
    exit;
}
elseif ($requestUri === '/api/admin/destination/category/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->getCategory();
    exit;
}

// Place API Routes
elseif ($requestUri === '/api/admin/destination/place/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->addPlace();
    exit;
}
elseif ($requestUri === '/api/admin/destination/place/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->updatePlace();
    exit;
}
elseif ($requestUri === '/api/admin/destination/place/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->deletePlace();
    exit;
}
elseif ($requestUri === '/api/admin/destination/place/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->getPlace();
    exit;
}

// ========== ADMIN ACCOMMODATION MANAGEMENT ROUTES ==========
// Accommodation listings page
elseif ($requestUri === '/admin/accommodations' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminAccommodationController.php';
    $adminAccommodationController = new AdminAccommodationController();
    $adminAccommodationController->index();
    exit;
}
// View accommodation details
elseif (strpos($requestUri, '/admin/accommodations/view') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminAccommodationController.php';
    $adminAccommodationController = new AdminAccommodationController();
    $adminAccommodationController->view();
    exit;
}
// Delete accommodation (soft delete)
elseif ($requestUri === '/api/admin/accommodation/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminAccommodationController.php';
    $adminAccommodationController = new AdminAccommodationController();
    $adminAccommodationController->delete();
    exit;
}
// Get accommodation statistics
elseif ($requestUri === '/api/admin/accommodation/stats' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminAccommodationController.php';
    $adminAccommodationController = new AdminAccommodationController();
    $adminAccommodationController->getStats();
    exit;
}

// ========== ADMIN TRANSPORT / VEHICLE MANAGEMENT ROUTES ==========
// Vehicle listings page
elseif ($requestUri === '/admin/transport' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminTransportController.php';
    $adminTransportController = new AdminTransportController();
    $adminTransportController->index();
    exit;
}
// View vehicle details
elseif (strpos($requestUri, '/admin/transport/view') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminTransportController.php';
    $adminTransportController = new AdminTransportController();
    $adminTransportController->view();
    exit;
}
// Delete vehicle (soft delete)
elseif ($requestUri === '/api/admin/transport/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/AdminTransportController.php';
    $adminTransportController = new AdminTransportController();
    $adminTransportController->delete();
    exit;
}
// Get vehicle statistics
elseif ($requestUri === '/api/admin/transport/stats' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/controllers/AdminTransportController.php';
    $adminTransportController = new AdminTransportController();
    $adminTransportController->getStats();
    exit;
}

// ========== PUBLIC BLOG ROUTES ==========
elseif ($requestUri === '/blogs' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../app/models/Blog.php';
    $blogModel = new BlogModel();
    $blogs = $blogModel->getApprovedBlogs();
    $categories = $blogModel->getCategories();
    require_once '../app/views/public/blogs.view.php';
    exit;
}
elseif (preg_match('#^/blog/([a-z0-9-]+)$#', $requestUri, $matches)) {
    $slug = $matches[1];
    require_once '../app/models/Blog.php';
    $blogModel = new BlogModel();
    $blog = $blogModel->getBySlug($slug);
    
    if ($blog) {
        // Increment view count
        $blogModel->incrementViews($blog->id);
        
        // Get blog images and categories
        $images = $blogModel->getImages($blog->id);
        $categories = $blogModel->getCategories($blog->id);
        
        // Get related blogs (same location or category)
        $relatedBlogs = [];
        
        require_once '../app/views/public/singleBlog.view.php';
        exit;
    } else {
        http_response_code(404);
        echo "Blog not found";
        exit;
    }
}

// Destination API routes
elseif ($requestUri === '/api/destination/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->create();
    exit;
} elseif ($requestUri === '/api/destination/list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->list();
    exit;
} elseif ($requestUri === '/api/destination/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->get();
    exit;
} elseif ($requestUri === '/api/destination/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->update();
    exit;
} elseif ($requestUri === '/api/destination/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->delete();
    exit;
} elseif ($requestUri === '/api/destination/place/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->placeCreate();
    exit;
} elseif ($requestUri === '/api/destination/place/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->placeDelete();
    exit;
} elseif ($requestUri === '/api/destination/place/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinationController = new DestinationController();
    $destinationController->placeUpdate();
    exit;
}
// Public destination places page (traveller view)
elseif ($requestUri === '/destination/places' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/DestinationController.php';
    $ctrl = new App\Controllers\DestinationController();
    $ctrl->viewCategoryPlaces();
    exit;
}

// Auth routes
if ($requestUri === '/login') {
    $authController = new AuthController();
    $authController->showLogin();
} elseif ($requestUri === '/signup') {
    $authController = new AuthController();
    $authController->showSignup();
} elseif ($requestUri === '/loginUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $authController->loginUser();
} elseif ($requestUri === '/registerUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $authController->registerUser();
} elseif ($page === 'logout') {
    $authController = new AuthController();
    $authController->logoutUser();
}

// Booking API routes
elseif ($requestUri === '/api/booking/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingController = new BookingController();
    $bookingController->createBooking();
} elseif ($requestUri === '/api/booking/list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookingController = new BookingController();
    $bookingController->getUserBookings();
} elseif ($requestUri === '/api/booking/user' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookingController = new BookingController();
    $bookingController->getUserBookings();
} elseif (preg_match('/^\/api\/booking\/([^\/]+)$/', $requestUri, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Only match single-level paths like /api/booking/BK123
    // Don't match multi-level paths like /api/booking/create
    if (!in_array($matches[1], ['create', 'list', 'user', 'update-status', 'cancel', 'delete', 'filter', 'search'])) {
        $bookingController = new BookingController();
        $bookingController->getBooking($matches[1]);
    }
} elseif ($requestUri === '/api/booking/update-status' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $bookingController = new BookingController();
    $bookingController->updateBookingStatus();
} elseif ($requestUri === '/api/booking/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingController = new BookingController();
    $bookingController->updateBooking();
} elseif ($requestUri === '/api/booking/cancel' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingController = new BookingController();
    $bookingController->cancelBooking();
} elseif ($requestUri === '/api/booking/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingController = new BookingController();
    $bookingController->deleteBooking();
} elseif ($requestUri === '/api/booking/filter' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookingController = new BookingController();
    $bookingController->getFilteredBookings();
} elseif ($requestUri === '/api/booking/search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookingController = new BookingController();
    $bookingController->searchBookings();
} 

// Transport Booking API routes
elseif ($requestUri === '/api/transport-booking/init-booking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->initBooking();
    exit;
} elseif ($requestUri === '/api/transport-booking/save-details' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->saveDetails();
    exit;
} elseif ($requestUri === '/api/transport-booking/save-payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->savePayment();
    exit;
} elseif ($requestUri === '/api/transport-booking/complete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->completeBooking();
    exit;
} elseif ($requestUri === '/api/transport-booking/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->create();
    exit;
} elseif ($requestUri === '/api/transport-booking/all' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->getAll();
    exit;
} elseif ($requestUri === '/api/transport-booking/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->get();
    exit;
} elseif ($requestUri === '/api/transport-booking/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->update();
    exit;
} elseif ($requestUri === '/api/transport-booking/cancel' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->cancel();
    exit;
} elseif ($requestUri === '/api/transport-booking/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->delete();
    exit;
} elseif ($requestUri === '/api/transport-booking/upcoming' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->getUpcoming();
    exit;
} elseif ($requestUri === '/api/transport-booking/stats' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/../app/controllers/TransportBookingController.php';
    $transportBookingController = new App\Controllers\TransportBookingController();
    $transportBookingController->getStats();
    exit;
}

elseif ($requestUri === '/preference/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $preferenceController = new PreferenceController();
    $preferenceController->save();
}

// // Vehicles API
// elseif ($requestUri === '/api/vehicle/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {    
//     $vehicleController = new VehicleController();
//     $vehicleController->create();
// } elseif ($requestUri === '/api/vehicle/list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
//     $vehicleController = new VehicleController();
//     $vehicleController->listByUser();
// } elseif ($requestUri === '/api/vehicle/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
//     $vehicleController = new VehicleController();
//     $vehicleController->get();
// } elseif ($requestUri === '/api/vehicle/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
//     $vehicleController = new VehicleController();
//     $vehicleController->update();
// } elseif ($requestUri === '/api/vehicle/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
//     $vehicleController = new VehicleController();
//     $vehicleController->delete();
// }

// Traveller pages
elseif ($page === 'home' || $requestUri === '/') {
    include '../app/views/home.view.php';
} elseif ($page === 'login') {
    include '../app/views/traveller/login.view.php';
} elseif ($page === 'signup') {
    include '../app/views/traveller/signup.view.php';
} elseif ($page === 'signupmodel') {
    include '../app/views/signupmodel.view.php';
} elseif ($page === 'about') {
    include '../app/views/about.view.php';
} elseif ($page === 'contact') {
    include '../app/views/contact.view.php';
} elseif ($page === 'termsandcondition') {
    include '../app/views/termsandcondition.view.php';
} elseif ($page === 'privacy') {
    include '../app/views/privacy.view.php';
} elseif ($page === 'homet') {
    include '../app/views/traveller/homet.view.php';
} elseif ($page === 'booking_details') {
    include '../app/views/traveller/booking_details.view.php';
} elseif ($page === 'booking_finish') {
    include '../app/views/traveller/booking_finish.view.php';
} elseif ($page === 'booking_availability') {
    include '../app/views/traveller/booking_availability.view.php';
} elseif ($page === 'booking_payment') {
    include '../app/views/traveller/booking_payment.view.php';
} elseif ($page === 'mybookings') {
    include '../app/views/traveller/mybookings.view.php';
} elseif ($page === 'mybooking_details') {
    include '../app/views/traveller/mybooking_details.view.php';
} elseif ($page === 'feed') {
    include '../app/views/traveller/feed.view.php';
} elseif ($page === 'header') {
    include '../app/views/traveller/header.view.php';
} elseif ($page === 'footer') {
    include '../app/views/traveller/footer.view.php';
} elseif ($page === 'dashboard') {
    include '../app/views/traveller/dashboard.view.php';
} elseif ($page === 'profile_setting') {
    include '../app/views/traveller/profilesetting.view.php';
} elseif ($page === 'detailsProperty') {
    include '../app/views/accommodation/detailsProperty.view.php';
} elseif ($page === 'updateProperty') {
    include '../app/views/accommodation/updateProperty.view.php';
} elseif ($page === 'preference') {
    include '../app/views/traveller/preference.view.php';
} elseif ($page === 'ride_booking_details') {
    include '../app/views/traveller/ride_booking_details.view.php';
} elseif ($page === 'ride_booking_finish') {
    include '../app/views/traveller/ride_booking_finish.view.php';
} elseif ($page === 'ride_booking_selection') {
    include '../app/views/traveller/ride_booking_selection.view.php';
} elseif ($page === 'beach') {
    include '../app/views/traveller/beach.view.php';
} elseif ($page === 'beachdetail') {
    include '../app/views/traveller/beachdetail.view.php';
} elseif ($page === 'blog') {
    include '../app/views/traveller/blog.view.php';
} elseif ($page === 'favactivity') {
    include '../app/views/traveller/favactivity.view.php';
} elseif ($page === 'favdestination') {
    include '../app/views/traveller/favdestination.view.php';
} elseif ($page === 'surfing') {
    include '../app/views/traveller/surfing.view.php';
} elseif ($page === 'transport') {
    include '../app/views/traveller/transport.view.php';
} elseif ($page === 'accommodation') {
    include '../app/views/traveller/accommodation.view.php';
} elseif ($page === 'accommodationdetail') {
    include '../app/views/traveller/accommodationdetail.view.php';
} elseif ($page === 'message_box') {
    include '../app/views/traveller/message_box.view.php';
} elseif ($page === 'inc_message_box') {
    include '../app/views/traveller/inc_message_box.view.php';
} elseif ($page === 'transportdetails') {
    include '../app/views/traveller/transportdetails.view.php';
} elseif ($page === 'transport-booking-details') {
    include '../app/views/traveller/transport_booking_details.view.php';
} elseif ($page === 'transport-booking-payment') {
    include '../app/views/traveller/transport_booking_payment.view.php';
} elseif ($page === 'transport-booking-finish') {
    include '../app/views/traveller/transport_booking_finish.view.php';
} elseif ($page === 'mytransportbookings') {
    include '../app/views/traveller/mytransportbookings.view.php';
} 

//Transpoter pages
elseif ($page === 'tr_dashboard') {
    include '../app/views/transpoter/dashboard.view.php';
} elseif ($page === 'vehicleType') {
    include '../app/views/transpoter/vehicleType.view.php';
} elseif ($page === 'personalDetails') {
    include '../app/views/transpoter/personalDetails.view.php';
} elseif ($page === 'vehicleDocument') {
    include '../app/views/transpoter/vehicleDocument.view.php';
} elseif ($page === 'bookingnew') {
    include '../app/views/transpoter/bookingnew.view.php';
} elseif ($page === 'ViewDetailspending') {
    include '../app/views/transpoter/ViewDetailspending.view.php';
} elseif ($page === 'bookingHistory') {
    include '../app/views/transpoter/bookingHistory.view.php';
} elseif ($page === 'setting') {
    include '../app/views/transpoter/setting.view.php';
} elseif ($page === 'tripDetails') {
    include '../app/views/transpoter/tripDetails.view.php';
} elseif ($page === 'ViewDetailsAccepted') {
    include '../app/views/transpoter/ViewDetailsAccepted.view.php';
} elseif ($page === 'editVehicle') {
    include '../app/views/transpoter/editVehicle.view.php';
} elseif ($page === 'DeleteVehicle') {
    include '../app/views/transpoter/DeleteVehicle.view.php';
} elseif ($page === 'confirmBooking') {
    include '../app/views/transpoter/confirmBooking.view.php';
}

//accomodation pages
elseif ($page === 'ac_dashboard') {
    $controller = new AccommodationController();
    $controller->accommodationActivitySummary();
} elseif ($page === 'photoUpload') {
    include '../app/views/accommodation/photoUpload.view.php';
} elseif ($page === 'houseRules') {
    include '../app/views/accommodation/houseRules.view.php';
} elseif ($page === 'success') {
    include '../app/views/accommodation/success.view.php';
} elseif ($page === 'bookingDetailsAcc') {
    include '../app/views/accommodation/bookingDetailsAcc.view.php';
} elseif ($page === 'acc_setting') {
    include '../app/views/accommodation/setting.view.php';
} elseif ($page === 'editListing') {
    include '../app/views/accommodation/editListing.view.php';
} else if ($page === 'propertyListingStart') {
    include '../app/views/accommodation/propertyListingStart.view.php';
} elseif ($page === 'accommodationFeatures') {
    include '../app/views/accommodation/accommodationFeatures.view.php';
} elseif ($page === 'accommodationPhotos') {
    include '../app/views/accommodation/accommodationPhotos.view.php';
} elseif ($page === 'accommodationPricing') {
    include '../app/views/accommodation/accommodationPricing.view.php';
} elseif ($page === 'accommodationCalendar') {
    include '../app/views/accommodation/accommodationCalendar.view.php';
} elseif ($page === 'propertyDetails') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../app/controllers/AccommodationController.php';
        $controller = new AccommodationController();
        $controller->index();
    } else {
        include '../app/views/accommodation/propertyDetails.view.php';
    }
} elseif ($page === 'bedRoom') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../app/controllers/AccommodationController.php';
        $controller = new AccommodationController();
        $controller->saveBedRoom();
    } else {
        include '../app/views/accommodation/bedRoom.view.php';
    }
} elseif ($page === 'editBedrooms') {
    include '../app/views/accommodation/editBedrooms.view.php';
} elseif ($page === 'viewProperty') {
    include '../app/views/accommodation/viewProperty.view.php';
} elseif ($page === 'detailsProperty') {
    include '../app/views/accommodation/detailsProperty.view.php';
} elseif ($page === 'updateProperty') {
    include '../app/views/accommodation/updateProperty.view.php';
}elseif ($page === 'price'){ //if requested page == 'price'
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // check the request method (POST/GET)
        require_once __DIR__ . '/../app/controllers/AccommodationController.php';
        $controller = new AccommodationController();
        $controller->savePrice();
    } else {
        include '../app/views/accommodation/price.view.php';
    }
}

//admin pages
elseif ($page === 'ad_dashboard') {
    require_once '../app/controllers/AdminDashboardController.php';
    $adminDashboardController = new AdminDashboardController();
    $adminDashboardController->index();
}elseif ($page === 'content') {
    require_once '../app/controllers/AdminBlogController.php';
    $adminBlogController = new AdminBlogController();
    $adminBlogController->moderationQueue();
}elseif ($page === 'viewHotel') {
    include '../app/views/admin/viewHotel.view.php';
}elseif ($page === 'viewVehicle') {
    include '../app/views/admin/viewVehicle.view.php';
}elseif ($page === 'Users') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->index();
}elseif ($page === 'notifications') {
    require_once '../app/controllers/AdminNotificationController.php';
    $adminNotificationController = new AdminNotificationController();
    $adminNotificationController->index();
}elseif ($page === 'announcement') {
    require_once '../app/controllers/AdminAnnouncementController.php';
    $adminAnnouncementController = new AdminAnnouncementController();
    $adminAnnouncementController->index();
}elseif ($page === 'report') {
    include '../app/views/admin/report.view.php';
}elseif ($page === 'ad_setting') {
    include '../app/views/admin/setting.view.php';
}elseif ($page === 'ad_destinations') {
    require_once '../app/controllers/AdminDestinationController.php';
    $adminDestinationController = new AdminDestinationController();
    $adminDestinationController->index();
} elseif ($page === 'createDestination') {
    include '../app/views/admin/createDestination.view.php';
} elseif ($page === 'editDestination') {
    include '../app/views/admin/editDestination.view.php';
} elseif ($page === 'viewprovider') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->viewProvider();
} elseif ($page === 'viewtraveller') {
    require_once '../app/controllers/AdminUserController.php';
    $adminUserController = new AdminUserController();
    $adminUserController->viewTraveller();
} elseif ($page === 'viewblog') {
    include '../app/views/admin/viewblog.view.php';
}

// Default - 404
else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => '404 - Route not found: ' . $requestUri
    ]);
}