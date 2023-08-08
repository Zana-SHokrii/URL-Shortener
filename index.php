<?php
include "Include/Config.php";

# Redirect
if(isset($_GET['c'])){
    $Shorten = $_GET['c'];
    # Include Controll
    include "Include/Controll.php";
    $Controll = new Controll_All();
    # Find a URl
    $URL = $Controll->Find($Connect,$Shorten);
    header("location: $URL");
}

# Save a URL
if(isset($_POST['full_url']) && !empty($_POST['full_url'])){
    $URL = $_POST['full_url'];
    if(!filter_var($URL, FILTER_VALIDATE_URL) === false){
        # Include Controll
        include "Include/Controll.php";
        $Controll = new Controll_All();
        # Save URl
        $Save_Status = $Controll->Save($Connect,$URL);
        # Return Status
        if($Save_Status == "با موفقیت ساخته شد"){
            $Success = $Save_Status;
        }
        else{
            $Error = $Save_Status;
        }
    }
    else{
        $Error = "URL معتبر نیست";
    }
}

# Delete All URLs
if(isset($_GET['delete_all'])){
    # Include Controll
    include "Include/Controll.php";
    $Controll = new Controll_All();
    # Delete All URls
    $Success = $Controll->Delete_all($Connect);
}

# Delete Selected URLs
if(isset($_GET['delete'])){
    $ID = $_GET['delete'];
    # Include Controll
    include "Include/Controll.php";
    $Controll = new Controll_All();
    # Delete Selected URLs
    $Success = $Controll->Delete($Connect,$ID);
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>URL Shortener | Zana Shokrii</title>
    <base href="<?php echo $Base; ?>">
    <link rel="stylesheet" href="Theme/Css/Main.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
</head>
<body>
    <div class="wrapper">

        <?php
            # Error Message
            if(isset($Error)){
                ?>
                <div class="Error">
                    <p><?php echo $Error; ?></p>
                </div>
                <?php
            }
            # Success Message
            if(isset($Success)){
                ?>
                <div class="Success">
                    <p><?php echo $Success; ?></p>
                </div>
                <?php
            }
        ?>
        
        <form action="<?php echo $Base; ?>" autocomplete="off" method="POST">
            <input type="text" spellcheck="false" name="full_url" placeholder="جای گذاری لینک" required>
            <i class="url-icon uil uil-link"></i>
            <button>کوتاه کن</button>
        </form>

        <?php
            # Count Links
            $SQL = "SELECT `shorten_url` FROM `url`";
            $Result = $Connect->query($SQL);
            $Links = $Result->num_rows;
            # Count Clicks
            $SQL = "SELECT `clicks` FROM `url`";
            $Result = $Connect->query($SQL);
            $Total = 0;
            while($count = mysqli_fetch_assoc($Result)){
                $Total = $count['clicks'] + $Total;
            }
            if(!$Links == 0){
                ?>
                <div class="statistics">
                    <span>کل لینک ها: <span><?php echo $Links; ?></span> & کل کلیک ها: <span><?php echo $Total; ?></span></span>
                    <a href="?delete_all">پاک کردن همه</a>
                </div>
                <div class="urls-area">
                    <div class="title">
                        <li>لینک کوتاه شده</li>
                        <li>لینک اورجینال</li>
                        <li>کلیک</li>
                        <li>عملیات</li>
                    </div>
                    <?php
                    $SQL = "SELECT * FROM url ORDER BY id DESC";
                    $Result = $Connect->query($SQL);
                    while($Row = $Result->fetch_assoc()){
                        ?>
                        <div class="data">
                            <li><a href="<?php echo $Base . "?c=" . $Row['shorten_url']; ?>" target="_blank"><?php echo $Row['shorten_url']; ?></a></li> 
                            <li><?php echo $Row['full_url']; ?></li>
                            <li><?php echo $Row['clicks']; ?></li>
                            <li><a href="?delete=<?php echo $Row['id']; ?>">حذف</a></li>
                        </div>
                        <?php
                    }
                    $Connect->close();
                    ?>
                </div>
                <?php
            }
        ?>
    </div>
</body>
</html>