<?php
class Controll_All{
    function Save ($Connect,$URL){
        # Generate a random shorten URL
        $Characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $Shorten = substr(str_shuffle($Characters),0, 10);
        # Save
        $SQL = "INSERT INTO `url` VALUES (null,'$Shorten','$URL','0')";
        if($Connect->query($SQL) === true){
            return "با موفقیت ساخته شد";
        }
        else{
            return "مشکلی در ذخیره";
        }
    }

    function Delete_all ($Connect){
        $SQL = "DELETE FROM `url`";
        $Connect->query($SQL);
        $SQL = "ALTER TABLE url AUTO_INCREMENT=0";
        $Connect->query($SQL);
        return "با موفقیت تمامی لینک ها حذف شد";
    }

    function Delete ($Connect,$ID){
        $SQL = "DELETE FROM `url` WHERE id='$ID'";
        $Connect->query($SQL);
        return "با موفقیت حذف شد";
    }

    function Find ($Connect,$Shorten){
        # Find a URL
        $SQL = "SELECT `full_url`,`clicks` FROM `url` WHERE shorten_url='$Shorten'";
        $Result = $Connect->query($SQL);
        $Row = $Result->fetch_assoc();
        $URL = $Row['full_url'];
        # Update Clicks
        $Clicks = $Row['clicks'] + 1;
        $Connect->query("UPDATE `url` SET clicks='$Clicks' WHERE shorten_url='$Shorten'");
        return $URL;
    }
}
?>