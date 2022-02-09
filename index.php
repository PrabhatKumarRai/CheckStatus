<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckStatus</title>
    <meta name="description" content="Check Website's HTTP Response">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    
        <!-- Header Section -->
        <div class="header">
            <div class="header-top">
                <h1>CheckStatus</h1>
                <p>Easily Check Website HTTP Response & Status.</p>
            </div>
            <div class="header-bottom search-container">
                <form method="post" id="form">
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
                    for($x = 0; $x < count($data); $x++):
            ?>
                        <!---------- Accordion Starts ---------->
                        <div class="section accordion <?= ( $i == 0 && count($data) == 1 )? 'show': ''; ?>">

                            <!---------- Accordion Header Starts ---------->
                            <div class="accordion-header">
                                <h2 class="accordion-url">
                                    <a href="<?= $data[$x]['URL']; ?>" target="_blank">
                                        <?php echo $data[$x]['URL']; unset($data[$x]['URL']); ?>
                                    </a>
                                </h2>
                                <span class="response-code">
                                    <?php                                         
                                        //Printing response codes with color
                                        for($p = 0; $p < count($data[$x]); $p++){
                                            echo "<span style='color: " . getColor($data[$x][$p]['response']) . " '>" .
                                                    $data[$x][$p]['response'] . " " . 
                                                "</span>";
                                        }
                                    ?>    
                                </span>
                                <button class="btn"><?= ( $i == 0 && count($data) == 1 )? '-': '+'; ?></button>
                            </div>
                            <!---------- Accordion Header Ends ---------->
                            <!---------- Accordion Body Starts ---------->
                            <div class="accordion-body">
                             <?php                                
                                for($p = 0; $p < count($data[$x]); $p++):
                                    ?>                                    
                                    <div class="inner-section" style="border-color: <?= getColor($data[$x][$p]['response']); ?>;">
                                    <?php
                                        foreach($data[$x][$p] as $key => $value):
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
                                    ?>
                                    
                                    </div>
                                    
                                    <?php
                                endfor;
                            ?>
                            </div>
                            <!---------- Accordion Body Ends ---------->
                        </div>
                        <!---------- Accordion Ends ---------->
                            
                            <?php
                        $i++;
                    endfor;
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