<?php
$file = 'index.php';
$content = file_get_contents($file);


$searchQuery = '$cars = $conn->query("SELECT * FROM cars WHERE category = \'most_searched\' LIMIT 4");' . "\n" . '$conn->close();';

if (strpos($content, $searchQuery) === false) {
    $searchQuery = '$cars = $conn->query("SELECT * FROM cars WHERE category = \'most_searched\' LIMIT 4");' . "\r\n" . '$conn->close();';
}

$replaceQuery = '$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");';

if (strpos($content, $searchQuery) !== false) {
    $content = str_replace($searchQuery, $replaceQuery, $content);
    echo "Updated top query.\n";
} else {

    if (strpos($content, $replaceQuery) !== false) {
        echo "Top query already updated.\n";
    } else {
        echo "Warning: Could not find top query block to update.\n";
    }
}


$startMarker = '<div class="cars-grid">';
$endMarker = '<!-- Quote Section -->';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker);

if ($startPos !== false && $endPos !== false) {

    $sectionEnd = strrpos(substr($content, 0, $endPos), '</section>');
    if ($sectionEnd) {
        $containerEnd = strrpos(substr($content, 0, $sectionEnd), '</div>');
        if ($containerEnd) {
            $gridEnd = strrpos(substr($content, 0, $containerEnd), '</div>');

            if ($gridEnd && $gridEnd > $startPos) {
                $innerStart = $startPos + strlen($startMarker);
                $length = $gridEnd - $innerStart;


                $newGridContent = '
                <?php
                if ($cars && $cars->num_rows > 0) {
                    $cars->data_seek(0);
                    while($car = $cars->fetch_assoc()) {
                        $name = htmlspecialchars($car[\'name\']);
                        $price = htmlspecialchars($car[\'price\']);
                        $image = htmlspecialchars($car[\'image_path\']);
                        $badge = isset($car[\'badge\']) && $car[\'badge\'] ? htmlspecialchars($car[\'badge\']) : \'\';
                        $body_type = isset($car[\'body_type\']) ? htmlspecialchars($car[\'body_type\']) : \'Car\';
                        $fuel_type = isset($car[\'fuel_type\']) ? htmlspecialchars($car[\'fuel_type\']) : \'Petrol\';
                        $mileage = isset($car[\'mileage\']) ? htmlspecialchars($car[\'mileage\']) : \'N/A\';
                        $year = isset($car[\'year\']) ? htmlspecialchars($car[\'year\']) : \'2024\';
                ?>
                <div class="car-card">
                    <div class="car-image">
                        <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
                        <?php if ($badge): ?><span class="car-badge"><?php echo $badge; ?></span><?php endif; ?>
                        <button class="like-btn" onclick="this.classList.toggle(\'liked\')">
                            <svg viewBox="0 0 24 24" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </button>
                    </div>
                    <div class="car-info">
                        <h3 class="car-title"><?php echo $name; ?></h3>
                        <p class="car-price"><?php echo $price; ?></p>
                        <div class="car-tags">
                            <span class="car-tag"><?php echo $body_type; ?></span>
                            <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path><path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3"></path></svg><?php echo $fuel_type; ?></span>
                        </div>
                        <div class="car-specs">
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 6v6l3 3"></path></svg>
                                <?php echo $mileage; ?>
                            </div>
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="5" width="16" height="16" rx="2"></rect><path d="M16 3v4"></path><path d="M8 3v4"></path><path d="M4 11h16"></path></svg>
                                <?php echo $year; ?>
                            </div>
                        </div>
                        <button class="car-btn">View Details</button>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo \'<div style="grid-column: 1/-1; text-align: center; padding: 40px;"><p>No cars available at the moment.</p></div>\';
                }
                ?>
                 ';

                $content = substr_replace($content, $newGridContent, $innerStart, $length);
                echo "Updated cars grid content with tighter whitespace.\n";
            } else {
                echo "Could not find grid end.\n";
            }
        } else {
            echo "Could not find container end.\n";
        }
    } else {
        echo "Could not find section end.\n";
    }
} else {
    echo "Could not find start/end markers.\n";
}

file_put_contents($file, $content);
?>