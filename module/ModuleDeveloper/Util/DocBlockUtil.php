<?php


namespace Module\ModuleDeveloper\Util;


use ModStart\Core\Util\RandomUtil;
use Mpociot\Reflection\DocBlock\Tag;
use stdClass;

class DocBlockUtil
{
    /**
     * @param $tag Tag
     */
    public static function parseTagContent($tag)
    {
        $content = '';
        if (!empty($tag)) {
            $content = trim($tag->getContent());
        }
        return $content;
    }

    public static function parseReturn($tag)
    {
        /** @var $tag Tag */
        // Format:
        // <type> <desc>
        // <type>
        // <type1>|<type2>
        $type = 'void';
        $desc = '';
        if (!empty($tag)) {
            $content = $tag->getContent();
            if (preg_match('/(.+?)\s+(.*)/', $content, $mat)) {
                $type = $mat[1];
                $desc = $mat[2];
            } elseif (preg_match('/^([a-z\\|\\\\]+)$/', $content, $mat)) {
                $type = $mat[1];
            } else {
                $desc = $content;
            }
        }
        $type = self::normalizeParameterType($type);
        return [
            'type' => $type,
            'desc' => $desc,
        ];
    }

    public static function parseField($tag)
    {
        /** @var $tag Tag */
        // Format:
        // @bodyParam <name> <type> <"required" (optional)> <desc>
        // Examples:
        // @bodyParam text string required The text.
        // @bodyParam user_id integer The ID of the user.
        preg_match('/(.+?)\s+(.+?)\s+(required\s+)?(.*)/', $tag->getContent(), $content);
        $content = preg_replace('/\s?No-example.?/', '', $content);
        if (empty($content)) {
            // this means only name and type were supplied
            $pcs = preg_split('/\s+/', $tag->getContent());
            $name = $pcs[0];
            $type = 'any';
            if (isset($pcs[1])) {
                $type = $pcs[1];
            }
            $required = false;
            $desc = '';
        } else {
            list($_, $name, $type, $required, $desc) = $content;
            $desc = trim($desc);
            if ($desc == 'required' && empty(trim($required))) {
                $required = $desc;
                $desc = '';
            }
            $required = trim($required) == 'required' ? true : false;
        }

        $type = self::normalizeParameterType($type);
        list($desc, $example) = self::parseParamDescription($desc, $type);
        $value = is_null($example) && !self::shouldExcludeExample($tag->getContent())
            ? self::generateDummyValue($type)
            : $example;
        return [
            'name' => $name,
            'type' => $type,
            'desc' => $desc,
            'required' => $required,
            'value' => $value,
        ];
    }

    private static function normalizeParameterType($type)
    {
        $typeMap = [
            'int' => 'integer',
            'bool' => 'boolean',
            'double' => 'float',
        ];
        return $type ? (isset($typeMap[$type]) ? $typeMap[$type] : $type) : 'string';
    }

    private static function parseParamDescription($description, $type)
    {
        $example = null;
        if (preg_match('/(.*)\bExample:\s*(.+)\s*/', $description, $content)) {
            $description = trim($content[1]);
            // examples are parsed as strings by default, we need to cast them properly
            $example = self::castToType($content[2], $type);
        }
        return [$description, $example];
    }

    private static function castToType($value, $type)
    {
        $casts = [
            'integer' => 'intval',
            'int' => 'intval',
            'float' => 'floatval',
            'number' => 'floatval',
            'double' => 'floatval',
            'boolean' => 'boolval',
            'bool' => 'boolval',
        ];
        // First, we handle booleans. We can't use a regular cast,
        //because PHP considers string 'false' as true.
        if ($value == 'false' && ($type == 'boolean' || $type == 'bool')) {
            return false;
        }
        if (isset($casts[$type])) {
            return $casts[$type]($value);
        }
        return $value;
    }

    private static function shouldExcludeExample($desc)
    {
        return strpos($desc, ' No-example') !== false;
    }

    private static function generateDummyValue($type)
    {
        $fakeFactories = [
            'integer' => function () {
                return random_int(1, 20);
            },
            'number' => function () {
                return (random_int(100, 200) / 10);
            },
            'float' => function () {
                return (random_int(100, 200) / 10);
            },
            'boolean' => function () {
                return random_int(1, 10) > 5;
            },
            'string' => function () {
                return RandomUtil::string(10);
            },
            'array' => function () {
                return [];
            },
            'object' => function () {
                return new stdClass();
            },
        ];
        $fakeFactory = isset($fakeFactories[$type]) ? $fakeFactories[$type] : $fakeFactories['string'];
        return $fakeFactory();
    }
}
