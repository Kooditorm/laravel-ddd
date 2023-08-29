<?php

namespace App\Infrastructure\Console\Makers\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait FieldTrait
{

    public function getFieldType(): array
    {
        return [
            'integer' => ['INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INTEGER', 'BIGINT'],
            'float'   => ['FLOAT', 'DOUBLE', 'DECIMAL'],
            'string'  => [
                'CHAR',
                'VARCHAR',
                'TINYBLOB',
                'TINYTEXT',
                'BLOB',
                'TEXT',
                'MEDIUMBLOB',
                'MEDIUMTEXT',
                'LONGBLOB',
                'LONGTEXT',
                'BINARY',
                'VARBINARY'
            ],
            'date'    => ['DATE', 'TIME', 'YEAR', 'DATETIME', 'TIMESTAMP'],
            'json'    => ['JSON']
        ];
    }

    public function getRules(): string
    {
        return "[".PHP_EOL."\t\t".$this->bindStartPlaceholder.PHP_EOL.$this->getfields().PHP_EOL."\t\t".$this->bindEndPlaceholder.PHP_EOL."\t]";
    }

    public function getFields(): string
    {
        $filter = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        $length   = 0;
        $fieldAll = DB::select('show full columns from '.Str::plural(Str::snake($this->getName())));
//        $fieldAll = array_filter($fieldAll, function ($item) use ($filter) {
//            return !in_array($item->Field, $filter, true);
//        });

        array_map(static function ($item) use (&$length) {
            if (mb_strlen($item->Field) > $length) {
                $length = mb_strlen($item->Field);
            }
        },$fieldAll);

        $fieldType = $this->getFieldType();

        return implode(','.PHP_EOL, array_filter(array_map(static function ($item) use ($fieldType, $length) {
            $type = preg_replace('#\(.+\)#', '', strtoupper($item->Type));
            $type = str_replace(array('UNSIGNED', 'ZEROFILL'), '', $type);
            $type = trim($type);

            foreach ($fieldType as $_type => $types) {
                if (in_array($type, $types, true)) {
                    $type = $_type;
                    break;
                }
            }

            if (empty($item->Comment)) {
                return '';
            }

            $item->Comment = head(explode(' ', $item->Comment));
            return "\t\t'{$item->Field}'".str_pad('', $length - mb_strlen($item->Field),
                    " ")." => ['type' => '{$type}', 'comment' => '{$item->Comment}']";
        }, array_filter($fieldAll, static function ($item) use ($filter) {
            return !in_array($item->Field, $filter, true);
        }))));
    }

    /**
     * Get the filling attributes.
     *
     * @return string
     */
    public function getFilling(): string
    {
        $results = '['.PHP_EOL;
        $results .= "\t\t".$this->bindStartPlaceholder.PHP_EOL;

        foreach (explode(',', $this->getSchemaParser()) as $value) {
            $results .= "\t\t'{$value}',".PHP_EOL;
        }

        $results .= "\t\t".$this->bindEndPlaceholder.PHP_EOL;

        return $results."\t".']';
    }

    /**
     * Get schema parser.
     *
     * @return string
     */
    public function getSchemaParser(): string
    {
        $filter = ['id', 'created_at', 'updated_at', 'deleted_at'];
        return implode(',', array_map(function ($item) {
            return (string)($item->Field);
        }, array_filter(DB::select('show full columns from '.Str::plural(Str::snake($this->getName()))), function ($item) use ($filter) {
            return !in_array($item->Field, $filter, true);
        })));
    }

    /**
     * @param $kw
     * @param  mixed  ...$mark
     * @return string
     */
    public function getStrBetween($kw, ...$mark): string
    {
//        $mark = array_shift($mark) ?: [];
        if (in_array(count($mark), [1, 2])) {
            $st = stripos($kw, $mark[0]);
            $mk = strlen($mark[0]);
            if (count($mark) === 1) {
                $ed = stripos($kw, $mark[0], $st + $mk);
            } else {
                $mk = strlen($mark[1]);
                $ed = stripos($kw, $mark[1]);
            }

            if ($st !== false && $ed !== false && $st < $ed) {
                return substr($kw, ($st), ($ed + $mk - $st));
            }
        }

        return '';
    }
}
