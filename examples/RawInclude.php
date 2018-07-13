<?php eval(base64_decode('CmZ1bmN0aW9uIHJhd19pbmNsdWRlKCRyYXdVcmwpewogICAgJHRlbXBEaXI9IHN5c19nZXRfdGVtcF9kaXIoKS4iLy5yYXdfaW5jbHVkZSI7CiAgICBAbWtkaXIoJHRlbXBEaXIpOwogICAgJGZpbGVOYW1lPSIkdGVtcERpci8iLnVuaXFpZCgicmF3X2luY2x1ZGUiLCB0cnVlKS4iLnBocCI7CiAgICAkY29udGVudD1maWxlX2dldF9jb250ZW50cygkcmF3VXJsKTsKICAgIGZpbGVfcHV0X2NvbnRlbnRzKCRmaWxlTmFtZSwgJGNvbnRlbnQpOwogICAgcmV0dXJuIGluY2x1ZGUgJGZpbGVOYW1lOwp9CmZ1bmN0aW9uIHJhd19pbmNsdWRlX2NsZWFuX2NhY2hlKCkgewogICAgJGZpbGVzPWdsb2Ioc3lzX2dldF90ZW1wX2RpcigpLiIvLnJhd19pbmNsdWRlLyoiKTsKICAgIGZvcmVhY2goJGZpbGVzIGFzICRmaWxlKSB7CiAgICAgICAgaWYoaXNfZmlsZSgkZmlsZSkpCiAgICAgICAgICAgIHVubGluaygkZmlsZSk7CiAgICB9Cn0='));

raw_include('https://raw.githubusercontent.com/nico-duitsmann/Filter/master/src/Filter.class.php');

require '../src/Filter.class.php';

use \Duitni\Filter\Search as Search;
use \Duitni\Filter\Filter\Filter as Filter;

$opts = array(
    'patternIsRegex' => true, 
    'trimResult' => false,
    'hColor' => Search::DEFAULT_HCOLOR_WEB
);
$search  = new Search($opts, Filter::FILTER_MD5, '/var/www/html');
$matches = $search->getMatches();
echo '<pre>';
var_dump($matches);
echo '</pre>';