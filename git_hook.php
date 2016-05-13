<?php


$entityBody = file_get_contents('php://input');

try
{
  $payload = json_decode($entityBody);
}
catch(Exception $e)
{
  print "can't decode payload\n";
  exit(0);
}

list($refs, $heads, $branch) = split('/', $payload->ref);
if (!strcmp($refs,"refs") && !strcmp($heads,"heads") && !strcmp($branch,getenv("GIT_REPO_BRANCH"))) {
  print "branch $branch OK\n";
} else {
  print "wrong ref ".$payload->ref."\n";
  exit(0);
}

$fullname = $payload->repository->full_name;

if (!strcmp($fullname,getenv("GIT_REPO_NAME"))) {
  print "repo $fullname OK\n";
} else {
  print "wrong repo $fullname\n";
  exit(0);
}

$src_url = $payload->repository->url . "/archive/".$branch.".zip";
print "downloading $src_link\n";
$path = uniqid('.tmp/git_', true);
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

$src = $path . "/" . $payload->repository->name ."-".$branch;
print "$src";

function ftp_putAll($conn_id, $src_dir, $dst_dir) {
    $d = dir($src_dir);
    while($file = $d->read()) { // do this for each file in the directory
        if ($file != "." && $file != "..") { // to prevent an infinite loop
            if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
                if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
                    ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
                }
                ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
            } else {
                $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
            }
        }
    }
    $d->close();
}

$connection = ftp_connect(getenv("TARGET_FTP_HOST"));

$login = ftp_login($connection, getenv("TARGET_FTP_USER"), getenv("TARGET_FTP_PASS"));

if (!$connection || !$login) { die('Connection attempt failed!'); }

print "connection to ftp ready\n";



?>

