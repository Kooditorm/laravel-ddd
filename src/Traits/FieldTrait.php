<?php

namespace DDDCore\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @trait FieldTrait
 * @package DDDCore\Traits
 */
trait FieldTrait
{

    /**
     * Get the Field attributes.
     *
     * @return string
     */
    protected function getFields(): string
    {
        return "[".PHP_EOL."\t\t".$this->bindStartPlaceholder.PHP_EOL.$this->getFieldComment().PHP_EOL."\t\t".$this->bindEndPlaceholder.PHP_EOL."\t]";
    }

    /**
     * Get the filling attributes.
     *
     * @return string
     */
    protected function getFilling(): string
    {
        $filling = '['.PHP_EOL;
        $filling .= "\t\t".$this->bindStartPlaceholder.PHP_EOL;

        foreach ($this->getSchemaParser() as $value) {
            $filling .= "\t\t'{$value}',".PHP_EOL;
        }

        $filling .= "\t\t".$this->bindEndPlaceholder.PHP_EOL;

        return $filling."\t".']';
    }

    /**
     * Get field comment
     *
     * @return string
     */
    private function getFieldComment(): string
    {
        $filter    = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        $length    = 0;
        $fieldAll  = $this->getFieldAll($filter);
        $fieldType = $this->getFieldTypeMapping();
        collect($fieldAll)->map(function ($item) use (&$length) {
            if (mb_strlen($item->Field) > $length) {
                $length = mb_strlen($item->Field);
            }
        });

        $filedComment = collect($fieldAll)->transform(function ($item) use ($fieldType, $length) {
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
        })->filter()->toArray();

        return implode(','.PHP_EOL, $filedComment);
    }

    /**
     * Get schema parser.
     *
     * @return array
     */
    private function getSchemaParser(): array
    {
        $filter = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $fields = $this->getFieldAll($filter);
        return array_map(static function ($item) {
            return (string)($item->Field);
        }, $fields);
    }

    /**
     * 字段类型映射
     *
     * @return array
     */
    private function getFieldTypeMapping(): array
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

    /**
     * 获取表字段
     *
     * @param  array  $field
     * @return array
     */
    private function getFieldAll(array $field = []): array
    {
        $fields = [];
        if (method_exists($this, 'getName')) {
            $fieldAll = DB::select('show full columns from '.Str::plural(Str::snake($this->getName())));
            $fields   = array_filter($fieldAll, static function ($item) use ($field) {
                return !in_array($item->Field, $field, true);
            });
        }

        return $fields;

    }

    /**
     * get string Between
     *
     * @param $kw
     * @param ...$mark
     * @return string
     */
    protected function getStrBetween($kw, ...$mark): string
    {
        if (in_array(count($mark), [1, 2], true)) {
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
        return $kw;
    }


}
