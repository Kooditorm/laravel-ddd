<?php

namespace App\Infrastructure\Supports\TraceLog;

use JsonException;
use Monolog\Formatter\NormalizerFormatter;

/**
 * @class TraceLogFormatter
 * @package App\Infrastructure\Supports\TraceLogs
 */
class TraceLogFormatter extends NormalizerFormatter
{
    public const SIMPLE_FORMAT = "%datetime% %level_name% [%micro%,%traceId%,%spanId%,false] %process% --- [%threadId%] %className%: %message% %context% %extra%\n";
    public const SIMPLE_DATE   = 'Y-m-d H:i:s.u';

    protected ?string $format;
    protected bool $allowInlineLineBreaks;
    protected bool $ignoreEmptyContextAndExtra;
    protected bool $includeStackTraces;
    protected array $recode;
    protected array $skipClassesPartials = ['Monolog\\', 'Illuminate\\'];


    /**
     * @param  string|null  $format
     * @param  string|null  $dateFormat
     * @param  bool  $allowInlineLineBreaks
     * @param  bool  $ignoreEmptyContextAndExtra
     * @param  array  $recode
     */
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false,
        array $recode = []
    ) {
        $this->format                     = $format ?: static::SIMPLE_FORMAT;
        $this->allowInlineLineBreaks      = $allowInlineLineBreaks;
        $this->ignoreEmptyContextAndExtra = $ignoreEmptyContextAndExtra;
        $this->recode                     = $recode;
        parent::__construct($dateFormat);
    }

    public function includeStackTraces($include = true): void
    {
        $this->includeStackTraces = $include;
        if ($this->includeStackTraces) {
            $this->allowInlineLineBreaks = true;
        }
    }

    public function allowInlineLineBreaks($allow = true): void
    {
        $this->allowInlineLineBreaks = $allow;
    }

    public function ignoreEmptyContextAndExtra($ignore = true): void
    {
        $this->ignoreEmptyContextAndExtra = $ignore;
    }

    public function format(array $record)
    {
        $record['className'] = $this->getTransferClassName();
        $record['micro']     = env('MIC_SERVICE_NAME', env('APP_NAME'));

        if (!empty($this->recode)) {
            $record = array_merge($record, $this->recode);
        }

        $this->datetimeFormat($record);

        $vars   = parent::format($record);
        $output = $this->format;

        foreach ($vars['extra'] as $var => $val) {
            if (str_contains($output, '%context.'.$var.'%')) {
                $output = str_replace('%context.'.$var.'%', $this->stringify($val), $output);
                unset($vars['context'][$var]);
            }
        }

        foreach ($vars['context'] as $var => $val) {
            if (str_contains($output, '%context.'.$var.'%')) {
                $output = str_replace('%context.'.$var.'%', $this->stringify($val), $output);
                unset($vars['context'][$var]);
            }
        }

        if ($this->ignoreEmptyContextAndExtra) {
            if (empty($vars['context'])) {
                unset($vars['context']);
                $output = str_replace('%context%', '', $output);
            }

            if (empty($vars['extra'])) {
                unset($vars['extra']);
                $output = str_replace('%extra%', '', $output);
            }
        }

        foreach ($vars as $var => $val) {
            if (str_contains($output, '%'.$var.'%')) {
                $output = str_replace('%'.$var.'%', $this->stringify($val), $output);
            }
        }

        // remove leftover %extra.xxx% and %context.xxx% if any
        if (str_contains($output, '%')) {
            $output = preg_replace('/%(?:extra|context)\..+?%/', '', $output);
        }

        return $output;
    }

    public function stringify($value)
    {
        return $this->replaceNewlines($this->convertToString($value));
    }


    /**
     * 处理请求时间
     * @param  array  $recode
     */
    protected function datetimeFormat(array &$recode): void
    {
        foreach ($recode as $key => $item) {
            if ($item instanceof \DateTime) {
                [$dateFormat, $timeFormat] = explode('.', self::SIMPLE_DATE);
                $sec          = $item->format($timeFormat);
                $sec          = bcdiv($sec, 1000);
                $recode[$key] = $item->format($dateFormat).'.'.$sec;
            }
        }
    }

    /**
     * 获取调用类
     * @return string
     */
    protected function getTransferClassName(): string
    {
        $className = '';
        $trace     = debug_backtrace();
        array_shift($trace);
        array_shift($trace);
        $i = 0;

        while (isset($trace[$i]['class'])) {
            foreach ($this->skipClassesPartials as $partial) {
                if (str_contains($trace[$i]['class'], $partial)) {
                    $i++;
                    continue 2;
                }
            }
            break;
        }

        if (isset($trace[$i]['class'])) {
            $className = $trace[$i]['class'];
        }

        return $className;
    }


    protected function convertToString($data)
    {
        if (null === $data || is_bool($data)) {
            return var_export($data, true);
        }
        if (is_scalar($data)) {
            return (string)$data;
        }
        if (PHP_VERSION_ID >= 50400) {
            return $this->toJson($data, true);
        }
        return str_replace('\\/', '/', @json_encode($data, JSON_THROW_ON_ERROR));
    }


    protected function replaceNewlines($str)
    {
        if ($this->allowInlineLineBreaks) {
            if (str_starts_with($str, '{')) {
                return str_replace(array('\r', '\n'), array("\r", "\n"), $str);
            }

            return $str;
        }

        return str_replace(array("\r\n", "\r", "\n"), ' ', $str);
    }
}
