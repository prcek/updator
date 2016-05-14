<?php
$src_url = getenv("GIT_SRC_URL");
$repo_name = getenv("GIT_REPO_SHORTNAME");
print "downloading $src_link\n";
$path = uniqid('/app/.tmp/git_', true);
print "to $path\n";
$fp = fopen($path . ".zip", 'w+');
print "fp ready\n";

$ch = curl_init($src_url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
$r = curl_exec($ch);
curl_close($ch);
fclose($fp);
print "curl res $r\n";
system("mkdir $path");
system("unzip -qq $path.zip -d $path");


$src = $path . "/" . $repo_name ."-".getenv("GIT_REPO_BRANCH")."/".getenv("GIT_REPO_SOURCE_PATH");
putenv("LOCAL_DIR=$src");
system("./up.sh");



?>

