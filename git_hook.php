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
$short_name = $payload->repository->name;
putenv("GIT_SRC_URL=$src_url");
putenv("GIT_REPO_SHORTNAME=$short_name");

exec("php bg.php > .logs/bg.log 2>&1 &");

print "done\n";



?>

