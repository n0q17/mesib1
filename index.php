<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
echo "<script>window.location.href='./login.php';</script>";
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>N0Q1</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style>
body {
background-image: url("./assets/a.jpg");
background-attachment: fixed;
background-position: center;
}

.container {
max-width: 960px;
margin: 0 auto;
padding: 40px;
text-align: center;
}

.welcome-header {
background-color: transparent;
padding: 10px;
text-align: center;
}

.welcome-message {
font-size: 24px;
color: #fff;
text-transform: uppercase;
letter-spacing: 2px;
font-weight: bold;
}

.options-menu {
position: absolute;
top: 20px;
left: 20px;
z-index: 999;
cursor: pointer;
color: #fff;
}

.options-line {
width: 20px;
height: 2px;
background-color: #fff;
margin-bottom: 4px;
}

.dropdown-menu {
position: absolute;
top: 40px;
left: 0;
display: none;
background-color: #000;
padding: 10px;
border-radius: 3px;
z-index: 999;
}

.dropdown-menu.show {
display: block;
}

.dropdown-item {
color: #fff;
text-decoration: none;
display: block;
padding: 8px 12px;
cursor: pointer;
}

.dropdown-item:hover {
background-color: #333;
}

.gallery {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
gap: 20px;
justify-items: center;
align-items: center;
margin-top: 40px;
}

.gallery-item {
position: relative;
}

.gallery-image {
width: 100%;
height: auto;
border-radius: 5px;
box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
cursor: pointer;
transition: transform 0.3s ease;
}

.gallery-image:hover {
transform: scale(1.05);
}

.gallery-text {
color: #fff;
font-size: 18px;
}

.modal-content {
text-align: center;
background-color: black;
}

#modal-image {
max-width: 100%;
height: auto;
}

.modal-buttons {
display: flex;
justify-content: space-between;
margin-top: 20px;
}

.modal-buttons .btn-primary {
margin-left: 10px;
}
</style>
</head>
<body>
<div class="container">
<header class="welcome-header">
<h1 class="welcome-message"><?php echo "Welcome " . $_SESSION["username"] . "!"; ?></h1>
</header>
<div class="options-menu" onclick="toggleDropdown()">
<div class="options-line"></div>
<div class="options-line"></div>
<div class="options-line"></div>
</div>
<div id="dropdownMenu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
<a class="dropdown-item" href="?type=image">ستوريات</a>
<a class="dropdown-item" href="?type=text">نصوص شعريه</a>
</div>
<div class="gallery">
<?php
$fileType = isset($_GET['type']) ? $_GET['type'] : 'image';
$directory = $fileType === 'text' ? './n0q1v/' : './n0q1/';
$files = glob($directory . '*');
shuffle($files); // تخلط الصور بشكل عشوائي

foreach ($files as $file) {
if (is_file($file) && (getimagesize($file) || $fileType === 'text')) {
$fileName = basename($file);
echo '<div class="gallery-item">';
if ($fileType === 'text') {
$fileContent = file_get_contents($file);
echo '<p class="gallery-text">' . nl2br(htmlspecialchars($fileContent)) . '</p>';
} else {
echo '<img class="gallery-image lazy" data-src="' . $file . '" alt="' . $fileName . '">';
}
echo '</div>';
}
}
?>
</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content">
<div class="modal-body">
<img id="modal-image" class="lazy" data-src="" alt="">
<div class="modal-buttons">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<a href="#" class="modal-download-button btn btn-primary" download>Download</a>
</div>
</div>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
var lazyImages = [].slice.call(document.querySelectorAll(".lazy"));

if ("IntersectionObserver" in window) {
let lazyImageObserver = new IntersectionObserver(function (entries, observer) {
entries.forEach(function (entry) {
if (entry.isIntersecting) {
let lazyImage = entry.target;
lazyImage.src = lazyImage.dataset.src;
lazyImage.classList.remove("lazy");
lazyImageObserver.unobserve(lazyImage);
}
});
});

lazyImages.forEach(function (lazyImage) {
lazyImageObserver.observe(lazyImage);
});
}
});

function toggleDropdown() {
var dropdownMenu = document.getElementById("dropdownMenu");
dropdownMenu.classList.toggle("show");
}

$(document).ready(function () {
$('.gallery-image').click(function () {
var imageSrc = $(this).attr('src');
var downloadURL = imageSrc.replace(/^.*[\\\/]/, '');
$('#modal-image').attr('src', imageSrc);
$('.modal-download-button').attr('href', imageSrc).attr('download', downloadURL);
$('#myModal').modal('show');
});
});
</script>
</body>
</html>
