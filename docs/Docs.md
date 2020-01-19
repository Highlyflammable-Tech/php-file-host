### Add file:
Post to `URL_HERE/index.php`

With `secret` in the body and `sharex` with the file

It will return JSON:
{
  "status":"OK",
  "errormsg":"",
  "url":"file_name"
}

### View file
Get to `URL_HERE/index.php?`

With `f` as a request query holding the file name
