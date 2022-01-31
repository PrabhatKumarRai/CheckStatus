<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckStatus</title>
    <meta name="description" content="Website's HTTP response & status checker.">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    
        <!-- Header Section -->
        <div class="header">
            <div class="header-top">
                <h1>CheckStatus</h1>
                <p>Easily Check Website Status.</p>
            </div>
            <div class="header-bottom search-container">
                <form method="post" id="form">
                    <!-- <input type="text" name="website_url" placeholder="Enter website address" value="<?php //echo !empty($_GET['website_url'])? $_GET['website_url']: ''; ?>"> -->
                    <textarea name="website_urls" id="" placeholder="Add each URL in a new line.."><?= !empty($_POST['website_urls'])? $_POST['website_urls']: ''; ?></textarea>                    
                    <br>
                    <button type="submit" id="submit">Check Status</button>
                </form>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-wrapper">
            <?php 
                include_once __DIR__.'/process/process.php';
                if(!empty($data)):
                    $i = 0;
                    foreach($data as $keys):
            ?>
                        <div class="section accordion <?= ( $i == 0 && count($data) == 1 )? 'show': ''; ?>">
                        <div class="accordion-header">
                            <h2 class="accordion-url"><?= $keys['URL']; ?></h2>
                            <span class="response-code"><?= $keys['response']; ?></span>
                            <button class="btn"><?= ( $i == 0 && count($data) == 1 )? '-': '+'; ?></button>
                        </div>
                        <div class="accordion-body">

            <?php
                        unset($keys['URL']);
                        
                        foreach($keys as $key => $value):
            ?>
                            <div class="item">
                                <div class="item-head">
                                    <?= ucwords($key); ?>
                                </div>
                                <div class="item-content">
                                    <?= 
                                        (is_string($value) !== false)? $value: ((is_array($value) !== false)? $value[0]: print_r($value)); 
                                    ?>
                                </div>
                            </div>
            <?php 
                        endforeach;
                        echo "</div></div>";
                        $i++;
                    endforeach;
                endif;
            ?>
        </div>       

        <!-- Footer Section -->
        <div class="footer">
            <hr>
            <p>By Prabhat Rai</p>
            <hr>
        </div>

    </div>

    <!-- Loading the script file -->
    <script src="script.js"></script>

</body>
</html>