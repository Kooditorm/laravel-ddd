<?php

namespace DDDCore\Supports;

class Tree
{

    public static function parse(array $data, int $parent_id = 0, string $parent_key = 'parent_id', string $child_key = 'id'): array
    {
        $rtn = [];
        $res = self::findChildren($data, $parent_id, $parent_key);

        if (!empty($res['children'])) {
            foreach ($res['children'] as $child) {
                $children = self::parse($res['remain'], $child[$child_key], $parent_key, $child_key);
                if (!empty($children)) {
                    $child['children'] = $children;
                }

                $rtn[] = $child;
            }
        }
        return $rtn;
    }


    public static function findChildren(array $data, int $parent_id, $parent_key = 'parent_id'): array
    {
        $result = ['children' => [], 'remain' => []];

        foreach ($data as $datum) {
            if (isset($datum[$parent_key]) && $datum[$parent_key] === $parent_id) {
                $result['children'][] = $datum;
            } else {
                $result['remain'][] = $datum;
            }
        }

        return $result;
    }


    public static function hasChildren(array $data, int $parent_id, string $parent_key = 'parent_id'): bool
    {
        return count(self::findChildren($data, $parent_id, $parent_key)['children']) > 0;
    }

    public static function getPaths(array $item, array $data, string $parent_key = 'parent_id', string $child_key = 'id'): array
    {
        $rtn[] = $item;
        $parent = self::findItem($item[$parent_key], $data, $child_key);
        if (!empty($parent)) {
            $list = self::getPaths($parent, $data, $parent_key, $child_key);
            foreach ($list as $value) {
                $rtn[] = $value;
            }
        }

        return $rtn;
    }

    public static function findItem(int $parent_id, array $data, string $child_key = 'id'): array
    {
        $rtn = [];
        foreach ($data as $datum) {
            if (isset($datum[$child_key]) && $datum[$child_key] === $parent_id) {
                $rtn = $datum;
                break;
            }
        }
        return $rtn;
    }

}
