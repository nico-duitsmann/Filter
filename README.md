# Filter
***NOTE*** This software is still `under developement`!

[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat-square)](LICENSE)


Filter is a php library to find string like patterns or regular expressions in strings, files, directorys and urls.

## Install

Via Composer

``` bash
$ composer require duitni/filter
```

Manually

``` php
require 'Filter.class.php';

use \Duitni\Filter\Search as Search;
```

Or via remote import
``` php
eval(base64_decode('Ci8qKgogKiBSYXcgdXJsIGltcG9ydC4KICogQGF1dGhvciBOaWNvIER1aXRzbWFubgogKi8KCmZ1bmN0aW9uIHJhd19pbmNsdWRlKHN0cmluZyAkcmF3VXJsKSB7CiAgICAkdGVtcERpciAgPSBzeXNfZ2V0X3RlbXBfZGlyKCkuIi8ucmF3X2luY2x1ZGUiOwogICAgQG1rZGlyKCR0ZW1wRGlyKTsKICAgICRmaWxlTmFtZSA9ICIkdGVtcERpci8iLnVuaXFpZCgicmF3X2luY2x1ZGUiLCB0cnVlKS4iLnBocCI7CiAgICAkY29udGVudCAgPSBmaWxlX2dldF9jb250ZW50cygkcmF3VXJsKTsKICAgIGZpbGVfcHV0X2NvbnRlbnRzKCRmaWxlTmFtZSwgJGNvbnRlbnQpOwogICAgcmV0dXJuIGluY2x1ZGUgJGZpbGVOYW1lOwp9CgpmdW5jdGlvbiByYXdfaW5jbHVkZV9jbGVhbl9jYWNoZSgpIHsKICAgICRmaWxlcyA9IGdsb2Ioc3lzX2dldF90ZW1wX2RpcigpLiIvLnJhd19pbmNsdWRlLyoiKTsKICAgIGZvcmVhY2goJGZpbGVzIGFzICRmaWxlKSB7CiAgICAgICAgaWYoaXNfZmlsZSgkZmlsZSkpCiAgICAgICAgICAgIHVubGluaygkZmlsZSk7CiAgICB9Cn0K'));
raw_include('https://raw.githubusercontent.com/nico-duitsmann/Filter/master/src/Filter.class.php');

raw_include_clean_cache(); # at file end clearing cache
```
The raw_include source can be found here:<br>
```https://gist.github.com/nico-duitsmann/f8f301b28389518ff10a5266f12b53cb```

## Usage

``` php
$options = array(
    'trimResult' => true
);
$search  = new Search($options, 'MyPattern', 'Subject1', 'Subject2', 'Subject3');
$matches = $search->getMatches();
$stats   = $search->getStats();

foreach ($matches as $match) {
    echo 'Found '.$match['pattern'].' on position '.$match['position'].' in '.$match['subject'].'<br>';
}

echo 'Scan finished in : '.$stats[0]['time'];
```

Available options
``` php
/*
 * patternIsRegex > Search with regex instead of pattern
 * trimResult     > Trim the outputted result
 * maxLineLen     > Define max line length
 * colored        > Colored output
 * hColor         > Define highlight color
*/

// build options array
$options = array(
    'patternIsRegex' => false,
    'trimResult'     => true,
    'maxLineLen'     => 50 ,
    'colored'        => true,
    'hColor'         => 'red',
);
```

***NOTE***: More examples can be find in `examples`. 

## Author

- [Nico Duitsmann](https://github.com/nico-duitsmann)

## License

GNU General Public License v3. Please see [License File](LICENSE) for more information.

## Disclaimer

The author takes NO responsibility and/or liability for how you choose to use any of the tools/source code/any files provided. The author and anyone affiliated with will not be liable for any losses and/or damages in connection with use of ANY files provided with Filter. By using Filter or any files included, you understand that you are AGREEING to that.
