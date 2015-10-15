<?php

/**
 * @param string $directory
 *
 * @return array
 */
function getRules($directory)
{
    $rules    = [];
    $iterator = new DirectoryIterator($directory);

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'json') {
            $rules[$file->getBasename('.json')] = json_decode(file_get_contents($file->getRealPath()), true);
        }
    }

    return $rules;
}

/**
 * @param array $array
 * @param int   $depth
 *
 * @return string
 */
function arrayToString(array $array, $depth = 0)
{
    $string = "[\n";
    foreach ($array as $key => $value) {
        $string .= str_repeat(' ', ($depth+1)*4)."'$key' => ";
        if (is_array($value)) {
            $string .= arrayToString($value, $depth+1);
        } else {
            $string .= "'$value'";
        }
        $string .= ",\n";
    }
    $string .= str_repeat(' ', $depth*4)."]";

    return $string;
}

/**
 * @param string $fileName
 * @param array  $rules
 *
 * @return bool
 */
function insertRules($fileName, array $rules = [])
{
    $startTag = '/*INSERT_START*/';
    $endTag   = '/*INSERT_END*/';

    $content = file_get_contents($fileName);
    $content = preg_replace(
        $regexp = sprintf('#%s(.*)%s#', quotemeta($startTag), quotemeta($endTag)),
        $startTag.arrayToString($rules, 1).$endTag,
        $content
    );

    return false !== file_put_contents($fileName, $content);
}

$directory = __DIR__.'/../Resources/rules';
$fileName  = __DIR__.'/../src/RuleProvider/DefaultRuleProvider.php';
$rules     = getRules($directory);

$result = insertRules($fileName, $rules);

$ruleCount = array_reduce($rules, function ($count, $rules) {
    return $count + count($rules);
}, 0);

if ($result) {
    printf("Written %d rules into '%s'.\n", $ruleCount, $fileName);
} else {
    printf("Error writing rules into '%s'.\n", $fileName);
}

