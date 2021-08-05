<?php
ob_start('ob_gzhandler');
$cookie          = "sb=w8_BYBy-As8rgTuF_6qZ5uSZ; _fbp=fb.1.1623339108949.1226927059; dpr=0.8999999761581421; datr=WgwGYZ7GJ90-Irl-VyI51Yyj; wd=2133x1076; c_user=100001070779111; spin=r.1004201521_b.trunk_t.1628046359_s.1_v.2_; xs=19%3AygnoTRwTt4i69A%3A2%3A1628046351%3A-1%3A15062%3A%3AAcXYg5gxNM1RfmF25fWpMZydfYYvvtkxDqcLUkWnoQ; fr=1QJm8W6CLrjuJq110.AWUuH67jr_9dZcKU1K21HYmbc-s.BhCy4j.-0.AAA.0.0.BhCy4j.AWXMWLjsD04; presence=C%7B%22t3%22%3A%5B%5D%2C%22utc3%22%3A1628122873730%2C%22v%22%3A1%7D; useragent=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgNi4xOyBXaW42NDsgeDY0KSBBcHBsZVdlYktpdC81MzcuMzYgKEtIVE1MLCBsaWtlIEdlY2tvKSBDaHJvbWUvOTEuMC40NDcyLjEyNCBTYWZhcmkvNTM3LjM2; "; // ambil lewat FireFOX/ kiwi Browser
$id_user         = "100001070779111"; //idfb kamu
$message_comment = "Test bot komentar...";
$botkomen        = "false"; //true (aktif) False (Non-Aktif)
$reaction_type   = "2"; //1 like, 2 love, 3 wow, 4 haha, 5 sad, 6 angry

$url     = curl("https://m.facebook.com/", $cookie);

preg_match_all('/name="fb_dtsg" value="(.*?)" autocomplete="off"/is',$url, $fbdtsg);
$fb_dtsg = $fbdtsg[1][0];

if (preg_match_all('#ft_ent_identifier=(.+?)&#is', $url, $gettings)) {
    for ($i = 0; $i < count($gettings[1]); $i++) {
        if (file_exists('' . $id_user)) {
            $log = json_encode(file('' . $id_user));
        } else {
            $log = '';
        }

        if (!preg_match("/" . $gettings[1][$i] . "/", $log)) {
            SaveLog($id_user, $gettings[1][$i]);
            echo $gettings[1][$i];
            post_data("https://m.facebook.com/ufi/reaction/?ft_ent_identifier=" . $gettings[1][$i] . "&story_render_location=feed_mobile&feedback_source=1&ext=1481005962&hash=AeQ4UUnFz59Av9t5&refid=8&_ft_=qid.6359758912943651311%3Amf_story_key.-7381576517051739942%3Atop_level_post_id.1864991263733728&av=" . $id_user . "&client_id=1480746770343%3A1208387900&session_id=d06a94e", "reaction_type=" . $reaction_type . "&ft_ent_identifier=" . $gettings[1][$i] . "&m_sess=&fb_dtsg=" . $fb_dtsg . "&__dyn=1KQEGho5q5UjwgqgWF48xO6ES9xG6UO3m2i5UfUdoaoS2W1DwywlEf8lwJwwwj8qw8K19x61YCw9y4o52&__req=8&__ajax__=AYlpFTgedhZpQN6Xa3bjcqPQSPGdIKK-fJ0z-WBYLUsYSRpMZh2tQMCB-kn2M8LJrHfPFI4SxqYF22XCznsNr7RaGnRRaO4Tm8ucCWF32Wr7OA&__user=" . $id_user, $cookie);
            if ($botkomen == 'true') {
                echo " => ".$message_comment." ";
                post_data("https://m.facebook.com/a/comment.php?fs=8&fr=%2Fprofile.php&actionsource=13&comment_logging&ft_ent_identifier=" . $gettings[1][$i] . "&gfid=AQDWVdFsGh2dPr2T&_ft_=top_level_post_id." . $gettings[1][$i] . "%3Atl_objid." . $gettings[1][$i] . "%3Athid." . $id_user . "&av=" . $id_user . "&refid=52", "fb_dtsg=" . $fb_dtsg . "&comment_text=" . $message_comment, $cookie);
            }
            echo "SUKSES<br>";
        } else {
            echo $gettings[1][$i]. " => Sudah pernah di eksekusi...<br>";
        }
    }
}

function SaveLog($fbid, $datapost)
{
    return file_put_contents("{$fbid}", $datapost.PHP_EOL, FILE_APPEND);
}

function curl($url, $cookie)
{
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14');
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Expect:'
    ));
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}

function post_data($site, $data, $cookie)
{
    $datapost = curl_init();
    $headers  = array(
        "Expect:"
    );
    curl_setopt($datapost, CURLOPT_URL, $site);
    curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
    curl_setopt($datapost, CURLOPT_HEADER, TRUE);
    curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($datapost, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($datapost, CURLOPT_POST, TRUE);
    curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
    curl_setopt($datapost, CURLOPT_COOKIE, $cookie);
    ob_start();
    curl_exec($datapost);
    //return curl_exec($datapost);
    ob_end_clean();
    curl_close($datapost);
    unset($datapost);
}
?>
