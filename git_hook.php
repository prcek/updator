<?php

try
{
  $payload = json_decode($_REQUEST['payload']);
}
catch(Exception $e)
{
  print "can't decode payload";
  exit(0);
}

file_put_contents('.logs/git_hook.log', print_r($payload, TRUE), FILE_APPEND);

if (($payload->ref == 'refs/heads/master') or ($payload->ref == 'refs/heads/test'))
{
  print "update triggered";
  exec('./git_pull.sh');
  exec('./ftp_push.sh');
} else {

}

?>
