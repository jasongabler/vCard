<?php
    require_once 'UCSFDirectory.php';
    
    $filter = empty($_POST['filter']) ? '' : $_POST['filter'];
    $error = null;

    if (!empty($filter)) {
        $directory = new UCSFDirectory();
        try {
            $people = $directory->query($filter);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    $people = empty($people) ? array() : $people;
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UCSF vCard Service</title>

    <!-- JQUERY -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>    
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</head>

<style>
    .container,
    #people h4 {
        margin-bottom:20px;
    }
    .person {
        margin-bottom:10px;
    }
    .person-header-name {
        padding-top: 6px;
        padding-bottom: 6px;
    }
    .person-content {
        margin-top:10px;
    }
    .person-content .field-name,
    .person-content .field-value {
        border-top: 1px solid #AAA;
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <h2 class="col-md-5">UCSF vCard Service</h2>
        </div>
    </div>

    <div class="container">
        <form role="form" method="POST" class="row">
            <div class="col-md-5">
                <input name="filter" type="text" class="form-control" value="<?php print $filter ?>" placeholder="Enter part of a person's name and/or department" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="icon-search icon-white"></i>
                People Search
            </button>
        </form>    
    </div>
    
    
    <div id="people" class="container">
        <?php if (empty($error)): ?>
            <?php if (empty($people)): ?>
                <?php if (!empty($filter)): ?>
                    <h4>Your search returned no results.</h4>
                <?php endif; ?>
            <?php else: // empty people ?>
                <h4>Your search returned <?php print count($people) ?> results.</h4>
                <?php foreach ($people as $person): 
                    $displayname = $person->get('displayname'); ?>
                    <div class="person">
                        <div class="person-vcard" style="display:none;"><?php print $person->vCard() ?></div>
                        <div class="person-header row">
                            <div class="person-header-name col-md-3"><?php print $displayname ?></div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary person-view-btn">
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                    Open/Close Record
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary person-vcard-btn" data-fn="<?php print $displayname ?>">
                                    <i class="glyphicon glyphicon-download"></i>
                                    Download vCard
                                </button>
                            </div>                
                        </div>
                        <div class="person-content row" style="display:none;">
                            <div class="container">
                                <?php foreach ($person->keys() as $key): ?>
                                    <div class="row">
                                        <div class="field-name col-md-3 col-md-offset-1"><label><?php print $key ?></label></div>
                                        <div class="field-value col-md-3"><?php print preg_replace('/[\r\n]/', '<br>', $person->get($key))  ?></div>
                                    </div>
                                <?php endforeach; ?>                
                            </div>
                        </div>
                    </div>
                <?php endforeach; // person ?>
            <?php endif; // empty people ?>
        
        <?php else: // error ?>
            <h4 class="error"><?php print $error ?></h4>
        <?php endif; // error ?>
    </div>
    <script>
        $(function() {
            $('.person-view-btn').click(function() {
                $(this).parents('.person').children('.person-content').toggle();
            });

            $('.person-vcard-btn').click(function() {
                // Get the vcard data stored within the same result container
                var vcard = $(this).parents('.person').children('.person-vcard').first().html();
                // Generate a filename that is the person's fullname with all whitespace converted to dashes.
                var filename = $(this).data('fn').replace(/\s+/, '-') + '.vcf';
                // Initiate the download of the data as a file
                download(vcard, filename, 'text/vcard');
            });            
            
            /**
             * Thank you, never-expiring pastebin.com page: http://pastebin.com/YZa4bZv9
             */
            var download = function(content, filename, contentType){ 
                if (!contentType) {
                    contentType = 'application/octet-stream';
                }
                var a = document.createElement('a');
                var blob = new Blob([content], {'type':contentType});
                a.href = window.URL.createObjectURL(blob);
                a.download = filename;
                a.click();
            };
        });
    </script>
</body>

</html>
