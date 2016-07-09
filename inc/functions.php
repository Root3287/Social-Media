<?php 
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function phrase($text, $hash = 0, $sender = null){
    $sender = new User($sender);
    $db = DB::getInstance();
    //links
    $text = preg_replace(
            '@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@',
             '<a href="$1" target="_blank">$1</a>',
            $text);

    //hashtags
    $text = preg_replace(
            '/\s+#(\w+)/',
            ' <a href="/search/$1" target="_blank">#$1</a>',
           $text);

    if(preg_match_all('/@(\w+)/', $text, $matches)){
        $matches = $matches[1];
        foreach ($matches as $match) {
            $user = $db->get('users', ['username', '=', $match]);
            if($user->count() != 0){
                $user = $user->first();
                //Do notification
                $message = "You have been mensioned on a post! Click <a href='/p/{$hash}'>here</a> to visit the post!";
                $db->insert('notification', array(
                   'user' => $user->id,
                    'message'=> $message,
                ));
                //add them to a mension list to display on their page
                $db->insert('mensions', [
                    'user_id'=>$user->id,
                    'post_hash'=>$hash,
                ]);
                //Update their scores
                $sender->update([
                    'score'=> $sender->data()->score+1,
                ], $sender->data()->id);
                $db->update('users', $user->id, ['score'=>$user->score+1]);
                //Replace the text with a link
                $text = preg_replace('/@(\w+)/','<a href="/u/$1">@$1</a>',$text);
            }else{
                continue;
            }
        }
    }
    return $text;
}
function date_compare($a, $b){
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t2 - $t1;
}   
function getClientIP(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function getSelfUrl(){
    if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){
        if($_SERVER['SERVER_PORT'] == 80){
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        }else{
            return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
        }
    }else{
        return false;
    }
}
function isBot(){
    // THE BOTS WE WANT TO IGNORE
    static
    $bad_robots= [
        'crawler', 'spider', 'robot', 'slurp', 'Atomz', 'googlebot', 'VoilaBot', 'msnbot', 'Gaisbot', 'Gigabot', 'SBIder', 'Zyborg', 'FunWebProducts', 'findlinks', 'ia_archiver', 'MJ12bot', 'Ask Jeeves', 'NG/2.0', 'voyager', 'Exabot', 'Nutch', 'Hercules', 'psbot', 'LocalcomBot'
    ];

    // COMPARE THE BOT STRINGS TO THE USER AGENT STRING
    foreach ($bad_robots as $spider)
    {
        $spider = '#' . $spider . '#i';
        if (preg_match($spider, $_SERVER["HTTP_USER_AGENT"])) return TRUE;
    }
    return FALSE;
}